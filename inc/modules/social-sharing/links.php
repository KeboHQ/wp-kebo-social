<?php
/*
 * Social Sharing Links
 */

function kbso_get_default_links() {

    global $post;
    
    /*
     * If we don't have a Post Object, then 
     */
    if ( ! $post instanceof WP_Post ) {
        return false;
    }

    $title = urlencode( get_the_title() );
    $permalink = urlencode( get_permalink() );
    $summary = urlencode( wp_trim_words( strip_tags( get_the_content( $post->ID ) ), 50) );
    $site_name = urlencode( get_bloginfo( 'name' ) );
    $post_thumnail_url = urlencode( wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ) );

    do_action( 'kbso_before_share_link_defaults' );
    
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
    
    do_action( 'kbso_after_share_link_defaults', $title, $permalink, $post_thumnail_url );
    
    return $defaults;
    
}
