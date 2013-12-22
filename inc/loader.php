<?php
/* 
 * Load Extra Classes and Files
 */

if ( ! defined( 'KBSO_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/*
 * Include Options file.
 */
require_once( KBSO_PATH . 'inc/options.php' );

/*
 * Include Main Menu file.
 */
require_once( KBSO_PATH . 'inc/menu.php' );

/*
 * Include Helper Functions file.
 */
require_once( KBSO_PATH . 'inc/helpers.php' );