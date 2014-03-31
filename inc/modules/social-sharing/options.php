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
        __('Settings', 'kbso'), // Section title
        '__return_false', // Section callback (we don't want anything)
        'kbso-sharing' // Menu slug
    );
    
    /**
     * Field - Share Links Label
     */
    add_settings_field(
        'social_sharing_label', // Unique identifier for the field for this section
        __('Share Label', 'kbso'), // Setting field label
        'kbso_options_render_text_input', // Function that renders the settings field
        'kbso-sharing', // Menu slug
        'kbso_social_sharing', // Settings section.
        array( // Args to pass to render function
            'name' => 'social_sharing_label',
            'help_text' => __('Text displayed before the Social Share buttons.', 'kbso')
        ) 
    );
    
    /**
     * Field - Social Sharing Theme
     */
    add_settings_field(
        'social_sharing_theme', // Unique identifier for the field for this section
        __('Theme', 'kbso'), // Setting field label
        'kbso_options_render_theme_dropdown', // Function that renders the settings field
        'kbso-sharing', // Menu slug
        'kbso_social_sharing', // Settings section.
        array( // Args to pass to render function
            'name' => 'social_sharing_theme',
            'help_text' => __('Choose the Theme used to display the share buttons.', 'kbso')
        ) 
    );
    
    /**
     * Field - Share Links Content
     */
    add_settings_field(
        'social_sharing_counts', // Unique identifier for the field for this section
        __('Social Count', 'kbso'), // Setting field label
        'kbso_options_render_switch', // Function that renders the settings field
        'kbso-sharing', // Menu slug
        'kbso_social_sharing', // Settings section.
        array( // Args to pass to render function
            'name' => 'social_sharing_counts',
            'id' => 'counts', // used to identify the switch in JS (optional)
            'help_text' => __('Display of social counts.', 'kbso')
        ) 
    );
    
    /**
     * Field - Share Links Content
     */
    add_settings_field(
        'social_sharing_link_size', // Unique identifier for the field for this section
        __('Button Size', 'kbso'), // Setting field label
        'kbso_options_render_link_size', // Function that renders the settings field
        'kbso-sharing', // Menu slug
        'kbso_social_sharing', // Settings section.
        array( // Args to pass to render function
            'name' => 'social_sharing_link_size',
            'help_text' => __('Controls the size of the Sharing buttons.', 'kbso')
        ) 
    );
    
    /**
     * Field - Social Sharing Position
     */
    add_settings_field(
        'social_sharing_position', // Unique identifier for the field for this section
        __('Position', 'kbso'), // Setting field label
        'kbso_options_render_position_checkboxes', // Function that renders the settings field
        'kbso-sharing', // Menu slug
        'kbso_social_sharing', // Settings section.
        array( // Args to pass to render function
            'name' => 'social_sharing_position',
            'help_text' => __('Choose where on the page to show the Share Buttons.', 'kbso')
        ) 
    );
    
    /**
     * Field - Social Sharing Post Types
     */
    add_settings_field(
        'social_sharing_post_types', // Unique identifier for the field for this section
        __('Post Types', 'kbso'), // Setting field label
        'kbso_options_render_post_type_checkboxes', // Function that renders the settings field
        'kbso-sharing', // Menu slug
        'kbso_social_sharing', // Settings section.
        array( // Args to pass to render function
            'name' => 'social_sharing_post_types',
            'help_text' => __('Which Post Types to display Share buttons for.', 'kbso')
        ) 
    );
    

    
}
add_action( 'admin_init', 'kbso_social_sharing_options_init' );

/*
 * Add Module Default Options
 */
function kbso_social_sharing_option_defaults( $defaults ) {
    
    $sharing = array(
        'social_sharing_label' => 'Share this:',
        'social_sharing_theme' => 'default',
        'social_sharing_position' => array( 'top', 'bottom' ),
        'social_sharing_counts' => 'yes',
        'social_sharing_link_size' => 'medium',
        'social_sharing_post_types' => array( 'post' ),
    );
    
    $options = wp_parse_args( $defaults, $sharing );
    
    return $options;
    
}
add_filter( 'kbso_get_plugin_options', 'kbso_social_sharing_option_defaults' );

/**
 * Returns an array of select inputs for the Theme dropdown.
 */
function kbso_options_theme_select_dropdown() {
    
    $dropdown = array(
        'default' => array(
            'value' => 'default',
            'label' => __('Default', 'kbso')
        ),
        'flat' => array(
            'value' => 'flat',
            'label' => __('Flat', 'kbso')
        ),
        'gradient' => array(
            'value' => 'gradient',
            'label' => __('Gradient', 'kbso')
        ),
    );

    return apply_filters( 'kbso_social_sharing_theme', $dropdown );
    
}

/**
 * Renders the Theme dropdown.
 */
function kbso_options_render_theme_dropdown( $args ) {
    
    $options = kbso_get_plugin_options();
    
    $name = esc_attr( $args['name'] );
    
    ?>
    <select id="<?php echo $name; ?>" name="kbso_plugin_options[<?php echo $name; ?>]">
    <?php
    foreach ( kbso_options_theme_select_dropdown() as $dropdown ) {
        
        ?>
        <option value="<?php echo esc_attr( $dropdown['value'] ); ?>" <?php selected( $dropdown['value'], $options[ $name ] ); ?>>
            <?php echo esc_html( $dropdown['label'] ); ?>
        </option>
        <?php
        
    }
    ?>
    </select>    
    <?php
        
}

/**
 * Returns an array of select inputs for the Theme dropdown.
 */
function kbso_options_position_dropdown() {
    
    $dropdown = array(
        'top' => array(
            'value' => 'top',
            'label' => __('Top', 'kbso')
        ),
        'bottom' => array(
            'value' => 'bottom',
            'label' => __('Bottom', 'kbso')
        ),
        'floating' => array(
            'value' => 'floating',
            'label' => __('Floating Bar', 'kbso')
        ),
    );

    return apply_filters( 'kbso_social_sharing_position', $dropdown );
    
}

/**
 * Renders the Post Type checkboxes.
 */
function kbso_options_render_position_checkboxes( $args ) {
    
    $options = kbso_get_plugin_options();
    
    $name = esc_attr( $args['name'] );
    
    $help_text = ( $args['help_text'] ) ? esc_html( $args['help_text'] ) : null;
    
    foreach ( kbso_options_position_dropdown() as $position ) {
        
        ?>
        <label for="<?php echo $name; ?>[<?php echo $position['value']; ?>]">
        <input type="checkbox" id="<?php echo $name; ?>[<?php echo $position['value']; ?>]" name="kbso_plugin_options[<?php echo $name; ?>][]" value="<?php echo $position['value']; ?>" <?php checked( true, in_array( $position['value'], $options[ $name ] ) ); ?> />
        <?php echo esc_html( $position['label'] ); ?>
        </label>
        <br>
        <?php
        
    }
    
    if ( $help_text ) { ?>
        <span class="howto"><?php echo esc_html( $help_text ); ?></span>
    <?php }
        
}

/**
 * Returns an array of radio options for Yes/No.
 */
function kbso_options_link_content_options() {
    
    $check_boxes = array(
        'icon' => array(
            'value' => 'icon',
            'label' => __('Icon', 'kbso')
        ),
        'name' => array(
            'value' => 'name',
            'label' => __('Name', 'kbso')
        ),
        'count' => array(
            'value' => 'count',
            'label' => __('Count', 'kbso')
        ),
    );

    return apply_filters( 'kbso_options_link_content_options', $check_boxes );
    
}

/**
 * Renders the radio options setting field.
 */
function kbso_options_render_link_content( $args ) {
    
    $options = kbso_get_plugin_options();
    
    $name = esc_attr( $args['name'] );
    
    $help_text = ( $args['help_text'] ) ? esc_html( $args['help_text'] ) : null;
    
    foreach ( kbso_options_link_content_options() as $checkbox ) {
        
        ?>
        <label for="<?php echo $name; ?>[<?php echo $checkbox['value']; ?>]">
        <input type="checkbox" id="<?php echo $name; ?>[<?php echo $checkbox['value']; ?>]" name="kbso_plugin_options[<?php echo $name; ?>][]" value="<?php echo $checkbox['value']; ?>" <?php checked( true, in_array( $checkbox['value'], $options[ $name ] ) ); ?> />
        <?php echo esc_html( $checkbox['label'] ); ?>
        </label>
        <br>
        <?php
        
    }
    if ( $help_text ) { ?>
        <span class="howto"><?php echo esc_html( $help_text ); ?></span>
    <?php }
    
}

/**
 * Returns an array of select inputs for the Theme dropdown.
 */
function kbso_options_link_size_options() {
    
    $dropdown = array(
        'xsmall' => array(
            'value' => 'xsmall',
            'label' => __('Extra Small', 'kbso')
        ),
        'small' => array(
            'value' => 'small',
            'label' => __('Small', 'kbso')
        ),
        'medium' => array(
            'value' => 'medium',
            'label' => __('Medium', 'kbso')
        ),
        'large' => array(
            'value' => 'large',
            'label' => __('Large', 'kbso')
        ),
        'xlarge' => array(
            'value' => 'xlarge',
            'label' => __('Extra Large', 'kbso')
        ),
    );

    return apply_filters( 'kbso_social_sharing_button_size', $dropdown );
    
}

/**
 * Renders the radio options setting field.
 */
function kbso_options_render_link_size( $args ) {
    
    $options = kbso_get_plugin_options();
    
    $name = esc_attr( $args['name'] );
    
    ?>
    <select id="<?php echo $name; ?>" name="kbso_plugin_options[<?php echo $name; ?>]">
    <?php
    foreach ( kbso_options_link_size_options() as $dropdown ) {
        
        ?>
        <option value="<?php echo esc_attr( $dropdown['value'] ); ?>" <?php selected( $dropdown['value'], $options[ $name ] ); ?>>
            <?php echo esc_html( $dropdown['label'] ); ?>
        </option>
        <?php
        
    }
    ?>
    </select>    
    <?php
    
}

/**
 * Renders the Post Type checkboxes.
 */
function kbso_options_render_post_type_checkboxes( $args ) {
    
    $options = kbso_get_plugin_options();
    
    $name = esc_attr( $args['name'] );
    
    $help_text = ( $args['help_text'] ) ? esc_html( $args['help_text'] ) : null;
    
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
    
    if ( $help_text ) { ?>
        <span class="howto"><?php echo esc_html( $help_text ); ?></span>
    <?php }
        
}

/**
 * Sanitize and validate options input. Accepts an array, return a sanitized array.
 */
function kbso_social_sharing_options_validate( $input, $output ) {
    
    if ( isset( $input['social_sharing_label'] ) && ! empty( $input['social_sharing_label'] ) ) {
	$output['social_sharing_label'] = sanitize_title( $input['social_sharing_label'] );
    }
    
    if ( isset( $input['social_sharing_position'] ) ) {
        $output['social_sharing_position'] = esc_html( $input['social_sharing_position'] );
    }
    
    if ( isset( $input['social_sharing_counts'] ) && array_key_exists( $input['social_sharing_counts'], kbso_options_radio_buttons() ) ) {
        $output['social_sharing_counts'] = $input['social_sharing_counts'];
    }
    
    if ( isset( $input['social_sharing_post_types'] ) ) {
        $output['social_sharing_post_types'] = esc_html( $input['social_sharing_post_types'] );
    }
    
    if ( isset( $input['social_sharing_theme'] ) && array_key_exists( $input['social_sharing_theme'], kbso_options_theme_select_dropdown() ) ) {
        $output['social_sharing_theme'] = $input['social_sharing_theme'];
    }
    
    return $output;
    
}
add_filter( 'kbso_plugin_options_validation', 'kbso_social_sharing_options_validate', 10, 2 );