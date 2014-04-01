<?php
/* 
 * Social Sharing AJAX
 */

if ( ! defined( 'KBSO_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/*
 * AJAX Save Share Link Order
 */
function kbso_save_post_sharing_order() {
    
    /*
     * Check for the required inputs
     */
    if ( ! isset( $_POST['action'] ) && ! isset( $_POST['data'] ) && ! isset( $_POST['nonce'] )  ) {
        die();
    }
    
    // Get the action
    $action = $_POST['action'];
    
    // Get the data
    $data = $_POST['data'];
    
    // Get nonce
    $nonce = $_POST['nonce'];
    
    /*
     * Check action
     */
    if ( 'kbso_save_post_sharing_order' !== $action ) {
        die();
    }
    
    /*
     * Check nonce
     */
    if ( ! wp_verify_nonce( $nonce, 'kbso_post_sharing_order' ) ) {
        die();
    }
    
    // Save Dashboard Positions
    update_option( 'kbso_post_sharing_order', $data );
    
    // Send successful response
    $response = array(
        'action' => 'save',
        'success' => 'true',
    );
    
    // Clear and previous output, like errors.
    ob_clean();
    
    // Output response
    print_r( json_encode( $response ) );
    
    // Send Output
    die();
    
}
add_action( 'wp_ajax_kbso_save_post_sharing_order', 'kbso_save_post_sharing_order' );