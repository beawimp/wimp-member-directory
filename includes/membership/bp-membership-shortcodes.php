<?php

function wmd_membership_slugs() {
	$bp_pages = array(
		'wimp_plus',
		'billing',
		'invoice',
	);

	return $bp_pages;
}

/**
 * A helper function to allow us to process the $_SERVER['request_uri'] and extract the base
 * name of the URL request.
 *
 * @param string $request The URL being requested from the server
 *
 * @return string
 */
function wmd_process_request_uri( $request ) {
	$has_querystring = strtok( $request, '?' );

	if ( $has_querystring ) {
		$base = basename( $has_querystring );
	} else {
		$base = basename( $url );
	}

	return $base;
}

/**
 * A list of URL's we can match against to see if we are currently viewing them in BuddyPress
 *
 * @return bool
 */
function wmd_is_bp_component() {
	$base = wmd_process_request_uri( $_SERVER['REQUEST_URI'] );

	return in_array( $base, wmd_membership_slugs() );
}

/**
 * A custom version of the pmpro_wp() function found in Paid Memberships Pro.
 *
 * This version should only run when we are viewing the BuddyPress pages listing
 * in wmd_is_bp_component(). This function will then load special customized
 * templates found in /includes/membership/templates/
 *
 * @global array $pmpro_pages
 * @global str   $pmpro_page_name
 * @global int   $pmpro_page_id
 * @global array $pmpro_body_classes
 *
 * @return void
 */
function wimp_membership_shortcodes() {
	if ( is_admin() ) {
		return;
	}

	global $pmpro_pages, $pmpro_page_name, $pmpro_page_id, $pmpro_body_classes, $is_wimp_plus_member, $current_page;

	// no pages yet?
	if ( empty( $pmpro_pages ) ) {
		return;
	}

	// Run the appropriate preheader function
	foreach ( $pmpro_pages as $pmpro_page_name => $pmpro_page_id ) {
		$current_page = wmd_process_request_uri( $_SERVER['REQUEST_URI'] );

		if ( 'wimp_plus' === $current_page ) {
			// We need an easy way to load the account shortcode and templates when we check the request URI
			$current_page = 'account';
		}

		if ( wmd_is_bp_component() && $pmpro_page_name === $current_page ) {

			// Pre-header
			if ( 'account' === $current_page || 'billing' === $current_page ) {
				$is_wimp_plus_member = false;
				// When we have a user that isn't a member yet, the PMPro redirects them to another page
				// We'll want to override that behavior and just have the page handle a different shortcode
				require_once( WMD_INCLUDES . '/membership/preheaders/' . $pmpro_page_name . '.php' );
			} else {
				require_once( PMPRO_DIR . '/preheaders/' . $pmpro_page_name . '.php' );
			}

			// add class to body
			$pmpro_body_classes[] = 'pmpro-' . str_replace( '_', '-', $pmpro_page_name );

			// shortcode
			function pmpro_pages_shortcode( $atts, $content = null, $code = "" ) {
				global $pmpro_page_name, $current_page, $is_wimp_plus_member;

				ob_start();
				if ( 'account' === $current_page && ! $is_wimp_plus_member ) {
					include( WMD_INCLUDES . '/membership/templates/levels.php' );
				} else {
					include( WMD_INCLUDES . 'membership/templates/' . $pmpro_page_name . '.php' );
				}

				$temp_content = ob_get_contents();
				ob_end_clean();

				return $temp_content;
			}

			add_shortcode( 'pmpro_' . $pmpro_page_name, 'pmpro_pages_shortcode' );
			break;    //only the first page found gets a shortcode replacement
		}
	}
}

// Only run our custom code for URL matches that we
if ( wmd_is_bp_component() ) {
	remove_action( 'wp', 'pmpro_wp', 1 );
	add_action( 'wp', 'wimp_membership_shortcodes', 1 );
}

function wmd_pages_confirmation() {
	ob_start();
	include( WMD_INCLUDES . '/membership/templates/confirmation.php' );
	$temp_content = ob_get_contents();
	ob_end_clean();

	return $temp_content;
}
add_filter( 'pmpro_pages_shortcode_confirmation', 'wmd_pages_confirmation' );