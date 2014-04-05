<?php
/* 
 * Update Social Share Counts
 */

/**
 * Should we refresh the Social Counts?
 */
function kbso_maybe_refresh_counts( $post_id ) {
    
    $options = kbso_get_plugin_options();
    
    /*
     * If displaying share counts is turned on, we should update
     * This can be disabled by setting KBSO_UPDATE_COUNTS to false.
     */
    if ( 'true' == KBSO_POST_SHARING_UPDATE_COUNTS ) {
        
        $caching = Kebo_Caching::get_instance();
    
        $caching->set_cache( 'kbso_post_sharing' );

        $caching->set_lock( $post_id );

        $caching->spawn_process();
        
    }
    
}

/**
 * If we detect a cache refresh request, run the refresh function
 */
function kbso_post_sharing_refresh_detect( $cache, $lock ) {
    
    if ( 'post_sharing' == $cache ) {
        
        $post_id = absint( $lock );
        
        kbso_post_sharing_update_counts( $post_id );
        
    }
    
}
add_action( 'kebo_caching_capture_request', 'kbso_post_sharing_refresh_detect', 5, 2 );

/**
 * Updates the Share Link Counts after the page has been rendered.
 */
function kbso_post_sharing_update_counts( $post_id ) {
    
    $options = kbso_get_plugin_options();
    
    $permalink = get_permalink( $post_id );

    // TODO- check we have a valid ID/Permalink before proceeding
    
    $counts = get_post_meta( $post_id, '_kbso_post_sharing_counts', true );
    
    if ( empty ( $counts ) ) {
        
        $counts = array();
        
    }
    
    $counts['total'] = 0;
    
    if ( ! isset( $counts['expiry'] ) || time() > $counts['expiry'] ) {
    
        /*
         * Update Twitter Share Count
         */
        $twitter = kbso_update_twitter_count( $permalink );
        
        if ( is_int( $twitter ) ) {

            $counts['twitter'] = kbso_update_count( $counts['twitter'], $twitter );
            $counts['total'] = kbso_update_count_total( $counts['total'], $twitter );

        } else {
             
            $counts['total'] = kbso_update_count_total( $counts['total'], $counts['twitter'] );
            
        }
        
        /*
         * Update Buffer Share Count
         */
        $buffer = kbso_update_buffer_count( $permalink );
        
        if ( is_int( $buffer ) ) {

            $counts['buffer'] = kbso_update_count( $counts['buffer'], $buffer );
            $counts['total'] = kbso_update_count_total( $counts['total'], $buffer );

        } else {
             
            $counts['total'] = kbso_update_count_total( $counts['total'], $counts['buffer'] );
            
        }

        /*
         * Update Facebook Share Count
         */
        $facebook = kbso_update_facebook_count( $permalink );

        if ( is_int( $facebook ) ) {

            $counts['facebook'] = kbso_update_count( $counts['facebook'], $facebook );
            $counts['total'] = kbso_update_count_total( $counts['total'], $facebook );

        } else {
            
            $counts['total'] = kbso_update_count_total( $counts['total'], $counts['facebook'] );
            
        }
        
        /*
         * Update Google+ Share Count
         */
        $googleplus = kbso_update_googleplus_count( $permalink );
        
        if ( is_int( $googleplus ) ) {

            $counts['googleplus'] = kbso_update_count( $counts['googleplus'], $googleplus );
            $counts['total'] = kbso_update_count_total( $counts['total'], $googleplus );

        } else {
            
            $counts['total'] = kbso_update_count_total( $counts['total'], $counts['googleplus'] );
            
        }
        
        /*
         * Update LinkedIn Share Count
         */
        $linkedin = kbso_update_linkedin_count( $permalink );

        if ( is_int( $linkedin ) ) {

            $counts['linkedin'] = kbso_update_count( $counts['linkedin'], $linkedin );
            $counts['total'] = kbso_update_count_total( $counts['total'], $linkedin );

        } else {
            
            $counts['total'] = kbso_update_count_total( $counts['total'], $counts['linkedin'] );
            
        }
        
        /*
         * Update Pinterest Share Count
         */
        $pinterest = kbso_update_pinterest_count( $permalink );
        
        if ( is_int( $pinterest ) ) {

            $counts['pinterest'] = kbso_update_count( $counts['pinterest'], $pinterest );
            $counts['total'] = kbso_update_count_total( $counts['total'], $pinterest );

        } else {
            
            $counts['total'] = kbso_update_count_total( $counts['total'], $counts['pinterest'] );
            
        }
        
        /*
         * Update StumbleUpon Share Count
         */
        $stumbleupon = kbso_update_stumbleupon_count( $permalink );
        
        if ( is_int( $stumbleupon ) ) {

            $counts['stumbleupon'] = kbso_update_count( $counts['stumbleupon'], $stumbleupon );
            $counts['total'] = kbso_update_count_total( $counts['total'], $stumbleupon );

        } else {
            
            $counts['total'] = kbso_update_count_total( $counts['total'], $counts['stumbleupon'] );
            
        }
        
        /*
         * Update Delicious Share Count
         */
        $delicious = kbso_update_delicious_count( $permalink );
        
        if ( is_int( $delicious ) ) {

            $counts['delicious'] = kbso_update_count( $counts['delicious'], $delicious );
            $counts['total'] = kbso_update_count_total( $counts['total'], $delicious );

        } else {
            
            $counts['total'] = kbso_update_count_total( $counts['total'], $counts['delicious'] );
            
        }

        /*
         * Update Expiry Time
         */
        $counts['expiry'] = time() + ( 5 * MINUTE_IN_SECONDS );

        if ( ! update_post_meta( $post_id, '_kbso_post_sharing_counts', $counts ) ) {

            add_post_meta( $post_id, '_kbso_post_sharing_counts', $counts, true );

        }
    
    }
    
}

/*
 * Helper Function for Share Counts using the HTTP API
 */
function kbso_post_sharing_count_request( $url ) {

    $args = array(
        'method'    => 'GET',
        'sslverify' => false,
        'timeout'   => 3,
    );
    
    /*
     * Make HTTP Request
     */
    $request = wp_remote_request( esc_url_raw( $url ), $args );

    /*
     * Check for Error, if none return response.
     */
    if ( is_wp_error( $request ) ) {
        
        return false;
        
    } else {
        
        $response = json_decode( $request['body'], true );

        return $response;
        
    }
    
}

/*
 * Get the Twitter Share Count.
 */
function kbso_update_twitter_count( $permalink ) {
    
    // Pre-Defined Social Count URL
    $service_url = 'http://urls.api.twitter.com/1/urls/count.json?url=' . $permalink;
    
    // Allow Override
    $url = apply_filters( 'kbso_twitter_share_count_url', $service_url, $permalink );
    
    $response = kbso_post_sharing_count_request( $url );
    
    if ( $response ) {
        
        return $response['count'];
        
    } else {
        
        return false;
        
    }
    
}

/*
 * Get the Twitter Share Count.
 */
function kbso_update_buffer_count( $permalink ) {
    
    // Pre-Defined Social Count URL
    $service_url = 'https://api.bufferapp.com/1/links/shares.json?url=' . $permalink;
    
    // Allow Override
    $url = apply_filters( 'kbso_buffer_share_count_url', $service_url, $permalink );
    
    $response = kbso_post_sharing_count_request( $url );
    
    if ( $response ) {
        
        return $response['shares'];
        
    } else {
        
        return false;
        
    }
    
}

/*
 * Get the Facebook Share Count.
 */
function kbso_update_facebook_count( $permalink ) {
    
    // Pre-Defined Social Count URL
    $service_url = 'http://api.facebook.com/method/links.getStats?format=json&urls=' . $permalink;
    
    // Allow Override
    $url = apply_filters( 'kbso_facebook_share_count_url', $service_url, $permalink );
    
    $response = kbso_post_sharing_count_request( $url );
    
    if ( $response ) {
        
        return $response[0]['share_count'];
        
    } else {
        
        return false;
        
    }
    
}

/*
 * Get the LinkedIn Share Count.
 */
function kbso_update_linkedin_count( $permalink ) {
    
    // Pre-Defined Social Count URL
    $service_url = 'http://www.linkedin.com/countserv/count/share?format=json&url=' . $permalink;
    
    // Allow Override
    $url = apply_filters( 'kbso_linkedin_share_count_url', $service_url, $permalink );
    
    $response = kbso_post_sharing_count_request( $url );
    
    if ( $response ) {
        
        return $response['count'];
        
    } else {
        
        return false;
        
    }
    
}

/*
 * Get the Pinterest Share Count.
 */
function kbso_update_pinterest_count( $permalink ) {
    
    // Pre-Defined Social Count URL
    $service_url = 'http://api.pinterest.com/v1/urls/count.json?url=' . $permalink;
    
    // Allow Override
    $url = apply_filters( 'kbso_pinterest_share_count_url', $service_url, $permalink );
    
    $response = kbso_post_sharing_count_request( $url );
    
    if ( $response ) {
        
        if ( ! empty( $response['count'] ) ) {
            
            return $response['count'];
            
        } else {
            
            return false;
            
        }
    
    }
    
}

/*
 * Get the Google+ Share Count.
 * requires a POST request to fetch social counts.
 */
function kbso_update_googleplus_count( $permalink ) {
    
    $url = 'https://clients6.google.com/rpc';

    $args = array(
        'method'    => 'POST',
        'sslverify' => false,
        'timeout'   => 5,
        'headers'   => array( 'Content-Type' => 'application/json' ),
        'body'      => '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $permalink . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]'
    );
    
    $request = wp_remote_request( esc_url_raw( $url ), $args );
    
    $response = json_decode( $request['body'], true );

    if ( isset( $response[0]['result']['metadata']['globalCounts']['count'] ) ) {
            
        return $response[0]['result']['metadata']['globalCounts']['count'];
        
    } else {
            
        return false;
            
    }
    
}

/*
 * Get the StumbleUpon Share Count.
 */
function kbso_update_stumbleupon_count( $permalink ) {
    
    // Pre-Defined Social Count URL
    $service_url = 'http://www.stumbleupon.com/services/1.01/badge.getinfo?url=' . $permalink;
    
    // Allow Override
    $url = apply_filters( 'kbso_stumbleupon_share_count_url', $service_url, $permalink );
    
    $response = kbso_post_sharing_count_request( $url );
    
    if ( $response ) {
        
        // Service specific check
        if ( isset( $response['result']['views'] ) ) {
        
            return $response['result']['views'];
        
        } else {
            
            return false;
            
        }
    
    } else {
        
        return false;
    
    }
    
}

/*
 * Get the Delicious Share Count.
 */
function kbso_update_delicious_count( $permalink ) {
    
    // Pre-Defined Social Count URL
    $service_url = 'http://feeds.delicious.com/v2/json/urlinfo/data?url=' . $permalink;
    
    // Allow Override
    $url = apply_filters( 'kbso_delicious_share_count_url', $service_url, $permalink );
    
    $response = kbso_post_sharing_count_request( $url );
    
    if ( $response ) {
        
        // Service specific check
        if ( isset( $response[0]['total_posts'] ) ) {

            return $response[0]['total_posts'];
        
        } else {
            
            return false;
            
        }
    
    } else {
        
        return false;
    
    }
    
}