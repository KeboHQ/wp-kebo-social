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
    
    if ( 'yes' == $options['feature_control_post_sharing'] && is_singular() && in_array( $post->post_type, $options['post_sharing_post_types'] ) ) {
    
        add_filter( 'the_content', 'kbso_post_sharing_content_insert', 95 );
        add_action( 'wp_enqueue_scripts', 'kbso_post_sharing_enqueue_frontend' );
    
    }
    
}
add_action( 'wp', 'kbso_post_sharing_activate' );

if ( is_admin() ) {
    
    /*
     * Include Uninstall file.
     */
    require_once( KBSO_PATH . 'inc/modules/post-sharing/dashboard-widgets.php' );
    
    /*
     * Include Sharing Menu file.
     */
    require_once( KBSO_PATH . 'inc/modules/post-sharing/menu.php' );
    
}

/*
 * Include Options file.
 */
require_once( KBSO_PATH . 'inc/modules/post-sharing/options.php' );

/*
 * Include General file.
 */
require_once( KBSO_PATH . 'inc/modules/post-sharing/social-sharing.php' );

/*
 * Include Social Count Update file.
 */
require_once( KBSO_PATH . 'inc/modules/post-sharing/update-counts.php' );

/*
 * Include AJAX file.
 */
require_once( KBSO_PATH . 'inc/modules/post-sharing/ajax.php' );

/*
 * Include Helper Functions file.
 */
require_once( KBSO_PATH . 'inc/modules/post-sharing/helpers.php' );

/*
 * Include Uninstall file.
 */
require_once( KBSO_PATH . 'inc/modules/post-sharing/uninstall.php' );