<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package blockter
 */
get_header(); ?>
<main id="main" class="page_content flwn blog-standard">
	<div class="container">
		<div class="row flex-center">
			<?php if(is_active_sidebar('blog-widget')): ?>
				<div class="col-md-9 col-lg-9">
					<?php
						if (have_posts()) :

							while ( have_posts() ) : the_post();
								get_template_part( 'content', get_post_format() );
							endwhile;
							// pagination
							blockter_paging_nav();
						else :
							get_template_part( 'content', 'none' );
						endif;
					?>
				</div>
				<div class="col-md-3 col-lg-3">
					<?php get_sidebar(); ?>
				</div>
			<?php else: ?>
					<div class="col-md-9 not-active-sidebar">
						<?php if ( have_posts() ) :
							while ( have_posts() ) : the_post();
								get_template_part( 'content', get_post_format() );

							endwhile;
							blockter_paging_nav();
						else :
							get_template_part('content', 'none') ;
						endif; ?>
					</div>


			<?php endif; ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>
