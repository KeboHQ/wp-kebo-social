<?php
/* 
 * Post Meta for Post Sharing module.
 */

if ( ! defined( 'KBSO_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/**
 * Add Post Meta Box if relevant Post Type.
 */
function kbso_post_sharing_add_meta_box( $post_type, $post ) {

    $options = kbso_get_plugin_options();
    
    /*
     * Check Post Type
     */
    if ( in_array( $post_type, $options['post_sharing_post_types'] ) ) {
        
        add_meta_box(
            'kbso_post_sharing_meta', // HTML ID
            __( 'Post Sharing Options', 'kbso'), // Title
            'kbso_post_sharing_render_meta_box', // Callback
            $post_type, // Post Type
            'side', // Context - 'normal', 'advanced', or 'side'
            'default' // Priority - 'high', 'core', 'default' or 'low'
        );
        
    }
    
}
add_action( 'add_meta_boxes', 'kbso_post_sharing_add_meta_box', 10, 2 );

/**
 * Render the Post Meta Box
 */
function kbso_post_sharing_render_meta_box( $post ) {

    /*
     * Fetch our post meta
     */
    $meta = kbso_get_post_sharing_meta( $post->ID );
    
    $post_labels = get_post_type_object( $post->post_type );
    
    // Begin Output Buffering
    ob_start();
    ?>

    <div class="misc-pub-section">
        <label for="kbso_post_sharing_url">
            <?php _e( 'Custom Share URL', 'kbso' ); ?>
            <input type="text" id="kbso_post_sharing_url" name="kbso_post_sharing_url" value="<?php echo esc_attr( $meta['kbso_post_sharing_url'] ); ?>" style="width: 100%;">
        </label>
        <p class="description"><?php esc_html_e( 'Enter a custom share URL here.', 'kbso' ); ?></p>
    </div>

    <div class="misc-pub-section">
        <label for="kbso_post_sharing_disable">
            <input type="checkbox" id="kbso_post_sharing_disable" name="kbso_post_sharing_disable" value="true" <?php checked( $meta['kbso_post_sharing_disable'], true ); ?>>
            <?php _e( 'Disable Share Buttons', 'kbso' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'Prevent the Share Buttons showing on this ' . $post_labels->labels->singular_name . '.', 'kbso' ); ?></p>
    </div>
    
    <?php wp_nonce_field( 'kbso_post_sharing_meta', 'kbso-post-sharing-meta' ); ?>
    
    <?php
    
    // End Output Buffering and Clear Buffer
    $output = ob_get_contents();
    ob_end_clean();
    
    echo apply_filters( '_kbso_post_sharing_meta', $output );
    
}

/**
 * Save Post Meta
 */
function kbso_post_sharing_save_meta( $post_id, $post ) {
    
    $options = kbso_get_plugin_options();
    
    if ( ! in_array( $post->post_type, $options['post_sharing_post_types'] ) ) {
        return;
    }
    
    if ( ! wp_verify_nonce( $_POST['kbso-post-sharing-meta'], 'kbso_post_sharing_meta' ) ) {
        return;
    }
    
    $meta = array();
    
    /*
     * Validate the URL option
     * 
     * TODO: Need to handle Permalink setting changes.
     */
    if ( isset( $_POST['kbso_post_sharing_url'] ) && ! empty( $_POST['kbso_post_sharing_url'] ) ) {
        
        $permalink = get_permalink( $post_id );
        
        // Commented as not as good as the custom validation function below
        //$url = filter_input( INPUT_POST, 'kbso_post_sharing_url', FILTER_VALIDATE_URL );
        $url = $_POST['kbso_post_sharing_url'];
        
        if ( $url !== $permalink ) {
                
            // An External URL so validate and use it
            $url = kbso_validate_url( $url, true );
                
            if ( false !== $url ) {
                    
                $meta['kbso_post_sharing_url'] = $url;
                    
            }
            
        } else {
            
            $meta['kbso_post_sharing_url'] = '';
            
        }
                    
    } else {
        
        $meta['kbso_post_sharing_url'] = '';
        
    }
    
    /*
     * Validate the Disable option
     */
    if ( isset( $_POST['kbso_post_sharing_disable'] ) && ! empty( $_POST['kbso_post_sharing_disable'] ) ) {
        
        $disable = filter_input( INPUT_POST, 'kbso_post_sharing_disable', FILTER_VALIDATE_BOOLEAN );
        
        $meta['kbso_post_sharing_disable'] = $disable;
        
    }
    
    if ( ! update_post_meta ( $post_id, '_kbso_post_sharing_meta', $meta ) ) {
        
        add_post_meta( $post_id, '_kbso_post_sharing_meta', $meta );
        
    }
    
}
add_action( 'save_post', 'kbso_post_sharing_save_meta', 10, 2 );

/**
 * Fetch post meta and use defaults if not found
 * 
 * @param type $post_id
 * @return type
 */
function kbso_get_post_sharing_meta( $post_id ) {
    
    $custom_meta = get_post_meta( $post_id, '_kbso_post_sharing_meta', true );
    
    $meta = array();
    $meta['kbso_post_sharing_url'] = ( isset( $custom_meta['kbso_post_sharing_url'] ) ) ? $custom_meta['kbso_post_sharing_url'] : '' ;
    $meta['kbso_post_sharing_disable'] = ( isset( $custom_meta['kbso_post_sharing_disable'] ) ) ? $custom_meta['kbso_post_sharing_disable'] : false ;
    
    return apply_filters( 'kbso_get_post_sharing_meta', $meta, $post_id );
    
}

function kbso_post_url_check( $url, $post_id ) {
    
    
    
}

/**
 * Hook into Post Save Function
 * 
 * Ensures we only run the save function on the relevant Post Types
 */
function kbso_post_sharing_meta_hook() {
    
    $options = kbso_get_plugin_options();
    
    foreach ( $options['post_sharing_post_types'] as $post_type ) {
        
        add_action( 'save_post_' . $post_type, 'kbso_post_sharing_save_meta', 10, 2 );
        
    }
    
}
add_action( 'init', 'kbso_post_sharing_meta_hook' );