<?php
/**
 * The template for displaying a single listing.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WIMP Member Directory
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php while ( have_posts() ) : the_post(); $listing = wmd_get_listing(); ?>

				<section
					id="member-<?php echo esc_attr( $listing->member_id ); ?>"
					class="wimp-member-listing member-<?php echo esc_attr( $listing->member_id ); ?>">

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
							<p class="location">Located in: <?php wmd_format_location( $listing->locations ); ?></p>
							<?php echo $listing->content; ?>

							<p><?php wmd_format_terms( $listing, 'types' ); ?><br />
							<?php wmd_format_terms( $listing, 'technologies' ); ?><br />
							<?php wmd_format_terms( $listing, 'industries' ); ?></p>
						</div>
					</header>

				</section>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>