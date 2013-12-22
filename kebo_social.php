<?php
/**
 * Plugin Name: Kebo Social
 * Plugin URI:  http://kebopowered.com/plugins/kebo-social/
 * Description: Social integration done right. The best WordPress plugin to integrate Social Services into your website.
 * Version:     0.1.0
 * Author:      Kebo
 * Author URI:  http://kebopowered.com/
 * License:     GPLv2+
 * Text Domain: kbso
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2013 Kebo (email : support@kebopowered.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// Block direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( 'Sorry, no direct access.' );
}

// Useful global constants
define( 'KBSO_VERSION', '0.1.0' );
define( 'KBSO_URL', plugin_dir_url(__FILE__) );
define( 'KBSO_PATH', plugin_dir_path(__FILE__) );
define( 'KBSO_API_URL', 'http://auth.kebopowered.com/request/request.php' );

/*
 * Load textdomain early, as we need it for the PHP version check.
 */
function kbso_load_textdomain() {
    
    load_plugin_textdomain( 'kbso', false, KBSO_PATH . '/languages' );
    
}
add_filter( 'wp_loaded', 'kbso_load_textdomain' );

/*
 * Check for the required version of PHP
 */
if ( version_compare( PHP_VERSION, '5.2', '<' ) ) {
    
    if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
        
        require_once ABSPATH . '/wp-admin/includes/plugin.php';
        deactivate_plugins(__FILE__);
        wp_die( __( 'Kebo Social requires PHP 5.2 or higher, as does WordPress 3.2 and higher.', 'kbso' ) );
        
    } else {
        
        return;
        
    }
    
}

/*
 * Load Relevant Internal Files
 */
function kbso_plugin_setup() {

    /*
     * Include Loader file.
     */
    require_once( KBSO_PATH . 'inc/loader.php' );
    
}
add_action( 'plugins_loaded', 'kbso_plugin_setup', 15 );

/**
 * Register plugin scripts and styles.
 */
function kbso_register_files() {

    // Register Styles
    wp_register_style( 'kbso-admin-css', KBSO_URL . 'assets/css/admin.css', array(), KBSO_VERSION, 'all' );
    wp_register_style( 'kbso-widgets-css', KBSO_URL . 'assets/css/widgets.css', array(), KBSO_VERSION, 'all' );
    wp_register_style( 'kbso-sharelinks-css', KBSO_URL . 'assets/css/sharelinks.css', array(), KBSO_VERSION, 'all' );
        
    // Register Scripts
    wp_register_script( 'kbso-admin-js', KBSO_URL . 'assets/js/admin.js', array(), KBSO_VERSION, true );
    wp_register_script( 'responsive-slides', KBSO_URL . 'assets/js/vendor/responsiveslides.min.js', array( 'jquery' ), KBSO_VERSION, false );
        
}
add_action( 'wp_enqueue_scripts', 'kbso_register_files' );
add_action( 'admin_enqueue_scripts', 'kbso_register_files' );
    
/**
 * Enqueue frontend plugin scripts and styles.
 */
function kbso_enqueue_frontend() {

    
        
}
add_action( 'wp_enqueue_scripts', 'kbso_enqueue_frontend' );
    
/**
 * Enqueue backend plugin scripts and styles.
 */
function kbso_enqueue_backend( $hook_suffix ) {
        
    // Enqueue on all pages
    wp_enqueue_style( 'kbso-admin-css' );
        
    // Enqueue files for sharing page
    if ( 'kebo-social_page_kbso-sharing' == $hook_suffix ) {
            
        wp_enqueue_script( 'jquery-ui-sortable' );
            
    }
        
}
add_action( 'admin_enqueue_scripts', 'kbso_enqueue_backend' );

/**
 * Add a link to the plugin screen, to allow users to jump straight to the settings page.
 */
function kbso_plugin_settings_link( $links ) {
    
    $links[] = '<a href="' . admin_url( 'options-general.php?page=kebo-twitter' ) . '">' . __( 'Settings', 'kbso' ) . '</a>';
    
    return $links;
    
}
add_filter( 'plugin_action_links_kebo-social/kebo-social.php', 'kbso_plugin_settings_link' ); 