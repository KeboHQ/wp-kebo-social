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
