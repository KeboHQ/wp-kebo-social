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
        private $cache;

        /**
         * The cache lock ID.
         *
         * @var string
         */
        private $lock;
        
        /**
         * Init
         */
        public function __construct() {
            
            add_action( 'init', array( $this, 'init' ), 9999 );
            
        }
        
        /**
         * Check for Kebo Caching requests
         */
        public function init() {
            
            if ( isset( $_POST['_kebo_cache'] ) && isset( $_POST['_kebo_lock'] )) {
                
                /**
                 * Validate for Lowercase Alphanumeric characters e.g. a-z,0-9,_,-.
                 */
                $this->cache = sanitize_key( $_POST['_kebo_cache'], false );
                $this->lock = sanitize_key( $_POST['_kebo_lock'], false );
                
                /**
                 * Allow plugins/themes to hook into this and perform their own cache updates.
                 */
                do_action( 'kebo_caching_capture_request', $this->cache, $this->lock );
                
                // Incase functions using the hook forget to exit.
                exit();
            
            }
            
        }
        
        /**
         * Set the name of the Cache
         */
        public function set_cache( $cache ) {
            
            // Ensure it is Alphanumeric
            $this->cache = sanitize_key( $cache );
            
        }
        
        /**
         * Get the name of the Cache
         */
        public function get_cache() {
            
            // Return cache or false if not set
            return ( ! empty( $this->cache ) ) ? $this->cache : false ;
            
        }
        
        /**
         * Set the name of the Lock
         */
        public function set_lock( $lock ) {
            
            // Ensure it is Alphanumeric
            $this->lock = sanitize_key( $lock );
            
        }
        
        /**
         * Get the name of the Lock
         */
        public function get_lock() {
            
            // Return lock or false if not set
            return ( ! empty( $this->lock ) ) ? $this->lock : false ;
            
        }
        
        /**
         * Make another request to process our caching update.
         */
        public function spawn_process() {
            
            $server_url = home_url( '/?kebo_caching_request' );
            
            $args = array( 
                'body' => array( 
                    '_kebo_cache' => $this->cache,
                    '_kebo_lock' => $this->lock
                ),
                'timeout' => 0.01,
                'blocking' => false,
                'sslverify' => apply_filters( 'https_local_ssl_verify', true )
            );
            
            wp_remote_post( $server_url, $args );
            
        }
        
    }
    
}