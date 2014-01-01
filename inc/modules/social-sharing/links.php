<?php
/*
 * Social Sharing Links
 */

/**
 * Adds Social Share links below Blog Post Content.
 */
function kbso_add_social_sharing_buttons( $content ) {
    
    global $post;

    $options = kbso_get_plugin_options();
    
    add_action( 'wp_footer', 'kbso_share_links_js_print' );

    if ( is_singular() && is_main_query() && in_array( $post->post_type, $options['share_links_post_types'] ) ) {
        
        $content = $content . kbso_render_share_buttons();
        
    }
    
    return $content;
    
}

/**
 * Renders the Social Share Buttons.
 */
function kbso_render_share_buttons() {
    
    global $post;

    $options = kbso_get_plugin_options();

    $theme = $options['social_sharing_theme'];
    
    /*
     * All basic themes use the same view files.
     */
    $basic_themes = array( 'default', 'flat', 'gradient' );
    
    if ( in_array( $theme, $basic_themes ) ) {
        
        $theme_view = 'default';
        
    } else {
        
        $theme_view = $theme;
        
    }
    
    /**
     * Setup an instance of the View class.
     * Allow customization using a filter.
     */
    $view = new Kebo_View(
        apply_filters(
            'kbso_social_sharing_view_dir',
            KBSO_PATH . 'inc/modules/social-sharing/views/' . $theme_view,
            $theme
        )
    );
    
    $selected_links = array();
    
    $all_links = kbso_get_social_share_links();
    
    /*
     * Group the selected Social Links
     */
    foreach ( kbso_share_links_order('selected') as $link ) {
            
        if ( isset( $all_links[ $link ] ) ) {
                
            $selected_links[ $link ] = $all_links[ $link ];
                
        }
            
    }
    
    /**
     * Prepare the HTML classes
     */
    $classes[] = 'ksharelinks';
    $classes[] = $options['social_sharing_theme'];
    $classes[] = $options['social_sharing_link_size'];
    if ( is_rtl() ) {
        $classes[] = 'rtl';
    }
    
    /*
     * Get Share Counts
     */
    $counts = get_post_meta( $post->ID, '_kbso_share_counts', true );
    
    /*
     * Build HTML output
     */
    $links = $view
        ->set_view( 'links' )
        ->set( 'classes', $classes )
        ->set( 'label', $options['social_sharing_label'] )
        ->set( 'link_content', $options['social_sharing_link_content'] )
        ->set( 'post_type', $post->post_type )
        ->set( 'options', $options )
        ->set( 'permalink', get_permalink() )
        ->set( 'title', get_the_title() )
        ->set( 'links', $selected_links )
        ->set( 'counts', $counts )
        ->set( 'view', $view )
        ->retrieve();
    
    unset( $view );
    
    return $links;
    
}

function kbso_get_social_share_links() {

    global $post;
    
    /*
     * If we don't have a Post Object, then 
     */
    if ( ! $post instanceof WP_Post ) {
        return false;
    }

    /*
     * Prepare Post Data
     */
    $title = urlencode( get_the_title() );
    $permalink = urlencode( get_permalink() );
    $summary = urlencode( wp_trim_words( strip_tags( get_the_content( $post->ID ) ), 50) );
    $site_name = urlencode( get_bloginfo( 'name' ) );
    $post_thumnail_url = urlencode( wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ) );

    // Allow Override
    do_action( 'kbso_before_share_link_defaults' );
    
    /*
     * Default Social Share Links
     */
    $defaults = array(
        'facebook' => array(
            'name' => 'facebook',
            'label' => 'Facebook',
            'href' => 'http://www.facebook.com/sharer.php?u=' . $permalink . '&t=' . $title . ''
        ),
        'twitter' => array(
            'name' => 'twitter',
            'label' => 'Twitter',
            'href' => 'http://twitter.com/share?text=' . $title . '&url=' . $permalink . ''
        ),
        'googleplus' => array(
            'name' => 'googleplus',
            'label' => 'Google+',
            'href' => 'https://plus.google.com/share?url=' . $permalink . ''
        ),
        'linkedin' => array(
            'name' => 'linkedin',
            'label' => 'LinkedIn',
            'href' => 'http://www.linkedin.com/shareArticle?mini=true&url=' . $permalink . '&title=' . $title . '&summary=' . $summary . '&source=' . $site_name . ''
        ),
        'pinterest' => array(
            'label' => 'Pinterest',
            'name' => 'pinterest',
            'href' => 'http://pinterest.com/pin/create/button/?url=' . $permalink . '&media=' . $post_thumnail_url . '&description=' . $title . '&is_video=false'
        ),
        'tumblr' => array(
            'name' => 'tumblr',
            'label' => 'Tumblr',
            'href' => 'https://www.tumblr.com/share/link?url=' . $permalink . '&name=' . $title . '&description=' . $summary . ''
        ),
        'reddit' => array(
            'name' => 'reddit',
            'label' => 'Reddit',
            'href' => 'http://www.reddit.com/submit?title=' . $title . '&url=' . $permalink . ''
        ),
        'stumbleupon' => array(
            'name' => 'stumbleupon',
            'label' => 'StumbleUpon',
            'href' => 'http://www.stumbleupon.com/submit?url=' . $permalink . '&title=' . $title . ''
        ),
        'digg' => array(
            'name' => 'digg',
            'label' => 'Digg',
            'href' => 'http://digg.com/submit?url=' . $permalink . '&title=' . $title . ''
        ),
        'delicious' => array(
            'name' => 'delicious',
            'label' => 'Delicious',
            'href' => 'https://delicious.com/save?v=5&noui&jump=close&url=' . $permalink . '&title=' . $title . ''
        ),
    );
    
    // Allow Override
    do_action( 'kbso_after_share_link_defaults', $title, $permalink, $post_thumnail_url );
    
    return $defaults;
    
}

/**
 * Prepare Share Link Order
 */
function kbso_share_links_order( $type = 'selected' ) {
    
    /*
     * Default Social Services
     */
    $all_links = array(
        'twitter', 'facebook', 'linkedin', 'googleplus', 'pinterest', 'tumblr', 'reddit', 'stumbleupon', 'digg', 'delicious'
    );
    
    $selected = get_option( 'kbso_sharelink_order' );
    
    if ( 'selected' == $type ) {
        
        return $selected;
        
    } else {
        
        foreach ( $all_links as $link ) {
            
            if ( ! in_array( $link, $selected ) ) {
                
                $remaining[] = $link; 
                
            }
            
        }
        
        return $remaining;
        
    }
    
    return $links;
    
}

/**
 * Outputs Share Links Javascript
 */
function kbso_share_links_js_print() {
    
    ?>
    <script type="text/javascript">
        
        jQuery(document).ready( function() {
            
            jQuery( '.ksharelinks ul li a' ).click( function( e ) {

                // Prevent Click from Reloading page
                e.preventDefault();

                var khref = jQuery(this).attr('href');
                window.open( khref, 'window', 'width=600, height=400, top=0, left=0');

            });
            
        });
        
    </script>
    <?php
    
}