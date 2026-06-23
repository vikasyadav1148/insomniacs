<?php
/**
 *	Template Name: Blog List
 */
get_header() ?>
<main id="main" class="page_content blog-standard flw">
	<div class="container">
		<div class="row flex-center flex-col">
			<?php if(is_active_sidebar('blog-widget')): ?>
				<div class="col-md-9 flex-rowwrap">
					<?php
						$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
						$query = new WP_Query(array(
							'post_type' => 'post',
							'post_status' => 'publish',					
		    				'ignore_sticky_posts' => 1,
							'paged' => $paged,
							'showposts' => 6,
						));
						?>
						<div class="non-wrap-item row">
							<?php
								if ($query->have_posts()) :
									while ($query->have_posts() ) : $query->the_post();
										
										echo '<div class="col-md-12 col-lg-12 blog-grid-it">';
											get_template_part( 'content', get_post_format() );
										echo '</div>';
									
									endwhile;
							?>
						</div>
							<?php
							// pagination
							blockter_paging_nav($query);
							?>
						<?php
						else :
							get_template_part( 'content', 'none' );
						endif;
						wp_reset_postdata();
					?>
				</div>
				<div class="col-md-3">
					<?php get_sidebar(); ?>
				</div>
			<?php else:  ?>
			<div class="col-md-9 flex-rowwrap">
				<?php
					$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
					$query = new WP_Query(array(
						'post_type' => 'post',
						'post_status' => 'publish',					
						'ignore_sticky_posts' => 1,
						'paged' => $paged,
						'showposts' => 6,
					));
					?>
					<div class="non-wrap-item row">
						<?php
							if ($query->have_posts()) :
								while ($query->have_posts() ) : $query->the_post();
									
									echo '<div class="col-md-12 col-lg-12 blog-grid-it">';
										get_template_part( 'content', get_post_format() );
									echo '</div>';
								
								endwhile;
						?>
					</div>
						<?php
						// pagination
						blockter_paging_nav($query);
						?>
					<?php
					else :
						get_template_part( 'content', 'none' );
					endif;
					wp_reset_postdata();
				?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>
