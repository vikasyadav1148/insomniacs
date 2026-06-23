<?php
/**
 * Template Name: My Account page
 */

get_header(); ?>
<div class="tax-mv_actor">
	<div class="movie_single">
		<div class="container">
			<div class="movie-single">
				<div class="row">

					<div class="col-md-3">
						<?php get_template_part( 'template-parts/my-account/navigation-v2' ); ?>
					</div><!-- .col -->

					<div class="col-md-9 user-pro">
						<?php if ( ! is_user_logged_in() ) : ?>
							<div class="unauth-portal-landing" style="background: rgba(13, 14, 18, 0.95); border: 1px solid rgba(220, 248, 54, 0.15); border-radius: 16px; padding: 48px 32px; text-align: center; font-family: monospace; max-width: 580px; margin: 40px auto; box-shadow: 0 15px 40px rgba(0,0,0,0.5);">
								<div class="shield-halo" style="width: 64px; height: 64px; border-radius: 50%; background: rgba(220, 248, 54, 0.05); border: 2px solid #dcf836; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; box-shadow: 0 0 20px rgba(220,248,54,0.3);">
									<span style="font-size: 28px;">👤</span>
								</div>
								<h2 style="color: #fff; font-size: 20px; font-weight: 900; letter-spacing: 0.5px; margin-bottom: 12px; text-transform: uppercase;">PORTAL ACCESS: SECURE PASS</h2>
								<p style="color: #8b92a6; font-size: 12px; line-height: 1.7; margin-bottom: 32px; max-width: 440px; margin-left: auto; margin-right: auto;">
									Gain full database authorization to wishlist upcoming movies & series, follow superstar actors, track live release countdown planners, and synchronize your cinematic feeds flawlessly across any terminal.
								</p>
								<div style="display: flex; flex-direction: column; gap: 12px; max-width: 280px; margin: 0 auto;">
									<a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" class="btn-main" style="background: #dcf836; color: #000; font-weight: 900; font-size: 11px; text-transform: uppercase; padding: 14px 24px; border-radius: 8px; text-decoration: none; display: block; border: 1px solid #dcf836; text-align: center; transition: all 0.2s; box-shadow: 0 4px 15px rgba(220,248,54,0.3);">
										SECURE PORTAL LOGIN
									</a>
									<a href="<?php echo esc_url( wp_registration_url() ); ?>" style="background: transparent; color: #dcf836; font-weight: bold; font-size: 11px; text-transform: uppercase; padding: 12px; border-radius: 6px; text-decoration: none; border: 1px solid rgba(220,248,54,0.15); text-align: center; display: block; transition: all 0.2s;">
										CREATE AN ACCOUNT
									</a>
								</div>
							</div>
						<?php else : ?>
							<?php
							$current_section = get_query_var( 'section' );
							if ( $current_section ) {
								get_template_part( 'template-parts/my-account/' . $current_section );
							} else {
								get_template_part( 'template-parts/my-account/user-profile' );
							}
							?>
						<?php endif; ?>
					</div><!-- .col -->

				</div><!-- .row -->
			</div><!-- .movie-single -->
		</div><!-- .container -->
	</div><!-- .movie-single -->
	<div class="clear-both"></div>
</div><!-- .tab-mv_actor -->

<script>
document.addEventListener('DOMContentLoaded', function () {
	var selectors = [
		'.ins-account-nav a span:last-child',
		'.footer-widget-it a',
		'nav a',
		'a'
	];

	selectors.forEach(function (selector) {
		document.querySelectorAll(selector).forEach(function (node) {
			if (node && node.textContent && node.textContent.trim() === 'Movies Watchlist') {
				node.textContent = 'Favourite Movies';
			}
		});
	});
});
</script>

<?php
get_footer();
