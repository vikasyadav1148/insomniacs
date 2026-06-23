<?php
if ( ! isset( $content_width ) )
    $content_width = 640;

function blockter_content_width() {
    global $content_width;

    if ( is_page_template( 'full-width.php' ) )
        $content_width = 780;
}
add_action( 'template_redirect', 'blockter_content_width' );
/**
 * Filters and Actions
 */
if ( ! function_exists( 'blockter_action_theme_setup' ) ) :
{
	function blockter_action_theme_setup() {

		/*
		 * Make Theme available for translation.
		 */
		load_theme_textdomain( 'blockter', get_template_directory() . '/languages' );

		// Add RSS feed links to <head> for posts and comments.
		add_theme_support( 'automatic-feed-links' );
		// Enable support for Post Thumbnails, and declare two sizes.
		add_theme_support( 'post-thumbnails' );
		add_theme_support('title-tag');
		add_image_size('blockter-poster-movie-single', 500, 750, true);
		add_image_size('blockter-poster-movie-item', 342, 513, true);
		add_image_size('blockter-poster-movie-item-small', 92, 138, true);
		add_image_size('blockter-poster-movie-item-fw', 500, 750, true);
		add_image_size('blockter-post-thumbnail-list', 640, 360, true );
		add_image_size('blockter-cast-thumbnail', 500, 750, true );
		add_image_size('blockter-cast-thumbnail-list', 185, 278, true );
		add_image_size('blockter-cast-thumbnail-grid', 500, 750, true );
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption'
		) );

		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'audio',
			'quote',
			'link',
			'gallery',
		) );

		// This theme uses its own gallery styles.
		add_filter( 'use_default_gallery_style', '__return_false' );

		add_theme_support( 'woocommerce' );
	    add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
}
endif;
add_action( 'after_setup_theme', 'blockter_action_theme_setup' );
/* *
 * Search for movies template
 */

function blockter_search_movie_template($template) {
	global $wp_query;
	if ( isset( $_GET['search-type'] ) && isset($_GET['s']) ) {
		$search_type = $_GET['search-type'];

		if ( $search_type == 'ht_movie' ) {
			$template = locate_template('search-movie.php');
		}

		if ( $search_type == 'ht_show' ) {
			$template = locate_template('search-show.php');
		}

		if ( $search_type == 'cast' ) {
			$template = locate_template('search-cast.php');
		}

		if ( $search_type == 'mv_collection' ) {
			$template = locate_template('search-collection.php');
		}

		if ( $search_type == 'networks' ) {
			$template = locate_template('search-network.php');
		}

		if ( $search_type == 'mv_keyword' ) {
			$template = locate_template('search-keyword.php');
		}
	}
	return $template;
}
add_filter('template_include', 'blockter_search_movie_template');

/**
 * Get the URL of the page that lists all collections (uses Movie Collections Page template).
 *
 * @return string|false Collections page URL or false if no such page exists.
 */
function blockter_get_collections_page_url() {
	$pages = get_pages( array(
		'meta_key'   => '_wp_page_template',
		'meta_value' => 'page-templates/page-collections.php',
		'number'     => 1,
	) );
	if ( ! empty( $pages ) ) {
		return get_permalink( $pages[0]->ID );
	}
	return false;
}

/**
 * Insert "Collections" breadcrumb with link to collections list on mv_collection term pages.
 * Ensures breadcrumb is: Home → Collections → [Collection name].
 *
 * @param array $items Breadcrumb items (each with 'name' and optionally 'url').
 * @return array Modified breadcrumb items.
 */
function blockter_breadcrumbs_add_collections_link( $items ) {
	if ( ! is_tax( 'mv_collection' ) || empty( $items ) ) {
		return $items;
	}
	$collections_url = blockter_get_collections_page_url();
	if ( ! $collections_url ) {
		return $items;
	}
	$collections_item = array(
		'name' => esc_html__( 'Collections', 'blockter' ),
		'url'  => $collections_url,
	);
	// Insert after first item (Home): [Home, Collections, ...current term].
	array_splice( $items, 1, 0, array( $collections_item ) );
	return $items;
}
add_filter( 'fw_ext_breadcrumbs_build', 'blockter_breadcrumbs_add_collections_link' );

/**
 * Get the URL of the page that lists all networks (uses Networks Page template).
 *
 * @return string|false Networks page URL or false if no such page exists.
 */
function blockter_get_networks_page_url() {
	$pages = get_pages( array(
		'meta_key'   => '_wp_page_template',
		'meta_value' => 'page-templates/page-networks.php',
		'number'     => 1,
	) );
	if ( ! empty( $pages ) ) {
		return get_permalink( $pages[0]->ID );
	}
	return false;
}

/**
 * Insert "Networks" breadcrumb with link to networks list on networks taxonomy term pages.
 * Ensures breadcrumb is: Home → Networks → [Network name].
 *
 * @param array $items Breadcrumb items (each with 'name' and optionally 'url').
 * @return array Modified breadcrumb items.
 */
function blockter_breadcrumbs_add_networks_link( $items ) {
	if ( ! is_tax( 'networks' ) || empty( $items ) ) {
		return $items;
	}
	$networks_url = blockter_get_networks_page_url();
	if ( ! $networks_url ) {
		return $items;
	}
	$networks_item = array(
		'name' => esc_html__( 'Networks', 'blockter' ),
		'url'  => $networks_url,
	);
	// Insert after first item (Home): [Home, Networks, ...current term].
	array_splice( $items, 1, 0, array( $networks_item ) );
	return $items;
}
add_filter( 'fw_ext_breadcrumbs_build', 'blockter_breadcrumbs_add_networks_link' );

/**
 * Get the URL of the page that lists all genres (uses Genre Listing Page template).
 *
 * @return string|false Genres page URL or false if no such page exists.
 */
function blockter_get_genres_page_url() {
	$pages = get_pages( array(
		'meta_key'   => '_wp_page_template',
		'meta_value' => 'page-templates/page-genres.php',
		'number'     => 1,
	) );
	if ( ! empty( $pages ) ) {
		return get_permalink( $pages[0]->ID );
	}
	return false;
}

/**
 * Insert "Genres" breadcrumb with link to genres list on mv_genre taxonomy term pages.
 * Ensures breadcrumb is: Home → Genres → [Genre name].
 *
 * @param array $items Breadcrumb items (each with 'name' and optionally 'url').
 * @return array Modified breadcrumb items.
 */
function blockter_breadcrumbs_add_genres_link( $items ) {
	if ( ! is_tax( 'mv_genre' ) || empty( $items ) ) {
		return $items;
	}
	$genres_url = blockter_get_genres_page_url();
	if ( ! $genres_url ) {
		return $items;
	}
	$genres_item = array(
		'name' => esc_html__( 'Genres', 'blockter' ),
		'url'  => $genres_url,
	);
	// Insert after first item (Home): [Home, Genres, ...current term].
	array_splice( $items, 1, 0, array( $genres_item ) );
	return $items;
}
add_filter( 'fw_ext_breadcrumbs_build', 'blockter_breadcrumbs_add_genres_link' );


add_filter( 'rank_math/json_ld', function( $data, $jsonld ) {
    if ( isset( $data['breadcrumb'] ) ) {
        unset( $data['breadcrumb'] );
    }
    return $data;
}, 99, 2 );

/**
 * Whether the current request is an mv_keyword term archive with an empty (full) term description.
 *
 * @return bool
 */
function blockter_mv_keyword_is_empty_description_archive() {
	if ( ! is_tax( 'mv_keyword' ) ) {
		return false;
	}
	$term = get_queried_object();
	if ( ! $term instanceof WP_Term || ( isset( $term->taxonomy ) && 'mv_keyword' !== $term->taxonomy ) ) {
		return false;
	}
	return '' === trim( (string) $term->description );
}

/**
 * Rank Math: noindex/follow keyword term pages when the term has no description.
 *
 * @param array $robots Robots meta parts (Rank Math format).
 * @return array
 */
function blockter_mv_keyword_empty_description_rank_math_robots( $robots ) {
	if ( ! blockter_mv_keyword_is_empty_description_archive() ) {
		return $robots;
	}
	unset( $robots['index'] );
	$robots['noindex'] = 'noindex';
	$robots['follow']  = 'follow';
	return $robots;
}

/**
 * Core wp_robots fallback when Rank Math is not active.
 *
 * @param array $robots Associative array of robot directives.
 * @return array
 */
function blockter_mv_keyword_empty_description_wp_robots( $robots ) {
	if ( ! blockter_mv_keyword_is_empty_description_archive() ) {
		return $robots;
	}
	$robots['noindex'] = true;
	$robots['follow']  = true;
	unset( $robots['index'] );
	return $robots;
}

if ( defined( 'RANK_MATH_VERSION' ) ) {
	add_filter( 'rank_math/frontend/robots', 'blockter_mv_keyword_empty_description_rank_math_robots', 100 );
} else {
	add_filter( 'wp_robots', 'blockter_mv_keyword_empty_description_wp_robots', 100 );
}

/**
 * Get the URL of the page that lists all cast/celebrities (uses Celebrity List or Celebrity Grid template).
 *
 * @return string|false Cast list page URL or false if no such page exists.
 */
function blockter_get_cast_page_url() {
	$templates = array( 'page-templates/celebrity-list.php', 'page-templates/celebrity-grid.php' );
	foreach ( $templates as $template ) {
		$pages = get_pages( array(
			'meta_key'   => '_wp_page_template',
			'meta_value' => $template,
			'number'     => 1,
		) );
		if ( ! empty( $pages ) ) {
			return get_permalink( $pages[0]->ID );
		}
	}
	return false;
}

/**
 * Insert "Cast" breadcrumb with link to cast list on mv_actor taxonomy term pages.
 * Ensures breadcrumb is: Home → Cast → [Actor name].
 *
 * @param array $items Breadcrumb items (each with 'name' and optionally 'url').
 * @return array Modified breadcrumb items.
 */
function blockter_breadcrumbs_add_cast_link( $items ) {
	if ( ! is_tax( 'mv_actor' ) || empty( $items ) ) {
		return $items;
	}
	$cast_url = blockter_get_cast_page_url();
	if ( ! $cast_url ) {
		return $items;
	}
	$cast_item = array(
		'name' => esc_html__( 'Cast', 'blockter' ),
		'url'  => $cast_url,
	);
	// Insert after first item (Home): [Home, Cast, ...current term].
	array_splice( $items, 1, 0, array( $cast_item ) );
	return $items;
}
add_filter( 'fw_ext_breadcrumbs_build', 'blockter_breadcrumbs_add_cast_link' );

/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Presence of header image.
 * 3. Index views.
 * 4. Full-width content layout.
 * 5. Presence of footer widgets.
 * 6. Single views.
 * 7. Featured content layout.
 *
 * @param array $classes A list of existing body class values.
 *
 * @return array The filtered body class list.
 * @internal
 */
function blockter_filter_theme_body_classes( $classes ) {
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( get_header_image() ) {
		$classes[] = 'header-image';
	} else {
		$classes[] = 'masthead-fixed';
	}

	if ( is_archive() || is_search() || is_home() ) {
		$classes[] = 'list-view';
	}

	if ( function_exists('blockter_sidebars_get_current_position') ) {
		$current_position = blockter_sidebars_get_current_position();
		if ( in_array( $current_position, array( 'full', 'left' ) )
		     || empty($current_position)
		     || is_page_template( 'page-templates/full-width.php' )
		     || is_page_template( 'page-templates/contributors.php' )
		     || is_attachment()
		) {
			$classes[] = 'full-width';
		}
	} else {
		$classes[] = 'full-width';
	}

	if ( is_active_sidebar( 'blog-widget' ) ) {
		$classes[] = 'footer-widgets';
	}

	if ( is_singular() && ! is_front_page() ) {
		$classes[] = 'singular';
	}


	// $c_sticky_footer = (function_exists('fw_get_db_customizer_option')) ? fw_get_db_customizer_option('c_sticky_footer') : 'no';
	// if($c_sticky_footer == 'yes'){
	// 	$classes[] = 'sticky-footer';
	// }
	return $classes;
}

add_filter( 'body_class', 'blockter_filter_theme_body_classes' );

/**
 * Extend the default WordPress post classes.
 *
 * Adds a post class to denote:
 * Non-password protected page with a post thumbnail.
 *
 * @param array $classes A list of existing post class values.
 *
 * @return array The filtered post class list.
 * @internal
 */
function blockter_filter_theme_post_classes( $classes ) {
	if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() ) {
		$classes[] = 'has-post-thumbnail';
	}

	return $classes;
}

add_filter( 'post_class', 'blockter_filter_theme_post_classes' );

/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 *
 * @return string The filtered title.
 * @internal
 */
function blockter_filter_theme_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( esc_html__( 'Page %s', 'blockter'), max( $paged, $page ) );
	}

	return $title;
}

add_filter( 'wp_title', 'blockter_filter_theme_wp_title', 10, 2 );

/**
 * Use Biography instead of Description for mv_actor term description (Open Graph, meta description, archive).
 *
 * @param string $term_desc Current term description.
 * @param int    $term_id   Term ID.
 * @return string Filtered description (Biography when available for actors).
 */
function blockter_actor_term_description_use_biography( $term_desc, $term_id ) {
	$term = get_term( $term_id );
	if ( ! $term || is_wp_error( $term ) || $term->taxonomy !== 'mv_actor' ) {
		return $term_desc;
	}
	if ( ! function_exists( 'blockter_sd_get_term_option' ) ) {
		return $term_desc;
	}
	$cast_opts = blockter_sd_get_term_option( (int) $term_id, 'mv_actor' );
	if ( ! empty( $cast_opts['biography'] ) ) {
		return $cast_opts['biography'];
	}
	return $term_desc;
}
add_filter( 'term_description', 'blockter_actor_term_description_use_biography', 10, 2 );

/**
 * Flush out the transients used in fw_theme_categorized_blog.
 * @internal
 */
function blockter_action_theme_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'blockter_theme_category_count' );
}

add_action( 'edit_category', 'blockter_action_theme_category_transient_flusher' );
add_action( 'save_post', 'blockter_action_theme_category_transient_flusher' );

/**
 * Theme Customizer support
 */
{
	/**
	* Disable/Enable default section in customizer
	*/
	global  $wp_customize;
	if ( isset($wp_customize) && $wp_customize->is_preview() ) {
		function blockter_customizer_remove_sections( $wp_customize ) {
			$wp_customize->remove_section( 'featured_content' );
			$wp_customize->remove_control('header_textcolor');
			$wp_customize->remove_control('background_color');
			$wp_customize->remove_section('background_image');
			$wp_customize->remove_section('header_image');
		}
		add_action( 'customize_register' , 'blockter_customizer_remove_sections' );
	}
}

/**
 * Register widget areas.
 * @internal
 */
function blockter_action_theme_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Blog Widget Area', 'blockter'),
		'id'            => 'blog-widget',
		'description'   => esc_html__( 'Appears in the blog sidebar section of the site.', 'blockter'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget Area', 'blockter'),
		'id'            => 'footer-widget',
		'description'   => esc_html__( 'Appears in the footer section of the site.', 'blockter'),
		'before_widget' => '<div class="footer-widget-it"><aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside></div>',
		'before_title'  => '<h4 class="footer-widget-title">',
		'after_title'   => '</h4>',
	) );
	// Register sidebar for celebrity page,
	register_sidebar( array(
		'id'            => 'sidebar_celebrity',
		'name'          => esc_html__( 'Celebrity sidebar', 'blockter' ),
		'description'   => esc_html__( 'The celebrity sidebar.', 'blockter' ),
		'before_widget' => '<aside id="%1$s" class="widget-celebrity cf %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	));
	//Register sidebar for movie page
	register_sidebar( array(
		'id'            => 'sidebar_movie',
		'name'          => esc_html__( 'Movie sidebar', 'blockter' ),
		'description'   => esc_html__( 'The movie sidebar.', 'blockter' ),
		'before_widget' => '<aside id="%1$s" class="widget-movie cf %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	));
}

add_action( 'widgets_init', 'blockter_action_theme_widgets_init' );
/**
 * Filter to wp_editor
 * to optimize fw_resize function
 */
add_filter( 'jpeg_quality', 'blockter_filter_theme_image_full_quality' );
add_filter( 'wp_editor_set_quality', 'blockter_filter_theme_image_full_quality' );

function blockter_filter_theme_image_full_quality( $quality ) {
	return 100;
}

// Register new option types
function blockter_include_custom_option_types() {
    get_template_part('/inc/includes/option-types/ht-switch/class-fw-option-type', 'ht-switch');
}
add_action('fw_option_types_init', 'blockter_include_custom_option_types');

add_action( 'admin_enqueue_scripts', 'blockter_deregister_woocommerce_setting', 99 );
/**
 * Fixed problem Unyson & YITH
 * conflict color picker
 * @return [type] [description]
 */
function blockter_deregister_woocommerce_setting(){
	$screen = get_current_screen();
	if ( $screen->post_type == 'page' ){
			//wp_deregister_script( 'woocommerce_settings' );
	}
}

/**
 * Install Demo content
 */
function blockter_backups_demos($demos) {
	$demos_array = array(
		'blockter' => array(
			'title' => esc_html__('Blockter Demo', 'blockter'),
			'screenshot' => get_template_directory_uri().'/screenshot.png',
			'preview_link' => 'boostifythemes.com/demo/wp/buster/',
		),
	);

	$download_url = 'https://boostifythemes.com/ht-demos/';

	foreach ($demos_array as $id => $data) {
		$demo = new FW_Ext_Backups_Demo($id, 'piecemeal', array(
			'url' => $download_url,
			'file_id' => $id,
		));
		$demo->set_title($data['title']);
		$demo->set_screenshot($data['screenshot']);
		$demo->set_preview_link($data['preview_link']);

		$demos[ $demo->get_id() ] = $demo;

		unset($demo);
	}

	return $demos;
}

add_filter('fw:ext:backups-demo:demos', 'blockter_backups_demos');

/* *
 * Custom checkout fields
 * $fields is passed via the filter
 */
function blockter_custom_checkout_fields( $fields ) {
     $fields['billing']['billing_first_name']['placeholder'] = 'FIRST NAME';
     $fields['billing']['billing_first_name']['label'] = '';
     $fields['billing']['billing_last_name']['placeholder'] = 'LAST NAME';
     $fields['billing']['billing_last_name']['label'] = '';
     $fields['billing']['billing_phone']['placeholder'] = 'PHONE NUMBER';
     $fields['billing']['billing_phone']['label'] = '';
     $fields['billing']['billing_email']['placeholder'] = 'EMAIL ID';
     $fields['billing']['billing_email']['label'] = '';
     $fields['billing']['billing_country']['placeholder'] = 'COUNTRY';
     $fields['billing']['billing_country']['label'] = '';
     $fields['billing']['billing_state']['placeholder'] = 'STATE';
     $fields['billing']['billing_state']['label'] = '';
     $fields['billing']['billing_address_1']['placeholder'] = 'STREET';
     $fields['billing']['billing_address_1']['label'] = '';
     $fields['billing']['billing_address_2']['placeholder'] = 'APARTMENT';
     $fields['billing']['billing_address_2']['label'] = '';
     $fields['billing']['billing_city']['placeholder'] = 'CITY';
     $fields['billing']['billing_city']['label'] = '';
     $fields['billing']['billing_postcode']['placeholder'] = 'POSTAL CODE';
     $fields['billing']['billing_postcode']['label'] = '';
     $fields['billing']['billing_company']['placeholder'] = 'COMPANY NAME';
     $fields['billing']['billing_company']['label'] = '';
     $fields['order']['order_comments']['label'] = 'ORDER NOTE';
     return $fields;
}

add_filter( 'woocommerce_checkout_fields' , 'blockter_custom_checkout_fields' );
/**
 * Add some text before donation form's amount section fields.
 *
 * @param   Charitable_Form $form
 * @return  void
 */
function blockter_add_description_to_donation_amount( $fields ) {
    $fields[ 'description_field' ] = array(
        'type'          => 'paragraph',
        'priority'      => 0,
        'fullwidth'     => true,
        'content'       => esc_html__( 'Please specify the amount of money you want to donate for this cause. You can choose one of these options below:', 'blockter' )
    );
    return $fields;
}
add_filter( 'charitable_donation_form_donation_fields', 'blockter_add_description_to_donation_amount' );

/**
 * Add custom query vars
 */
function blockter_register_query_vars ( $vars ) {
	$vars[] = 'section';
	$vars[] = 'topsortby';
	$vars[] = 'search-type';
	return $vars;
}
add_filter( 'query_vars', 'blockter_register_query_vars' );

/**
 * Set custom post meta for every single movie/tv-show
 * for filtering by rating purpose.
 *
 * @param $post_id
 */
function blockter_set_movie_rating() {

	if ( is_singular( array( 'ht_movie', 'ht_show' ) ) ) {
		global $post;
		$post_id = $post->ID;
		$rating_key   = 'blockter_rating';
		$rating_value = intval( fw_ext_feedback_stars_get_post_rating()['average'] );
		update_post_meta( $post_id, $rating_key, $rating_value );
	}
}
add_action( 'wp_head', 'blockter_set_movie_rating' );

// To keep the count accurate, lets get rid of prefetching
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );


/**
 * Modify pagination links for search pages.
 *
 * Rewrite `page/2` to `paged=2`
 */
add_filter( 'paginate_links', 'blockter_search_pagination_mod', 1 );

function blockter_search_pagination_mod( $link )
{

	if ( is_search() ) {

		$pattern = '/page\/([0-9]+)\//';

		if ( preg_match( $pattern, $link, $matches ) ) {
			$number = $matches[ 1 ];

			$link = remove_query_arg( 'paged' );

			$link = add_query_arg( 'paged', $number );

		}
	}

	return $link;
}
