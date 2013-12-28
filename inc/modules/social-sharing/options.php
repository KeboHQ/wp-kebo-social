<?php
/* 
 * Adds Module related options to the Plugin.
 */

if ( ! defined( 'KBSO_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/*
 * Register Module Setting Section and Fields.
 */
function kbso_social_sharing_options_init() {
    
    /**
     * Section - Social Sharing
     */
    add_settings_section(
        'kbso_social_sharing', // Unique identifier for the settings section
        __('Social Sharing', 'kbso'), // Section title
        '__return_false', // Section callback (we don't want anything)
        'kbso-sharing' // Menu slug
    );
    
    /**
     * Field - Share Links Label
     */
    add_settings_field(
        'social_sharing_label', // Unique identifier for the field for this section
        __('Sharing Label', 'kbso'), // Setting field label
        'kbso_options_render_text_input', // Function that renders the settings field
        'kbso-sharing', // Menu slug
        'kbso_social_sharing', // Settings section.
        array( // Args to pass to render function
            'name' => 'social_sharing_label',
            'help_text' => __('Text displayed before the Social Sharing links.', 'kbso')
        ) 
    );
    
    /**
     * Field - Social Sharing Theme
     */
    add_settings_field(
        'social_sharing_theme', // Unique identifier for the field for this section
        __('Theme', 'kbso'), // Setting field label
        'kbso_options_render_switch', // Function that renders the settings field
        'kbso-sharing', // Menu slug
        'kbso_social_sharing', // Settings section.
        array( // Args to pass to render function
            'name' => 'social_sharing_theme',
            'help_text' => __('Choose the Theme used to display the share links.', 'kbso')
        ) 
    );
    
    /**
     * Field - Share Links Content
     */
    add_settings_field(
        'social_sharing_link_content', // Unique identifier for the field for this section
        __('Social Sharing', 'kbso'), // Setting field label
        'kbso_options_render_switch', // Function that renders the settings field
        'kbso-sharing', // Menu slug
        'kbso_social_sharing', // Settings section.
        array( // Args to pass to render function
            'name' => 'social_sharing_link_content',
            'help_text' => __('Controls the content inside the Sharing links.', 'kbso')
        ) 
    );
    
    /**
     * Field - Social Sharing Post Types
     */
    add_settings_field(
        'social_sharing_post_types', // Unique identifier for the field for this section
        __('Social Sharing', 'kbso'), // Setting field label
        'kbso_options_render_post_type_checkboxes', // Function that renders the settings field
        'kbso-sharing', // Menu slug
        'kbso_social_sharing', // Settings section.
        array( // Args to pass to render function
            'name' => 'social_sharing_post_types',
            'help_text' => __('Which Post Types to display Social Share links for.', 'kbso')
        ) 
    );
    

    
}
add_action( 'admin_init', 'kbso_social_sharing_options_init' );

/*
 * 
 */
function kbso_social_sharing_option_defaults( $defaults ) {
    
    $sharing = array(
        'social_sharing_label' => '',
        'social_sharing_link_content' => array( 'icon', 'name', 'count' ),
        'social_sharing_post_types' => array( 'post' ),
    );
    
    $options = wp_parse_args( $defaults, $sharing );
    
    return $options;
    
}
add_filter( 'kbso_get_plugin_options', 'kbso_social_sharing_option_defaults' );

/**
 * Renders the Post Type checkboxes.
 */
function kbso_options_render_post_type_checkboxes( $args ) {
    
    $options = kbso_get_plugin_options();
    
    $name = esc_attr( $args['name'] );
    
    $args = array(
        'public' => true,
    );
    $post_types = get_post_types( $args, 'objects' );
    
    foreach ( $post_types as $post_type ) {
        
        ?>
        <label for="<?php echo $name; ?>[<?php echo $post_type->name; ?>]">
        <input type="checkbox" id="<?php echo $name; ?>[<?php echo $post_type->name; ?>]" name="kbso_plugin_options[<?php echo $name; ?>][]" value="<?php echo $post_type->name; ?>" <?php checked( true, in_array( $post_type->name, $options[ $name ] ) ); ?> />
        <?php echo esc_html( $post_type->labels->name ); ?>
        </label>
        <br>
        <?php
        
    }
        
}