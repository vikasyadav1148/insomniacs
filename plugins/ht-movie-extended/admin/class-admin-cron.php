<?php
defined( 'ABSPATH' ) or exit;
if(!class_exists('HT_Movie_Extended_Admin_Cron')) {
    class HT_Movie_Extended_Admin_Cron{
                 public function __construct() {
                    add_action('init',[&$this,'schedule_cron_event']);
                 
                    add_filter('cron_schedules', function ($schedules) {

                        $schedules['minute'] = [
                            'interval' => 60,
                            'display'  => 'Every Minute'
                        ];
                         $schedules['every_5_min'] = [
                            'interval' => 300,
                            'display'  => 'Every 5 Minutes'
                        ];
                        $schedules['every_6_hours'] = [
                            'interval' => 21600,
                            'display'  => 'Every 6 Hours'
                         ];

                        return $schedules;
                    });
                    add_action('ht_trending_build_cron',[&$this,'ht_trending_build_cron_callback']);
                    add_action('ht_trending_process_cron', [&$this,'ht_process_trending_queue']);
                    add_action('ht_tmdb_process_queue', [&$this,'ht_tmdb_process_queue']);

                 //   add_action('ht_tmdb_sync_changes_event', [&$this,'ht_tmdb_sync_changes']);
                  

                    add_action('ht_attach_media_delayed', [&$this,'ht_attach_media_runner']);


                 }

                      public  function ht_attach_media_runner($post_id) {

                        //    if (get_post_meta($post_id, '_media_fixed', true)) {
                        //         return;
                        // }
                        $thumb_id = get_post_thumbnail_id($post_id);

                    if($thumb_id && get_post_field('post_parent', $thumb_id) != $post_id) {
                                wp_update_post([
                                    'ID' => $thumb_id,
                                    'post_parent' => $post_id
                                ]);
                        }
                     $banner = fw_get_db_post_option($post_id, 'banner');
                      if (!empty($banner['attachment_id'])) {

                         $att_id = $banner['attachment_id'];

                        if (get_post_field('post_parent', $att_id) != $post_id) {
                            wp_update_post([
                                'ID' => $att_id,
                                'post_parent' => $post_id
                            ]);
                        }
                    }
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
                             }
                        }
                        }
                    
                    }
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
                                    }
                            }
                        }
                    }
                            // update_post_meta($post_id, '_media_fixed', 1);

                            // // cleanup flag
                            // delete_post_meta($post_id, '_media_attach_scheduled');
                        }
                 public function ht_tmdb_process_queue() {

                        if (get_transient('ht_tmdb_process_lock')) return;
                        set_transient('ht_tmdb_process_lock', 1, 300);

                        $batch = $this->ht_tmdb_get_batch(5);

                        if (empty($batch)) {
                            delete_transient('ht_tmdb_process_lock');
                            return;
                        }

                        foreach ($batch as $item) {

                            try {

                                $post_id = $this->ht_find_post_by_tmdb($item->tmdb_id, $item->type);

                                if ($post_id) {
                                    $this->ht_tmdb_update_post($post_id, $item->tmdb_id, $item->type);
                                } else {
                                    $this->ht_import_tmdb_by_id($item->tmdb_id, $item->type);
                                }

                                $this->ht_tmdb_queue_mark_done($item->id);

                            } catch (Exception $e) {

                                $this->ht_tmdb_queue_mark_failed($item->id);
                            }
                        }

                        delete_transient('ht_tmdb_process_lock');
                }
               public  function ht_tmdb_update_post($post_id, $tmdb_id, $type) {

                    $api = fw_get_db_ext_settings_option('ht-movie', 'api-key', NULL);

                    $url = "https://api.themoviedb.org/3/$type/$tmdb_id?api_key=$api";

                    $res = wp_remote_get($url);

                    if (is_wp_error($res)) return;

                    $data = json_decode(wp_remote_retrieve_body($res), true);

                    // 🔥 reuse your existing logic
                      $adminAjax=new HT_Movie_Extended_Admin_Ajax();
                      $adminAjax->ht_apply_htmovie_logic($post_id, $data, $type, $api);
                }
                public function ht_find_post_by_tmdb($tmdb_id, $type) {

                        $post_type = ($type === 'tv') ? 'ht_show' : 'ht_movie';

                        $query = new WP_Query([
                            'post_type'  => $post_type,
                            'meta_key'   => 'tmdb_id',
                            'meta_value' => $tmdb_id,
                            'fields'     => 'ids',
                            'posts_per_page' => 1
                        ]);

                        return $query->posts[0] ?? false;
                }
                 public function ht_tmdb_sync_changes() {

                    if (get_transient('ht_tmdb_changes_lock')) return;
                    set_transient('ht_tmdb_changes_lock', 1, 600);

                    $last_sync = get_option('ht_tmdb_last_sync', date('Y-m-d', strtotime('-1 day')));
                    $today     = date('Y-m-d');

                    $this->ht_tmdb_fetch_changes('movie', $last_sync, $today);
                    $this->ht_tmdb_fetch_changes('tv', $last_sync, $today);

                        update_option('ht_tmdb_last_sync', $today);

                        delete_transient('ht_tmdb_changes_lock');
                    }
                    public function ht_tmdb_get_batch($limit = 5) {
                                global $wpdb;

                                $table = $wpdb->prefix . 'ht_tmdb_queue';

                                $rows = $wpdb->get_results(
                                    "SELECT * FROM $table 
                                    WHERE status IN ('pending','failed') 
                                    AND attempts < 3
                                    ORDER BY priority DESC, id ASC
                                    LIMIT $limit"
                                );

                                foreach ($rows as $row) {
                                    $wpdb->update($table, [
                                        'status' => 'processing',
                                        'attempts' => $row->attempts + 1,
                                        'last_attempt' => current_time('mysql')
                                    ], ['id' => $row->id]);
                                }

                                return $rows;
                    }
                 public function ht_trending_build_cron_callback(){
                      $this->ht_prepare_trending_terms(); // clear first
                         $this->ht_build_trending_queue();
                 }
             
                public function ht_build_trending_queue() {

                    $queue = [];
                    $types = [
                        ['movie', 'day'],
                        ['tv', 'day'],
                        ['movie', 'week'],
                        ['tv', 'week']
                    ];

                    $api = fw_get_db_ext_settings_option('ht-movie', 'api-key', NULL);

                            foreach ($types as $t) {

                                [$type, $period] = $t;

                                $rank = 1;

                               
                             //   for ($page = 1; $page <= 3; $page++) {

                                    $url = "https://api.themoviedb.org/3/trending/$type/$period?api_key=$api&language=en-US";

                                    $res = wp_remote_get($url);

                                    if (is_wp_error($res)) continue;

                                    $data = json_decode(wp_remote_retrieve_body($res), true);

                                    if (empty($data['results'])) continue;
                                   // original_language

                                    foreach ($data['results'] as $item) {

                                        // stop at 50
                                        if ($rank > 50) break 2;

                                        $queue[] = [
                                            'id'     => $item['id'],
                                            'type'   => $type,
                                            'period' => $period,
                                            'rank'   => $rank,
                                            'popularity' => $item['popularity'] ?? 0
                                        ];

                                        $rank++;
                                    }
                                //}
                            }

                update_option('ht_trending_queue', $queue, false);
        }
               public  function ht_process_trending_queue() {
                             if (get_transient('ht_trending_lock')) {
                                 return;
                                }
                            set_transient('ht_trending_lock', 1, 55); // slightly less than 60 sec

                            $queue = get_option('ht_trending_queue', []);

                           if (empty($queue)) {
                                delete_transient('ht_trending_lock');
                                return;
                            }

                            // 🔥 process only 5 items
                            $batch = array_splice($queue, 0, 10);

                            foreach ($batch as $item) {

                                $post_id = $this->ht_get_post_by_tmdb($item['id'], $item['type']);

                                // if (!$post_id) {
                                //     $post_id = $this->ht_import_tmdb_by_id($item['id'], $item['type']);
                                // }

                                if ($post_id) {
                                     $rank = isset($item['rank']) ? (int)$item['rank'] : 999;
                                     update_post_meta(
                                         $post_id,
                                        '_trending_' . $item['type'] . '_' . $item['period'],
                                            $rank
                                    );
                                    if (isset($item['score'])) {
                                        update_post_meta(
                                            $post_id,
                                            '_trending_score_' . $item['type'] . '_' . $item['period'],
                                            $item['score']
                                        );
                                    }
                                    $this->ht_assign_trending($post_id, $item['type'], $item['period']);
                                }
                            }

                            // save remaining queue
                            update_option('ht_trending_queue', $queue, false);
                            delete_transient('ht_trending_lock');
                }
               public function ht_prepare_trending_terms() {

                    $this->ht_clear_trending('movie', 'day');
                    $this->ht_clear_trending('tv', 'day');
                    $this->ht_clear_trending('movie', 'week');
                    $this->ht_clear_trending('tv', 'week');
                }

                 public function schedule_cron_event(){
                    // if (!wp_next_scheduled('ht_trending_cron')) {
                    //   wp_schedule_event(time(), 'daily', 'ht_trending_cron');
                    // }
                    if (!wp_next_scheduled('ht_trending_build_cron')) {
                             wp_schedule_event(time(), 'daily', 'ht_trending_build_cron');
                        }

                    if (!wp_next_scheduled('ht_trending_process_cron')) {
                        wp_schedule_event(time(), 'minute', 'ht_trending_process_cron');
                    }
                    // Process queue
                    if (!wp_next_scheduled('ht_tmdb_process_queue')) {
                        wp_schedule_event(time(), 'every_5_min', 'ht_tmdb_process_queue');
                    }

                    // Fetch changes
                    if (!wp_next_scheduled('ht_tmdb_sync_changes_event')) {
                        //wp_schedule_event(time(), 'every_6_hours', 'ht_tmdb_sync_changes_event');
                    }

                 }
               
                 public function ht_get_post_by_tmdb($tmdb_id, $type) {

                            $post_type = ($type === 'tv') ? 'ht_show' : 'ht_movie';

                            $posts = get_posts([
                                'post_type' => $post_type,
                                'meta_query' => [
                                    [
                                        'key' => '_tmdb_id',
                                        'value' => $tmdb_id
                                    ]
                                ],
                                'numberposts' => 1
                            ]);

                            return $posts ? $posts[0]->ID : false;
                    }
                    public function ht_assign_trending($post_id, $type, $period) {

                        $parent = ($type === 'tv') ? 'TV' : 'Movie';
                        $slug   = strtolower($parent . '-' . ucfirst($period));

                        $term = get_term_by('slug', $slug, 'mv_trending');

                        if ($term) {
                            wp_set_object_terms($post_id, [$term->term_id], 'mv_trending', true);
                        }

                    }
                    public function ht_clear_trending($type, $period) {

                        $parent = ($type === 'tv') ? 'TV' : 'Movie';
                        $slug   = strtolower($parent . '-' . ucfirst($period));

                        $term = get_term_by('slug', $slug, 'mv_trending');

                        if (!$term) return;

                        $posts = get_posts([
                            'post_type' => ['ht_movie', 'ht_show'],
                            'numberposts' => -1,
                            'tax_query' => [
                                [
                                    'taxonomy' => 'mv_trending',
                                    'field'    => 'term_id',
                                    'terms'    => $term->term_id
                                ]
                            ]
                        ]);

                        foreach ($posts as $p) {
                            wp_remove_object_terms($p->ID, $term->term_id, 'mv_trending');
                            delete_post_meta($p->ID, '_trending_' . $type . '_' . $period);
                            delete_post_meta($p->ID, '_trending_score_' . $type . '_' . $period);
                        }
                    }
                    public function ht_process_trending($type = 'movie', $period = 'day') {

                                $api = fw_get_db_ext_settings_option( 'ht-movie', 'api-key', NULL );

                                $url = "https://api.themoviedb.org/3/trending/$type/$period?api_key=$api";

                                $res = wp_remote_get($url);

                                if (is_wp_error($res)) return;

                                $data = json_decode(wp_remote_retrieve_body($res), true);

                                if (empty($data['results'])) return;

                                // 🔥 Only top 50
                                $results = array_slice($data['results'], 0, 50);

                                // 🔥 clear previous trending
                                $this->ht_clear_trending($type, $period);

                                foreach ($results as $index => $item) {

                                    $tmdb_id = $item['id'];

                                    // check existing
                                    $post_id = $this->ht_get_post_by_tmdb($tmdb_id, $type);

                                    // import if not exists
                                    if (!$post_id) {
                                        $post_id = $this->ht_import_tmdb_by_id($tmdb_id, $type);
                                    }

                                    if ($post_id) {
                                        $this->ht_assign_trending($post_id, $type, $period);
                                    }
                                    update_post_meta(
                                    $post_id,
                                    '_trending_' . $type . '_' . $period,
                                    $index + 1
                                );
                                    if (isset($item['popularity'])) {
                                        update_post_meta(
                                            $post_id,
                                            '_trending_score_' . $type . '_' . $period,
                                            $item['popularity']
                                        );
                                    }
                                }
                    }
                    public function ht_import_tmdb_by_id($tmdb_id, $type = 'movie') {

                            $api = fw_get_db_ext_settings_option( 'ht-movie', 'api-key', NULL );

                            // ✅ 1. Check existing first
                            $existing = $this->ht_get_post_by_tmdb($tmdb_id, $type);

                            if ($existing) {
                                return $existing; // 🔥 skip re-import
                            }

                            // ✅ 2. Endpoint
                            $endpoint = ($type === 'tv')
                                ? "https://api.themoviedb.org/3/tv/$tmdb_id"
                                : "https://api.themoviedb.org/3/movie/$tmdb_id";

                            // ✅ 3. Single optimized API call
                            $url = $endpoint . "?api_key=$api";

                            $res = wp_remote_get($url);

                            if (is_wp_error($res)) return false;

                            $data = json_decode(wp_remote_retrieve_body($res), true);

                            if (empty($data) || isset($data['status_code'])) {
                                return false;
                            }
                            $adminAjax=new HT_Movie_Extended_Admin_Ajax();
                            // ✅ 4. Create post
                            $post_id = $adminAjax->ht_create_post_basic($data, $type);

                            if (!$post_id) return false;

                            // ✅ 5. Save TMDB ID (VERY IMPORTANT)
                            update_post_meta($post_id, '_tmdb_id', $tmdb_id);

                            // ✅ 6. Apply full HT Movie logic
                            $adminAjax->ht_apply_htmovie_logic($post_id, $data, $type, $api);

                           

                            return $post_id;
                        }
            public function ht_tmdb_queue_insert($tmdb_id, $type = 'movie', $priority = 0) {
                            global $wpdb;

                            $table = $wpdb->prefix . 'ht_tmdb_queue';

                            // avoid duplicate
                            $exists = $wpdb->get_var($wpdb->prepare(
                                "SELECT id FROM $table WHERE tmdb_id = %d AND type = %s LIMIT 1",
                                $tmdb_id, $type
                            ));

                            if ($exists) return;

                            $wpdb->insert($table, [
                                'tmdb_id' => $tmdb_id,
                                'type'    => $type,
                                'priority'=> $priority,
                                'status'  => 'pending'
                            ]);
    }
    public function ht_tmdb_queue_bulk_insert($items) {
    foreach ($items as $item) {
        $this->ht_tmdb_queue_insert($item['id'], $item['type']);
        }
    }
    public function ht_tmdb_queue_get_batch($limit = 5) {
            global $wpdb;

            $table = $wpdb->prefix . 'ht_tmdb_queue';

            $rows = $wpdb->get_results(
                "SELECT * FROM $table 
                WHERE status = 'pending'
                ORDER BY priority DESC, id ASC
                LIMIT $limit"
            );

            if (!$rows) return [];

            // mark as processing
            foreach ($rows as $row) {
                $wpdb->update($table, [
                    'status' => 'processing',
                    'last_attempt' => current_time('mysql'),
                    'attempts' => $row->attempts + 1
                ], ['id' => $row->id]);
            }

            return $rows;
        }
        public function ht_tmdb_queue_mark_done($id) {
            global $wpdb;
            $table = $wpdb->prefix . 'ht_tmdb_queue';

            $wpdb->update($table, [
                'status' => 'done'
            ], ['id' => $id]);
        }
        public function ht_tmdb_queue_mark_failed($id) {
                global $wpdb;
                $table = $wpdb->prefix . 'ht_tmdb_queue';

                $wpdb->update($table, [
                    'status' => 'failed'
                ], ['id' => $id]);
        }
        public function ht_tmdb_fetch_changes($type, $start, $end) {

            $api = fw_get_db_ext_settings_option( 'ht-movie', 'api-key', NULL );
            $page = 1;

    do {

        $url = "https://api.themoviedb.org/3/$type/changes?api_key=$api&start_date=$start&end_date=$end&page=$page";

        $res = wp_remote_get($url);

        if (is_wp_error($res)) break;

        $data = json_decode(wp_remote_retrieve_body($res), true);

        if (empty($data['results'])) break;

        foreach ($data['results'] as $item) {
            $this->ht_tmdb_queue_insert($item['id'], $type);
        }

             $page++;

            } while ($page <= $data['total_pages']);
        }

    }
    new HT_Movie_Extended_Admin_Cron();
}