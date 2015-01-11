<?php
global $wpdb, $pmpro_invoice, $pmpro_msg, $pmpro_msgt, $current_user;

if ( $pmpro_msg ) {
	?>
	<div class="pmpro_message <?php echo esc_attr( $pmpro_msgt ); ?>"><?php echo esc_html( $pmpro_msg ); ?></div>
<?php
}
?>

<?php
if ( $pmpro_invoice ) {
	?>
	<?php
	$pmpro_invoice->getUser();
	$pmpro_invoice->getMembershipLevel();
	?>

	<h3>
		<?php printf( __( 'Invoice #%s on %s', 'pmpro' ), $pmpro_invoice->code, date_i18n( get_option( 'date_format' ), $pmpro_invoice->timestamp ) ); ?>
	</h3>
	<a class="pmpro_a-print" href="javascript:window.print()">Print</a>
	<ul>
		<?php do_action( "pmpro_invoice_bullets_top", $pmpro_invoice ); ?>
		<li>
			<strong><?php esc_html_e( 'Account', 'pmpro' ); ?>:</strong> <?php echo esc_html( $pmpro_invoice->user->display_name ); ?> (<?php echo esc_html( $pmpro_invoice->user->user_email ); ?>)
		</li>
		<li>
			<strong><?php esc_html_e( 'Membership Level', 'pmpro' ); ?>:</strong> <?php echo esc_html( $current_user->membership_level->name ); ?>
		</li>
		<?php if ( $current_user->membership_level->enddate ) { ?>
			<li>
				<strong><?php esc_html_e( 'Membership Expires', 'pmpro' ); ?>:</strong> <?php echo date( get_option( 'date_format' ), $current_user->membership_level->enddate ); ?>
			</li>
		<?php } ?>
		<?php if ( $pmpro_invoice->getDiscountCode() ) { ?>
			<li>
				<strong><?php esc_html_e( 'Discount Code', 'pmpro' ); ?>:</strong> <?php echo esc_html( $pmpro_invoice->discount_code->code ); ?>
			</li>
		<?php } ?>
		<?php do_action( 'pmpro_invoice_bullets_bottom', $pmpro_invoice ); ?>
	</ul>

	<?php
	//check instructions
	if ( "check" === $pmpro_invoice->gateway && ! pmpro_isLevelFree( $pmpro_invoice->membership_level ) ) {
		echo wpautop( pmpro_getOption( 'instructions' ) );
	}
	?>

	<table id="pmpro_invoice_table" class="pmpro_invoice" width="100%" cellpadding="0" cellspacing="0" border="0">
		<thead>
		<tr>
			<?php if ( ! empty( $pmpro_invoice->billing->name ) ) { ?>
				<th><?php esc_html_e( 'Billing Address', 'pmpro' ); ?></th>
			<?php } ?>
			<th><?php esc_html_e( 'Payment Method', 'pmpro' ); ?></th>
			<th><?php esc_html_e( 'Membership Level', 'pmpro' ); ?></th>
			<th align="center"><?php esc_html_e( 'Total Billed', 'pmpro' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<?php if ( ! empty( $pmpro_invoice->billing->name ) ) { ?>
				<td>
					<?php echo esc_html( $pmpro_invoice->billing->name ); ?><br />
					<?php echo esc_html( $pmpro_invoice->billing->street ); ?><br />
					<?php if ( $pmpro_invoice->billing->city && $pmpro_invoice->billing->state ) { ?>
						<?php echo esc_html( $pmpro_invoice->billing->city ); ?>, <?php echo esc_html( $pmpro_invoice->billing->state ); ?> <?php echo esc_html( $pmpro_invoice->billing->zip ) ?> <?php echo esc_html( $pmpro_invoice->billing->country ); ?>
						<br />
					<?php } ?>
					<?php echo esc_html( formatPhone( $pmpro_invoice->billing->phone ) ); ?>
				</td>
			<?php } ?>
			<td>
				<?php if ( $pmpro_invoice->accountnumber ) { ?>
					<?php echo esc_html( $pmpro_invoice->cardtype ); ?> <?php echo __( 'ending in', 'pmpro' ); ?> <?php echo esc_html( last4( $pmpro_invoice->accountnumber ) ); ?>
					<br />
					<small><?php esc_html_e( 'Expiration', 'pmpro' ); ?>: <?php echo esc_html( $pmpro_invoice->expirationmonth ); ?>/<?php echo absint( $pmpro_invoice->expirationyear ); ?></small>
				<?php } elseif ( $pmpro_invoice->payment_type ) { ?>
					<?php echo esc_html( $pmpro_invoice->payment_type ); ?>
				<?php } ?>
			</td>
			<td><?php echo esc_html( $pmpro_invoice->membership_level->name ); ?></td>
			<td align="center">
				<?php if ( $pmpro_invoice->total != '0.00' ) { ?>
					<?php if ( ! empty( $pmpro_invoice->tax ) ) { ?>
						<?php esc_html_e( 'Subtotal', 'pmpro' ); ?>: <?php echo esc_html( pmpro_formatPrice( $pmpro_invoice->subtotal ) ); ?>
						<br />
						<?php esc_html_e( 'Tax', 'pmpro' ); ?>: <?php echo esc_html( pmpro_formatPrice( $pmpro_invoice->tax ) ); ?>
						<br />
						<?php if ( ! empty( $pmpro_invoice->couponamount ) ) { ?>
							<?php esc_html_e( 'Coupon', 'pmpro' ); ?>: (<?php echo esc_html( pmpro_formatPrice( $pmpro_invoice->couponamount ) ); ?>)
							<br />
						<?php } ?>
						<strong><?php esc_html_e( 'Total', 'pmpro' ); ?>: <?php echo esc_html( pmpro_formatPrice( $pmpro_invoice->total ) ); ?></strong>
					<?php } else { ?>
						<?php echo esc_html( pmpro_formatPrice( $pmpro_invoice->total ) ); ?>
					<?php } ?>
				<?php } else { ?>
					<small class="pmpro_grey"><?php echo esc_html( pmpro_formatPrice( 0 ) ); ?></small>
				<?php } ?>
			</td>
		</tr>
		</tbody>
	</table>
<?php
} else {
	//Show all invoices for user if no invoice ID is passed
	$invoices = $wpdb->get_results( "SELECT *, UNIX_TIMESTAMP(timestamp) as timestamp FROM $wpdb->pmpro_membership_orders WHERE user_id = '$current_user->ID' ORDER BY timestamp DESC" );
	if ( $invoices ) {
		?>
		<table id="pmpro_invoices_table" class="pmpro_invoice wmd-custom-style" width="100%" cellpadding="0" cellspacing="0" border="0">
			<thead>
			<tr>
				<th><strong><?php esc_html_e( 'Date', 'pmpro' ); ?></strong></th>
				<th><strong><?php esc_html_e( 'Invoice #', 'pmpro' ); ?></strong></th>
				<th><strong><?php esc_html_e( 'Total Billed', 'pmpro' ); ?></strong></th>
				<th>&nbsp;</th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach ( $invoices as $invoice ) {
				?>
				<tr>
					<td><?php echo esc_html( date( get_option( 'date_format' ), $invoice->timestamp ) ); ?></td>
					<td>
						<a href="<?php echo esc_url( wmd_get_membership_url( 'invoice', '?invoice=' . urlencode( $invoice->code ) ) ); ?>"><?php echo esc_html( $invoice->code ); ?></a>
					</td>
					<td><?php echo esc_html( pmpro_formatPrice( $invoice->total ) ); ?></td>
					<td>
						<a href="<?php echo esc_url( wmd_get_membership_url( 'invoice', '?invoice=' . urlencode( $invoice->code ) ) ); ?>"><?php esc_html_e( 'View Invoice', 'pmpro' ); ?></a>
					</td>
				</tr>
			<?php
			}
			?>
			</tbody>
		</table>
	<?php
	} else {
		?>
		<p><?php esc_html_e( 'No invoices found.', 'pmpro' ); ?></p>
	<?php
	}
}
?>
<nav id="nav-below invoice-nav" class="navigation" role="navigation">
	<?php if ( $pmpro_invoice ) { ?>
		<div class="nav-prev alignleft">
			<a href="<?php echo esc_url( wmd_get_membership_url( 'invoice' ) ); ?>"><?php esc_html_e( '&larr; View All Invoices', 'pmpro' ); ?></a>
		</div>
	<?php } ?>
</nav>
