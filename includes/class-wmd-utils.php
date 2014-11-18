<?php

// Deny any direct accessing of this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WMD_Utils {
	/**
	 * Default initialization for the plugin:
	 * - Registers the default textdomain.
	 */
	public static function init() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'wmd' );
		load_textdomain( 'wmd', WP_LANG_DIR . '/wmd/wmd-' . $locale . '.mo' );
		load_plugin_textdomain( 'wmd', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// Load our post types and taxonomies
		WMD_Post_Types::member_directory_init();

		// Load our cmb framework
		self::init_cmb();
	}

	/**
	 * Activate the plugin
	 */
	public static function activate() {
		// First load the init scripts in case any rewrite functionality is being loaded
		self::init();

		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin
	 * Uninstall routines should be in uninstall.php
	 */
	public static function deactivate() {

	}

	/**
	 * Initializes the CMB class
	 */
	public static function init_cmb() {
		if ( ! class_exists( 'cmb_Meta_Box' ) ) {
			require_once( 'lib/cmb/init.php' );
		}
	}
}