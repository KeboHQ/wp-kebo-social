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
    
    // Begin Output Buffering
    ob_start();
    ?>

    <p>
        <?php
        echo sprintf( __( 'Hi %s, welcome to your Kebo Social dashboard.', 'kbso' ), $current_user->display_name );
        ?>
    </p>
    
    <p>
        <?php
        echo sprintf( __( 'Kebo Social was inspired by the success of the <a href="%s" target="%s">Kebo Twitter Feed</a> plugin.', 'kbso' ), 'http://wordpress.org/plugins/kebo-twitter-feed/', '_blank' );
        echo '&nbsp;';
        echo __( 'We are now dedicated to providing the most hassle-free way to integrate your website with Social Services.', 'kbso' );
        ?>
    </p>

    <?php
    // End Output Buffering and Clear Buffer
    $content = ob_get_contents();
    ob_end_clean();
    
    kbso_dashboard_widget_render( $title, $content, $sortable = false );
    
}
add_action( 'kbso_dashboard_column_one', 'kbso_dashboard_widget_welcome' );

/**
 * Output 'Feature Control' Widget
 */
function kbso_dashboard_widget_feature_control() {
    
    $title = __( 'Feature Control', 'kbso' );
    
    // Begin Output Buffering
    ob_start();
    ?>
    
    <form method="post" action="options.php" class="feature-control">
        <?php
        settings_fields( 'kbso_options' );
        do_settings_sections( 'kbso-settings' );
        submit_button();
        ?>
    </form>

    <?php
    // End Output Buffering and Clear Buffer
    $content = ob_get_contents();
    ob_end_clean();
    
    kbso_dashboard_widget_render( $title, $content, $sortable = false );
    
}
add_action( 'kbso_dashboard_column_one', 'kbso_dashboard_widget_feature_control' );

/**
 * Output 'Getting Started' Widget
 */
function kbso_dashboard_widget_getting_started() {
    
    $title = __( 'Getting Started', 'kbso' );
    
    // Begin Output Buffering
    ob_start();
    ?>
    
    <p>
        <?php
        echo __( 'Kebo Social is just beginning its life as a WordPress plugin and will be under active development constantly.' , 'kbso' );
        echo '&nbsp;';
        echo sprintf( __( 'You can add Social Share Links to your posts from <a href="%s">here</a>.', 'kbso' ), admin_url( 'admin.php?page=kbso-sharing' ) );
        ?>
    </p>
    
    <p>
        <?php
        echo __( 'These are specially designed to look beautiful, be responsive and include no tracking scripts.', 'kbso' );
        ?>
    </p>
    
    <?php
    // End Output Buffering and Clear Buffer
    $content = ob_get_contents();
    ob_end_clean();
    
    kbso_dashboard_widget_render( $title, $content, $sortable = false );
    
}
add_action( 'kbso_dashboard_column_one', 'kbso_dashboard_widget_getting_started' );

/**
 * Output 'About Kebo' Widget
 */
function kbso_dashboard_widget_about_kebo() {
    
    $title = __( 'About Kebo', 'kbso' );
    
    // Begin Output Buffering
    ob_start();
    ?>
    
    <p>
        <?php
        echo __( 'We create hassle-free WordPress plugins which allow you to get more from your websites.' , 'kbso' );
        echo '&nbsp;';
        echo __( 'We thrive on making advanced features accessible and user-friendly.' , 'kbso' );
        ?>
    </p>
    
    <div class="more-from" style="width: 50%; float: left;">
        
        <h4><?php _e( 'More from Kebo', 'kbso' ); ?></h4>
        
        <ul>
            
            <li><?php echo sprintf( __( '<a href="%s" target="%s">%s</a>', 'kbso' ), 'https://kebopowered.com/plugins/', '_blank', 'Plugins' ); ?></li>
            
            <li><?php echo sprintf( __( '<a href="%s" target="%s">%s</a>', 'kbso' ), 'https://kebopowered.com/blog/', '_blank', 'Blog' ); ?></li>
            
            <li><?php echo sprintf( __( '<a href="%s" target="%s">%s</a>', 'kbso' ), 'https://kebopowered.com/documentation/', '_blank', 'Support Docs' ); ?></li>
            
        </ul>
        
    </div>
    
    <div class="follow-us" style="width: 50%; float: right;">
        
        <h4><?php _e( 'Follow Us', 'kbso' ); ?></h4>
        
        <ul>
            
            <li><?php echo sprintf( __( '<a href="%s" target="%s">%s</a>', 'kbso' ), 'https://twitter.com/kebopowered/', '_blank', 'Twitter' ); ?></li>
            
            <li><?php echo sprintf( __( '<a href="%s" target="%s">%s</a>', 'kbso' ), 'https://www.facebook.com/kebopowered/', '_blank', 'Facebook' ); ?></li>
            
            <li><?php echo sprintf( __( '<a href="%s" target="%s">%s</a>', 'kbso' ), 'https://github.com/kebopowered/', '_blank', 'Github' ); ?></li>
            
        </ul>
        
    </div>
    
    <br class="clear">
    
    <?php
    // End Output Buffering and Clear Buffer
    $content = ob_get_contents();
    ob_end_clean();
    
    kbso_dashboard_widget_render( $title, $content, $sortable = false );
    
}
add_action( 'kbso_dashboard_column_four', 'kbso_dashboard_widget_about_kebo' );

/**
 * Output 'Planned Features' Widget
 */
function kbso_dashboard_widget_coming_soon() {
    
    $title = __( 'Coming Soon', 'kbso' );
    
    // Begin Output Buffering
    ob_start();
    ?>
    
    <p>
        <?php
        echo __( 'In the coming months we will be adding many more features. You can see what to expect next below:' , 'kbso' );
        ?>
    </p>
    
    <div class="planned-features">
        
        <ul>
            
            <li>
                <h4 style="font-weight: bold;"><?php _e( 'Social Feeds', 'kbso' ); ?></h4>
                <p style="margin-top: 0;">
                    <?php echo __( 'Display your Social Feeds on your website in minutes.', 'kbso' ); ?>
                </p>
            </li>
            
            <li>
                <h4 style="font-weight: bold;"><?php _e( 'Social Posting', 'kbso' ); ?></h4>
                <p style="margin-top: 0;">
                    <?php echo __( 'Share your new content across Social Services instantly.', 'kbso' ) ?>
                </p>
            </li>
            
        </ul>
        
    </div>
    
    <?php
    // End Output Buffering and Clear Buffer
    $content = ob_get_contents();
    ob_end_clean();
    
    kbso_dashboard_widget_render( $title, $content, $sortable = false );
    
}
add_action( 'kbso_dashboard_column_two', 'kbso_dashboard_widget_coming_soon' );

/**
 * Output 'Support' Widget
 */
function kbso_dashboard_widget_support() {
    
    $title = __( 'Support', 'kbso' );
    
    // Begin Output Buffering
    ob_start();
    ?>
    
    <p>
        <?php
        echo sprintf( __( 'We are actively supporting the plugin and the easiest way to reach us is on the plugins <a href="%s" target="%s">support forum</a>.', 'kbso' ), 'http://wordpress.org/support/plugin/kebo-social/', '_blank' );
        echo '&nbsp;';
        echo __( 'You may run into bugs but if you let us know we will deal with them straight away.', 'kbso' );
        ?>
    </p>
    
    <p>
        <?php
        echo sprintf( __( 'You can also find Support Documentation <a href="%s" target="%s">here</a>, and we have listed some of the most common below: ', 'kbso' ), 'https://kebopowered.com/docs/kebo-social/', '_blank' );
        ?>
    </p>
    
    <div class="support-docs">
        
        <ul>
            
            <li><?php echo sprintf( __( '<a href="%s" target="%s">Getting Started</a>', 'kbso' ), 'https://kebopowered.com/docs/kebo-social/', '_blank' ); ?></li>
            
            <li><?php echo sprintf( __( '<a href="%s" target="%s">Social Sharing - Add a Custom Theme</a>', 'kbso' ), 'https://kebopowered.com/docs/kebo-social/', '_blank' ); ?></li>
            
            <li><?php echo sprintf( __( '<a href="%s" target="%s">Social Sharing - Add Social Services</a>', 'kbso' ), 'https://kebopowered.com/docs/kebo-social/', '_blank' ); ?></li>
            
        </ul>
        
    </div>
    
    <?php
    // End Output Buffering and Clear Buffer
    $content = ob_get_contents();
    ob_end_clean();
    
    kbso_dashboard_widget_render( $title, $content, $sortable = false );
    
}
add_action( 'kbso_dashboard_column_three', 'kbso_dashboard_widget_support' );

/**
 * Output 'Support' Widget
 */
function kbso_dashboard_widget_get_involved() {
    
    $title = __( 'Get Involved', 'kbso' );
    
    // Begin Output Buffering
    ob_start();
    ?>
    
    <p>
        <?php
        echo sprintf( __( 'You can see the plugin source on <a href="%s" target="%s">Github</a>. We welcome involvement from the community and would love to see how we can improve the plugin.', 'kbso' ), 'https://github.com/kebopowered/kebo-social/', '_blank' );
        ?>
    </p>
    
    <p>
        <?php
        echo sprintf( __( 'We also welcome anyone willing to translate the plugin, and those interested can find the .pot file <a href="%s" target="%s">here</a>.', 'kbso' ), 'https://github.com/kebopowered/wp-kebo-social/blob/master/languages/kbso.pot', '_blank' );
        echo '&nbsp;';
        echo __( 'Before you get started, please be aware that the plugin will be growing quickly in the coming months so translations will need to be updated.' , 'kbso' );
        ?>
    </p>
    
    <p>
        <?php
        echo sprintf( __( 'If you find any bugs please let me know using either the <a href="%s" target="%s">Support Forum</a> or Github <a href="%s" target="%s">Issues</a>.', 'kbso' ), 'http://wordpress.org/support/plugin/kebo-social', '_blank', 'https://github.com/kebopowered/wp-kebo-social/issues', '_blank' );
        ?>
    </p>
    
    <?php
    // End Output Buffering and Clear Buffer
    $content = ob_get_contents();
    ob_end_clean();
    
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