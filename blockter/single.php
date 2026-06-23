<?php
/**
 * The Template for displaying all single posts
 */
get_header();
?>

<main id="main" class="page_content flw blog-page page-background">
	<div class="container">
		<div class="row flex-center">
			<?php if(is_active_sidebar('blog-widget')): ?>
			<div class="col-md-9 col-lg-9">
				<div class="theme-blog-single">
					<?php if ( have_posts() ) :
						while ( have_posts() ) : the_post();
							get_template_part( 'content', get_post_format() );
						endwhile;
					else :
						get_template_part('content', 'none') ;
					endif; ?>
				</div>
			</div>
			<div class="col-md-3 col-lg-3">
				<?php get_sidebar(); ?>
			</div>
		<?php else:  ?>
			<div class="not-active-sidebar">
				<div class="theme-blog-single">
					<?php if ( have_posts() ) :
						while ( have_posts() ) : the_post();
							get_template_part( 'content', get_post_format() );
						endwhile;
					else :
						get_template_part('content', 'none') ;
					endif; ?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>
