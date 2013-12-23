<?php
/* 
 * Update Social Share Counts
 */

/**
 * Updates the Share Link Counts after the page has been rendered.
 */
function kbso_share_links_update_counts() {
    
    global $post;
    
    $options = kbso_get_plugin_options();
    
    $permalink = get_permalink();
    
    $counts = get_post_meta( $post->ID, '_kbso_share_counts', true );
    
    if ( ! isset( $counts['expiry'] ) || ( isset( $counts['expiry'] ) && $counts['expiry'] < time() ) ) {
    
        /*
         * Update Twitter Share Count
         */
        $twitter = kbso_update_twitter_count( $permalink );

        if ( isset( $twitter->count ) ) {

            $counts['twitter'] = $twitter->count;

        }

        /*
         * Update Facebook Share Count
         */
        $facebook = kbso_update_facebook_count( $permalink );

        if ( isset( $facebook[0]->total_count ) ) {

            $counts['facebook'] = $facebook[0]->total_count;

        }
        
        /*
         * Update Google+ Share Count
         */
        $googleplus = kbso_update_googleplus_count( $permalink );

        if ( $googleplus ) {

            $counts['googleplus'] = $googleplus;

        }
        
        /*
         * Update LinkedIn Share Count
         */
        $linkedin = kbso_update_linkedin_count( $permalink );

        if ( $linkedin->count ) {

            $counts['linkedin'] = $linkedin->count;

        }
        
        /*
         * Update Pinterest Share Count
         */
        $pinterest = kbso_update_pinterest_count( $permalink );

        if ( $pinterest ) {

            $counts['pinterest'] = $pinterest;

        }
        
        /*
         * Update StumbleUpon Share Count
         */
        $stumbleupon = kbso_update_stumbleupon_count( $permalink );
        
        if ( $stumbleupon ) {

            $counts['stumbleupon'] = $stumbleupon;

        }
        
        /*
         * Update Delicious Share Count
         */
        $delicious = kbso_update_delicious_count( $permalink );
        
        if ( $delicious ) {

            $counts['delicious'] = $delicious;

        }

        /*
         * Update Expiry Time
         */
        $counts['expiry'] = time() + ( 5 * MINUTE_IN_SECONDS );
        $counts['expiry'] = time();

        if ( ! update_post_meta( $post->ID, '_kbso_share_counts', $counts ) ) {

            add_post_meta( $post->ID, '_kbso_share_counts', $counts, true );

        }
    
    }
    
}

/*
 * Helper Function for Share Counts using the HTTP API
 */
function kbso_share_count_request( $url ) {

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
    
    $response = kbso_share_count_request( $url );
    
    if ( $response ) {
        
        return json_decode( $response );
        
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
    
    $request = kbso_share_count_request( $url );
    
    if ( $request ) {
        
        $response = json_decode( $request );
        
        return $response;
        
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
    
    $request = kbso_share_count_request( $url );
    
    if ( $request ) {
        
        $response = json_decode( $request, true );
        
        return $response;
        
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
    
    $request = kbso_share_count_request( $url );
    
    if ( $request ) {
        
        $filtered = preg_replace( '/.+?({.+}).+/','$1', $request );

        $response = json_decode( $filtered );

        return ( $json->count !== '-' ) ? $json->count : false;
    
    } else {
        
        return false;
    
    }
    
}

/*
 * Get the Google+ Share Count.
 * requires a POST request to fetch social counts.
 */
function kbso_update_googleplus_count( $permalink ) {
        
    $permalink = 'https://www.khanacademy.org/';
    
    $url = 'https://clients6.google.com/rpc';

    $args = array(
        'method'    => 'POST',
        'sslverify' => false,
        'timeout'   => 5,
        'headers'   => array( 'Content-Type' => 'application/json' ),
        'body'      => '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $permalink . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]'
    );
    
    $request = wp_remote_request( esc_url_raw( $url ), $args );

    if ( is_wp_error( $request ) || '400' <= $request['response']['code'] ) {
        
        return false;
        
    } else {
        
        $response = json_decode( $request['body'], true );

        if ( isset( $response[0]['result']['metadata']['globalCounts']['count'] ) ) {
            
            return $response[0]['result']['metadata']['globalCounts']['count'];
        
        } else {
            
            return false;
            
        }
        
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
    
    $request = kbso_share_count_request( $url );
    
    if ( $request ) {
        
        $response = json_decode( $request, true );
        
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
    
    $request = kbso_share_count_request( $url );
    
    if ( $request ) {
        
        $response = json_decode( $request, true );
        
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
