<?php
defined( 'ABSPATH' ) or exit;

if(!class_exists('HT_Movie_Extended_Admin_Ajax')) {
    class HT_Movie_Extended_Admin_Ajax{
         public function __construct() {
             add_action('wp_ajax_ht_movie_fetch_trending', [&$this, 'process_trending_fetch_request']);
             add_action('wp_ajax_sync_tmdb_ids',  [&$this, 'sync_tmdb_ids_callback']);
             add_action('wp_ajax_ht_movie_add_keywords', [&$this,'ht_movie_add_keywords']);
             add_action('wp_ajax_ht_testing_watch', [&$this,'ht_testing_watch_callback']);
             add_action('wp_ajax_ht_tmdb_manual_sync', [&$this,'ht_tmdb_manual_sync']);

             add_action('wp_ajax_ht_sync_attachments', [&$this,'ht_sync_all_media']);



             add_action('wp_ajax_ht_get_posts_missing_keywords', function () {

    $posts = get_posts([
        'post_type' => ['ht_movie', 'ht_show'],
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key' => '_tmdb_id',
                'compare' => 'EXISTS'
            ]
        ]
    ]);

    $data = [];

    foreach ($posts as $p) {

        $terms = wp_get_object_terms($p->ID, 'mv_keyword');

        if (!empty($terms)) continue; // already has keywords

        $tmdb_id = get_post_meta($p->ID, '_tmdb_id', true);

        if (!$tmdb_id) continue;

        $type = ($p->post_type === 'ht_show') ? 'tv' : 'movie';

        $data[] = [
            'post_id' => $p->ID,
            'tmdb_id' => $tmdb_id,
            'type' => $type
        ];
    }

    wp_send_json_success($data);
});
add_action('wp_ajax_ht_fix_keywords', function () {

    $post_id = intval($_POST['post_id']);
    $tmdb_id = intval($_POST['tmdb_id']);
    $type    = sanitize_text_field($_POST['type']);

    $api = fw_get_db_ext_settings_option( 'ht-movie', 'api-key', NULL );

    $endpoint = ($type === 'tv')
        ? "https://api.themoviedb.org/3/tv/$tmdb_id/keywords"
        : "https://api.themoviedb.org/3/movie/$tmdb_id/keywords";

    $res = wp_remote_get($endpoint . "?api_key=$api");

    if (is_wp_error($res)) {
        wp_send_json_error();
    }

    $data = json_decode(wp_remote_retrieve_body($res), true);
    if ($type === 'tv') {
        $keywords = $data['results'] ?? [];
    } else {
        $keywords = $data['keywords'] ?? [];
    }

    if (empty($keywords)) {
        wp_send_json_error();
    }

    $terms = [];

    foreach ($keywords as $k) {

        $term = term_exists($k['name'], 'mv_keyword');

        // if (!$term) {
        //     $term = wp_insert_term($k['name'], 'mv_keyword');
        // }

        if (!is_wp_error($term)) {
            $terms[] = (int)$term['term_id'];
        }
    }

    wp_set_object_terms($post_id, $terms, 'mv_keyword', false);

    wp_send_json_success();
});
             add_action('wp_ajax_get_unmatched_posts', function () {

    $posts = get_posts([
        'post_type' => ['ht_movie', 'ht_show'],
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key' => '_tmdb_id',
                'compare' => 'NOT EXISTS'
            ]
        ]
    ]);

    $data = [];

    foreach ($posts as $post) {

        $date = function_exists('fw_get_db_post_option') 
            ? fw_get_db_post_option($post->ID, 'release_date') 
            : '';

        $data[] = [
            'id' => $post->ID,
            'title' => $post->post_title,
            'year' => $date ? date('Y', strtotime($date)) : '',
            'type' => $post->post_type
        ];
    }

    wp_send_json_success($data);
});
add_action('wp_ajax_search_tmdb_manual', function () {

    $query = sanitize_text_field($_POST['query']);
    $type  = ($_POST['type'] === 'ht_movie') ? 'movie' : 'tv';

    $apiKey = fw_get_db_ext_settings_option( 'ht-movie', 'api-key', NULL );

    $url = "https://api.themoviedb.org/3/search/{$type}?api_key={$apiKey}&query=" . urlencode($query);

    $res = wp_remote_get($url);

    if (is_wp_error($res)) {
        wp_send_json_error();
    }

    $body = json_decode(wp_remote_retrieve_body($res), true);

    wp_send_json_success($body['results']);
});
add_action('wp_ajax_save_tmdb_id', function () {

    $post_id = intval($_POST['post_id']);
    $tmdb_id = intval($_POST['tmdb_id']);

    update_post_meta($post_id, '_tmdb_id', $tmdb_id);

    wp_send_json_success();
});

//
 add_action('wp_ajax_ht_bulk_import_tmdb',  [&$this, 'bulk_import_tmdb_callback']);
 add_action('wp_ajax_ht_check_tmdb_duplicate', [&$this, 'ht_check_tmdb_duplicate']);
 add_action('wp_ajax_nopriv_ht_check_tmdb_duplicate', [&$this, 'ht_check_tmdb_duplicate']);
add_action('wp_ajax_ht_fix_cast_person_id', [&$this, 'ht_fix_cast_person_id']);

add_action('wp_ajax_ht_sync_cast_full', [&$this, 'ht_sync_cast_full']);
add_action('wp_ajax_ht_sync_single_actor', [&$this, 'ht_sync_single_actor']);
add_action('wp_ajax_ht_test_provider_list', [&$this, 'ht_test_provider_list']);
  add_action('wp_ajax_ht_sync_watch_providers', [&$this, 'ht_sync_watch_providers']);
add_action('wp_ajax_ht_movie_add_networks', [&$this, 'ht_movie_add_networks']);
add_action('wp_ajax_ht_scan_duplicate_media', [&$this, 'ht_scan_duplicate_media']);
add_action('wp_ajax_ht_delete_duplicate_media', [&$this, 'ht_delete_duplicate_media']);

add_action('wp_ajax_generate_collection_og_images', [&$this, 'generate_collection_og_images']);
         }
         public function create_collection_og_image($source_path, $title = '') {

    $uploads = wp_upload_dir();

    $dir = $uploads['basedir'] . '/collection-og';

    if (!file_exists($dir)) {
        wp_mkdir_p($dir);
    }

    $filename = sanitize_title($title) . '-og.jpg';

    $output_path = $dir . '/' . $filename;

    $info = getimagesize($source_path);

    if (!$info) {
        return false;
    }

    switch ($info['mime']) {

        case 'image/jpeg':
            $src = imagecreatefromjpeg($source_path);
            break;

        case 'image/png':
            $src = imagecreatefrompng($source_path);
            break;

        case 'image/webp':
            $src = imagecreatefromwebp($source_path);
            break;

        default:
            return false;
    }

    $dst_w = 1200;
    $dst_h = 630;

    $src_w = imagesx($src);
    $src_h = imagesy($src);

    $target_ratio = $dst_w / $dst_h;
    $source_ratio = $src_w / $src_h;

    if ($source_ratio > $target_ratio) {

        // wider image
        $crop_h = $src_h;
        $crop_w = intval($crop_h * $target_ratio);

        $crop_x = intval(($src_w - $crop_w) / 2);
        $crop_y = 0;

    } else {

        // taller image
        $crop_w = $src_w;
        $crop_h = intval($crop_w / $target_ratio);

        $crop_x = 0;
        $crop_y = intval(($src_h - $crop_h) / 2);
    }

    $dst = imagecreatetruecolor($dst_w, $dst_h);

    imagecopyresampled(
        $dst,
        $src,
        0,
        0,
        $crop_x,
        $crop_y,
        $dst_w,
        $dst_h,
        $crop_w,
        $crop_h
    );

    /*
     * Bottom gradient
     */

    for ($i = 0; $i < 200; $i++) {

        $alpha = min(110, intval($i / 2));

        $color = imagecolorallocatealpha(
            $dst,
            0,
            0,
            0,
            $alpha
        );

        imageline(
            $dst,
            0,
            $dst_h - $i,
            $dst_w,
            $dst_h - $i,
            $color
        );
    }

    /*
     * Optional title
     */

    $font = get_stylesheet_directory() . '/assets/fonts/Roboto-Bold.ttf';

    if (
        $title &&
        file_exists($font) &&
        function_exists('imagettftext')
    ) {

        $shadow = imagecolorallocate($dst, 0, 0, 0);
        $white  = imagecolorallocate($dst, 255, 255, 255);

        imagettftext(
            $dst,
            34,
            0,
            42,
            585,
            $shadow,
            $font,
            $title
        );

        imagettftext(
            $dst,
            34,
            0,
            40,
            583,
            $white,
            $font,
            $title
        );
    }

    imagejpeg(
        $dst,
        $output_path,
        92
    );

    imagedestroy($src);
    imagedestroy($dst);

    /*
     * Create attachment
     */

    $attachment = [
        'post_mime_type' => 'image/jpeg',
        'post_title'     => $title . ' OG Image',
        'post_status'    => 'inherit'
    ];

    $attachment_id = wp_insert_attachment(
        $attachment,
        $output_path
    );

    update_post_meta(
        $attachment_id,
        '_wp_attachment_metadata',
        []
    );

    return $attachment_id;
}
         public function generate_collection_og_images() {
             if (!current_user_can('manage_options')) {
                         wp_send_json_error('Permission denied');
                }
                 $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
                 $limit  = 25;
                   $terms = get_terms([
                    'taxonomy'   => 'mv_collection',
                    'hide_empty' => false,
                    'number'     => $limit,
                    'offset'     => $offset
                ]);
                 if (empty($terms)) {
                    wp_send_json_success([
                        'finished' => true
                    ]);
                }
                $bg=[];
                foreach ($terms as $term) {
                     $term_id = $term->term_id;
                      $image_id = get_term_meta(
                        $term_id,
                        'rank_math_facebook_image_id',
                        true
                    );
                    if (!$image_id) {
                            continue;
                        }
                        $attachment_path = get_attached_file($image_id);
                                if (!$attachment_path || !file_exists($attachment_path)) {
                                      continue;
                                }
                                		//	$current_bg      = fw_get_db_term_option( $term_id, 'mv_collection' )['background_image'];

                                		//	$current_bg      = fw_get_db_term_option( $term_id, 'mv_collection' )['background_image'];
                                   // $bg[]=$current_bg;
                                //    $new_attachment_id=$current_bg['attachment_id'] ?? '';
                                 
                                $new_attachment_id =$this->create_collection_og_image(
                               $attachment_path,
                                 $term->name
                             );
                            if ($new_attachment_id) {
                            update_term_meta(
                                $term_id,
                                'rank_math_facebook_image_id',
                                $new_attachment_id
                            );

                            update_term_meta(
                                $term_id,
                                'rank_math_facebook_image',
                                wp_get_attachment_url($new_attachment_id)
                            );

                            }



                }
                 wp_send_json_success([
                    'finished' => false,
                     'next'     => $offset + $limit
                    ]);

         }
         public function ht_delete_duplicate_media(){

    $duplicates = get_option(
        'ht_duplicate_media_scan',
        []
    );

    if (empty($duplicates)) {

        wp_send_json_error([
            'message' => 'No duplicate scan data found.'
        ]);
    }

    $deleted = [];
    $failed  = [];

    foreach ($duplicates as $item) {

        $attachment_id = $item['delete_id'];

        if (!$attachment_id) {
            continue;
        }

        // =========================================
        // DOUBLE SAFETY CHECK
        // =========================================

        $attachment = get_post($attachment_id);

        if (!$attachment) {

            $failed[] = [
                'id' => $attachment_id,
                'reason' => 'Attachment not found'
            ];

            continue;
        }

        // =========================================
        // DELETE ATTACHMENT + FILES
        // =========================================

        $result = wp_delete_attachment(
            $attachment_id,
            true
        );

        if ($result) {

            $deleted[] = [
                'id' => $attachment_id,
                'file' => $item['file']
            ];

        } else {

            $failed[] = [
                'id' => $attachment_id,
                'reason' => 'Delete failed'
            ];
        }
    }

    // =========================================
    // CLEAR SCAN CACHE
    // =========================================

    delete_option('ht_duplicate_media_scan');

    wp_send_json_success([

        'deleted_count' => count($deleted),

        'failed_count' => count($failed),

        'deleted' => $deleted,

        'failed' => $failed
    ]);
}
  public function ht_normalize_filename($file) {

   // filename without extension
    $name = pathinfo($file, PATHINFO_FILENAME);

    // remove WordPress duplicate suffixes
    // example:
    // image-1
    // image-2
    // image-12
    $name = preg_replace('/-\d+$/', '', $name);

    // lowercase for consistency
    $name = strtolower($name);

    return $name;
}
     
public function ht_scan_duplicate_media(){
     $attachments = get_posts([
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'posts_per_page' => -1,
        'post_status' => 'inherit'
    ]);
    usort($attachments, function($a, $b){

   return $a->ID - $b->ID;

});
       $seen = [];
       $duplicates = [];
      foreach ($attachments as $attachment) {
     
         $path = get_attached_file($attachment->ID);

         $url=  wp_get_attachment_url($attachment->ID);
           if (!$path || !file_exists($path)) {
            continue;
        }
         $normalized = $this->ht_normalize_filename(
            basename($path)
        );
          $size = filesize($path);
          if (!$size) {
            continue;
        }
         $hash = md5_file($path);
                if (!$hash) {
            continue;
        }
                $key = $normalized;
        if (!isset($seen[$key])) {

            $seen[$key] = [
                'id'   => $attachment->ID,
                'path' => $path
            ];

            continue;
        }

        $duplicates[] = [

            'keep_id'   => $seen[$key]['id'],
            'delete_id' => $attachment->ID,

            'file'      => $path,
            'url'      => $url,
            'size'      => size_format($size),
        ];

      }

    update_option('ht_duplicate_media_scan', $duplicates);

    wp_send_json_success([
        'count' => count($duplicates),
        'duplicates' => $duplicates
    ]);
}
public function ht_movie_add_networks() {

    $providers = json_decode(stripslashes($_POST['providers']), true);

    if (empty($providers)) {
        wp_send_json(['result' => 0]);
    }

    $term_ids = [];

    foreach ($providers as $p) {

        $country_slug = ($p['country'] === 'US') ? 'us' : 'uk';

        // parent
        $parent = term_exists($country_slug, 'networks');

        if (!$parent) {
            $parent = wp_insert_term(ucfirst($country_slug), 'networks', [
                'slug' => $country_slug
            ]);
        }

        $parent_id = is_array($parent) ? $parent['term_id'] : $parent;

        // child
        $term = term_exists($p['name'], 'networks', $parent_id);

        if (!$term) {
            $term = wp_insert_term($p['name'], 'networks', [
                'parent' => $parent_id
            ]);
        }

        $term_id = is_array($term) ? $term['term_id'] : $term;

        // save logo
        if (!empty($p['logo'])) {
            update_term_meta(
                $term_id,
                'logo',
                'https://image.tmdb.org/t/p/w200' . $p['logo']
            );
        }

        $term_ids[] = $term_id;
    }

    wp_send_json(['result' => $term_ids]);
}
public function ht_sync_watch_providers() {

    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $api  = fw_get_db_ext_settings_option( 'ht-movie', 'api-key', NULL );

    $query = new WP_Query([
        'post_type' => ['ht_movie', 'ht_show'],
        'posts_per_page' => 5,
        'paged' => $page,
        'post_status' => 'publish'
    ]);

    $updated = 0;

    foreach ($query->posts as $post) {

        $post_id = $post->ID;
        $tmdb_id = get_post_meta($post_id, '_tmdb_id', true);

        if (!$tmdb_id) continue;

        $type = ($post->post_type === 'ht_show') ? 'tv' : 'movie';

        // 🔥 API CALL
        $url = "https://api.themoviedb.org/3/$type/$tmdb_id/watch/providers?api_key=$api";

        $res = wp_remote_get($url);

        if (is_wp_error($res)) continue;

        $data = json_decode(wp_remote_retrieve_body($res), true);

        if (empty($data['results'])) continue;

        $countries = [
    'US' => 'us',
    'GB' => 'uk'
];

foreach ($countries as $code => $slug) {

    if (empty($data['results'][$code])) continue;

    $countryData = $data['results'][$code];

    // 🔥 MERGE flatrate + buy
    $providers_raw = array_merge(
        $countryData['flatrate'] ?? [],
        $countryData['buy'] ?? []
    );

    if (empty($providers_raw)) continue;

    // =========================
    // REMOVE DUPLICATES (IMPORTANT)
    // =========================
    $providers = [];
    $seen = [];

    foreach ($providers_raw as $p) {
        if (!in_array($p['provider_id'], $seen)) {
            $seen[] = $p['provider_id'];
            $providers[] = $p;
        }
    }

    // =========================
    // PARENT TERM (US / UK)
    // =========================
    $parent = term_exists($slug, 'networks');

    if (!$parent) {
        $parent = wp_insert_term(ucfirst($slug), 'networks', [
            'slug' => $slug
        ]);
    }

    $parent_id = is_array($parent) ? $parent['term_id'] : $parent;

    $provider_ids = [];
    foreach ($providers as $provider) {

        $name = $provider['provider_name'];

        // 🔥 check under parent (IMPORTANT)
        $term = term_exists($name, 'networks', $parent_id);

        if (!$term) {
            $term = wp_insert_term($name, 'networks', [
                'parent' => $parent_id
            ]);
        }

        $term_id = is_array($term) ? $term['term_id'] : $term;

        // optional logo
        if (!get_term_meta($term_id, 'logo', true) && !empty($provider['logo_path'])) {
            update_term_meta(
                $term_id,
                'logo',
                'https://image.tmdb.org/t/p/w200' . $provider['logo_path']
            );
        }

        $provider_ids[] = intval($term_id);
    }

    // =========================
    // ASSIGN TERMS
    // =========================
    if (!empty($provider_ids)) {
        wp_set_post_terms($post_id, $provider_ids, 'networks', true);
        $updated++;
        }
        }
    }

    wp_send_json_success([
        'updated' => $updated,
        'page' => $page,
        'has_more' => $query->max_num_pages > $page
    ]);
}

public function ht_test_provider_list() {
    $api = fw_get_db_ext_settings_option( 'ht-movie', 'api-key', NULL );

    $url = "https://api.themoviedb.org/3/watch/providers/movie?language=en-US&watch_region=US&api_key=$api";

    $res = wp_remote_get($url);

    if (is_wp_error($res)) {
        wp_send_json_error();
    }

    $data = json_decode(wp_remote_retrieve_body($res), true);

    wp_send_json_success($data);
}
public function ht_sync_single_actor() {

    $term_id = intval($_POST['term_id']);
    $api     = fw_get_db_ext_settings_option( 'ht-movie', 'api-key', NULL );

    $person_id = get_term_meta($term_id, '_person_id', true);

    if (!$person_id) {
        wp_send_json_error();
    }

    $url = "https://api.themoviedb.org/3/person/$person_id?api_key=$api&language=en-US&append_to_response=external_ids";

    $res = wp_remote_get($url);

    if (is_wp_error($res)) {
        wp_send_json_error();
    }

    $data = json_decode(wp_remote_retrieve_body($res), true);

    if (empty($data)) {
        wp_send_json_error($data);
    }

					fw_set_db_term_option(
						$term_id,
						'mv_actor',
						'gender',
						$data['gender'] ==1 ? 'Female' : 'Male'
					);
			fw_set_db_term_option(
						$term_id,
						'mv_actor',
						'avatar_url',
						'https://image.tmdb.org/t/p/w300_and_h450_bestv2' . $data['profile_path']
					);
        fw_set_db_term_option(
						$term_id,
						'mv_actor',
						'biography',
						$data['biography']
					);
                    fw_set_db_term_option(
						$term_id,
						'mv_actor',
						'dateofbirth',
						$data['birthday']
					);

					fw_set_db_term_option(
						$term_id,
						'mv_actor',
						'knowfor',
						$data['known_for_department']
					);
					fw_set_db_term_option(
						$term_id,
						'mv_actor',
						'country',
						$data['place_of_birth']
					);
                    if( isset($data['external_ids']['facebook_id']) && !empty($data['external_ids']['facebook_id']) ) {
						fw_set_db_term_option(
							$term_id,
							'mv_actor',
							'facebook_link',
							'https://www.facebook.com/' . $data['external_ids']['facebook_id']
						);
					}
					if( isset($data['external_ids']['twitter_id']) && !empty($data['external_ids']['twitter_id']) ) {
						fw_set_db_term_option(
							$term_id,
							'mv_actor',
							'twitter_link',
							'https://www.twitter.com/' . $data['external_ids']['twitter_id']
						);
					}
					if( isset($data['external_ids']['instagram_id']) && !empty($data['external_ids']['instagram_id']) ) {
						fw_set_db_term_option(
							$term_id,
							'mv_actor',
							'instagram_link',
							'https://www.instagram.com/' . $data['external_ids']['instagram_id']
						);
					}

    wp_send_json_success($data);
}
public function ht_sync_cast_full() {

    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $api  = fw_get_db_ext_settings_option( 'ht-movie', 'api-key', NULL );

    // 🔥 Loop TERMS (not posts anymore — faster)
   
    $argc=[
        'taxonomy' => 'mv_actor',
        'hide_empty' => false,
        'number' => 20,
        'offset' => $page > 1 ? ($page - 1) * 20 : 0
    ];
   
    $terms = get_terms($argc);
     
    if (empty($terms) || is_wp_error($terms)) {
        wp_send_json_success([
            'updated' => 0,
            'done' => true
        ]);
    }
     
    $updated = 0;

    foreach ($terms as $term) {

        $term_id = $term->term_id;

        // ✅ get person_id
        $person_id = get_term_meta($term_id, '_person_id', true);

        if (!$person_id) continue;

        // ✅ skip already synced
        // if (get_term_meta($term_id, '_cast_full_synced', true)) {
        //     continue;
        // }

        // 🔥 API CALL
        $url = "https://api.themoviedb.org/3/person/$person_id?api_key=$api&language=en-US&append_to_response=external_ids";

        $res = wp_remote_get($url);

        if (is_wp_error($res)) continue;

        $data = json_decode(wp_remote_retrieve_body($res), true);

        if (empty($data)) continue;

                    wp_update_term($term_id, 'mv_actor', array(
                    'description' => $data['biography'] ?? '',
                    ));
					fw_set_db_term_option(
						$term_id,
						'mv_actor',
						'gender',
						$data['gender'] ==1 ? 'Female' : 'Male'
					);
			fw_set_db_term_option(
						$term_id,
						'mv_actor',
						'avatar_url',
						'https://image.tmdb.org/t/p/w300_and_h450_bestv2' . $data['profile_path']
					);
        fw_set_db_term_option(
						$term_id,
						'mv_actor',
						'biography',
						$data['biography']
					);
                    fw_set_db_term_option(
						$term_id,
						'mv_actor',
						'dateofbirth',
						$data['birthday']
					);

					fw_set_db_term_option(
						$term_id,
						'mv_actor',
						'knowfor',
						$data['known_for_department']
					);
					fw_set_db_term_option(
						$term_id,
						'mv_actor',
						'country',
						$data['place_of_birth']
					);
                    if( isset($data['external_ids']['facebook_id']) && !empty($data['external_ids']['facebook_id']) ) {
						fw_set_db_term_option(
							$term_id,
							'mv_actor',
							'facebook_link',
							'https://www.facebook.com/' . $data['external_ids']['facebook_id']
						);
					}
					if( isset($data['external_ids']['twitter_id']) && !empty($data['external_ids']['twitter_id']) ) {
						fw_set_db_term_option(
							$term_id,
							'mv_actor',
							'twitter_link',
							'https://www.twitter.com/' . $data['external_ids']['twitter_id']
						);
					}
					if( isset($data['external_ids']['instagram_id']) && !empty($data['external_ids']['instagram_id']) ) {
						fw_set_db_term_option(
							$term_id,
							'mv_actor',
							'instagram_link',
							'https://www.instagram.com/' . $data['external_ids']['instagram_id']
						);
					}
        // mark synced
        update_term_meta($term_id, '_cast_full_synced', 1);

        $updated++;
    }

    wp_send_json_success([
        'updated' => $updated,
        'done' => false
    ]);
}

   public  function ht_fix_cast_person_id() {

    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $api  = fw_get_db_ext_settings_option( 'ht-movie', 'api-key', NULL );

    $query = new WP_Query([
        'post_type' => ['ht_movie', 'ht_show'],
        'posts_per_page' => 5, 
        'paged' => $page,
        'post_status' => 'publish'
    ]);

    $fixed = 0;
    $checked = 0;

    foreach ($query->posts as $post) {

        $post_id = $post->ID;
        $tmdb_id = get_post_meta($post_id, '_tmdb_id', true);

        if (!$tmdb_id) continue;

        $type = ($post->post_type === 'ht_show') ? 'tv' : 'movie';

        // 🔥 single API call
        $url = "https://api.themoviedb.org/3/$type/$tmdb_id?api_key=$api&append_to_response=credits";

        $res = wp_remote_get($url);

        if (is_wp_error($res)) continue;

        $data = json_decode(wp_remote_retrieve_body($res), true);

        if (empty($data['credits']['cast'])) continue;

        $existing_terms = wp_get_post_terms($post_id, 'mv_actor');

        foreach ($existing_terms as $term) {

            $term_id = $term->term_id;

            // ✅ skip if already has person_id
          if (get_term_meta($term_id, '_person_id', true)) {
                continue;
            }

            

            foreach ($data['credits']['cast'] as $cast) {

                // 🔥 smart matching
                similar_text(
                    strtolower($term->name),
                    strtolower($cast['name']),
                    $percent
                );

                if ($percent > 90) {
                  delete_term_meta($term_id, 'person_id'); // just in case  
                 update_term_meta($term_id, '_person_id', $cast['id']);


                    $fixed++;
                    break;
                }
            }

            $checked++;
        }
    }

    wp_send_json_success([
        'fixed' => $fixed,
        'checked' => $checked,
        'page' => $page,
        'has_more' => $query->max_num_pages > $page
    ]);
}
         public function ht_check_tmdb_duplicate() {

    $tmdb_id = intval($_REQUEST['id']);
    // $type = sanitize_text_field($_POST['type']);
    if (!$tmdb_id) {
        wp_send_json_error('Invalid ID');
    }

    global $wpdb;

    $post_id = $wpdb->get_var($wpdb->prepare("
        SELECT post_id FROM {$wpdb->postmeta}
        WHERE meta_key = '_tmdb_id'
        AND meta_value = %s
        LIMIT 1
    ", $tmdb_id));

    if ($post_id) {
        wp_send_json_success([
            'exists' => true,
            'post_id' => $post_id
        ]);
    } else {
        wp_send_json_success([
            'exists' => false
        ]);
    }
}

         public function bulk_import_tmdb_callback(){
             $id   = intval($_POST['id']);
             $type = sanitize_text_field($_POST['type']);
            $api  = fw_get_db_ext_settings_option( 'ht-movie', 'api-key', NULL );

         $endpoint = $type === 'tv'
            ? "https://api.themoviedb.org/3/tv/$id"
            : "https://api.themoviedb.org/3/movie/$id";
             $url = $endpoint . "?api_key=$api";
                $res = wp_remote_get($url);
           
                
                if (is_wp_error($res)) {
                    wp_send_json_error();
                }
                    $data = json_decode(wp_remote_retrieve_body($res), true);
                    $post_id = $this->ht_create_post_basic($data, $type);
                    $this->ht_apply_htmovie_logic($post_id,$data,$type,$api);
                    wp_send_json_success($post_id);

         }
public function ht_create_post_basic($data, $type) {

    $tmdb_id = $data['id'];

    $post_type = ($type === 'tv') ? 'ht_show' : 'ht_movie';

    // 🔥 CHECK EXISTING (VERY IMPORTANT)
    $existing = get_posts([
        'post_type'  => $post_type,
        'meta_key'   => '_tmdb_id',
        'meta_value' => $tmdb_id,
        'fields'     => 'ids',
        'posts_per_page' => 1
    ]);

    if (!empty($existing)) {
        return $existing[0]; // update mode
    }

    // CREATE NEW
    $title = ($type === 'tv') ? $data['name'] : $data['title'];

    $post_id = wp_insert_post([
        'post_title'  => $title,
        'post_status' => 'publish',
        'post_type'   => $post_type
    ]);

    // SAVE TMDB ID
    update_post_meta($post_id, '_tmdb_id', $tmdb_id);

    return $post_id;
}
public function ht_movie_add_keywords() {

    $input_terms = explode(',', $_POST['keywords']);
    $terms = [];

    foreach ($input_terms as $term) {

        $term = trim($term);

        $existing = term_exists($term, 'mv_keyword');

        if ($existing && isset($existing['term_id'])) {
            $term_id = $existing['term_id'];
        } else {
            // $new = wp_insert_term($term, 'mv_keyword');
            // if (!is_wp_error($new)) {
            //     $term_id = $new['term_id'];
            // }
        }

        if (!empty($term_id)) {
            $terms[] = (int) $term_id;
        }
    }

    wp_send_json([
        'result' => $terms
    ]);
}

public function ht_apply_htmovie_logic($post_id, $data, $type, $api_key) {
        fw_set_db_post_option($post_id, 'overview', $data['overview'] ?? '');
            if ($type === 'movie') {

        fw_set_db_post_option($post_id, 'tagline', $data['tagline'] ?? '');
        fw_set_db_post_option($post_id, 'release_date', $data['release_date'] ?? '');
        fw_set_db_post_option($post_id, 'runtime', isset($data['runtime']) ? $data['runtime'] . 'm' : '');

        if (!empty($data['spoken_languages'])) {
            $langs = array_column($data['spoken_languages'], 'name');
            fw_set_db_post_option($post_id, 'languages', implode(', ', $langs));
        }

        if (!empty($data['production_countries'])) {
            $countries = array_column($data['production_countries'], 'name');
            fw_set_db_post_option($post_id, 'country', implode(', ', $countries));
        }

        if (!empty($data['production_companies'])) {
            $companies = array_column($data['production_companies'], 'name');
            fw_set_db_post_option($post_id, 'production', implode(', ', $companies));
        }
    }
     if (!empty($data['genres'])) {
        $genre_ids = $this->ht_sync_genres($data['genres']);
        wp_set_object_terms($post_id, $genre_ids, 'mv_genre');
    }
        $this->ht_sync_credits_api($post_id, $data['id'], $type, $api_key);
    if (!empty($data['belongs_to_collection']['name'])) {
        $collection_id = $this->ht_sync_collection($data['belongs_to_collection']['name']);
        wp_set_object_terms($post_id, $collection_id, 'mv_collection');
    }
        $this->ht_sync_media($post_id, $data);
        $this->ht_sync_videos_api($post_id, $data['id'], $type, $api_key);
    if ($type === 'tv') {
                $this->ht_sync_seasons($post_id, $data);
    }
    $this->ht_sync_keywords_api($post_id, $data['id'], $type, $api_key);
    $this->ht_add_watch_providers($post_id, $data['id'], $type, $api_key);

   
}
public function ht_sync_credits_api($post_id, $tmdb_id, $type, $api_key) {

    $url = "https://api.themoviedb.org/3/$type/$tmdb_id/credits?api_key=$api_key";

    $res = wp_remote_get($url);
    if (is_wp_error($res)) return;

    $data = json_decode(wp_remote_retrieve_body($res), true);

    // CAST
    if (!empty($data['cast'])) {
        $actor_ids = $this->ht_sync_cast($data['cast'], $api_key);
        wp_set_object_terms($post_id, $actor_ids, 'mv_actor');
    }

    // DIRECTORS / WRITERS
    $directors = [];
    $writers = [];

    if (!empty($data['crew'])) {
        foreach ($data['crew'] as $crew) {

            if ($crew['job'] === 'Director') {
                $directors[] = $crew['name'];
            }

            if ($crew['department'] === 'Writing') {
                $writers[] = $crew['name'];
            }
        }
    }

    fw_set_db_post_option($post_id, 'directors', implode(', ', $directors));
    fw_set_db_post_option($post_id, 'writers', implode(', ', $writers));
}
public function ht_sync_videos_api($post_id, $tmdb_id, $type, $api_key) {

    $url = "https://api.themoviedb.org/3/$type/$tmdb_id/videos?api_key=$api_key";

    $res = wp_remote_get($url);
    if (is_wp_error($res)) return;

    $data = json_decode(wp_remote_retrieve_body($res), true);

    if (empty($data['results'])) return;

    $videos = [];

    foreach ($data['results'] as $v) {

         if ($v['site'] === 'YouTube') {
            $videos[] = $v['key'];
        }
    }

    fw_set_db_post_option($post_id, 'video', $videos);
   
}
public function ht_add_watch_providers($post_id, $tmdb_id, $type, $api) {

    $url = "https://api.themoviedb.org/3/$type/$tmdb_id/watch/providers?api_key=$api";

    $res = wp_remote_get($url);

    if (is_wp_error($res)) return;

    $data = json_decode(wp_remote_retrieve_body($res), true);

    if (empty($data['results'])) return;

    $countries = [
        'US' => 'us',
        'GB' => 'uk'
    ];

    foreach ($countries as $code => $slug) {

        if (empty($data['results'][$code])) continue;

        $countryData = $data['results'][$code];

        // 🔥 merge flatrate + buy
        $providers_raw = array_merge(
            $countryData['flatrate'] ?? [],
            $countryData['buy'] ?? []
        );

        if (empty($providers_raw)) continue;

        // =========================
        // REMOVE DUPLICATES
        // =========================
        $providers = [];
        $seen = [];

        foreach ($providers_raw as $p) {
            if (!in_array($p['provider_id'], $seen)) {
                $seen[] = $p['provider_id'];
                $providers[] = $p;
            }
        }

        // =========================
        // PARENT TERM (US / UK)
        // =========================
        $parent = term_exists($slug, 'networks');

        if (!$parent) {
            $parent = wp_insert_term(ucfirst($slug), 'networks', [
                'slug' => $slug
            ]);
        }

        $parent_id = is_array($parent) ? $parent['term_id'] : $parent;

        $provider_ids = [];

        foreach ($providers as $provider) {

            $name = $provider['provider_name'];

            // 🔥 parent-aware check (VERY IMPORTANT)
            $term = term_exists($name, 'networks', $parent_id);

            if (!$term) {
                $term = wp_insert_term($name, 'networks', [
                    'parent' => $parent_id
                ]);
            }

            $term_id = is_array($term) ? $term['term_id'] : $term;

            // =========================
            // SAVE LOGO
            // =========================
            if (!get_term_meta($term_id, 'logo', true) && !empty($provider['logo_path'])) {
                update_term_meta(
                    $term_id,
                    'logo',
                    'https://image.tmdb.org/t/p/w200' . $provider['logo_path']
                );
            }

            $provider_ids[] = intval($term_id);
        }

        // =========================
        // ASSIGN TERMS
        // =========================
        if (!empty($provider_ids)) {
            wp_set_post_terms($post_id, $provider_ids, 'networks', true);
        }
    }
}
public function ht_sync_keywords($post_id, $data, $type) {

    if ($type === 'tv') {
        $keywords = $data['keywords']['results'] ?? [];
    } else {
        $keywords = $data['keywords']['keywords'] ?? [];
    }

    if (empty($keywords)) return;

    $terms = [];

    foreach ($keywords as $k) {

        $name = $k['name'];

        $term = term_exists($name, 'mv_keyword');

        // if (!$term) {
        //     $term = wp_insert_term($name, 'mv_keyword');
        // }

        if (!is_wp_error($term)) {
            $terms[] = (int)$term['term_id'];
        }
    }

    // assign to post
    wp_set_object_terms($post_id, $terms, 'mv_keyword', false);
}
public function ht_sync_keywords_api($post_id, $tmdb_id, $type, $api_key) {

    $url = "https://api.themoviedb.org/3/$type/$tmdb_id/keywords?api_key=$api_key";

    $res = wp_remote_get($url);
    if (is_wp_error($res)) return;

    $data = json_decode(wp_remote_retrieve_body($res), true);
    $keywords = ($type === 'movie')
        ? ($data['keywords'] ?? [])
        : ($data['results'] ?? []);

    if (empty($keywords)) return;

    $term_ids = [];

    foreach ($keywords as $k) {

        $term = term_exists($k['name'], 'mv_keyword');

        if (!is_wp_error($term)) {
            $term_ids[] = (int)$term['term_id'];
        }

    }

    wp_set_object_terms($post_id, $term_ids, 'mv_keyword');
}
public function ht_sync_seasons($post_id, $data) {

    if (empty($data['seasons'])) return;

    $seasons = [];

    foreach ($data['seasons'] as $season) {

        // skip specials (optional)
        if ($season['season_number'] == 0) continue;

        $seasons[] = [
            'air_date'       => $season['air_date'] ?? '',
            'overview'       => $season['overview'] ?? '',
            'season_number'  => $season['season_number'],
            'episode_count'  => $season['episode_count'],
            'poster_path'    => !empty($season['poster_path'])
                ? 'https://image.tmdb.org/t/p/w154' . $season['poster_path']
                : ''
        ];
    }

    fw_set_db_post_option($post_id, 'seasons', $seasons);
}
public function ht_sync_genres($genres) {

    $ids = [];

    foreach ($genres as $g) {

        $term = term_exists($g['name'], 'mv_genre');

        if (!$term) {
            $term = wp_insert_term($g['name'], 'mv_genre');
        }

        $ids[] = (int)$term['term_id'];
    }

    return $ids;
}
public function ht_sync_cast($casts, $api_key = null) {

    $ids = [];

    foreach (array_slice($casts, 0, 10) as $cast) {

        $term = term_exists($cast['name'], 'mv_actor');

        if (!$term) {

            $term = wp_insert_term($cast['name'], 'mv_actor');

            if (!is_wp_error($term)) {

                $term_id = $term['term_id'];

                // avatar
                if ($cast['profile_path']) {
                    fw_set_db_term_option(
                        $term_id,
                        'mv_actor',
                        'avatar_url',
                        'https://image.tmdb.org/t/p/w300_and_h450_bestv2' . $cast['profile_path']
                    );
                }
                wp_update_term($term_id, 'mv_actor', array(
                    'description' =>  $person['biography'] ?? ''
                ));

                // gender
                fw_set_db_term_option(
                    $term_id,
                    'mv_actor',
                    'gender',
                    $cast['gender'] == 1 ? 'Female' : 'Male'
                );

                // 🚀 OPTIONAL (disable for speed)
            
                if ($api_key) {
                    $person = wp_remote_get("https://api.themoviedb.org/3/person/{$cast['id']}?api_key=$api_key&language=en-US&append_to_response=external_ids");
                    $person = json_decode(wp_remote_retrieve_body($person), true);

                    fw_set_db_term_option($term_id, 'mv_actor', 'biography', $person['biography']);
                    fw_set_db_term_option(
						$term_id,
						'mv_actor',
						'dateofbirth',
						$person['birthday']
					);
                    	fw_set_db_term_option(
						$term_id,
						'mv_actor',
						'knowfor',
						$person['known_for_department']
					);
                    	fw_set_db_term_option(
						$term_id,
						'mv_actor',
						'country',
						$person['place_of_birth']
					);
                    if( isset($person['external_ids']['facebook_id']) && !empty($person['external_ids']['facebook_id']) ) {
						fw_set_db_term_option(
							$term_id,
							'mv_actor',
							'facebook_link',
							'https://www.facebook.com/' . $person['external_ids']['facebook_id']
						);
					}
					if( isset($person['external_ids']['twitter_id']) && !empty($person['external_ids']['twitter_id']) ) {
						fw_set_db_term_option(
							$term_id,
							'mv_actor',
							'twitter_link',
							'https://www.twitter.com/' . $person['external_ids']['twitter_id']
						);
					}
					if( isset($person['external_ids']['instagram_id']) && !empty($person['external_ids']['instagram_id']) ) {
						fw_set_db_term_option(
							$term_id,
							'mv_actor',
							'instagram_link',
							'https://www.instagram.com/' . $person['external_ids']['instagram_id']
						);
					}

                }
                
            }
        }

        $ids[] = (int)$term['term_id'];
    }

    return $ids;
}
public function ht_sync_collection($name) {

    $term = term_exists($name, 'mv_collection');

    if (!$term) {
        $term = wp_insert_term($name, 'mv_collection');
    }

    return (int)$term['term_id'];
}
/**
 * =====================================================
 * DELETE ALL ATTACHED IMAGES BEFORE RE-SYNC
 * =====================================================
 */

public function ht_delete_post_attachments($post_id){

    // =========================================
    // GET ATTACHMENTS
    // =========================================

    $attachments = get_children([

        'post_parent'    => $post_id,

        'post_type'      => 'attachment',

        'post_mime_type' => 'image',

        'posts_per_page' => -1,

        'post_status'    => 'inherit'
    ]);

    if (empty($attachments)) {
        return;
    }

    // =========================================
    // DELETE ATTACHMENTS
    // =========================================

    foreach ($attachments as $attachment) {

        wp_delete_attachment(
            $attachment->ID,
            true
        );
    }
}
public function ht_sync_media($post_id, $data) {
    $this->ht_delete_post_attachments($post_id);
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Poster
    if (!empty($data['poster_path'])) {
        if ( has_post_thumbnail( $post_id ) ) {
        // 2. Get the old attachment ID
        $old_attachment_id = get_post_thumbnail_id( $post_id );
        // 3. Delete the old attachment (pass true to force deletion, not just trash)
        wp_delete_attachment( $old_attachment_id, true );
    }

        $img = 'https://image.tmdb.org/t/p/w780' . $data['poster_path'];
        $id = media_sideload_image($img, $post_id, null, 'id');

        if (!is_wp_error($id)) {
            set_post_thumbnail($post_id, $id);
          // $this->update_and_delete_old_thumbnail($post_id, $id);
        }
    }

    // Banner
    if (!empty($data['backdrop_path'])) {
             $existing = fw_get_db_post_option($post_id, 'banner');
            if ($existing && isset($existing['attachment_id'])) {
                wp_delete_attachment($existing['attachment_id'], true);
            }
        $img = 'https://image.tmdb.org/t/p/w1280' . $data['backdrop_path'];
        $id = media_sideload_image($img, $post_id, null, 'id');

        if (!is_wp_error($id)) {
           
            fw_set_db_post_option($post_id, 'banner', [
                'attachment_id' => $id,
                'url' => wp_get_attachment_url($id)
            ]);
        }
    }
}
public function update_and_delete_old_thumbnail( $post_id, $new_attachment_id ) {
    // 1. Check if a featured image already exists
    if ( has_post_thumbnail( $post_id ) ) {
        // 2. Get the old attachment ID
        $old_attachment_id = get_post_thumbnail_id( $post_id );
        // 3. Delete the old attachment (pass true to force deletion, not just trash)
        wp_delete_attachment( $old_attachment_id, true );
    }

    // 4. Set the new featured image
    set_post_thumbnail( $post_id, $new_attachment_id );
}

public function ht_sync_videos($post_id, $data) {

    if (empty($data['videos']['results'])) return;

    $videos = [];

    foreach ($data['videos']['results'] as $v) {
        if ($v['site'] === 'YouTube') {
            $videos[] = $v['key'];
        }
    }

    fw_set_db_post_option($post_id, 'video', $videos);
}
public function sync_tmdb_ids_callback() {

    $type = $_POST['type']; // movie / tv
    $page = intval($_POST['page']);

    $post_type = ($type === 'movie') ? 'ht_movie' : 'ht_show';

    $query = new WP_Query([
        'post_type' => $post_type,
        'posts_per_page' => 10, // batch
        'paged' => $page,
        'meta_query' => [
            [
                'key' => '_tmdb_id',
                'compare' => 'NOT EXISTS'
            ]
        ]
    ]);

    $apiKey = fw_get_db_ext_settings_option( 'ht-movie', 'api-key', NULL );
    $updated = [];

    foreach ($query->posts as $post) {

        $title = $post->post_title;

        $release_date = function_exists('fw_get_db_post_option') 
            ? fw_get_db_post_option($post->ID, 'release_date') 
            : '';

        $year = $release_date ? date('Y', strtotime($release_date)) : '';

        // 🔥 TMDB search
        $url = "https://api.themoviedb.org/3/search/{$type}?api_key={$apiKey}&query=" . urlencode($title);

        $response = wp_remote_get($url);

        if (is_wp_error($response)) continue;

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (empty($body['results'])) continue;

        foreach ($body['results'] as $result) {

            $tmdb_year = isset($result['release_date']) 
                ? date('Y', strtotime($result['release_date'])) 
                : (isset($result['first_air_date']) ? date('Y', strtotime($result['first_air_date'])) : '');

            if ($year && $tmdb_year && $year != $tmdb_year) {
                continue;
            }
 // ✅ Match found
            update_post_meta($post->ID, '_tmdb_id', $result['id']);
            $updated[] = $title;
            break;
        }
    }

    wp_send_json_success([
        'updated' => $updated,
        'found' => count($updated),
        'has_more' => $query->max_num_pages > $page
    ]);
}
         public function process_trending_fetch_request(){
            // $token  = new ApiToken(fw_get_db_ext_settings_option( 'ht-movie', 'api-key', NULL )); // preferred
            $api_key=fw_get_db_ext_settings_option( 'ht-movie', 'api-key', NULL );
                   $type = sanitize_text_field($_POST['type']);
                    $time = sanitize_text_field($_POST['time']);
                    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;

                    $url = "https://api.themoviedb.org/3/trending/{$type}/{$time}?api_key={$api_key}&page={$page}";

                
                    $response = wp_remote_get($url);

                    if (is_wp_error($response)) {
                       wp_send_json_error();
                    }
                      //  print_r(json_decode(wp_remote_retrieve_body($response), true));

                      $body = json_decode(wp_remote_retrieve_body($response), true);

                        $results = [];


                            if (!empty($body['results'])) {
                                foreach ($body['results'] as $item) {

                                    $results[] = [
                                        'id' => $item['id'],
                                        'poster' => 'https://image.tmdb.org/t/p/w200' . $item['poster_path'],
                                        'title' => $item['title'] ?? $item['name'],
                                        'overview' => $item['overview'],
                                        'release_date' => $item['release_date'] ?? $item['first_air_date'],
                                        'imported'=>$this->is_already_imported($item['id'],$type)
                                    ];
                                }
                                

                            }
                                wp_send_json_success([
                                    'results' => $results,
                                    'total_results' => $body['total_results']
                                    ]);

         }
      function is_already_imported($tmdb_id, $type) {

    if (empty($tmdb_id)) {
        return false;
    }

    // 🔹 Determine post type
    $post_type = ($type === 'movie') ? 'ht_movie' : 'ht_show';

    // 🔹 Query by TMDB ID
    $query = new WP_Query([
        'post_type'      => $post_type,
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'meta_query'     => [
            [
                'key'   => '_tmdb_id',
                'value' => $tmdb_id,
                'compare' => '='
            ]
        ],
        'fields' => 'ids'
    ]);

    return !empty($query->posts);
}

public function ht_tmdb_manual_sync() {

    $post_id = intval($_POST['post_id']);

    if (!$post_id) {
        wp_send_json_error('Invalid post');
    }

    $post_type = get_post_type($post_id);

    $type = ($post_type === 'ht_show') ? 'tv' : 'movie';

    $tmdb_id = get_post_meta($post_id, '_tmdb_id', true);

    if (!$tmdb_id) {
        wp_send_json_error('Missing TMDB ID');
    }

    // 🔥 CALL YOUR EXISTING UPDATE FUNCTION

    $cronMain=new HT_Movie_Extended_Admin_Cron();
    $cronMain->ht_tmdb_update_post($post_id, $tmdb_id, $type);

    wp_send_json_success('Updated');
}
    public function ht_testing_watch_callback(){
      //  wp_die('Testing watch callback works!');
        // Just a placeholder for testing
//          $api = fw_get_db_ext_settings_option( 'ht-movie', 'api-key', NULL );
//     $movie_id=687163;
//   //  $endpoint="https://api.themoviedb.org/3/movie/{$movie_id}/watch/providers";
//     $endpoint="https://api.themoviedb.org/3/movie/{$movie_id}/release_dates";

//     $res = wp_remote_get($endpoint . "?watch_region=GB&api_key=$api");
//     if (is_wp_error($res)) {
//         wp_send_json_error();
//     }
//     $data = json_decode(wp_remote_retrieve_body($res), true);
//         wp_send_json_success(['message' => $endpoint, 'data' => $data]);   
        // $args=array(
        //     'paged'=>1,
        //     'post_type'=>'ht_movie',
        //     'post_status'=>'publish',
        //     'posts_per_page'=>4,
        //     'ignore_sticky_posts'=>1,
        //     'tax_query'=>array(
        //         array(
        //             'field'=>'slug',
        //             'include_children'=>true,
        //             'operator'=>'IN',
        //             'taxonomy'=>'mv_trending',
        //             'terms'=>array('movie-week')
        //         )
        //     ),
        //     'meta_key'=>'_trending_movie_week',
        //     'orderby'=>'meta_value_num',
        //     'order'=>'ASC',
        //     'meta_query'=>array(
        //         array(
        //             'key'=>'_trending_movie_week',
        //             'compare'=>'EXISTS'
        //         )
        //     )
        // );
        //    $args=array(
        //     'paged'=>1,
        //     'post_type'=>'ht_show',
        //     'post_status'=>'publish',
        //     'posts_per_page'=>4,
        //     'ignore_sticky_posts'=>1,
        //     'tax_query'=>array(
        //         array(
        //             'field'=>'slug',
        //             'include_children'=>true,
        //             'operator'=>'IN',
        //             'taxonomy'=>'mv_trending',
        //             'terms'=>array('tv-day')
        //         )
        //     ),
        //     'meta_key'=>'_trending_tv_day',
        //     'orderby'=>'meta_value_num',
        //     'order'=>'ASC',
        //     'meta_query'=>array(
        //         array(
        //             'key'=>'_trending_tv_day',
        //             'compare'=>'EXISTS'
        //         )
        //     )
        // );
        // $query=new WP_Query($args);
        // $results=[];
        // if($query->have_posts()){
        //     while($query->have_posts()){
        //         $query->the_post();
        //         $results[]['id']=get_the_ID();
        //         $results[]['title']=get_the_title(); 
        //         $results[]['trending']=get_post_meta(get_the_ID(), '_trending_movie_week');
            

        //     }
        // }
        $postID=23822;
        $tmdb_id=287011;//get_post_meta($postID,'_tmdb_id',true);
        $api_key=fw_get_db_ext_settings_option( 'ht-movie', 'api-key', NULL );
        $type='tv';
        $endpoint = $type === 'tv'
            ? "https://api.themoviedb.org/3/tv/$tmdb_id"
            : "https://api.themoviedb.org/3/movie/$tmdb_id";
             $url = $endpoint . "?api_key=$api_key&append_to_response=credits,videos,keywords";
        $res = wp_remote_get($url);
        if (is_wp_error($res)) {
            wp_send_json_error();
        }
            $data = json_decode(wp_remote_retrieve_body($res), true);
           // $this->ht_apply_htmovie_logic($postID,$data,$type,$api_key);
        wp_send_json_success($data);
    }   
    public function ht_sync_all_media() {

    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;

    $query = new WP_Query([
        'post_type' => ['ht_movie', 'ht_show'],
        'posts_per_page' => 20,
        'paged' => $paged,
        'post_status' => 'publish'
    ]);

    $updated = 0;

    foreach ($query->posts as $post) {

        $post_id = $post->ID;

        // =========================
        // 1. FEATURED IMAGE
        // =========================
        $thumb_id = get_post_thumbnail_id($post_id);

        if ($thumb_id && get_post_field('post_parent', $thumb_id) != $post_id) {
            wp_update_post([
                'ID' => $thumb_id,
                'post_parent' => $post_id
            ]);
            $updated++;
        }

        // =========================
        // 2. BANNER
        // =========================
        $banner = fw_get_db_post_option($post_id, 'banner');

        if (!empty($banner['attachment_id'])) {
            $att_id = $banner['attachment_id'];

            if (get_post_field('post_parent', $att_id) != $post_id) {
                wp_update_post([
                    'ID' => $att_id,
                    'post_parent' => $post_id
                ]);
                $updated++;
            }
        }

        // =========================
        // 3. GALLERY
        // =========================
        $gallery = fw_get_db_post_option($post_id, 'gallery');

        if (!empty($gallery)) {
            foreach ($gallery as $img) {

                if (!empty($img['attachment_id'])) {

                    $att_id = $img['attachment_id'];

                    if (get_post_field('post_parent', $att_id) != $post_id) {
                        wp_update_post([
                            'ID' => $att_id,
                            'post_parent' => $post_id
                        ]);
                        $updated++;
                    }
                }
            }
        }

        // =========================
        // 4. SEASONS (TV ONLY)
        // =========================
        $seasons = fw_get_db_post_option($post_id, 'seasons');

        if (!empty($seasons)) {
            foreach ($seasons as $season) {

                if (!empty($season['poster_id'])) {

                    $att_id = $season['poster_id'];

                    if (get_post_field('post_parent', $att_id) != $post_id) {
                        wp_update_post([
                            'ID' => $att_id,
                            'post_parent' => $post_id
                        ]);
                        $updated++;
                    }
                }
            }
        }

        // =========================
        // 5. CAST (TERM META)
        // =========================
        $actors = wp_get_post_terms($post_id, 'mv_actor');

        if (!empty($actors)) {
            foreach ($actors as $actor) {

                $avatar = fw_get_db_term_option($actor->term_id, 'mv_actor', 'avatar_id');

                if ($avatar) {

                    if (get_post_field('post_parent', $avatar) != $post_id) {
                        wp_update_post([
                            'ID' => $avatar,
                            'post_parent' => $post_id
                        ]);
                        $updated++;
                    }
                }
            }
        }
    }

    wp_send_json_success([
        'updated' => $updated,
        'page' => $paged,
        'has_more' => $query->max_num_pages > $paged
    ]);
}
}
    new  HT_Movie_Extended_Admin_Ajax();
}