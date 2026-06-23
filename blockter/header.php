<?php
ob_start();
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package blockter
 */

$c_lg = get_theme_mod('logo_img', '');

?><!DOCTYPE html>
<html <?php language_attributes(); ?> itemscope itemtype="http://schema.org/WebPage">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5042988809647798">
     crossorigin="anonymous"></script> -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5042988809647798" crossorigin="anonymous"></script>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	}
?>
<?php  $has_preloading_effect = get_theme_mod('loading', 0);?>
<?php if($has_preloading_effect != '0'): ?>

<!--preloading-->
<div id="preloader">
	<img src="<?php echo esc_url($c_lg); ?>" alt="<?php esc_attr_e("logo image", 'blockter'); ?>" width="119" height="58">
	<div id="status">
		<span></span>
		<span></span>
	</div>
</div>
<?php endif; ?>
<div class="overlay">
	<!-- login -->
	<div class="wrapper login-form">
		<h3 class="form-title"><?php echo esc_html__('login', 'blockter'); ?></h3>
		<?php wp_login_form(); ?>
		<div class="signup-link">
			<span class="signup-btn"><a href="<?php echo wp_registration_url(); ?>"><?php echo esc_html__( 'Sign Up', 'blockter') ?></a></span>
		</div>
		
		<?php
			if ( ! function_exists( 'is_plugin_active' ) )
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
			if( defined('APSL_VERSION') ) {
				// Plugin is active
				echo do_shortcode('[apsl-login-lite login_text="Or via social"]');
			}
		?>
		<div class="Social-Icons">
			<h4 class="title">Or via Socials</h4>
<?php
		echo do_shortcode('[miniorange_social_login shape="round" theme="default" space="12" size="40"]');
		?>
		</div>
		<a href="#" class="close"><i class="ion-close"></i></a>
	</div>
</div>

<?php
blockter_header_layout();


