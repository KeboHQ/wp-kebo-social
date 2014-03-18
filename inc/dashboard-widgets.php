<?php
/* 
 * Plugin Dashboard and WP Admin Dashboard Widgets
 */

/**
 * TODO:
 * 1) Look at how we have used translation functions. Should the text be so fragmented into many functions?
 * 2) Should the text be inside paragraph tags?
 */


/**
 * Output 'Welcome' Widget
 */
function kbso_dashboard_widget_welcome() {
    
    global $current_user;
    
    $title = __( 'Welcome', 'kbso' );
    
    $content = sprintf( __( 'Hi %s, welcome to your Kebo Social dashboard.', 'kbso' ), $current_user->display_name );
    
    $content .= '<br><br>';
    
    $content .= sprintf( __( 'Kebo Social was inspired by the success of the <a href="%s" target="%s">Kebo Twitter Feed</a> plugin.', 'kbso' ), 'http://wordpress.org/plugins/kebo-twitter-feed/', '_blank' );
    
    $content .= '&nbsp;';
    
    $content .= __( 'We are now dedicated to providing the most hassle-free way to integrate your website with Social Services.', 'kbso' );
    
    kbso_dashboard_widget_render( $title, $content, $sortable = false );
    
}
add_action( 'kbso_dashboard_column_one', 'kbso_dashboard_widget_welcome' );

/**
 * Output 'Getting Started' Widget
 */
function kbso_dashboard_widget_getting_started() {
    
    $title = __( 'Getting Started', 'kbso' );
    
    $content = __( 'Kebo Social is just beginning its life as a WordPress plugin and will be under active development constantly.' , 'kbso' );
    
    $content .= '&nbsp;';
    
    $content .= sprintf( __( 'You can add Social Share Links to your posts from <a href="%s">here</a>.', 'kbso' ), admin_url( 'admin.php?page=kbso-sharing' ) );
    
    $content .= '<br><br>';
    
    $content .= __( 'These are specially designed to look beautiful, be responsive and include no tracking scripts.', 'kbso' );
    
    kbso_dashboard_widget_render( $title, $content, $sortable = false );
    
}
add_action( 'kbso_dashboard_column_one', 'kbso_dashboard_widget_getting_started' );

/**
 * Output 'About Kebo' Widget
 */
function kbso_dashboard_widget_about_kebo() {
    
    $title = __( 'About Kebo', 'kbso' );
    
    $content = __( 'We create hassle-free WordPress plugins which allow you to get more from your websites.' , 'kbso' );
    
    $content .= '&nbsp;';
    
    $content .= __( 'We thrive on making advanced features accessible and user-friendly.' , 'kbso' );
    
    $content .= '<br><br>';
    
    $content .= '<div class="more-from" style="width: 50%; float: left;">';
    
        $content .= '<h4>' . __( 'More from Kebo', 'kbso' ) . '</h4>';

        $content .= '<ul>';

            $content .= '<li>';
                $content .= sprintf( __( '<a href="%s" target="%s">%s</a>', 'kbso' ), 'https://kebopowered.com/plugins/', '_blank', 'Plugins' );
            $content .= '</li>';

            $content .= '<li>';
                $content .= sprintf( __( '<a href="%s" target="%s">%s</a>', 'kbso' ), 'https://kebopowered.com/blog/', '_blank', 'Blog' );
            $content .= '</li>';

            $content .= '<li>';
                $content .= sprintf( __( '<a href="%s" target="%s">%s</a>', 'kbso' ), 'https://kebopowered.com/documentation/', '_blank', 'Support Docs' );
            $content .= '</li>';

        $content .= '</ul>';
    
    $content .= '</div>';
    
    $content .= '<div class="follow-us" style="width: 50%; float: right;">';
    
        $content .= '<h4>' . __( 'Follow Us', 'kbso' ) . '</h4>';

        $content .= '<ul>';

            $content .= '<li>';
                $content .= sprintf( __( '<a href="%s" target="%s">%s</a>', 'kbso' ), 'https://twitter.com/kebopowered/', '_blank', 'Twitter' );
            $content .= '</li>';
            
            $content .= '<li>';
                $content .= sprintf( __( '<a href="%s" target="%s">%s</a>', 'kbso' ), 'https://www.facebook.com/kebopowered/', '_blank', 'Facebook' );
            $content .= '</li>';

            $content .= '<li>';
                $content .= sprintf( __( '<a href="%s" target="%s">%s</a>', 'kbso' ), 'https://github.com/kebopowered/', '_blank', 'Github' );
            $content .= '</li>';
            
        $content .= '</ul>';
    
    $content .= '</div>';
    
    $content .= '<div class="clearfix"></div>';
    
    kbso_dashboard_widget_render( $title, $content, $sortable = false );
    
}
add_action( 'kbso_dashboard_column_four', 'kbso_dashboard_widget_about_kebo' );

/**
 * Output 'Planned Features' Widget
 */
function kbso_dashboard_widget_coming_soon() {
    
    $title = __( 'Coming Soon', 'kbso' );
    
    $content = __( 'In the coming months we will be adding many more features. You can see what to expect next below:' , 'kbso' );
    
    $content .= '<br>';
    
    $content .= '<div class="planned-features">';

        $content .= '<ul>';

            $content .= '<li>';
                $content .= '<h4><strong>' . __( 'Social Feeds', 'kbso' ) . '</strong></h4>';
                $content .= '<p style="margin-top: 0;">' . __( 'Display your Social Feeds on your website in minutes.', 'kbso' ) . '</p>';
            $content .= '</li>';

            $content .= '<li>';
                $content .= '<h4><strong>' . __( 'Social Posting', 'kbso' ) . '</strong></h4>';
                $content .= '<p style="margin-top: 0;">' . __( 'Share your new content across Social Services instantly.', 'kbso' ) . '</p>';
            $content .= '</li>';

            $content .= '<li>';
                $content .= '<h4><strong>' . __( 'Social Widgets', 'kbso' ) . '</strong></h4>';
                $content .= '<p style="margin-top: 0;">' . __( 'Display your Social Feeds on your website in minutes.', 'kbso' ) . '</p>';
            $content .= '</li>';

        $content .= '</ul>';
    
    $content .= '</div>';
    
    kbso_dashboard_widget_render( $title, $content, $sortable = false );
    
}
add_action( 'kbso_dashboard_column_two', 'kbso_dashboard_widget_coming_soon' );

/**
 * Output 'Support' Widget
 */
function kbso_dashboard_widget_support() {
    
    $title = __( 'Support', 'kbso' );
    
    $content = sprintf( __( 'We are actively supporting the plugin and the easiest way to reach us is on the plugins <a href="%s" target="%s">support forum</a>.', 'kbso' ), 'http://wordpress.org/support/plugin/kebo-social/', '_blank' );
    
    $content .= '&nbsp;';
    
    $content .= __( 'You may run into bugs but if you let us know we will deal with them straight away.', 'kbso' );
    
    $content .= '<br><br>';
    
    $content .= sprintf( __( 'You can also find Support Documentation <a href="%s" target="%s">here</a>, and we have listed some of the most common below: ', 'kbso' ), 'https://kebopowered.com/docs/kebo-social/', '_blank' );
    
    $content .= '<br>';
    
    $content .= '<div class="support-docs">';

        $content .= '<ul>';

            $content .= '<li>';
                $content .= sprintf( __( '<a href="%s" target="%s">Getting Started</a>', 'kbso' ), 'https://kebopowered.com/docs/kebo-social/', '_blank' );
            $content .= '</li>';
            
            $content .= '<li>';
                $content .= sprintf( __( '<a href="%s" target="%s">Social Sharing - Add a Custom Theme</a>', 'kbso' ), 'https://kebopowered.com/docs/kebo-social/', '_blank' );
            $content .= '</li>';

            $content .= '<li>';
                $content .= sprintf( __( '<a href="%s" target="%s">Social Sharing - Add Social Services</a>', 'kbso' ), 'https://kebopowered.com/docs/kebo-social/', '_blank' );
            $content .= '</li>';

        $content .= '</ul>';
    
    $content .= '</div>';
    
    kbso_dashboard_widget_render( $title, $content, $sortable = false );
    
}
add_action( 'kbso_dashboard_column_three', 'kbso_dashboard_widget_support' );

/**
 * Output 'Support' Widget
 */
function kbso_dashboard_widget_get_involved() {
    
    $title = __( 'Get Involved', 'kbso' );
    
    $content = sprintf( __( 'You can see the plugin source on <a href="%s" target="%s">Github</a>. We welcome involvement from the community and would love to see how we can improve the plugin.', 'kbso' ), 'https://github.com/kebopowered/kebo-social/', '_blank' );
    
    $content .= '<br><br>';
    
    $content .= sprintf( __( 'We also welcome anyone willing to translate the plugin, and those interested can find the .pot file <a href="%s" target="%s">here</a>.', 'kbso' ), 'https://github.com/kebopowered/wp-kebo-social/blob/master/languages/kbso.pot', '_blank' );
    
    $content .= '&nbsp;';
    
    $content .= __( 'Before you get started, please be aware that the plugin will be growing quickly in the coming months so translations will need to be updated.' , 'kbso' );
    
    $content .= '<br><br>';
    
    $content .= sprintf( __( 'If you find any bugs please let me know using either the <a href="%s" target="%s">Support Forum</a> or Github <a href="%s" target="%s">Issues</a>.', 'kbso' ), 'http://wordpress.org/support/plugin/kebo-social', '_blank', 'https://github.com/kebopowered/wp-kebo-social/issues', '_blank' );
    
    kbso_dashboard_widget_render( $title, $content, $sortable = false );
    
}
add_action( 'kbso_dashboard_column_three', 'kbso_dashboard_widget_get_involved' );

/**
 * Output 'Kebo Social Pro' Widget
 */
/*
function kbso_dashboard_widget_kebo_social_pro() {
    
    $title = __( 'Kebo Social Pro', 'kbso' );
    
    $content = __( 'We are aiming to release a paid extension to this plugin around summer. This will add a very specific feature, Social Statistics.' , 'kbso' );
    
    $content .= '&nbsp;';
    
    $content .= __( 'No other features included, or planned, will ever be restricted or put behind a pay wall.' , 'kbso' );
    
    $content .= '<br><br>';
    
    $content .= __( 'Kebo Social Pro will provide users with insight into how their content is being absorbed and spread on Social Media.' , 'kbso' );
    
    $content .= '&nbsp;';
    
    $content .= __( 'This will allow you to make more informed decisions about how you use Social Media and what content you create.' , 'kbso' );
    
    kbso_dashboard_widget_render( $title, $content, $sortable = false );
    
}
add_action( 'kbso_dashboard_column_four', 'kbso_dashboard_widget_kebo_social_pro' );
 * 
 */