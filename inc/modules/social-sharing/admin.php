<?php
/* 
 * Handle choosing which links to show and where.
 */

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