<?php
/* 
 * Core plugin AJAX functions
 */

/*
 * AJAX Update Feature Control Options
 */
function kbso_feature_control_update() {
    
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
    if ( 'kbso_save_sharelink_order' !== $action ) {
        die();
    }
    
    /*
     * Check nonce
     */
    if ( ! wp_verify_nonce( $nonce, 'kbso_sharelink_order' ) ) {
        die();
    }
    
    // Save Dashboard Positions
    update_option( 'kbso_social_sharing_order', $data );
    
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
add_action( 'wp_ajax_kbso_feature_control_update', 'kbso_feature_control_update' );