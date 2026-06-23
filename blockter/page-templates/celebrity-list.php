<?php
/**
 * Template Name: Celebrity List
 *
 * @package blockter
 */
get_header(); ?>
<main id="main" class="page_content flw blog-standard celebrity-list-item">
	<div class="container">
		<div class="row flex-center">
			<?php if(is_active_sidebar('sidebar_celebrity')): ?>
				<div class="col-md-9 col-lg-9">
					<?php
						if (have_posts()) :
							while ( have_posts() ) : the_post();
								get_template_part( 'content', 'celebrity-list' );
							endwhile;
							// pagination
						else :
							get_template_part( 'content', 'none' );
						endif;
					?>
				</div>
				<div class="col-md-3 col-lg-3">
					<?php get_sidebar('celebrity'); ?>
				</div>
			<?php else: ?>
				<div class="col-md-9 not-active-sidebar">
					<?php if ( have_posts() ) :
						while ( have_posts() ) : the_post();
							get_template_part( 'content', 'celebrity-list');
						endwhile;
					else :
						get_template_part('content', 'none') ;
					endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>
