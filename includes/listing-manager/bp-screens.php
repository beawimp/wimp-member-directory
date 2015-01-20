<?php

/**
 * Member Directory Listing Manager screen
 */
function bp_wimp_listing_screen() {
	add_action( 'bp_template_title', 'bp_wimp_listing_screen_title' );
	add_action( 'bp_template_content', 'bp_wimp_listing_screen_content' );

	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function bp_wimp_listing_screen_title() {
	_e( 'My Directory Listing', 'wmd' );
}

function bp_wimp_listing_screen_content() {
	// Fetch the currently viewed user ID and fetch the listing based on that.
	$data = wmd_get_listing_by_user_id( bp_displayed_user_id() );

	echo wmd_get_listing_form( $data );
}