<?php

// Deny any direct accessing of this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WMD_Shortcodes {

	public function __construct() {
		add_shortcode( 'member-directory', array( __CLASS__, 'get_member_directory' ) );
	}

	public static function get_member_directory() {
		echo 'asdfdsf';
	}
}
new WMD_Shortcodes();