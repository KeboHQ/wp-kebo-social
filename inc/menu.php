<?php
/*
 * Register the Top Level menu item for the plugin.
 */

if ( ! defined( 'KBSO_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

function kbso_plugin_menu() {

    add_menu_page(
            __('Dashboard', 'kbso'), // Page Title
            __('Kebo Social', 'kbso'), // Menu Title
            'edit_others_posts', // Capability ** Let Editors See It **
            'kbso-dashboard', // Menu Slug
            'kbso_dashboard_page_render', // Render Function
            null, // Icon URL
            '99.00018384' // Menu Position (use decimals to ensure no conflicts
    );

    /*
     * Plugin Dashboard Page
     */
    add_submenu_page(
            'kbso-dashboard', // Parent Page Slug
            __('Dashboard', 'kbso'), // Name of Page
            __('Dashboard', 'kbso'), // Label in Menu
            'manage_options', // Capability Required
            'kbso-dashboard', // Menu Slug, used to uniquely identify the page
            'kbso_dashboard_page_render' // Function that renders the options page
    );
    
    /*
     * Plugin Settings Page
     */
    add_submenu_page(
            'kbso-dashboard', // Parent Page Slug
            __('Settings', 'kbso'), // Name of Page
            __('Settings', 'kbso'), // Label in Menu
            'manage_options', // Capability Required
            'kbso-settings', // Menu Slug, used to uniquely identify the page
            'kbso_settings_page_render' // Function that renders the options page
    );
    
}
add_action( 'admin_menu', 'kbso_plugin_menu' );

/*
 * Render the Dashboard Page
 */
function kbso_dashboard_page_render() {

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __('You do not have sufficient permissions to access this page.') );
    }
    
    global $current_user;
    ?>

    <div class="wrap">
        <h2><?php _e('Kebo Social - Dashboard', 'kbso'); ?></h2>
        <?php settings_errors(); ?>

        <div id="dashboard-widgets-wrap">
            
            <div id="dashboard-widgets" class="metabox-holder">
                
                <div id="postbox-container-1" class="postbox-container">
                    
                    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                        
                        <!-- Begin Dashboard Items - Column One -->
                        
                        <?php do_action( 'kbso_dashboard_column_one' ); ?>
                        
                        <!-- End Dashboard Items - Column One -->
                        
                    </div><!-- .meta-box-sortables .ui-sortable -->
                    
                </div><!-- .postbox-container -->
                
                <div id="postbox-container-2" class="postbox-container">
                    
                    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                        
                        <!-- Begin Dashboard Items - Column Two -->
                        
                        <?php do_action( 'kbso_dashboard_column_two' ); ?>
                        
                        <!-- End Dashboard Items - Column Two -->
                        
                    </div>
                    
                </div>
                
                <div id="postbox-container-3" class="postbox-container">
                    
                    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                        
                        <!-- Begin Dashboard Items - Column Three -->
                        
                        <?php do_action( 'kbso_dashboard_column_three' ); ?>
                        
                        <!-- End Dashboard Items - Column Three -->
                        
                    </div>
                    
                </div>
                
                <div id="postbox-container-4" class="postbox-container">
                    
                    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                        
                        <!-- Begin Dashboard Items - Column Four -->
                        
                        <?php do_action( 'kbso_dashboard_column_four' ); ?>
                        
                        <!-- End Dashboard Items - Column Four -->
                        
                    </div>
                    
                </div>
                
            </div>
            
        </div>

    </div><!-- .wrap -->
    <?php
}

/*
 * Render Dashboard Javascript
 */
function kbso_dashboard_page_scripts() {
    
    ?>
    <script type="text/javascript">
        
        //jQuery( '.handlediv' ).on( 'click', function($) {
            //$(this).parent( '.postbox' ).toggleClass( 'closed' );
        //});
        
    </script>
    
    <style type="text/css">
        
        .js .postbox .hndle {
            cursor: auto;
        }
        
    </style>
    <?php
    
}
add_action( 'admin_print_footer_scripts', 'kbso_dashboard_page_scripts' );

/*
 * Render the Settings Page
 */
function kbso_settings_page_render() {
    
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __('You do not have sufficient permissions to access this page.') );
    }
    
    ?>

    <div class="wrap">
        
        <h2><?php _e( 'Kebo Social - Settings', 'kbso' ); ?></h2>
        <?php settings_errors('kbso-settings'); ?>

        <form method="post" action="options.php">
            <?php
            settings_fields( 'kbso_options' );
            do_settings_sections( 'kbso-settings' );
            submit_button();
            ?>
        </form>
            
    </div>

    <?php
    
}