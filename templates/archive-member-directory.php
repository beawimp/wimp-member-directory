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
				<h1 class="page-title">Member Directory</h1>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
				/* Include the Post-Format-specific template for the content.
				 * If you want to overload this in a child theme then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				the_content();
				?>

			<?php endwhile; ?>

			<?php //wimp_content_nav( 'nav-below' ); ?>

		<?php else : ?>

			<?php //get_template_part( 'no-results', 'archive' ); ?>

		<?php endif; ?>

	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>