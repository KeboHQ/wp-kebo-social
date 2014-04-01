<?php
/*
 * Helper Functions
 */

/**
 * Update the count using a new number.
 */
function kbso_update_count( $current, $new ) {

    /*
     * If we have no values yet, set it to 0 as a default
     */
    if ( empty( $current ) ) {

        $current = 0;
    }

    /*
     * If we have a valid new value, use it.
     */
    if ( isset( $new ) && is_int( $new ) && ( 0 < $new ) ) {

        $current = $new;
    }

    return $current;
    
}

/**
 * Update the total count using a new number.
 */
function kbso_update_count_total( $total, $new ) {

    if ( ! empty( $new ) && is_int( absint( $new ) ) && ( 0 < $new ) ) {

        $total = $total + $new;
    }

    return $total;
    
}

/**
 * Output Selected Share Services
 */
function kbso_social_sharing_services_admin( $type = 'selected' ) {

    $services = kbso_social_sharing_filter_services( $type );

    if ( ! empty( $services ) && is_array( $services ) ) {

        foreach ( $services as $link ) {
            ?>
            <li class="<?php echo $link['name']; ?> share-link sortable" data-service="<?php echo $link['name']; ?>">
                <a><i class="zocial <?php echo $link['name']; ?>"></i><span class="name"><?php echo $link['label']; ?></span></a>
            </li>
            <?php
        }
        
    }
    
}

/**
 * Process Social Count for Display
 */
function kbso_social_share_count_display( $count, $thousand = 'K', $million = 'M' ) {

    if ( ! is_numeric( $count ) ) {
        $result = 0;
    }

    /*
     * If greater than 1000 divide by 1000 and add k.
     */
    if ( $count > 1000000 ) {

        $result = (int) ( $count / 1000000 );

        $result .= $million;
        
    } elseif ( $count > 1000 ) {

        $result = (int) ( $count / 1000 );

        $result .= $thousand;
        
    } else {

        $result = $count;
        
    }

    return apply_filters('kbso_social_sharing_count_display', absint( $result ), $count);
    
}

/**
 * Prepare Social Count Totals
 */
function kbso_social_sharing_prepare_count_totals( $results ) {

    $counts = new stdClass();

    /*
     * Loop each post_meta and update total counts
     */
    foreach ( $results as $meta ) {

        $post_counts = unserialize( $meta->meta_value );

        if ( isset( $post_counts['total'] ) && is_int( $post_counts['total'] ) ) {

            $counts->total = $counts->total + $post_counts['total'];
            
        }

        if ( isset( $post_counts['twitter'] ) && is_int( $post_counts['twitter'] ) ) {

            $counts->twitter = $counts->twitter + $post_counts['twitter'];
            
        }

        if ( isset( $post_counts['facebook'] ) && is_int( $post_counts['facebook'] ) ) {

            $counts->facebook = $counts->facebook + $post_counts['facebook'];
            
        }

        if ( isset( $post_counts['linkedin'] ) && is_int( $post_counts['linkedin'] ) ) {

            $counts->linkedin = $counts->linkedin + $post_counts['linkedin'];
            
        }

        if ( isset( $post_counts['googleplus'] ) && is_int( $post_counts['googleplus'] ) ) {

            $counts->googleplus = $counts->googleplus + $post_counts['googleplus'];
            
        }
        
    }
    
    return $counts;
    
}

/*
 * Decides if we should display the Post Sharing counts or not.
 */
function kbso_post_sharing_display_counts( $count, $option ) {
    
    if ( 'yes' == $option && ( is_int( $count ) || is_admin() ) ) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}