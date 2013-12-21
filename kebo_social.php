<?php
/**
 * Plugin Name: Kebo Social
 * Plugin URI:  http://kebopowered.com/plugins/kebo-social/
 * Description: Social integration done right. The best WordPress plugin to integrate Social Services into your website.
 * Version:     0.1.0
 * Author:      Kebo
 * Author URI:  http://kebopowered.com/
 * License:     GPLv2+
 * Text Domain: kbso
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2013 Kebo (email : support@kebopowered.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// Useful global constants
define( 'KBSO_VERSION', '0.1.0' );
define( 'KBSO_URL',     plugin_dir_url( __FILE__ ) );
define( 'KBSO_PATH',    dirname( __FILE__ ) . '/' );

/**
 * Default initialization for the plugin:
 * - Registers the default textdomain.
 */
function kbso_init() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'kbso' );
	load_textdomain( 'kbso', WP_LANG_DIR . '/kbso/kbso-' . $locale . '.mo' );
	load_plugin_textdomain( 'kbso', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

/**
 * Activate the plugin
 */
function kbso_activate() {
	// First load the init scripts in case any rewrite functionality is being loaded
	kbso_init();

	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'kbso_activate' );

/**
 * Deactivate the plugin
 * Uninstall routines should be in uninstall.php
 */
function kbso_deactivate() {

}
register_deactivation_hook( __FILE__, 'kbso_deactivate' );

// Wireup actions
add_action( 'init', 'kbso_init' );

// Wireup filters

// Wireup shortcodes
