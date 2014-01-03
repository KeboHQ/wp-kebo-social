<?php
/* 
 * Handle choosing which links to show and where.
 */

/**
 * Register plugin scripts and styles.
 */
function kbso_social_sharing_register_files() {

    // Register Styles
    wp_register_style( 'kbso-sharelinks-min', KBSO_URL . 'inc/modules/social-sharing/assets/css/sharelinks.min.css', array(), KBSO_VERSION, 'all' );
        
}
add_action( 'wp_enqueue_scripts', 'kbso_register_files' );
add_action( 'admin_enqueue_scripts', 'kbso_register_files' );

/**
 * Enqueue backend plugin scripts and styles.
 */
function kbso_social_sharing_enqueue_backend( $hook_suffix ) {
    
    // Enqueue files for sharing page
    if ( 'kebo-social_page_kbso-sharing' == $hook_suffix ) {
        
        wp_enqueue_style( 'kbso-sharelinks' );
            
    }
        
}
add_action( 'admin_enqueue_scripts', 'kbso_enqueue_backend' );

/*
 * Social Sharing Admin Button Preview
 */
function kbso_share_button_preview() {
    
    $all_links = array(
        'facebook' => array(
            'name' => 'facebook',
            'label' => 'Facebook',
            'href' => '#'
        ),
        'twitter' => array(
            'name' => 'twitter',
            'label' => 'Twitter',
            'href' => '#'
        ),
        'googleplus' => array(
            'name' => 'googleplus',
            'label' => 'Google+',
            'href' => '#'
        ),
        'linkedin' => array(
            'name' => 'linkedin',
            'label' => 'LinkedIn',
            'href' => '#'
        ),
        'pinterest' => array(
            'label' => 'Pinterest',
            'name' => 'pinterest',
            'href' => '#'
        ),
    );
    
    $selected_links = array();
    
    /*
     * Group the selected Social Links
     */
    foreach ( kbso_share_links_order('selected') as $link ) {
            
        if ( isset( $all_links[ $link ] ) ) {
                
            $selected_links[ $link ] = $all_links[ $link ];
                
        }
            
    }
    
    $options = kbso_get_plugin_options();

    $theme = $options['social_sharing_theme'];
    
    /*
     * All basic themes use the same view files.
     */
    $basic_themes = array( 'default', 'flat', 'gradient' );
    
    if ( in_array( $theme, $basic_themes ) ) {
        
        $theme_view = 'default';
        
    } else {
        
        $theme_view = $theme;
        
    }
    
    /**
     * Setup an instance of the View class.
     * Allow customization using a filter.
     */
    $view = new Kebo_View(
        apply_filters(
            'kbso_social_sharing_view_dir',
            KBSO_PATH . 'inc/modules/social-sharing/views/' . $theme_view
        )
    );
    
    /**
     * Prepare the HTML classes
     */
    $classes[] = 'ksharelinks';
    $classes[] = $options['social_sharing_theme'];
    $classes[] = $options['social_sharing_link_size'];
    if ( is_rtl() ) {
        $classes[] = 'rtl';
    }
    
    /*
     * Build HTML output
     */
    $preview_links = $view
        ->set_view( 'links' )
        ->set( 'classes', $classes )
        ->set( 'label', $options['social_sharing_label'] )
        ->set( 'link_content', $options['social_sharing_link_content'] )
        ->set( 'options', $options )
        ->set( 'links', $selected_links )
        ->set( 'view', $view )
        ->retrieve();
    
    unset( $view );
    
    return $preview_links;
    
}

/*
 * Social Sharing Admin Page JS
 */
function kbso_sharing_page_print_js() {
    
    ?>
    <script type="text/javascript">

        jQuery(document).ready(function() {

            jQuery("#share-links-available, #share-links-selected").sortable({
                connectWith: ".connectedSortable",
                placeholder: "sortable-placeholder",
                dropOnEmpty: true,
                start: function(event, ui) {

                    ui.placeholder.height(ui.helper.outerHeight() - 2);
                    ui.placeholder.width(ui.helper.outerWidth() - 2);

                },
                update: function(event, ui) {

                    // do AJAX config save
                    var korder = new Array;

                    jQuery('#share-links-selected .sortable').delay(500).each(function(index) {

                        var kservice = jQuery(this).data('service');

                        // Add data to array
                        korder.push(kservice);

                    });

                    var data = {
                        action: 'kbso_save_sharelink_order',
                        data: korder,
                        nonce: '<?php echo wp_create_nonce('kbso_sharelink_order'); ?>'
                    };

                    // do AJAX update
                    jQuery.post(ajaxurl, data, function(response) {

                        response = jQuery.parseJSON(response);

                        if ('true' === response.success && 'save' === response.action && window.console) {
                            console.log('Kebo Social - Share Link order successfully saved.');
                        }

                    });

                }

            }).disableSelection();

        });

    </script>
    <?php
    
}