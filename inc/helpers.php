<?php
/*
 * General Helper Functions
 */

if ( ! defined( 'KBSO_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/**
 * Get Plugin Capability
 */
function kbso_get_plugin_capability() {
    
    return apply_filters( 'kbso_get_plugin_capability', 'manage_posts' );
    
}

/**
 * Validate URLs
 * 
 * @param string $url
 * @param boolean $strict
 * @return string|boolean
 */
function kbso_validate_url( $url, $strict = false ) {
    
    // No URL to Validate
    if ( ! isset( $url ) || empty( $url ) ) {
        return false;
    }
    
    // Check for scheme e.g. http://
    $scheme = parse_url( $url, PHP_URL_SCHEME );
    
    // Add scheme if missing
    if ( empty( $scheme ) ) {
        $url = 'http://' . $url;
    }
    
    // Check for a valid URL
    if ( ! filter_var( $url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED ) ) {
        return false;
    }
    
    // If no strict check, return valid URL
    if ( true != $strict ) {
        return $url;
    }
    
    // Prepare HTTP Request Args
    $args = array(
        'timeout' => 5,
        'sslverify' => false,
    );
    
    $max_attempts = apply_filters( 'kbso_validate_url_attempts', 3 );
    
    $attempts = 0;
    
    $code = 0;
    
    /*
     * Repeat until max attempts is reached or code is 200
     */
    while ( $attempts <= $max_attempts && 200 != $code ) {
        
        // Make GET HTTP Request
        $response = wp_remote_get( $url, $args );

        // Fetch the Response Code - Should be 200
        $code = wp_remote_retrieve_response_code( $response );
        
        $attempts++;
        
    }
    
    /*
     * Check the Response Code
     */
    if ( 200 == $code ) {
        
        return $url;
        
    } else {
        
        return false;
        
    }
    
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