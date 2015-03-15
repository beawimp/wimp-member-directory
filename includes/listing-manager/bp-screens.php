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
	if ( ! wmd_is_wimp_plus_member() ) {
		echo '<h2>WIMP+ Membership Required!</h2>';
		echo '<p>Please <a href="' . esc_url( wmd_get_membership_url() ) . '">sign-up for a WIMP+ membership</a> to create your own Member Listing!</p>';
	} else {
		// Fetch the currently viewed user ID and fetch the listing based on that.
		$data = wmd_get_listing_by_user_id( bp_displayed_user_id() );

		if ( ! empty( $data ) && 'publish' === get_post_status( $data->ID ) ) {
			echo '<p><a href="' . esc_url( get_permalink( $data->ID ) ) . '" class="button">View Your Listing</a></p>';
		}

		echo wmd_get_listing_form( $data );
	}
}