<?php
/**
 * Template Name: Movie Grid Fullwidth Template
 *
 * @package blockter
 */

get_header(); ?>
<main id="main" class="page_content flw blog-standard movie-list movie-grid-fw">
	<div class="container">
		<div class="row flex-center">
			<div class="col-md-12 col-lg-12">
				<?php
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
						get_template_part( 'content', 'movie-grid-fw' );
					endwhile;
				else :
					get_template_part( 'content', 'none' );
				endif;
				?>
			</div><!-- .col -->
		</div><!-- .row -->
	</div><!-- .container -->
</main><!-- #main -->
<?php get_footer(); ?>
