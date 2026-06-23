<?php
/**
 * The template for displaying all pages
 */

get_header(); ?>
	<main id="main" class="page_content flw">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php
				$vc = get_post_meta($post->ID, '_wpb_vc_js_status', true);
				$fw_page_layout = ( function_exists('bridge_sidebars_get_current_position') ) ? bridge_sidebars_get_current_position() : 'full';
				if( function_exists('fw_ext_page_builder_is_builder_post')  && fw_ext_page_builder_is_builder_post($post->ID) ) {
					// When page builder activate and sidebar extension
					switch ($fw_page_layout) {
						case 'left':
							echo '<div class="container">
							<div class="row">';
								echo '<div class="col-md-3 col-lg-3">';
									get_sidebar('content');
								echo '</div>';

								echo '<div class="col-md-9 col-lg-9">';
									get_template_part('content', 'page');
								echo '</div>';

							echo '</div></div>';
							break;

						case 'right':
							echo '<div class="container">
							<div class="row">';
								echo '<div class="col-md-9 col-lg-9">';
									get_template_part('content', 'page');
								echo '</div>';
								echo '<div class="col-md-3 col-lg-3">';
									get_sidebar('content');
								echo '</div>';
							echo '</div></div>';
							break;

						default:
							get_template_part('content', 'page');
							break;
						}

				}else{
					switch ($fw_page_layout) {
						case 'left':
							echo '<div class="container">
							<div class="row">';
								echo '<div class="col-md-3 col-lg-3">';
									get_sidebar('content');

								echo '</div>';

								echo '<div class="col-md-9 col-lg-9">';
									get_template_part('content', 'page');
									if ( comments_open() || get_comments_number() ) {
										comments_template();
									}
								echo '</div>';

							echo '</div></div>';
							break;

						case 'right':
							echo '<div class="container">
							<div class="row">';
								echo '<div class="col-md-9 col-lg-9">';
									get_template_part('content', 'page');
									if ( comments_open() || get_comments_number() ) {
										comments_template();
									}
								echo '</div>';
								echo '<div class="col-md-3 col-lg-3">';
									get_sidebar('content');
								echo '</div>';
							echo '</div></div>';
							break;

						default:
							echo '<div class="container">';
								if($vc == false):
									echo '<div class="blockter-start-page flw">';
									/*comment*/
									get_template_part('content', 'page');
									if ( comments_open() || get_comments_number() ) {
										comments_template();
									}
									echo '</div>';
								else:
									get_template_part('content', 'page');
								endif;
							echo '</div>';
						break;
					}
				}

				endwhile; ?>

			<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>
	</main>
<?php get_footer(); ?>
