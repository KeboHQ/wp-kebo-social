<?php
/**
 * Plugin Name: Kebo Social
 * Plugin URI:  https://kebopowered.com/plugins/kebo-social/
 * Description: Social integration done right. The best WordPress plugin to integrate Social Services into your website.
 * Version:     0.3.5
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
define( 'KBSO_VERSION', '0.3.0' );
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
    wp_register_style( 'kbso-admin', KBSO_URL . 'assets/css/admin.css', array(), KBSO_VERSION, 'all' );
    wp_register_style( 'kbso-admin-min', KBSO_URL . 'assets/css/admin.min.css', array(), KBSO_VERSION, 'all' );
    //wp_register_style( 'kbso-widgets', KBSO_URL . 'assets/css/widgets.css', array(), KBSO_VERSION, 'all' );
    //wp_register_style( 'kbso-widgets-min', KBSO_URL . 'assets/css/widgets.min.css', array(), KBSO_VERSION, 'all' );
        
    // Register Scripts
    wp_register_script( 'kbso-admin-js', KBSO_URL . 'assets/js/admin.js', array(), KBSO_VERSION, true );
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
            
        wp_enqueue_style( 'kbso-admin' );
            
    }
    
    // Enqueue files for dashboard page
    if ( 'toplevel_page_kbso-dashboard' == $hook_suffix ) {
            
        wp_enqueue_style( 'kbso-admin' );
            
    }
        
    // Enqueue files for settings page
    if ( 'kebo-social_page_kbso-settings' == $hook_suffix ) {
        
        wp_enqueue_style( 'kbso-admin' );
            
    }
        
}
add_action( 'admin_enqueue_scripts', 'kbso_enqueue_backend' );

/**
 * Add a link to the plugin screen, to allow users to jump straight to the settings page.
 */
function kbso_plugin_settings_link( $links ) {
    
    $links[] = '<a href="' . admin_url( 'admin.php?page=kbso-settings' ) . '">' . __( 'Settings', 'kbso' ) . '</a>';
    
    return $links;
    
}
add_filter( 'plugin_action_links_kebo-social/kebo-social.php', 'kbso_plugin_settings_link' );

/**
 * Prints the Admin Menu Icon CSS in the Footer
 * Added fallback image for pre 3.8 installs
 */
function kbso_admin_menu_styles_print() {
    
    // Begin Output Buffering
    ob_start();
    
    ?>
    <style type="text/css">
        
        #adminmenu .toplevel_page_kbso-dashboard div.wp-menu-image:before {
                font-family: "dashicons";
                content: "\f319";
        }
        .branch-3-7 .toplevel_page_kbso-dashboard div.wp-menu-image,
        .branch-3-6 .toplevel_page_kbso-dashboard div.wp-menu-image,
        .branch-3-5 .toplevel_page_kbso-dashboard div.wp-menu-image,
        .branch-3-4 .toplevel_page_kbso-dashboard div.wp-menu-image,
        .branch-3-3 .toplevel_page_kbso-dashboard div.wp-menu-image,
        .branch-3-2 .toplevel_page_kbso-dashboard div.wp-menu-image {
                background: url('../images/icons/admin_menu_icon.png') 0 -32px no-repeat;
        }
        .branch-3-7 .toplevel_page_kbso-dashboard div.wp-menu-image:before,
        .branch-3-6 .toplevel_page_kbso-dashboard div.wp-menu-image:before,
        .branch-3-5 .toplevel_page_kbso-dashboard div.wp-menu-image:before,
        .branch-3-4 .toplevel_page_kbso-dashboard div.wp-menu-image:before,
        .branch-3-3 .toplevel_page_kbso-dashboard div.wp-menu-image:before,
        .branch-3-2 .toplevel_page_kbso-dashboard div.wp-menu-image:before {
                content: "";
        }
        .branch-3-7 .toplevel_page_kbso-dashboard .wp-menu-open div.wp-menu-image,
        .branch-3-6 .toplevel_page_kbso-dashboard .wp-menu-open div.wp-menu-image,
        .branch-3-5 .toplevel_page_kbso-dashboard .wp-menu-open div.wp-menu-image,
        .branch-3-4 .toplevel_page_kbso-dashboard .wp-menu-open div.wp-menu-image,
        .branch-3-3 .toplevel_page_kbso-dashboard .wp-menu-open div.wp-menu-image,
        .branch-3-2 .toplevel_page_kbso-dashboard .wp-menu-open div.wp-menu-image {
                background-position: 0 0;
        }
        .branch-3-7 .toplevel_page_kbso-dashboard:hover div.wp-menu-image,
        .branch-3-6 .toplevel_page_kbso-dashboard:hover div.wp-menu-image,
        .branch-3-5 .toplevel_page_kbso-dashboard:hover div.wp-menu-image,
        .branch-3-4 .toplevel_page_kbso-dashboard:hover div.wp-menu-image,
        .branch-3-3 .toplevel_page_kbso-dashboard:hover div.wp-menu-image,
        .branch-3-2 .toplevel_page_kbso-dashboard:hover div.wp-menu-image {
                background-position: 0 0;
        }
        
    </style>
    <?php
    
    // End Output Buffering and Clear Buffer
    $output = ob_get_contents();
    ob_end_clean();
        
    echo $output;
    
}
add_action( 'in_admin_footer', 'kbso_admin_menu_styles_print' );

/**
 * Add a widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function kbso_add_dashboard_widgets() {

	wp_add_dashboard_widget(
            'kbso_social_sharing_status',
            __( 'Social Sharing Status', 'kbso' ),
            'kbso_status_dashboard_widget_render'
        );	
}
add_action( 'wp_dashboard_setup', 'kbso_add_dashboard_widgets' );

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function kbso_status_dashboard_widget_render() {
    
    /*
     * Store Query Results in Transient to save processing
     * 
     * expiry 5 mins
     * 
     * TODO: Make this refresh in the background so it never impacts 
     */
    if ( false === ( $counts = get_transient( 'kbso_social_sharing_status_widget' . get_current_blog_id() ) ) ) {
        
        global $wpdb;

        /*
         * Query for all share count post_meta
         * 
         * TODO: Test on blogs with extreme numbers of posts.
         */
        $results = $wpdb->get_results( 
            "SELECT * FROM $wpdb->postmeta WHERE meta_key = '_kbso_share_counts'"
        );

        $counts = new stdClass();
        
        /*
         * Loop each post_meta and update total counts
         */
        foreach ( $results as $meta ) {

            $post_counts = unserialize( $meta->meta_value );

            if ( isset( $post_counts['total'] ) && is_int( $post_counts['total'] ) ) {

                $counts->total = $counts->total + $post_counts['total'];

            }

            if ( isset( $post_counts['twitter'] ) && is_int( $post_counts['twitter'] ) ) {

                $counts->twitter = $counts->twitter + $post_counts['twitter'];

            }

            if ( isset( $post_counts['facebook'] ) && is_int( $post_counts['facebook'] ) ) {

                $counts->facebook = $counts->facebook + $post_counts['facebook'];

            }
            
            if ( isset( $post_counts['linkedin'] ) && is_int( $post_counts['linkedin'] ) ) {

                $counts->linkedin = $counts->linkedin + $post_counts['linkedin'];

            }
            
            if ( isset( $post_counts['googleplus'] ) && is_int( $post_counts['googleplus'] ) ) {

                $counts->googleplus = $counts->googleplus + $post_counts['googleplus'];

            }

        }
        
        /*
         * Add custom expiry time for use in soft expiry
         */
        $counts->expiry = time() + ( 5 * MINUTE_IN_SECONDS );
        
        set_transient( 'kbso_social_sharing_status_widget' . get_current_blog_id(), $counts, 5 * MINUTE_IN_SECONDS );
        
    }
    
    ?>
    <div class="kbso-status">
        
        <div class="kfull" style="border: none;" title="Total Number of Social Shares">
            <span class="kcount total" style="font-size: 18px; margin: 0;"><?php echo esc_html( $counts->total ); ?></span>
            <span class="klabel" style="margin: 0;">Total Shares</span>
        </div>
        
        <div class="khalf">
            <span class="kcount twitter"><?php echo esc_html( $counts->twitter ); ?></span>
            <span class="klabel">Twitter</span>
        </div>
        
        <div class="khalf kright">
            <span class="kcount facebook"><?php echo esc_html( $counts->facebook ); ?></span>
            <span class="klabel">Facebook</span>
        </div>
        
        <div class="khalf">
            <span class="kcount googleplus"><?php echo esc_html( $counts->googleplus ); ?></span>
            <span class="klabel">Google+</span>
        </div>
        
        <div class="khalf kright">
            <span class="kcount linkedin"><?php echo esc_html( $counts->linkedin ); ?></span>
            <span class="klabel">LinkedIn</span>
        </div>
        
        <br class="clear">
        
    </div>
    <?php
        
} 