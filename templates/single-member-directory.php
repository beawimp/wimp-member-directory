<?php
/**
 * The template for displaying a single listing.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WIMP Member Directory
 */

get_header(); ?>

	<div class="full-banner">
		<a href="<?php echo esc_url( home_url( '/member-directory/' ) ); ?>">
			&#8592; View All Listings
		</a>
	</div>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php while ( have_posts() ) : the_post(); $listing = wmd_get_listing(); ?>
				<?php if ( is_object( $listing ) ) : ?>
					<section
						id="member-<?php echo esc_attr( $listing->member_id ); ?>"
						class="wimp-member-listing member-<?php echo esc_attr( $listing->member_id ); ?>">
						<?php
						if ( $listing->member_id === get_current_user_id() ) {
							echo '<span class="edit-link" style="z-index:999999;"><a class="post-edit-link" href="' . bp_loggedin_user_domain() . 'listing_manager/">Edit Listing</a></span>';
						}
						?>
						<header class="listing-header">
							<div class="listing-name">
								<?php if ( ! empty( $listing->logo_id ) ) : ?>
									<?php $logo = wp_get_attachment_image_src( $listing->logo_id, 'wmd_logo_single' ); ?>
									<img src="<?php echo esc_url( $logo[0] ); ?>"
									     width="<?php echo esc_attr( $logo[1] ); ?>"
									     height="<?php echo esc_attr( $logo[2] ); ?>"
									     alt="<?php echo esc_attr( $listing->title ); ?>" />
								<?php else : ?>
									<h2 class="listing-text-logo">
										<?php echo esc_html( $listing->title ); ?>
									</h2>
								<?php endif; ?>
							</div>
							<div class="member-meta">
								<h2><?php echo esc_html( $listing->title ); ?></h2>
								<p class="prices"><?php wmd_format_prices( $listing->low_price, $listing->high_price ); ?></p>
								<p class="location">Located in: <?php wmd_format_location( $listing->state, $listing->city ); ?></p>
								<?php echo wp_kses_post( $listing->content ); ?>

								<p><?php wmd_format_terms( $listing, 'types' ); ?><br />
								<?php wmd_format_terms( $listing, 'technologies' ); ?><br />
								<?php wmd_format_terms( $listing, 'industries' ); ?></p>

								<?php wmd_display_member( $listing->member_id, $listing->url ); ?>
							</div>
						</header>

						<section class="listing-cta">
							<a href="<?php echo esc_url( bp_core_get_user_domain( $listing->member_id ) ); ?>">WIMP Profile</a>
							<a href="<?php echo esc_url( $listing->url ); ?>">Visit Website</a>
						</section>

					</section>

					<section id="portfolio">
						<?php wmd_display_portfolio( $listing->portfolio, 'list' ); ?>
					</section>
				<?php else : ?>
					<h2>No listing found!</h2>
				<?php endif; ?>
			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>