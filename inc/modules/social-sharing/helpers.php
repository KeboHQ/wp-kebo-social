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
    
    return apply_filters( 'kbso_social_sharing_count_display', $result, $count );
    
}

/**
 * Theme Content Width Adjustment
 * Hide Floating Bar if screen width is less than the themes $content_width
 */
function kbso_social_share_content_width_compat() {
    
    global $content_width;
    
    /**
     * If content width is set use it, if not use a default guestimate
     */
    if ( ! empty( $content_width ) ) {
        
        $width = $content_width;
        
    } else {
        
        $width = 960;
        
    }
    ?>
    
    <style type="text/css">
    @media ( max-width: 1000px ) {
        
        body .kfloating {
            position: fixed;
            top: auto;
            bottom: 0;
            width: 100%;
            height: 80px;
            z-index: 150;
        }
        .kfloating .ksharelinks ul li {
            clear: none;
            width: auto;
        }
        body .kfloating .ksharelinks ul li .kcount {
            display: none;
        }
        
    }
    </style>
    
    <?php
    
}
add_action( 'wp_footer', 'kbso_social_share_content_width_compat' );