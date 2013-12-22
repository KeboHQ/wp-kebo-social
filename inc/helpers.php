<?php
/*
 * General Helper Functions
 */

if ( ! defined( 'KBSO_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/**
 * Render Dashboard Widgets
 */
function kbso_dashboard_widget_render($title, $content, $sortable = false) {
    
    ?>
    <div id="unique-id" class="postbox">

        <!--
        <div class="handlediv" title="<?php _e('Click to toggle'); ?>">
            
            <br>
            
        </div>
        -->

        <h3 class="hndle">

            <span><?php echo esc_html( $title ); ?></span>

        </h3>

        <div class="inside">

            <div class="main">

                <?php echo $content; ?>
                
            </div>

        </div>

    </div><!-- .postbox -->
    <?php
    
}
