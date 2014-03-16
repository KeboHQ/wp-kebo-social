<?php
/* 
 * Init for the Social Sharing module.
 */

if ( ! defined( 'KBSO_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

define( 'KBSO_UPDATE_COUNTS', 'true' );

/**
 * If the Share Links feature has been activated it, hook the feature in.
 */
$options = kbso_get_plugin_options();

if ( 'yes' == $options['feature_control_social_sharing'] ) {
    
    add_filter( 'the_content', 'kbso_add_social_sharing_buttons', 95 );
    
}

/*
 * Include General file.
 */
require_once( KBSO_PATH . 'inc/modules/social-sharing/social-sharing.php' );

/*
 * Include Sharing Menu file.
 */
require_once( KBSO_PATH . 'inc/modules/social-sharing/menu.php' );

/*
 * Include Options file.
 */
require_once( KBSO_PATH . 'inc/modules/social-sharing/options.php' );

/*
 * Include Links Function file.
 */
require_once( KBSO_PATH . 'inc/modules/social-sharing/links.php' );

/*
 * Include Social Count Update file.
 */
require_once( KBSO_PATH . 'inc/modules/social-sharing/update-counts.php' );

/*
 * Include AJAX file.
 */
require_once( KBSO_PATH . 'inc/modules/social-sharing/ajax.php' );

/*
 * Include Helper Functions file.
 */
require_once( KBSO_PATH . 'inc/modules/social-sharing/helpers.php' );

