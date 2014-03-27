<?php
/*
 * Register the Top Level menu item for the plugin.
 */

if ( ! defined( 'KBSO_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

function kbso_plugin_menu() {

    /*
     * Plugin Page - Under Settings Menu
     * 
     * Uses Internal Tabs
     */
    add_submenu_page(
        'options-general.php', // Parent
        __('Kebo Social', 'kbso'), // Page Title
        __('Kebo Social', 'kbso'), // Menu Title
        'manage_options', // Capability
        'kebo-social', // Menu Slug
        'kebo_social_menu_render' // Render Function
    );
    
}
add_action( 'admin_menu', 'kbso_plugin_menu' );

/*
 * Render the Dashboard Page
 */
function kbso_dashboard_page_render() {
    ?>

        <?php settings_errors('kbso-dashboard'); ?>

        <div id="dashboard-widgets-wrap" class="kebo">
            
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

        <style type="text/css">
        
            .js .postbox .hndle {
                cursor: auto;
            }
        
        </style>

    <?php
}
add_action( 'kbso_tab_page_dashboard', 'kbso_dashboard_page_render' );

/*
 * Render the Settings Page
 */
function kbso_settings_page_render() {
    
    ?>

        <?php settings_errors('kbso-settings'); ?>
        
        <form method="post" action="options.php">
            <?php
            settings_fields( 'kbso_options' );
            do_settings_sections( 'kbso-settings' );
            submit_button();
            ?>
        </form>

    <?php
    
}
//add_action( 'kbso_tab_page_settings', 'kbso_settings_page_render' );

/**
 * Renders the Twitter Feed Options page.
 */
function kebo_social_menu_render() {
    
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    
    ?>
    <div class="wrap kebo">
        
        <h2><?php _e('Kebo Social', 'kbso'); ?></h2>
        
        <?php
        /**
         * Render Page
         */
        kbso_tab_page_render();
        ?>
        
    </div><!-- .wrap -->

    <?php
    
}