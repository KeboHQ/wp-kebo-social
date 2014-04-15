<?php
/* 
 * Handle choosing which links to show and where.
 */

/**
 * Register plugin scripts and styles.
 */
function kbso_post_sharing_register_files() {

    // Register Styles
    wp_register_style( 'kbso-post-sharing', KBSO_POST_SHARING_URL . 'assets/css/sharelinks.css', array(), KBSO_VERSION, 'all' );
    wp_register_style( 'kbso-post-sharing-min', KBSO_POST_SHARING_URL . 'assets/css/sharelinks.min.css', array(), KBSO_VERSION, 'all' );
        
}
add_action( 'wp_enqueue_scripts', 'kbso_post_sharing_register_files' );
add_action( 'admin_enqueue_scripts', 'kbso_post_sharing_register_files' );

/**
 * Enqueue backend plugin scripts and styles.
 */
function kbso_post_sharing_enqueue_backend( $hook_suffix ) {
    
    // Enqueue files for sharing page
    if ( 'settings_page_kebo-social' == $hook_suffix ) {
        
        /*
         * Use minified files where available, unless SCRIPT_DEBUG is true
         */
        if ( true == SCRIPT_DEBUG ) {
            
            wp_enqueue_style( 'kbso-admin' );
            wp_enqueue_style( 'kbso-post-sharing' );
            
        } else {
            
            wp_enqueue_style( 'kbso-admin-min' );
            wp_enqueue_style( 'kbso-post-sharing-min' );
            
        }
        
        wp_enqueue_script( 'jquery-ui-touchpunch' ); // depends on jquery-ui-sortable
            
    }
        
}
add_action( 'admin_enqueue_scripts', 'kbso_post_sharing_enqueue_backend' );

/**
 * Enqueue frontend plugin scripts and styles.
 */
function kbso_post_sharing_enqueue_frontend() {
        
    /*
     * Use minified files where available, unless SCRIPT_DEBUG is true
     */
    if ( false == SCRIPT_DEBUG ) {
            
        wp_enqueue_style( 'kbso-post-sharing-min' );
            
    } else {
            
        wp_enqueue_style( 'kbso-post-sharing' );
            
    }
        
}

/**
 * Makes the floating bar responsive.
 */
function kbso_post_sharing_responsive_compat() {
    
    $options = kbso_get_plugin_options();
    
    /*
     * Themes to make responsive
     */
    $themes = array(
        'default',
        'plain',
        'gradient'
    );
    
    /*
     * Only use for relevant themes
     */
    if ( ! in_array( $options['post_sharing_theme'], $themes ) ) {
        return;
    }
    
    $max_site_width = kbso_get_max_site_width();
        
    ?>
    <style type="text/css" media="screen">
        @media ( min-width: <?php echo absint( $max_site_width ) . 'px'; ?> ) {

            .kfloating.plain,
            .kfloating.gradient {
                background: #fff;
                border: 1px solid #ddd;
                border-left: none;
                top: 20%;
                bottom: auto;
                width: auto;
                padding: 0.6em;
                border-top-right-radius: 3px;
                border-bottom-right-radius: 3px;
                z-index: 99999;
            }
            .kfloating.plain .ksharelinks.plain li,
            .kfloating.gradient .ksharelinks.gradient li {
                clear: both;
                float: left;
                margin: 0 0 0.6em 0;
                width: 100%;
            }
            .kfloating.plain .ksharelinks.plain li:last-of-type,
            .kfloating.gradient .ksharelinks.gradient li:last-of-type {
                margin: 0;
            }
            .kfloating.plain .ksharelinks.plain .klink,
            .kfloating.gradient .ksharelinks.gradient .klink {
                display: block;
                width: 4em;
            }
            .kfloating.plain .ksharelinks.plain .kcount,
            .kfloating.gradient .ksharelinks.gradient .kcount {
                display: block;
                margin: 0.7em 0 0 0;
                width: 4em;
            }
            body {
                margin-bottom: 0px !important;
            }

        }
    </style>
    <?php
    
}

/**
 * Include Social Sharing Links on Frontend
 */
function kbso_post_sharing_content_insert( $content ) {
    
    global $post;

    $options = kbso_get_plugin_options();

    if ( is_singular() && is_main_query() && in_array( $post->post_type, $options['post_sharing_post_types'] ) ) {
        
        // Go ahead and add share links
        add_action( 'wp_footer', 'kbso_post_sharing_frontend_js_print' );
        
        /**
         * Add to top of content
         */
        if ( in_array( 'top', $options['post_sharing_position'] ) ) {
            
            $content = kbso_post_sharing_services_render() . $content;
            
        }
        
        /**
         * Add to bottom of post content
         */
        if ( in_array( 'bottom', $options['post_sharing_position'] ) ) {
            
            $content = $content . kbso_post_sharing_services_render();
            
        }
        
        /**
         * Add to the footer
         */
        if ( in_array( 'floating', $options['post_sharing_position'] ) ) {
            
            add_action( 'wp_footer', 'kbso_post_sharing_floating_bar_render' );
            add_action( 'wp_footer', 'kbso_post_sharing_responsive_compat' );
            
        }
        
        // Decide if we need to refresh counts
        kbso_maybe_refresh_counts( $post->ID );
        
    }
    
    return $content;
    
}

/*
 * Render the Floating Bar in the Footer
 */
function kbso_post_sharing_floating_bar_render() {
    
    $options = kbso_get_plugin_options();
    
    $classes = array(
        'kfloating',
        $options['post_sharing_theme'],
    );
    
    echo '<div class="' . implode( ' ', $classes ) . '">' . kbso_post_sharing_services_render() . '</div>';
    
}

/*
 * Get Social Sharing Services
 */
function kbso_get_post_sharing_services() {
    
    $services = array(
        'buffer' => array(
            'name' => 'buffer',
            'label' => __( 'Buffer', 'kbso' ),
            'href' => '#'
        ),
        'delicious' => array(
            'name' => 'delicious',
            'label' => __( 'Delicious', 'kbso' ),
            'href' => '#'
        ),
        'digg' => array(
            'name' => 'digg',
            'label' => __( 'Digg', 'kbso' ),
            'href' => '#'
        ),
        'facebook' => array(
            'name' => 'facebook',
            'label' => __( 'Facebook', 'kbso' ),
            'href' => '#'
        ),
        'googleplus' => array(
            'name' => 'googleplus',
            'label' => __( 'Google+', 'kbso' ),
            'href' => '#'
        ),
        'linkedin' => array(
            'name' => 'linkedin',
            'label' => __( 'LinkedIn', 'kbso' ),
            'href' => '#'
        ),
        'pinterest' => array(
            'name' => 'pinterest',
            'label' => __( 'Pinterest', 'kbso' ),
            'href' => '#'
        ),
        'reddit' => array(
            'name' => 'reddit',
            'label' => __( 'Reddit', 'kbso' ),
            'href' => '#'
        ),
        'stumbleupon' => array(
            'name' => 'stumbleupon',
            'label' => __( 'Stumbleupon', 'kbso' ),
            'href' => '#'
        ),
        'tumblr' => array(
            'name' => 'tumblr',
            'label' => __( 'Tumblr', 'kbso' ),
            'href' => '#'
        ),
        'twitter' => array(
            'name' => 'twitter',
            'label' => __( 'Twitter', 'kbso' ),
            'href' => '#'
        ),
    );
    
    /**
     * Use this filter to add more services.
     */
    return apply_filters( 'kbso_post_sharing_services', $services );
    
}

/**
 * Prepare Social Sharing Links
 */
function kbso_post_sharing_filter_services( $type = 'selected' ) {
    
    /*
     * Get Social Services
     */
    $all_services = kbso_get_post_sharing_services();
    
    /*
     * Get the user selected services
     */
    $user_selected = get_option( 'kbso_post_sharing_order' );
    
    /*
     * Prepare array for selected services
     */
    $selected = array();
    
    /*
     * If we are previewing return all items.
     * 
     * The preview will be controlled via javascript.
     */
    if ( 'preview' == $type ) {
        
        return $all_services;
        
    }
    
    /*
     * Return services the user Selected
     */
    if ( 'selected' == $type ) {
        
        if ( is_array( $user_selected ) ) {

            /*
             * Loop services and return selected.
             */
            foreach ( $user_selected as $selection ) {

                if ( isset( $all_services[ $selection ] ) ) {

                    $selected[ $selection ] = $all_services[ $selection ]; 

                }

            }
        
        } else {
            
            return array();
            
        }
        
        return $selected;
        
    }
    
    /*
     * Return services not selected by user
     */
    else {
        
        if ( empty( $user_selected ) ) {
        
            return $all_services;
        
        } else {
            
            /*
             * Loop services and return those not selected.
             */
            foreach ( $all_services as $service ) {

                if ( ! in_array( $service['name'], $user_selected ) ) {

                    $remaining[] = $service; 

                }

            }
            
        }
        
        return $remaining;
        
    }
    
}

/*
 * Prepare Social Sharing Links
 */
function kbso_post_sharing_prepare_links() {
    
    global $post;
    
    /*
     * If we somehow don't have a Post Object, then return
     * Unless we are in admin, then this is probably a preview
     */
    if ( ! $post instanceof WP_Post && ! is_admin() ) {
        return false;
    }
    
    /*
     * Get the selected services
     */
    $services = kbso_post_sharing_filter_services( 'selected' );
    
    /*
     * Check we have an array as expected
     */
    if ( empty( $services ) || ! is_array( $services ) ) {
        
        return false;
        
    }
    
    /*
     * If we are in the admin, its just a preview. No need for proper HREFs.
     * Else pass through the filter so HREFs can be added.
     */
    return ( is_admin() ) ? $services : apply_filters( 'kbso_post_sharing_prepare_link', $services ) ;
    
}

/*
 * Social Sharing Buffer Link Setup
 */
function kbso_post_sharing_buffer_href( $services ) {
    
    global $post;
    
    if ( isset( $services['buffer'] ) ) {
            
        $services['buffer']['href'] = esc_url( 'http://bufferapp.com/add?&text=' . urlencode( html_entity_decode( get_the_title() ) ) . '&url=' . urlencode( get_permalink() ) . '' );
            
    }
    
    return $services;
    
}
add_filter( 'kbso_post_sharing_prepare_link', 'kbso_post_sharing_buffer_href' );

/*
 * Social Sharing Delicious Link Setup
 */
function kbso_post_sharing_delicious_href( $services ) {
    
    global $post;
    
    if ( isset( $services['delicious'] ) ) {
            
        $services['delicious']['href'] = esc_url( 'https://delicious.com/save?v=5&noui&jump=close&url=' . urlencode( get_permalink() ) . '&title=' . urlencode( html_entity_decode( get_the_title() ) ) . '' );
            
    }
    
    return $services;
    
}
add_filter( 'kbso_post_sharing_prepare_link', 'kbso_post_sharing_delicious_href' );

/*
 * Social Sharing Digg Link Setup
 */
function kbso_post_sharing_digg_href( $services ) {
    
    global $post;
    
    if ( isset( $services['digg'] ) ) {
            
        $services['digg']['href'] = esc_url( 'http://digg.com/submit?url=' . urlencode( get_permalink() ) . '&title=' . urlencode( html_entity_decode( get_the_title() ) ) . '' );
            
    }
    
    return $services;
    
}
add_filter( 'kbso_post_sharing_prepare_link', 'kbso_post_sharing_digg_href' );

/*
 * Social Sharing Facebook Link Setup
 */
function kbso_post_sharing_facebook_href( $services ) {
    
    global $post;
    
    if ( isset( $services['facebook'] ) ) {
            
        $services['facebook']['href'] = esc_url( 'http://www.facebook.com/sharer.php?u=' . rawurlencode( get_permalink() ) . '&t=' . rawurlencode( html_entity_decode( get_the_title() ) ) . '' );
            
    }
    
    return $services;
    
}
add_filter( 'kbso_post_sharing_prepare_link', 'kbso_post_sharing_facebook_href' );

/*
 * Social Sharing Google+ Link Setup
 */
function kbso_post_sharing_googleplus_href( $services ) {
    
    global $post;
    
    if ( isset( $services['googleplus'] ) ) {
            
        $services['googleplus']['href'] = esc_url( 'https://plus.google.com/share?url=' . rawurlencode( get_permalink() ) . '' );
            
    }
    
    return $services;
    
}
add_filter( 'kbso_post_sharing_prepare_link', 'kbso_post_sharing_googleplus_href' );

/*
 * Social Sharing LinkedIn Link Setup
 */
function kbso_post_sharing_linkedin_href( $services ) {
    
    global $post;
    
    $summary = wp_trim_words( strip_tags( get_the_content( $post->ID ) ), 50);
    
    if ( isset( $services['linkedin'] ) ) {
            
        $services['linkedin']['href'] = esc_url( 'http://www.linkedin.com/shareArticle?mini=true&url=' . rawurlencode( get_permalink() ) . '&title=' . rawurlencode( html_entity_decode( get_the_title() ) ) . '&summary=' . urlencode( $summary ) . '&source=' . urlencode( get_bloginfo( 'name' ) ) . '' );
            
    }
    
    return $services;
    
}
add_filter( 'kbso_post_sharing_prepare_link', 'kbso_post_sharing_linkedin_href' );

/*
 * Social Sharing Pinterest Link Setup
 */
function kbso_post_sharing_pinterest_href( $services ) {
    
    global $post;
    
    if ( isset( $services['pinterest'] ) ) {
        
        /*
         * Pinterest requires an image so check we have one, else remove the service.
         * TODO: Add video support.
         */
        if ( has_post_thumbnail( $post->ID ) ) {
            
            $featured_image = wp_prepare_attachment_for_js( get_post_thumbnail_id( $post->ID ) );
            
            /*
             * Get the largest image we can
             */
            if ( isset( $featured_image['sizes']['large'] ) ) {
                
                $featured_size = $featured_image['sizes']['large'];
                
            } elseif ( $featured_image['sizes']['medium'] ) {
                
                $featured_size = $featured_image['sizes']['medium'];
                
            } else {
                
                $featured_size = $featured_image['sizes']['thumbnail'];
                
            }
            
            /*
             * Check we have an image with the required dimensions for Pinterest
             * Currently 80 x 80
             */
            if ( 80 < $featured_size['width'] || 80 < $featured_size['height'] ) {
            
                $featured_src = $featured_image['sizes']['large']['url'];

                // Pinterest allows upto 500 characters for the description parameter.
                $summary = wp_trim_words( get_the_content( $post->ID ), 50);

                $description = ( ! empty( $summary ) ) ? $summary : $featured_image['alt'] ;

                $services['pinterest']['href'] = esc_url( 'http://pinterest.com/pin/create/button/?url=' . rawurlencode( get_permalink() ) . '&media=' . rawurlencode( $featured_src ) . '&description=' . rawurlencode( $description ) . '&is_video=false' );
            
            } else {
                
                unset( $services['pinterest'] );
                
            }
            
        } else {
            
            unset( $services['pinterest'] );
            
        }
        
    }
    
    return $services;
    
}
add_filter( 'kbso_post_sharing_prepare_link', 'kbso_post_sharing_pinterest_href' );

/*
 * Social Sharing Reddit Link Setup
 */
function kbso_post_sharing_reddit_href( $services ) {
    
    global $post;
    
    if ( isset( $services['reddit'] ) ) {
            
        $services['reddit']['href'] = esc_url( 'http://www.reddit.com/submit?title=' . rawurlencode( html_entity_decode( get_the_title() ) ) . '&url=' . rawurlencode( get_permalink() ) . '' );
            
    }
    
    return $services;
    
}
add_filter( 'kbso_post_sharing_prepare_link', 'kbso_post_sharing_reddit_href' );

/*
 * Social Sharing Stumbleupon Link Setup
 */
function kbso_post_sharing_stumbleupon_href( $services ) {
    
    global $post;
    
    if ( isset( $services['stumbleupon'] ) ) {
            
        $services['stumbleupon']['href'] = esc_url( 'http://www.stumbleupon.com/submit?url=' . rawurlencode( get_permalink() ) . '&title=' . rawurlencode( html_entity_decode( get_the_title() ) ) . '' );
            
    }
    
    return $services;
    
}
add_filter( 'kbso_post_sharing_prepare_link', 'kbso_post_sharing_stumbleupon_href' );

/*
 * Social Sharing Tumblr Link Setup
 */
function kbso_post_sharing_tumblr_href( $services ) {
    
    global $post;
    
    $summary = wp_trim_words( strip_tags( get_the_content( $post->ID ) ), 50);
    
    if ( isset( $services['tumblr'] ) ) {
            
        $services['tumblr']['href'] = esc_url( 'https://www.tumblr.com/share/link?url=' . rawurlencode( get_permalink() ) . '&name=' . rawurlencode( html_entity_decode( get_the_title() ) ) . '&description=' . rawurlencode( $summary ) . '' );
            
    }
    
    return $services;
    
}
add_filter( 'kbso_post_sharing_prepare_link', 'kbso_post_sharing_tumblr_href' );

/*
 * Social Sharing Twitter Link Setup
 */
function kbso_post_sharing_twitter_href( $services ) {
    
    global $post;
    
    if ( isset( $services['twitter'] ) ) {
            
        $services['twitter']['href'] = esc_url( 'http://twitter.com/share?text=' . rawurlencode( html_entity_decode( get_the_title() ) ) . '&url=' . rawurlencode( get_permalink() ) . '' );
            
    }
    
    return $services;
    
}
add_filter( 'kbso_post_sharing_prepare_link', 'kbso_post_sharing_twitter_href' );

/**
 * Renders the Social Share Buttons.
 */
function kbso_post_sharing_services_render( $preview = false ) {
    
    global $post;

    $options = kbso_get_plugin_options();

    $theme = $options['post_sharing_theme'];
    
    $theme_view = apply_filters( 'kbso_post_sharing_view_dir', 'default', $theme );
    
    /**
     * Setup an instance of the View class.
     * Allow customization using a filter.
     */
    $view = new Kebo_View(
        apply_filters(
            'kbso_post_sharing_view_dir',
            KBSO_PATH . 'inc/modules/post-sharing/views/' . $theme_view,
            $theme
        )
    );
    
    /*
     * Get the services selected by the user or all services for previews
     */
    if ( $preview ) {
        
        $selected = kbso_post_sharing_filter_services( 'preview' );
        
    } else {
        
        $selected = kbso_post_sharing_prepare_links();
        
    }
    
    /*
     * If we have no services, don't display.
     */
    if ( empty( $selected ) ) {
        return;
    }
    
    /**
     * Prepare the HTML classes
     */
    $classes[] = 'ksharelinks';
    $classes[] = $options['post_sharing_theme'];
    $classes[] = $options['post_sharing_size'];
    if ( is_rtl() ) {
        $classes[] = 'rtl';
    }
    
    /*
     * Get Share Counts
     */
    $counts = get_post_meta( $post->ID, '_kbso_post_sharing_counts', true );
    
    /*
     * Build HTML output
     */
    $links = $view
        ->set_view( 'links' )
        ->set( 'classes', $classes )
        ->set( 'label', $options['post_sharing_label'] )
        ->set( 'post_type', $post->post_type )
        ->set( 'options', $options )
        ->set( 'permalink', get_permalink() )
        ->set( 'title', get_the_title() )
        ->set( 'links', $selected )
        ->set( 'counts', $counts )
        ->set( 'view', $view )
        ->retrieve();
    
    unset( $view );
    
    return $links;
    
}

/*
 * Filters Social Sharing view dirctory
 * TODO: Move into example code.
 */
function kbso_post_sharing_standard_view_dir( $theme_view, $theme ) {
    
    if ( 'custom_theme' == $theme ) {
        
        $theme_view = 'custom_view';
        
    }
    
    return $theme_view;
    
}
add_filter( 'kbso_post_sharing_view_dir', 'kbso_post_sharing_standard_view_dir', 10, 2 );

/*
 * Social Sharing Admin Page JS
 */
function kbso_post_sharing_page_print_js() {
    
    ?>
    <script type="text/javascript">

        jQuery(document).ready(function($) {
            
            $( '.ksharelinks ul li a' ).click( function( e ) {

                // Prevent Click from Reloading page
                e.preventDefault();

            });

            $("#share-links-available, #share-links-selected").sortable({
                
                connectWith: ".connectedSortable",
                placeholder: "sortable-placeholder",
                dropOnEmpty: true,
                start: function( event, ui ) {

                    ui.placeholder.height(ui.helper.outerHeight() - 2);
                    ui.placeholder.width(ui.helper.outerWidth() - 2);

                },
                update: function( event, ui ) {

                    // do AJAX config save
                    var korder = new Array;

                    $( '#share-links-selected .sortable' ).delay( 500 ).each( function( index ) {

                        var kservice = $(this).data( 'service' );

                        // Add data to array
                        korder.push( kservice );

                    });

                    var data = {
                        action: 'kbso_save_post_sharing_order',
                        data: korder,
                        nonce: '<?php echo wp_create_nonce('kbso_post_sharing_order'); ?>'
                    };

                    // do AJAX update
                    $.post( ajaxurl, data, function( response ) {

                        response = $.parseJSON( response );

                        if ( 'true' === response.success && 'save' === response.action && window.console ) {
                            console.log( 'Kebo Social - Post Sharing order successfully saved.' );
                            
                            $( '.ksharelinks .klink' ).each( function() {

                                $(this).parent().css('display', 'none');

                            });

                            $.each( korder, function( index, item ) {

                                $( '.ksharelinks .klink.' + item ).parent().css('display', 'inline-block');

                            });
                            
                            var krevorder = korder.reverse();
                            
                            var kul = $('.ksharelinks ul');
                            
                            $.each( krevorder, function( index, item ) {
                                
                                var ksort = $( '.ksharelinks .klink.' + item ).parent().detach();
                                
                                kul.prepend( ksort );
                                
                            });
                            
                        }

                    });

                }

            }).disableSelection();

        });

    </script>
    <?php
    
}

/**
 * Outputs Social Sharing Frontend Javascript
 */
function kbso_post_sharing_frontend_js_print() {
    
    // Begin Output Buffering
    ob_start();
    
    ?>
    <script type="text/javascript">
        
        jQuery(document).ready( function($) {
            
            $( '.ksharelinks ul li a' ).click( function( e ) {

                // Prevent Click from Reloading page
                e.preventDefault();

                var khref = $(this).attr('href');
                window.open( khref, 'window', 'width=600, height=400, top=0, left=0');

            });
            
        });
        
    </script>
    <?php
    
    // End Output Buffering and Clear Buffer
    $output = ob_get_contents();
    ob_end_clean();
    
    echo apply_filters( 'kbso_post_sharing_frontend_js', $output );
    
}