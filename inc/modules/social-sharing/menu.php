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

    <div class="wrap">
        
        <h2><?php _e( 'Kebo Social - Sharing', 'kbso' ); ?></h2>
        <?php settings_errors('kbso-sharing'); ?>

        <form method="post" action="options.php">
            <?php
            settings_fields( 'kbso_options' );
            do_settings_sections( 'kbso-sharing' );
            submit_button();
            ?>
        </form>
            
    </div>

    <?php
    
}