<?php
/* 
 * Core Plugin Options
 */

if ( ! defined( 'KBSO_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/**
 * Register our core options.
 */
function kbso_plugin_options_init() {
    
    // Get Options
    $options = kbso_get_plugin_options();
    
    register_setting(
            'kbso_options', // Options group
            'kbso_plugin_options', // Database option
            'kbso_plugin_options_validate' // The sanitization callback,
    );

    /**
     * Section - Share Links
     */
    add_settings_section(
            'kbso_share_links_general', // Unique identifier for the settings section
            __('General Options', 'kbso'), // Section title
            '__return_false', // Section callback (we don't want anything)
            'kbso-sharing' // Menu slug
    );
    
    /**
     * Field - Activate Feature
     */
    add_settings_field(
            'share_links_activate_feature', // Unique identifier for the field for this section
            __('Activate Feature', 'kbso'), // Setting field label
            'kbso_options_render_switch', // Function that renders the settings field
            'kbso-sharing', // Menu slug
            'kbso_share_links_general', // Settings section.
            array( // Args to pass to render function
                'name' => 'share_links_activate_feature',
                'help_text' => __('Turns the feature on or off.', 'kbso')
            ) 
    );

}
add_action( 'admin_init', 'kbso_plugin_options_init' );

/**
 * Change the capability required to save the 'kbso_options' options group.
 */
function kbso_plugin_option_capability( $capability ) {
    
    return 'manage_options';
    
}
add_filter('option_page_capability_kbso_options', 'kbso_plugin_option_capability');

/**
 * Returns the options array for 'kbso_options'.
 */
function kbso_get_plugin_options() {
    
    $saved = (array) get_option( 'kbso_plugin_options' );
    
    $defaults = array(
        // Section - Share Links - General
        'share_links_activate_feature' => 'no',
        'share_links_intro_text' => null,
        // Section - Share Links - Visual
        'share_links_link_content' => array( 'icon', 'name', 'count' ),
        'share_links_theme' => 'default',
        'share_links_post_types' => array( 'post' ),
    );

    /*
     * Allow modules to add their own defaults
     */
    $defaults = apply_filters( 'kbso_get_plugin_options', $defaults );

    $options = wp_parse_args( $saved, $defaults );
    $options = array_intersect_key( $options, $defaults );

    return $options;
    
}

/**
 * Sanitize and validate options input. Accepts an array, return a sanitized array.
 */
function kbso_plugin_options_validate( $input ) {
    
    $options = kbso_get_plugin_options();
    
    $output = array();
    
    if ( isset( $input['share_links_activate_feature'] ) && array_key_exists( $input['share_links_activate_feature'], kbso_options_radio_buttons() ) ) {
        $output['share_links_activate_feature'] = $input['share_links_activate_feature'];
    }
    
    if ( isset( $input['share_links_intro_text'] ) && ! empty( $input['share_links_intro_text'] ) ) {
	$output['share_links_intro_text'] = sanitize_title( $input['share_links_intro_text'] );
    }
    
    if ( isset( $input['share_links_link_content'] ) && ! empty( $input['share_links_link_content'] ) ) {
        
        if ( in_array( 'icon', $input['share_links_link_content'] ) || in_array( 'name', $input['share_links_link_content'] ) ) {
            
            $output['share_links_link_content'] = $input['share_links_link_content'];
            
        } else {
            
            $input['share_links_link_content'][] = 'icon';
            
            $output['share_links_link_content'] = $input['share_links_link_content'];
            
            add_settings_error(
                'kbso-options-sharelinks',
                esc_attr( 'settings_updated' ),
                __('Link Content - You must select at least one from icon and name.', 'kbso'),
                'error'
            );
            
        }
        
    }
    
    if ( isset( $input['share_links_post_types'] ) ) {
        $output['share_links_post_types'] = esc_html( $input['share_links_post_types'] );
    }
    
    if ( isset( $input['share_links_theme'] ) && array_key_exists( $input['share_links_theme'], kbso_options_theme_select_dropdown() ) ) {
        $output['share_links_theme'] = $input['share_links_theme'];
    }
    
    /*
     * Allow modules to add their own validate functions
     */
    do_action( 'kbso_plugin_options_validation', $input, $output );
    
    // Combine Inputs with currently Saved data, for multiple option page compability
    $options = wp_parse_args( $input, $options );
    
    return apply_filters( 'kbso_plugin_options_validate', $options, $output );
    
}