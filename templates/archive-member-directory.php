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

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">Member Directory: Find A WIMP</h1>
			</header><!-- .page-header -->

			<?php while ( have_posts() ) : the_post(); ?>
				<?php $listing = wmd_get_listing(); ?>
				<section
					id="member-<?php echo esc_attr( $listing->member_id ); ?>"
					class="wimp-member member-<?php echo esc_attr( $listing->member_id ); ?>">

					<header>
						<?php $logo = wp_get_attachment_image_src( $listing->logo_id, 'wmd_logo' ); ?>
						<img src="<?php echo esc_url( $logo[0] ); ?>"
						     width="<?php echo esc_attr( $logo[1] ); ?>"
						     height="<?php echo esc_attr( $logo[2] ); ?>"
						     alt="<?php echo esc_attr( $listing->title ); ?>" />

						<aside class="member-meta">
							<ul>
								<li><?php wmd_format_prices( $listing->prices ); ?></li>
								<li><?php wmd_format_location( $listing->locations ); ?></li>
							</ul>
						</aside>
					</header>

					<article class="listing-wrapper">
						<?php wmd_display_portfolio( $listing->portfolio ); ?>
					</article>

					<aside class="directory-archive-meta">
						<div class="column">
							<?php wmd_format_terms( $listing, 'industries' ); ?>
							<?php wmd_format_terms( $listing, 'types' ); ?>
						</div>
						<div class="column">
							<?php wmd_format_terms( $listing, 'technologies' ); ?>
						</div>
					</aside>

					<section class="listing-cta">
						<a href="<?php the_permalink(); ?>">View Details</a>
						<a href="<?php echo esc_url( $listing->url ); ?>">Visit Website</a>
					</section>
				</section>

			<?php endwhile; ?>

		<?php endif; ?>

	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>