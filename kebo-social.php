<?php
/**
 * Plugin Name: Kebo Social
 * Plugin URI:  https://kebopowered.com/plugins/kebo-social/
 * Description: Social integration done right. The best WordPress plugin to integrate Social Services into your website.
 * Version:     0.4.6
 * Author:      Kebo
 * Author URI:  https://kebopowered.com/
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
define( 'KBSO_VERSION', '0.4.6' );
define( 'KBSO_URL', plugin_dir_url(__FILE__) );
define( 'KBSO_PATH', plugin_dir_path(__FILE__) );

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
    wp_register_style( 'kbso-admin', KBSO_URL . 'assets/css/admin.css', array(), KBSO_VERSION, 'all' );
    wp_register_style( 'kbso-admin-min', KBSO_URL . 'assets/css/admin.min.css', array(), KBSO_VERSION, 'all' );
        
    // Register Scripts
    wp_register_script( 'kbso-admin-js', KBSO_URL . 'assets/js/admin.js', array(), KBSO_VERSION, true );
    
    wp_register_script( 'kbso-feature-control', KBSO_URL . 'assets/js/feature-control.js', array( 'jquery' ), KBSO_VERSION, true );
    
    wp_register_script( 'jquery-ui-touchpunch', KBSO_URL . 'assets/js/vendor/jquery.ui.touch-punch.min.js', array( 'jquery-ui-sortable' ), KBSO_VERSION, false );
    
    wp_register_script( 'flot', KBSO_URL . 'assets/js/vendor/flot/jquery.flot.js', array( 'jquery' ), KBSO_VERSION, false );
    wp_register_script( 'flot-min', KBSO_URL . 'assets/js/vendor/flot/jquery.flot.min.js', array( 'jquery' ), KBSO_VERSION, false );
    
    wp_register_script( 'flot-resize', KBSO_URL . 'assets/js/vendor/flot/jquery.flot.resize.js', array( 'flot' ), KBSO_VERSION, false );
    wp_register_script( 'flot-resize-min', KBSO_URL . 'assets/js/vendor/flot/jquery.flot.resize.min.js', array( 'flot-min' ), KBSO_VERSION, false );
    
    wp_register_script( 'flot-pie', KBSO_URL . 'assets/js/vendor/flot/jquery.flot.pie.js', array( 'flot' ), KBSO_VERSION, false );
    wp_register_script( 'flot-pie-min', KBSO_URL . 'assets/js/vendor/flot/jquery.flot.pie.min.js', array( 'flot-min' ), KBSO_VERSION, false );
    
    wp_register_script( 'flot-canvas', KBSO_URL . 'assets/js/vendor/flot/jquery.flot.canvas.js', array( 'flot' ), KBSO_VERSION, false );
    wp_register_script( 'flot-canvas-min', KBSO_URL . 'assets/js/vendor/flot/jquery.flot.canvas.min.js', array( 'flot-min' ), KBSO_VERSION, false );
    
    wp_register_script( 'flot-time', KBSO_URL . 'assets/js/vendor/flot/jquery.flot.time.js', array( 'flot' ), KBSO_VERSION, false );
    wp_register_script( 'flot-time-min', KBSO_URL . 'assets/js/vendor/flot/jquery.flot.time.min.js', array( 'flot-min' ), KBSO_VERSION, false );
        
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
    
    // Enqueue files for core dashboard page
    if ( 'index.php' == $hook_suffix ) {
            
        /*
         * Use minified files where available, unless SCRIPT_DEBUG is true
         */
        if ( false == SCRIPT_DEBUG ) {
            
            wp_enqueue_style( 'kbso-admin-min' );
            
        } else {
            
            wp_enqueue_style( 'kbso-admin' );
            
        }
            
    }
    
    // Enqueue files for Kebo Social pages
    if ( 'settings_page_kebo-social' == $hook_suffix ) {
            
        /*
         * Use minified files where available, unless SCRIPT_DEBUG is true
         */
        if ( false == SCRIPT_DEBUG ) {
            
            wp_enqueue_style( 'kbso-admin-min' );
            
        } else {
            
            wp_enqueue_style( 'kbso-admin' );
            
        }
        
        wp_enqueue_script( 'kbso-feature-control' );
            
    }
        
}
add_action( 'admin_enqueue_scripts', 'kbso_enqueue_backend' );

/**
 * Add a link to the plugin screen, to allow users to jump straight to the settings page.
 */
$kbso_plugin_file = plugin_basename(__FILE__);

function kbso_plugin_settings_link( $links ) {
    
    $links[] = '<a href="' . admin_url( 'options-general.php?page=kebo-social' ) . '">' . __( 'Settings', 'kbso' ) . '</a>';
    
    return $links;
    
}
add_filter( 'plugin_action_links_'. $kbso_plugin_file, 'kbso_plugin_settings_link' );