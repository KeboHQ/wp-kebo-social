<?php
/* 
 * Register the Sharing sub menu item.
 */

if ( ! defined( 'KBSO_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/**
 * Adds Modules Page(s) to the Tabs
 */
function kbso_post_sharing_tab_page_add( $pages ) {
    
    $pages[] = array(
        'slug' => 'sharing',
        'label' => __( 'Post Sharing', 'kbso' )
    );
    
    return $pages;
    
}
add_filter( 'kbso_tab_pages', 'kbso_post_sharing_tab_page_add' );

/*
 * Render the Sharing Page
 */
function kbso_post_sharing_page_render() {
    
    ?>
        <?php settings_errors( 'kbso-post-sharing' ); ?>
        
        <h3><?php esc_html_e( 'Share Links','kbso' ); ?></h3>
        <p><?php esc_html_e( 'Add sharing buttons to your website and allow visitors to share your content on social networks.', 'kbso' ); ?></p>
        
        <div class="share-links-container">

            <div class="available-services share-panel">

                <div class="description">
                    
                    <h3><?php esc_html_e( 'Available Services','kbso' ); ?></h3>
                    
                    <p><?php esc_html_e( 'Drag the services you would like to display on your site into the box below.', 'kbso' ); ?></p>
                    
                </div>
                
                <div class="links available">
                    
                    <ul id="share-links-available" class="link-container connectedSortable">
                        
                        <?php kbso_post_sharing_services_admin( 'remaining' ); ?>
                        
                    </ul>
                    
                </div>
                
                <div class="clearfix"></div>

            </div>

            <div class="selected-services share-panel">
                
                <div class="description">
                    
                    <h3><?php esc_html_e( 'Selected Services','kbso' ); ?></h3>
                    
                    <p><?php esc_html_e( 'Services listed here will appear in the same order on your website.', 'kbso' ); ?></p>
                    
                </div>
                
                <div class="links selected">
                    
                    <ul id="share-links-selected" class="link-container connectedSortable">
                        
                        <?php kbso_post_sharing_services_admin( 'selected' ); ?>
                        
                    </ul>
                    
                </div>
                
                <div class="clearfix"></div>
                
            </div>
            
            <div class="preview-services share-panel">
                
                <div class="description">
                    
                    <h3><?php esc_html_e( 'Preview','kbso' ); ?></h3>
                    
                    <p><?php esc_html_e( 'This is how the links will look on your website.', 'kbso' ); ?></p>
                    
                </div>
                
                <div class="links selected">
                    
                    <?php echo kbso_post_sharing_services_render( $preview = true ); ?>
                    
                </div>
                
                <div class="clearfix"></div>
                
            </div>
        
        </div><!-- .share-links-container -->
        
        <form method="post" action="options.php">
            <?php
            settings_fields( 'kbso_options' );
            do_settings_sections( 'kbso-post-sharing' );
            submit_button();
            ?>
        </form>
        
        <script type="text/javascript">
            jQuery(document).ready(function($) {

                // do AJAX config save
                var korder = new Array;

                $( '#share-links-selected .sortable' ).delay( 500 ).each( function( index ) {

                    var kservice = $(this).data( 'service' );

                    // Add data to array
                    korder.push( kservice );

                });
                
                var krevorder = korder.reverse();
                            
                var kul = $('.ksharelinks ul');
                            
                $.each( krevorder, function( index, item ) {
                                
                    var ksort = $( '.ksharelinks .klink.' + item ).parent().detach();

                    kul.prepend( ksort );
                                
                });

                $( "select#post_sharing_theme" ).change( function() {
                    
                    var kclass = $(this).val();
                    
                    $( '.links.selected .ksharelinks' ).removeClass( 'default gradient plain' ).addClass( kclass );
                    
                });

                $( "select#post_sharing_size" ).change( function() {
                    
                    var kclass = $(this).val();
                    
                    $( '.links.selected .ksharelinks' ).removeClass( 'xsmall small medium large xlarge' ).addClass( kclass );
                    
                });
                
                // Link Order
                var korder = new Array;
                
                $( '#share-links-selected .sortable' ).delay( 500 ).each( function( index ) {

                    var kservice = $(this).data( 'service' );

                    // Add data to array
                    korder.push( kservice );

                });
                
                $( '.ksharelinks .klink' ).each( function() {
                    
                    $(this).parent().css( 'display', 'none' );
                    
                });
                
                $.each( korder, function( index, item ) {
                        
                    $( '.ksharelinks .klink.' + item ).parent().css( 'display', 'inline-block' );
                        
                });
                
                // Counts Switch
                $( 'div.switch[data-id="counts"]' ).click( function(e) {
                   
                    $( '.ksharelinks li .kcount' ).toggle( 100 );
                    
                });
                
                // Share Text
                $( 'input#social_sharing_label' ).keyup(function(e) {
                    
                    var ktext = $(this).val();
                    
                    $(".ksharelinks .kintro").text( ktext );       
                    
                });

            });
        </script>

    <?php
    
    /*
     * Print Sharing Page JS
     */
    kbso_post_sharing_page_print_js();
    
}
add_action( 'kbso_tab_page_sharing', 'kbso_post_sharing_page_render' );