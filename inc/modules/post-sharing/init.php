<?php
/* 
 * Init for the Social Sharing module.
 */

if ( ! defined( 'KBSO_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

define( 'KBSO_POST_SHARING_URL', plugin_dir_url(__FILE__) );
define( 'KBSO_POST_SHARING_PATH', plugin_dir_path(__FILE__) );

if ( ! defined( KBSO_POST_SHARING_UPDATE_COUNTS ) ) {
    define( 'KBSO_POST_SHARING_UPDATE_COUNTS', 'true' );
}

/**
 * Ensure the Kebo Caching class is running to detect requests
 * Uses Singleton Pattern to ensure only one instance is used.
 */
Kebo_Caching::get_instance();

/**
 * If the Share Links feature has been activated it, hook the feature in.
 */
$options = kbso_get_plugin_options();

/**
 * Activate the Feature on relevant content
 */
function kbso_post_sharing_activate() {
    
    global $post;

    $options = kbso_get_plugin_options();
    
    /*
     * Check we are in the right environment to be adding the post sharing links.
     * Feature On? Main Query? Single Post? Selected Post Type?
     */
    if ( 'yes' == $options['feature_control_post_sharing'] && is_main_query() && is_singular() && in_array( $post->post_type, $options['post_sharing_post_types'] ) && empty( $post->post_password ) ) {
    
        add_filter( 'the_content', 'kbso_post_sharing_content_insert', 95 );
        add_action( 'wp_enqueue_scripts', 'kbso_post_sharing_enqueue_frontend' );
    
    }
    
}
add_action( 'wp', 'kbso_post_sharing_activate' );

/*
 * Only include on Admin
 */
if ( is_admin() ) {
    
    /*
     * Include Uninstall file.
     */
    require_once( KBSO_POST_SHARING_PATH . 'dashboard-widgets.php' );
    
    /*
     * Include Sharing Menu file.
     */
    require_once( KBSO_POST_SHARING_PATH . 'menu.php' );
    
}

/*
 * Only include on Ajax
 */
if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
    
    /*
     * Include AJAX file.
     */
    require_once( KBSO_POST_SHARING_PATH . 'ajax.php' );
    
}

/*
 * Include Options file.
 */
require_once( KBSO_POST_SHARING_PATH . 'options.php' );

/*
 * Include General file.
 */
require_once( KBSO_POST_SHARING_PATH . 'social-sharing.php' );

/*
 * Include Social Count Update file.
 */
require_once( KBSO_POST_SHARING_PATH . 'update-counts.php' );

/*
 * Include Helper Functions file.
 */
require_once( KBSO_POST_SHARING_PATH . 'helpers.php' );

/*
 * Include Uninstall file.
 */
require_once( KBSO_POST_SHARING_PATH . 'uninstall.php' );