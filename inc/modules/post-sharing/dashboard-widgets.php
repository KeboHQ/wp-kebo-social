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
if ( current_user_can( 'manage_options' ) ) {
    
    function kbso_post_sharing_add_status_widget() {

            wp_add_dashboard_widget(
                'kbso_post_sharing_status',
                __( 'Social Sharing Overview', 'kbso' ),
                'kbso_post_sharing_status_widget_render'
            );	
    }
    add_action( 'wp_dashboard_setup', 'kbso_post_sharing_add_status_widget' );
    
}

/**
 * Social Sharing Dashboard Widget
 * 
 * Displays Total and Grouped Social Counts.
 */
function kbso_post_sharing_status_widget_render() {
    
    /*
     * Store Query Results in Transient to save processing
     * 
     * Expiry 5 mins
     * 
     * TODO: Make this refresh in the background so it never impacts pageload
     */
    if ( false === ( $counts = get_transient( 'kbso_post_sharing_status_widget' . get_current_blog_id() ) ) ) {
        
        global $wpdb;

        /*
         * Query for all share count post_meta
         * 
         * TODO: Test on blogs with extreme numbers of posts.
         */
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $wpdb->postmeta WHERE meta_key = %s",
                '_kbso_post_sharing_counts'
            )
        );
        
        /**
         * Prepare Social Count Totals
         */
        $counts = kbso_post_sharing_prepare_count_totals( $results );
        
        /*
         * Add custom expiry time for use in soft expiry
         */
        //$counts->expiry = time() + ( 5 * MINUTE_IN_SECONDS );
        
        $refresh = apply_filters( 'kbso_post_sharing_dashboard_widget_refresh', 5 * MINUTE_IN_SECONDS );
        
        set_transient( 'kbso_post_sharing_status_widget' . get_current_blog_id(), $counts, $refresh );
        
    }
    
    /**
     * Check we have valid Data before output.
     */
    if ( is_object( $counts ) && ! empty( $counts->total ) ) : 
    ?>

    <div class="kbso-status">

        <div id="shares-chart" style="width: 100%; min-height: 300px;"></div>

        <br class="clear">

    </div>

        <script type="text/javascript">

            jQuery( document ).ready(function() {

                var data = [
                    { label: "Facebook",  data: "<?php echo esc_html( $counts->facebook ); ?>", color: '#3b5998' },
                    { label: "Google+",  data: "<?php echo esc_html( $counts->googleplus ); ?>", color: '#dd4b39' },
                    { label: "LinkedIn",  data: "<?php echo esc_html( $counts->linkedin ); ?>", color: '#007bb6' },
                    { label: "Twitter",  data: "<?php echo esc_html( $counts->twitter ); ?>", color: '#00aced' }
                ];

                var sharesChart = jQuery("#shares-chart");

                jQuery.plot( sharesChart, data, {
                    series: {
                        pie: {
                            innerRadius: 0.4,
                            show: true,
                            combine: {
                                color: '#999',
                                threshold: 0.1,
                                label: 'Other'
                            },
                            radius: 1,
                            label: {
                                show: true,
                                radius: 2/3,
                                formatter: labelFormatter,
                                threshold: 0.1
                            }
                        }
                    },
                    grid: {
                        hoverable: false,
                        clickable: false
                    },
                    legend: {
                        show: false
                    }
                });

                function labelFormatter( label, series ) {
                    return "<div style='font-size: 10pt; font-weight: bold; text-align: center; color: #fff;'>"    + label + "<br/>" + Number( series.data[0][1] ).toLocaleString('en') + "</div>";
                }

            });

        </script>

    <?php
    else :
    ?>

    <div class="kbso-status">
        
        <h3 style="text-align: center;"><?php _e( 'Sorry, not enough data.', 'kbso' ); ?></h3>
        
    </div>

    <?php
    endif;
    
}