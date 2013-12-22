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
            'kbso_dashboard_page', // Render Function
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
            'kbso_dashboard_page' // Function that renders the options page
    );
    
}
add_action( 'admin_menu', 'kbso_plugin_menu' );

function kbso_dashboard_page() {

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __('You do not have sufficient permissions to access this page.') );
    }
    
    global $current_user;
    get_currentuserinfo();
    ?>

    <div class="wrap">
        <h2><?php _e('Kebo Social - Dashboard', 'kbso'); ?></h2>
        <?php settings_errors(); ?>

        <div id="dashboard-widgets-wrap">
            
            <div id="dashboard-widgets" class="metabox-holder">
                
                <div id="postbox-container-1" class="postbox-container">
                    
                    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                        
                        <!-- Begin Dashboard Items -->
                        <div id="unique-id" class="postbox">
                            
                            <div class="handlediv" title="<?php _e('Click to toggle'); ?>">
                                
                                <br>
                                
                            </div>
                            
                            <h3 class="hndle">
                                
                                <span><?php _e('Welcome'); ?></span>
                                
                            </h3>
                            
                            <div class="inside">
                                
                                <div class="main">
                                    
                                    <!-- Content -->
                                    <p><?php echo sprintf( __('Hi %s, welcome to your Kebo Social dashboard.'), $current_user->display_name ); ?></p>
                                    
                                </div>
                                
                            </div>
                            
                        </div><!-- .postbox -->
                        
                    </div><!-- .meta-box-sortables .ui-sortable -->
                    
                </div><!-- .postbox-container -->
                
                <div id="postbox-container-2" class="postbox-container">
                    
                    
                </div>
                
                <div id="postbox-container-3" class="postbox-container">
                    
                    
                </div>
                
                <div id="postbox-container-4" class="postbox-container">
                    
                    
                </div>
                
            </div>
            
        </div>
        
        <script type="text/javascript">
        
            jQuery( '.handlediv' ).on( 'click', function() {
                jQuery(this).parent( '.postbox' ).toggleClass( 'closed' );
            });
        
        </script>

    </div><!-- .wrap -->
    <?php
}
