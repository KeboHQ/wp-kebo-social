<?php
/**
 * To change this license header, choose License Headers in Project Properties.
 */
?>

<li>

    <?php do_action( 'kbso_before_sharelinks_link', $name, $label, $href, $post_type ); ?>
    
    <a class="<?php echo esc_attr( 'klink ' . $name ); ?>" href="<?php echo esc_url( $href ); ?>" title="<?php echo esc_attr( sprintf( 'Share on %s', $label ) ); ?>" target="_blank">
        
        <span class="kicon"><i class="<?php echo esc_attr( 'zocial ' . $name ); ?>"></i></span>
        
        <span class="kname"><?php echo esc_html( $label ); ?></span>
        
        <?php if ( isset( $count ) && 0 != $count ) : ?>
            <span class="kcount"><?php echo esc_html( $count ); ?></span>
        <?php endif; ?>
            
    </a>
    
    <?php do_action( 'kbso_after_sharelinks_link', $name, $label, $href, $post_type ); ?>

</li>
