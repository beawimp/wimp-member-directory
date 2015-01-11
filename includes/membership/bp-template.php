<?php

/**
 * Echo the component's slug
 */
function bp_wimp_membership_slug() {
	echo bp_get_wimp_membership_slug();
}

/**
 * Return the component's slug
 *
 * Having a template function for this purpose is not absolutely necessary, but it helps
 * avoid too-frequent direct calls to the $bp global.
 *
 * @uses apply_filters() Filter 'bp_get_wimp_membership_slug' to change the output
 *
 * @return str $slug The slug from $bp->membership->slug, if it exists.
 */
function bp_get_wimp_membership_slug() {
	global $bp;
	$slug = isset( $bp->wimp_membership->slug ) ? $bp->wimp_membership->slug : '';

	return apply_filters( 'bp_get_wimp_membership_slug', $slug );
}

function wmd_get_membership_url( $slug = '', $prefix = '' ) {
	global $bp;

	// To ensure our URL is correctly formatted we'll need to apply a forward slash before it
	if ( ! empty( $slug ) ) {
		$slug = trailingslashit( '/' . $slug );
	}

	// Append a prefix to the URL
	if ( ! empty( $prefix ) ) {
		$slug = $slug . $prefix;
	}

	return bp_loggedin_user_domain() . $bp->wimp_membership->slug . $slug;
}