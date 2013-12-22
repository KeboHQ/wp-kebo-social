<?php
/* 
 * Load Extra Classes and Files
 */

if ( ! defined( 'KBSO_VERSION' ) ) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/*
 * Include Main Menu file.
 */
require_once( KBSO_PATH . 'inc/menu.php' );