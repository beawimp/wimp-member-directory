<?php

class WMD_Utils {
	/**
	 * Default initialization for the plugin:
	 * - Registers the default textdomain.
	 */
	public static function wmd_init() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'wmd' );
		load_textdomain( 'wmd', WP_LANG_DIR . '/wmd/wmd-' . $locale . '.mo' );
		load_plugin_textdomain( 'wmd', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// Load our cmb framework
		self::wmd_init_cmb();
	}

	/**
	 * Activate the plugin
	 */
	public static function wmd_activate() {
		// First load the init scripts in case any rewrite functionality is being loaded
		self::wmd_init();

		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin
	 * Uninstall routines should be in uninstall.php
	 */
	public static function wmd_deactivate() {

	}

	/**
	 * Initializes the CMB class
	 */
	public static function wmd_init_cmb() {
		if ( ! class_exists( 'cmb_Meta_Box' ) ) {
			require_once( 'lib/cmb/init.php' );
		}
	}
}