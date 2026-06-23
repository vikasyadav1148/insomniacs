<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Direct access forbidden.' ); }
/**
 * Include static files: javascript and css
 */

if ( is_admin() ) {
	return;
}
// Load our main stylesheet. Version comes from the active theme's style.css header
// so bumping "Version:" there updates the query string and busts browser cache.
$blockter_theme_version = wp_get_theme()->get( 'Version' );
if ( ! $blockter_theme_version ) {
	$blockter_theme_version = '1.0';
}
wp_enqueue_style(
	'blockter-theme-style',
	get_stylesheet_uri(),
	array(),
	$blockter_theme_version
);

$custom_bg = blockter_custom_page_header_bg();
if ( $custom_bg ) {
	$custom_css = ".blockter-breadcrumb { background-image:url('$custom_bg') !important; }";
	wp_add_inline_style( 'blockter-theme-style', $custom_css );
}

if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
	wp_enqueue_script( 'comment-reply' );
}
//superfish style menu dropdown
wp_enqueue_script( 'jquery-superfish', get_template_directory_uri() . '/js/superfish.js', array( 'jquery' ), '1.7.5', true );

wp_enqueue_script(
	'blockter-countdown',
	get_template_directory_uri() . '/js/countdown.js',
	array( 'jquery' ),
	'1.0',
	true
);
wp_enqueue_script(
	'dropkick',
	get_template_directory_uri() . '/js/dropkick.min.js',
	array( 'jquery' ),
	'',
	true
);
wp_enqueue_script( 'enquire.js', get_template_directory_uri() . '/js/enquire.min.js', array(), '2.1.6', true );
wp_enqueue_script(
	'fancybox',
	get_template_directory_uri() . '/js/fancybox.min.js',
	array( 'jquery' ),
	'2.1.5',
	true
);
wp_enqueue_script(
	'plyr',
	get_template_directory_uri() . '/js/plyr.min.js',
	array( 'jquery' ),
	'2.0.11',
	true
);
wp_enqueue_script(
	'slick',
	get_template_directory_uri() . '/js/slick.min.js',
	array( 'jquery' ),
	'',
	true
);
wp_enqueue_script(
	'slimmenu',
	get_template_directory_uri() . '/js/slimmenu.min.js',
	array( 'jquery' ),
	'',
	true
);
wp_enqueue_script(
	'themetab',
	get_template_directory_uri() . '/js/themetab.min.js',
	array( 'jquery' ),
	'',
	true
);
// Font Awesome stylesheet
wp_enqueue_style(
	'font-awesome',
	get_template_directory_uri() . '/css/font-awesome.min.css',
	array(),
	'4.4.0',
	'all'
);

wp_enqueue_script(
	'widgets-js',
	get_template_directory_uri() . '/js/widgets.js',
	array( 'jquery' ),
	'2.3.0',
	true
);
wp_enqueue_script(
	'blockter-script',
	get_template_directory_uri() . '/js/functions.js',
	array( 'jquery' ),
	'1.0',
	true
);

wp_enqueue_script(
	'blockter-custom-js',
	get_template_directory_uri() . '/js/custom.js',
	array( 'jquery' ),
	'1.0',
	true
);
/*enqueue font*/
wp_enqueue_style( 'google-font-dosis-nunito', '//fonts.googleapis.com/css?family=Dosis:400,700,500|Nunito:300,400,600&subset=latin,latin-ext', array(), '2014-12-20', 'all' );
