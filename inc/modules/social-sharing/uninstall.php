<?php
/* 
 * Social Sharing Module - Uninstall Functions
 */

function kbso_social_sharing_uninstall() {
    
    // Delete Options
    delete_option( 'kbso_social_sharing_order' );
    
}
add_action( 'kbso_plugin_uninstall', 'kbso_social_sharing_uninstall' );
add_action( 'kbso_plugin_uninstall_multisite', 'kbso_social_sharing_uninstall' );