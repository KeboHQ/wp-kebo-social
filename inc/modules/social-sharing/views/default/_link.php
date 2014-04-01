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
            
    </a>
    
    <?php if ( 'yes' == $options['social_sharing_counts'] && is_int( $count ) ) : ?>
        <span class="kcount"><?php echo esc_html( kbso_social_share_count_display( $count ) ); ?></span>
    <?php endif; ?>
    
    <?php do_action( 'kbso_after_sharelinks_link', $name, $label, $href, $post_type ); ?>

</li>
