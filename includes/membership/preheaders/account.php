<?php

global $wpdb, $current_user, $pmpro_msg, $pmpro_msgt, $is_wimp_plus_member;

if ( $current_user->ID ) {
	$current_user->membership_level = pmpro_getMembershipLevelForUser( $current_user->ID );
}

if ( isset( $_REQUEST['msg'] ) ) {
	if ( 1 === $_REQUEST['msg']) {
		$pmpro_msg = __( 'Your membership status has been updated - Thank you!', 'pmpro' );
	} else {
		$pmpro_msg  = __( 'Sorry, your request could not be completed - please try again in a few moments.', 'pmpro' );
		$pmpro_msgt = 'pmpro_error';
	}
} else {
	$pmpro_msg = false;
}

//if no user, redirect to levels page
if ( empty( $current_user->ID ) ) {
	// It is recommended we utilize the filter instead of hard-coding this.
	$redirect = apply_filters( 'pmpro_account_preheader_no_user_redirect', pmpro_url( 'levels' ) );
	if ( $redirect ) {
		wp_redirect( $redirect );
		exit;
	}
}

// if no membership level, redirect to levels page
if ( ! empty( $current_user->membership_level->ID ) ) {
	$is_wimp_plus_member = true;

	global $pmpro_levels;
	$pmpro_levels = pmpro_getAllLevels();
}

require_once( WMD_INCLUDES . '/membership/preheaders/levels.php' );