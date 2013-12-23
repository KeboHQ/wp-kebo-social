<?php
/* 
 * Init for the Social Sharing module.
 */

if ( ! defined( 'KBSO_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/*
 * Include Sharing Menu file.
 */
require_once( KBSO_PATH . 'inc/modules/social-sharing/menu.php' );

/**
 * If the Share Links feature has been activated it, hook the feature in.
 */
$options = kbso_get_plugin_options();

if ( 'yes' == $options['share_links_activate_feature'] ) {
    
    add_filter( 'the_content', 'kbso_add_share_links', 95 );
    
}

