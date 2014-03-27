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
function kbso_dashboard_widget_render( $title, $content, $sortable = false ) {
    
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

/**
 * Menu Tabs Helpers
 */

/**
 * Get Tabs Pages
 */
function kbso_get_tabs_pages() {
    
    $pages = array(
        array(
            'slug' => 'dashboard',
            'label' => __( 'Dashboard', 'kbso' )
        ),
        /*
        array(
            'slug' => 'settings',
            'label' => __( 'Settings', 'kbso' )
        ),
         * 
         * Not Currently Used
         * Might be later
         */
    );
    
    return apply_filters( 'kbso_tab_pages', $pages );
    
}

/**
 * Render Tab Pages
 */
function kbso_tab_page_render() {
    
    /**
     * Render Tabs
     */
    kbso_tabs_render();
    
    /**
     * Allow page content to be added via hooks.
     */
    do_action( 'kbso_tab_page_' . kbso_get_tab_page() );
    
}

/**
 * Renders Tabs
 */
function kbso_tabs_render() {
    
    $pages = kbso_get_tabs_pages();
    
    ?>
    <h2 class="nav-tab-wrapper">
    <?php
    
    if ( is_array( $pages ) ) :
        
        foreach ( $pages as $page ) {

            ?>
            <a class="nav-tab<?php echo kbso_is_active_tab( $page ); ?>" data-slug="<?php echo esc_attr( $page['slug'] ); ?>" href="<?php echo admin_url( 'options-general.php?page=kebo-social&tab=' . esc_attr( $page['slug'] ) ); ?>"><?php echo esc_html( $page['label'] ); ?></a>
            <?php
        
        }
        
    endif;
    
    ?>
    </h2>
    <?php
    
}

/**
 * Checks for active tab and outputs class if needed
 */
function kbso_get_tab_page() {
    
    if ( isset( $_GET['tab'] ) ) {
        
        $page = sanitize_title( $_GET['tab'], 'dashboard' );
        
    } else {
        
        $page = 'dashboard';
        
    }
        
    return $page;
    
}

/**
 * Checks for active tab and outputs class if needed
 */
function kbso_is_active_tab( $page ) {
    
    if ( $page['slug'] == kbso_get_tab_page() ) {
                
        echo ' nav-tab-active';
                
    }
    
}