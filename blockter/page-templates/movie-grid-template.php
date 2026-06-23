<?php
/**
 * Template Name: Movie Grid Template
 *
 * @package blockter
 */

get_header(); ?>
<main id="main" class="page_content flw blog-standard movie-list movie-grid">
	<div class="container">
		<div class="row flex-center">
			<?php if ( is_active_sidebar( 'sidebar_movie' ) ) : ?>

				<div class="col-md-8 col-lg-8">
					<?php
					if ( have_posts() ) :
						while ( have_posts() ) :
								the_post();
								get_template_part( 'content', 'movie-grid' );
							endwhile;
					else :
						get_template_part( 'content', 'none' );
					endif;
					?>
				</div><!-- .col -->

				<div class="col-md-4 col-lg-4">
					<?php get_sidebar( 'movie' ); ?>
				</div><!-- .col -->

			<?php else : ?>

				<div class="col-md-9 not-active-sidebar">
					<?php
					if ( have_posts() ) :
						while ( have_posts() ) :
							the_post();
							get_template_part( 'content', 'movie-grid' );
						endwhile;
					else :
						get_template_part( 'content', 'none' );
					endif;
					?>
				</div><!-- .col -->

			<?php endif; ?>
		</div><!-- .row -->
	</div><!-- .container -->
</main><!-- #main -->
<?php get_footer(); ?>
