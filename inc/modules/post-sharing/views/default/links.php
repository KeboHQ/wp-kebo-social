<?php
/**
 * Template file to show Share Links
 */
?>

<?php do_action( 'kbso_before_post_sharing_links', $post_type ); ?>

<div class="<?php echo implode( ' ', $classes ); ?>">

    <?php if ( ! empty( $label ) ) { ?>
    
        <span class="kintro">
            <?php echo esc_html( $label ); ?>
        </span>
    
    <?php } ?>
    
    <ul>

        <?php
        $buffer = '';
        
        /**
         * Loop through each Link and render.
         */
        foreach ( $links as $link ) {
            
            $count = ( isset( $counts[ $link['name'] ] ) ) ? $counts[ $link['name'] ] : null ;
            
            /**
             * Already contains: ???
             */
            $buffer .= $view
                ->set_view( '_link' )
                ->set( 'name', $link['name'] )
                ->set( 'label', $link['label'] )
                ->set( 'href', $link['href'] )
                ->set( 'count', $count )
                ->retrieve();
            
        }
        
        echo $buffer;
        ?>

    </ul>

</div><!-- .ksharelinks -->

<?php do_action( 'kbso_after_post_sharing_links', $post_type ); ?>
