<?php
/*
 * Class to handle updating cached data in the background.
 */

/**
 * Check WordPress is running.
 */
if ( ! defined( 'ABSPATH' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/**
 * Kebo_View
 */
if ( ! class_exists( 'Kebo_Caching' ) ) {
    
    class Kebo_Caching {

        /**
         * Name of the cache to be refreshed
         * Must be unique
         *
         * @var string
         */
        public static $cache;

        /**
         * The cache lock ID.
         *
         * @var string
         */
        public static $lock;

        /**
         * Refers to a single instance of this class.
         *
         * @var string
         */
        private static $instance = null;

        /**
         * Creates or returns an instance of this class.
         */
        public static function get_instance() {

            if ( null == self::$instance ) {
                
                self::$instance = new self;
                
            }

            return self::$instance;

        } // end get_instance
        
        /**
         * Init
         */
        public function __construct() {
            
            // Watch for incomming cache refresh requests.
            add_action( 'init', array( $this, 'watcher' ) );
            
        } // end __construct
        
        /**
         * Check for Kebo Caching requests
         */
        public static function watcher() {
            
            /**
             * Validate for Lowercase Alphanumeric characters e.g. a-z,0-9,_,-.
             */
            self::$cache = sanitize_key( $_POST['_kebo_cache'], false );
            self::$lock = sanitize_key( $_POST['_kebo_lock'], false );
            
            /**
             * Allow plugins/themes to hook into this and perform their own cache updates.
             */
            do_action( 'kebo_caching_capture_request', self::$cache, self::$lock );
            
            /*
             * Test Refresh Script
             */
            if ( isset( $_POST['_kebo_cache'] ) ) {
                
                kbso_social_sharing_update_counts( self::$lock );
                
                // Incase functions using the hook forget to exit.
                exit();
            
            }
            
        } // end watcher
        
        /**
         * Set the name of the Cache
         */
        public function set_cache( $cache ) {
            
            // Ensure it is Alphanumeric
            self::$cache = sanitize_key( $cache );
            
        }
        
        /**
         * Get the name of the Cache
         */
        public function get_cache() {
            
            // Return cache or false if not set
            return ( ! empty( self::$cache ) ) ? self::$cache : false ;
            
        }
        
        /**
         * Set the name of the Lock
         */
        public function set_lock( $lock ) {
            
            // Ensure it is Alphanumeric
            self::$lock = sanitize_key( $lock );
            
        }
        
        /**
         * Get the name of the Lock
         */
        public function get_lock() {
            
            // Return lock or false if not set
            return ( ! empty( self::$lock ) ) ? self::$lock : false ;
            
        }
        
        /**
         * Make another request to process our caching update.
         */
        public function spawn_process() {
            
            $server_url = home_url( '/?kebo_caching_request' );
            
            $args = array( 
                'body' => array( 
                    '_kebo_cache' => self::$cache,
                    '_kebo_lock' => self::$lock
                ),
                'timeout' => 0.01,
                'blocking' => false,
                'sslverify' => apply_filters( 'https_local_ssl_verify', true )
            );
            
            wp_remote_post( $server_url, $args );
            
        } // end spawn_process
        
    }
    
}