<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Direct access forbidden.' ); }
/**
 * Getter function for Featured Content Plugin.
 *
 * @return array An array of WP_Post objects.
 */
define( 'BUSTER_VER', wp_get_theme()->get( 'Version' ) );

add_action( 'body_class', 'buster_ver' );

function buster_ver( $classes ) {
	$classes[] = 'buster-ver-' . BUSTER_VER;
	if ( is_active_sidebar( 'blog-widget' ) ) {
		$classes[] = 'has-sidebar';
		$classes[] = 'right-sidebar';
	} else {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}

function blockter_get_featured_posts() {
	/**
	 * @param array|bool $posts Array of featured posts, otherwise false.
	 */
	return apply_filters( 'blockter_get_featured_posts', array() );
}

/**
 * A helper conditional function that returns a boolean value.
 *
 * @return bool Whether there are featured posts.
 */
function blockter_has_featured_posts() {
	return ! is_paged() && (bool) blockter_get_featured_posts();
}

if ( ! function_exists('blockter_the_attached_image') ) : /**
 * Print the attached image with a link to the next attached image.
 */ {
	function blockter_the_attached_image() {
		$post = get_post();
		/**
		 * Filter the default attachment size.
		 *
		 * @param array $dimensions {
		 *     An array of height and width dimensions.
		 *
		 * @type int $height Height of the image in pixels. Default 810.
		 * @type int $width Width of the image in pixels. Default 810.
		 * }
		 */
		$attachment_size     = apply_filters( 'blockter_attachment_size', array( 810, 810 ) );
		$next_attachment_url = wp_get_attachment_url();

		/*
		 * Grab the IDs of all the image attachments in a gallery so we can get the URL
		 * of the next adjacent image in a gallery, or the first image (if we're
		 * looking at the last image in a gallery), or, in a gallery of one, just the
		 * link to that image file.
		 */
		$attachment_ids = get_posts( array(
			'post_parent'    => $post->post_parent,
			'fields'         => 'ids',
			'numberposts'    => - 1,
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => 'ASC',
			'orderby'        => 'menu_order ID',
		) );

		// If there is more than 1 attachment in a gallery...
		if ( count( $attachment_ids ) > 1 ) {
			foreach ( $attachment_ids as $attachment_id ) {
				if ( $attachment_id == $post->ID ) {
					$next_id = current( $attachment_ids );
					break;
				}
			}

			// get the URL of the next image attachment...
			if ( $next_id ) {
				$next_attachment_url = get_attachment_link( $next_id );
			} // or get the URL of the first image attachment.
			else {
				$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
			}
		}

		printf( '<a href="%1$s" rel="attachment">%2$s</a>',
			esc_url( $next_attachment_url ),
			wp_get_attachment_image( $post->ID, $attachment_size )
		);
	}
}
endif;

if ( ! function_exists( 'blockter_list_authors' ) ) : /**
 * Print a list of all site contributors who published at least one post.
 */ {
	function blockter_list_authors() {
		$contributor_ids = get_users( array(
			'fields'  => 'ID',
			'orderby' => 'post_count',
			'order'   => 'DESC',
			'who'     => 'authors',
		) );

		foreach ( $contributor_ids as $contributor_id ) :
			$post_count = count_user_posts( $contributor_id );

			// Move on if user has not published a post (yet).
			if ( ! $post_count ) {
				continue;
			}
			?>

			<div class="contributor">
				<div class="contributor-info">
					<div class="contributor-avatar"><?php echo get_avatar( $contributor_id, 132 ); ?></div>
					<div class="contributor-summary">
						<h2 class="contributor-name"><?php echo get_the_author_meta( 'display_name',
								$contributor_id ); ?></h2>

						<p class="contributor-bio">
							<?php echo get_the_author_meta( 'description', $contributor_id ); ?>
						</p>
						<a class="button contributor-posts-link"
						   href="<?php echo esc_url( get_author_posts_url( $contributor_id ) ); ?>">
							<?php printf( _n( '%d Article', '%d Articles', $post_count, 'blockter' ), $post_count ); ?>
						</a>
					</div>
					<!-- .contributor-summary -->
				</div>
				<!-- .contributor-info -->
			</div><!-- .contributor -->

		<?php
		endforeach;
	}
}
endif;

/**
 * Custom template tags
 */
{
	if ( ! function_exists( 'blockter_paging_nav' ) ) : /**
	 * Display navigation to next/previous set of posts when applicable.
	 */ {
		function blockter_paging_nav( $wp_query = null ) {

			if ( ! $wp_query ) {
				$wp_query = $GLOBALS['wp_query'];
			}

			// Don't print empty markup if there's only one page.

			if ( $wp_query->max_num_pages < 2 ) {
				return;
			}

			$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
			$pagenum_link = html_entity_decode( get_pagenum_link() );
			$query_args   = array();
			$url_parts    = explode( '?', $pagenum_link );

			if ( isset( $url_parts[1] ) ) {
				wp_parse_str( $url_parts[1], $query_args );
			}

			$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
			$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

			$format = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link,
				'index.php' ) ? 'index.php/' : '';
			$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%',
				'paged' ) : '?paged=%#%';

			// Set up paginated links.
			$links = paginate_links( array(
				'base'      => $pagenum_link,
				'format'    => $format,
				'total'     => $wp_query->max_num_pages,
				'current'   => $paged,
				'mid_size'  => 1,
				'add_args'  => array_map( 'urlencode', $query_args ),
				'prev_text' => esc_html__( '<', 'blockter' ),
				'next_text' => esc_html__( '>', 'blockter' ),
			) );

			if ( $links ) :

				?>
				<nav class="paging-navigation">
					<h1 class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'blockter'); ?></h1>

						<?php echo wp_kses($links,
						array(
							'ul' => array(
								'class' => array(),
							),
							'li' => array(),
							'span' => array(
								'class' => array(),
							),
							'a' => array(
								'class' => array(),
								'href' => array(),
							),
						)); ?>
				</nav>
			<?php
			endif;
		}
	}
	endif;

	if ( ! function_exists( 'blockter_post_nav' ) ) : /**
	 * Display navigation to next/previous post when applicable.
	 */ {
		function blockter_post_nav() {
			// Don't print empty markup if there's nowhere to navigate.
			$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '',
				true );
			$next     = get_adjacent_post( false, '', false );

			if ( ! $next && ! $previous ) {
				return;
			}

			?>
			<nav class="navigation post-navigation" role="navigation">
				<h1 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'blockter' ); ?></h1>

				<div class="nav-links">
					<?php
					if ( is_attachment() ) :
						previous_post_link( '%link',
							esc_html__( '<span class="meta-nav">Published In</span>%title', 'blockter' ) );
					else :
						previous_post_link( '%link',
							esc_html__( '<span class="meta-nav">Previous Post</span>%title', 'blockter' ) );
						next_post_link( '%link', esc_html__( '<span class="meta-nav">Next Post</span>%title', 'blockter' ) );
					endif;
					?>
				</div>
				<!-- .nav-links -->
			</nav><!-- .navigation -->
		<?php
		}
	}
	endif;
}

/**
 * Custom template tags and functions by HAINTHEME
 */
{
	/**
	 * Custom comment output.
	 */
	function blockter_comment($comment, $args, $depth)
	{
		$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<div id="comment-<?php comment_ID(); ?>">

			<div class="comment-author vcard">
				<?php echo get_avatar(get_the_author_meta('ID'), $size = '72'); ?>
			</div>

			<div class="comment-content">

				<div class="comment-content-inner">
					<?php if ($comment->comment_approved == '0') : ?>
						<em><?php esc_html_e('Your comment is awaiting moderation.', 'blockter') ?></em>
						<br/>
					<?php endif; ?>

					<div class="metadata">
						<?php printf(esc_html__('<cite class="fn">%s</cite>', 'blockter'), get_comment_author_link()) ?>
						<p class="time"><a
								href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>">
								<?php printf(esc_html__('%1$s', 'blockter'), get_comment_date(), get_comment_time()) ?></a></p>
						<?php edit_comment_link(esc_html__('(Edit)', 'blockter'), '  ', '') ?>
						<?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
					</div>

					<div class="comment-text"><?php comment_text() ?></div>
				</div>
				<!-- /.comment-content-inner -->

			</div>
		</div>
	<?php
	}
}
/*logo*/
if(!function_exists('blockter_logo_image')){
	function blockter_logo_image(){
		$pid = get_queried_object_id();
		$p_lg = function_exists('fw_get_db_post_option') ? fw_get_db_post_option($pid, 'p_lg') : '';

		$c_lg = get_theme_mod('logo_img', '');
		$lg_img = '';

		$tag = 'div';
		if( is_front_page() ){
			$tag = 'h1';
		}

		if(isset($p_lg['gadget']) && $p_lg['gadget'] == 'custom'){
			$lg_img = $p_lg['custom']['lg_data']['url'];
		?>
			<<?php echo esc_attr($tag); ?> itemscope itemtype="http://schema.org/Organization" class="ht-logo">
				<a class="lg" href="<?php echo esc_url(home_url('/')); ?>" itemprop="url">
	                <img
	                	src="<?php echo esc_url($lg_img); ?>"
	                	alt="<?php esc_attr_e('Logo image', 'blockter'); ?>"
	                	itemprop="logo"
	                	width="161"
	                	height="43" >
	            </a>
	            <span class="screen-reader-text"><?php echo esc_attr( bloginfo( 'name' ) ); ?></span>
            </<?php echo esc_attr($tag); ?>>
        <?php
			}else{
				$lg_img = ($c_lg != '') ? $c_lg : get_template_directory_uri().'/images/lg.png';
		?>
			<<?php echo esc_attr($tag); ?> itemscope itemtype="http://schema.org/Organization" class="ht-logo">
				<a class="lg" href="<?php echo esc_url(home_url("/")); ?>" itemprop="url">
	                <img
	                	src="<?php echo esc_url($lg_img); ?>"
	                	alt="<?php esc_attr_e('Logo image', 'blockter'); ?>"
	                	itemprop="logo"
	                	width="161"
	                	height="43" >
	            </a>
	            <span class="screen-reader-text"><?php echo esc_attr( bloginfo( 'name' ) ); ?></span>
			</<?php echo esc_attr($tag); ?>>
		<?php
		}
	}
}
/* header layout */
if(!function_exists('blockter_header_layout')){
	function blockter_header_layout(){
		/*PAGE OPTIONS*/
		$pid = get_queried_object_id();
		$p_header_layout = (function_exists('fw_get_db_post_option')) ? fw_get_db_post_option($pid, 'page_header_layout') : '';

		/*CUSTOMIZER*/
		$c_header_layout = get_theme_mod('header_layout_cfg', 'layout-3');

		if(isset($p_header_layout['gadget']) && $p_header_layout['gadget'] != 'default' ){
			switch($p_header_layout['gadget']){
				case 'layout-1' :
					get_template_part('page-templates/header', 'layout-1');
					break;
				case 'layout-2' :
					get_template_part('page-templates/header', 'layout-2');
					break;
				case 'layout-3' :
					get_template_part('page-templates/header', 'layout-3');
				break;
			}
		}else{
			switch($c_header_layout){
				case 'layout-1' :
					get_template_part('page-templates/header', 'layout-1');
					break;
				case 'layout-2' :
					get_template_part('page-templates/header', 'layout-2');
					break;
				case 'layout-3' :
					get_template_part('page-templates/header', 'layout-3');
				break;
				default :
					get_template_part('page-templates/header', 'layout-3');
					break;

			}
		}
	}
}
/* Edit location *******************************************************************/
/*hader layout edit*/
if(!function_exists('blockter_header_edit_location')){
	function blockter_header_edit_location($id = ''){
		$option = $id.'_edit_location';
		if(is_customize_preview()):
			echo '<div id="'. esc_attr($id) .'-edit-location" class="header-edit-location">';
				if ( class_exists( 'Kirki' ) ){
					echo Kirki::get_option($option);
				}
			echo '</div>';
		endif;
	}
}

/*breadcrumbs layout edit location*/
if(!function_exists('blockter_bread_edit_location')){
	function blockter_bread_edit_location($id = ''){
		$option = $id.'_edit_location';
		if(is_customize_preview()):
			echo '<div id="' . esc_attr($id) . '-edit-location">';
				if ( class_exists( 'Kirki' ) ){
					echo Kirki::get_option($option);
				}
			echo '</div>';
		endif;
	}
}
function blockter_primary_menu_right() {
  register_nav_menu('primary-menu-right',__( 'Primary Menu Right', 'blockter'));
}
add_action( 'init', 'blockter_primary_menu_right' );
/*blog categories*/
if(!function_exists('blockter_blog_categories')):
	function blockter_blog_categories(){
		$categories_string = '';
		$categories = get_the_category();
		$separator = ', ';
		$output = '';
		if ( ! empty( $categories ) ) {
			foreach( $categories as $category ) {
				$output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( sprintf( esc_html__( 'View all posts in %s', 'blockter'), $category->name ) ) . '">' . esc_html($category->name ) . '</a>' . $separator;
			}
			$categories_string .= trim( $output, $separator );
		}
		return $categories_string;
	}
endif;
/*custom excerpt*/
if( ! function_exists('blockter_excerpt')):
	function blockter_excerpt($limit) {
		$excerpt = explode(' ', get_the_excerpt(), $limit);
		if (count($excerpt)>=$limit) {
			array_pop($excerpt);
			$excerpt = implode(" ",$excerpt).'...';
		} else {
			$excerpt = implode(" ",$excerpt);
		}
		$excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
		return $excerpt;
	}
endif;
/*Author Card*/
if(!function_exists('blockter_author_card')){
	function blockter_author_card(){
		if ( empty( $post ) && isset( $GLOBALS['post'] ) )
		$post = $GLOBALS['post'];
		$authordesc = get_the_author_meta( 'description' );
		if(!empty($authordesc)): ?>
			<div class="flw" itemscope itemtype="https://schema.org/CreativeWork">
				<div class="post-author-info-single" itemprop="author" itemscope itemtype="http://schema.org/Person">
					<div class="author-av"><?php echo get_avatar( $post->ID, $size = 123 ); ?></div>
					<div class="post-author-detail">
						<strong class="author-name" itemprop="name"><?php the_author(); ?></strong>
						<p class="author-desc" itemprop="description"><?php the_author_meta('description'); ?></p>
					</div>
				</div>
			</div><?php
		endif;
	}
}
/*post navigation*/
if ( ! function_exists('blockter_theme_post_nav') ) :
	function blockter_theme_post_nav() {
		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous ) {
			return;
		}
		if(!empty($previous)){
			$pre_link = get_permalink($previous);
			$pre_post_id = $previous->ID;
			$pre_post_thumb = wp_get_attachment_image_src( get_post_thumbnail_id($pre_post_id), 'thumbnail' );
		}
		if(!empty($next)){
			$next_link = get_permalink($next);
			$next_post_id = $next->ID;
			$next_post_thumb = wp_get_attachment_image_src( get_post_thumbnail_id($next_post_id), 'thumbnail' );
		}

		?>

		<div class="blog-related-post flw">
			<?php
			if( is_attachment() ):
			?>
			<?php if(!empty($previous)){ ?>
				<div class="prev-post">
					<?php if($pre_post_thumb != false) : ?>
						<div class="control-post-img">
							<img src="<?php echo esc_url($pre_post_thumb[0]); ?>"
								 alt="<?php esc_attr_e('Post nav thumb', 'blockter'); ?>">
						</div>
					<?php endif; ?>
					<div class="control-post-desc">
						<h3 class="control-post-name"><?php previous_post_link('%link'); ?></h3>
						<a href="<?php echo esc_url($pre_link); ?>" class="control-post-btn"><?php esc_html_e('Previous', 'blockter'); ?></a>
					</div>
				</div>
			<?php } ?>
			<?php else : ?>
			<?php if(!empty($previous)){ ?>
				<div class="prev-post">
					<?php if($pre_post_thumb != false) : ?>
						<div class="control-post-img">
							<img src="<?php echo esc_url($pre_post_thumb[0]); ?>"
								 alt="<?php esc_attr_e('Post nav thumb', 'blockter'); ?>">
						</div>
					<?php endif; ?>
					<div class="control-post-desc">
						<h3 class="control-post-name"><?php previous_post_link('%link'); ?></h3>
						<a href="<?php echo esc_url($pre_link); ?>" class="control-post-btn"><?php esc_html_e('Previous', 'blockter'); ?></a>
					</div>
				</div>
			<?php } ?>
			<?php if(!empty($next)){ ?>
				<div class="next-post">
					<?php if($next_post_thumb != false) : ?>
						<div class="control-post-img">
							<img src="<?php echo esc_url($next_post_thumb[0]); ?>"
								 alt="<?php esc_attr_e('Post nav thumb', 'blockter'); ?>">
						</div>
					<?php endif; ?>
					<div class="control-post-desc">
						<h3 class="control-post-name"><?php next_post_link('%link'); ?></h3>
						<a href="<?php echo esc_url($next_link); ?>" class="control-post-btn"><?php esc_html_e('Next', 'blockter'); ?></a>
					</div>
				</div>
			<?php } ?>
			<?php endif; ?>
		</div>
		<?php
	}
endif;
// blog sticky post
if(!function_exists('blockter_sticky_post')){
	function blockter_sticky_post(){
		if(!is_single()){
			if(is_sticky()): ?>
				<div class="sticky-post theme-sticky"><span><?php esc_html_e('STICKY', 'blockter'); ?></span></div>
			<?php
			endif;
		}
	}
}
/*post date*/
if ( ! function_exists( 'blockter_post_date' ) ) :
	function blockter_post_date() {
		$post_date = get_the_date('d');
		$post_m = get_the_date('M');
		$post_y = get_the_date('Y');

		$post_info = '<span class="date-day">'.$post_date.'</span>';
		$post_info .= '<span class="date-month">'.$post_m.'</span>';
		$post_info .= '<span class="date-year">'.$post_y.'</span>';

		return $post_info;
	}
endif;
/*Custom comment output*/
function blockter_comment_list($comment, $args, $depth){
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
		<div class="comment-post-pingback">
			<?php esc_html_e( 'Pingback:', 'blockter' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( 'Edit', 'blockter' ), '<span class="edit-link">', '</span>' ); ?>
		</div>
	<?php
		break;
		default :
	?>
	<div id="comment-<?php comment_ID(); ?>" class="comment-item" itemscope itemtype="http://schema.org/Comment">
		<div class="comment-avatar">
			<?php echo get_avatar($comment,$size='60'); ?>
		</div>
		<div class="comment-content">
			<div class="flex-it">
				<a href="#comment-<?php comment_ID(); ?>" itemprop="discussionUrl"><strong class="comment-author-name" itemprop="creator"><?php echo get_comment_author(); ?></strong></a>
				<div class="comment-time" itemprop="datePublished" content="<?php echo get_comment_date('c'); ?>"><?php echo get_comment_date(); ?></div>
			</div>
			<div class="comment-text" itemprop="about">
				<?php if ($comment->comment_approved == '0') : ?>
					<em><?php esc_html_e('Your comment is awaiting moderation.', 'blockter') ?></em>
				<?php endif; ?>
				<?php comment_text() ?>
			</div>
			<div class="flex-it">

					<?php edit_comment_link(esc_html__('Edit', 'blockter'), '  ', '') ?>
					<?php echo get_comment_reply_link(array_merge($args,array(
						'depth' => $depth,
						'reply_text' => '+ Reply',
						'max_depth' => $args['max_depth'])));
					?>
			</div>
		</div>
	</div>
<?php
	break;
	endswitch;
}
/*footer layout edit location*/
if(!function_exists('blockter_footer_edit_location')){
	function blockter_footer_edit_location($id = ''){
		$option = $id.'_edit_location';
		if(is_customize_preview()): ?>
			<div id="<?php echo esc_attr($id); ?>-edit-location">
				<?php
					if ( class_exists( 'Kirki' ) ){
						echo Kirki::get_option($option);
					}
				?>
			</div>
		<?php endif;
	}
}
/*footer widget*/
if(!function_exists('blockter_footer_sidebar')){
	function blockter_footer_sidebar(){
		if(is_active_sidebar('footer-widget')): ?>
			<div class="theme-footer-widget flw">
				<div class="container">
					<div class="row">
						<?php dynamic_sidebar('footer-widget'); ?>
					</div>
				</div>
			</div>
		<?php endif;
	}
}
/*footer display*/
if(!function_exists('blockter_footer_display')){
	function blockter_footer_display(){
		/*page id*/
		$pid = get_queried_object_id();
		$footer_data = (function_exists('fw_get_db_post_option')) ? fw_get_db_post_option($pid, 'footer_data') : '';
		$c_footer_display = get_theme_mod('c_footer_display', '1');

		if(isset($footer_data) && $footer_data != 'default'){
			switch($footer_data){
				case 'enable' :
					blockter_footer_sidebar();
					break;
				case 'disable' :
					break;
				}
		}else{
			switch($c_footer_display){
				case '1' :
				blockter_footer_sidebar();
					break;
				case '0' :
					break;
				default :
					blockter_footer_sidebar();
					break;
				}
		}
	}
}
/*custom search form widget*/
add_filter( 'get_search_form', 'blockter_search_form_widget', 100 );
function blockter_search_form_widget( $form ) {
    $form =
    '<form role="search" method="get" class="search-form" action="' . esc_url(home_url( '/' )) . '" >
	    <label class="screen-reader-text" for="s">' . esc_html__( 'Search for:', 'blockter' ) . '</label>
	    <input type="text" class="search-field" placeholder="'.esc_attr__('Enter Keyword', 'blockter').'" value="' . get_search_query() . '" name="s" id="s" />
	    <button type="submit" class="search-submit ion-android-search"></button>
    </form>';
    return $form;
}
/* VISUAL COMPOSER *******************************************************************/
/*Extend VC*/
if ( class_exists( 'WPBakeryVisualComposerAbstract' ) ) {
	function blockter_require_VC() {
		require_once get_template_directory() . '/inc/vc-include.php';
	}
	add_action( 'init', 'blockter_require_VC', 2 );
}
function time_ago( $type = 'post' ) {
    $d = 'comment' == $type ? 'get_comment_time' : 'get_post_time';
    return human_time_diff($d('U'), current_time('timestamp')) . " " . __('ago', 'blockter');
}
/*AJAX filter posts by taxonomy term========================================================================*/

function blockter_filter_scripts() {
	wp_enqueue_script( 'blockter-ajax', get_template_directory_uri() . '/js/ajax-filter-posts.js', array('jquery'), true);
	wp_localize_script( 'blockter-ajax', 'blockter', array(
		'nonce'    => wp_create_nonce( 'bobz' ),
		'ajax_url' => admin_url( 'admin-ajax.php' ),
	));
}
add_action('wp_enqueue_scripts', 'blockter_filter_scripts', 100);

function blockter_filter_posts() {
    if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'bobz' ) ) die('Permission denied');
    /*Default response*/
    $response = [
        'status'  => 500,
        'message' => 'Something is wrong, please try again later ...',
        'content' => false,
        'found'   => 0
    ];
    $tax   = sanitize_text_field($_POST['params']['tax']);
    $term  = sanitize_text_field($_POST['params']['term']);
    $media = sanitize_text_field($_POST['params']['media']);
    // $postins = sanitize_text_field($_POST['params']['postin']);
    // if( isset($_POST['params']['postin']) && isset($postins) ) {
    // 	$postin = explode(',', $postins);
    // } else {
    // 	$postin = array();
    // }
	$page = intval($_POST['params']['page']);
	$qty  = intval($_POST['params']['qty']);
	$row  = intval($_POST['params']['row']);

    /*Check if term exists*/
    if (!term_exists( $term, $tax) && $term != 'all-terms') :
        $response = [
            'status'  => 501,
            'message' => 'Term doesn\'t exist',
            'content' => 0
        ];
        die(json_encode($response));
    endif;

    if ($term == 'all-terms') :
        $tax_qry[] = [
            'taxonomy' => $tax,
            'field'    => 'slug',
            'terms'    => $term,
            'operator' => 'NOT IN'
        ];
    else :
        $tax_qry[] = [
            'taxonomy' => $tax,
            'field'    => 'slug',
            'terms'    => $term,
        ];
    endif;
    /*Setup query*/
    $args = [
        'paged'          => $page,
        'post_type'      => 'ht_' . $media,
        'post_status'    => 'publish',
        'posts_per_page' => $qty,
        'tax_query'      => $tax_qry,
        // 'post__in'       => $postin,
    ];
    $qry = new WP_Query( $args );
    ob_start();
        if ($qry->have_posts()) : ?>

	        	<div class="movie-grid-items  ht-grid ht-grid-4">

		            <?php while ($qry->have_posts()) : $qry->the_post(); ?>
						<?php  $thumbnail_id = get_post_thumbnail_id( get_the_ID() ); ?>
						<div class="ht-grid-item">
							<div class="movie-grid-it">
								<div class="movie-thumbnail">
									<a href="<?php echo esc_url( get_the_permalink() ); ?>">
									<?php
									if ( ! empty( $thumbnail_id ) ) :

										echo wp_kses(
											wp_get_attachment_image( $thumbnail_id, 'blockter-poster-movie-item-fw' ),
											array(
												'img' => array(
													'width' => array(),
													'hight' => array(),
													'src'   => array(),
													'alt'   => array(),
													'class' => array(),
												),
											)
										);
									else :
										?>
										<img src="<?php echo esc_url( get_template_directory_uri() . '/images/poster.png' ); ?>" alt="<?php echo esc_attr( 'Poster Placeholder' ); ?>">
										<?php
									endif;
									?>
										<span class="readmore-btn">
											<?php echo esc_html__( 'Read More', 'blockter' ); ?>
											<i class="ion-android-arrow-dropright"></i>
										</span>
									</a>
								</div>
								<div class="movie-content">
									<h6 class="mv-title"><a itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
									<?php $feedback = fw()->extensions->get( 'feedback' ); ?>
									<?php if($feedback != null): ?>
									<?php if(comments_open() && get_comments_number()): ?>
										<div class="rate-average">
											<div class="left-it">
												<span class="fa fa-star icon"></span>
												<div class="inner-cmt-infor">
													<?php   $average = fw_ext_feedback_stars_get_post_rating();?>
													<div class="rate-num">
														<span><?php echo esc_html(number_format($average['average']),0); ?></span>
														<span class="sm-text"><?php echo esc_html__("/", 'blockter'); ?></span>
														<span class="sm-text"><?php
														$star = fw_ext_feedback_stars_get_post_detailed_rating($post->ID);
														echo count($star['stars']);
														?>
														</span>
													</div>
												</div>
											</div>
										</div>
									<?php endif;?>
									<?php endif; ?>
								</div>
							</div>
						</div>
		            <?php endwhile; ?>
	            </div>
	        </div>

            <?php /*Pagination*/
            blockter_ajax_pager($qry,$page, $media);
            $response = [
                'status'=> 200,
                'found' => $qry->found_posts
            ];

        else :
            $response = [
                'status'  => 201,
                'message' => 'No posts found'
            ];
        endif;
    $response['content'] = ob_get_clean();
    die(json_encode($response));
}
add_action('wp_ajax_do_filter_posts', 'blockter_filter_posts');
add_action('wp_ajax_nopriv_do_filter_posts', 'blockter_filter_posts');

/*Pagination*/
function blockter_ajax_pager( $query = null, $paged = 1, $media ) {
    if (!$query) return;

    $paginate = paginate_links([
        'base'      => '%_%',
        'type'      => 'array',
        'total'     => $query->max_num_pages,
        'format'    => '#page=%#%',
        'current'   => max( 1, $paged ),
        'prev_text' => esc_html__('Prev', 'blockter'),
        'next_text' => esc_html__('Next', 'blockter')
    ]);
    if ($query->max_num_pages > 1) : ?>
        <ul class="category-pagination flw">
            <?php foreach ( $paginate as $page ) :?>
                <li data-media="<?php echo esc_attr( $media ); ?>"><?php echo wp_kses_post($page); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif;
}

// Pagination number helper
function blockter_paginated_number($args = []){
	//Set defaults to use
    $defaults = [
        'query'                 => $GLOBALS['wp_query'],
        'previous_page_text'    =>  esc_html__( '&laquo;', 'blockter' ),
        'next_page_text'        =>  esc_html__( '&raquo;', 'blockter' ),
        'first_page_text'       =>  esc_html__( 'First', 'blockter'),
        'last_page_text'        =>  esc_html__( 'Last', 'blockter'),
        'next_link_text'        =>  esc_html__( 'Older Entries','blockter'),
        'previous_link_text'    =>  esc_html__( 'Newer Entries' ,'blockter'),
        'show_posts_links'      => false,
        'range'                 => 5,
    ];

    // Merge default arguments with user set arguments
    $args = wp_parse_args( $args, $defaults );

    /**
     * Get current page if query is paginated and more than one page exists
     * The first page is set to 1
     *
     * Static front pages is included
     *
     * @see WP_Query pagination parameter 'paged'
     * @link http://codex.wordpress.org/Class_Reference/WP_Query#Pagination_Parameters
     *
    */
    if ( get_query_var('paged') ) {

        $current_page = get_query_var('paged');

    }elseif ( get_query_var('page') ) {

        $current_page = get_query_var('page');

    }else{

        $current_page = 1;

    }

    // Get the amount of pages from the query
    $max_pages = ( is_object( $args['query'] ) ) ? (int) $args['query']->max_num_pages : (int) $args['query'];

    /**
     * If $args['show_posts_links'] is set to false, numbered paginated links are returned
     * If $args['show_posts_links'] is set to true, pagination links are returned
    */
    if( false === $args['show_posts_links'] ) {

        // Don't display links if only one page exists
        if( 1 === $max_pages ) {

            $paginated_text = '';

        }else{

            /**
             * For multi-paged queries, we need to set the variable ranges which will be used to check
             * the current page against and according to that set the correct output for the paginated numbers
            */
            $mid_range      = (int) floor( $args['range'] / 2 );
            $start_range    = range( 1 , $mid_range );
            $end_range      = range( ( $max_pages - $mid_range +1 ) , $max_pages );
            $exclude        = array_merge( $start_range, $end_range );

            /**
             * The amount of pages must now be checked against $args['range']. If the total amount of pages
             * is less than $args['range'], the numbered links must be returned as is
             *
             * If the total amount of pages is more than $args['range'], then we need to calculate the offset
             * to just return the amount of page numbers specified in $args['range']. This defaults to 5, so at any
             * given instance, there will be 5 page numbers displayed
            */
            $check_range    = ( $args['range'] > $max_pages )   ? true : false;

            if( true === $check_range ) {

                $range_numbers = range( 1, $max_pages );

            }elseif( false === $check_range ) {

                if( !in_array( $current_page, $exclude ) ) {

                    $range_numbers = range( ( $current_page - $mid_range ), ( $current_page + $mid_range ) );

                }elseif( in_array( $current_page, $start_range ) && ( $current_page - $mid_range ) <= 0 ) {

                    $range_numbers = range( 1, $args['range'] );

                }elseif(  in_array( $current_page, $end_range ) && ( $current_page + $mid_range ) >= $max_pages ) {

                    $range_numbers = range( ( $max_pages - $args['range'] +1 ), $max_pages );

                }

            }

            /**
             * The page numbers are set into an array through this foreach loop. The current page, or active page
             * gets the class 'current' assigned to it. All the other pages get the class 'inactive' assigned to it
            */
            foreach ( $range_numbers as $v ) {

                if ( $v == $current_page ) {

                    $page_numbers[] = '<span class="current">' . $v . '</span>';

                }else{

                    $page_numbers[] = '<a href="' . get_pagenum_link( $v ) . '" class="inactive">' . $v . '</a>';

                }

            }

            /**
            * All the texts are set here and when they should be displayed which will link back to:
             * - $previous_page The previous page from the current active page
             * - $next_page The next page from the current active page
             * - $first_page Links back to page number 1
             * - $last_page Links to the last page
            */
            $previous_page  = ( $current_page !== 1 )                       ? '<a href="' . get_pagenum_link( $current_page - 1 ) . '">' . $args['previous_page_text'] . '</a>' : '';
            $next_page      = ( $current_page !== $max_pages )              ? '<a href="' . get_pagenum_link( $current_page + 1 ) . '">' . $args['next_page_text'] . '</a>'     : '';
            $first_page     = ( !in_array( 1, $range_numbers ) )            ? '<a href="' . get_pagenum_link( 1 ) . '">' . $args['first_page_text'] . '</a>'                    : '';
            $last_page      = ( !in_array( $max_pages, $range_numbers ) )   ? '<a href="' . get_pagenum_link( $max_pages ) . '">' . $args['last_page_text'] . '</a>'            : '';

            /**
             * Text to display before the page numbers
             * This is set to the following structure:
             * - Page X of Y
            */
            $page_text      = '<span class="page-text">' . sprintf( esc_html__( 'Page %s of %s:', 'blockter'), $current_page, $max_pages ) . '</span>';
            // Turn the array of page numbers into a string
            $numbers_string = implode( ' ', $page_numbers );

            // The final output of the function
			$paginated_text = '<div class="celebrity-pagination">';
			$paginated_text .= '<div class="pag-text">';
			$paginated_text .= $page_text;
			$paginated_text .= '</div>';
			$paginated_text .= '<div class="page-num">';
			$paginated_text .= $first_page . $previous_page . $numbers_string . $next_page . $last_page;
			$paginated_text .= '</div>';
			$paginated_text .= '</div>';


        }

    }elseif( true === $args['show_posts_links'] ) {

        /**
        * If $args['show_posts_links'] is set to true, only links to the previous and next pages are displayed
        * The $max_pages parameter is already set by the function to accommodate custom queries
        */
        $paginated_text = next_posts_link( '<div class="next-posts-link">' . $args['next_link_text'] . '</div>', $max_pages );
        $paginated_text .= previous_posts_link( '<div class="previous-posts-link">' . $args['previous_link_text'] . '</div>' );

    }

    // Finally return the output text from the function
    return $paginated_text;
}
/*get cast item by id*/
function cast_query_by_termid(){
	if( get_query_var( 'paged' )){
		$paged = get_query_var( 'paged' );
	}elseif( get_query_var( 'page' )){
		$paged = get_query_var( 'page' );
	}else{
		$paged = 1;
	}
	$tpp = 6; // term per page
	$offset = (($paged - 1) * $tpp);
	$args = [
		'number' => $tpp,
		'orderby'=>'term_id',
		'offset' => $offset
	];
	return $args;
}
/*get cast item by name*/
function cast_query_by_termname(){
	if( get_query_var( 'paged' )){
		$paged = get_query_var( 'paged' );
	}elseif( get_query_var( 'page' )){
		$paged = get_query_var( 'page' );
	}else{
		$paged = 1;
	}
	$tpp = 6; // term per page
	$offset = (($paged - 1) * $tpp);
	$args = [
		'number' => $tpp,
		'orderby'=>'name',
		'offset' => $offset
	];
	return $args;
}
// Get list cast term
function blockter_get_cast_list($args){
	$actor_tax = 'mv_actor';
	$term_count = get_terms('mv_actor', ['fields' => 'count']);
	if(!$term_count){
		return false;
	}
	$tpp = 6; // term per page
	$max_num_pages = ceil($term_count / $tpp);
	// Caculate term offset

	$output = '';
	$wpbtags = get_terms( $actor_tax, $args );

	$output.= '<div class="taxography-grid theme-celebrity-items movie-items">';
	foreach($wpbtags as $tag) {
		$term_id = $tag->term_id;
		$cast_name = $tag->name;
		$cast_des = $tag->description;
		$cast_avatar = fw_get_db_term_option($term_id, 'mv_actor');
		$cast_terms = fw_get_db_term_option($term_id, 'mv_actor');

		if(isset($cast_terms['country']) && $cast_terms['country'] !== ''){
			$country = $cast_terms['country'];
		}
		if(array_key_exists('knowfor',$cast_terms) && isset($cast_terms['knowfor']) && $cast_terms['knowfor'] !== ''){
			$knowfor = $cast_terms['knowfor'];
		}
		$output.= ' <div class="celebrity-grid-item celeb-list-it item list-group-item">';
			if ( array_key_exists( 'avatar_url', $cast_avatar ) && $cast_avatar['avatar_url'] != '' ) {
				$output.= '<a class="celebrity-img" href="'. get_term_link($tag, $actor_tax ) .'">';
				$output.=  '<img src="' . esc_attr($cast_avatar['avatar_url']) . '" alt="' . esc_attr( 'Actor Avatar', 'blockter' ) . '">';
				$output.='</a>';
			} elseif ( array_key_exists('avatar',$cast_avatar) && $cast_avatar['avatar'] != '' ) {
				$att_id = $cast_avatar['avatar']['attachment_id'];
				$output.= '<a  class="celebrity-img" href="'. get_term_link($tag, $actor_tax ) .'">';
				$output.=  wp_get_attachment_image($att_id, 'blockter-cast-thumbnail-list');
				$output.='</a>';
			} else {
				$output.= '<a  class="celebrity-img" href="'. get_term_link($tag, $actor_tax ) .'">';
				$output.= '<div class="no-image"></div>';
				$output.= '</a>';
			}
			$output.=' <div class="celebrity-infor">';
			$output.=' <h4 class="celebrity-name">';
			$output.=' <a class="actor-name" href="'. get_term_link($tag, $actor_tax ) .'">'. $tag->name.'</a>';
			$output.='</h4>';
			$output.='<span class="ceb-detail">';
			if(array_key_exists('knowfor',$cast_terms) && isset($cast_terms['knowfor']) && $cast_terms['knowfor'] !== ''){
				$output.='<span class="ceb-knowfor">'.$knowfor.'</span>';
			}
			if(isset($cast_terms['country']) && $cast_terms['country'] !== ''){
				$output.='<span class="ceb-country">'.$country.'</span>';
			}
			$output.='</span>';
			if($cast_des != ''){
				$output.='<span class="ceb-des">'.$cast_des.'</span>';
			}
			$output.='</div>';
		$output.= '</div>';

	}
	$output.= '</div>';
	$output .= blockter_paginated_number(['query' => $max_num_pages]);

    return $output;
}
//Get grid cast item
function blockter_get_cast_grid($args){
	$actor_tax = 'mv_actor';
	$term_count = get_terms('mv_actor', ['fields' => 'count']);
	if(!$term_count){
		return false;
	}
	$tpp = 6; // term per page
	$max_num_pages = ceil($term_count / $tpp);
	// Caculate term offset

	$output = '';
	$wpbtags = get_terms( $actor_tax, $args );

	$output.= '<div class="taxography-grid theme-celebrity-items movie-items">';
	foreach($wpbtags as $tag) {
		$term_id = $tag->term_id;
		$cast_name = $tag->name;
		$cast_des = $tag->description;
		$cast_avatar = fw_get_db_term_option($term_id, 'mv_actor');
		$cast_terms = fw_get_db_term_option($term_id, 'mv_actor');

		if(isset($cast_terms['country']) && $cast_terms['country'] !== '') {
			$country = $cast_terms['country'];
		}
		if(array_key_exists('knowfor',$cast_terms) && isset($cast_terms['knowfor']) && $cast_terms['knowfor'] !== ''){
			$knowfor = $cast_terms['knowfor'];
		}
		$output.= ' <div class="celebrity-grid-item celeb-list-it item grid-group-item">';
			if ( array_key_exists( 'avatar_url', $cast_avatar ) && $cast_avatar['avatar_url'] != '' ) {
				$output.= '<a class="celebrity-img" href="'. get_term_link($tag, $actor_tax ) .'">';
				$output.=  '<img src="' . esc_attr($cast_avatar['avatar_url']) . '" alt="' . esc_attr( 'Actor Avatar', 'blockter' ) . '">';
				$output.='</a>';
			} elseif ( array_key_exists( 'avatar', $cast_avatar ) && $cast_avatar['avatar'] != '' ){
				$att_id = $cast_avatar['avatar']['attachment_id'];
				$output.= '<a  class="celebrity-img" href="'. get_term_link($tag, $actor_tax ) .'">';
				$output.=  wp_get_attachment_image($att_id, 'blockter-cast-thumbnail-list');
				$output.='</a>';
			} else {
				$output.= '<a  class="celebrity-img" href="'. get_term_link($tag, $actor_tax ) .'">';
				$output.= '<div class="no-image"></div>';
				$output.= '</a>';
			}
			$output.=' <div class="celebrity-infor">';
			$output.=' <h4 class="celebrity-name">';
			$output.=' <a class="actor-name" href="'. get_term_link($tag, $actor_tax ) .'">'. $tag->name.'</a>';
			$output.='</h4>';
			$output.='<span class="ceb-detail">';
			if(array_key_exists('knowfor',$cast_terms) && isset($cast_terms['knowfor']) && $cast_terms['knowfor'] !== ''){
				$output.='<span class="ceb-knowfor">'.$knowfor.'</span>';
			}
			if(isset($cast_terms['country']) && $cast_terms['country'] !== ''){
				$output.='<span class="ceb-country">'.$country.'</span>';
			}
			$output.='</span>';
			if($cast_des != ''){
				$output.='<span class="ceb-des">'.$cast_des.'</span>';
			}
			$output.='</div>';
		$output.= '</div>';

	}
	$output.= '</div>';
	$output .= blockter_paginated_number(['query' => $max_num_pages]);

    return $output;
}
function add_login_logout_register_menu( $items, $args ) {
	if ( $args->theme_location != 'primary-menu-right' ) {
		return $items;
	}
	if ( is_user_logged_in() ) {
		$pages = get_pages(array(
		    'meta_key' => '_wp_page_template',
		    'meta_value' => 'page-templates/user-profile.php'
		));
		if(isset($pages[0])) {
			$user_url = get_permalink($pages[0]->ID);
		} else {
			$user_url = '#';
		}
		$items .= '<li class="avatar-image"><a href="' . $user_url . '">'.get_avatar(get_current_user_id(), 46).'</a></li>';
		$items .= '<li class="logout-btn"><a href="' . wp_logout_url(home_url()) . '">' . esc_html__( 'Log Out', 'blockter') . '</a></li>';
	} else {
		$items .= '<li class="login-btn"><a href="' . wp_login_url() . '">' . esc_html__( 'Log In', 'blockter') . '</a></li>';
	}
	return $items;
}
add_filter( 'wp_nav_menu_items', 'add_login_logout_register_menu', 199, 2 );

// register function
function register_link_url( $url ) {
	if ( ! is_user_logged_in() ) {
		if ( get_option('users_can_register') )
			$url = '<li><a href="' . home_url() . "/register" . '">' . esc_html__('Register', 'blockter') . '</a></li>';
		else
			$url = '';
  	} else {
	  	$url = '<li><a href="' . admin_url() . '">' . esc_html__('Site Admin', 'blockter') . '</a></li>';
  	}
 	return $url;
}
add_filter( 'register', 'register_link_url', 10, 2 );

/**
 * Determine the appropriate default search type for the current page context.
 * Returns the GET param value if already set; otherwise infers from the page type.
 */
if ( ! function_exists( 'blockter_detect_search_type' ) ) {
	function blockter_detect_search_type() {
		if ( ! empty( $_GET['topsortby'] ) ) {
			return sanitize_key( $_GET['topsortby'] );
		}

		// Collections: listing page or individual term
		if ( is_page_template( 'page-templates/page-collections.php' ) || is_tax( 'mv_collection' ) ) {
			return 'mv_collection';
		}
		if ( is_page_template( 'page-templates/page-trends.php' ) || is_tax( 'mv_keyword' ) ) {
			return 'mv_keyword';
		}
		// Networks: listing page or individual term
		if ( is_page_template( 'page-templates/page-networks.php' ) || is_tax( 'networks' ) ) {
			return 'networks';
		}
		// Shows / series (including the dedicated Series listing page)
		if ( is_singular( 'ht_show' ) || is_post_type_archive( 'ht_show' ) || is_page( 20511 ) ) {
			return 'ht_show';
		}
		// Movies (also covers genre / collection taxonomy archive fallback)
		if ( is_singular( 'ht_movie' ) || is_post_type_archive( 'ht_movie' ) || is_tax( 'mv_genre' ) ) {
			return 'ht_movie';
		}
		// News / blog
		if ( is_singular( 'post' ) || is_home() || is_category() || is_tag() || is_page_template( 'page-templates/page-genres.php' ) ) {
			return 'news';
		}

		return 'ht_movie';
	}
}

if ( ! function_exists( 'blockter_search_sort_option_labels' ) ) {
	/**
	 * Labels for header search sort dropdown (shared by both search form variants).
	 *
	 * @return array<string,string> Sort value => label.
	 */
	function blockter_search_sort_option_labels() {
		$search_options = fw_get_db_ext_settings_option( 'ht-movie', 'search-options' );
		if ( $search_options && is_array( $search_options ) ) {
			$orderby_options = array();

			if ( array_key_exists( 'movie', $search_options ) ) {
				$orderby_options['ht_movie'] = __( 'Movie', 'blockter' );
			}

			if ( array_key_exists( 'tv-show', $search_options ) ) {
				$orderby_options['ht_show'] = __( 'TV Show', 'blockter' );
			}

			if ( array_key_exists( 'cast', $search_options ) ) {
				$orderby_options['cast'] = __( 'Cast', 'blockter' );
			}

			if ( array_key_exists( 'news', $search_options ) ) {
				$orderby_options['news'] = __( 'News', 'blockter' );
			}

			$orderby_options['mv_collection'] = __( 'Collection', 'blockter' );
			$orderby_options['networks']      = __( 'Network', 'blockter' );
			$orderby_options['mv_keyword']      = __( 'Theme', 'blockter' );
		} else {
			$orderby_options = array(
				'ht_movie'      => __( 'Movie', 'blockter' ),
				'ht_show'       => __( 'TV Show', 'blockter' ),
				'cast'          => __( 'Cast', 'blockter' ),
				'news'          => __( 'News', 'blockter' ),
				'mv_collection' => __( 'Collection', 'blockter' ),
				'networks'      => __( 'Network', 'blockter' ),
				'mv_keyword'    => __( 'Theme', 'blockter' ),
			);
		}
		return $orderby_options;
	}
}

if ( ! function_exists( 'blockter_search_input_placeholder' ) ) {
	/**
	 * Placeholder text: "Search for {label}" for the currently detected sort type.
	 *
	 * @return string Escaped for HTML attribute.
	 */
	function blockter_search_input_placeholder() {
		$labels = blockter_search_sort_option_labels();
		$sortby = blockter_detect_search_type();
		if ( ! isset( $labels[ $sortby ] ) && ! empty( $labels ) ) {
			$sortby = (string) array_keys( $labels )[0];
		}
		$label = isset( $labels[ $sortby ] ) ? $labels[ $sortby ] : __( 'Movie', 'blockter' );
		return esc_attr(
			sprintf(
				/* translators: %s: current search type label (Movie, Theme, etc.). */
				__( 'Search for %s', 'blockter' ),
				$label
			)
		);
	}
}

if(!function_exists('blockter_top_search_form')){
	function blockter_top_search_form(){
		$orderby_options = blockter_search_sort_option_labels();
		$sortby          = blockter_detect_search_type();
		$placeholder     = blockter_search_input_placeholder();
		?>
		<form action="<?php echo esc_url(home_url('/')); ?>" class="header-search-form">
			<label for="topsortby" class="screen-reader-text">
    				Sort Movies
			</label>
			<select id="topsortby" name="topsortby" class="search-movies">
				<?php
					foreach ( $orderby_options as $value => $label ) {
						echo '<option ' . selected( $sortby, $value, false ) . ' value="' . esc_attr( $value ) . '">' . esc_html( $label ) . '</option>';
					}
				?>
			</select>
	        <input required class="header-search-form-input form-control" name="s" value="<?php get_search_query();?>" type="text" placeholder="<?php echo $placeholder; ?>" >
			<input type="hidden" class="post-type" name="search-type" value="ht_movie">
		</form>
	    <?php
	}
}
// top ajax search search movies
if(!function_exists('blockter_top_ajaxsearch_form')){
	/**
	 * @param string $form_id         HTML id for the form (default searchmovie). Use a unique id for additional instances on the same page.
	 * @param string $extra_form_class Optional extra CSS class(es) on the form element.
	 */
	function blockter_top_ajaxsearch_form( $form_id = 'searchmovie', $extra_form_class = '' ){
		$orderby_options = blockter_search_sort_option_labels();
		$sortby          = blockter_detect_search_type();
		$placeholder     = blockter_search_input_placeholder();
		$form_class = 'header-search-form';
		if ( '' !== $extra_form_class ) {
			$form_class .= ' ' . sanitize_html_class( $extra_form_class );
		}
		?>
		<form action="<?php echo esc_url(home_url('/')); ?>" class="<?php echo esc_attr( $form_class ); ?>" id="<?php echo esc_attr( $form_id ); ?>">
			<label for="topsortby" class="screen-reader-text">
    				Sort Movies
			</label>
			<select id="topsortby" name="topsortby" class="search-movies">
				<?php
					foreach ( $orderby_options as $value => $label ) {
						echo '<option ' . selected( $sortby, $value, false ) . ' value="' . esc_attr( $value ) . '">' . esc_html( $label ) . '</option>';
					}
				?>
			</select>
	        <input required class="header-search-form-input form-control search-autocomplete" name="s" value="<?php get_search_query();?>" type="text" placeholder="<?php echo $placeholder; ?>" >
	        <input type="hidden" class="post-type" name="search-type" value="ht_movie">
	    </form>

	    <?php
	}
}
/**
 * Enqueue scripts and styles.
 *
 * @since 1.0.0
 */
function ja_global_enqueues() {
	wp_enqueue_script(
		'jquery-auto-complete',
		get_template_directory_uri() . '/js/auto-complete.min.js',
		array( 'jquery' ),
		'1.0.7',
		true
	);
	wp_enqueue_script(
		'global',
		get_template_directory_uri() . '/js/global.js',
		array( 'jquery' ),
		'1.1.5',
		true
	);
	wp_localize_script(
		'global',
		'global',
		array(
			'ajax'                    => admin_url( 'admin-ajax.php' ),
			'search_placeholder_prefix' => __( 'Search for ', 'blockter' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'ja_global_enqueues' );
/**
 * Live autocomplete search feature.
 *
 * @since 1.0.0
 */
function ja_ajax_search() {
	$search_term = '';
	if ( isset( $_POST['searchmovie'] ) ) {
		$search_term = wp_unslash( $_POST['searchmovie'] );
	} elseif ( isset( $_POST['search'] ) ) {
		$search_term = wp_unslash( $_POST['search'] );
	}

	$search_type = isset( $_POST['type'] ) ? sanitize_key( wp_unslash( $_POST['type'] ) ) : '';
	if ( $search_type == 'ht_movie' ) {
		$results = new WP_Query( array(
			'post_type'     => 'ht_movie',
			'post_status'   => 'publish',
			'posts_per_page'=> -1,
			's'             => $search_term,
		) );
	} elseif ( $search_type == 'ht_show' ) {
		$results = new WP_Query( array(
			'post_type'     => 'ht_show',
			'post_status'   => 'publish',
			'posts_per_page'=> -1,
			's'             => $search_term,
		) );
	} elseif ( $search_type == 'cast' ) {
		$results = get_terms( array(
			'taxonomy'      => array( 'mv_actor' ),
			'posts_per_page'=> -1,
			'orderby'       => 'id',
		  'order'         => 'ASC',
		  'name__like'    => $search_term,
			's'             => $search_term,
		) );
	} elseif ( $search_type == 'mv_collection' ) {
		$results = get_terms( array(
			'taxonomy'   => array( 'mv_collection' ),
			'orderby'    => 'name',
			'order'      => 'ASC',
			'name__like' => $search_term,
			'hide_empty' => false,
		) );
	} elseif ( $search_type == 'networks' ) {
		$results = get_terms( array(
			'taxonomy'   => array( 'networks' ),
			'orderby'    => 'name',
			'order'      => 'ASC',
			'name__like' => $search_term,
			'hide_empty' => false,
		) );
	} elseif ( $search_type == 'mv_keyword' ) {
		$results = get_terms( array(
			'taxonomy'   => array( 'mv_keyword' ),
			'orderby'    => 'name',
			'order'      => 'ASC',
			'name__like' => $search_term,
			'hide_empty' => false,
		) );
	} else {
		$results = new WP_Query( array(
			'post_type'     => 'post',
			'post_status'   => 'publish',
			'posts_per_page'=> -1,
			's'             => $search_term,
		) );
	}

	$items = array();
	if ( in_array( $search_type, array( 'cast', 'mv_collection', 'networks', 'mv_keyword' ), true ) ) {
		if ( ! empty( $results ) && ! is_wp_error( $results ) ) {
			foreach ( $results as $result ) {
				$url = get_term_link( $result );
				$items[] = array(
					'label' => $result->name,
					'url'   => ! is_wp_error( $url ) ? $url : '',
				);
			}
		}
	} else {
		if ( ! empty( $results->posts ) ) {
			foreach ( $results->posts as $result ) {
				$items[] = array(
					'label' => $result->post_title,
					'url'   => get_permalink( $result->ID ),
				);
			}
		}
	}
	wp_send_json_success( $items );
}
add_action( 'wp_ajax_search_site',        'ja_ajax_search' );
add_action( 'wp_ajax_nopriv_search_site', 'ja_ajax_search' );

/**
 * Setup movie settings
 */
function blockter_setup_movie_settings( $post_type, $posts_per_page ) {
	$movie_settings = array(
		'paged'           => is_front_page() ? max( 1, get_query_var( 'page' ) ) : max( 1, get_query_var( 'paged' ) ),
		'sortby'          => array_key_exists( 'sortby', $_GET ) ? $_GET['sortby'] : 'date',
		'posts_per_page'  => $posts_per_page,
		'orderby_options' => array(
			'date'   => 'Default',
			'title'  => 'Title',
			'rating' => 'Rating',
		),
	);

	$movie_query_args = array(
		'post_type'      => $post_type,
		'posts_per_page' => $movie_settings['posts_per_page'],
		'paged'          => $movie_settings['paged'],
	);

	if ( 'rating' === $movie_settings['sortby'] ) {
		$movie_query_args['meta_key'] = 'blockter_rating';
		$movie_query_args['orderby']  = 'meta_value_num';
		$movie_query_args['order']    = 'DESC';
	} else {
		$movie_query_args['orderby'] = $movie_settings['sortby'];
		$movie_query_args['order'] = 'date' === $movie_settings['sortby'] ? 'DESC' : 'ASC';
	}

	if ( ! empty( get_search_query() ) ) {
		$movie_query_args['s'] = get_search_query();
	}

	$movie_settings['movie']        = new WP_Query( $movie_query_args );
	$movie_settings['total_movies'] = $movie_settings['movie']->found_posts;

	return $movie_settings;
}

/**
 * Render movie filter
 */
function blockter_render_movie_filter( $total_movies, $orderby_options, $sortby, $layout ) {
	// If current page is search page, add addition attribute to filter.
	if ( is_search() ) {
		$top_sort_by = get_query_var( 'topsortby', 'ht_movie'  );
		$search_term = get_search_query();
		$search_type = get_query_var( 'search-type', 'ht_movie' );
	}

	if ( ! get_option( 'permalink_structure' ) && ! is_search() ) {
		$page_id = get_query_var( 'page_id', get_queried_object_id() );
	}
	?>
	<div class="celebrity-topbar-filter">

		<div class="celebrity-result-count">
			<span><?php printf( esc_html( _n( 'Found %d movie in total', 'Found %d movies in total', $total_movies, 'blockter' ) ), $total_movies ); ?></span>
		</div><!-- celebrity-result-count -->

		<div class="filter-right">
			<form class="celebrity-sorting">
				<span><?php echo esc_html__( 'Sort By:', 'blockter' ); ?></span>
				<select name="sortby" class="consult-dropdown-list">
					<?php
					foreach ( $orderby_options as $value => $label ) {
						echo '<option ' . selected( $sortby, $value ) . ' value=' . esc_attr( $value ) . '>' . esc_attr( $label ) . '</option>';
					}
					?>
				</select>
				<?php if ( is_search() ) : ?>
					<input type="hidden" name="topsortby" value="<?php echo esc_attr( $top_sort_by ); ?>">
					<input type="hidden" name="s" value="<?php echo esc_attr( $search_term ); ?>">
					<input type="hidden" name="search-type" value="<?php echo esc_attr( $search_type ); ?>">
				<?php endif; ?>
				<?php if ( ! get_option( 'permalink_structure' ) && ! is_search() ) : ?>
					<input type="hidden" name="page_id" value="<?php echo esc_attr( $page_id ); ?>">
				<?php endif; ?>
				<input type="hidden" name="paged" value="1">
			</form>

			<div class="celebrity-view btn-group">
				<?php if ( 'list' === $layout ) : ?>
					<a href="#" class="ion-ios-list-outline list current"></a>
					<a href="#" class="ion-grid grid"></a>
				<?php else : ?>
					<a href="#" class="ion-ios-list-outline list"></a>
					<a href="#" class="ion-grid grid current"></a>
				<?php endif; ?>
			</div>
		</div><!-- .filter-right -->

	</div><!-- .celebrity-topbar-filter -->
	<?php
}

/**
 * Render movie filter
 */
function blockter_render_show_filter( $total_show, $orderby_options, $sortby, $layout ) {
	// If current page is search page, add addition attribute to filter.
	if ( is_search() ) {
		$top_sort_by = get_query_var( 'topsortby', 'ht_show'  );
		$search_term = get_search_query();
		$search_type = get_query_var( 'search-type', 'ht_show' );
	}

	if ( ! get_option( 'permalink_structure' ) && ! is_search() ) {
		$page_id = get_query_var( 'page_id', get_queried_object_id() );
	}
	?>
	<div class="celebrity-topbar-filter">

		<div class="celebrity-result-count">
			<span><?php printf( esc_html( _n( 'Found %d show in total', 'Found %d shows in total', $total_show, 'blockter' ) ), $total_show ); ?></span>
		</div><!-- celebrity-result-count -->

		<div class="filter-right">
			<form class="celebrity-sorting">
				<span><?php echo esc_html__( 'Sort By:', 'blockter' ); ?></span>
				<select name="sortby" class="consult-dropdown-list">
					<?php
					foreach ( $orderby_options as $value => $label ) {
						echo '<option ' . selected( $sortby, $value ) . ' value=' . esc_attr( $value ) . '>' . esc_attr( $label ) . '</option>';
					}
					?>
				</select>
				<?php if ( is_search() ) : ?>
					<input type="hidden" name="topsortby" value="<?php echo esc_attr( $top_sort_by ); ?>">
					<input type="hidden" name="s" value="<?php echo esc_attr( $search_term ); ?>">
					<input type="hidden" name="search-type" value="<?php echo esc_attr( $search_type ); ?>">
				<?php endif; ?>
				<?php if ( ! get_option( 'permalink_structure' ) && ! is_search() ) : ?>
					<input type="hidden" name="page_id" value="<?php echo esc_attr( $page_id ); ?>">
				<?php endif; ?>
				<input type="hidden" name="paged" value="1">
			</form>

			<div class="celebrity-view btn-group">
				<?php if ( 'list' === $layout ) : ?>
					<a href="#" class="ion-ios-list-outline list current"></a>
					<a href="#" class="ion-grid grid"></a>
				<?php else : ?>
					<a href="#" class="ion-ios-list-outline list"></a>
					<a href="#" class="ion-grid grid current"></a>
				<?php endif; ?>
			</div>
		</div><!-- .filter-right -->

	</div><!-- .celebrity-topbar-filter -->
	<?php
}


/**
 * Render movie list content
 */
function blockter_render_movie_content( $layout ) {
		$id = get_the_ID();

		$permalink    = get_permalink( $id );
		$title        = get_the_title( $id );
		$thumbnail_id = get_post_thumbnail_id( $id );
		$tagline      = fw_get_db_post_option( $id, 'tagline' );
		$overview     = fw_get_db_post_option( $id, 'overview' );
		$runtime      = fw_get_db_post_option( $id, 'runtime' );
		$release_date = fw_get_db_post_option( $id, 'release_date' );
		$directors    = fw_get_db_post_option( $id, 'directors' );
		$actor_list  = get_the_terms( $id, 'mv_actor' );
		$feedback     = fw()->extensions->get( 'feedback' );

		if ( 'list' === $layout ) {
			$movie_classes = 'col-md-12 col-sm-12 col-xs-12 item list-group-item';
		} else if ( 'grid' === $layout ) {
			$movie_classes = 'col-md-12 col-sm-12 col-xs-12 item grid-group-item';
		}

	?>
	<div class="<?php echo esc_attr( $movie_classes ); ?>">
		<div class="movie-item">
			<div class="movie-thumbnail">
				<?php if ( ! empty( $thumbnail_id ) ) : ?>
					<a href="<?php echo esc_url( $permalink ); ?>">
						<?php echo wp_get_attachment_image( $thumbnail_id, 'blockter-poster-movie-item' ); ?>
						<span class="readmore-btn"><?php echo esc_html__( 'Read more', 'blockter' ); ?><i class="ion-android-arrow-dropright"></i></span>
					</a>
				<?php endif; ?>
			</div><!-- .movie-thumbnail -->

			<div class="movie-content">
				<h6 class="mv-title">
					<a itemprop="url" href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
				</h6>
				<?php if ( null !== $feedback ) :
					if ( comments_open( $id ) && get_comments_number( $id ) ) :
						$average = fw_ext_feedback_stars_get_post_rating( $id );
						$star    = fw_ext_feedback_stars_get_post_detailed_rating( $id );
						?>
						<div class="rate-average">
							<div class="left-it">
								<span class="fa fa-star icon"></span>
								<div class="inner-cmt-infor">
									<div class="rate-num">
										<span><?php echo esc_html( number_format( $average['average'] ), 0 ); ?></span>
										<span class="sm-text"><?php echo esc_html__( '/', 'blockter' ); ?></span>
										<span class="sm-text"><?php echo count( $star['stars'] ); ?></span>
									</div>
								</div>
							</div>
						</div>
					<?php endif; ?>
				<?php endif; ?>
				<div class="mv-list-content">
					<?php if ( ! empty( $overview ) ) : ?>
						<div class="mv-des">
							<?php echo wp_kses_post( $overview ); ?>
						</div>
					<?php endif; ?>
					<div class="flex-it movie-details">
						<?php if ( ! empty( $runtime ) ) : ?>
							<span><?php echo esc_html__( 'Run time: ', 'blockter' ); ?><?php echo esc_html( $runtime ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $tagline ) ) : ?>
							<span><?php echo esc_html__( 'Tagline: ', 'blockter' ); ?><?php echo esc_html( $tagline ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $release_date ) ) : ?>
							<span><?php echo esc_html__( 'Release: ', 'blockter' ); ?><?php echo esc_html( $release_date ); ?></span>
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $directors ) ) : ?>
						<p class="mv-directors"><?php echo esc_html__( 'Director: ', 'blockter' ); ?><span class="link-color"><?php echo esc_html( $directors ); ?></span></p>
					<?php endif; ?>
					<?php if ( ! empty( $actor_list ) ) : ?>
						<p class="mv-stars">
							<span><?php echo esc_html__( 'Stars: ', 'blockter' ); ?></span>
							<?php foreach ( $actor_list as $item ) : ?>
								<?php
								$ac_name = $item->name;
								$ac_url  = get_term_link( $item );
								?>
								<a href="<?php echo esc_url( $ac_url ); ?>"><?php echo esc_html( $ac_name ); ?></a>
							<?php endforeach; ?>
						</p>
					<?php endif; ?>
				</div>
			</div><!-- .movie-content -->
		</div><!-- .movie-item -->
	</div><!-- .col -->
	<?php
}

/**
 * Render show content
 */
function blockter_render_show_content( $layout ) {
	$id = get_the_ID();

	$permalink       = get_permalink( $id );
	$title           = get_the_title( $id );
	$thumbnail_id    = get_post_thumbnail_id( $id );
	$tagline         = fw_get_db_post_option( $id, 'tagline' );
	$overview        = fw_get_db_post_option( $id, 'overview' );
	$episode_runtime = fw_get_db_post_option( $id, 'episode_runtime' );
	$first_air_date  = fw_get_db_post_option( $id, 'first_air_date' );
	$creators        = fw_get_db_post_option( $id, 'creators' );
	$actor_list     = get_the_terms( $id, 'mv_actor' );
	$feedback        = fw()->extensions->get( 'feedback' );

	if ( 'list' === $layout ) {
		$show_classes = 'col-md-12 col-sm-12 col-xs-12 item list-group-item';
	} else if ( 'grid' === $layout ) {
		$show_classes = 'col-md-12 col-sm-12 col-xs-12 item grid-group-item';
	}

	?>
	<div class="<?php echo esc_attr( $show_classes ); ?>">
		<div class="movie-item">

			<div class="movie-thumbnail">
				<?php if ( ! empty( $thumbnail_id ) ) : ?>
					<a href="<?php the_permalink(); ?>">
						<?php echo wp_get_attachment_image( $thumbnail_id, 'blockter-poster-movie-item' ); ?>
						<span class="readmore-btn"><?php echo esc_html__( 'Read more', 'blockter' ); ?><i class="ion-android-arrow-dropright"></i></span>
					</a>
				<?php endif; ?>
			</div><!-- .movie-thumbnail -->

			<div class="movie-content">
				<h6 class="mv-title"><a itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
				<?php
				if ( null !== $feedback ) :
					if ( comments_open( $id ) && get_comments_number( $id ) ) :
						$average = fw_ext_feedback_stars_get_post_rating( $id );
						$star    = fw_ext_feedback_stars_get_post_detailed_rating( $id );
						?>
						<div class="rate-average">
							<div class="left-it">
								<span class="fa fa-star icon"></span>
								<div class="inner-cmt-infor">
									<div class="rate-num">
										<span><?php echo esc_html( number_format( $average['average'] ), 0 ); ?></span>
										<span class="sm-text"><?php echo esc_html__( '/', 'blockter' ); ?></span>
										<span class="sm-text"><?php echo count( $star['stars'] ); ?></span>
									</div>
								</div>
							</div>
						</div><!-- .rate-average -->
					<?php endif; ?>
				<?php endif; ?>
				<div class="mv-list-content">
					<?php if ( ! empty( $overview ) ) : ?>
						<div class="mv-des">
							<?php echo wp_kses_post( $overview ); ?>
						</div>
					<?php endif; ?>
					<div class="flex-it movie-details">
						<?php if ( ! empty( $episode_runtime ) ) : ?>
							<span><?php echo esc_html__( 'Episode Runtime: ', 'blockter' ); ?><?php echo esc_html( $episode_runtime ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $tagline ) ) : ?>
							<span><?php echo esc_html__( 'Tagline: ', 'blockter' ); ?><?php echo esc_html( $tagline ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $first_air_date ) ) : ?>
							<span><?php echo esc_html__( 'First Air Date: ', 'blockter' ); ?><?php echo esc_html( $first_air_date ); ?></span>
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $creators ) ) : ?>
						<p class="mv-directors"><?php echo esc_html__( 'Creators: ', 'blockter' ); ?><span class="link-color"><?php echo esc_html( $creators ); ?></span></p>
					<?php endif; ?>
					<?php if ( ! empty( $actor_list ) ) : ?>
						<p class="mv-stars">
							<span><?php echo esc_html__( 'Stars: ', 'blockter' ); ?></span>
							<?php foreach ( $actor_list as $item ) : ?>
								<?php
								$ac_name = $item->name;
								$ac_url  = get_term_link( $item );
								?>
								<a href="<?php echo esc_url( $ac_url ); ?>"><?php echo esc_html( $ac_name ); ?></a>
							<?php endforeach; ?>
						</p>
					<?php endif; ?>
				</div><!-- .mv-list-content -->
			</div><!-- .movie-content -->

		</div><!-- .movie-item -->
	</div><!-- .col -->
	<?php
}

/**
 * Render movie pagination
 */
function blockter_render_movie_pagination( $max_num_pages, $posts_per_page, $paged ) {
	if ( $max_num_pages > 1 ) :
		?>
		<nav class="movie-pagination">
			<div class="pagination-left">
				<span><?php echo sprintf( esc_html__( "Movies per page: %d movies", 'blockter' ), $posts_per_page ); ?></span>
			</div>
			<div class="pagination-right">
				<div class="page-text">
					<span><?php printf( esc_html__( 'Page %1$d of %2$d:', 'blockter' ), $paged, $max_num_pages ); ?></span>
				</div>
				<?php
				$big = 999999999; // need an unlikely integer
				echo paginate_links(
					array(
						'base'      => str_replace( $big, '%#%', html_entity_decode( get_pagenum_link( $big, false ) ) ),
						'format'    => '?paged=%#%',
						'current'   => $paged,
						'total'     => $max_num_pages,
						'prev_text' => ' ',
						'next_text' => ' ',
						'type'      => 'list',
					)
				);
				?>
			</div>
		</nav>
		<?php
	endif;
}

/**
 * Get my account menu item classes
 */
function blockter_get_account_menu_item_classes( $endpoint ) {
	global $wp;

	$classes = array(
		'tab-link',
		'tab-link-' . $endpoint,
	);

	$current = false;

	// Set current item classes.
	if ( $wp->query_vars['section'] === $endpoint ) {
		$current = true;
	}

	if ( empty( $wp->query_vars['section'] ) && 'my-account' === $endpoint ) {
		$current = true;
	}

	if ( $current ) {
		$classes[] = 'current';
	}

	return implode( ' ', array_map( 'sanitize_html_class', $classes ) );
}

/**
 * Get account endpoint URL
 */
function blockter_get_account_endpoint_url( $endpoint ) {
	if ( 'my-account' === $endpoint ) {
		return blockter_get_page_permalink( 'my-account' );
	}

	if ( 'logout' === $endpoint ) {
		return wp_logout_url( home_url() );
	}

	return blockter_get_endpoint_url( $endpoint, blockter_get_page_permalink( 'my-account' ) );
}

/**
 * Retrieve page permalink.
 */
function blockter_get_page_permalink( $page_slug ) {
	$page      = get_page_by_path( $page_slug );
	$permalink = get_permalink( $page->ID );

	if ( ! $permalink ) {
		return get_home_url();
	}

	return $permalink;
}

/**
 * Get endpoint URL
 */
function blockter_get_endpoint_url( $endpoint, $permalink ) {
	return add_query_arg( 'section', $endpoint, $permalink );
}

/**
 * Add custom page header background image
 */
function blockter_custom_page_header_bg() {
	$current_term = get_queried_object();
	if ( ! empty( $current_term ) && property_exists( $current_term, 'term_id' ) ) {
		$current_taxonomy = $current_term->taxonomy;
		if ( 'mv_collection' === $current_taxonomy || 'mv_genre' === $current_taxonomy ) {
			$current_term_id = $current_term->term_id;
			$current_bg      = fw_get_db_term_option( $current_term_id, $current_taxonomy )['background_image'];

			if ( isset( $current_bg ) && '' !== $current_bg ) {
				return $current_bg['url'];
			}
		}
	} elseif ( is_single() ) {
		$custom_bg = (function_exists('fw_get_db_post_option')) ? fw_get_db_post_option(get_queried_object_id(), 'custom_bg') : 'no';
		if ( 'yes' === $custom_bg ) {
			return get_the_post_thumbnail_url( 0 );
		}
	}
	return '';
}

/**
 * Render casts list
 */
function blockter_casts_list( $cast_name, $cast_url, $cast_options ) {
	?>
	<div class="flw">
		<div class="cast-item flw">
			<div class="celebrity-thumbnail">
				<?php
				if ( array_key_exists( 'avatar_url', $cast_options ) && '' !== $cast_options['avatar_url'] ) :
					?>
					<a class="celebrity-img" href="<?php echo esc_url( $cast_url ); ?>">
						<img
							src="<?php echo esc_attr( $cast_options['avatar_url'] ); ?>"
							alt="<?php echo esc_attr__( 'Actor Avatar', 'blockter' ); ?>"
						>
					</a>
				<?php
				elseif ( array_key_exists( 'avatar', $cast_options ) && '' !== $cast_options['avatar'] ) :
					$att_id = $cast_options['avatar']['attachment_id'];
					?>
					<a  class="celebrity-img" href="<?php echo esc_url( $cast_url ); ?>">
						<?php echo wp_get_attachment_image( $att_id, array( 70, 70 ) ); ?>
					</a>
				<?php else : ?>
					<a  class="celebrity-img" href="<?php echo esc_url( $cast_url ); ?>">
						<div class="no-image"></div>
					</a>
				<?php endif; ?>
			</div><!-- .celebrity-thumbnail -->

			<div class="celebrity-summary">
				<div>
					<a class="actor-name" href="<?php echo esc_url( $cast_url ); ?>"><?php echo esc_html( $cast_name ); ?></a>
				</div>
				<?php if ( array_key_exists( 'knowfor', $cast_options ) && isset( $cast_options['knowfor'] ) ) : ?>
					<div class="celebrity-pos"><?php echo esc_html( $cast_options['knowfor'] ); ?></div>
				<?php endif; ?>
			</div><!-- .celebrity-summary -->
		</div><!-- .cast-item -->
	</div><!-- .flw -->
	<?php
}

if ( ! function_exists( 'buster_remove_curl' ) ) {
	function buster_remove_curl() {
		?>
		<div class="celebrity-summary">
		</div>
		<?php
	}
}

/* ADD PingBack header
***************************************************/
if ( ! function_exists( 'buster_pingback_header' ) ) {
	function buster_pingback_header() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}

}


add_action( 'wp_head', 'buster_pingback_header' );

/**
 * Helper to cache external images locally
 */
function blockter_cache_external_image($url) {
    if (empty($url)) return $url;
    
    // Convert Wikimedia thumbnail size directly to original non-thumbnail image to solve mediawiki restricts
    if (strpos($url, 'upload.wikimedia.org/wikipedia/commons/thumb/') !== false) {
        $url = preg_replace('/(https?:\/\/)?upload\.wikimedia\.org\/wikipedia\/commons\/thumb\/([^\/]+\/[^\/]+\/[^\/]+)\/.+$/i', 'https://upload.wikimedia.org/wikipedia/commons/$2', $url);
    }
    
    if (strpos($url, home_url()) !== false) return $url;
    
    $upload_dir = wp_upload_dir();
    $cache_dir = $upload_dir['basedir'] . '/cached-images';
    if (!file_exists($cache_dir)) wp_mkdir_p($cache_dir);
    
    $filename = md5($url) . '.jpg';
    $filepath = $cache_dir . '/' . $filename;
    $fileurl = $upload_dir['baseurl'] . '/cached-images/' . $filename;
    
    // If cache exists, verify it represents a valid image (not a small text/error file)
    if (file_exists($filepath)) {
        if (filesize($filepath) > 2048) {
            $handle = fopen($filepath, 'r');
            $first_bytes = fread($handle, 100);
            fclose($handle);
            if (stripos($first_bytes, '<html') === false && stripos($first_bytes, '<!DOCTYPE') === false) {
                return $fileurl;
            }
        }
        @unlink($filepath);
    }
    
    $args = array(
        'timeout'     => 30,
        'redirection' => 5,
        'headers'     => array(
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36',
            'Accept'     => 'image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8',
        )
    );
    
    $response = wp_remote_get($url, $args);
    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) return $url;
    
    $content_type = wp_remote_retrieve_header($response, 'content-type');
    if ($content_type && stripos($content_type, 'html') !== false) {
        return $url; // Do not cache html error pages
    }
    
    $body = wp_remote_retrieve_body($response);
    if (strlen($body) < 2048 || stripos($body, '<html') !== false || stripos($body, '<!DOCTYPE') !== false) {
        return $url; // Do not cache invalid content or error page
    }
    
    file_put_contents($filepath, $body);
    return $fileurl;
}