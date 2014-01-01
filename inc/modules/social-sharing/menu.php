<?php
/* 
 * Register the Sharing sub menu item.
 */

if ( ! defined( 'KBSO_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

function kbso_plugin_menu_social_sharing() {
    
    /*
     * Plugin Settings Page
     */
    add_submenu_page(
            'kbso-dashboard', // Parent Page Slug
            __('Sharing', 'kbso'), // Name of Page
            __('Sharing', 'kbso'), // Label in Menu
            'manage_options', // Capability Required
            'kbso-sharing', // Menu Slug, used to uniquely identify the page
            'kbso_sharing_page_render' // Function that renders the options page
    );
    
}
add_action( 'admin_menu', 'kbso_plugin_menu_social_sharing' );

/*
 * Render the Sharing Page
 */
function kbso_sharing_page_render() {
    
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __('You do not have sufficient permissions to access this page.') );
    }
    
    ?>

    <div class="wrap kebo">
        
        <h2><?php esc_html_e( 'Kebo Social - Sharing', 'kbso' ); ?></h2>
        <?php settings_errors('kbso-sharing'); ?>
        
        <h3><?php esc_html_e('Share Links','kbso'); ?></h3>
        <p><?php esc_html_e('Add sharing buttons to your website and allow visitors to share your content on social networks.', 'kbso'); ?></p>
        
        <div class="share-links-container">

            <div class="available-services share-panel">

                <div class="description">
                    
                    <h3><?php esc_html_e('Available Services','kbso'); ?></h3>
                    
                    <p><?php esc_html_e('Drag the services you would like to display on your site into the box below.', 'kbso'); ?></p>
                    
                </div>
                
                <div class="links available">
                    
                    <ul id="share-links-available" class="link-container connectedSortable">
                        
                        <?php kbso_social_share_services( 'remaining' ); ?>
                        
                    </ul>
                    
                </div>
                
                <div class="clearfix"></div>

            </div>

            <div class="selected-services share-panel">
                
                <div class="description">
                    
                    <h3><?php esc_html_e('Selected Services','kbso'); ?></h3>
                    
                    <p><?php esc_html_e('Services listed here will appear in the same order on your website.', 'kbso'); ?></p>
                    
                </div>
                
                <div class="links selected">
                    
                    <ul id="share-links-selected" class="link-container connectedSortable">
                        
                        <?php kbso_social_share_services( 'selected' ); ?>
                        
                    </ul>
                    
                </div>
                
                <div class="clearfix"></div>
                
            </div>
            
            <div class="preview-services share-panel">
                
                <div class="description">
                    
                    <h3><?php esc_html_e('Preview','kbso'); ?></h3>
                    
                    <p><?php esc_html_e('This is how the links will look on your website.', 'kbso'); ?></p>
                    
                </div>
                
                <div class="links selected">
                    
                    <?php echo kbso_share_button_preview(); ?>
                    
                </div>
                
                <div class="clearfix"></div>
                
            </div>
        
        </div><!-- .share-links-container -->
        
        <form method="post" action="options.php">
            <?php
            settings_fields( 'kbso_options' );
            do_settings_sections( 'kbso-sharing' );
            submit_button();
            ?>
        </form>
            
    </div>

    <?php
    
    /*
     * Print Sharing Page JS
     */
    kbso_sharing_page_print_js();
    
}