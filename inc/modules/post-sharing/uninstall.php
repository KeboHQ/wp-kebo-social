<?php
/* 
 * Social Sharing Module - Uninstall Functions
 */

function kbso_post_sharing_uninstall() {
    
    // Delete Options
    delete_option( 'kbso_post_sharing_order' );
    
    // TODO: Remove share count post_meta from all posts.
    
}
add_action( 'kbso_plugin_uninstall', 'kbso_post_sharing_uninstall' );
add_action( 'kbso_plugin_uninstall_multisite', 'kbso_post_sharing_uninstall' );