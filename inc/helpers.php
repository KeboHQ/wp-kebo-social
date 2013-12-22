<?php
/* 
 * General Helper Functions
 */

/**
 * Render Dashboard Widgets
 */
function kbso_dashboard_widget_render( $title, $content, $sortable = false ) {
    
    ?>
    <div class="dashboard-box">

        <div class="dash-header">

            <h3><?php echo esc_html( $title ); ?></h3>

        </div>

        <div class="dash-content">

            <?php echo $content; ?>

        </div>

    </div>
    <?php
    
}