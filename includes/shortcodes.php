<?php

// Deny any direct accessing of this file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WMD_Shortcodes {

	public function __construct() {
		add_shortcode( 'wimp-signup-url', array( __CLASS__, 'signup_url' ) );
	}

	public static function signup_url( $attrs, $content = null ) {
		$atts = shortcode_atts( array(
			'class' => '',
		), $attrs );

		if ( wmd_is_wimp_plus_member() ) {
			$url = wmd_get_membership_url();
		} else {
			$url = home_url( '/members/sign-up' );
		}

		if ( ! empty( $atts['class'] ) ) {
			$classes = ' class="' . esc_attr( $atts['class'] ) . '"';
		} else {
			$classes = '';
		}

		return sprintf( '<a href="%s"%s>%s</a>', esc_url( $url ), $classes, esc_html( $content ) );
	}
}
new WMD_Shortcodes();