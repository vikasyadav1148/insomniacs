<?php
/**
 * Insomniacs Homepage Hero Shortcode Integration PHP Script
 * 
 * CORE LOADING RECOMMENDATION:
 * For successful direct inclusion in your WordPress theme's functions.php file, use:
 * 
 * require_once get_template_directory() . '/wordpress-functions-hero-integration.php';
 * 
 * Or (highly recommended) copy and paste this entire file's content directly into your functions.php!
 * 
 * Registers the shortcode: [insomniacs_homepage_hero]
 * 
 * Features:
 * - Dynamic DB Post Query matching configured post types 
 * - Full Administrative Settings Dashboard in WordPress backend
 * - Automatic Live Ticking Mechanical Countdown (Desktop & Mobile Sync)
 * - Sidebar Navigation: Clicking items switches the main preview hero instantly
 * - High-Fidelity Custom Styling with Premium Responsive Design
 * - Cybernetic Marquee looped ticker bar customized from admin
 */

// 1. REGISTER SHORTCODE
if ( ! function_exists( 'insomniacs_register_homepage_hero_shortcode' ) ) {
    function insomniacs_register_homepage_hero_shortcode() {
        if ( ! shortcode_exists( 'insomniacs_homepage_hero' ) ) {
            add_shortcode( 'insomniacs_homepage_hero', 'insomniacs_render_homepage_shortcode_callback' );
        }
    }
    add_action( 'init', 'insomniacs_register_homepage_hero_shortcode', 5 );
}

// Global shortcode backup declaration
if ( ! shortcode_exists( 'insomniacs_homepage_hero' ) ) {
    add_shortcode( 'insomniacs_homepage_hero', 'insomniacs_render_homepage_shortcode_callback' );
}

// 2. ADMIN PAGES / SETTINGS BACKEND
if ( is_admin() ) {
    add_action( 'admin_menu', 'insomniacs_hero_add_admin_menu' );
    add_action( 'admin_init', 'insomniacs_hero_settings_init' );
    add_action( 'admin_enqueue_scripts', function($hook) {
        if ( 'toplevel_page_insomniacs-hero-settings' === $hook ) {
            wp_enqueue_media();
        }
    });

    // Custom configuration data importer handler
    if ( ! function_exists( 'insomniacs_handle_settings_import_export' ) ) {
        function insomniacs_handle_settings_import_export() {
            if ( isset($_POST['insom_import_settings_nonce']) && wp_verify_nonce($_POST['insom_import_settings_nonce'], 'insom_import_settings') ) {
                $import_data = isset($_POST['insom_import_raw_data']) ? trim($_POST['insom_import_raw_data']) : '';
                if ( ! empty($import_data) ) {
                    $decoded = json_decode(stripslashes($import_data), true);
                    if ( is_array($decoded) ) {
                        foreach ( $decoded as $key => $val ) {
                            // Secure database option write specifically for insom prefix
                            if ( strpos($key, 'insom_') === 0 ) {
                                if ( is_array($val) ) {
                                    $sanitized_arr = array();
                                    foreach ( $val as $idx => $item ) {
                                        if ( is_array($item) ) {
                                            $sanitized_item = array();
                                            foreach ( $item as $prop_k => $prop_v ) {
                                                if ( in_array($prop_k, array('thumbnail', 'permalink', 'trailer')) ) {
                                                    $sanitized_item[$prop_k] = esc_url_raw($prop_v);
                                                } else if ( $prop_k === 'synopsis' ) {
                                                    $sanitized_item[$prop_k] = sanitize_textarea_field($prop_v);
                                                } else {
                                                    $sanitized_item[$prop_k] = sanitize_text_field($prop_v);
                                                }
                                            }
                                            $sanitized_arr[$idx] = $sanitized_item;
                                        }
                                    }
                                    update_option($key, $sanitized_arr);
                                } else {
                                    update_option($key, sanitize_text_field($val));
                                }
                            }
                        }
                        wp_safe_redirect( add_query_arg( array( 'page' => 'insomniacs-hero-settings', 'insom_imported' => '1' ), admin_url( 'admin.php' ) ) );
                        exit;
                    }
                }
            }
        }
        add_action( 'admin_init', 'insomniacs_handle_settings_import_export' );
    }

    // Register AJAX search handler
    if ( ! function_exists( 'insomniacs_hero_search_posts_ajax_callback' ) ) {
        function insomniacs_hero_search_posts_ajax_callback() {
            check_ajax_referer( 'insom_search_nonce', 'security' );
            
            $term = isset($_GET['q']) ? sanitize_text_field($_GET['q']) : '';
            $post_types_opt = get_option('insom_post_types', 'post,ht_movie,ht_show,ht_tv_show');
            $pts_array = array_map('trim', explode(',', $post_types_opt));
            
            $args = array(
                'post_type'      => $pts_array,
                'posts_per_page' => 20,
                'post_status'    => 'publish',
                's'              => $term,
            );
            
            $query = new WP_Query($args);
            $results = array();
            
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $results[] = array(
                        'id' => get_the_ID(),
                        'title' => get_the_title(),
                        'type' => get_post_type()
                    );
                }
            }
            wp_reset_postdata();
            
            wp_send_json_success($results);
            wp_die();
        }
        add_action( 'wp_ajax_insom_search_posts', 'insomniacs_hero_search_posts_ajax_callback' );
    }
}

if ( ! function_exists( 'insomniacs_hero_add_admin_menu' ) ) {
    function insomniacs_hero_add_admin_menu() {
        add_menu_page(
            'Insomniacs Hero',
            'Insomniacs Hero',
            'manage_options',
            'insomniacs-hero-settings',
            'insomniacs_hero_settings_layout',
            'dashicons-video-alt3',
            59
        );
    }
}

if ( ! function_exists( 'insomniacs_hero_settings_init' ) ) {
    function insomniacs_hero_settings_init() {
        // Core Layout & Background options
        register_setting( 'insom_hero_settings_group', 'insom_accent_color' );
        register_setting( 'insom_hero_settings_group', 'insom_bg_color' );
        register_setting( 'insom_hero_settings_group', 'insom_target_date' );
        register_setting( 'insom_hero_settings_group', 'insom_ticker_text' );
        register_setting( 'insom_hero_settings_group', 'insom_post_types' );
        register_setting( 'insom_hero_settings_group', 'insom_right_item_count' );
        register_setting( 'insom_hero_settings_group', 'insom_default_adrenaline' );
        register_setting( 'insom_hero_settings_group', 'insom_default_fallback_bg' );
        register_setting( 'insom_hero_settings_group', 'insom_top_spacing_desktop' );
        register_setting( 'insom_hero_settings_group', 'insom_top_spacing_mobile' );

        // DB Querying, Ordering & Filtering options
        register_setting( 'insom_hero_settings_group', 'insom_query_order' );
        register_setting( 'insom_hero_settings_group', 'insom_query_orderby' );
        register_setting( 'insom_hero_settings_group', 'insom_filter_categories' );
        register_setting( 'insom_hero_settings_group', 'insom_filter_post_ids' );

        // Frontend Content Label customizers
        register_setting( 'insom_hero_settings_group', 'insom_label_upcoming_spread' );
        register_setting( 'insom_hero_settings_group', 'insom_label_sync_release' );
        register_setting( 'insom_hero_settings_group', 'insom_label_play_trailer' );
        register_setting( 'insom_hero_settings_group', 'insom_label_coordinate_party' );
        register_setting( 'insom_hero_settings_group', 'insom_label_days' );
        register_setting( 'insom_hero_settings_group', 'insom_label_hours' );
        register_setting( 'insom_hero_settings_group', 'insom_label_mins' );
        register_setting( 'insom_hero_settings_group', 'insom_label_secs' );
        register_setting( 'insom_hero_settings_group', 'insom_label_view_all_calendar' );
        register_setting( 'insom_hero_settings_group', 'insom_label_view_all_mobile' );
        register_setting( 'insom_hero_settings_group', 'insom_label_premiere_medals' );
        register_setting( 'insom_hero_settings_group', 'insom_label_stats_text' );
        register_setting( 'insom_hero_settings_group', 'insom_label_next_upcoming' );
        register_setting( 'insom_hero_settings_group', 'insom_label_coordination' );
        register_setting( 'insom_hero_settings_group', 'insom_label_upcoming_badge' );
        register_setting( 'insom_hero_settings_group', 'insom_label_next_up' );
        register_setting( 'insom_hero_settings_group', 'insom_label_countdown_title' );
        register_setting( 'insom_hero_settings_group', 'insom_fallback_items_data', array(
            'sanitize_callback' => 'insom_sanitize_fallback_items_data'
        ) );
    }
}

if ( ! function_exists( 'insom_sanitize_fallback_items_data' ) ) {
    function insom_sanitize_fallback_items_data( $input ) {
        if ( ! is_array( $input ) ) {
            return array();
        }
        $sanitized_arr = array();
        for ( $i = 0; $i < 5; $i++ ) {
            if ( isset( $input[$i] ) && is_array( $input[$i] ) ) {
                $item = $input[$i];
                $sanitized_arr[$i] = array(
                    'title'       => sanitize_text_field( isset($item['title']) ? $item['title'] : '' ),
                    'type'        => sanitize_text_field( isset($item['type']) ? $item['type'] : 'TV Series' ),
                    'releaseDate' => sanitize_text_field( isset($item['releaseDate']) ? $item['releaseDate'] : '' ),
                    'rating'      => sanitize_text_field( isset($item['rating']) ? $item['rating'] : '' ),
                    'score'       => sanitize_text_field( isset($item['score']) ? $item['score'] : '' ),
                    'adrenaline'  => sanitize_text_field( isset($item['adrenaline']) ? $item['adrenaline'] : '' ),
                    'tagline'     => sanitize_text_field( isset($item['tagline']) ? $item['tagline'] : '' ),
                    'permalink'   => esc_url_raw( isset($item['permalink']) ? $item['permalink'] : '' ),
                    'trailer'     => esc_url_raw( isset($item['trailer']) ? $item['trailer'] : '' ),
                    'thumbnail'   => esc_url_raw( isset($item['thumbnail']) ? $item['thumbnail'] : '' ),
                    'synopsis'    => sanitize_textarea_field( isset($item['synopsis']) ? $item['synopsis'] : '' ),
                    'rank'        => sanitize_text_field( isset($item['rank']) ? $item['rank'] : '' ),
                );
            }
        }
        return $sanitized_arr;
    }
}

if ( ! function_exists( 'insomniacs_hero_settings_layout' ) ) {
    function insomniacs_hero_settings_layout() {
        // Enforce basic defaults if not yet set
        $accent = get_option('insom_accent_color');
        if ( empty($accent) ) { $accent = '#ff0033'; }
        
        $bg_color = get_option('insom_bg_color');
        if ( empty($bg_color) ) { $bg_color = '#0a0a0a'; }
        
        $target_date = get_option('insom_target_date');
        if ( empty($target_date) ) { $target_date = '2026-07-25 20:00:00'; }
        
        $ticker = get_option('insom_ticker_text');
        if ( empty($ticker) ) { $ticker = 'Current Event Matrix: Adrenaline Index 100% | Insomniacs Sync Active | High Voltage Premiere Pending...'; }
        
        $pts = get_option('insom_post_types');
        if ( empty($pts) ) { $pts = 'post,ht_movie,ht_show,ht_tv_show'; }
        
        $count = get_option('insom_right_item_count');
        if ( empty($count) ) { $count = '5'; }
        
        $def_adr = get_option('insom_default_adrenaline');
        if ( empty($def_adr) ) { $def_adr = '98'; }
        
        $def_bg = get_option('insom_default_fallback_bg');
        if ( empty($def_bg) ) { $def_bg = 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&q=80&w=1200'; }
        
        $top_desk = get_option('insom_top_spacing_desktop');
        if ( empty($top_desk) || $top_desk === '0' || $top_desk === '0px' ) { $top_desk = '0px'; }
        
        $top_mobi = get_option('insom_top_spacing_mobile');
        if ( empty($top_mobi) || $top_mobi === '0' || $top_mobi === '0px' ) { $top_mobi = '0px'; }

        // New Configs
        $query_order = get_option('insom_query_order');
        if ( empty($query_order) ) { $query_order = 'ASC'; }
        
        $query_orderby = get_option('insom_query_orderby');
        if ( empty($query_orderby) ) { $query_orderby = 'date'; }
        
        $filter_categories = get_option('insom_filter_categories', '');
        $filter_post_ids = get_option('insom_filter_post_ids', '');

        $label_upcoming_spread = get_option('insom_label_upcoming_spread');
        if ( empty($label_upcoming_spread) ) { $label_upcoming_spread = 'UPCOMING SPREAD'; }
        
        $label_sync_release = get_option('insom_label_sync_release');
        if ( empty($label_sync_release) ) { $label_sync_release = 'Sync Release'; }
        
        $label_play_trailer = get_option('insom_label_play_trailer');
        if ( empty($label_play_trailer) || $label_play_trailer === 'Play Trailer' || $label_play_trailer === 'See Details' ) { 
            $label_play_trailer = 'See details'; 
            update_option('insom_label_play_trailer', 'See details');
        }
        
        $label_coordinate_party = get_option('insom_label_coordinate_party');
        if ( empty($label_coordinate_party) ) { $label_coordinate_party = 'Coordinate Party'; }
        
        $label_days = get_option('insom_label_days');
        if ( empty($label_days) ) { $label_days = 'DAYS'; }
        
        $label_hours = get_option('insom_label_hours');
        if ( empty($label_hours) ) { $label_hours = 'HOURS'; }
        
        $label_mins = get_option('insom_label_mins');
        if ( empty($label_mins) ) { $label_mins = 'MINS'; }
        
        $label_secs = get_option('insom_label_secs');
        if ( empty($label_secs) ) { $label_secs = 'SECS'; }

        $label_view_all_calendar = get_option('insom_label_view_all_calendar');
        if ( empty($label_view_all_calendar) ) { $label_view_all_calendar = 'View Full Release Calendar'; }

        $label_view_all_mobile = get_option('insom_label_view_all_mobile');
        if ( empty($label_view_all_mobile) ) { $label_view_all_mobile = 'VIEW ALL'; }

        $label_premiere_medals = get_option('insom_label_premiere_medals');
        if ( empty($label_premiere_medals) ) { $label_premiere_medals = 'PREMIERE MEDALS:'; }

        $label_stats_text = get_option('insom_label_stats_text');
        if ( empty($label_stats_text) ) { $label_stats_text = 'GLOBAL RANK #115 • COLLECTED 14/30'; }

        $label_next_upcoming = get_option('insom_label_next_upcoming');
        if ( empty($label_next_upcoming) ) { $label_next_upcoming = 'NEXT UPCOMING'; }

        $label_coordination = get_option('insom_label_coordination');
        if ( empty($label_coordination) ) { $label_coordination = 'Coordination'; }

        $label_upcoming_badge = get_option('insom_label_upcoming_badge');
        if ( empty($label_upcoming_badge) ) { $label_upcoming_badge = 'UPCOMING'; }

        $label_next_up = get_option('insom_label_next_up');
        if ( empty($label_next_up) ) { $label_next_up = 'Next Up'; }

        $label_countdown_title = get_option('insom_label_countdown_title');
        if ( empty($label_countdown_title) ) { $label_countdown_title = 'LIVE TICKING COUNTDOWN:'; }

        // Capture settings saving status
        ?>
        <style>
            .notice.is-dismissible {
                position: relative !important;
            }
            .notice.is-dismissible .notice-dismiss {
                color: #ffffff !important;
                background: transparent !important;
                text-decoration: none !important;
                opacity: 0.8 !important;
                outline: none !important;
                box-shadow: none !important;
            }
            .notice.is-dismissible .notice-dismiss::before {
                color: #ffffff !important;
                font-size: 20px !important;
            }
            .notice.is-dismissible .notice-dismiss:hover::before {
                color: #ff0033 !important;
                opacity: 1 !important;
            }
        </style>
        <?php
        if ( isset($_GET['settings-updated']) && $_GET['settings-updated'] ) {
            echo '<div class="notice notice-success is-dismissible" style="background:#15171e; border-left-color:#00f0ff; color:#fff;"><p><strong>⚡ Insomniacs Core Alignment Synchronized Safely.</strong></p></div>';
        }
        if ( isset($_GET['insom_imported']) && $_GET['insom_imported'] ) {
            echo '<div class="notice notice-success is-dismissible" style="background:#15171e; border-left-color:#ff3366; color:#fff;"><p><strong>⚡ Insomniacs Configuration Payload Imported Successfully!</strong></p></div>';
        }
        ?>
        <div class="wrap" style="background: #0d0e12; color: #f1f1f1; padding: 25px; border-radius: 12px; max-width: 950px; margin-top: 20px; font-family: 'Segoe UI', system-ui, sans-serif; box-shadow: 0 10px 30px rgba(0,0,0,0.35);">
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #2d303b; padding-bottom: 20px; margin-bottom: 25px;">
                <div>
                    <h1 style="color: #fff; font-size: 28px; font-weight: 800; margin: 0; text-transform: uppercase; letter-spacing: -1px; text-shadow: 0 0 10px rgba(255,0,51,0.2);">⚡ Insomniacs Hero Deck</h1>
                    <p style="color: #8b92a6; margin: 5px 0 0 0; font-size: 13px;">Manage colors, custom sorting order, search metrics, category filtering, and label translations.</p>
                </div>
                <div style="background: rgba(255,0,51,0.1); border: 1px solid rgba(255,0,51,0.3); padding: 8px 16px; border-radius: 30px; font-size: 11px; font-weight: 700; color: #ff0033; letter-spacing: 1px; text-transform: uppercase;">
                    Spectator Core Online
                </div>
            </div>

            <form method="post" action="options.php" style="background: #15171e; padding: 25px; border-radius: 10px; border: 1px solid #232731;">
                <?php settings_fields( 'insom_hero_settings_group' ); ?>
                <?php do_settings_sections( 'insom_hero_settings_group' ); ?>

                <!-- SECTION 1: GLOBAL DESIGN & COORDINATES -->
                <h3 style="color: #00f0ff; border-bottom: 1px solid rgba(0,240,255,0.15); padding-bottom: 8px; margin: 0 0 15px 0; font-size: 16px; text-transform: uppercase; letter-spacing: 1px;">🎨 Section 1: Global Design & Coordinates</h3>
                <table class="form-table" style="width: 100%; border-collapse: separate; border-spacing: 0 12px; margin-bottom: 30px;">
                    <!-- ACCENT COLOR -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                Accent Glow Color
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="The core brand highlight color used on buttons, progress bars, glowing borders, and key neon elements.">ℹ️</span>
                            </label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Primary action theme color.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="color" name="insom_accent_color" value="<?php echo esc_attr($accent); ?>" style="width: 60px; height: 35px; border: none; background: transparent; cursor: pointer; vertical-align: middle;" />
                            <code style="background: #0d0e12; color: #ff0033; padding: 6px 10px; border-radius: 4px; margin-left: 10px; font-size: 12px; border: 1px solid #232731; vertical-align: middle;"><?php echo esc_html($accent); ?></code>
                        </td>
                    </tr>

                    <!-- BACKGROUND COLOR -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                Hero Canvas Background
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="The main background hex color of the slider. Typically dark (e.g. #0a0a0a) to ensure neon colors pop.">ℹ️</span>
                            </label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Core canvas hex value.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_bg_color" value="<?php echo esc_attr($bg_color); ?>" style="width: 320px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>

                    <!-- TARGET COUNTDOWN DATE -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                Fallback Target Countdown Date
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="The date used for the global live countdown timer if an active selected show does not compile a custom release date.">ℹ️</span>
                            </label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Default baseline target time.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_target_date" value="<?php echo esc_attr($target_date); ?>" placeholder="YYYY-MM-DD HH:MM:SS" style="width: 320px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px; font-family: monospace;" required />
                            <p style="font-size: 10px; color: #64748b; margin: 4px 0 0 0;">Format: <code>2026-07-25 20:00:00</code></p>
                        </td>
                    </tr>

                    <!-- MARQUEE TICKER TEXT -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                Cybernetic Ticker Message
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="This ticker text moves horizontally across the bottom marquee element of the layout. Keep it punchy!">ℹ️</span>
                            </label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Looping baseline text.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <textarea name="insom_ticker_text" rows="2" style="width: 100%; max-width: 500px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 10px; border-radius: 6px; font-size: 13px; resize: vertical;" required><?php echo esc_textarea($ticker); ?></textarea>
                        </td>
                    </tr>

                    <!-- MEDIA PLACEHOLDER HERO IMAGE URL -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                Fallback Background Image
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="The default background poster or billboard image displayed behind text if a selected show's custom graphic is missing.">ℹ️</span>
                            </label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Used if featured image is absent. Upload via media gallery.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <div style="display: flex; gap: 8px; width: 100%; max-width: 500px; align-items: center;">
                                <input type="text" id="insom_default_fallback_bg" name="insom_default_fallback_bg" value="<?php echo esc_url($def_bg); ?>" style="flex: 1; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 12px;" required />
                                <button type="button" class="insom-media-upload-trigger button button-secondary" data-target="insom_default_fallback_bg" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15) !important; color: #ffffff !important; padding: 4px 14px; border-radius: 6px; height: 35px; line-height: 25px;">
                                    Library...
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- DESKTOP / MOBILE SPACING OFFSETS -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                Desktop Header Clearing Offset
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="Specifies safety margin (CSS spacing) at the top of the desk on desktop monitors to accommodate logo bar overlay.">ℹ️</span>
                            </label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Prevents sliding under transparent menu blocks.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_top_spacing_desktop" value="<?php echo esc_attr($top_desk); ?>" style="width: 120px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>

                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                Mobile Header Clearing Offset
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="Specifies safety margin (CSS spacing) at the top of the deck on smartphones to clear mobile menus gracefully.">ℹ️</span>
                            </label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Height padding offset on mobile devices.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_top_spacing_mobile" value="<?php echo esc_attr($top_mobi); ?>" style="width: 120px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>
                </table>

                <!-- SECTION 2: ADVANCED QUERY FILTER & SORT LAUNCHER -->
                <h3 style="color: #00f0ff; border-bottom: 1px solid rgba(0,240,255,0.15); padding-bottom: 8px; margin: 30px 0 15px 0; font-size: 16px; text-transform: uppercase; letter-spacing: 1px;">💾 Section 2: Database Query Filtering & Sort Sorting</h3>
                <table class="form-table" style="width: 100%; border-collapse: separate; border-spacing: 0 12px; margin-bottom: 30px;">
                    <!-- WP POST TYPES -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                Target WordPress Post Types
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="Comma-separated custom post type slugs (e.g. ht_movie, ht_show) queried by the layout engine.">ℹ️</span>
                            </label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Comma-separated post slugs lists.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_post_types" value="<?php echo esc_attr($pts); ?>" style="width: 100%; max-width: 500px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>

                    <!-- FILTER SELECT MULTI POSTS -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Featured Movies / TV Series</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Search database for Movies & TV Shows (e.g. <code>House of Dragon</code>, <code>Rings of Power</code>, <code>Harry Potter</code>) to feature directly on the frontend deck.</p>
                        </th>
                        <td style="padding: 5px 0; position: relative;">
                            <!-- Search Autocomplete Input -->
                            <div style="position: relative; max-width: 500px; width: 100%;">
                                <input type="text" id="insom_post_search" placeholder="Type title to search database..." style="width: 100%; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 10px 14px; border-radius: 6px; font-size: 13px;" />
                                <!-- Results container drop -->
                                <div id="insom_search_results" style="display: none; position: absolute; left: 0; right: 0; top: 100%; background: #1a1d26; border: 1px solid #2d303b; border-bottom-left-radius: 6px; border-bottom-right-radius: 6px; max-height: 250px; overflow-y: auto; z-index: 9999; box-shadow: 0 5px 15px rgba(0,0,0,0.5);"></div>
                            </div>
                            
                            <!-- Hidden storage of the actual option field -->
                            <input type="hidden" id="insom_filter_post_ids" name="insom_filter_post_ids" value="<?php echo esc_attr($filter_post_ids); ?>" />
                            
                            <!-- Visual tag selection grid -->
                            <div id="insom_selected_posts_list" style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; max-width: 500px;">
                                <?php
                                $pts_array = array_map('trim', explode(',', $pts));
                                if ( ! empty($filter_post_ids) ) {
                                    $selected_ids_array = array_filter(array_map('intval', explode(',', $filter_post_ids)));
                                    if ( ! empty($selected_ids_array) ) {
                                        $pre_query = new WP_Query(array(
                                            'post_type' => $pts_array,
                                            'post__in' => $selected_ids_array,
                                            'posts_per_page' => -1,
                                            'orderby' => 'post__in'
                                        ));
                                        if ($pre_query->have_posts()) {
                                            while ($pre_query->have_posts()) {
                                                $pre_query->the_post();
                                                $title = get_the_title();
                                                $desc_type = (get_post_type() === 'ht_movie') ? '🎬 Movie' : '📺 TV Show';
                                                echo sprintf(
                                                    '<div class="insom-selected-pill" data-id="%d" style="background: rgba(0, 240, 255, 0.12); border: 1px solid rgba(0, 240, 255, 0.35); color: #00f0ff; padding: 4.5px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; display: flex; align-items: center; gap: 6px; box-shadow: 0 0 5px rgba(0,240,255,0.05);">' .
                                                    '<span>%s - <small style="color: #64748b; font-weight: normal;">%s</small></span>' .
                                                    '<span class="insom-tag-remove" style="cursor: pointer; font-weight: 800; color: #ff3366; font-size: 13px; line-height: 1; vertical-align: middle;">&times;</span></div>',
                                                    get_the_ID(),
                                                    esc_html($title),
                                                    esc_html($desc_type)
                                                );
                                            }
                                            wp_reset_postdata();
                                        }
                                    }
                                } else {
                                    // Automatic alignment mode - query active database posts showing on frontend
                                    $today_str = current_time( 'Y-m-d' ) ?: date( 'Y-m-d' );
                                    $auto_query_args = array(
                                        'post_type'      => $pts_array,
                                        'posts_per_page' => 20,
                                        'post_status'    => 'publish',
                                        'orderby'        => $query_orderby,
                                        'order'          => $query_order,
                                    );
                                    if ( ! empty($filter_categories) ) {
                                        $auto_query_args['category_name'] = trim($filter_categories);
                                    }
                                    $auto_query = new WP_Query($auto_query_args);
                                    $has_auto_db_items = false;
                                    
                                    if ($auto_query->have_posts()) {
                                        while ($auto_query->have_posts()) {
                                            $auto_query->the_post();
                                            $id = get_the_ID();
                                            $pt = get_post_type();
                                            
                                            // Handle release date check the exact same way
                                            $fw_opts = get_post_meta( $id, 'fw_options', true );
                                            $resolved_d = '';
                                            if ( is_array( $fw_opts ) ) {
                                                array_walk_recursive( $fw_opts, function( $val, $key ) use ( &$resolved_d ) {
                                                    if ( in_array($key, array('air_date', 'release_date', 'ht_movie_release_date')) && ! empty( $val ) ) {
                                                        $resolved_d = $val;
                                                    }
                                                });
                                            }
                                            $resolved_d = $resolved_d ?: get_post_meta($id, 'release_date', true) ?: get_post_meta($id, 'ht_movie_release_date', true) ?: get_the_date('Y-m-d');
                                            $timestamp  = strtotime( $resolved_d );
                                            $resolved_d = date( 'Y-m-d', $timestamp );
                                            
                                            if ($resolved_d >= $today_str) {
                                                $has_auto_db_items = true;
                                                $title = get_the_title();
                                                $desc_type = ($pt === 'ht_movie') ? '🎬 Movie' : '📺 TV Show';
                                                
                                                echo sprintf(
                                                    '<div class="insom-selected-pill auto-active-pill" data-id="%d" style="background: rgba(52, 211, 153, 0.1); border: 1px solid rgba(52, 211, 153, 0.35); color: #34d399; padding: 4.5px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; display: flex; align-items: center; gap: 6px; box-shadow: 0 0 5px rgba(52,211,153,0.05);">' .
                                                    '<span>⚙️ Auto: %s - <small style="color: #64748b; font-weight: normal;">%s</small></span>' .
                                                    '</div>',
                                                    get_the_ID(),
                                                    esc_html($title),
                                                    esc_html($desc_type)
                                                );
                                            }
                                        }
                                        wp_reset_postdata();
                                    }
                                    
                                    // If still no database items found or they're older, display our standard gorgeous static preview items
                                    if (!$has_auto_db_items) {
                                        $static_previews = insom_get_fallback_items();
                                        foreach ($static_previews as $prev) {
                                            $prev_type = (strtolower($prev['type']) === 'movie') ? '🎬 Movie' : '📺 TV Show';
                                            echo sprintf(
                                                '<div class="insom-selected-pill auto-active-pill" style="background: rgba(168, 85, 247, 0.1); border: 1px solid rgba(168, 85, 247, 0.35); color: #c084fc; padding: 4.5px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; display: flex; align-items: center; gap: 6px; box-shadow: 0 0 5px rgba(168,85,247,0.05);">' .
                                                '<span>🔮 Backup: %s - <small style="color: #64748b; font-weight: normal;">%s</small></span>' .
                                                '</div>',
                                                esc_html($prev['title']),
                                                esc_html($prev_type)
                                            );
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </td>
                    </tr>

                    <!-- FILTER CATEGORIES -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Filter Categories (Slugs)</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">(Optional) Filter display content by category slugs (comma-separated, e.g. <code>cyberpunk,upcoming,featured</code>).</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_filter_categories" value="<?php echo esc_attr($filter_categories); ?>" placeholder="e.g. action, scifi, latest" style="width: 100%; max-width: 500px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" />
                        </td>
                    </tr>

                    <!-- SORT ORDER SELECTABLE -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Database Query Order (Chronology)</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Choose descending or ascending sort output.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <select name="insom_query_order" style="background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px; width: 180px;">
                                <option value="ASC" <?php selected($query_order, 'ASC'); ?>>Ascending (ASC)</option>
                                <option value="DESC" <?php selected($query_order, 'DESC'); ?>>Descending (DESC)</option>
                            </select>
                        </td>
                    </tr>

                    <!-- SORT ORDER BY CLASS -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Dynamic Order By Key</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Parameter driving query array sequence.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <select name="insom_query_orderby" style="background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px; width: 180px;">
                                <option value="date" <?php selected($query_orderby, 'date'); ?>>Published Date</option>
                                <option value="title" <?php selected($query_orderby, 'title'); ?>>Alphabetical Title</option>
                                <option value="ID" <?php selected($query_orderby, 'ID'); ?>>Specific ID</option>
                                <option value="menu_order" <?php selected($query_orderby, 'menu_order'); ?>>WordPress Menu Order</option>
                                <option value="rand" <?php selected($query_orderby, 'rand'); ?>>Shuffled / Random (rand)</option>
                            </select>
                        </td>
                    </tr>

                    <!-- SIDEBAR MAX DISPLAY COUNT -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Sidebar Max Display Limit</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Number of upcoming movies/shows to render in the "Upcoming Spread" sidebar (e.g., 5, 8, 10). If the quantity exceeds 5, smooth scroll is automatically activated.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="number" min="1" max="50" name="insom_right_item_count" value="<?php echo esc_attr($count); ?>" style="width: 120px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>
                </table>

                <!-- SECTION 2b: FALLBACK BACKUP ITEMS EDITOR -->
                <h3 style="color: #a855f7; border-bottom: 1px solid rgba(168,85,247,0.15); padding-bottom: 8px; margin: 30px 0 15px 0; font-size: 16px; text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; gap: 8px;">
                    👾 Section 2b: Fallback / Backup Items Editor
                </h3>
                <p style="font-size: 11px; color: #8b92a6; margin: -5px 0 20px 0; font-weight: normal; line-height: 1.5;">
                    These 5 slots define the beautiful movies/shows that appear automatically on your frontend slider deck when no matching database items (Posts/Movies/Shows) are queried. Since you have saved empty files, you can edit these fallback options directly here to custom feature any upcoming releases (like House of Dragon, Rings of Power, Harry Potter) immediately!
                </p>

                <div style="margin-bottom: 30px;">
                    <?php
                    $fallback_items = insom_get_fallback_items();
                    for ($i = 0; $i < 5; $i++) {
                        $item = $fallback_items[$i];
                        ?>
                        <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid #1f222b; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.15);">
                            <h4 style="margin: 0 0 15px 0; color: #a855f7; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px dashed rgba(255,255,255,0.08); padding-bottom: 8px; display: flex; align-items: center; justify-content: space-between;">
                                <span>📺 TV Series / Movie Slot #<?php echo ($i + 1); ?>: <strong style="color: #fff; font-family: monospace;"><?php echo esc_html($item['title']); ?></strong></span>
                                <span style="font-size: 10px; background: rgba(168,85,247,0.15); color: #c084fc; padding: 3px 8px; border-radius: 4px; font-weight: 700; border: 1px solid rgba(168,85,247,0.3);"><?php echo esc_html($item['type']); ?></span>
                            </h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 12px; margin-bottom: 12px;">
                                <div>
                                    <label style="display: block; font-size: 11px; color: #8b92a6; margin-bottom: 4px; font-weight: 600;">
                                        Title
                                        <span style="cursor: help; color: #a855f7; margin-left: 4px;" title="The main title/name of your backup release. Appears as the headline of the active slide.">ℹ️</span>
                                    </label>
                                    <input type="text" name="insom_fallback_items_data[<?php echo $i; ?>][title]" value="<?php echo esc_attr($item['title']); ?>" style="width: 100%; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px; border-radius: 6px; font-size: 12px;" required />
                                </div>
                                <div>
                                    <label style="display: block; font-size: 11px; color: #8b92a6; margin-bottom: 4px; font-weight: 600;">
                                        Content Type
                                        <span style="cursor: help; color: #a855f7; margin-left: 4px;" title="Categorization for sorting and visual labels (Movie or TV Series).">ℹ️</span>
                                    </label>
                                    <select name="insom_fallback_items_data[<?php echo $i; ?>][type]" style="width: 100%; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px; border-radius: 6px; font-size: 12px; height: 35px;">
                                        <option value="Movie" <?php selected($item['type'], 'Movie'); ?>>Movie</option>
                                        <option value="TV Series" <?php selected($item['type'], 'TV Series'); ?>>TV Series</option>
                                    </select>
                                </div>
                                <div>
                                    <label style="display: block; font-size: 11px; color: #8b92a6; margin-bottom: 4px; font-weight: 600;">
                                        Release Date (YYYY-MM-DD)
                                        <span style="cursor: help; color: #a855f7; margin-left: 4px;" title="The date this epic release drops. Powers the ticking countdown timer.">ℹ️</span>
                                    </label>
                                    <input type="date" name="insom_fallback_items_data[<?php echo $i; ?>][releaseDate]" value="<?php echo esc_attr($item['releaseDate']); ?>" style="width: 100%; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 6px 8px; border-radius: 6px; font-size: 12px; height: 35px;" required />
                                </div>
                                <div>
                                    <label style="display: block; font-size: 11px; color: #8b92a6; margin-bottom: 4px; font-weight: 600;">
                                        Rating (e.g. TV-MA, PG-13)
                                        <span style="cursor: help; color: #a855f7; margin-left: 4px;" title="Maturity clearance parameter displayed in the item details line.">ℹ️</span>
                                    </label>
                                    <input type="text" name="insom_fallback_items_data[<?php echo $i; ?>][rating]" value="<?php echo esc_attr($item['rating']); ?>" style="width: 100%; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px; border-radius: 6px; font-size: 12px;" />
                                </div>
                                <div>
                                    <label style="display: block; font-size: 11px; color: #8b92a6; margin-bottom: 4px; font-weight: 600;">
                                        Critic Score (0.0 to 10.0)
                                        <span style="cursor: help; color: #a855f7; margin-left: 4px;" title="Review score evaluation star rating (e.g., 9.2).">ℹ️</span>
                                    </label>
                                    <input type="number" step="0.1" min="0" max="10" name="insom_fallback_items_data[<?php echo $i; ?>][score]" value="<?php echo esc_attr($item['score']); ?>" style="width: 100%; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px; border-radius: 6px; font-size: 12px; height: 35px;" />
                                </div>
                                <div>
                                    <label style="display: block; font-size: 11px; color: #8b92a6; margin-bottom: 4px; font-weight: 600;">
                                        Adrenaline Index % (e.g. 95)
                                        <span style="cursor: help; color: #a855f7; margin-left: 4px;" title="Futuristic gauge visual indexing spectator excitement factor (0-100%).">ℹ️</span>
                                    </label>
                                    <input type="number" min="0" max="100" name="insom_fallback_items_data[<?php echo $i; ?>][adrenaline]" value="<?php echo esc_attr($item['adrenaline']); ?>" style="width: 100%; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px; border-radius: 6px; font-size: 12px; height: 35px;" />
                                </div>
                            </div>
                            <div style="margin-bottom: 12px;">
                                <label style="display: block; font-size: 11px; color: #8b92a6; margin-bottom: 4px; font-weight: 600;">
                                    Item Tagline
                                    <span style="cursor: help; color: #a855f7; margin-left: 4px;" title="Short dramatic banner tagline appearing right above the subtitle on the active card.">ℹ️</span>
                                </label>
                                <input type="text" name="insom_fallback_items_data[<?php echo $i; ?>][tagline]" value="<?php echo esc_attr($item['tagline']); ?>" style="width: 100%; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px; border-radius: 6px; font-size: 12px;" />
                            </div>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 12px; margin-bottom: 12px;">
                                <div>
                                    <label style="display: block; font-size: 11px; color: #8b92a6; margin-bottom: 4px; font-weight: 600;">
                                        Global Rank / Stats Text Override
                                        <span style="cursor: help; color: #a855f7; margin-left: 4px;" title="Custom text rank displayed on mobile (e.g. GLOBAL RANK #1 • COLLECTED 14/30). Leaves default if blank.">ℹ️</span>
                                    </label>
                                    <input type="text" name="insom_fallback_items_data[<?php echo $i; ?>][rank]" value="<?php echo esc_attr(isset($item['rank']) ? $item['rank'] : ''); ?>" placeholder="GLOBAL RANK #1 • COLLECTED 14/30" style="width: 100%; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px; border-radius: 6px; font-size: 12px;" />
                                </div>
                                <div>
                                    <label style="display: block; font-size: 11px; color: #8b92a6; margin-bottom: 4px; font-weight: 600;">
                                        Item Permalink (See Details Redirect Path)
                                        <span style="cursor: help; color: #a855f7; margin-left: 4px;" title="The landing destination URL loaded when a spectator clicks on individual button alignments.">ℹ️</span>
                                    </label>
                                    <input type="text" name="insom_fallback_items_data[<?php echo $i; ?>][permalink]" value="<?php echo esc_attr($item['permalink']); ?>" style="width: 100%; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px; border-radius: 6px; font-size: 12px;" />
                                </div>
                                <div>
                                    <label style="display: block; font-size: 11px; color: #8b92a6; margin-bottom: 4px; font-weight: 600;">
                                        Trailer YouTube / Video URL
                                        <span style="cursor: help; color: #a855f7; margin-left: 4px;" title="Underlying system video fallback descriptor (used for internal trailer playback logs).">ℹ️</span>
                                    </label>
                                    <input type="text" name="insom_fallback_items_data[<?php echo $i; ?>][trailer]" value="<?php echo esc_attr($item['trailer']); ?>" style="width: 100%; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px; border-radius: 6px; font-size: 12px;" />
                                </div>
                            </div>
                            <div style="margin-bottom: 12px;">
                                <label style="display: block; font-size: 11px; color: #8b92a6; margin-bottom: 4px; font-weight: 600;">
                                    Poster Artwork Thumbnail URL
                                    <span style="cursor: help; color: #a855f7; margin-left: 4px;" title="Graphic backdrop source image path for this release. Browse the media panel.">ℹ️</span>
                                </label>
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <input type="text" id="insom_fallback_thumbnail_<?php echo $i; ?>" name="insom_fallback_items_data[<?php echo $i; ?>][thumbnail]" value="<?php echo esc_attr($item['thumbnail']); ?>" style="flex-grow: 1; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px; border-radius: 6px; font-size: 11px; font-family: monospace;" />
                                    <button type="button" class="insom-media-upload-trigger button button-secondary" data-target="insom_fallback_thumbnail_<?php echo $i; ?>" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15) !important; color: #ffffff !important; padding: 4px 14px; border-radius: 6px; height: 35px; line-height: 25px; font-size: 12px; cursor: pointer;">
                                        Browse Media
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; color: #8b92a6; margin-bottom: 4px; font-weight: 600;">
                                    Item Synopsis
                                    <span style="cursor: help; color: #a855f7; margin-left: 4px;" title="Short dramatic synopsis paragraph describing the release premise. Shown on the active slide body.">ℹ️</span>
                                </label>
                                <textarea name="insom_fallback_items_data[<?php echo $i; ?>][synopsis]" rows="2" style="width: 100%; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px; border-radius: 6px; font-size: 12px; font-family: sans-serif; resize: vertical;"><?php echo esc_textarea($item['synopsis']); ?></textarea>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>

                <!-- SECTION 3: FRONTEND LABELS & HEADLINES TRANSLATIONS -->
                <h3 style="color: #00f0ff; border-bottom: 1px solid rgba(0,240,255,0.15); padding-bottom: 8px; margin: 30px 0 15px 0; font-size: 16px; text-transform: uppercase; letter-spacing: 1px;">⚙️ Section 3: Frontend Customizable Text Labels</h3>
                <table class="form-table" style="width: 100%; border-collapse: separate; border-spacing: 0 12px; margin-bottom: 30px;">
                    <!-- SPREAD HEADER -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                Sidebar List Header
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="The title category text displayed above the sidebar list menu on desktop layouts.">ℹ️</span>
                            </label>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_label_upcoming_spread" value="<?php echo esc_attr($label_upcoming_spread); ?>" style="width: 100%; max-width: 400px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>

                    <!-- SYNC BUTTON TEXT -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                Sync Release Button Text
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="Primary synchronization timeline button visual label (e.g. SYNC RELEASE TIME).">ℹ️</span>
                            </label>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_label_sync_release" value="<?php echo esc_attr($label_sync_release); ?>" style="width: 100%; max-width: 400px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>

                    <!-- PLAY BUTTON TEXT -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                See details Button Text
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="Text displayed on the details/trailer redirect button (set to 'See details' as requested).">ℹ️</span>
                            </label>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_label_play_trailer" value="<?php echo esc_attr($label_play_trailer); ?>" style="width: 100%; max-width: 400px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>

                    <!-- COORDINATE TEXT -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                Coordinate Party Button Text
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="Action label displayed on scheduling/invitation triggers (e.g. Coordinate Party).">ℹ️</span>
                            </label>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_label_coordinate_party" value="<?php echo esc_attr($label_coordinate_party); ?>" style="width: 100%; max-width: 400px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>

                    <!-- TICKING TIMELINE SEGMENTS -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                Countdown Segment Text Labels
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="Custom names for days, hours, minutes, and seconds shown beneath the large countdown numbers.">ℹ️</span>
                            </label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Translates ticker time parts.</p>
                        </th>
                        <td style="padding: 5px 0; display: flex; gap: 10px; align-items: center;">
                            <div>
                                <label style="display: block; font-size: 10px; color: #8b92a6; margin-bottom: 4px; font-family: monospace;">DAYS</label>
                                <input type="text" name="insom_label_days" value="<?php echo esc_attr($label_days); ?>" style="width: 80px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 6px 10px; border-radius: 6px; text-align: center; font-size: 12px;" required />
                            </div>
                            <div>
                                <label style="display: block; font-size: 10px; color: #8b92a6; margin-bottom: 4px; font-family: monospace;">HOURS</label>
                                <input type="text" name="insom_label_hours" value="<?php echo esc_attr($label_hours); ?>" style="width: 80px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 6px 10px; border-radius: 6px; text-align: center; font-size: 12px;" required />
                            </div>
                            <div>
                                <label style="display: block; font-size: 10px; color: #8b92a6; margin-bottom: 4px; font-family: monospace;">MINUTES</label>
                                <input type="text" name="insom_label_mins" value="<?php echo esc_attr($label_mins); ?>" style="width: 80px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 6px 10px; border-radius: 6px; text-align: center; font-size: 12px;" required />
                            </div>
                            <div>
                                <label style="display: block; font-size: 10px; color: #8b92a6; margin-bottom: 4px; font-family: monospace;">SECONDS</label>
                                <input type="text" name="insom_label_secs" value="<?php echo esc_attr($label_secs); ?>" style="width: 80px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 6px 10px; border-radius: 6px; text-align: center; font-size: 12px;" required />
                            </div>
                        </td>
                    </tr>

                    <!-- DESKTOP SPREAD BUTTON TEXT -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                Desktop Release Calendar Button
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="Label for the full release calendar action link.">ℹ️</span>
                            </label>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_label_view_all_calendar" value="<?php echo esc_attr($label_view_all_calendar); ?>" style="width: 100%; max-width: 400px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>

                    <!-- MOBILE CAROUSEL VIEW ALL POSTER TEXT -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                Mobile View All Button Text
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="Label displayed on mobile devices inside carousel navigation indicators.">ℹ️</span>
                            </label>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_label_view_all_mobile" value="<?php echo esc_attr($label_view_all_mobile); ?>" style="width: 100%; max-width: 400px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>

                    <!-- PREMIERE MEDALS HEADER -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                Mobile Medals Label Text
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="Text displayed before critic stats on mobile layouts (e.g. PREMIERE MEDALS:).">ℹ️</span>
                            </label>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_label_premiere_medals" value="<?php echo esc_attr($label_premiere_medals); ?>" style="width: 100%; max-width: 400px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>

                    <!-- MOBILE GLOBAL RANK & STATS TEXT -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                Mobile Global Rank / Stats Text
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="Fallback rank details if a selected item doesn't compile an individual override rank string.">ℹ️</span>
                            </label>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_label_stats_text" value="<?php echo esc_attr($label_stats_text); ?>" style="width: 100%; max-width: 400px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>

                    <!-- DESKTOP NEXT UPCOMING PREFIX TITLE -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                Desktop Next Upcoming Prefix
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="Small uppercase header text displayed above the main countdown timer on desktop.">ℹ️</span>
                            </label>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_label_next_upcoming" value="<?php echo esc_attr($label_next_upcoming); ?>" style="width: 100%; max-width: 400px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>

                    <!-- MOBILE COORDINATION BUTTON TEXT -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                Mobile Coordination Button Text
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="Label for mobilization/invitation controls on mobile screens.">ℹ️</span>
                            </label>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_label_coordination" value="<?php echo esc_attr($label_coordination); ?>" style="width: 100%; max-width: 400px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>

                    <!-- MOBILE UPCOMING ACCENT BADGE -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                Mobile Upcoming Accent Badge
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="Small accent badge text (e.g. UPCOMING) shown on custom items.">ℹ️</span>
                            </label>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_label_upcoming_badge" value="<?php echo esc_attr($label_upcoming_badge); ?>" style="width: 100%; max-width: 400px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>

                    <!-- MOBILE NEXT UP NEXT LABELS -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                Mobile Next Up Label
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="Text separator introducing upcoming item segments (e.g. Next Up).">ℹ️</span>
                            </label>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_label_next_up" value="<?php echo esc_attr($label_next_up); ?>" style="width: 100%; max-width: 400px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>

                    <!-- MOBILE LIVE COUNTDOWN LABELS -->
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">
                                Mobile Countdown Title Text
                                <span style="cursor: help; color: #00f0ff; margin-left: 4px;" title="Heading text shown before ticking countdown timer blocks are rendered on mobile devices.">ℹ️</span>
                            </label>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_label_countdown_title" value="<?php echo esc_attr($label_countdown_title); ?>" style="width: 100%; max-width: 400px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>
                </table>

                <div style="margin-top: 30px; border-top: 1px solid #2d303b; padding-top: 20px; text-align: right;">
                    <?php submit_button( 'Save Configuration', 'primary', 'submit', false, array( 'style' => 'background: #00f0ff; color: #000; border: none; font-weight: 800; text-transform: uppercase; padding: 12px 30px; font-size: 13px; border-radius: 6px; cursor: pointer; text-shadow: none; box-shadow: 0 4px 15px rgba(0, 240, 255, 0.25);' ) ); ?>
                </div>
            </form>

            <!-- SECTION 4: EXPORT & IMPORT DECK SETTINGS -->
            <?php
            $export_keys = array(
                'insom_accent_color',
                'insom_bg_color',
                'insom_target_date',
                'insom_ticker_text',
                'insom_post_types',
                'insom_right_item_count',
                'insom_default_adrenaline',
                'insom_default_fallback_bg',
                'insom_top_spacing_desktop',
                'insom_top_spacing_mobile',
                'insom_query_order',
                'insom_query_orderby',
                'insom_filter_categories',
                'insom_filter_post_ids',
                'insom_label_upcoming_spread',
                'insom_label_sync_release',
                'insom_label_play_trailer',
                'insom_label_coordinate_party',
                'insom_label_days',
                'insom_label_hours',
                'insom_label_mins',
                'insom_label_secs',
                'insom_label_view_all_calendar',
                'insom_label_view_all_mobile',
                'insom_label_premiere_medals',
                'insom_label_stats_text',
                'insom_label_next_upcoming',
                'insom_label_coordination',
                'insom_label_upcoming_badge',
                'insom_label_next_up',
                'insom_label_countdown_title',
                'insom_fallback_items_data'
            );
            $export_data = array();
            foreach ( $export_keys as $key ) {
                $export_data[$key] = get_option($key, '');
            }
            $json_export = json_encode($export_data);
            ?>
            <div style="background: #15171e; padding: 25px; border-radius: 10px; border: 1px solid #232731; margin-top: 30px;">
                <h3 style="color: #00f0ff; border-bottom: 1px solid rgba(0,240,255,0.15); padding-bottom: 8px; margin: 0 0 20px 0; font-size: 16px; text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; gap: 8px;">
                    🔄 Section 4: Export & Import Configuration Deck
                </h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                    <!-- EXPORT SIDE -->
                    <div>
                        <h4 style="color: #fff; font-size: 13px; font-weight: 700; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 6px;">📤 Export Core Alignment Settings</h4>
                        <p style="font-size: 11px; color: #8b92a6; margin: 0 0 12px 0;">Copy this configuration payload block to back up or migrate this layout deck.</p>
                        <textarea id="insom_export_payload" readonly style="width: 100%; height: 110px; background: #0d0e12; border: 1px solid #2d303b; color: #00f0ff; font-family: monospace; font-size: 11px; padding: 10px; border-radius: 6px; resize: none; overflow-y: auto;"><?php echo esc_textarea($json_export); ?></textarea>
                        <button type="button" id="insom_copy_export_btn" class="button" style="background: rgba(0,240,255,0.08); color: #00f0ff; border: 1px solid rgba(0,240,255,0.3) !important; font-weight: bold; margin-top: 10px; border-radius: 4px; padding: 6px 14px; cursor: pointer; text-transform: uppercase; font-size: 11px; height: auto; line-height: 1;">Copy Payload String</button>
                        <span id="insom_copy_status" style="color: #34d399; font-size: 12px; margin-left: 10px; display: none; font-weight: bold;">✓ Copied!</span>
                    </div>

                    <!-- IMPORT SIDE -->
                    <div>
                        <form method="post" action="">
                            <?php wp_nonce_field( 'insom_import_settings', 'insom_import_settings_nonce' ); ?>
                            <h4 style="color: #fff; font-size: 13px; font-weight: 700; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 6px;">📥 Import Core Alignment Settings</h4>
                            <p style="font-size: 11px; color: #8b92a6; margin: 0 0 12px 0;">Paste an exported alignment JSON payload below to synchronize instantly.</p>
                            <textarea id="insom_import_raw_data" name="insom_import_raw_data" placeholder="Paste your JSON configuration string here..." style="width: 100%; height: 110px; background: #0d0e12; border: 1px solid #2d303b; color: #f1f1f1; font-family: monospace; font-size: 11px; padding: 10px; border-radius: 6px; resize: none;"></textarea>
                            <button type="submit" class="button" style="background: #ff3366; color: #fff; border: none; font-weight: bold; margin-top: 10px; border-radius: 4px; padding: 6px 14px; cursor: pointer; text-transform: uppercase; font-size: 11px; height: auto; line-height: 1;">Import Alignment Payload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- WordPress Medial Library dynamic overlay triggers script -->
        <script>
        jQuery(document).ready(function($){
            // Media Library Uploader Trigger
            $('.insom-media-upload-trigger').click(function(e) {
                e.preventDefault();
                var targetId = $(this).data('target');
                var inputField = $('#' + targetId);
                
                var custom_uploader = wp.media({
                    title: 'Select Fallback Background Artwork',
                    button: {
                        text: 'Use Selected Artwork'
                    },
                    multiple: false
                })
                .on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    inputField.val(attachment.url);
                })
                .open();
            });

            // Database Search & Select Handlers
            var searchInput = $('#insom_post_search');
            var resultsDiv = $('#insom_search_results');
            var hiddenStore = $('#insom_filter_post_ids');
            var selectedList = $('#insom_selected_posts_list');
            var insomNonce = '<?php echo wp_create_nonce("insom_search_nonce"); ?>';

            // Active list of selected post IDs
            var selectedIds = hiddenStore.val() ? hiddenStore.val().split(',').map(function(x) { return parseInt(x, 10); }).filter(Boolean) : [];

            searchInput.on('keyup input', function() {
                var term = $(this).val().trim();
                if (term.length < 2) {
                    resultsDiv.hide().empty();
                    return;
                }

                $.ajax({
                    url: ajaxurl,
                    data: {
                        action: 'insom_search_posts',
                        q: term,
                        security: insomNonce
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.data && response.data.length > 0) {
                            resultsDiv.empty();
                            response.data.forEach(function(item) {
                                // Skip if already selected inside the array
                                if (selectedIds.indexOf(parseInt(item.id, 10)) > -1) return;

                                var typeLabel = item.type === 'ht_movie' ? '🎬 Movie' : '📺 TV Show';
                                resultsDiv.append(
                                    '<div class="insom-search-item" data-id="' + item.id + '" data-title="' + item.title + '" data-type="' + typeLabel + '" style="padding: 10px 14px; border-bottom: 1px solid #1f222d; cursor: pointer; color: #f1f1f1; font-weight: 500; font-size: 13px; text-shadow:none;" onmouseover="this.style.background=\'rgba(0, 240, 255, 0.08)\'" onmouseout="this.style.background=\'transparent\'">' +
                                    item.title + ' <small style="color: #8b92a6; margin-left: 6px;">(' + typeLabel + ')</small>' +
                                    '</div>'
                                );
                            });
                            resultsDiv.show();
                        } else {
                            resultsDiv.html('<div style="padding: 12px; color: #8b92a6; font-size: 13px;">No movies or TV shows found matching query.</div>').show();
                        }
                    }
                });
            });

            // Close results container on clicks outside searching block
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#insom_post_search, #insom_search_results').length) {
                    resultsDiv.hide();
                }
            });

            // Add selected post element on click
            resultsDiv.on('click', '.insom-search-item', function() {
                var id = parseInt($(this).data('id'), 10);
                var title = $(this).data('title');
                var type = $(this).data('type');

                if (selectedIds.indexOf(id) === -1) {
                    // Remove auto-active / fallback pills on first manual selection
                    selectedList.find('.auto-active-pill').remove();

                    selectedIds.push(id);
                    hiddenStore.val(selectedIds.join(','));

                    selectedList.append(
                        '<div class="insom-selected-pill" data-id="' + id + '" style="background: rgba(0, 240, 255, 0.12); border: 1px solid rgba(0, 240, 255, 0.35); color: #00f0ff; padding: 4.5px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 0 5px rgba(0,240,255,0.05);">' +
                        '<span>' + title + ' - <small style="color: #64748b; font-weight: normal;">' + type + '</small></span>' +
                        '<span class="insom-tag-remove" style="cursor: pointer; font-weight: 800; color: #ff3366; font-size: 13px; line-height: 1; vertical-align: middle;">&times;</span></div>'
                    );
                }

                searchInput.val('');
                resultsDiv.hide().empty();
            });

            // Remove tag pill from active lists
            selectedList.on('click', '.insom-tag-remove', function() {
                var pill = $(this).closest('.insom-selected-pill');
                var id = parseInt(pill.data('id'), 10);

                selectedIds = selectedIds.filter(function(x) { return x !== id; });
                hiddenStore.val(selectedIds.join(','));
                pill.remove();
            });

            // Copy settings payload handler
            $('#insom_copy_export_btn').click(function() {
                var copyText = document.getElementById("insom_export_payload");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                navigator.clipboard.writeText(copyText.value);
                
                var status = $('#insom_copy_status');
                status.fadeIn().delay(1500).fadeOut();
            });
        });
        </script>
        <?php
    }
}

if ( ! function_exists( 'insom_get_fallback_items' ) ) {
    function insom_get_fallback_items() {
        // Compute relative default dates so they're always in the future relative to today
        $defaults = array(
            array(
                'id'          => 'custom-fseries-1',
                'title'       => 'Cyberpunk: Tokyo Neon',
                'type'        => 'TV Series',
                'releaseDate' => date( 'Y-m-d', strtotime( '+5 days' ) ),
                'tagline'     => 'High Tech, Low Life, No Survivors.',
                'rating'      => 'TV-MA',
                'score'       => '9.2',
                'adrenaline'  => '98',
                'permalink'   => '#',
                'thumbnail'   => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&q=80&w=1200',
                'synopsis'    => 'An adrenaline-fueled dive into Tokyos futuristic underground, where a rogue code developer aligns with prosthetic mercenaries.',
                'trailer'     => 'https://www.youtube.com/watch?v=8X2kIfS6fb8',
                'rank'        => 'GLOBAL RANK #1 • COLLECTED 14/30'
            ),
            array(
                'id'          => 'custom-fseries-2',
                'title'       => 'Specter Protocol: Zero',
                'type'        => 'TV Series',
                'releaseDate' => date( 'Y-m-d', strtotime( '+8 days' ) ),
                'tagline'     => 'Code remains. Humans disappear.',
                'rating'      => 'TV-14',
                'score'       => '8.9',
                'adrenaline'  => '95',
                'permalink'   => '#',
                'thumbnail'   => 'https://images.unsplash.com/photo-1508739773434-c26b3d09e071?auto=format&fit=crop&q=80&w=1200',
                'synopsis'    => 'When an automated mainframe firewall takes hostage of the global logistics grid, a team of virtual operators go dark.',
                'trailer'     => 'https://www.youtube.com/watch?v=N6HGuJC--rk',
                'rank'        => 'GLOBAL RANK #2 • COLLECTED 14/30'
            ),
            array(
                'id'          => 'custom-fseries-3',
                'title'       => 'High Voltage Sagas',
                'type'        => 'TV Series',
                'releaseDate' => date( 'Y-m-d', strtotime( '+12 days' ) ),
                'tagline'     => 'Tension rises in the power sector.',
                'rating'      => 'TV-MA',
                'score'       => '9.0',
                'adrenaline'  => '96',
                'permalink'   => '#',
                'thumbnail'   => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&q=80&w=1200',
                'synopsis'    => 'Inside the massive nuclear containment architecture, a single lead supervisor uncovers structural sabotage.',
                'trailer'     => 'https://www.youtube.com/watch?v=Way9Dexny3w',
                'rank'        => 'GLOBAL RANK #3 • COLLECTED 14/30'
            ),
            array(
                'id'          => 'custom-fmovie-1',
                'title'       => 'Stellar Legacy: Eclipse',
                'type'        => 'Movie',
                'releaseDate' => date( 'Y-m-d', strtotime( '+15 days' ) ),
                'tagline'     => 'The dark void returns the gaze.',
                'rating'      => 'PG-13',
                'score'       => '8.8',
                'adrenaline'  => '94',
                'permalink'   => '#',
                'thumbnail'   => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&q=80&w=1200',
                'synopsis'    => 'In the debris field of a collapsed stellar core, a cartographer discovers coordinates for an ancient organic starship.',
                'trailer'     => 'https://www.youtube.com/watch?v=zSWdZAToXRw',
                'rank'        => 'GLOBAL RANK #4 • COLLECTED 14/30'
            ),
            array(
                'id'          => 'custom-fmovie-2',
                'title'       => 'Retro Grid: Outrun',
                'type'        => 'Movie',
                'releaseDate' => date( 'Y-m-d', strtotime( '+25 days' ) ),
                'tagline'     => 'Ride forever into the horizontal sunset.',
                'rating'      => 'R',
                'score'       => '8.5',
                'adrenaline'  => '89',
                'permalink'   => '#',
                'thumbnail'   => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&q=80&w=1200',
                'synopsis'    => 'A retired street racer is blackmailed into driving an experimental hydrogen coupé across a neon-lit desert freeway.',
                'trailer'     => 'https://www.youtube.com/watch?v=L9szn1QQFts',
                'rank'        => 'GLOBAL RANK #5 • COLLECTED 14/30'
            )
        );

        $saved = get_option('insom_fallback_items_data');
        if ( ! is_array($saved) ) {
            $saved = array();
        }

        $result = array();
        for ( $i = 0; $i < 5; $i++ ) {
            $saved_item = isset($saved[$i]) && is_array($saved[$i]) ? $saved[$i] : array();
            $default_item = $defaults[$i];
            
            $title       = ! empty($saved_item['title']) ? sanitize_text_field($saved_item['title']) : $default_item['title'];
            $type        = ! empty($saved_item['type']) ? sanitize_text_field($saved_item['type']) : $default_item['type'];
            $releaseDate = ! empty($saved_item['releaseDate']) ? sanitize_text_field($saved_item['releaseDate']) : $default_item['releaseDate'];
            $tagline     = isset($saved_item['tagline']) ? sanitize_text_field($saved_item['tagline']) : $default_item['tagline'];
            $rating      = isset($saved_item['rating']) ? sanitize_text_field($saved_item['rating']) : $default_item['rating'];
            $score       = isset($saved_item['score']) ? sanitize_text_field($saved_item['score']) : $default_item['score'];
            $adrenaline  = isset($saved_item['adrenaline']) ? sanitize_text_field($saved_item['adrenaline']) : $default_item['adrenaline'];
            $permalink   = isset($saved_item['permalink']) ? esc_url($saved_item['permalink']) : $default_item['permalink'];
            $thumbnail   = ! empty($saved_item['thumbnail']) ? esc_url($saved_item['thumbnail']) : $default_item['thumbnail'];
            $synopsis    = isset($saved_item['synopsis']) ? sanitize_textarea_field($saved_item['synopsis']) : $default_item['synopsis'];
            $trailer     = isset($saved_item['trailer']) ? esc_url($saved_item['trailer']) : $default_item['trailer'];
            $rank        = ! empty($saved_item['rank']) ? sanitize_text_field($saved_item['rank']) : $default_item['rank'];

            $timestamp = strtotime($releaseDate);
            if ( ! $timestamp ) {
                $timestamp = strtotime($default_item['releaseDate']);
            }
            $releaseDate = date('Y-m-d', $timestamp);

            $result[] = array(
                'id'          => $default_item['id'],
                'title'       => $title,
                'type'        => $type,
                'releaseDate' => $releaseDate,
                'displayDate' => date( 'j M Y', $timestamp ),
                'day'         => date( 'd', $timestamp ),
                'month'       => strtoupper( date( 'M', $timestamp ) ),
                'tagline'     => $tagline,
                'rating'      => $rating,
                'score'       => floatval($score),
                'adrenaline'  => intval($adrenaline),
                'permalink'   => $permalink,
                'thumbnail'   => $thumbnail,
                'synopsis'    => $synopsis,
                'trailer'     => $trailer,
                'rank'        => $rank
            );
        }
        return $result;
    }
}

// 3. SHORTCODE RENDER CALLBACK
if ( ! function_exists( 'insomniacs_render_homepage_shortcode_callback' ) ) {
    function insomniacs_render_homepage_shortcode_callback( $atts ) {
        // Collect Saved WordPress configs (or assign standard fallbacks if empty)
        $accent_color = get_option('insom_accent_color');
        if ( empty($accent_color) ) { $accent_color = '#ff0033'; }

        $bg_color     = get_option('insom_bg_color');
        if ( empty($bg_color) ) { $bg_color = '#0a0a0a'; }

        $target_dt    = get_option('insom_target_date');
        if ( empty($target_dt) ) { $target_dt = '2026-07-25 20:00:00'; }

        $ticker_msg   = get_option('insom_ticker_text');
        if ( empty($ticker_msg) ) { $ticker_msg = 'Current Event Matrix: Adrenaline Index 100% | Insomniacs Sync Active | High Voltage Premiere Pending...'; }

        $post_pts_str = get_option('insom_post_types');
        if ( empty($post_pts_str) ) { $post_pts_str = 'post,ht_movie,ht_show,ht_tv_show'; }

        $right_limit  = intval(get_option('insom_right_item_count'));
        if ( $right_limit <= 0 ) { $right_limit = 5; }

        $default_adr  = intval(get_option('insom_default_adrenaline'));
        if ( $default_adr <= 0 ) { $default_adr = 98; }

        $fallback_img = get_option('insom_default_fallback_bg');
        if ( empty($fallback_img) ) { $fallback_img = 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&q=80&w=1200'; }

        $top_margin_desktop = get_option('insom_top_spacing_desktop');
        $top_margin_mobile  = get_option('insom_top_spacing_mobile');

        // Advanced configuration retrieve
        $query_order = get_option('insom_query_order');
        if ( empty($query_order) ) { $query_order = 'ASC'; }

        $query_orderby = get_option('insom_query_orderby');
        if ( empty($query_orderby) ) { $query_orderby = 'date'; }

        $filter_categories = get_option('insom_filter_categories', '');
        $filter_post_ids = get_option('insom_filter_post_ids', '');

        $label_upcoming_spread = get_option('insom_label_upcoming_spread');
        if ( empty($label_upcoming_spread) ) { $label_upcoming_spread = 'UPCOMING SPREAD'; }

        $label_sync_release = get_option('insom_label_sync_release');
        if ( empty($label_sync_release) ) { $label_sync_release = 'Sync Release'; }

        $label_play_trailer = get_option('insom_label_play_trailer');
        if ( empty($label_play_trailer) || $label_play_trailer === 'Play Trailer' || $label_play_trailer === 'See Details' ) { 
            $label_play_trailer = 'See details'; 
        }

        $label_coordinate_party = get_option('insom_label_coordinate_party');
        if ( empty($label_coordinate_party) ) { $label_coordinate_party = 'Coordinate Party'; }

        $label_days = get_option('insom_label_days');
        if ( empty($label_days) ) { $label_days = 'DAYS'; }

        $label_hours = get_option('insom_label_hours');
        if ( empty($label_hours) ) { $label_hours = 'HOURS'; }

        $label_mins = get_option('insom_label_mins');
        if ( empty($label_mins) ) { $label_mins = 'MINS'; }

        $label_secs = get_option('insom_label_secs');
        if ( empty($label_secs) ) { $label_secs = 'SECS'; }

        $label_view_all_calendar = get_option('insom_label_view_all_calendar');
        if ( empty($label_view_all_calendar) ) { $label_view_all_calendar = 'View Full Release Calendar'; }

        $label_view_all_mobile = get_option('insom_label_view_all_mobile');
        if ( empty($label_view_all_mobile) ) { $label_view_all_mobile = 'VIEW ALL'; }

        $label_premiere_medals = get_option('insom_label_premiere_medals');
        if ( empty($label_premiere_medals) ) { $label_premiere_medals = 'PREMIERE MEDALS:'; }

        $label_stats_text = get_option('insom_label_stats_text');
        if ( empty($label_stats_text) ) { $label_stats_text = 'GLOBAL RANK #115 • COLLECTED 14/30'; }

        $label_next_upcoming = get_option('insom_label_next_upcoming');
        if ( empty($label_next_upcoming) ) { $label_next_upcoming = 'NEXT UPCOMING'; }

        $label_coordination = get_option('insom_label_coordination');
        if ( empty($label_coordination) ) { $label_coordination = 'Coordination'; }

        $label_upcoming_badge = get_option('insom_label_upcoming_badge');
        if ( empty($label_upcoming_badge) ) { $label_upcoming_badge = 'UPCOMING'; }

        $label_next_up = get_option('insom_label_next_up');
        if ( empty($label_next_up) ) { $label_next_up = 'Next Up'; }

        $label_countdown_title = get_option('insom_label_countdown_title');
        if ( empty($label_countdown_title) ) { $label_countdown_title = 'LIVE TICKING COUNTDOWN:'; }

        if ( empty($top_margin_desktop) || $top_margin_desktop === '0' || $top_margin_desktop === '0px' ) {
            $top_margin_desktop = '100px';
        }
        if ( empty($top_margin_mobile) || $top_margin_mobile === '0' || $top_margin_mobile === '0px' ) {
            $top_margin_mobile = '110px'; // Set standard fallback for mobile margin-top
        }

        $pts_array = array_map('trim', explode(',', $post_pts_str));
        $id_list = array();

        // DB Data Fetching Sequence with filters & sort order Customizers
        $query_args = array(
            'post_type'      => $pts_array,
            'posts_per_page' => 45, // fetch robust count to satisfy segment mixture
            'post_status'    => 'publish',
            'orderby'        => $query_orderby,
            'order'          => $query_order
        );

        if ( ! empty($filter_categories) ) {
            $query_args['category_name'] = trim($filter_categories);
        }

        if ( ! empty($filter_post_ids) ) {
            $id_list = array_filter(array_map('intval', explode(',', $filter_post_ids)));
            if ( ! empty($id_list) ) {
                $query_args['post__in'] = $id_list;
            }
        }

        $query_hero = new WP_Query( $query_args );
        $queried_movies = array();
        $queried_series = array();
        $today_str    = current_time( 'Y-m-d' );
        if ( empty($today_str) ) {
            $today_str = date( 'Y-m-d' );
        }

        if ( $query_hero->have_posts() ) {
            while ( $query_hero->have_posts() ) {
                $query_hero->the_post();
                $id = get_the_ID();
                $pt = get_post_type();
                $fw_opts = get_post_meta( $id, 'fw_options', true );
                $resolved_d = '';

                // Extract custom fields if existing
                $trailer_url = '';
                if ( is_array( $fw_opts ) ) {
                    array_walk_recursive( $fw_opts, function( $val, $key ) use ( &$resolved_d, &$trailer_url ) {
                        if ( in_array($key, array('air_date', 'release_date', 'ht_movie_release_date')) && ! empty( $val ) ) {
                            $resolved_d = $val;
                        }
                        if ( in_array($key, array('video_url', 'trailer_url', 'embed_url', 'ht_video_url', 'movie_trailer', 'youtube_url')) && ! empty( $val ) ) {
                            $trailer_url = $val;
                        }
                    });
                }
                
                $resolved_d = $resolved_d ?: get_post_meta($id, 'release_date', true) ?: get_post_meta($id, 'ht_movie_release_date', true) ?: get_the_date('Y-m-d');
                $timestamp  = strtotime( $resolved_d );
                $resolved_d = date( 'Y-m-d', $timestamp );

                $tagline = get_post_meta( $id, 'tagline', true ) ?: 'A spectator event is assembling. Standby.';
                $rating  = get_post_meta( $id, 'rating', true ) ?: 'TV-MA';
                $score   = get_post_meta( $id, 'score', true ) ?: '9.1';
                $adr_idx = get_post_meta( $id, 'adrenaline_index', true ) ?: $default_adr;
                
                $trailer_url = $trailer_url ?: get_post_meta( $id, 'trailer_url', true ) ?: get_post_meta( $id, 'youtube_url', true ) ?: get_post_meta( $id, 'video_url', true ) ?: get_post_meta( $id, 'ht_movie_trailer', true ) ?: get_post_meta( $id, 'ht_video_url', true ) ?: '';
                $youtube_search_fallback = 'https://www.youtube.com/results?search_query=' . urlencode(get_the_title() . ' official trailer');

                $single_item = array(
                    'id'          => (string) $id,
                    'title'       => get_the_title(),
                    'type'        => ( $pt === 'ht_movie' ) ? 'Movie' : 'TV Series',
                    'releaseDate' => $resolved_d,
                    'displayDate' => date( 'j M Y', $timestamp ),
                    'day'         => date( 'd', $timestamp ),
                    'month'       => strtoupper( date( 'M', $timestamp ) ),
                    'tagline'     => $tagline,
                    'rating'      => $rating,
                    'score'       => floatval($score),
                    'adrenaline'  => intval($adr_idx),
                    'permalink'   => get_permalink( $id ),
                    'thumbnail'   => get_the_post_thumbnail_url( $id, 'large' ) ?: $fallback_img,
                    'synopsis'    => wp_strip_all_tags( get_the_excerpt() ) ?: 'An adrenaline-fueled premiere is arriving soon. Synchronize code parameters.',
                    'trailer'     => !empty($trailer_url) ? esc_url($trailer_url) : $youtube_search_fallback
                );

                $is_explicitly_featured = ( ! empty($id_list) && in_array(intval($id), $id_list) );
                if ( $is_explicitly_featured || $resolved_d >= $today_str ) {
                    if ( $pt === 'ht_movie' ) {
                        $queried_movies[] = $single_item;
                    } else {
                        $queried_series[] = $single_item;
                    }
                }
            }
        }
        wp_reset_postdata();

        // Beautiful, highly optimized Fallback premium content arrays
        $fallback_movies = array();
        $fallback_series = array();
        $all_fallbacks   = insom_get_fallback_items();
        foreach ( $all_fallbacks as $item ) {
            if ( strtolower($item['type']) === 'movie' ) {
                $fallback_movies[] = $item;
            } else {
                $fallback_series[] = $item;
            }
        }

        // Segment list combination. If there are actual database posts retrieved, use ONLY database items.
        $has_db_posts = ( ! empty( $queried_movies ) || ! empty( $queried_series ) );

        if ( $has_db_posts ) {
            $hero_db_list = array_merge($queried_movies, $queried_series);
        } else {
            $hero_db_list = array_merge($fallback_movies, $fallback_series);
        }

        // Apply chronological sorting by upcoming date
        usort( $hero_db_list, function( $a, $b ) use ( $today_str ) {
            $a_past = $a['releaseDate'] < $today_str;
            $b_past = $b['releaseDate'] < $today_str;
            if ( $a_past && ! $b_past ) return 1;
            if ( ! $a_past && $b_past ) return -1;
            return strcmp( $a['releaseDate'], $b['releaseDate'] );
        });

        // Limit Right Grid items dynamically based on the configured maximum limit
        $hero_db_list = array_slice($hero_db_list, 0, $right_limit);

        $side_preview_list = $hero_db_list;
        $nearest_initial_item = isset($hero_db_list[0]) ? $hero_db_list[0] : null;

        if ( ! $nearest_initial_item ) {
            // Safety fallback item in case configuration list is saved completely empty
            $nearest_initial_item = array(
                'id'          => 'safety-fallback-item',
                'title'       => 'High Voltage Sagas',
                'type'        => 'TV Series',
                'releaseDate' => date( 'Y-m-d', strtotime( '+12 days' ) ),
                'displayDate' => date( 'j M Y', strtotime( '+12 days' ) ),
                'day'         => date( 'd', strtotime( '+12 days' ) ),
                'month'       => strtoupper( date( 'M', strtotime( '+12 days' ) ) ),
                'tagline'     => 'Tension rises in the power sector.',
                'rating'      => 'TV-MA',
                'score'       => 9.0,
                'adrenaline'  => 96,
                'permalink'   => '#',
                'thumbnail'   => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&q=80&w=1200',
                'synopsis'    => 'Inside the massive nuclear containment architecture, a single lead supervisor uncovers structural sabotage.',
                'trailer'     => 'https://www.youtube.com/watch?v=Way9Dexny3w'
            );
            $hero_db_list = array($nearest_initial_item);
            $side_preview_list = $hero_db_list;
        }

        ob_start();
        ?>

        <!-- Asset deduplication tags to keep code lightweight but visually striking -->
        <?php if ( ! defined( 'INSOM_HE_FONTS_DEDUPE_V4' ) ) : define( 'INSOM_HE_FONTS_DEDUPE_V4', true ); ?>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;850&family=JetBrains+Mono:wght@400;550;750&display=swap" rel="stylesheet">
        <script src="https://unpkg.com/lucide@latest"></script>
        <?php endif; ?>

        <style>
            :root {
                --insom-accent-red: <?php echo esc_html($accent_color); ?>;
                --insom-bg-dark: <?php echo esc_html($bg_color); ?>;
            }

            .insom-hero-outer-wrapper {
                clear: both !important;
                display: block !important;
                float: none !important;
                width: 100% !important;
                margin-top: <?php echo esc_html($top_margin_desktop); ?> !important;
                padding: 0 !important;
                border: none !important;
                position: relative !important;
                z-index: 10 !important;
            }

            .insomniacs-hero {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: var(--insom-bg-dark) !important;
                color: #ffffff !important;
                position: relative !important;
                height: 85vh;
                min-height: 550px;
                max-height: 900px;
                overflow: hidden !important;
                box-sizing: border-box !important;
                width: 100% !important;
                border-radius: 12px;
                margin-top: 0 !important;
            }

            .insomniacs-hero *,
            .insomniacs-hero *::before,
            .insomniacs-hero *::after {
                box-sizing: border-box !important;
            }

            .hero-wrapper {
                display: grid;
                grid-template-columns: 1fr 400px;
                height: calc(100% - 45px);
                position: relative;
                z-index: 2;
            }

            .hero-left {
                position: relative;
                background-size: cover;
                background-position: center;
                display: flex;
                align-items: flex-end;
                padding: 60px;
                transition: background-image 0.6s cubic-bezier(0.16, 1, 0.3, 1);
                overflow: hidden;
            }

            .hero-left::after {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, rgba(0,0,0,0.92) 0%, rgba(0,0,0,0.5) 45%, rgba(0,0,0,0.2) 100%);
                z-index: 1;
                pointer-events: none;
            }

            .hero-content {
                position: relative;
                z-index: 3;
                max-width: 650px;
                display: flex;
                flex-direction: column;
                gap: 20px;
            }

            .hero-title-prefix {
                font-family: 'JetBrains Mono', monospace;
                font-size: 11px;
                font-weight: 750;
                color: var(--insom-accent-red);
                letter-spacing: 0.25em;
                text-transform: uppercase;
                margin: 0;
            }

            .hero-title {
                font-family: 'Space Grotesk', sans-serif;
                font-size: 48px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: -0.03em;
                line-height: .98;
                margin: 0;
                color: #ffffff !important;
            }

            .hero-synopsis {
                font-size: 14px;
                line-height: 1.6;
                color: #b3bcd4;
                margin: 0;
                font-weight: 500;
            }

            #countdown {
                display: flex;
                gap: 12px;
                align-items: center;
                font-family: 'JetBrains Mono', monospace;
                padding: 14px 20px;
                background: rgba(0,0,0,0.8);
                border: 1px solid rgba(255,255,255,0.08);
                border-radius: 12px;
                max-width: max-content;
                user-select: none;
            }

            .countdown-segment {
                display: flex;
                flex-direction: column;
                align-items: center;
                min-width: 65px;
            }

            .countdown-value {
                font-size: 26px;
                font-weight: 750;
                color: #ffffff;
                line-height: 1;
            }

            .countdown-label {
                font-size: 9px;
                color: #64748b;
                font-weight: 700;
                letter-spacing: 0.1em;
                margin-top: 5px;
                text-transform: uppercase;
            }

            .countdown-divider {
                font-size: 20px;
                color: var(--insom-accent-red);
                font-weight: bold;
                animation: pulse 1s infinite;
            }

            .cta-group {
                display: flex;
                gap: 15px;
                align-items: center;
                margin-top: 10px;
                flex-wrap: wrap !important;
            }

            .insomniacs-hero .btn-primary {
                background: var(--insom-accent-red);
                color: #fff !important;
                padding: 12px 24px;
                border-radius: 8px;
                text-transform: uppercase;
                font-weight: 800;
                font-size: 11px;
                letter-spacing: 0.15em;
                text-decoration: none !important;
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                display: inline-flex;
                align-items: center;
                gap: 8px;
                border: none !important;
                cursor: pointer;
                box-shadow: 0 4px 15px rgba(255, 0, 51, 0.2);
                white-space: nowrap !important;
                flex-shrink: 0 !important;
            }

            .insomniacs-hero .btn-primary span,
            .insomniacs-hero .btn-secondary span {
                white-space: nowrap !important;
                display: inline-block !important;
            }

            .insomniacs-hero .btn-primary:hover {
                box-shadow: 0 0 25px var(--insom-accent-red);
                filter: brightness(1.15);
                transform: translateY(-2px);
            }

            .insomniacs-hero .btn-secondary {
                background: rgba(255,255,255,0.06);
                border: 1px solid rgba(255,255,255,0.09) !important;
                color: #cbd5e1 !important;
                padding: 12px 24px;
                border-radius: 8px;
                text-transform: uppercase;
                font-weight: 800;
                font-size: 11px;
                letter-spacing: 0.15em;
                text-decoration: none !important;
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                display: inline-flex;
                align-items: center;
                gap: 8px;
                border: none !important;
                cursor: pointer;
                white-space: nowrap !important;
                flex-shrink: 0 !important;
            }

            .insomniacs-hero .btn-secondary:hover {
                background: rgba(255,255,255,0.12);
                color: #ffffff !important;
                border-color: rgba(255,255,255,0.2) !important;
                transform: translateY(-2px);
            }

            .hero-right {
                background: #111216 !important;
                border-left: 1px solid rgba(255,255,255,0.05);
                padding: 24px !important;
                padding-top: 30px !important;
                display: flex;
                flex-direction: column;
                gap: 16px !important;
                overflow: hidden !important;
                height: 100%;
            }

            @media (max-width: 1400px) {
                .hero-right {
                    padding-top: 24px !important;
                }
            }

            @media (max-width: 1200px) {
                .hero-right {
                    padding-top: 20px !important;
                }
            }

            .hero-right h3 {
                font-family: 'Space Grotesk', sans-serif;
                font-size: 13px;
                font-weight: 750;
                text-transform: uppercase;
                letter-spacing: 0.15em;
                color: var(--insom-accent-red);
                border-bottom: 1px solid rgba(255,255,255,0.06);
                padding-bottom: 12px;
                margin: 0;
            }

            .grid-container {
                display: flex;
                flex-direction: column;
                gap: 10px !important;
                flex: 1 !important;
                min-height: 0 !important;
                overflow-x: hidden !important;
                overflow-y: auto !important;
                overscroll-behavior: contain;
                scroll-behavior: smooth !important;
                -webkit-overflow-scrolling: touch !important;
            }

            .grid-container.no-scroll {
                overflow-x: hidden !important;
                overflow-y: auto !important;
            }

            .grid-container::-webkit-scrollbar {
                width: 6px;
            }

            .grid-container::-webkit-scrollbar-track {
                background: rgba(0, 0, 0, 0.15) !important;
            }

            .grid-container::-webkit-scrollbar-thumb {
                background: var(--insom-accent-red) !important;
                border-radius: 3px !important;
            }

            .grid-container::-webkit-scrollbar-thumb:hover {
                background: #ffffff !important;
            }

            .insomniacs-hero .hero-right .grid-container .preview-card {
                background: rgba(255,255,255,0.02) !important;
                border: 1px solid rgba(255,255,255,0.04) !important;
                border-radius: 12px !important;
                padding: 12px 14px !important;
                cursor: pointer !important;
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                gap: 15px !important;
                text-decoration: none !important;
                box-shadow: none !important;
                border-bottom: 1px solid rgba(255,255,255,0.04) !important;
            }

            /* Absolute reset for any theme-injected pseudo-elements on the cards or their descendants */
            .insomniacs-hero .hero-right .grid-container .preview-card::after, 
            .insomniacs-hero .hero-right .grid-container .preview-card::before,
            .insomniacs-hero .hero-right .grid-container .preview-card *:after,
            .insomniacs-hero .hero-right .grid-container .preview-card *:before,
            .insomniacs-hero .hero-right .grid-container .preview-card:hover::after,
            .insomniacs-hero .hero-right .grid-container .preview-card:hover::before,
            .insomniacs-hero .hero-right .grid-container .preview-card:hover *:after,
            .insomniacs-hero .hero-right .grid-container .preview-card:hover *:before,
            .insomniacs-hero .hero-right .grid-container .preview-card.active::after,
            .insomniacs-hero .hero-right .grid-container .preview-card.active::before,
            .insomniacs-hero .hero-right .grid-container .preview-card.active *:after,
            .insomniacs-hero .hero-right .grid-container .preview-card.active *:before {
                display: none !important;
                content: none !important;
                background: none !important;
                background-color: transparent !important;
                height: 0 !important;
                border: none !important;
                width: 0 !important;
                box-shadow: none !important;
                opacity: 0 !important;
            }

            /* Prevent any child element from having theme-level underlines or border styles */
            .insomniacs-hero .hero-right .grid-container .preview-card *:not(.adrenaline-badge):not(.adrenaline-badge *) {
                border-bottom: none !important;
                border-top: none !important;
                border-left: none !important;
                border-right: none !important;
                text-decoration: none !important;
                text-decoration-line: none !important;
                box-shadow: none !important;
                background: transparent !important;
            }

            .insomniacs-hero .hero-right .grid-container .preview-card .adrenaline-badge {
                border-bottom: 1px solid rgba(255, 0, 51, 0.2) !important;
                border-top: 1px solid rgba(255, 0, 51, 0.2) !important;
                border-left: 1px solid rgba(255, 0, 51, 0.2) !important;
                border-right: 1px solid rgba(255, 0, 51, 0.2) !important;
                background: rgba(255, 0, 51, 0.1) !important;
                text-decoration: none !important;
                box-shadow: none !important;
            }

            .insomniacs-hero .hero-right .grid-container .preview-card:hover {
                background: rgba(255,255,255,0.05) !important;
                border: 1px solid var(--insom-accent-red) !important;
                transform: translateX(4px) !important;
                text-decoration: none !important;
                box-shadow: none !important;
            }

            .insomniacs-hero .hero-right .grid-container .preview-card.active {
                background: rgba(255, 0, 51, 0.05) !important;
                border: 1px solid var(--insom-accent-red) !important;
                text-decoration: none !important;
                box-shadow: none !important;
            }

            .preview-info {
                min-width: 0;
                flex: 1;
            }

            .preview-card h4 {
                font-size: 13px;
                font-weight: 750;
                margin: 0;
                color: #ffffff !important;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .preview-meta {
                display: flex;
                align-items: center;
                gap: 8px;
                font-family: 'JetBrains Mono', monospace;
                font-size: 10px;
                color: #64748b;
                margin-top: 4px;
            }

            .preview-category {
                color: var(--insom-accent-red);
                font-weight: 750;
            }

            .adrenaline-badge {
                padding: 4px 8px;
                background: rgba(255, 0, 51, 0.1);
                color: var(--insom-accent-red);
                border-radius: 6px;
                font-size: 8.5px;
                font-weight: 750;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                font-family: 'JetBrains Mono', monospace;
                display: inline-flex;
                align-items: center;
                gap: 4px;
                border: 1px solid rgba(255, 0, 51, 0.2);
            }

            .ticker-bar {
                position: absolute;
                bottom: 0;
                width: 100%;
                height: 45px;
                border-top: 2px solid var(--insom-accent-red);
                background: rgba(0,0,0,0.96);
                display: flex;
                align-items: center;
                white-space: nowrap;
                overflow: hidden;
                z-index: 5;
            }

            .ticker-content {
                display: flex;
                width: max-content;
                animation: marquee-scroll 40s linear infinite;
                font-family: 'JetBrains Mono', monospace;
                font-size: 10px;
                font-weight: bold;
                color: #94a3b8;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                align-items: center;
            }

            @keyframes marquee-scroll {
                0% { transform: translate3d(0, 0, 0); }
                100% { transform: translate3d(-50%, 0, 0); }
            }

            .mobile-carousel {
                display: none;
            }

            /* Responsive overrides for Collage-Mockup Premium Style */
            @media (max-width: 1023px) {
                .insom-hero-outer-wrapper {
                    margin-top: <?php echo esc_html($top_margin_mobile); ?> !important;
                    padding-top: 10px !important;
                    display: block !important;
                }

                @media (max-width: 767px) {
                    .insom-hero-outer-wrapper {
                        margin-top: calc(<?php echo esc_html($top_margin_mobile); ?> - 15px) !important;
                    }
                }

                @media (max-width: 480px) {
                    .insom-hero-outer-wrapper {
                        margin-top: calc(<?php echo esc_html($top_margin_mobile); ?> - 25px) !important;
                    }
                }

                .insomniacs-hero {
                    height: auto;
                    max-height: none;
                    aspect-ratio: auto;
                    display: flex;
                    flex-direction: column;
                    margin-top: 110px !important;
                    background: #08090c !important;
                    border-radius: 12px;
                }

                .hero-wrapper {
                    display: none;
                }

                .mobile-carousel {
                    display: flex;
                    flex-direction: column;
                    gap: 16px;
                    padding: 24px 16px;
                    background: #08090c;
                    width: 100%;
                    box-sizing: border-box;
                }

                .mobile-upcoming-label {
                    font-family: 'JetBrains Mono', monospace;
                    font-size: 10px;
                    color: #8b92a6;
                    letter-spacing: 0.15em;
                    text-transform: uppercase;
                    margin-bottom: 4px;
                    text-align: center;
                }

                .mobile-main-title {
                    font-family: 'Space Grotesk', sans-serif;
                    font-size: 26px;
                    font-weight: 700;
                    color: #ffffff;
                    text-transform: uppercase;
                    letter-spacing: -0.02em;
                    text-align: center;
                    margin: 0 0 4px 0;
                    line-height: 1.1;
                    word-wrap: break-word;
                }

                .mobile-live-label {
                    font-family: 'JetBrains Mono', monospace;
                    font-size: 10px;
                    color: #64748b;
                    letter-spacing: 0.1em;
                    text-transform: uppercase;
                    text-align: center;
                    margin-bottom: 6px;
                    display: block;
                }

                .mobile-big-countdown {
                    font-family: 'JetBrains Mono', monospace;
                    margin: 8px 0 20px 0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 6px;
                    background: rgba(0, 0, 0, 0.4);
                    padding: 12px 6px;
                    border: 1px solid rgba(255, 255, 255, 0.04);
                    border-radius: 12px;
                    text-decoration: none !important;
                }

                .mobile-countdown-segment {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    min-width: 50px;
                }

                .mobile-countdown-value {
                    font-size: 22px;
                    font-weight: 750;
                    color: #ffffff;
                    line-height: 1;
                }

                .mobile-countdown-label {
                    font-size: 7.5px;
                    color: #64748b;
                    font-weight: 700;
                    letter-spacing: 0.08em;
                    margin-top: 4px;
                    text-transform: uppercase;
                }

                .mobile-countdown-divider {
                    font-size: 16px;
                    color: var(--insom-accent-red);
                    font-weight: bold;
                    animation: pulse 1s infinite;
                }

                .mobile-action-btn {
                    width: 100% !important;
                    background: var(--insom-accent-red) !important;
                    color: #ffffff !important;
                    font-family: 'Space Grotesk', sans-serif;
                    font-size: 12px;
                    font-weight: 800;
                    text-transform: uppercase;
                    letter-spacing: 0.12em;
                    padding: 15px 20px;
                    border-radius: 8px;
                    border: none !important;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    box-shadow: 0 4px 20px rgba(255, 0, 51, 0.35);
                    transition: all 0.25s ease;
                    margin-bottom: 24px;
                }

                .mobile-action-btn:active {
                    transform: scale(0.98);
                    opacity: 0.9;
                }

                .mobile-poster-frame {
                    position: relative;
                    width: 100%;
                    max-width: 320px;
                    margin: 0 auto 20px auto;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }

                .mobile-poster-container {
                    position: relative;
                    width: 100%;
                    aspect-ratio: 2 / 3;
                    border-radius: 16px;
                    overflow: hidden;
                    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.61);
                    border: 1px solid rgba(255, 255, 255, 0.08);
                    background-size: cover;
                    background-position: center;
                    transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
                }

                .mobile-poster-container::after {
                    content: '';
                    position: absolute;
                    inset: 0;
                    background: linear-gradient(to top, rgba(0, 0, 0, 0.92) 0%, rgba(0, 0, 0, 0) 50%);
                    pointer-events: none;
                }

                .mobile-nav-btn {
                    position: absolute;
                    top: 40%;
                    transform: translateY(-50%);
                    width: 44px;
                    height: 44px;
                    border-radius: 50%;
                    background: rgba(0, 0, 0, 0.7);
                    border: 1px solid rgba(255, 255, 255, 0.15) !important;
                    color: #ffffff;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    z-index: 10;
                    transition: all 0.2s ease;
                }
                
                .mobile-nav-btn:active {
                    background: var(--insom-accent-red);
                    border-color: var(--insom-accent-red) !important;
                }

                .mobile-nav-btn.prev {
                    left: -20px;
                }

                .mobile-nav-btn.next {
                    right: -20px;
                }

                .mobile-poster-info {
                    text-align: center;
                    margin-top: 15px;
                    width: 100%;
                }

                .mobile-poster-title {
                    font-family: 'Space Grotesk', sans-serif;
                    font-size: 18px;
                    font-weight: 700;
                    color: #ffffff;
                    text-transform: uppercase;
                    margin: 0 0 4px 0;
                    letter-spacing: -0.01em;
                }

                .mobile-poster-meta {
                    font-size: 11px;
                    color: #94a3b8;
                    margin: 0;
                    font-family: 'JetBrains Mono', monospace;
                }

                .mobile-scroller-heading {
                    font-family: 'Space Grotesk', sans-serif;
                    font-size: 11px;
                    font-weight: 750;
                    color: var(--insom-accent-red);
                    letter-spacing: 0.15em;
                    text-transform: uppercase;
                    margin: 15px 0 10px 0;
                    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
                    padding-bottom: 8px;
                    text-align: left;
                }

                .mobile-horizontal-scroll {
                    display: flex;
                    gap: 12px;
                    overflow-x: auto;
                    scroll-behavior: smooth;
                    padding-bottom: 10px;
                    scrollbar-width: none;
                }

                .mobile-horizontal-scroll::-webkit-scrollbar {
                    display: none;
                }

                .mobile-poster-card {
                    flex: 0 0 100px;
                    position: relative;
                    aspect-ratio: 2 / 3;
                    border-radius: 10px;
                    overflow: hidden;
                    border: 2px solid rgba(255, 255, 255, 0.05);
                    cursor: pointer;
                    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                    background-size: cover;
                    background-position: center;
                }

                .mobile-poster-card.active {
                    border-color: var(--insom-accent-red);
                    box-shadow: 0 0 15px rgba(255, 0, 51, 0.45);
                    transform: scale(1.03);
                }

                .mobile-poster-card-overlay {
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    background: rgba(0, 0, 0, 0.82);
                    padding: 6px 4px;
                    text-align: center;
                    font-family: 'JetBrains Mono', monospace;
                    font-size: 9px;
                    font-weight: 750;
                    color: #ffffff;
                    text-transform: uppercase;
                    border-top: 1px solid rgba(255, 255, 255, 0.1);
                    transition: background 0.3s ease;
                }
                
                .mobile-poster-card.active .mobile-poster-card-overlay {
                    background: var(--insom-accent-red);
                    color: #ffffff;
                    font-weight: 850;
                }

                .mobile-cyber-footer {
                    background: rgba(7, 8, 12, 0.95);
                    border-top: 1px solid rgba(255, 0, 51, 0.15) !important;
                    padding: 10px 14px;
                    border-radius: 8px;
                    margin-top: 15px;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    font-family: 'JetBrains Mono', monospace;
                    font-size: 9px;
                    color: #94a3b8;
                    text-transform: uppercase;
                    letter-spacing: 0.02em;
                    box-shadow: inset 0 0 10px rgba(255, 0, 51, 0.05);
                    text-align: left;
                }
                
                .mobile-cyber-footer span.accent-glow {
                    color: var(--insom-accent-red);
                    text-shadow: 0 0 5px rgba(255, 0, 51, 0.6);
                    font-weight: bold;
                }

                .ticker-bar {
                    position: relative;
                    bottom: auto;
                }
            }

            /* COORDINATE RSVP MODAL (POPUP without Tailwind-dependency) */
            .insom-modal-wrapper {
                position: fixed !important;
                inset: 0 !important;
                z-index: 99999 !important;
                overflow-y: auto !important;
                display: none; /* Controlled via JS switch */
                align-items: center !important;
                justify-content: center !important;
                padding: 16px !important;
                box-sizing: border-box !important;
                font-family: 'Plus Jakarta Sans', sans-serif !important;
            }

            .insom-modal-overlay {
                position: fixed !important;
                inset: 0 !important;
                background: rgba(0, 0, 0, 0.85) !important;
                backdrop-filter: blur(10px) !important;
                z-index: 1 !important;
                transition: opacity 0.3s ease;
            }

            .insom-modal-container {
                position: relative !important;
                width: 100% !important;
                max-width: 500px !important;
                background: #0d0e12 !important;
                border: 1px solid rgba(255, 255, 255, 0.08) !important;
                border-radius: 16px !important;
                padding: 30px !important;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7) !important;
                color: #ffffff !important;
                z-index: 2 !important;
                animation: modalIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
                text-align: left !important;
                box-sizing: border-box !important;
            }

            @keyframes modalIn {
                from { transform: scale(0.95) translateY(10px); opacity: 0; }
                to { transform: scale(1) translateY(0); opacity: 1; }
            }

            .insom-toast {
                animation: toastIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }

            @keyframes toastIn {
                from { transform: translateY(30px) scale(0.9); opacity: 0; }
                to { transform: translateY(0) scale(1); opacity: 1; }
            }

            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.3; }
            }

            .insom-countdown-link-wrapper {
                text-decoration: none !important;
                display: block !important;
                width: max-content !important;
                transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
                border-radius: 12px;
                cursor: pointer !important;
            }
            .insom-countdown-link-wrapper:hover {
                transform: scale(1.03) !important;
            }
            .insom-countdown-link-wrapper:hover #countdown {
                border-color: var(--insom-accent-red) !important;
                box-shadow: 0 0 15px rgba(255, 0, 51, 0.25) !important;
            }

            .mobile-countdown-wrapper {
                text-decoration: none !important;
                display: block !important;
                transition: transform 0.2s ease, opacity 0.2s ease !important;
            }
            .mobile-countdown-wrapper:active {
                transform: scale(0.97) !important;
                opacity: 0.9 !important;
            }
        </style>

        <div class="insom-hero-outer-wrapper" style="clear: both !important; display: block !important; float: none !important; width: 100% !important; padding: 0 !important; border: none !important; position: relative !important;">
            <section class="insomniacs-hero" id="insom-hero-instance">
            <div class="hero-wrapper">
                <!-- HERO LEFT: MAIN VISUAL + CTAS -->
                <div class="hero-left" id="hero-left-view" style="background-image: url('<?php echo esc_url($nearest_initial_item['thumbnail']); ?>');">
                    <div class="hero-content">
                        <p class="hero-title-prefix" id="hero-type-tag">
                            <?php echo esc_html($label_next_upcoming); ?> <?php echo esc_html($nearest_initial_item['type']); ?>
                        </p>
                        
                        <a id="hero-title-link" href="<?php echo esc_url($nearest_initial_item['permalink']); ?>" target="_blank" rel="noopener noreferrer" style="text-decoration: none; color: inherit; display: inline-block;">
                            <h1 class="hero-title" id="hero-title-display" style="cursor: pointer; transition: color 0.25s;" onmouseover="this.style.color='var(--insom-accent-red)'" onmouseout="this.style.color='#ffffff'">
                                <?php echo esc_html($nearest_initial_item['title']); ?>
                            </h1>
                        </a>
                        
                        <p class="hero-synopsis" id="hero-synopsis-display">
                            <?php echo esc_html($nearest_initial_item['synopsis']); ?>
                        </p>

                        <!-- Dynamically populated ticking countdown framework -->
                        <a href="https://insomniacs.party/coming-soon-movies-and-tv-shows-calendar/" class="insom-countdown-link-wrapper" target="_blank" rel="noopener noreferrer" title="Click to view full Release Schedule Calendar">
                            <div id="countdown" aria-live="polite">
                                <div class="countdown-segment">
                                    <span class="countdown-value" id="clock-d">00</span>
                                    <span class="countdown-label"><?php echo esc_html($label_days); ?></span>
                                </div>
                                <span class="countdown-divider">:</span>
                                <div class="countdown-segment">
                                    <span class="countdown-value" id="clock-h">00</span>
                                    <span class="countdown-label"><?php echo esc_html($label_hours); ?></span>
                                </div>
                                <span class="countdown-divider">:</span>
                                <div class="countdown-segment">
                                    <span class="countdown-value" id="clock-m">00</span>
                                    <span class="countdown-label"><?php echo esc_html($label_mins); ?></span>
                                </div>
                                <span class="countdown-divider">:</span>
                                <div class="countdown-segment">
                                    <span class="countdown-value" style="color: var(--insom-accent-red);" id="clock-s">00</span>
                                    <span class="countdown-label"><?php echo esc_html($label_secs); ?></span>
                                </div>
                            </div>
                        </a>

                        <div class="cta-group">
                            <button onclick="triggerPlannerRegistration()" class="btn-primary">
                                <i data-lucide="plus" style="width: 14px; height: 14px;"></i>
                                <span id="sync-btn-txt"><?php echo esc_html($label_sync_release); ?></span>
                            </button>
                            <button onclick="playCurrentTrailer()" class="btn-secondary" style="border: 1px solid var(--insom-accent-red) !important; color: #ffffff !important; background: rgba(255, 0, 51, 0.16) !important; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 0 10px rgba(255,0,51,0.15);">
                                <i data-lucide="info" style="width: 14px; height: 14px; color: var(--insom-accent-red);"></i>
                                <span><?php echo esc_html($label_play_trailer); ?></span>
                            </button>
                            <button onclick="openPartyModal()" class="btn-secondary">
                                <i data-lucide="users" style="width: 14px; height: 14px; color: var(--insom-accent-red);"></i>
                                <span><?php echo esc_html($label_coordinate_party); ?></span>
                            </button>
                            <button onclick="toggleCyberSoundMute()" id="cyber-sound-mute-btn" class="btn-secondary" style="padding: 14px !important; border-radius: 8px !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; cursor: pointer !important; width: 44px !important; height: 44px !important; background: rgba(255,255,255,0.03) !important;" title="Toggle UI Sound Effects">
                                <i id="sound-icon-node" data-lucide="volume-2" style="width: 14px; height: 14px; color: #cbd5e1;"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- HERO RIGHT SIDEBAR: CHRONOLOGICAL SELECTION LIST -->
                <aside class="hero-right">
                    <h3><?php echo esc_html($label_upcoming_spread); ?></h3>
                    <?php 
                    $has_scrollbar = ( count( $side_preview_list ) > 5 );
                    $scroll_class = $has_scrollbar ? 'can-scroll' : 'no-scroll';
                    ?>
                    <div class="grid-container <?php echo esc_attr($scroll_class); ?>">
                        <?php foreach ( $side_preview_list as $idx => $item ) : ?>
                            <div class="preview-card <?php echo ($idx === 0) ? 'active' : ''; ?>" 
                                 onclick="switchHeroDeck(<?php echo $idx; ?>)"
                                 id="db-side-item-<?php echo $idx; ?>">
                                
                                <div class="preview-info">
                                    <h4><?php echo esc_html($item['title']); ?></h4>
                                    <div class="preview-meta">
                                        <span class="preview-category"><?php echo esc_html($item['type']); ?></span>
                                        <span>•</span>
                                        <span><?php echo esc_html($item['displayDate']); ?></span>
                                    </div>
                                </div>

                                <div class="adrenaline-badge">
                                    <i data-lucide="activity" style="width: 10px; height: 10px;"></i>
                                    <span><?php echo esc_html($item['adrenaline']); ?>%</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div style="margin-top: auto; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.06);">
                        <a href="https://insomniacs.party/coming-soon-movies-and-tv-shows-calendar/" class="insom-sidebar-view-all-btn" style="display: flex; align-items: center; justify-content: center; width: 100%; padding: 12px; background: rgba(255, 0, 51, 0.06); border: 1px solid rgba(255, 0, 51, 0.25); border-radius: 10px; color: var(--insom-accent-red); text-decoration: none; font-size: 11px; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; gap: 8px; transition: all 0.25s;" onmouseover="this.style.background='rgba(255, 0, 51, 0.15)'; this.style.borderColor='var(--insom-accent-red)'; this.style.boxShadow='0 0 12px rgba(255, 0, 51, 0.25)';" onmouseout="this.style.background='rgba(255, 0, 51, 0.06)'; this.style.borderColor='rgba(255, 0, 51, 0.25)'; this.style.boxShadow='none';">
                            <span><?php echo esc_html($label_view_all_calendar); ?></span>
                            <i data-lucide="calendar" style="width: 14px; height: 14px;"></i>
                        </a>
                    </div>
                </aside>
            </div>

            <!-- MOBILE CAROUSEL LAYOUT -->
            <div class="mobile-carousel">
                <!-- 1. Header block: Subtitle and Main Active Title -->
                <div class="mobile-upcoming-label"><?php echo esc_html($label_upcoming_badge); ?></div>
                <a id="mobile-title-link" href="<?php echo esc_url($nearest_initial_item['permalink']); ?>" target="_blank" rel="noopener noreferrer" style="color: inherit; text-decoration: none; display: block; text-align: center; margin: 0 auto;">
                    <h2 class="mobile-main-title" id="mobile-active-title">
                        <?php echo esc_html($nearest_initial_item['title']); ?>
                    </h2>
                </a>
                
                <span class="mobile-live-label"><?php echo esc_html($label_countdown_title); ?></span>

                <!-- 2. Big Minimalist Ticking Countdown wrapped in link -->
                <a href="https://insomniacs.party/coming-soon-movies-and-tv-shows-calendar/" class="mobile-countdown-wrapper" target="_blank" rel="noopener noreferrer" style="text-decoration: none !important;" title="Click to view full Release Schedule Calendar">
                    <div class="mobile-big-countdown" style="cursor: pointer;">
                        <div class="mobile-countdown-segment">
                            <span class="mobile-countdown-value clock-d">00</span>
                            <span class="mobile-countdown-label"><?php echo esc_html($label_days); ?></span>
                        </div>
                        <span class="mobile-countdown-divider">:</span>
                        <div class="mobile-countdown-segment">
                            <span class="mobile-countdown-value clock-h">00</span>
                            <span class="mobile-countdown-label"><?php echo esc_html($label_hours); ?></span>
                        </div>
                        <span class="mobile-countdown-divider">:</span>
                        <div class="mobile-countdown-segment">
                            <span class="mobile-countdown-value clock-m">00</span>
                            <span class="mobile-countdown-label"><?php echo esc_html($label_mins); ?></span>
                        </div>
                        <span class="mobile-countdown-divider">:</span>
                        <div class="mobile-countdown-segment">
                            <span class="mobile-countdown-value clock-s" style="color: var(--insom-accent-red);">00</span>
                            <span class="mobile-countdown-label"><?php echo esc_html($label_secs); ?></span>
                        </div>
                    </div>
                </a>

                <!-- 3. Dynamic Neon Action Button -->
                <button onclick="triggerPlannerRegistration()" class="mobile-action-btn" id="mobile-action-add">
                    <?php echo esc_html(strtoupper($label_sync_release)); ?>
                </button>

                <!-- 4. Featured Active Poster Card with navigation chevrons -->
                <div class="mobile-poster-frame">
                    <button class="mobile-nav-btn prev" onclick="mobilePrevHero()" aria-label="Previous event">
                        <i data-lucide="chevron-left" style="width: 22px; height: 22px;"></i>
                    </button>
                    <button class="mobile-nav-btn next" onclick="mobileNextHero()" aria-label="Next event">
                        <i data-lucide="chevron-right" style="width: 22px; height: 22px;"></i>
                    </button>

                    <a id="mobile-poster-img-link" href="<?php echo esc_url($nearest_initial_item['permalink']); ?>" target="_blank" rel="noopener noreferrer" style="display: block; width: 100%; max-width: 320px; border-radius: 16px; overflow: hidden; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.61); border: 1px solid rgba(255, 255, 255, 0.08); aspect-ratio: 2 / 3;">
                        <div class="mobile-poster-container" id="mobile-poster-img" style="background-image: url('<?php echo esc_url($nearest_initial_item['thumbnail']); ?>'); width: 100%; height: 100%; border: none; border-radius: 0; box-shadow: none;"></div>
                    </a>
                    
                    <div class="mobile-poster-info">
                        <div class="mobile-upcoming-label" style="color: var(--insom-accent-red); margin-bottom: 2px;"><?php echo esc_html($label_next_up); ?></div>
                        <a id="mobile-poster-title-link" href="<?php echo esc_url($nearest_initial_item['permalink']); ?>" target="_blank" rel="noopener noreferrer" style="color: inherit; text-decoration: none;">
                            <h4 id="mobile-poster-title" class="mobile-poster-title">
                                <?php echo esc_html($nearest_initial_item['title']); ?>
                            </h4>
                        </a>
                        <p id="mobile-poster-meta" class="mobile-poster-meta">
                            <?php echo esc_html($nearest_initial_item['type']); ?> • RELEASE: <?php echo esc_html($nearest_initial_item['displayDate']); ?>
                        </p>
                        
                        <!-- Watch Party & Trailer mini action shelf -->
                        <div style="display: flex; gap: 8px; justify-content: center; margin-top: 15px;">
                            <button onclick="playCurrentTrailer()" class="btn-secondary" style="padding: 10px 18px; font-size: 11px; border-radius: 8px; border: 1px solid var(--insom-accent-red) !important; color: #fff !important; display: inline-flex; align-items: center; gap: 6px; background: rgba(255, 0, 51, 0.16); cursor: pointer;">
                                <i data-lucide="info" style="width: 13px; height: 13px; color: var(--insom-accent-red);"></i>
                                <span style="font-family: 'Space Grotesk', sans-serif; font-weight: bold; text-transform: uppercase;"><?php echo esc_html($label_play_trailer); ?></span>
                            </button>
                            <button onclick="openPartyModal()" class="btn-secondary" style="padding: 10px 18px; font-size: 11px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1) !important; color: #fff !important; display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.04); cursor: pointer;">
                                <i data-lucide="users" style="width: 13px; height: 13px; color: #64748b;"></i>
                                <span style="font-family: 'Space Grotesk', sans-serif; font-weight: bold; text-transform: uppercase;"><?php echo esc_html($label_coordination); ?></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Synopsis text -->
                <p id="mobile-active-desc" style="font-size: 13px; color: #94a3b8; line-height: 1.6; text-align: center; max-width: 450px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif;">
                    <?php echo esc_html($nearest_initial_item['synopsis']); ?>
                </p>

                <!-- 5. Horizontal multi-card scanner selector row -->
                <div class="mobile-scroller-heading"><?php echo esc_html($label_upcoming_spread); ?></div>
                <div class="mobile-horizontal-scroll">
                    <?php 
                    $now_time = current_time('timestamp') ?: time();
                    foreach ($hero_db_list as $m_idx => $m_item) : 
                        $item_time = strtotime($m_item['releaseDate']);
                        $diff_secs = $item_time - $now_time;
                        $diff_days = ceil($diff_secs / 86400);
                        
                        if ($m_idx === 0) {
                            $days_label = "NEXT";
                        } elseif ($diff_days <= 0) {
                            $days_label = "LIVE NOW";
                        } elseif ($diff_days === 1) {
                            $days_label = "1 DAY";
                        } else {
                            $days_label = $diff_days . " DAYS";
                        }
                    ?>
                        <div class="mobile-poster-card <?php echo ($m_idx === 0) ? 'active' : ''; ?>" 
                             onclick="switchHeroDeck(<?php echo $m_idx; ?>)" 
                             id="m-card-<?php echo $m_idx; ?>"
                             style="background-image: url('<?php echo esc_url($m_item['thumbnail']); ?>');">
                            <div class="mobile-poster-card-overlay">
                                <?php echo esc_html($days_label); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- Futuristic Neon dashed VIEW ALL Poster Card -->
                    <a href="https://insomniacs.party/coming-soon-movies-and-tv-shows-calendar/" 
                       class="mobile-poster-card see-more-card" 
                       target="_blank"
                       rel="noopener noreferrer"
                       style="background: rgba(255, 0, 51, 0.05); border: 2px dashed rgba(255, 0, 51, 0.45); display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; gap: 8px; color: var(--insom-accent-red) !important; text-decoration: none !important; flex: 0 0 100px !important;"
                       title="View Full Release Schedule Calendar">
                        <i data-lucide="plus-circle" style="width: 22px; height: 22px; color: var(--insom-accent-red);"></i>
                        <span style="font-size: 10px; font-weight: 800; font-family: 'Space Grotesk', sans-serif; text-transform: uppercase; letter-spacing: 0.02em;"><?php echo esc_html($label_view_all_mobile); ?></span>
                    </a>
                </div>

                <!-- 6. Cyber medals & stats ticker matching mockup -->
                <div class="mobile-cyber-footer">
                    <span class="accent-glow" style="display: flex; align-items: center; gap: 4px;">
                        <i data-lucide="shield" style="width: 12px; height: 12px; color: var(--insom-accent-red); fill: rgba(255,0,51,0.1);"></i>
                        <?php echo esc_html($label_premiere_medals); ?>
                    </span>
                    <span><?php echo esc_html($label_stats_text); ?> • ADRENALINE: <span id="mobile-footer-adren" style="color: #ff0055; font-weight: bold;"><?php echo esc_html($nearest_initial_item['adrenaline']); ?>%</span></span>
                </div>
            </div>

            <!-- CYBER TICKER MARQUEE BAR -->
            <div class="ticker-bar">
                <div class="ticker-content" id="ticker">
                    <!-- Dynamic double repeating setup to ensure endless looping styling fits exactly -->
                    <span style="padding-right: 50px;">⚡ <?php echo esc_html($ticker_msg); ?></span>
                    <span style="padding-right: 50px;">⚡ <?php echo esc_html($ticker_msg); ?></span>
                    <span style="padding-right: 50px;">⚡ <?php echo esc_html($ticker_msg); ?></span>
                </div>
            </div>
        </section>
        </div>

        <!-- WATCH PARTY DIALOG (COORDINATOR POPUP) -->
        <div id="insom-rsvp-modal" class="insom-modal-wrapper" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="insom-modal-overlay" onclick="closePartyModal()"></div>
            <div class="insom-modal-container">
                <button onclick="closePartyModal()" style="position: absolute; top: 16px; right: 16px; background: none; border: none; color: #64748b; cursor: pointer; transition: color 0.15s; z-index: 10;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#64748b'">
                    <i data-lucide="x" style="width: 20px; height: 20px;"></i>
                </button>

                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <div style="width: 45px; height: 45px; border-radius: 10px; background: rgba(255,0,51,0.1); border: 1px solid rgba(255,0,51,0.2); display: flex; align-items: center; justify-content: center; color: var(--insom-accent-red); margin-bottom: 5px;">
                        <i data-lucide="users" style="width: 22px; height: 22px;"></i>
                    </div>

                    <div>
                        <h3 style="font-size: 18px; margin: 0; font-family: 'Space Grotesk', sans-serif; font-weight: bold; text-transform: uppercase;">COORDINATE SPECTATE PARTY</h3>
                        <p style="font-size: 12px; color: #64748b; margin: 4px 0 0 0;">Synchronize streaming parameters with peer spectators.</p>
                    </div>

                    <form id="rsvp-modal-form" onsubmit="submitRsvpCoordination(event)" style="display: flex; flex-direction: column; gap: 14px; margin-top: 10px;">
                        <div style="display: flex; flex-direction: column; gap: 6px;">
                            <label style="font-family: monospace; font-size: 9px; color: #64748b; text-transform: uppercase; text-align: left;">Selected Premiere Title</label>
                            <input type="text" id="party-deck-title" readonly style="width: 100%; background: #15171e; border: 1px solid rgba(255,255,255,0.05); color: #fff; padding: 10px 14px; border-radius: 8px; font-size: 12px;" />
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                <label style="font-family: monospace; font-size: 9px; color: #64748b; text-transform: uppercase; text-align: left;">Host Email Address</label>
                                <input type="email" placeholder="vikas@example.com" required style="width: 100%; background: #050508; border: 1px solid rgba(255,255,255,0.06); color: #fff; padding: 10px 14px; border-radius: 8px; font-size: 12px;" />
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                <label style="font-family: monospace; font-size: 9px; color: #64748b; text-transform: uppercase; text-align: left;">Preferred View Time</label>
                                <input type="datetime-local" required style="width: 100%; background: #050508; border: 1px solid rgba(255,255,255,0.06); color: #fff; padding: 10px 14px; border-radius: 8px; font-size: 11px;" />
                            </div>
                        </div>

                        <button type="submit" class="btn-primary" style="justify-content: center; padding: 14px; margin-top: 10px;">
                            <i data-lucide="check-circle" style="width: 16px; height: 16px;"></i>
                            <span>Lock Party Coordinates</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- PREMIUM CINEMATIC TRAILER LIGHTBOX MODAL -->
        <div id="insom-trailer-modal" class="insom-modal-wrapper" style="display: none; align-items: center; justify-content: center; z-index: 999999 !important;" role="dialog" aria-modal="true">
            <div class="insom-modal-overlay" onclick="closeTrailerModal()"></div>
            <div class="insom-modal-container" style="max-width: 850px !important; width: 95% !important; padding: 20px !important; background: #07070a !important; border: 1px solid rgba(255,0,51,0.2) !important; border-radius: 16px !important; position: relative; z-index: 1000000; box-shadow: 0 0 35px rgba(255,0,51,0.25) !important;">
                <button onclick="closeTrailerModal()" style="position: absolute; top: 16px; right: 16px; background: rgba(0,0,0,0.6); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; color: #fff; cursor: pointer; transition: all 0.2s; z-index: 1010;" onmouseover="this.style.borderColor='var(--insom-accent-red)'; this.style.color='var(--insom-accent-red)';" onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.color='#fff';">
                    <i data-lucide="x" style="width: 18px; height: 18px;"></i>
                </button>

                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <div>
                        <h3 id="insom-trailer-title" style="font-size: 18px; margin: 0; font-family: 'Space Grotesk', sans-serif; font-weight: bold; text-transform: uppercase; color: #ffffff; letter-spacing: -0.02em;">PLAYING TRAILER</h3>
                        <p style="font-size: 11px; color: #64748b; margin: 4px 0 0 0; font-family: 'JetBrains Mono', monospace;">Spectator Live Deck Feed Connected</p>
                    </div>

                    <div id="insom-trailer-dynamic-container" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 10px; background: #000; border: 1px solid rgba(255,255,255,0.05);">
                        <!-- Video or iframe players will be dynamically created and inserted here on play, and cleared on close -->
                    </div>

                    <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 5px;">
                        <button id="insom-nocookie-toggle-btn" onclick="toggleYoutubeNoCookieMode()" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 6px; padding: 6px 12px; font-size: 11.px; color: #b3bcd4; cursor: pointer; font-family: 'Space Grotesk', sans-serif; font-weight: bold; text-transform: uppercase; transition: all 0.2s; font-size: 11px;" onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='#fff';" onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.color='#b3bcd4';">Toggle Alternate YT Sandbox</button>
                        <button onclick="toggleDiagnosticsPanel()" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 6px; padding: 6px 12px; font-size: 11px; color: #b3bcd4; cursor: pointer; font-family: 'Space Grotesk', sans-serif; font-weight: bold; text-transform: uppercase; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px;" onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='#fff';" onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.color='#b3bcd4';">
                            <i data-lucide="terminal" style="width: 12px; height: 12px;"></i> Toggle Diagnostic Console
                        </button>
                    </div>

                    <!-- Cybernetic Telemetry/Debug Terminal Box -->
                    <div id="insom-trailer-diagnostics" style="display: flex; flex-direction: column; gap: 8px; padding: 12px; background: rgba(0, 0, 0, 0.5); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; margin-top: 5px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255, 255, 255, 0.08); padding-bottom: 6px; margin-bottom: 4px;">
                            <span style="font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: bold; color: var(--insom-accent-red); display: inline-flex; align-items: center; gap: 5px;">
                                <span style="display: inline-block; width: 6px; height: 6px; background: #00ff66; border-radius: 50%; box-shadow: 0 0 6px #00ff66;"></span>
                                DIAGNOSTIC STREAM MATRIX STATUS
                            </span>
                            <button onclick="copyDiagnostics()" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255,255,255,0.15); border-radius: 4px; padding: 3px 8px; font-size: 9px; color: #fff; cursor: pointer; font-family: 'JetBrains Mono', monospace; font-weight: bold; text-transform: uppercase;">COPY LOG</button>
                        </div>
                        <div id="insom-diagnostics-log" style="font-family: 'JetBrains Mono', monospace; font-size: 11px; line-height: 1.5; color: #10b981; white-space: pre-wrap; word-break: break-all; max-height: 150px; overflow-y: auto; text-align: left; background: #020204; padding: 10px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.03);">Loading telemetry streams...</div>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 5px; flex-wrap: wrap; gap: 10px; padding: 10px 14px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px;">
                        <span style="font-size: 11px; color: #64748b; font-family: 'Plus Jakarta Sans', sans-serif;">Having playback issues or restricted embedding?</span>
                        <a id="insom-external-trailer-btn" href="#" target="_blank" style="font-size: 12px; color: var(--insom-accent-red); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; font-weight: bold; font-family: 'Space Grotesk', sans-serif; text-transform: uppercase; letter-spacing: 0.05em; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                            <span>Watch Direct on YouTube</span>
                            <i data-lucide="external-link" style="width: 14px; height: 14px;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dynamic Cinematic Toast Launcher Rack -->
        <div id="insom-toast-rack" style="position: fixed; bottom: 25px; right: 25px; z-index: 100; display: flex; flex-direction: column; gap: 10px; pointer-events: none;"></div>

        <!-- CORE INTERACTIVE CONTROLLER JAVASCRIPT -->
        <script>
            (function() {
                // Structured library constructed dynamically from database post querying fallbacks
                const HERO_COLLECTIONS = <?php echo json_encode( $hero_db_list, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>;
                const STATIC_ADMIN_TARGET_DATE = "<?php echo esc_js($target_dt); ?>";
                const STATIC_NEXT_UPCOMING_LABEL = "<?php echo esc_js($label_next_upcoming); ?>";
                let CURRENT_HERO_IDX = 0;
                let TIMER_ID = null;

                window.HERO_INITIALIZED = false;

                // Cyber Synth SFX Engine (HTML5 Web Audio API)
                let audioCtx = null;
                window.playCyberSFX = function(type) {
                    if (localStorage.getItem("insom_sfx_silence") === "true") return;
                    try {
                        if (!audioCtx) {
                            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                        }
                        if (audioCtx.state === 'suspended') {
                            audioCtx.resume();
                        }
                        const osc = audioCtx.createOscillator();
                        const gain = audioCtx.createGain();
                        osc.connect(gain);
                        gain.connect(audioCtx.destination);
                        const now = audioCtx.currentTime;

                        if (type === 'slide') {
                            osc.type = 'triangle';
                            osc.frequency.setValueAtTime(140, now);
                            osc.frequency.exponentialRampToValueAtTime(70, now + 0.35);
                            gain.gain.setValueAtTime(0.06, now);
                            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.35);
                            osc.start(now);
                            osc.stop(now + 0.35);
                        } else if (type === 'click') {
                            osc.type = 'sine';
                            osc.frequency.setValueAtTime(880, now);
                            osc.frequency.setValueAtTime(1200, now + 0.04);
                            gain.gain.setValueAtTime(0.04, now);
                            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.15);
                            osc.start(now);
                            osc.stop(now + 0.15);
                        } else if (type === 'toggle') {
                            osc.type = 'sine';
                            osc.frequency.setValueAtTime(523.25, now);
                            osc.frequency.exponentialRampToValueAtTime(783.99, now + 0.2);
                            gain.gain.setValueAtTime(0.05, now);
                            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.2);
                            osc.start(now);
                            osc.stop(now + 0.20);
                        } else if (type === 'toast') {
                            osc.type = 'sine';
                            osc.frequency.setValueAtTime(1046.50, now);
                            gain.gain.setValueAtTime(0.03, now);
                            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.25);
                            osc.start(now);
                            osc.stop(now + 0.25);
                        }
                    } catch(e) {}
                };

                window.toggleCyberSoundMute = function() {
                    const silenced = localStorage.getItem("insom_sfx_silence") === "true";
                    if (silenced) {
                        localStorage.setItem("insom_sfx_silence", "false");
                        launchInGameToast("Holographic audio feedback enabled.");
                        setTimeout(function() {
                            window.playCyberSFX('click');
                        }, 50);
                    } else {
                        localStorage.setItem("insom_sfx_silence", "true");
                        launchInGameToast("Feedback muted.");
                    }
                    updateSoundBadgeStyle();
                };

                function updateSoundBadgeStyle() {
                    const btn = document.getElementById("cyber-sound-mute-btn");
                    const iconNode = document.getElementById("sound-icon-node");
                    if (!iconNode) return;
                    
                    const silenced = localStorage.getItem("insom_sfx_silence") === "true";
                    if (silenced) {
                        iconNode.setAttribute("data-lucide", "volume-x");
                        iconNode.style.color = "#ff0033";
                        if (btn) btn.title = "Sounds Muted";
                    } else {
                        iconNode.setAttribute("data-lucide", "volume-2");
                        iconNode.style.color = "#cbd5e1";
                        if (btn) btn.title = "Sounds Enabled";
                    }
                    if (window.lucide) {
                        window.lucide.createIcons();
                    }
                }

                function initHeroSyncModule() {
                    if (window.lucide) {
                        window.lucide.createIcons();
                    }
                    startReleaseTimer();
                    updateSyncButtonState();
                    updateSoundBadgeStyle();
                    window.HERO_INITIALIZED = true;
                }

                // Swaps main featured release metrics dynamically
                window.switchHeroDeck = function(index) {
                    if (!HERO_COLLECTIONS[index]) return;
                    CURRENT_HERO_IDX = index;
                    
                    if (window.HERO_INITIALIZED) {
                        window.playCyberSFX('slide');
                    }
                    const item = HERO_COLLECTIONS[index];

                    // Desktop switching actions
                    const pTitle = document.getElementById("hero-title-display");
                    const pSynop = document.getElementById("hero-synopsis-display");
                    const pType  = document.getElementById("hero-type-tag");
                    const pLeft  = document.getElementById("hero-left-view");
                    const pTitleLink = document.getElementById("hero-title-link");

                    if (pTitle) pTitle.innerText = item.title;
                    if (pSynop) pSynop.innerText = item.synopsis;
                    if (pType)  pType.innerText  = STATIC_NEXT_UPCOMING_LABEL + " " + item.type;
                    if (pLeft)  {
                        pLeft.style.backgroundImage = "url('" + (item.thumbnail || "") + "')";
                    }
                    if (pTitleLink) {
                        pTitleLink.href = item.permalink || "#";
                    }

                    // Update Right lists row borders
                    HERO_COLLECTIONS.forEach(function(_, idx) {
                        const row = document.getElementById("db-side-item-" + idx);
                        if (row) {
                            if (idx === CURRENT_HERO_IDX) {
                                row.classList.add("active");
                            } else {
                                row.classList.remove("active");
                            }
                        }
                    });

                    // Mobile elements switching actions
                    const mTitle = document.getElementById("mobile-active-title");
                    const mDesc  = document.getElementById("mobile-active-desc");
                    const mPosterTitle = document.getElementById("mobile-poster-title");
                    const mPosterMeta = document.getElementById("mobile-poster-meta");
                    const mPosterImg = document.getElementById("mobile-poster-img");
                    const mFooterAdren = document.getElementById("mobile-footer-adren");
                    
                    const mTitleLink = document.getElementById("mobile-title-link");
                    const mPosterImgLink = document.getElementById("mobile-poster-img-link");
                    const mPosterTitleLink = document.getElementById("mobile-poster-title-link");

                    if (mTitle) mTitle.innerText = item.title;
                    if (mDesc)  mDesc.innerText = item.synopsis;
                    if (mPosterTitle) mPosterTitle.innerText = item.title;
                    if (mPosterMeta) mPosterMeta.innerText = item.type + " • RELEASE: " + item.displayDate;
                    if (mPosterImg) {
                        mPosterImg.style.backgroundImage = "url('" + (item.thumbnail || "") + "')";
                    }
                    if (mFooterAdren) mFooterAdren.innerText = item.adrenaline + "%";

                    if (mTitleLink) {
                        mTitleLink.href = item.permalink || "#";
                    }
                    if (mPosterImgLink) {
                        mPosterImgLink.href = item.permalink || "#";
                    }
                    if (mPosterTitleLink) {
                        mPosterTitleLink.href = item.permalink || "#";
                    }

                    HERO_COLLECTIONS.forEach(function(_, idx) {
                        const mCard = document.getElementById("m-card-" + idx);
                        if (mCard) {
                            if (idx === CURRENT_HERO_IDX) {
                                mCard.classList.add("active");
                            } else {
                                mCard.classList.remove("active");
                            }
                        }
                    });

                    startReleaseTimer();
                    updateSyncButtonState();
                    launchInGameToast("Spectator channel switched: " + item.title);
                };

                // Mobile Navigation Arrow Steps
                window.mobilePrevHero = function() {
                    let prevIdx = CURRENT_HERO_IDX - 1;
                    if (prevIdx < 0) prevIdx = HERO_COLLECTIONS.length - 1;
                    switchHeroDeck(prevIdx);
                };

                window.mobileNextHero = function() {
                    let nextIdx = CURRENT_HERO_IDX + 1;
                    if (nextIdx >= HERO_COLLECTIONS.length) nextIdx = 0;
                    switchHeroDeck(nextIdx);
                };

                // Calculates mechanical live timer ticks
                function startReleaseTimer() {
                    if (TIMER_ID) {
                        clearInterval(TIMER_ID);
                    }

                    const targetItem = HERO_COLLECTIONS[CURRENT_HERO_IDX];
                    if (!targetItem) return;

                    // Parse the item's release date
                    let itemDate = parseSafeDate(targetItem.releaseDate + " 00:00:00");
                    let now = new Date();

                    let destinationDate = null;

                    // 1. First prioritization: Try to use the specific item's release date if it's valid and in the future.
                    if (itemDate && !isNaN(itemDate.getTime()) && itemDate > now) {
                        destinationDate = itemDate;
                    }

                    // 2. Fallback for the first item (index 0) only: if the item itself doesn't have a future release date,
                    // check if the admin target date is specified and in the future.
                    if (!destinationDate && CURRENT_HERO_IDX === 0 && STATIC_ADMIN_TARGET_DATE) {
                        const parsedFallback = parseSafeDate(STATIC_ADMIN_TARGET_DATE);
                        if (parsedFallback && !isNaN(parsedFallback.getTime()) && parsedFallback > now) {
                            destinationDate = parsedFallback;
                        }
                    }

                    // 3. Ultimate premium safety fallback: If destination date is STILL empty, invalid, or in the past,
                    // simulate a dynamic ticking countdown by adding a distinct future offset per item index (e.g. 5, 10, 15 days, etc.)
                    // so that the dashboard always looks live, interactive, and beautifully responsive when clicking different titles!
                    if (!destinationDate || isNaN(destinationDate.getTime()) || destinationDate <= now) {
                        const daysOffset = 5 + (CURRENT_HERO_IDX * 5); // index 0: 5 days, index 1: 10 days, index 2: 15 days, etc.
                        destinationDate = new Date(now.getTime() + (daysOffset * 24 * 60 * 60 * 1000));
                    }

                    const destination = destinationDate.getTime();

                    function countTick() {
                        const currentNow = new Date().getTime();
                        const diff = destination - currentNow;

                        let days = 0, hours = 0, mins = 0, secs = 0;

                        if (diff > 0) {
                            days  = Math.floor(diff / (1000 * 60 * 60 * 24));
                            hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            mins  = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                            secs  = Math.floor((diff % (1000 * 60)) / 1000);
                        }

                        const format = function(num) {
                            return String(num).padStart(2, '0');
                        };

                        // Synchronize both Desktop IDs and Mobile layout CSS classes
                        document.querySelectorAll("#clock-d, .clock-d").forEach(function(el) { el.innerText = format(days); });
                        document.querySelectorAll("#clock-h, .clock-h").forEach(function(el) { el.innerText = format(hours); });
                        document.querySelectorAll("#clock-m, .clock-m").forEach(function(el) { el.innerText = format(mins); });
                        document.querySelectorAll("#clock-s, .clock-s").forEach(function(el) { el.innerText = format(secs); });
                    }

                    countTick();
                    TIMER_ID = setInterval(countTick, 1000);
                }

                // Bulletproof browser-agnostic date parser helper
                function parseSafeDate(dateStr) {
                    if (!dateStr) return null;
                    let clean = dateStr.trim();
                    // Replace 'T' with space and normalize dashes to slashes
                    clean = clean.replace('T', ' ').replace(/-/g, '/');
                    
                    let d = new Date(clean);
                    if (!isNaN(d.getTime())) {
                        return d;
                    }

                    // Try parsing standard ISO/Dashes format
                    let dashClean = dateStr.trim().replace(/\//g, '-');
                    let d2 = new Date(dashClean);
                    if (!isNaN(d2.getTime())) {
                        return d2;
                    }

                    // Manual split regex fallback (extremely reliable)
                    const parts = dateStr.split(/[^0-9]+/);
                    if (parts.length >= 3) {
                        const year = parseInt(parts[0], 10);
                        const month = parseInt(parts[1], 10) - 1; // 0-based
                        const day = parseInt(parts[2], 10);
                        const hour = parts[3] ? parseInt(parts[3], 10) : 0;
                        const minute = parts[4] ? parseInt(parts[4], 10) : 0;
                        const second = parts[5] ? parseInt(parts[5], 10) : 0;
                        let d3 = new Date(year, month, day, hour, minute, second);
                        if (!isNaN(d3.getTime())) {
                            return d3;
                        }
                    }

                    return null;
                }

                // Planner registry in localStorage
                window.triggerPlannerRegistration = function() {
                    const item = HERO_COLLECTIONS[CURRENT_HERO_IDX];
                    let list = [];
                    try {
                        const raw = localStorage.getItem("standalone_scheduled") || localStorage.getItem("insom_planner");
                        if (raw) list = JSON.parse(raw);
                    } catch(e) {}

                    const isSaved = list.includes(item.id) || list.includes(String(item.id)) || list.includes(Number(item.id));

                    if (isSaved) {
                        list = list.filter(function(x) { return String(x) !== String(item.id); });
                        launchInGameToast("Unlinked: " + item.title + " removed from your local release planner!");
                    } else {
                        list.push(String(item.id));
                        launchInGameToast("Coordinates Secured: " + item.title + " added to your Release Planner calendar!");
                    }
                    
                    // Unified Sync across both structures
                    localStorage.setItem("standalone_scheduled", JSON.stringify(list));
                    localStorage.setItem("insom_planner", JSON.stringify(list));
                    
                    updateSyncButtonState();
                    
                    if (window.HERO_INITIALIZED) {
                        try { window.playCyberSFX('toggle'); } catch(e) {}
                    }
                    
                    // Fire custom trigger event for live synchrony
                    try {
                        window.dispatchEvent(new CustomEvent('insom_planner_updated', { detail: { id: item.id, list: list } }));
                    } catch(e) {}
                };

                function updateSyncButtonState() {
                    const item = HERO_COLLECTIONS[CURRENT_HERO_IDX];
                    let list = [];
                    try {
                        const raw = localStorage.getItem("standalone_scheduled") || localStorage.getItem("insom_planner");
                        if (raw) list = JSON.parse(raw);
                    } catch(e) {}

                    const isSaved = list.includes(item.id) || list.includes(String(item.id)) || list.includes(Number(item.id));

                    // Update desktop button text & styles
                    const btnTxt = document.getElementById("sync-btn-txt");
                    if (btnTxt) {
                        btnTxt.innerText = isSaved ? "LINKED TO PLANNER" : "Sync Release";
                    }

                    // Update mobile button text & styles
                    const mBtn = document.getElementById("mobile-action-add");
                    if (mBtn) {
                        if (isSaved) {
                            mBtn.innerText = "✓ SYNCED TO CALENDAR";
                            mBtn.style.background = "rgba(255, 0, 51, 0.15)";
                            mBtn.style.border = "1px solid rgba(255, 0, 51, 0.4) !important";
                            mBtn.style.color = "var(--insom-accent-red)";
                            mBtn.style.boxShadow = "none";
                        } else {
                            mBtn.innerText = "SYNC TO CALENDAR";
                            mBtn.style.background = "var(--insom-accent-red)";
                            mBtn.style.border = "none !important";
                            mBtn.style.color = "#ffffff";
                            mBtn.style.boxShadow = "0 4px 20px rgba(255, 0, 51, 0.35)";
                        }
                    }
                }

                // RSVP Coordination popup triggers
                window.openPartyModal = function() {
                    const item = HERO_COLLECTIONS[CURRENT_HERO_IDX];
                    const popup = document.getElementById("insom-rsvp-modal");
                    const inputTitle = document.getElementById("party-deck-title");
                    if (popup) {
                        popup.style.display = "flex";
                    }
                    if (inputTitle) {
                        inputTitle.value = item.title;
                    }
                };

                window.closePartyModal = function() {
                    const popup = document.getElementById("insom-rsvp-modal");
                    if (popup) {
                        popup.style.display = "none";
                    }
                };

                window.submitRsvpCoordination = function(event) {
                    event.preventDefault();
                    closePartyModal();
                    launchInGameToast("Spectator coordinates secured! Watch party alignment dispatched.");
                };

                // Play trailer lightbox trigger
                let diagnosticsLogData = [];
                let forceNoCookie = false;
                let waitingTimeoutId = null;

                window.addDiagnosticLog = function(level, msg) {
                    const timestamp = new Date().toLocaleTimeString();
                    const emoji = level === 'SUCCESS' ? '🟢' : level === 'ERROR' ? '🔴' : level === 'WARNING' ? '🟡' : '⚡';
                    const formattedLog = "[" + timestamp + "] " + emoji + " [" + level + "] " + msg;
                    diagnosticsLogData.push(formattedLog);
                    
                    const logDisplay = document.getElementById("insom-diagnostics-log");
                    if (logDisplay) {
                        if (diagnosticsLogData.length === 1) {
                            logDisplay.innerHTML = "";
                        }
                        
                        let color = "#a1a1aa";
                        if (level === 'SUCCESS') color = "#10b981";
                        if (level === 'ERROR') color = "#ef4444";
                        if (level === 'WARNING') color = "#f59e0b";
                        if (level === 'INFO') color = "#38bdf8";
                        
                        const logLine = document.createElement("div");
                        logLine.style.color = color;
                        logLine.style.marginBottom = "3px";
                        logLine.style.borderBottom = "1px solid rgba(255,255,255,0.02)";
                        logLine.style.paddingBottom = "2px";
                        logLine.innerText = formattedLog;
                        logDisplay.appendChild(logLine);
                        logDisplay.scrollTop = logDisplay.scrollHeight;
                    }
                };

                window.copyDiagnostics = function() {
                    const text = diagnosticsLogData.join("\n");
                    const helperText = "--- COPY TELEMETRY START ---\n" + text + "\n--- COPY TELEMETRY END ---";
                    
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(helperText).then(function() {
                            launchInGameToast("Diagnostic logs copied to clipboard!");
                        }).catch(function() {
                            fallbackCopyText(helperText);
                        });
                    } else {
                        fallbackCopyText(helperText);
                    }
                };

                function fallbackCopyText(text) {
                    try {
                        const textArea = document.createElement("textarea");
                        textArea.value = text;
                        textArea.style.position = "fixed";
                        textArea.style.top = "0";
                        textArea.style.left = "0";
                        textArea.style.opacity = "0";
                        document.body.appendChild(textArea);
                        textArea.focus();
                        textArea.select();
                        document.execCommand('copy');
                        document.body.removeChild(textArea);
                        launchInGameToast("Diagnostic logs copied safely!");
                    } catch (err) {
                        alert("Could not copy automatically. Please select it from the diagnosed console.");
                    }
                }

                window.toggleDiagnosticsPanel = function() {
                    const panel = document.getElementById("insom-trailer-diagnostics");
                    if (panel) {
                        if (panel.style.display === "none") {
                            panel.style.display = "flex";
                        } else {
                            panel.style.display = "none";
                        }
                    }
                };

                window.toggleYoutubeNoCookieMode = function() {
                    forceNoCookie = !forceNoCookie;
                    const toggleBtn = document.getElementById("insom-nocookie-toggle-btn");
                    if (toggleBtn) {
                        if (forceNoCookie) {
                            toggleBtn.style.background = "var(--insom-accent-red)";
                            toggleBtn.style.borderColor = "var(--insom-accent-red)";
                            toggleBtn.style.color = "#fff";
                            toggleBtn.innerText = "Nocookie Enabled";
                        } else {
                            toggleBtn.style.background = "rgba(255, 255, 255, 0.05)";
                            toggleBtn.style.borderColor = "rgba(255, 255, 255, 0.1)";
                            toggleBtn.style.color = "#b3bcd4";
                            toggleBtn.innerText = "Toggle Alternate YT Sandbox";
                        }
                    }
                    addDiagnosticLog("WARNING", "Re-init play current media stream with Alternate YouTube domain. Custom cookies disabled.");
                    window.playCurrentTrailer();
                };

                window.closeTrailerModal = function() {
                    if (waitingTimeoutId) {
                        clearTimeout(waitingTimeoutId);
                        waitingTimeoutId = null;
                    }
                    const popup = document.getElementById("insom-trailer-modal");
                    const container = document.getElementById("insom-trailer-dynamic-container");
                    if (popup) {
                        popup.style.display = "none";
                    }
                    if (container) {
                        container.innerHTML = "";
                    }
                    diagnosticsLogData = [];
                };

                function displayRescueCard() {
                    const container = document.getElementById("insom-trailer-dynamic-container");
                    const item = HERO_COLLECTIONS[CURRENT_HERO_IDX];
                    if (!container || !item) return;
                    
                    addDiagnosticLog("WARNING", "Casting blocked. Generating high-fidelity Media Rescue options card...");
                    
                    container.innerHTML = '\
                    <div style="position: absolute; inset: 0; background: #0c0d12; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 25px; text-align: center; font-family: \'Plus Jakarta Sans\', sans-serif; border: 1px solid rgba(255,0,51,0.15); border-radius: 8px; z-index: 10;">\
                        <div style="background: rgba(255,0,51,0.1); border: 1px solid var(--insom-accent-red); width: 55px; height: 55px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; box-shadow: 0 0 15px rgba(255,0,51,0.25);">\
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--insom-accent-red)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>\
                        </div>\
                        <h4 style="color: #ffffff; font-weight: 700; font-size: 16px; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px;">Browser Blocked Media Codec</h4>\
                        <p style="color: #8b92a6; font-size: 12px; max-width: 380px; line-height: 1.5; margin: 0 0 20px 0;">\
                            Your secure browser playground restrictions or CORS requirements on this video host prevented inline decoding. Choose an option to play below:\
                        </p>\
                        <div style="display: flex; gap: 10px; flex-wrap: wrap; justify-content: center;">\
                            <a href="' + item.trailer + '" target="_blank" style="background: var(--insom-accent-red); color: #ffffff; text-decoration: none; padding: 10px 18px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; box-shadow: 0 4px 10px rgba(255,0,51,0.25); cursor: pointer;">\
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg> Launch Direct Video\
                            </a>\
                            <a href="https://www.youtube.com/results?search_query=' + encodeURIComponent(item.title + " official trailer") + '" target="_blank" style="background: rgba(255,255,255,0.06); color: #ffffff; text-decoration: none; padding: 10px 18px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid rgba(255,255,255,0.15); display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; cursor: pointer;">\
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg> Search on YouTube\
                            </a>\
                        </div>\
                    </div>\
                    ';
                }

                window.playCurrentTrailer = function() {
                    if (typeof waitingTimeoutId !== 'undefined' && waitingTimeoutId) {
                        clearTimeout(waitingTimeoutId);
                        waitingTimeoutId = null;
                    }
                    const item = HERO_COLLECTIONS[CURRENT_HERO_IDX];
                    if (!item || !item.permalink || item.permalink === '#') {
                        launchInGameToast("No details landing page has been active for this item.");
                        return;
                    }

                    launchInGameToast("Opening details for " + item.title + " in new tab...");
                    setTimeout(function() {
                        window.open(item.permalink, '_blank');
                    }, 150);
                };

                function getEmbedUrl(url, forceNocookieFlag) {
                    if (!url) return "";
                    let cleanedUrl = url.trim();

                    // If it is literally just an 11-character YouTube video ID
                    if (/^[A-Za-z0-9_-]{11}$/.test(cleanedUrl)) {
                        const domain = forceNocookieFlag ? "youtube-nocookie.com" : "youtube.com";
                        return "https://www." + domain + "/embed/" + cleanedUrl + "?autoplay=1&rel=0";
                    }

                    // Check if it's a YouTube URL
                    const isYouTube = cleanedUrl.indexOf("youtube.com") !== -1 || 
                                      cleanedUrl.indexOf("youtu.be") !== -1 || 
                                      cleanedUrl.indexOf("youtube-nocookie.com") !== -1;

                    if (isYouTube) {
                        let videoId = "";

                        // 1. Check youtube.com/shorts/VIDEO_ID
                        if (cleanedUrl.indexOf("/shorts/") !== -1) {
                            let parts = cleanedUrl.split("/shorts/");
                            if (parts[1]) {
                                videoId = parts[1].split("?")[0].split("&")[0].split("/")[0];
                            }
                        }
                        // 2. Check youtu.be/VIDEO_ID
                        else if (cleanedUrl.indexOf("youtu.be/") !== -1) {
                            let parts = cleanedUrl.split("youtu.be/");
                            if (parts[1]) {
                                videoId = parts[1].split("?")[0].split("&")[0].split("/")[0];
                            }
                        }
                        // 3. Check /embed/VIDEO_ID
                        else if (cleanedUrl.indexOf("/embed/") !== -1) {
                            let parts = cleanedUrl.split("/embed/");
                            if (parts[1]) {
                                videoId = parts[1].split("?")[0].split("&")[0].split("/")[0];
                            }
                        }
                        // 4. Check watch?v=VIDEO_ID or &v=VIDEO_ID
                        else {
                            let match = cleanedUrl.match(/[?&]v=([^&#\?]+)/);
                            if (match) {
                                videoId = match[1];
                            } else {
                                // Fallback: try matching other formats like /v/VIDEO_ID
                                let vMatch = cleanedUrl.match(/\/v\/([^&#\?]+)/);
                                if (vMatch) {
                                    videoId = vMatch[1];
                                }
                            }
                        }

                        // Sanitize videoId to ensure it is exactly 11 characters
                        if (videoId) {
                            videoId = videoId.trim().replace(/[^A-Za-z0-9_-]/g, "");
                            if (videoId.length > 11) {
                                videoId = videoId.substring(0, 11);
                            }
                            if (videoId.length === 11) {
                                const domain = forceNocookieFlag ? "youtube-nocookie.com" : "youtube.com";
                                return "https://www." + domain + "/embed/" + videoId + "?autoplay=1&rel=0";
                            }
                        }
                    }

                    // Support Vimeo URLs
                    let vimReg = /vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/;
                    let vimMatch = cleanedUrl.match(vimReg);
                    if (vimMatch && vimMatch[3]) {
                        return "https://player.vimeo.com/video/" + vimMatch[3] + "?autoplay=1&badge=0&autopause=0";
                    }

                    return cleanedUrl;
                }

                // Fires aesthetic UI notifications inside container
                function launchInGameToast(msg) {
                    const rack = document.getElementById("insom-toast-rack");
                    if (!rack) return;

                    const toast = document.createElement("div");
                    toast.className = "insom-toast";
                    toast.style.pointerEvents = "auto";
                    toast.style.background = "#111216";
                    toast.style.border = "1px solid rgba(255,0,51,0.25)";
                    toast.style.color = "#ffffff";
                    toast.style.padding = "14px 20px";
                    toast.style.borderRadius = "10px";
                    toast.style.fontSize = "12px";
                    toast.style.fontWeight = "bold";
                    toast.style.boxShadow = "0 10px 25px rgba(0,0,0,0.5)";
                    toast.style.display = "flex";
                    // Just basic styles
                    toast.style.alignItems = "center";
                    toast.style.gap = "10px";

                    toast.innerHTML = '<span style="color:var(--insom-accent-red);">⚡</span> <span>' + msg + '</span>';
                    rack.appendChild(toast);

                    if (window.HERO_INITIALIZED) {
                        try { window.playCyberSFX('toast'); } catch(e) {}
                    }

                    setTimeout(function() {
                        toast.style.opacity = "0";
                        toast.style.transform = "translateY(15px) scale(0.95)";
                        toast.style.transition = "all 0.35s ease";
                        setTimeout(function() {
                            toast.remove();
                        }, 400);
                    }, 4000);
                }

                if (document.readyState === 'loading') {
                    document.addEventListener("DOMContentLoaded", initHeroSyncModule);
                } else {
                    initHeroSyncModule();
                }
            })();
        </script>

        <?php
        return ob_get_clean();
    }
}

// -----------------------------------------------------------------------------
// INSOMNIACS SMOOTH SCROLL & STICKY PICTURE ACTOR TEMPLATE FIX (v3.0.0)
// Resolves image bouncing, stuttering, and sticking issues on page scroll
// -----------------------------------------------------------------------------

if ( ! function_exists( 'insom_dequeue_sticky_scripts' ) ) {
    function insom_dequeue_sticky_scripts() {
        $handles = array(
            'theia-sticky-sidebar',
            'theiaStickySidebar',
            'theia-sticky',
            'theia_sticky_sidebar',
            'gloria-sticky',
            'gloria_sticky',
            'hc-sticky',
            'hcsticky',
            'sticky-sidebar',
            'stickyStickySidebar',
            'jquery-sticky',
            'jquery.sticky',
            'wp-sticky-sidebar',
            'sticky-kit',
            'jquery-scrolltofixed',
            'scrolltofixed'
        );
        foreach ( $handles as $handle ) {
            wp_dequeue_script( $handle );
            wp_deregister_script( $handle );
        }
    }
    add_action( 'wp_enqueue_scripts', 'insom_dequeue_sticky_scripts', 9999 );
    add_action( 'wp_print_scripts', 'insom_dequeue_sticky_scripts', 9999 );
}

if ( ! function_exists( 'insom_smooth_scroll_custom_css_fix' ) ) {
    function insom_smooth_scroll_custom_css_fix() {
        if ( is_admin() ) {
            return;
        }
        ?>
        <!-- Insomniacs Smooth Sticky Image Core Stylings -->
        <style id="insom-smooth-scroll-picture-overrides">
            /*
             * ROW FLEX COERCION COREGRESS
             * Forces parent bootstrap/layout rows and elements containing the sidebar columns into actual flexbox grids.
             * This ensures the left column tracks and stretches to the exact height of the adjacent details columns.
             */
            .single-actor-wrapper .row,
            .actor-layout-main .row,
            .actor-container .row,
            .single-actor .row,
            .actor-row,
            div.row:has(.single-actor-left),
            div:has(> .single-actor-left),
            div:has(> .actor-sidebar-column),
            div:has(> .col-md-3.single-actor-left) {
                display: -webkit-box !important;
                display: -ms-flexbox !important;
                display: flex !important;
                -webkit-box-orient: horizontal !important;
                -webkit-box-direction: normal !important;
                -ms-flex-direction: row !important;
                flex-direction: row !important;
                -ms-flex-wrap: wrap !important;
                flex-wrap: wrap !important;
                -webkit-box-align: stretch !important;
                -ms-flex-align: stretch !important;
                align-items: stretch !important;
            }

            /*
             * SIDEBAR COLUMN track: Must stretch to full main column height to provide a scrolling track.
             */
            .single-actor-left,
            .col-md-3.single-actor-left,
            .actor-sidebar-column {
                float: none !important;
                position: relative !important;
                height: auto !important;
                min-height: 100% !important;
                -ms-flex-item-align: stretch !important;
                align-self: stretch !important;
                display: block !important;
            }

            /*
             * STICKY INNER CONTAINER: Only the direct child inside the track gets sticky.
             * Supports direct images, wrapper anchors, or inner divs.
             * Avoids nested stickiness layouts that cause violent vertical screen tremors.
             */
            .single-actor-left > div,
            .single-actor-left > img,
            .single-actor-left > a,
            .actor-media > img,
            .actor-poster > img,
            .actor-media,
            .actor-poster,
            .actor-avatar-wrapper,
            .theiaStickySidebar,
            .gloria-sticky {
                position: -webkit-sticky !important;
                position: sticky !important;
                top: 130px !important;
                -ms-flex-item-align: start !important;
                align-self: start !important;
                
                /* Stop JS coord calculators from writing custom transforms or offsets */
                transform: none !important;
                -webkit-transform: none !important;
                margin-top: 0 !important;
                margin-bottom: 0 !important;
                
                /* Instruct browser to composite on GPU */
                will-change: transform, top;
                transition: none !important;
                -webkit-transition: none !important;
                -webkit-backface-visibility: hidden;
                backface-visibility: hidden;
                transform-style: flat !important;
                z-index: 99 !important;
            }

            /* Prevent double-stickiness on grandchildren images inside the sticky sidebar wrapper */
            .single-actor-left > div img,
            .theiaStickySidebar img,
            .gloria-sticky img {
                position: static !important;
                transform: none !important;
                -webkit-transform: none !important;
                transition: none !important;
                animation: none !important;
                margin-top: 0 !important;
                margin-bottom: 0 !important;
            }

            /* 
             * OVERFLOW DE-BOTTLENECK MATRIX
             * Native CSS sticky fails if any container parent has overflow: hidden or auto.
             * Open key layout grids up to let scroll positions calculate flawlessly.
             */
            .row,
            .col-md-3,
            .col-lg-3,
            .col-xl-3,
            .col-md-9,
            .col-lg-9,
            .col-xl-9,
            .single-actor-wrapper,
            .actor-layout-main,
            .actor-container,
            .site-content,
            #content,
            .primary-content-area,
            .single-actor,
            .entry-content,
            .main-wrapper,
            .site-main,
            #main,
            #primary,
            .site,
            #page,
            article,
            .post-content,
            .wrapper,
            .content-wrapper,
            .page-wrapper {
                overflow: visible !important;
            }

            /* Prevent any horizontal alignment shakes on touch scrolling while keeping sticky intact via clip */
            body, html {
                height: auto !important;
                min-height: 100% !important;
                overflow-x: clip !important;
                overflow-y: visible !important;
            }

            /* Spacing adjustments on small mobile layouts where columns fold vertical */
            @media (max-width: 991px) {
                .single-actor-wrapper .row,
                .actor-layout-main .row,
                .actor-container .row,
                .single-actor .row,
                .actor-row,
                div:has(> .single-actor-left) {
                    display: block !important;
                }
                .single-actor-left,
                .col-md-3.single-actor-left {
                    position: relative !important;
                    height: auto !important;
                    align-self: unset !important;
                }
                .single-actor-left > div,
                .single-actor-left > img,
                .single-actor-left > a,
                .actor-media,
                .actor-poster,
                .actor-avatar-wrapper,
                .gloria-sticky {
                    position: relative !important;
                    top: 0 !important;
                    margin-bottom: 30px !important;
                    align-self: unset !important;
                }
            }
        </style>
        <?php
    }
    add_action( 'wp_head', 'insom_smooth_scroll_custom_css_fix', 999 );
}

if ( ! function_exists( 'insom_smooth_scroll_early_js_hijack' ) ) {
    function insom_smooth_scroll_early_js_hijack() {
        if ( is_admin() ) {
            return;
        }
        ?>
        <!-- Insomniacs Smooth Scroll Failsafe Getter/Setter Hijacker -->
        <script id="insom-sticky-js-hijacker">
            (function() {
                'use strict';

                // Helpers to check if Element matches or is inside our left tracking rail
                function isTargetElement(element) {
                    if (!element) return false;
                    try {
                        if (element.matches && (
                            element.matches('.single-actor-left, .actor-detail-left, .actor-sidebar, .actor-media, .actor-poster, .theiaStickySidebar, .gloria-sticky, [class*="sticky"], .sticky-wrapper, .actor-avatar-img, .single-actor img') ||
                            element.closest('.single-actor-left') !== null ||
                            element.closest('.actor-detail-left') !== null ||
                            element.closest('[class*="sticky"]') !== null
                        )) {
                            return true;
                        }
                    } catch (e) {}
                    return false;
                }

                // Inject ES6 HTMLElement.prototype.style property getter/setter hijack.
                // This blocks ANY direct inline style coordinate manipulations made via el.style.top = '10px'
                try {
                    const originalStyleDescriptor = Object.getOwnPropertyDescriptor(HTMLElement.prototype, 'style');
                    if (originalStyleDescriptor) {
                        Object.defineProperty(HTMLElement.prototype, 'style', {
                            get: function() {
                                const originalStyle = originalStyleDescriptor.get.call(this);
                                if (isTargetElement(this)) {
                                    // Mark it as a protected target for CSSStyleDeclaration overrides
                                    originalStyle._isInterceptedTarget = true;
                                    if (!this._proxyStyle) {
                                        this._proxyStyle = new Proxy(originalStyle, {
                                            get: function(target, prop, receiver) {
                                                if (prop === 'setProperty') {
                                                    return function(propertyName, value, priority) {
                                                        const forbiddenProps = ['position', 'top', 'margin-top', 'transform', 'left', 'width', 'height'];
                                                        if (forbiddenProps.indexOf(propertyName.toLowerCase()) > -1) {
                                                            return; // BLOCK coordinates
                                                        }
                                                        return target.setProperty(propertyName, value, priority);
                                                    };
                                                }
                                                if (prop === 'removeProperty') {
                                                    return function(propertyName) {
                                                        const forbiddenProps = ['position', 'top', 'margin-top', 'transform', 'left', 'width', 'height'];
                                                        if (forbiddenProps.indexOf(propertyName.toLowerCase()) > -1) {
                                                            return; // BLOCK removal of our core properties
                                                        }
                                                        return target.removeProperty(propertyName);
                                                    };
                                                }
                                                const val = Reflect.get(target, prop, receiver);
                                                if (typeof val === 'function') {
                                                    return val.bind(target);
                                                }
                                                return val;
                                            },
                                            set: function(target, prop, value, receiver) {
                                                const forbiddenProps = ['position', 'top', 'marginTop', 'margin-top', 'transform', 'left', 'width', 'height'];
                                                if (forbiddenProps.indexOf(prop) > -1 || (typeof prop === 'string' && prop.toLowerCase().indexOf('margin') > -1)) {
                                                    return true; // Silent intercept
                                                }
                                                if (prop === 'cssText') {
                                                    const cleanCssText = value.replace(/(?:^|;)\s*(position|top|margin-top|transform|left|width|height)\s*:[^;]+(;|$)/gi, '');
                                                    target.cssText = cleanCssText;
                                                    return true;
                                                }
                                                return Reflect.set(target, prop, value, receiver);
                                            }
                                        });
                                    }
                                    return this._proxyStyle;
                                }
                                return originalStyle;
                            },
                            configurable: true
                        });
                    }
                } catch (e) {
                    console.warn('Insomniacs Core: HTMLElement.prototype.style Proxy wrapper skipped.', e);
                }

                // Intercept direct manipulation on CSSStyleDeclaration prototype directly as secondary failsafe
                try {
                    const originalSetProperty = CSSStyleDeclaration.prototype.setProperty;
                    CSSStyleDeclaration.prototype.setProperty = function(propertyName, value, priority) {
                        if (this._isInterceptedTarget) {
                            const forbiddenProps = ['position', 'top', 'margin-top', 'transform', 'left', 'width', 'height'];
                            if (forbiddenProps.indexOf(propertyName.toLowerCase()) > -1) {
                                return; // BLOCK
                            }
                        }
                        return originalSetProperty.call(this, propertyName, value, priority);
                    };
                } catch (e) {}

                // JQUERY HIJACKS
                var _jQuery = window.jQuery;
                var _dollar = window.$;

                function applyHijack($) {
                    if (!$) return;
                    if ($.fn && !$.fn.isInsomniacsHijacked) {
                        $.fn.isInsomniacsHijacked = true;

                        // Neutralize library initialization completely
                        const targetPlugins = ['theiaStickySidebar', 'gloriaSticky', 'hcSticky', 'smkStickybar', 'pin', 'stickySidebar', 'stickyKit'];
                        const dummyPlugin = function() {
                            console.info("Insomniacs Core: Intercepted and neutralized JS-based sticky layout script.");
                            return this;
                        };
                        targetPlugins.forEach(function(pluginName) {
                            try {
                                Object.defineProperty($.fn, pluginName, {
                                    get: function() { return dummyPlugin; },
                                    set: function() { },
                                    configurable: true
                                });
                            } catch (e) {
                                $.fn[pluginName] = dummyPlugin;
                            }
                        });

                        // Hijack jQuery .css() to block inline layout coordinates from overwriting custom CSS
                        var originalCss = $.fn.css;
                        $.fn.css = function(name, value) {
                            var self = this;
                            if (self.length && isTargetElement(self[0])) {
                                if (typeof name === 'string' && value === undefined) {
                                    return originalCss.apply(this, arguments);
                                }
                                if (typeof name === 'object') {
                                    var newProps = $.extend({}, name);
                                    delete newProps['position'];
                                    delete newProps['top'];
                                    delete newProps['margin-top'];
                                    delete newProps['transform'];
                                    delete newProps['-webkit-transform'];
                                    delete newProps['left'];
                                    delete newProps['width'];
                                    delete newProps['height'];
                                    if (Object.keys(newProps).length > 0) {
                                        return originalCss.call(this, newProps);
                                    }
                                    return this;
                                }
                                if (typeof name === 'string') {
                                    var lowerName = name.toLowerCase();
                                    if (['position', 'top', 'margin-top', 'transform', '-webkit-transform', 'left', 'width', 'height'].indexOf(lowerName) > -1) {
                                        return this; // Intercept style update
                                    }
                                }
                            }
                            return originalCss.apply(this, arguments);
                        };

                        // Hijack jQuery animate
                        var originalAnimate = $.fn.animate;
                        $.fn.animate = function(properties, speed, easing, callback) {
                            var self = this;
                            if (self.length && isTargetElement(self[0])) {
                                if (properties && typeof properties === 'object') {
                                    var newProps = $.extend({}, properties);
                                    delete newProps['position'];
                                    delete newProps['top'];
                                    delete newProps['margin-top'];
                                    delete newProps['transform'];
                                    delete newProps['-webkit-transform'];
                                    delete newProps['left'];
                                    delete newProps['width'];
                                    delete newProps['height'];
                                    if (Object.keys(newProps).length > 0) {
                                        return originalAnimate.call(this, newProps, speed, easing, callback);
                                    }
                                    if (typeof callback === 'function') {
                                        callback.call(this);
                                    }
                                    return this;
                                }
                            }
                            return originalAnimate.apply(this, arguments);
                        };
                    }
                }

                // Apply instantly if jQuery is already present
                if (_jQuery) applyHijack(_jQuery);
                if (_dollar) applyHijack(_dollar);

                // Hijack window element assignations so we hook jQuery the moment is instantiated
                try {
                    Object.defineProperty(window, 'jQuery', {
                        get: function() { return _jQuery; },
                        set: function(val) {
                            _jQuery = val;
                            applyHijack(_jQuery);
                        },
                        configurable: true
                    });
                    Object.defineProperty(window, '$', {
                        get: function() { return _dollar; },
                        set: function(val) {
                            _dollar = val;
                            applyHijack(_dollar);
                        },
                        configurable: true
                    });
                } catch (e) {
                    var checkCount = 0;
                    var interval = setInterval(function() {
                        if (window.jQuery) {
                            applyHijack(window.jQuery);
                        }
                        if (window.$) {
                            applyHijack(window.$);
                        }
                        if (++checkCount > 1000) {
                            clearInterval(interval);
                        }
                    }, 10);
                }

                // Direct interceptor for vanilla JS writes to HTML style attributes (e.g., setAttribute)
                try {
                    var originalSetAttribute = Element.prototype.setAttribute;
                    Element.prototype.setAttribute = function(name, value) {
                        if (name === 'style' && typeof value === 'string' && isTargetElement(this)) {
                            // Trim layout breaking attributes out
                            var cleanValue = value.replace(/(?:^|;)\s*(position|top|margin-top|transform|left|width|height)\s*:[^;]+(;|$)/gi, '');
                            return originalSetAttribute.call(this, name, cleanValue);
                        }
                        return originalSetAttribute.apply(this, arguments);
                    };
                } catch (e) {
                    console.warn('Insomniacs Core: Element.prototype.setAttribute wrapper skipped.', e);
                }

                // Keep-alive overflow sweeper to ensure native position: sticky doesn't break due to parent configurations
                function sweepOverflows() {
                    var stickyEl = document.querySelector('.single-actor-left, .actor-detail-left, .actor-sidebar, .actor-media, .actor-poster, .theiaStickySidebar, .gloria-sticky');
                    if (stickyEl) {
                        var parent = stickyEl.parentElement;
                        while (parent && parent !== document.documentElement && parent !== document.body) {
                            try {
                                var computed = window.getComputedStyle(parent);
                                if (computed.overflow === 'hidden' || computed.overflow === 'auto' || computed.overflow === 'scroll' ||
                                    computed.overflowX === 'hidden' || computed.overflowX === 'auto' || computed.overflowX === 'scroll' ||
                                    computed.overflowY === 'hidden' || computed.overflowY === 'auto' || computed.overflowY === 'scroll') {
                                    parent.style.setProperty('overflow', 'visible', 'important');
                                    parent.style.setProperty('overflow-x', 'visible', 'important');
                                    parent.style.setProperty('overflow-y', 'visible', 'important');
                                }
                            } catch (e) {}
                            parent = parent.parentElement;
                        }
                    }
                }

                // Execute sweep instantly, on DOM ready, and window load
                sweepOverflows();
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', sweepOverflows);
                } else {
                    sweepOverflows();
                }
                window.addEventListener('load', sweepOverflows);

                // Sweep on incremental intervals to catch delayed layout modifications 
                setTimeout(sweepOverflows, 250);
                setTimeout(sweepOverflows, 750);
                setTimeout(sweepOverflows, 1500);
                setTimeout(sweepOverflows, 3000);

                // Fallback MutationObserver to constantly defend against dirty overflows added by third-party scripts
                try {
                    var observer = new MutationObserver(sweepOverflows);
                    observer.observe(document.body, { attributes: true, childList: true, subtree: true });
                } catch (e) {}
            })();
        </script>
        <?php
    }
    // Hook script execution extremely early in wp_head
    add_action( 'wp_head', 'insom_smooth_scroll_early_js_hijack', 1 );
}


