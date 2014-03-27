<?php
/* 
 * Register the core Dashboard Widgets
 */

if ( ! defined( 'KBSO_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/**
 * Add a widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function kbso_social_sharing_add_widget_status() {

	wp_add_dashboard_widget(
            'kbso_social_sharing_status',
            __( 'Social Sharing Status', 'kbso' ),
            'kbso_social_sharing_status_widget_render'
        );	
}
add_action( 'wp_dashboard_setup', 'kbso_social_sharing_add_widget_status' );

/**
 * Social Sharing Dashboard Widget
 * 
 * Displays Total and Grouped Social Counts.
 */
function kbso_social_sharing_status_widget_render() {
    
    /*
     * Store Query Results in Transient to save processing
     * 
     * expiry 5 mins
     * 
     * TODO: Make this refresh in the background so it never impacts 
     */
    if ( false === ( $counts = get_transient( 'kbso_social_sharing_status_widget' . get_current_blog_id() ) ) ) {
        
        global $wpdb;

        /*
         * Query for all share count post_meta
         * 
         * TODO: Test on blogs with extreme numbers of posts.
         */
        $results = $wpdb->get_results(
            "SELECT * FROM $wpdb->postmeta WHERE meta_key = '_kbso_share_counts'"
        );
        
        /**
         * Prepare Social Count Totals
         */
        $counts = kbso_social_sharing_prepare_count_totals( $results );
        
        /*
         * Add custom expiry time for use in soft expiry
         */
        //$counts->expiry = time() + ( 5 * MINUTE_IN_SECONDS );
        
        set_transient( 'kbso_social_sharing_status_widget' . get_current_blog_id(), $counts, 5 * MINUTE_IN_SECONDS );
        
    }
    
    /**
     * Check we have valid Data before output.
     */
    if ( is_object( $counts ) && ! empty( $counts->total ) ) : 
    ?>

    <div class="kbso-status">
        
        <div class="kfull" style="border: none;" title="Total Number of Social Shares">
            <span class="kcount total" style="font-size: 18px; margin: 0;"><?php echo esc_html( $counts->total ); ?></span>
            <span class="klabel" style="margin: 0;">Total Shares</span>
        </div>
        
        <div class="khalf">
            <span class="kcount twitter"><?php echo esc_html( $counts->twitter ); ?></span>
            <span class="klabel">Twitter</span>
        </div>
        
        <div class="khalf kright">
            <span class="kcount facebook"><?php echo esc_html( $counts->facebook ); ?></span>
            <span class="klabel">Facebook</span>
        </div>
        
        <div class="khalf">
            <span class="kcount googleplus"><?php echo esc_html( $counts->googleplus ); ?></span>
            <span class="klabel">Google+</span>
        </div>
        
        <div class="khalf kright">
            <span class="kcount linkedin"><?php echo esc_html( $counts->linkedin ); ?></span>
            <span class="klabel">LinkedIn</span>
        </div>
        
        <br class="clear">
        
    </div>

    <?php
    else :
    ?>

    <div class="kbso-status">
        
        <h3 style="text-align: center;"><?php _e( 'Sorry, not enough data.', 'kbso' ); ?></h3>
        
    </div>

    <?php
    endif;
    
}