<?php
/*
 * Helper Functions
 */

/*
 * Output Selected Share Services
 */
function kbso_social_share_services( $type = 'selected' ) {

    $services = kbso_share_links_order( $type );

    if ( is_array( $services ) ) {

        foreach ( $services as $link ) {
            ?>
            <li class="<?php echo $link; ?> share-link sortable" data-service="<?php echo $link; ?>">
                <a><i class="zocial <?php echo $link; ?>"></i><span class="name"><?php echo ucfirst($link); ?></span></a>
            </li>
            <?php
        }
        
    }
    
}

/*
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