<?php
/**
 * The template for displaying 404 pages (Not Found)
 */
get_header(); ?>
<main id="main" class="flw">
	<div class="error-page flw">
		<div class="container notfound-container">
			<div class="error-ct">

				<div class="error-content flw">
					<a class="lg" href="<?php echo esc_url(home_url('/')); ?>" itemprop="url">
						<img src="<?php echo get_template_directory_uri(); ?>/images/lg.png" alt="<?php esc_attr_e("logo image", 'blockter'); ?>">
					</a>
					<div class="content-404">
<!-- 						<img class="error-image" src="<?php # echo get_template_directory_uri(); ?>/images/error-image.png" alt="<?php # esc_attr_e("error image", 'blockter'); ?>"> -->
						<div class="full-404-bg">
    <img src="https://insomniacs.party/wp-content/uploads/2026/04/insomniacs_404_upscaled.png" alt="<?php esc_attr_e( 'Page Not Found', 'blockter' ); ?>">
</div>
						<h1 class="error-title"><?php esc_html_e('Page not found', 'blockter'); ?></h1>
					</div>
					<a href="<?php echo esc_url(home_url('/')); ?>" class="page-error-btn theme-btn-animation"><?php esc_html_e('GO HOME', 'blockter'); ?></a>


				</div>

			</div>
		</div>
	</div>
</main>
<?php get_footer(); ?>

