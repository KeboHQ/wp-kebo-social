<?php
/*
 * Class to handle processing data in the background.
 * 
 * TODO: Expand to handle any data and batches like WP Cron.
 */

/**
 * Check WordPress is running.
 */
if ( ! defined( 'ABSPATH' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/**
 * Kebo_Job
 *
 * Processes functions in a different request, to not impact user performance.
 *
 */
if ( ! class_exists( 'Kebo_Job' ) ) {
    
    class Kebo_Job {

        /**
         * Singleton Instance
         *
         * @var Kebo_Job The one true Kebo_Job
         */
        private static $instance;

        /**
         * Function Name
         *
         * @var string
         */
        public static $name;

        /**
         * Args for Function
         *
         * @var array
         */
        public static $args = array();

        /**
         * Get the Job Name
         *
         * @return string
         */
        public function get_name() {

            return self::$name;

        }

        /**
         * Set the Job Name
         *
         * @param string $name
         */
        public function set_name( $name ) {

            self::$name = $name;

        }

        /**
         * Get the Function Arguments
         *
         * @return array
         */
        public function get_args() {

            return self::$args;

        }

        /**
         * Set the Function Arguments
         *
         * @param $args
         */
        public function set_args( $args ) {

            self::$args = (array) $args;

        }
        
        /**
         * Make Compatibility Request
         */
        public function spawn_compat_test() {
            
            update_option( 'kebo_job_compat', 'false' );

            $server_url = home_url( '/?kebo_job_compat_test' );
            
            $args = array(
                'body' => array(
                    '_kebo_job_compat_test' => true,
                ),
                'timeout' => 0.01,
                'blocking' => false,
                'sslverify' => apply_filters( 'https_local_ssl_verify', true )
            );
            
            wp_remote_post( $server_url, $args );

        } // end spawn_compat_test
        
        /**
         * Check Compatibility
         */
        public function check_compat() {
            
            if ( isset( $_POST['_kebo_job_compat_test'] ) && true == $_POST['_kebo_job_compat_test'] ) {
                
                update_option( 'kebo_job_compat', true );
                
            }

        } // end check_compat
        
        /**
         * Get Compatibility
         */
        public function get_compat() {
                
            $compatibility = get_option( 'kebo_job_compat' );
            
            return apply_filters( 'kebo_job_compat', $compatibility );

        } // end get_compat
        
        /**
         * Init
         */
        public function __construct() {
            
            // Watch for incoming cache refresh requests.
            add_action( 'init', array( $this, 'watcher' ) );
            
            // Watch for incomming compatibility check requests.
            add_action( 'init', array( $this, 'check_compat' ) );
            
        } // end __construct

        /**
         * Single Kebo_Job Instance
         *
         * Ensures that only one instance of Kebo_Job exists in memory at any one time.
         * @return object Kebo_Job
         */
        public static function instance() {

            if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Kebo_Job ) ) {

                self::$instance = new Kebo_Job;

            }

            return self::$instance;

        } // end instance
        
        /**
         * Check for Kebo Caching requests
         */
        public function watcher() {

            if ( ! isset( $_GET['kebo_job_request'] ) ) {
                return;
            }

            /**
             * Validate for Lowercase Alphanumeric characters e.g. a-z,0-9,_,-.
             */
            self::$name = sanitize_key( $_POST['kebo_job_name'], false );
            self::$args = $_POST['kebo_job_args'];
            
            /**
             * Allow plugins/themes to hook into this and perform their own Job updates.
             * Use this to check for your own jobs and processing accordingly.
             */
            do_action( 'kebo_job_capture_request', self::$name, self::$args );

                
            // Incase functions using the hook forget to exit.
            exit();
            
        } // end watcher
        
        /**
         * Make another request to process our caching update.
         */
        public function spawn_process() {

            if ( false === $this->check_lock() ) {
                return;
            }

            $server_url = home_url( '/?kebo_job_request' );
            
            $args = array( 
                'body' => array(
                    'kebo_job_name' => self::$name,
                    'kebo_job_args' => self::$args,
                ),
                'timeout' => 0.01,
                'blocking' => false,
                'sslverify' => apply_filters( 'https_ssl_verify', true )
            );
            
            wp_remote_post( $server_url, $args );
            
        } // end spawn_process

        /**
         *
         *
         * @return bool|mixed $lock
         */
        private function get_lock() {

            $lock = false;

            // Skip local cache and force refetch of kebo_doing_job transient in case
            // another processs updated the cache
            if ( wp_using_ext_object_cache() ) {

                $lock = wp_cache_get( 'kebo_doing_job', 'transient', true );

            } else {

                global $wpdb;

                $timeout = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1",
                        '_transient_timeout_kebo_doing_job'
                    )
                );

                if ( time() < $timeout->option_value ) {

                    $value = $wpdb->get_row(
                        $wpdb->prepare(
                            "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1",
                            '_transient_kebo_doing_job'
                        )
                    );

                    if ( is_object( $value ) ) {
                        $lock = $value->option_value;
                    }

                } else {

                    $lock = false;

                }

            }

            return $lock;

        }

        private function check_lock() {

            /*
             * Check if we are already updating.
             */
            if ( $this->get_lock() ) {
                return false;
            }

            /*
             * Create hash of the current time (nothing else should occupy the same microtime).
             */
            $hash = hash( 'sha1', microtime() );

            /*
             * Set transient to show we are updating and set the hash for this specific thread.
             */
            set_transient( 'kebo_doing_job', $hash, 5 );

            /*
             * Sleep so that other threads at the same point can set the hash
             */
            usleep( 200000 ); // Sleep for 1/5th of a second

            /*
             * Only one thread will have the same hash as is stored in the transient now, all others can die.
             */
            if ( $this->get_lock() && $this->get_lock() != $hash ) {
                return false;
            }

            return true;

        }
        
    }
    
}