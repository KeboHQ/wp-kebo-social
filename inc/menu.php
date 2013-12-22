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
                            
                            <!--
                            <div class="handlediv" title="<?php _e('Click to toggle'); ?>">
                                
                                <br>
                                
                            </div>
                            -->
                            
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
                        
                        <!-- End Dashboard Items -->
                        
                    </div><!-- .meta-box-sortables .ui-sortable -->
                    
                </div><!-- .postbox-container -->
                
                <div id="postbox-container-2" class="postbox-container">
                    
                    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                        
                        <!-- Begin Dashboard Items -->
                        
                        <div id="unique-id" class="postbox">
                            
                            <!--
                            <div class="handlediv" title="<?php _e('Click to toggle'); ?>">
                                
                                <br>
                                
                            </div>
                            -->
                            
                            <h3 class="hndle">
                                
                                <span><?php _e('Getting Started'); ?></span>
                                
                            </h3>
                            
                            <div class="inside">
                                
                                <div class="main">
                                    
                                    <!-- Content -->
                                    <p>Kebo Social is just beginning its life as a WordPress plugin and will be under active development constantly. You can currently add Social Share Links to your posts from <a href="<?php echo esc_url( admin_url( '/admin.php?page=kbso-sharing' ) ); ?>">here</a>. These are specially designed to look beautiful, be responsive and include no tracking scripts.</p>
                                    
                                </div>
                                
                            </div>
                            
                        </div><!-- .postbox -->
                        
                        <!-- End Dashboard Items -->
                        
                    </div>
                    
                </div>
                
                <div id="postbox-container-3" class="postbox-container">
                    
                    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                        
                        <!-- Begin Dashboard Items -->
                        
                        <div id="unique-id" class="postbox">
                            
                            <!--
                            <div class="handlediv" title="<?php _e('Click to toggle'); ?>">
                                
                                <br>
                                
                            </div>
                            -->
                            
                            <h3 class="hndle">
                                
                                <span><?php _e('Planned Features'); ?></span>
                                
                            </h3>
                            
                            <div class="inside">
                                
                                <div class="main">
                                    
                                    <!-- Content -->
                                    <p>We have many features planned which are in various stages of completion:</p>
                                    
                                    <ul>
                                        <li><h4>Social Feed Widgets</h4></li>
                                        <li><h4>Post to Social Services</h4></li>
                                        <li><h4>Social Stats (Premium)</h4></li>
                                    </ul>
                                    
                                    <p>You can read more about the planned features here.</p>
                                    
                                </div>
                                
                            </div>
                            
                        </div><!-- .postbox -->
                        
                        <!-- End Dashboard Items -->
                        
                    </div>
                    
                </div>
                
                <div id="postbox-container-4" class="postbox-container">
                    
                    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                        
                        <!-- Begin Dashboard Items -->
                        
                        <div id="unique-id" class="postbox">
                            
                            <!--
                            <div class="handlediv" title="<?php _e('Click to toggle'); ?>">
                                
                                <br>
                                
                            </div>
                            -->
                            
                            <h3 class="hndle">
                                
                                <span><?php _e('Kebo Social Pro'); ?></span>
                                
                            </h3>
                            
                            <div class="inside">
                                
                                <div class="main">
                                    
                                    <!-- Content -->
                                    <p>Coming Soon - Kebo Social Pro tracks the Social Service activity for all your posts, recording historical data. This allows you to view valuable information about how your content is consumed and spread on Social Networks.</p>
                                    
                                </div>
                                
                            </div>
                            
                        </div><!-- .postbox -->
                        
                        <div id="unique-id" class="postbox">
                            
                            <!--
                            <div class="handlediv" title="<?php _e('Click to toggle'); ?>">
                                
                                <br>
                                
                            </div>
                            -->
                            
                            <h3 class="hndle">
                                
                                <span><?php _e('More from Kebo'); ?></span>
                                
                            </h3>
                            
                            <div class="inside">
                                
                                <div class="main">
                                    
                                    <!-- Content -->
                                    
                                    <div style="width: 50%; float: left;">
                                        
                                        <h4><?php _e('Other Plugins', 'kbso'); ?></h4>
                                    
                                        <ul>
                                            <li><a href="http://wordpress.org/plugins/kebo-twitter-feed/" target="_blank">Kebo Twitter Feed</a></li>
                                            <li><a href="http://wordpress.org/plugins/kebo-testimonials/" target="_blank">Kebo Testimonials</a></li>
                                        </ul>
                                        
                                    </div>
                                    
                                    <div style="width: 50%; float: left;">
                                    
                                        <h4><?php _e('Follow Us', 'kbso'); ?></h4>

                                        <ul>
                                            <li><a href="http://kebopowered.com/" target="_blank">Website</a></li>
                                            <li><a href="https://twitter.com/kebopowered/" target="_blank">Twitter</a></li>
                                            <li><a href="https://www.facebook.com/kebopowered/" target="_blank">Facebook</a></li>
                                            <li><a href="https://www.linkedin.com/company/2894278/" target="_blank">LinkedIn</a></li>
                                            <li><a href="https://github.com/kebopowered/" target="_blank">Github</a></li>
                                        </ul>
                                    
                                    </div>
                                    
                                    <div style="clear: both;"></div>
                                    
                                </div>
                                
                            </div>
                            
                        </div><!-- .postbox -->
                        
                        <!-- End Dashboard Items -->
                        
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
        
        //jQuery( '.handlediv' ).on( 'click', function() {
            //jQuery(this).parent( '.postbox' ).toggleClass( 'closed' );
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