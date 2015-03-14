<?php
/**
 * The template for displaying Member Directory archive.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WIMP Member Directory
 */

get_header(); ?>

	<section class="main-body" class="site-content" role="main">

		<header class="page-header">
			<?php if ( is_tax() ) : ?>
				<p class="alignright button" style="padding:5px;">
					<a href="<?php echo esc_url( home_url( '/member-directory/' ) ); ?>">
						&#8592; Back to All Listings
					</a>
				</p>
			<?php endif; ?>

			<h1 class="page-title">Member Directory: Find A WIMP</h1>

			<?php if ( is_tax() ) : ?>
				<h3 class="page-title">Viewing Listings Labeled as:
					<span style="font-weight: 400"><?php echo esc_html( single_term_title() ); ?></span>
				</h3>
			<?php endif; ?>

			<?php wmd_filter_options(); ?>
		</header><!-- .page-header -->

		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php $listing = wmd_get_listing(); ?>
				<section
					id="member-<?php echo esc_attr( $listing->member_id ); ?>"
					class="wimp-member-listing member-<?php echo esc_attr( $listing->member_id ); ?>">

					<header class="listing-header">

						<div class="listing-name">
							<?php if ( ! empty( $listing->logo_id ) ) : ?>
								<?php $logo = wp_get_attachment_image_src( $listing->logo_id, 'wmd_logo' ); ?>
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
							<ul>
								<li><?php echo esc_html( $listing->title ); ?></li>
								<li class="wmd-cost"><?php wmd_format_prices( $listing->low_price, $listing->high_price ); ?></li>
								<li><?php echo esc_html( array_shift( $listing->state )->name . ', ' . array_shift( $listing->city )->name ); ?></li>
							</ul>
						</div>

					</header>

					<article class="listing-wrapper">
						<?php wmd_display_portfolio( $listing->portfolio ); ?>
					</article>

					<aside class="directory-archive-meta">
						<div class="column">
							<table>
								<tbody>
									<?php wmd_format_terms( $listing, 'industries' ); ?>
									<?php wmd_format_terms( $listing, 'types' ); ?>
								</tbody>
							</table>
						</div>
						<div class="column">
							<table>
								<tbody>
									<?php wmd_format_terms( $listing, 'technologies' ); ?>
								</tbody>
							</table>
						</div>
					</aside>

					<section class="listing-cta">
						<a href="<?php the_permalink(); ?>">View Details</a>
						<a href="<?php echo esc_url( $listing->url ); ?>">Visit Website</a>
					</section>
				</section>

			<?php endwhile; ?>
		<?php else: ?>
			<h3>No Listings Found!</h3>
			<p>Looks like we couldn't find the listings you were looking for. Go back to the
			<a href="<?php echo esc_url( home_url( '/member-directory/' ) ); ?>">Member Listings</a></p>
		<?php endif; ?>

	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>