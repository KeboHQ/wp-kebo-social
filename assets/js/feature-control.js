/** 
 * Controls the activation and de-activation of Features in real-time.
 * 
 * Uses Ajax to update options.
 */

jQuery(document).ready( function($) {

    $( 'div.switch[data-id="post_sharing"]' ).click( function(e) {

        var kslug = $(this).data( 'id' );

        var data = {
            action: 'kbso_feature_control_update',
            data: kslug,
            nonce: keboFeatures.nonce
        };

        if ( $(this).children( 'input#x1' ).is( ':checked' ) ) {

            // Update the Option with AJAX
            $.post(ajaxurl, data, function( response ) {

                response = $.parseJSON( response );

                if ( 'true' === response.success && 'save' === response.action ) {

                    $( 'h2.nav-tab-wrapper' ).append( '<a class="nav-tab" data-slug="' + kslug + '" href="' + keboFeatures.url + kslug + '" style="display: none;">Post Sharing</a>' );
                    $( 'h2.nav-tab-wrapper a[data-slug=' + kslug + ']' ).fadeIn(400);

                }

            });



        } else {

            // Update the Option with AJAX
            $.post( ajaxurl, data, function( response ) {

                response = $.parseJSON( response );

                if ( 'true' === response.success && 'save' === response.action ) {

                    $( 'h2.nav-tab-wrapper a[data-slug=' + kslug + ']' ).fadeOut( 400, function() {
                        $(this).remove();
                    });

                }

            });

        }

    });

});