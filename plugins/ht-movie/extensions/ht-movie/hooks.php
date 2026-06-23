<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

/**
 * Define view for Movie template
 *
 * @param [type] $template
 * @return void
 */
function _filter_ht_movie_template_include($template){
	/**
	 * FW_Extension_Movie
	 * @var $recipe
	 */
	$ht_movie = fw()->extensions->get('ht-movie');
	if(is_singular($ht_movie->get_mv_post_type_name())){
		if($ht_movie->locate_view_path('single-movie')){
			return $ht_movie->locate_view_path('single-movie');
		}
	} elseif( is_singular( $ht_movie->get_show_post_type_name() ) ) {
		if ( $ht_movie->locate_view_path( 'single-show' ) ) {
			return $ht_movie->locate_view_path( 'single-show' );
		}
	} elseif (is_tax($ht_movie->get_genere_tax_name()) && $ht_movie->locate_view_path('mv_genre')){
			return $ht_movie->locate_view_path('mv_genre');
	} elseif (is_tax($ht_movie->get_collection_tax_name()) && $ht_movie->locate_view_path('mv_collection')){
			return $ht_movie->locate_view_path('mv_collection');
	} elseif (is_tax($ht_movie->get_actor_tax_name()) && $ht_movie->locate_view_path('mv_actor')){
			return $ht_movie->locate_view_path('mv_actor');
	}
	return $template;
}
add_filter('template_include', '_filter_ht_movie_template_include');

/**
 * Modifying Rest API Response
 */

 // Receive metabox value
function ht_movie_get_metabox($object, $field_name){
	$metabox = fw_get_db_post_option($object['id'], $field_name, '');
	return $metabox;
}

// Update metabox value
function ht_movie_update_metabox($value, $object, $field_name){
	if(!$value){
		return;
	}

	fw_set_db_post_option($object->ID, $field_name, $value);
}

// Register metabox to the rest api
add_action('rest_api_init', 'ht_movie_create_api_field');
function ht_movie_create_api_field(){
	
	register_rest_field(
		'ht_movie',
		'tagline',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);

	register_rest_field(
		'ht_movie',
		'overview',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);

	register_rest_field(
		'ht_movie',
		'release_date',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);

	register_rest_field(
		'ht_movie',
		'runtime',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);

	register_rest_field(
		'ht_movie',
		'languages',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);

	register_rest_field(
		'ht_movie',
		'production',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);

	register_rest_field(
		'ht_movie',
		'country',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);

	register_rest_field(
		'ht_movie',
		'directors',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);

	register_rest_field(
		'ht_movie',
		'writers',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);

	register_rest_field(
		'ht_movie',
		'banner',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);

	register_rest_field(
		'ht_movie',
		'gallery',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);

	register_rest_field(
		'ht_movie',
		'video',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);

	register_rest_field(
		'ht_show',
		'overview',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);

	register_rest_field(
		'ht_show',
		'creators',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);

	register_rest_field(
		'ht_show',
		'first_air_date',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);

	register_rest_field(
		'ht_show',
		'episode_runtime',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);

	register_rest_field(
		'ht_show',
		'production',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);

	register_rest_field(
		'ht_show',
		'country',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);

	register_rest_field(
		'ht_show',
		'languages',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);

	register_rest_field(
		'ht_show',
		'banner',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);

	register_rest_field(
		'ht_show',
		'gallery',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);

	register_rest_field(
		'ht_show',
		'video',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);
	register_rest_field(
		'ht_show',
		'seasons',
		array(
			'get_callback' => 'ht_movie_get_metabox',
			'update_callback' => 'ht_movie_update_metabox'
		)
	);
}

// add favourite enqueue script
function buster_favourite_enqueue_script() {
	wp_enqueue_script('jquery');
	wp_register_script( 'custom', fw()->extensions->get( 'ht-movie' )->locate_js_URI( 'favourite' ), array('jquery') );
	wp_localize_script( 'custom', 'favourite_params', array(
		'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php',
	) );
	wp_enqueue_script( 'custom' );
}
add_action( 'wp_enqueue_scripts', 'buster_favourite_enqueue_script' );

/**
 * Add favourite ajax handler
 */
function buster_add_favourite() {
	var_dump($_POST);exit;
	ob_clean();

	// user's meta key
	$meta_key_mv = 'favourite_mv_id';
	$meta_key_show = 'favourite_show_id';

	// get movie/tvshow ID from user's meta key
	$current_fav_mv_id = get_user_meta(  $_POST['user_id'], $meta_key_mv );
	$current_fav_show_id = get_user_meta(  $_POST['user_id'], $meta_key_show );

	// get movie/tvshow ID from AJAX
	$new_fav_mv_id = $_POST['post_id'];
	$new_fav_show_id = $_POST['show_id'];

	// add or remove movie ID to user's meta field
	if( in_array($new_fav_mv_id,$current_fav_mv_id) ) {
		delete_user_meta(  $_POST['user_id'], $meta_key_mv, $_POST['post_id'] );
	} else {
		add_user_meta(  $_POST['user_id'], $meta_key_mv, $_POST['post_id'] );
	}
	// add or remove tvshow id to user's meta field
	if( in_array($new_fav_show_id,$current_fav_show_id) ) {
		delete_user_meta(  $_POST['user_id'], $meta_key_show, $_POST['show_id'] );
	} else {
		add_user_meta(  $_POST['user_id'], $meta_key_show, $_POST['show_id'] );
	}

 	wp_die();
}
add_action('wp_ajax_nopriv_buster_add_favourite', 'buster_add_favourite');
add_action('wp_ajax_buster_add_favourite', 'buster_add_favourite');



/**
 * Add poster for imported movie
 */
function ht_movie_add_poster_src() {
	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/media.php');

	$url = $_GET['img'];
	$tmp = download_url( $url );
	$file_array = [];

	// Set variables for storage
	// Fix file filename for query strings
	preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $url, $matches);
    $file_array['name'] = basename($matches[0]);
    $file_array['tmp_name'] = $tmp;

    // If error storing temporarily, unlink
    if ( is_wp_error( $tmp ) ) {
        @unlink($file_array['tmp_name']);
        $file_array['tmp_name'] = '';
    }

    // Do the validation and storage stuff
	$id = media_handle_sideload( $file_array, 0, NULL );

	// If error storing permanently, unlink
	if ( is_wp_error($id) ) {
		@unlink($file_array['tmp_name']);
		return $id;
	}
	 wp_update_post([
        'ID' => $id,
        'post_parent' => $post_id
    ]);
	$response['id'] = $id;

	print_r(json_encode($response));
	die();
}

add_action( 'wp_ajax_ht_movie_add_poster_src', 'ht_movie_add_poster_src' );


/**
 * Add banner for imported movie
 */
function ht_movie_add_banner_src() {
	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/media.php');

	$url = $_GET['img'];
	$tmp = download_url( $url );
	$file_array = [];

	// Set variables for storage
	// Fix file filename for query strings
	preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $url, $matches);
    $file_array['name'] = basename($matches[0]);
    $file_array['tmp_name'] = $tmp;

    // If error storing temporarily, unlink
    if ( is_wp_error( $tmp ) ) {
        @unlink($file_array['tmp_name']);
        $file_array['tmp_name'] = '';
    }

    // Do the validation and storage stuff
	$id = media_handle_sideload( $file_array, 0, NULL );

	// If error storing permanently, unlink
	if ( is_wp_error($id) ) {
		@unlink($file_array['tmp_name']);
		return $id;
	}
	$src = wp_get_attachment_url( $id );

	$response['id'] = $id;
	$response['src'] = $src;

	print_r(json_encode($response));
	die();
}

add_action( 'wp_ajax_ht_movie_add_banner_src', 'ht_movie_add_banner_src' );

/**
 * Add genre term to mv_genre taxonomy
 */
function ht_movie_add_genres() {
	$input_terms = explode(',', $_POST['genres']);
	$terms = [];

	foreach ( $input_terms as $term ) {
		$existent_term = term_exists( $term, 'mv_genre' );

		if ( $existent_term && isset( $existent_term['term_id'] ) ) {
			$term_id = $existent_term['term_id'];
		} else {
			// Insert the term if it doesn't exist
			$term = wp_insert_term( $term, 'mv_genre' );
			if ( !is_wp_error($term) && isset( $term['term_id'] ) ) {
				$term_id = $term['term_id'];
			}
		}

		$terms[] = (int) $term_id;
	}
	$response['result'] = $terms;
	print_r(json_encode($response));
	die();
}

add_action( 'wp_ajax_ht_movie_add_genres', 'ht_movie_add_genres' );

/**
 * Add actor term to mv_actor
 */
function ht_movie_add_cast() {
	
	$input_terms = explode( ',', $_POST['casts'] );
	$input_avatar = explode( ',', $_POST['avatar_url'] );
	$genders = explode( ',', $_POST['gender'] );
	$cast_id = isset( $_POST['person_id'] ) ? explode( ',', $_POST['person_id'] ) : array();
	$key_api = isset( $_POST['api_key'] ) ? $_POST['api_key'] : '';
	foreach ($genders as $i => $gender) {
		if ( 1 == $gender ) {
			$genders[$i] = 'Female';
		}
		else {
			$genders[$i] = 'Male';
		}
	}
	$terms = [];
	$i = 0;
	foreach ( $input_terms as $term ) {
		$existent_term = term_exists( $term, 'mv_actor' );

		if ( $existent_term && isset( $existent_term['term_id'] ) ) {
			$term_id = $existent_term['term_id'];
		} else {
			// Insert the term if it doesn't exist
			$term = wp_insert_term( $term, 'mv_actor' );
			if ( !is_wp_error($term) && isset( $term['term_id'] ) ) {
				$term_id = $term['term_id'];
				if ( $input_avatar[$i] ) {
					fw_set_db_term_option(
						$term_id,
						'mv_actor',
						'avatar_url',
						'https://image.tmdb.org/t/p/w300_and_h450_bestv2' . $input_avatar[$i]
					);
				}
				if ( $genders[$i] ) {
					fw_set_db_term_option(
						$term_id,
						'mv_actor',
						'gender',
						$genders[$i]
					);
				}
				if ( isset( $_POST['person_id'] ) && ! empty($_POST['person_id']) ) {

					$url = "https://api.themoviedb.org/3/person/".$cast_id[$i]."?api_key=".$key_api."&language=en-US&append_to_response=external_ids";
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

					// get the (still encoded) JSON data:
					$json = curl_exec($ch);
					curl_close($ch);

					// Decode JSON response:
					$cast = json_decode($json, true);
					//wc_get_logger()->info('Cast Data: ' . print_r($cast, true));
					update_term_meta( $term_id, '_person_id', $cast_id[$i]);
					fw_set_db_term_option(
						$term_id,
						'mv_actor',
						'biography',
						$cast['biography']
					);

					fw_set_db_term_option(
						$term_id,
						'mv_actor',
						'dateofbirth',
						$cast['birthday']
					);

					fw_set_db_term_option(
						$term_id,
						'mv_actor',
						'knowfor',
						$cast['known_for_department']
					);
					fw_set_db_term_option(
						$term_id,
						'mv_actor',
						'country',
						$cast['place_of_birth']
					);
					if( isset($cast['external_ids']['facebook_id']) && !empty($cast['external_ids']['facebook_id']) ) {
						fw_set_db_term_option(
							$term_id,
							'mv_actor',
							'facebook_link',
							'https://www.facebook.com/' . $cast['external_ids']['facebook_id']
						);
					}
					if( isset($cast['external_ids']['twitter_id']) && !empty($cast['external_ids']['twitter_id']) ) {
						fw_set_db_term_option(
							$term_id,
							'mv_actor',
							'twitter_link',
							'https://www.twitter.com/' . $cast['external_ids']['twitter_id']
						);
					}
					if( isset($cast['external_ids']['instagram_id']) && !empty($cast['external_ids']['instagram_id']) ) {
						fw_set_db_term_option(
							$term_id,
							'mv_actor',
							'instagram_link',
							'https://www.instagram.com/' . $cast['external_ids']['instagram_id']
						);
					}
					// fw_set_db_term_option(
					// 	$term_id,
					// 	'mv_actor',
					// 	'knowfor',
					// 	$cast['"known_for_department"']
					// );
			//	$url = "https://api.themoviedb.org/3/person/".$cast_id[$i]."?api_key=".$key_api."&language=en-US";

				}
			}

		}

		$terms[] = (int) $term_id;
		// $terms[] = $term_option;
		$i++;
	}

	$response['result'] = $terms;

	print_r(json_encode($response));
	die();

}

add_action( 'wp_ajax_ht_movie_add_cast', 'ht_movie_add_cast' );

/**
 * Add collection term to mv_collection
 */
function ht_movie_add_collection() {
	$term = $_GET['collection'];

	$existent_term = term_exists( $term, 'mv_collection' );

	if ( $existent_term && isset( $existent_term['term_id'] ) ) {
		$term_id = $existent_term['term_id'];
	} else {
		// Insert the term if it doesn't exist
		$term = wp_insert_term( $term, 'mv_collection' );
		if ( !is_wp_error($term) && isset( $term['term_id'] ) ) {
			$term_id = $term['term_id'];
		}
	}

	$response = $term_id;

	print_r(json_encode($response));
	die();
}
add_action( 'wp_ajax_ht_movie_add_collection', 'ht_movie_add_collection' );

/**
 * Add settings link to menu options.
 */
function ht_movie_add_setting_link() {
    global $submenu;
    $url = admin_url() . 'admin.php?page=fw-extensions&sub-page=extension&extension=ht-movie';
    $submenu['edit.php?post_type=ht_movie'][] = array( esc_html__( 'Settings', 'blockter' ), 'manage_options', $url );
    $submenu['edit.php?post_type=ht_show'][] = array( esc_html__( 'Settings', 'blockter' ), 'manage_options', $url );
}
add_action( 'admin_menu', 'ht_movie_add_setting_link' );

/**
 * Get trailer for hosted video
 * on Movie single
 *
 * @param [type] $hosted_videos
 * @return html
 */
function ht_movie_hosted_movie_trailer($hosted_videos){
	?>
<div class="btn-transform transform-vertical red">
    <div><a class="item item-1 redbtn"> <i
                class="ion-play"></i><?php echo esc_html__("Watch Trailer", 'blockter'); ?></a></div>
    <div><a data-fancybox data-src="#trailer" href="javascript:;" class="item item-2 redbtn fancybox-media hvr-grow"><i
                class="ion-play"></i></a></div>
    <video style="display: none;" id="trailer" playsinline controls>
        <source src="<?php echo $hosted_videos[0]['movie_url']; ?>" type="video/mp4">
        <!-- <source src="/path/to/video.webm" type="video/webm"> -->
    </video>
</div>
<?php
}

/**
 * Get trailer for iframe video
 *
 * @param [type] $iframe_videos
 * @return void
 */
function ht_movie_iframe_movie_trailer($iframe_videos){
	?>
<div class="btn-transform transform-vertical red">
    <div><a class="item item-1 redbtn"> <i
                class="ion-play"></i><?php echo esc_html__("Watch Trailer", 'blockter'); ?></a></div>
    <div><a data-fancybox data-src="#trailer" href="javascript:;" class="item item-2 redbtn fancybox-media hvr-grow"><i
                class="ion-play"></i></a></div>
    <div style="display: none;" id="trailer">
        <?php echo $iframe_videos[0]['movie_iframe']; ?>
    </div>
</div>
<?php
}

/**
 * Get hosted media
 *
 * @param [type] $hosted_videos
 * @param boolean $limit
 * @return void
 */
function ht_movie_hosted_media($hosted_videos, $limit = false){
	$i = 0;
	foreach($hosted_videos as $hosted_video) :
		if($i > 1 && $limit == true){
			break;
		}
		$rand_video_id = fw_unique_increment();
	?>
<div class="vd-it">
    <a data-fancybox data-src="#player-<?php echo $rand_video_id; ?>" href="javascript:;"><img
            src="<?php echo $hosted_video['movie_thumb']['url']; ?>"></a>
    <video style="display: none;" id="player-<?php echo $rand_video_id; ?>" playsinline controls>
        <source src="<?php echo $hosted_video['movie_url']; ?>" type="video/mp4">
        <!-- <source src="/path/to/video.webm" type="video/webm"> -->
    </video>
    <span class="vd-title"><?php echo $hosted_video['movie_title']; ?></span>
</div>
<?php
	$i++;
	endforeach;
}

/**
 * Get hosted media
 *
 * @param [type] $hosted_videos
 * @param boolean $limit
 * @return void
 */
function ht_movie_iframe_media($iframe_videos, $limit = false){
	$i = 0;
	foreach($iframe_videos as $iframe_video) :
		if($i > 1 && $limit == true){
			break;
		}
		$rand_video_id = fw_unique_increment();
	?>
<div class="vd-it">
    <a data-fancybox data-src="#player-<?php echo $rand_video_id; ?>" href="javascript:;"><img
            src="<?php echo $iframe_video['movie_thumb']['url']; ?>"></a>
    <div style="display: none;" id="player-<?php echo $rand_video_id; ?>">
        <?php echo $iframe_video['movie_iframe']; ?>
    </div>
    <span class="vd-title"><?php echo $iframe_video['movie_title']; ?></span>
</div>
<?php
	$i++;
	endforeach;
}

/**
 * Get youtube media
 *
 * @param [type] $videoId
 * @param [type] $thumbURL
 * @param [type] $title
 * @return void
 */
function ht_movie_youtube_media($videoId, $thumbURL, $title){
	?>
<div class="vd-it">
    <a class="fancybox-media hvr-grow"
        href="https://www.youtube.com/watch?v=<?php echo esc_attr($videoId); ?>"><?php  echo '<img src="'.$thumbURL.'"/>'; ?></a>
    <span class="vd-title">
        <?php esc_html_e($title); ?>
    </span>
</div>
<?php
}

/*
add authors menu filter to admin post list for HT Movie
*/
function ht_movie_restrict_manage_authors() {
    if (isset($_GET['post_type']) && post_type_exists($_GET['post_type']) && in_array(strtolower($_GET['post_type']), array('ht_movie', 'ht_movie_2'))) {
        wp_dropdown_users(array(
            'show_option_all'   => 'Show all Authors',
            'show_option_none'  => false,
            'name'          => 'author',
            'selected'      => !empty($_GET['author']) ? $_GET['author'] : 0,
            'include_selected'  => false
        ));
    }
}
add_action('restrict_manage_posts', 'ht_movie_restrict_manage_authors');
 
function ht_movie_custom_columns_author($columns) {
    $columns['author'] = 'Author';
    return $columns;
}
add_filter('manage_edit-ht_movie_columns', 'ht_movie_custom_columns_author');

function ht_movie_pagination($total_page){
	if ($total_page > 1){

		$current_page = max(1, get_query_var('paged'));
		echo '<nav class="pagination ht-movie-pagination">';
		echo paginate_links(array(
			'base'         => get_pagenum_link(1) . '%_%',
			'format'       => '/page/%#%',
			'current'      => $current_page,
			'total'        => $total_page,
			'prev_text'    => esc_html__( 'Prev', 'ht_movie' ),
			'next_text'    => esc_html__( 'Next', 'ht_movie' ),
			'end_size'     => 3,
			'mid_size'     => 3,
		));
		echo '</nav>';
		}
}

function ht_movie_body_classes( $classes ) {
	$classes[] = 'ht-movie-' . HT_MOVIE_VER;

    return $classes;
}

add_filter( 'body_class', 'ht_movie_body_classes' );