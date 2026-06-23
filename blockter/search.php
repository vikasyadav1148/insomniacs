<?php
/**
 * The Template for search page
 */
get_header();
?>

<main id="main" class="page_content flw blog-page blog-standard search_result_page">
	<div class="container">
		<div class="row">
			<?php if(is_active_sidebar('blog-widget')): ?>
			<div class="col-md-9 col-lg-9">
				<div class="theme-blog-single">
					<?php if ( have_posts() ) :
					?>
					<?php
						while ( have_posts() ) : the_post();
							get_template_part( 'content', get_post_format() );
						endwhile;

						global $wp_query;

						$big = 999999999; // need an unlikely integer

						echo paginate_links(
							array(
								'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
								'format'  => '?paged=%#%',
								'current' => max( 1, get_query_var( 'paged' ) ),
								'total'   => $wp_query->max_num_pages,
							)
						);
					else :
						get_template_part('content', 'none') ;
					endif; ?>
				</div>
			</div>
			<div class="col-md-3 col-lg-3">
				<?php get_sidebar(); ?>
			</div>
		<?php else:  ?>
			<div class="col-md-9 not-active-sidebar">
				<div class="theme-blog-single">
					<?php if ( have_posts() ) :
					?>
					<header class="page-header">
						<h3 class="page-title"><?php printf( esc_html__( 'Search results for: %s', 'blockter' ), get_search_query() ); ?></h3>
					</header><!-- .page-header -->
					<?php
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
