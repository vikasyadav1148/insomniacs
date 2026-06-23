<?php 
defined( 'ABSPATH' ) or exit;

// if ( ! defined( 'FW' ) ) {
// 	die( 'Forbidden' );
// }
use GeoIp2\Database\Reader;

if(!class_exists('HT_Movie_Extended_Admin')) {
    class HT_Movie_Extended_Admin{
         public function __construct() {
             require_once HTE_PLUGIN_ADMIN_DIR . '/class-admin-ajax.php';
             require_once HTE_PLUGIN_ADMIN_DIR . '/class-admin-cron.php';
            add_action('admin_menu',[&$this, 'plugin_setup_menu']);
            add_action('wp_ajax_ht_movie_fetch_treding',[&$this,'fetch_trending']);
            add_action('post_submitbox_misc_actions', [&$this, 'ht_tmdb_sync_button']);

          //  add_action('pre_get_posts', [&$this,'ht_trending_archive_order']);


            add_action('rest_after_insert_ht_movie', function ($post, $request) {
                    if (isset($request['tmdb_id'])) {
                        update_post_meta($post->ID, '_tmdb_id', intval($request['tmdb_id']));
                    }
            }, 10, 2);

            add_action('rest_after_insert_ht_show', function ($post, $request) {
            if (isset($request['tmdb_id'])) {
                update_post_meta($post->ID, '_tmdb_id', intval($request['tmdb_id']));
            }
        }, 10, 2);
      
              add_action('init',[&$this,'register_taxonmoies']);
              add_action('init',[&$this,'ht_create_trending_terms']);
              add_shortcode('ht_movie_providers', [&$this, 'ht_movie_providers_shortcode']);
              add_filter('rest_pre_insert_ht_movie', [&$this, 'ht_prevent_duplicate_tmdb'], 10, 2);
              add_filter('rest_pre_insert_ht_show', [&$this, 'ht_prevent_duplicate_tmdb'], 10, 2);

              add_action('rest_after_insert_ht_movie', [&$this, 'ht_attach_media_after_rest'], 20, 3);
             add_action('rest_after_insert_ht_show', [&$this, 'ht_attach_media_after_rest'], 20, 3);

            add_action('mv_actor_edit_form_fields', [&$this, 'ht_actor_sync_button']);



         }
      

public function ht_actor_sync_button($term) {

    $person_id = get_term_meta($term->term_id, '_person_id', true);
    ?>

    <tr class="form-field">
        <th scope="row">TMDB Sync</th>
        <td>
            <?php if ($person_id): ?>
                <button type="button" class="button button-primary" id="syncActorBtn"
                        data-term="<?php echo esc_attr($term->term_id); ?>">
                    Sync from TMDB
                </button>

                <span id="syncActorStatus" style="margin-left:10px;"></span>
            <?php else: ?>
                <span style="color:red;">No person_id found</span>
            <?php endif; ?>
        </td>
    </tr>
                <script>
                    jQuery(document).ready(function ($) {

    $('#syncActorBtn').on('click', function () {

        let btn = $(this);
        let term_id = btn.data('term');

        btn.prop('disabled', true);
        $('#syncActorStatus').text('Syncing...');

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'ht_sync_single_actor',
                term_id: term_id
            },
            success: function (res) {

                if (res.success) {
                    $('#syncActorStatus').html('<span style="color:green;">✔ Synced</span>');
                } else {
                    $('#syncActorStatus').html('<span style="color:red;">Failed</span>');
                }

                btn.prop('disabled', false);
            },
            error: function () {
                $('#syncActorStatus').html('<span style="color:red;">Error</span>');
                btn.prop('disabled', false);
            }
        });
    });

});
                    </script>
    <?php
}
            public function ht_attach_media_after_rest($post, $request, $creating) {
                 $post_id = $post->ID;

                // avoid duplicate scheduling
                // if (get_post_meta($post_id, '_media_attach_scheduled', true)) {
                //     return;
                // }

                // 🔥 SCHEDULE DELAYED ATTACH
             //   wc_get_logger()->info('Fired for : '.$post_id);
             //   wp_schedule_single_event(time() + 10, 'ht_attach_media_delayed', [$post_id]);

                // mark scheduled
                $cronClass= new HT_Movie_Extended_Admin_Cron();
                $cronClass->ht_attach_media_runner($post_id);
               // update_post_meta($post_id, '_media_attach_scheduled', 1);
            }
            public function ht_prevent_duplicate_tmdb($prepared_post, $request) {
                   
                     $tmdb_id = $request->get_param('tmdb_id');
                    
                     if (!$tmdb_id) return $prepared_post;
                      global $wpdb;
                       $existing = $wpdb->get_var($wpdb->prepare("
                        SELECT post_id FROM {$wpdb->postmeta}
                        WHERE meta_key = '_tmdb_id'
                        AND meta_value = %s
                        LIMIT 1
                    ", $tmdb_id));
                  
                    if ($existing) {
                     //  wc_get_logger('hte')->warning("Duplicate TMDB ID detected: $tmdb_id already exists in post ID $existing");
                        return new WP_Error('duplicate_tmdb_id', 'A movie/show with this TMDB ID already exists.', ['status' => 400]);
                    }
                    return $prepared_post;

            }
         public function ht_get_user_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    }
    return $_SERVER['REMOTE_ADDR'];
}
        public  function ht_get_country_code() {

    // cache per user (1 day)
    if (isset($_COOKIE['ht_country'])) {
        return sanitize_text_field($_COOKIE['ht_country']);
    }

    $ip = $this->ht_get_user_ip();

    $res = wp_remote_get("https://ipapi.co/$ip/json/");

    if (is_wp_error($res)) return 'GB';

    $data = json_decode(wp_remote_retrieve_body($res), true);

    $country = $data['country'] ?? 'GB';

    // cache in cookie
    setcookie('ht_country', $country, time() + 86400, '/');

    return $country;
}
        public function ht_tmdb_sync_button($post) {
                if (!in_array($post->post_type, ['ht_movie', 'ht_show'])) return;
                ?>
                 <div class="misc-pub-section">
                    <button data-id="<?php echo $post->ID; ?>" type="button" class="button button-primary" id="ht-sync-tmdb">
                        🔄 Sync from TMDB
                    </button>
                        <span id="ht-sync-status" style="margin-left:10px;"></span>
                    </div>
                     <script>
    jQuery(document).ready(function($){

        $('#ht-sync-tmdb').on('click', function(){

            let btn = $(this);
            let status = $('#ht-sync-status');

            btn.prop('disabled', true).text('Syncing...');

            $.post(ajaxurl, {
                action: 'ht_tmdb_manual_sync',
                post_id: <?php echo $post->ID; ?>
            }, function(res){

                if(res.success){
                    status.html('✅ Synced');
                } else {
                    status.html('❌ Failed');
                }

                btn.prop('disabled', false).text('🔄 Sync from TMDB');

            });

        });

    });
    </script>
                <?php


        }
        public function ht_movie_providers_shortcode($atts){
            $atts = shortcode_atts(array('id' => '','type' => 'movie'), $atts);
    
            $postid = intval($atts['id']);
            $type = sanitize_text_field($atts['type']);
            $terms = get_the_terms($postid, 'networks');
                $providers = [
                    'us' => [],
                    'uk' => []
                ];
                foreach ($terms as $term) {

                    if ($term->parent) {

                        $parent = get_term($term->parent, 'networks');

                        if ($parent && in_array($parent->slug, ['us', 'uk'])) {
                            $providers[$parent->slug][] = $term;
                        }
                    }
                }
         
                    $html = '';
                    $style="style='width:30px;height:30px;margin-right:5px;vertical-align:middle;display:inline-block;'";
                    $svgUK= '<svg '.$style.' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="256" height="256" viewBox="0 0 256 256" xml:space="preserve">
<g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
	<path d="M 88.35 57.052 c 0.034 -0.123 0.076 -0.243 0.109 -0.367 l -0.004 -0.002 C 89.457 52.957 90 49.043 90 45 c 0 -4.033 -0.54 -7.938 -1.538 -11.657 l 0.004 -0.002 c -0.039 -0.146 -0.088 -0.289 -0.128 -0.434 c -0.137 -0.492 -0.28 -0.982 -0.434 -1.468 c -0.081 -0.257 -0.167 -0.512 -0.253 -0.768 c -0.073 -0.217 -0.139 -0.437 -0.215 -0.653 h -0.015 c -1.645 -4.653 -4.021 -8.96 -7.01 -12.768 L 59.997 27.458 V 2.57 c -4.368 -1.544 -9.046 -2.427 -13.915 -2.542 h -2.164 c -4.868 0.115 -9.545 0.998 -13.913 2.541 v 24.889 L 9.589 17.249 c -2.989 3.809 -5.366 8.116 -7.01 12.769 H 2.564 c -0.076 0.216 -0.143 0.436 -0.216 0.653 c -0.086 0.255 -0.172 0.509 -0.253 0.765 c -0.154 0.486 -0.297 0.977 -0.434 1.47 c -0.04 0.145 -0.089 0.287 -0.128 0.432 l 0.004 0.002 C 0.54 37.061 0 40.966 0 45 c 0 4.043 0.543 7.957 1.545 11.684 l -0.004 0.002 c 0.033 0.123 0.074 0.242 0.108 0.365 c 0.146 0.524 0.298 1.046 0.462 1.562 c 0.075 0.236 0.154 0.47 0.233 0.705 c 0.077 0.231 0.148 0.464 0.229 0.693 H 2.59 c 1.647 4.651 4.025 8.955 7.016 12.761 l 20.4 -10.2 v 24.86 C 34.697 89.089 39.741 90 45 90 c 5.26 0 10.305 -0.911 14.997 -2.57 V 62.572 l 20.398 10.199 c 2.991 -3.806 5.368 -8.11 7.015 -12.76 h 0.015 c 0.081 -0.229 0.152 -0.463 0.23 -0.694 c 0.079 -0.234 0.158 -0.468 0.233 -0.704 C 88.052 58.096 88.205 57.575 88.35 57.052 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(243,244,245); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 53.999 0.902 c -2.565 -0.521 -5.213 -0.81 -7.917 -0.874 h -2.164 c -2.703 0.064 -5.35 0.354 -7.914 0.874 v 35.116 H 0.899 C 0.311 38.92 0 41.924 0 45 c 0 3.087 0.312 6.1 0.904 9.012 h 35.1 v 35.087 C 38.911 89.689 41.919 90 45 90 c 3.082 0 6.091 -0.311 8.999 -0.902 V 54.012 h 35.097 C 89.688 51.1 90 48.087 90 45 c 0 -3.076 -0.311 -6.08 -0.899 -8.983 H 53.999 V 0.902 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(204,0,0); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 85.242 65.135 c 0.829 -1.653 1.56 -3.363 2.184 -5.125 H 74.993 L 85.242 65.135 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(204,0,0); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 82.216 19.701 L 61.581 30.019 h 13.412 l 10.261 -5.131 C 84.353 23.088 83.341 21.354 82.216 19.701 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(204,0,0); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 4.747 24.887 c -0.829 1.655 -1.559 3.368 -2.182 5.132 H 15.01 L 4.747 24.887 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(204,0,0); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 7.8 70.321 L 28.422 60.01 H 15.01 L 4.758 65.136 C 5.661 66.936 6.674 68.67 7.8 70.321 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(204,0,0); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 9.605 72.771" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,102); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 80.412 17.251" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,102); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 80.395 72.77" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,102); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 9.589 17.25" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,102); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 9.589 17.249 l 20.416 10.208 v -3.99 V 2.584 C 21.874 5.458 14.813 10.593 9.589 17.249 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,102); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 59.997 2.585 v 22.302 v 2.57 L 80.411 17.25 C 75.188 10.594 68.128 5.459 59.997 2.585 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,102); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 30.006 72.77 V 62.572 l -20.4 10.2 c 5.222 6.646 12.276 11.774 20.4 14.646 V 72.77 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,102); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 59.997 62.572 v 9.296 v 15.548 c 8.123 -2.872 15.176 -8 20.398 -14.646 L 59.997 62.572 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,102); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
</g>
</svg>';
                    $svgUS= '<svg '.$style.' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="256" height="256" viewBox="0 0 256 256" xml:space="preserve">
<g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
	<path d="M 0.511 51.794 c 0.924 6.099 3.076 11.793 6.19 16.83 h 76.599 c 3.114 -5.037 5.266 -10.731 6.19 -16.83 H 0.511 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(243,244,245); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 69.638 82.649 H 20.362 C 27.441 87.291 35.902 90 45 90 S 62.559 87.291 69.638 82.649 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(243,244,245); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 6.701 68.624 c 3.472 5.617 8.146 10.408 13.661 14.025 h 49.276 c 5.516 -3.617 10.189 -8.408 13.661 -14.025 H 6.701 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(220,48,39); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 49.629 0.236 C 48.107 0.08 46.563 0 45 0 C 20.147 0 0 20.147 0 45 c 0 2.309 0.175 4.578 0.511 6.794 h 49.118 V 0.236 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(40,57,145); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 13.688 23.094 l -1.392 -4.274 l 3.625 -2.635 h -4.483 l -0.273 -0.837 c -1.422 1.621 -2.728 3.344 -3.908 5.158 l -0.843 2.588 l 3.636 -2.643 L 13.688 23.094 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(243,244,245); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 22.743 9.658 l 3.62 2.631 l -1.388 -4.262 l 3.621 -2.646 h -4.483 l -0.066 -0.204 c -1.414 0.746 -2.787 1.558 -4.107 2.444 l 0.568 0.413 l -1.366 4.255 L 22.743 9.658 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(243,244,245); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<polygon points="7.81,40.43 6.42,44.7 10.05,42.06 13.69,44.7 12.3,40.43 15.92,37.79 11.44,37.79 10.05,33.53 8.66,37.79 4.18,37.79 " style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(243,244,245); fill-rule: nonzero; opacity: 1;" transform="  matrix(1 0 0 1 0 0) "/>
	<polygon points="33.21,40.43 31.82,44.7 35.45,42.06 39.09,44.7 37.7,40.43 41.32,37.79 36.84,37.79 35.45,33.53 34.07,37.79 29.58,37.79 " style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(243,244,245); fill-rule: nonzero; opacity: 1;" transform="  matrix(1 0 0 1 0 0) "/>
	<polygon points="33.21,18.82 31.82,23.09 35.45,20.45 39.09,23.09 37.7,18.82 41.32,16.18 36.84,16.18 35.45,11.93 34.07,16.18 29.58,16.18 " style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(243,244,245); fill-rule: nonzero; opacity: 1;" transform="  matrix(1 0 0 1 0 0) "/>
	<polygon points="20.48,29.62 19.09,33.9 22.73,31.26 26.36,33.9 24.97,29.62 28.6,26.99 24.11,26.99 22.73,22.73 21.34,26.99 16.86,26.99 " style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(243,244,245); fill-rule: nonzero; opacity: 1;" transform="  matrix(1 0 0 1 0 0) "/>
	<path d="M 89.414 37.768 H 49.629 v 14.025 h 39.86 C 89.825 49.578 90 47.309 90 45 C 90 42.537 89.794 40.124 89.414 37.768 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(220,48,39); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 49.629 37.768 h 39.785 c -0.988 -6.111 -3.209 -11.806 -6.395 -16.83 H 49.629 V 37.768 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(243,244,245); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 49.629 20.938 h 33.389 c -3.587 -5.656 -8.397 -10.454 -14.064 -14.025 H 49.629 V 20.938 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(220,48,39); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
	<path d="M 49.629 0.24 v 6.673 h 19.326 C 63.248 3.316 56.681 0.963 49.629 0.24 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(243,244,245); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
</g>
</svg>';
                    if (!empty($providers['uk'])) {
                        $combined_providers_uk = $providers['uk'];
                        $html .= '<h4>' . $svgUK . 'Watch in the UK</h4>';
                        if (!empty($combined_providers_uk)) {
                            $html .= '<div class="streaming-block"><ul class="streaming-group">' . $this->ht_generate_provider_list($combined_providers_uk) . '</ul></div>';
                        } else {
                            $html .= '<div class="streaming-block"><ul class="streaming-group"><li>- Not Available to Stream Right Now, Check Back Soon.</li></ul></div>';
                        }
                      
                    }
                    if (!empty($providers['us'])) {
                        $html .= '<h4>' . $svgUS . 'Watch in the US</h4>';
                        $combined_providers_us = $providers['us'];
                        if (!empty($combined_providers_us)) {
                            $html .= '<div class="streaming-block"><ul class="streaming-group">' . $this->ht_generate_provider_list($combined_providers_us) . '</ul></div>';
                        } else {
                            $html .= '<div class="streaming-block"><ul class="streaming-group"><li>- Not Available to Stream Right Now, Check Back Soon.</li></ul></div>';
                        }
                      
                    }
                    if (empty($html)) {
                        $html = '<p>No provider information available for your region. Please check back soon.</p>';
                    } else {
                        $html .= '<p>Data provided by <a href="https://www.justwatch.com" target="_blank"><img src="https://insomniacs.party/wp-content/uploads/2025/01/IMG-20250116-WA0001-removebg-preview.png" alt="Just Watch" style="width:100px;height:auto;"></a></p>';
                    }
                    $provider_list = $html;

             $noProvier=false;
             if(empty($providers['uk']) && empty($providers['us'])) {
                    $noProvier=true;
             }
             
             if($noProvier) {

                $isCurrentlyPlaying = $this->ht_is_now_playing($postid, $type);
               // print_r($);
                if ($isCurrentlyPlaying) {
                    return '<p><a href="https://insomniacs.party/collection/in_theatres/" target="_blank"><img width="200px" style="width: 200px;" src="' . HTE_PLUGIN_ABSOLUTE_PATH . 'admin/assets/img/now-playing.png" alt="Now Playing"></a></p>';
                }
             }
            return $provider_list;

        }
       public  function ht_is_now_playing($tmdb_id, $type = 'movie') {

    $country = 'US';//$this->ht_get_country_code(); // your function
    $api =  fw_get_db_ext_settings_option( 'ht-movie', 'api-key', NULL );;

    $url = "https://api.themoviedb.org/3/$type/$tmdb_id/release_dates?api_key=$api";

    $res = wp_remote_get($url);

    if (is_wp_error($res)) return false;

    $data = json_decode(wp_remote_retrieve_body($res), true);
    //print_r($data['results']);

    if (empty($data['results'])) return false;

    $release_date = null;

    foreach ($data['results'] as $region) {

        if ($region['iso_3166_1'] !== $country) continue;

        foreach ($region['release_dates'] as $rd) {

            if (in_array($rd['type'], [2, 3])) {
                $release_date = $rd['release_date'];
                break 2;
            }
        }
    }
             //   print_r($release_date);
    // fallback to US if not found
    if (!$release_date) {
        foreach ($data['results'] as $region) {
            if ($region['iso_3166_1'] !== 'US') continue;

            foreach ($region['release_dates'] as $rd) {
                if (in_array($rd['type'], [2, 3])) {
                    $release_date = $rd['release_date'];
                    break 2;
                }
            }
        }
    }

    if (!$release_date) return false;

    $release_time = strtotime($release_date);
    $now = current_time('timestamp');

    $weeks_8 = 56 * DAY_IN_SECONDS;

    return ($now >= $release_time && $now <= ($release_time + $weeks_8));
}
public function ht_generate_provider_list( $providers ) {
	$provider_list = '';
	foreach ( $providers as $term)  {
		 $logo_url =  get_term_meta($term->term_id, 'logo', true);
		 $name    =esc_html($term->name);
		 $link    = get_term_link($term);

		$img     = '<img title="' . esc_attr( $name ) . '" src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $name ) . '" style="width:50px;height:auto;"/>';
		if ( $link ) {
			$provider_list .= '<li><a href="' . esc_url( $link ) . '">' . $img . '</a></li>';
		} else {
			$provider_list .= '<li>' . $img . '</li>';
		}
	}
	return $provider_list;
}


        public function register_taxonmoies(){
              register_taxonomy('mv_keyword', ['ht_movie', 'ht_show'], [
                    'label' => 'Keywords',
                    'hierarchical' => true, // keep as tags
                    'public' => true,
                    'rewrite' => ['slug' => 'keyword'],
                    'show_ui' => true,
                    'show_in_rest' => true,
                    'meta_box_cb' => 'post_tags_meta_box' // 🔥 THIS FIXES UI
                ]);
                 register_taxonomy('mv_trending', ['ht_movie', 'ht_show'], [
                    'label' => 'Trending',
                    'hierarchical' => true,
                    'public' => true,
                    'rewrite' => ['slug' => 'trending'],
                    'show_ui' => true,
                    'show_admin_column' => true,
                    'show_in_rest' => true
                ]);
        }
        public function ht_create_trending_terms() {

                    $structure = [
                        'Movie' => ['Day', 'Week'],
                        'TV'    => ['Day', 'Week']
                    ];

                    foreach ($structure as $parent => $children) {

                        $parent_term = term_exists($parent, 'mv_trending');

                        if (!$parent_term) {
                            $parent_term = wp_insert_term($parent, 'mv_trending');
                        }

                        $parent_id = $parent_term['term_id'];

                        foreach ($children as $child) {

                            $slug = strtolower($parent . '-' . $child);

                            if (!term_exists($slug, 'mv_trending')) {
                                wp_insert_term($child, 'mv_trending', [
                                    'parent' => $parent_id,
                                    'slug'   => $slug
                                ]);
                            }
                        }
                    }
        }
         public function plugin_setup_menu(){
            
             $mainMenu= add_submenu_page(
    	'edit.php?post_type=ht_movie',
    	'Trending Import',
    	'Trending Import',
    	'manage_options',
    	'import_trending',
    	array($this,'tredning_page_setup'),
        50
  	        );
             add_action( 'load-' . $mainMenu, [&$this,'admin_scripts_for_template'],99 );
              $mainSub=add_submenu_page(
        'edit.php?post_type=ht_movie',
        'Sync Old Data',
        'Sync Old Data',
        'manage_options',
        'sync-tmdb-ids',
        array($this,'render_tmdb_sync_page'),
       
    );
     add_action( 'load-' . $mainSub, [&$this,'admin_scripts_for_template'],99 );
         }
           public function admin_scripts_for_template($hook) {
                wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');
                wp_enqueue_style('bootstrap-datatable-css', 'https://cdn.datatables.net/2.3.7/css/dataTables.bootstrap5.min.css');
                wp_enqueue_style('bootstrap-datatable-button-css', 'https://cdn.datatables.net/buttons/3.2.6/css/buttons.bootstrap5.min.css');

                wp_enqueue_style('select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css');
                wp_enqueue_style('select2-bootstrap-css', 'https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css');

              //  wp_enqueue_style('fontawesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css');

                wp_enqueue_style( 'ht-movie-builder-style', HTE_PLUGIN_ABSOLUTE_PATH . 'admin/assets/css/movie-builder.css' );
               // wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.0', true);
                wp_enqueue_script('datatable-js', 'https://cdn.datatables.net/2.3.7/js/dataTables.min.js', array('jquery'), '2.3.7', true);
                wp_enqueue_script('datatable-bootstrap-js', 'https://cdn.datatables.net/2.3.7/js/dataTables.bootstrap5.min.js', array('jquery'), '2.3.7', true);
                wp_enqueue_script('datatable-buttons-js', 'https://cdn.datatables.net/buttons/3.2.6/js/dataTables.buttons.min.js', array('jquery'), '3.2.6', true);
                wp_enqueue_script('datatable-bootstrap-buttons-js', 'https://cdn.datatables.net/buttons/3.2.6/js/buttons.bootstrap5.min.js', array('jquery'), '3.2.6', true);

                wp_register_script( 'movie-builder-script', HTE_PLUGIN_ABSOLUTE_PATH . 'admin/assets/js/movie-builder.js', array('jquery'), time(), true );
                 
             wp_localize_script('movie-builder-script','movie_builder_vars',[
                'ajax_url'=>admin_url('admin-ajax.php'),
                'nonce'=>wp_create_nonce('movie_builder_nonce')

             ]);
             wp_enqueue_script('movie-builder-script');
        }
        public function render_tmdb_sync_page(){
            ?>
                      <div class="wrap">
                            <button id="btnFixKeywords" class="btn btn-primary">
                                    Fix Missing Keywords
                                </button> 

                                <!-- <button id="btnTestingWatch" class="btn btn-primary">
                                    Just Testing
                                </button> -->
                                <button id="syncAttachments" class="btn btn-primary">Sync Images</button>

                            <button id="fixCastID" class="btn btn-primary">Fix Cast IDs</button>
                            <div id="castProgressWrap" style="width:300px;background:#eee;margin-top:10px;">
                                 <div id="castProgress" style="width:0;height:20px;background:#28a745;"></div>
                            </div>
                            <button id="syncCastFull" class="button button-primary">
  Sync Cast Full Data
</button>
 <button id="testProviderList" class="button button-primary">
  Test Provider List
</button>

<div style="width:300px;background:#eee;margin-top:10px;">
  <div id="castFullBar" style="width:0;height:20px;background:#0073aa;"></div>
</div>

<div id="castFullLog" style="margin-top:10px;font-size:12px;"></div>

                            <div id="castLog" style="margin-top:10px;font-size:12px;"></div>
                            <div style="margin-top:10px;">
                                <div id="kwProgressBar" style="width:0%;height:20px;background:green;"></div>
                            </div>

                            <div id="kwLog"></div>

                            <div id="syncProgress" style="width:300px;background:#eee;">
                                 <div id="syncBar" style="width:0;height:20px;background:green;"></div>
                                </div>

                        <div id="syncLog"></div>
                        <button id="syncProviders" class="button button-primary">
  Sync Watch Providers (US + UK)
</button>

<div style="width:300px;background:#eee;margin-top:10px;">
  <div id="providerBar" style="width:0;height:20px;background:#28a745;"></div>
</div>
<div id="providerLog" style="margin-top:10px;font-size:12px;"></div>
  <button class="button button-primary" id="ht-scan-duplicates">
            Scan Duplicate Images
        </button>
        <button class="button button-primary" id="ht-delete-duplicates">
            Delete Duplicate Images
        </button>
          <button class="button button-primary" id="ht-create-collection-og-images">
            Create Collection OG Images
        </button>
        <pre id="ht-duplicate-log" style="margin-top:20px;background:#fff;padding:15px;max-height:500px;overflow:auto;"></pre>

                    </div>
            <?php
        }
         public function tredning_page_setup(){
            ?>
            <div id="trending-import-container" class="wrap">
                <h1>Import Trending Movie/TV Show</h1>
                <form class="row g-3">
                <div class="col-md-4">
                    <select id="import_type">
                        <option value="">Select Import Type</option>
                        <option value="movie">Movies</option>
                         <option value="tv">TV Shows</option>
                    </select>
                </div>
                <div class="col-md-4">
                     <select id="import_endpoint">
                        <option value="">Select Import Endpoint</option>
                        <option value="day">Day</option>
                         <option value="week">Week</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary" type="button" id="btnFetch">
                         <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                        <span class="buttonText">Fetch</span>
                    </button>
                </div>
                </form>
  <button class="btn btn-success mt-3" disabled id="btnImportSelected">
                        Import Selected
            </button>
           <div class="mt-3" id="importStatus">
                <div id="progressBar" style="width:0%;height:20px;background:green;"></div>
                </div>
                <div id="log"></div>
            <table class="table" id="tableTrending">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select_all"/></th>
                        <th>ID</th>
                         <th>Poster</th>
                          <th>Title</th>
                          <th>Overview</th>
                          <th>Release Date</th>
                    </tr>
                </thead>

            </table>

          
            
            </div>
         
            <?php
           
         }
        

public function ht_trending_archive_order($query) {

    if (is_admin() || !$query->is_main_query()) return;

    if (is_tax('mv_trending')) {
        
        $term = get_queried_object();
            
        if (!$term || empty($term->slug)) return;

        // 🔥 extract movie-day → movie + day
        if (strpos($term->slug, '-') !== false) {

            list($type, $period) = explode('-', $term->slug);

        } else {
            return; // invalid slug
        }

        // 🔥 set correct post type
        if ($type === 'tv') {
            $query->set('post_type', 'ht_show');
        } else {
            $query->set('post_type', 'ht_movie');
        }

        // 🔥 build meta key
        $meta_key = '_trending_' . $type . '_' . $period;
        // 🔥 apply ordering
        $query->set('meta_key', $meta_key);
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');

        // 🔥 ensure only trending items
        $query->set('meta_query', [
            [
                'key'     => $meta_key,
                'compare' => 'EXISTS'
            ]
        ]);
    }
}
         
    }
    new HT_Movie_Extended_Admin();
}