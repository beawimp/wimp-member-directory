<?php
global $bp, $wpdb, $pmpro_msg, $pmpro_msgt, $pmpro_levels, $current_user, $levels;

// If a member is logged in, show them some info here (1. past invoices. 2. billing information with button to update.)
if ( ! $current_user->membership_level->ID ) {
	return '';
}
?>
<div id="pmpro_account">
	<div id="pmpro_account-membership" class="pmpro_box">
		<h3>Your membership is <strong>active</strong>.</h3>
		<ul>
			<li>
				<strong><?php esc_html_e( 'Level', 'pmpro' ); ?>:</strong> <?php echo esc_html( $current_user->membership_level->name ); ?>
			</li>
			<?php if ( 0 < $current_user->membership_level->billing_amount ) { ?>
				<li><strong><?php esc_html_e( 'Membership Fee', 'pmpro' ); ?>:</strong>
					<?php
					$level = $current_user->membership_level;
					if ( 1 < $current_user->membership_level->cycle_number ) {
						printf( __( '%s every %d %s.', 'pmpro' ), pmpro_formatPrice( $level->billing_amount ), $level->cycle_number, pmpro_translate_billing_period( $level->cycle_period, $level->cycle_number ) );
					} elseif ( 1 === $current_user->membership_level->cycle_number ) {
						printf( __( '%s per %s.', 'pmpro' ), pmpro_formatPrice( $level->billing_amount ), pmpro_translate_billing_period( $level->cycle_period ) );
					} else {
						echo pmpro_formatPrice( $current_user->membership_level->billing_amount );
					}
					?>
				</li>
			<?php } ?>

			<?php if ( $current_user->membership_level->billing_limit ) { ?>
				<li>
					<strong><?php esc_html_e( 'Duration', 'pmpro' ); ?>:</strong> <?php echo esc_html( $current_user->membership_level->billing_limit . ' ' . sornot( $current_user->membership_level->cycle_period, $current_user->membership_level->billing_limit ) ); ?>
				</li>
			<?php } ?>

			<?php if ( $current_user->membership_level->enddate ) { ?>
				<li>
					<strong><?php esc_html_e( 'Membership Expires', 'pmpro' ); ?>:</strong> <?php echo esc_html( date_i18n( get_option( 'date_format' ), $current_user->membership_level->enddate ) ); ?>
				</li>
			<?php } ?>

			<?php if ( 1 === $current_user->membership_level->trial_limit ) {
				printf( __( 'Your first payment will cost %s.', 'pmpro' ), pmpro_formatPrice( $current_user->membership_level->trial_amount ) );
			} elseif ( ! empty( $current_user->membership_level->trial_limit ) ) {
				printf( __( 'Your first %d payments will cost %s.', 'pmpro' ), $current_user->membership_level->trial_limit, pmpro_formatPrice( $current_user->membership_level->trial_amount ) );
			}
			?>
		</ul>
	</div>
	<!-- end pmpro_account-membership -->

	<?php
	//last invoice for current info
	//$ssorder = $wpdb->get_row("SELECT *, UNIX_TIMESTAMP(timestamp) as timestamp FROM $wpdb->pmpro_membership_orders WHERE user_id = '$current_user->ID' AND membership_id = '" . $current_user->membership_level->ID . "' AND status = 'success' ORDER BY timestamp DESC LIMIT 1");
	$ssorder = new MemberOrder();
	$ssorder->getLastMemberOrder();
	$user_id = (int) $current_user->ID;

	if ( 0 !== $user_id ) {
		$invoices = $wpdb->get_results( "SELECT *, UNIX_TIMESTAMP(timestamp) as timestamp FROM $wpdb->pmpro_membership_orders WHERE user_id = '$user_id' ORDER BY timestamp DESC LIMIT 6" );
		if ( ! empty( $ssorder->id ) && 'check' !== $ssorder->gateway && 'paypalexpress' !== $ssorder->gateway && 'paypalstandard' !== $ssorder->gateway && 'twocheckout' !== $ssorder->gateway ) {
			//default values from DB (should be last order or last update)
			$bfirstname      = get_user_meta( $current_user->ID, 'pmpro_bfirstname', true );
			$blastname       = get_user_meta( $current_user->ID, 'pmpro_blastname', true );
			$baddress1       = get_user_meta( $current_user->ID, 'pmpro_baddress1', true );
			$baddress2       = get_user_meta( $current_user->ID, 'pmpro_baddress2', true );
			$bcity           = get_user_meta( $current_user->ID, 'pmpro_bcity', true );
			$bstate          = get_user_meta( $current_user->ID, 'pmpro_bstate', true );
			$bzipcode        = get_user_meta( $current_user->ID, 'pmpro_bzipcode', true );
			$bcountry        = get_user_meta( $current_user->ID, 'pmpro_bcountry', true );
			$bphone          = get_user_meta( $current_user->ID, 'pmpro_bphone', true );
			$bemail          = get_user_meta( $current_user->ID, 'pmpro_bemail', true );
			$bconfirmemail   = get_user_meta( $current_user->ID, 'pmpro_bconfirmemail', true );
			$CardType        = get_user_meta( $current_user->ID, 'pmpro_CardType', true );
			$AccountNumber   = hideCardNumber( get_user_meta( $current_user->ID, 'pmpro_AccountNumber', true ), false );
			$ExpirationMonth = get_user_meta( $current_user->ID, 'pmpro_ExpirationMonth', true );
			$ExpirationYear  = get_user_meta( $current_user->ID, 'pmpro_ExpirationYear', true );
			?>

			<div id="pmpro_account-billing" class="pmpro_box">
				<h3><?php esc_html_e( 'Billing Information', 'pmpro' ); ?></h3>
				<?php if ( ! empty( $baddress1 ) ) { ?>
					<p>
						<strong><?php esc_html_e( 'Billing Address', 'pmpro' ); ?></strong><br />
						<?php echo esc_html( $bfirstname . ' ' . $blastname ); ?>
						<br />
						<?php echo esc_html( $baddress1 ); ?><br />
						<?php if ( $baddress2 ) {
							echo esc_html( $baddress2 ) . '<br />';
						} ?>
						<?php if ( $bcity && $bstate ) { ?>
							<?php echo esc_html( $bcity ); ?>, <?php echo esc_html( $bstate ); ?> <?php echo esc_html( $bzipcode ); ?> <?php echo esc_html( $bcountry ); ?>
						<?php } ?>
						<br />
						<?php echo esc_html( formatPhone( $bphone ) ); ?>
					</p>
				<?php } ?>

				<?php if ( ! empty( $AccountNumber ) ) { ?>
					<p>
						<strong><?php esc_html_e( 'Payment Method', 'pmpro' ); ?></strong><br />
						<?php echo esc_html( $CardType ) ?>: <?php echo absint( last4( $AccountNumber ) ); ?> (<?php echo esc_html( $ExpirationMonth ); ?>/<?php echo esc_html( $ExpirationYear ); ?>)
					</p>
				<?php } ?>

				<?php
				if ( ( isset( $ssorder->status ) && 'success' === $ssorder->status ) && ( isset( $ssorder->gateway ) && in_array( $ssorder->gateway, array(
					'authorizenet',
					'paypal',
					'stripe',
					'braintree',
					'payflow',
					'cybersource',
				) ) ) ) {
				?>
					<p>
						<a href="<?php echo esc_url( wmd_get_membership_url( 'billing' ) ); ?>"><?php _e( 'Edit Billing Information', 'pmpro' ); ?></a>
					</p>
				<?php
				}
				?>
			</div> <!-- end pmpro_account-billing -->
		<?php
		}
	}
	?>

	<?php if ( ! empty( $invoices ) ) { ?>
		<div id="pmpro_account-invoices" class="pmpro_box">
			<h3><?php _e( 'Past Invoices', 'pmpro' ); ?></h3>
			<ul>
				<?php
				$count = 0;
				foreach ( $invoices as $invoice ) {
					if ( $count ++ > 5 ) {
						break;
					}
					?>
					<li>
						<a href="<?php echo esc_url( wmd_get_membership_url( 'invoice', '?invoice=' . $invoice->code ) ); ?>"><?php echo esc_html( date_i18n( get_option( 'date_format' ), $invoice->timestamp ) ); ?> (<?php echo esc_html( pmpro_formatPrice( $invoice->total ) ); ?>)</a>
					</li>
				<?php
				}
				?>
			</ul>
			<?php if ( $count == 6 ) { ?>
				<p>
					<a href="<?php echo esc_url( wmd_get_membership_url( 'invoice' ) ); ?>"><?php esc_html_e( 'View All Invoices', 'pmpro' ); ?></a>
				</p>
			<?php } ?>
		</div> <!-- end pmpro_account-billing -->
	<?php } ?>

	<div id="pmpro_account-links" class="pmpro_box">
		<h3><?php esc_html_e( 'Member Links', 'pmpro' ); ?></h3>
		<ul>
			<?php
			do_action( 'pmpro_member_links_top' );
			?>
			<?php if ( 1 < count( $pmpro_levels ) && ! defined( 'PMPRO_DEFAULT_LEVEL' ) ) { ?>
				<li>
					<a href="<?php echo esc_url( wmd_get_membership_url( 'levels' ) ); ?>"><?php esc_html_e( 'Change Membership Level', 'pmpro' ); ?></a>
				</li>
			<?php } ?>
			<li>
				<a href="<?php echo esc_url( pmpro_url( 'cancel' ) ); ?>"><?php esc_html_e( 'Cancel Membership', 'pmpro' ); ?></a>
			</li>
			<?php
			do_action( 'pmpro_member_links_bottom' );
			?>
		</ul>
	</div>
	<!-- end pmpro_account-links -->
</div> <!-- end pmpro_account -->
