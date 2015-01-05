<?php

/**
 * Membership Account Screen
 */
function bp_wimp_membership_account_screen() {
	add_action( 'bp_template_title', 'bp_wimp_membership_account_screen_title' );
	add_action( 'bp_template_content', 'bp_wimp_membership_account_screen_content' );

	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function bp_wimp_membership_account_screen_title() {
	_e( 'WIMP Membership', 'wmd' );
}

function bp_wimp_membership_account_screen_content() {
	echo do_shortcode( '[pmpro_account]' );
}

/**
 * Membership Billing Screen
 */
function bp_wimp_membership_billing_screen() {
	add_action( 'bp_template_title', 'bp_wimp_membership_billing_screen_title' );
	add_action( 'bp_template_content', 'bp_wimp_membership_billing_screen_content' );

	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function bp_wimp_membership_billing_screen_title() {
	_e( 'Billing Information', 'wmd' );
}

function bp_wimp_membership_billing_screen_content() {
	echo do_shortcode( '[pmpro_billing]' );
}