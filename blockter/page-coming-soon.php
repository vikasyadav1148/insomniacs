<?php
/**
 * Template Name: Insomniacs Cinematic Release Schedule
 * Description: All-in-One Movie & TV Release Calendar v10. A premium, high-fidelity responsive dashboard with an interactive spiral-ring desk calendar, virtual cell countdowns, live-preview companion smartphone, detail modals, responsive search/filters, and an integrated sidebar planner. Runs modularly inside WordPress or as a standalone landing page template.
 * Version: 10.0 - Final Production Code
 * Author: Vikas Yadav
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ==========================================
// 1. DYNAMIC WORDPRESS POST ACCESS HOOKS
// ==========================================
/**
 * Register AJAX toggle favorites handler. Toggles favorites in the user's secure cookie.
 * This can be placed directly inside functions.php, or safely auto-registered here
 * to guarantee plug-and-play compatibility!
 */
if ( ! function_exists( 'insom_v3_toggle_fav_handler' ) ) {
    function insom_v3_toggle_fav_handler() {
        if ( isset( $_POST['post_id'] ) ) {
            $post_id = intval( $_POST['post_id'] );
            $favs_cookie = isset( $_COOKIE['idc_favs'] ) ? sanitize_text_field( $_COOKIE['idc_favs'] ) : '';
            $favs = ! empty( $favs_cookie ) ? explode( ',', $favs_cookie ) : array();

            if ( ( $key = array_search( $post_id, $favs ) ) !== false ) {
                unset( $favs[$key] );
            } else {
                $favs[] = $post_id;
            }

            $cookie_val = implode( ',', $favs );
            // Save cookie for 30 days
            setcookie( 'idc_favs', $cookie_val, time() + ( 30 * 24 * 60 * 60 ), '/', COOKIE_DOMAIN, is_ssl(), false );
            
            if ( is_user_logged_in() ) {
                $user_id = get_current_user_id();
                $post_obj = get_post( $post_id );
                if ( $post_obj ) {
                    $meta_key = ( $post_obj->post_type === 'ht_show' ) ? 'favourite_show_id' : 'favourite_mv_id';
                    $existing_favs = array_map( 'intval', (array) get_user_meta( $user_id, $meta_key ) );
                    if ( in_array( $post_id, $existing_favs, true ) ) {
                        delete_user_meta( $user_id, $meta_key, $post_id );
                    } else {
                        add_user_meta( $user_id, $meta_key, $post_id );
                    }
                }
            }

            wp_send_json_success( array(
                'post_id' => $post_id,
                'favs'    => array_values( $favs )
            ) );
        } else {
            wp_send_json_error( 'Invalid post parameters.' );
        }
        wp_die();
    }
    add_action( 'wp_ajax_insom_v3_toggle_fav', 'insom_v3_toggle_fav_handler' );
    add_action( 'wp_ajax_nopriv_insom_v3_toggle_fav', 'insom_v3_toggle_fav_handler' );
}

/**
 * Register AJAX handler to send gorgeous HTML emails for cinema release reminders.
 */
if ( ! function_exists( 'insom_v3_send_reminder_handler' ) ) {
    function insom_v3_send_reminder_handler() {
        if ( isset( $_POST['email'] ) && isset( $_POST['movie_title'] ) ) {
            $email          = sanitize_email( $_POST['email'] );
            $name           = sanitize_text_field( $_POST['name'] );
            $movie_title    = sanitize_text_field( $_POST['movie_title'] );
            $movie_type     = sanitize_text_field( $_POST['movie_type'] );
            $movie_date     = sanitize_text_field( $_POST['movie_date'] );
            $movie_duration = sanitize_text_field( $_POST['movie_duration'] );
            $movie_genre    = sanitize_text_field( $_POST['movie_genre'] );
            $movie_rating   = sanitize_text_field( $_POST['movie_rating'] );
            $movie_score    = sanitize_text_field( $_POST['movie_score'] );
            $movie_director = sanitize_text_field( $_POST['movie_director'] );
            $movie_cast     = sanitize_text_field( $_POST['movie_cast'] );
            $movie_tagline  = sanitize_text_field( $_POST['movie_tagline'] );
            $movie_desc     = wp_kses_post( $_POST['movie_description'] );
            $movie_gradient = sanitize_text_field( $_POST['movie_gradient'] );
            $movie_accent   = sanitize_text_field( $_POST['movie_accent'] );

            $accent_color = ! empty( $movie_accent ) ? $movie_accent : '#ef4444';
            $subject      = "🎬 Connection Secured: Premiere Alert for {$movie_title}!";
            $headers      = array('Content-Type: text/html; charset=UTF-8');

            ob_start();
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8">
                <title><?php echo esc_html( $movie_title ); ?></title>
                <style>
                    body {
                        background-color: #0c0c0e !important;
                        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
                        color: #f3f4f6 !important;
                        margin: 0 !important;
                        padding: 0 !important;
                    }
                    .container {
                        max-width: 600px !important;
                        margin: 40px auto !important;
                        background-color: #121216 !important;
                        border: 1px solid #222228 !important;
                        border-radius: 20px !important;
                        overflow: hidden !important;
                        box-shadow: 0 10px 40px rgba(0,0,0,0.8) !important;
                    }
                    .header {
                        background: <?php echo ! empty( $movie_gradient ) ? $movie_gradient : 'linear-gradient(135deg, #0f172a 0%, #1e1b4b 60%, #450a0a 100%)'; ?> !important;
                        padding: 40px 30px !important;
                        text-align: center !important;
                    }
                    .header h1 {
                        font-size: 32px !important;
                        font-weight: 800 !important;
                        letter-spacing: -0.025em !important;
                        margin: 10px 0 0 0 !important;
                        color: #ffffff !important;
                        text-transform: uppercase !important;
                    }
                    .badge {
                        display: inline-block !important;
                        padding: 6px 12px !important;
                        font-size: 10px !important;
                        font-weight: bold !important;
                        border-radius: 9999px !important;
                        text-transform: uppercase !important;
                        letter-spacing: 0.15em !important;
                        background-color: rgba(239, 68, 68, 0.2) !important;
                        color: <?php echo esc_attr( $accent_color ); ?> !important;
                        border: 1px solid rgba(239, 68, 68, 0.4) !important;
                    }
                    .content {
                        padding: 40px 30px !important;
                    }
                    .welcome {
                        font-size: 16px !important;
                        line-height: 1.6 !important;
                        color: #a3a3a3 !important;
                        margin-bottom: 30px !important;
                        border-bottom: 1px solid #222228 !important;
                        padding-bottom: 20px !important;
                    }
                    .welcome strong {
                        color: #ffffff !important;
                    }
                    .grid-card {
                        background-color: #18181f !important;
                        border: 1px solid #262630 !important;
                        border-radius: 16px !important;
                        padding: 24px !important;
                        margin-bottom: 30px !important;
                    }
                    .grid-title {
                        font-size: 11px !important;
                        font-weight: bold !important;
                        color: <?php echo esc_attr( $accent_color ); ?> !important;
                        letter-spacing: 0.15em !important;
                        text-transform: uppercase !important;
                        margin-bottom: 12px !important;
                    }
                    .metadata-list {
                        margin: 0 !important;
                        padding: 0 !important;
                        list-style: none !important;
                    }
                    .metadata-item {
                        display: flex !important;
                        justify-content: space-between !important;
                        padding: 10px 0 !important;
                        border-bottom: 1px solid #222228 !important;
                        font-size: 13px !important;
                    }
                    .metadata-label {
                        color: #737373 !important;
                        font-weight: 600 !important;
                    }
                    .metadata-value {
                        color: #ffffff !important;
                        font-weight: 700 !important;
                    }
                    .synopsis {
                        font-size: 14px !important;
                        line-height: 1.7 !important;
                        color: #d4d4d4 !important;
                        margin-top: 25px !important;
                    }
                    .footer {
                        background-color: #09090b !important;
                        padding: 30px !important;
                        text-align: center !important;
                        font-size: 11px !important;
                        color: #52525b !important;
                        border-top: 1px solid #18181b !important;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <span class="badge"><?php echo esc_html( $movie_type ); ?> PREMIERE ALERT</span>
                        <h1><?php echo esc_html( $movie_title ); ?></h1>
                        <?php if ( ! empty( $movie_tagline ) ) : ?>
                            <p style="color: #cbd5e1; font-style: italic; font-size: 13px; margin: 10px 0 0 0;"><?php echo esc_html( $movie_tagline ); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="content">
                        <div class="welcome">
                            Hello <strong><?php echo esc_html( $name ); ?></strong>,<br><br>
                            Your cinematic reminder has been successfully activated! Our transmission pipelines have secured coordinates for the upcoming premiere of <strong><?php echo esc_html( $movie_title ); ?></strong>.
                        </div>
                        <div class="grid-card">
                            <div class="grid-title">Premiere Specifications</div>
                            <div class="metadata-list">
                                <div class="metadata-item">
                                    <span class="metadata-label">Release Date:</span>
                                    <span class="metadata-value" style="color: <?php echo esc_attr( $accent_color ); ?>;"><?php echo esc_html( $movie_date ); ?></span>
                                </div>
                                <?php if ( ! empty( $movie_duration ) ) : ?>
                                <div class="metadata-item">
                                    <span class="metadata-label">Duration / Format:</span>
                                    <span class="metadata-value"><?php echo esc_html( $movie_duration ); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if ( ! empty( $movie_genre ) ) : ?>
                                <div class="metadata-item">
                                    <span class="metadata-label">Genre:</span>
                                    <span class="metadata-value"><?php echo esc_html( $movie_genre ); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if ( ! empty( $movie_rating ) ) : ?>
                                <div class="metadata-item">
                                    <span class="metadata-label">Rating:</span>
                                    <span class="metadata-value"><?php echo esc_html( $movie_rating ); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if ( ! empty( $movie_score ) ) : ?>
                                <div class="metadata-item">
                                    <span class="metadata-label">Cinematic Score:</span>
                                    <span class="metadata-value">⭐ <?php echo esc_html( $movie_score ); ?>/10</span>
                                </div>
                                <?php endif; ?>
                                <?php if ( ! empty( $movie_director ) ) : ?>
                                <div class="metadata-item">
                                    <span class="metadata-label">Director/Creator:</span>
                                    <span class="metadata-value"><?php echo esc_html( $movie_director ); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php if ( ! empty( $movie_desc ) ) : ?>
                                <div class="synopsis"><?php echo wp_kses_post( $movie_desc ); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="footer">
                        Sent securely from the <strong>Insomniacs Cinematic Hub</strong>.<br>
                        Stay synchronized with global entertainment developments.<br><br>
                        © <?php echo date('Y'); ?> Insomniacs | Powered by WordPress.
                    </div>
                </div>
            </body>
            </html>
            <?php
            $message = ob_get_clean();

            $mail_sent = wp_mail( $email, $subject, $message, $headers );

            wp_send_json_success( array(
                'message'   => 'Secure reminder transmission activated successfully.',
                'mail_sent' => $mail_sent
            ) );
        } else {
            wp_send_json_error( 'Invalid submission parameters.' );
        }
        wp_die();
    }
    add_action( 'wp_ajax_insom_v3_send_reminder', 'insom_v3_send_reminder_handler' );
    add_action( 'wp_ajax_nopriv_insom_v3_send_reminder', 'insom_v3_send_reminder_handler' );
}

/**
 * Palette Color Generator - Creates premium, randomized yet deterministic 
 * cinematic gradients and matching neon accent colors based on post ID.
 */
function get_cinematic_colors( $id ) {
    $palettes = array(
        array( 'gradient' => 'linear-gradient(135deg, #010409 0%, #0c1a30 50%, #c2a13b 100%)', 'accent' => '#d4af37' ), // Gold Crown
        array( 'gradient' => 'linear-gradient(135deg, #000000 0%, #450a0a 60%, #b91c1c 100%)', 'accent' => '#ef4444' ), // Crimson Fire
        array( 'gradient' => 'linear-gradient(135deg, #0b1329 0%, #1e1b4b 50%, #dc2626 100%)', 'accent' => '#3b82f6' ), // Deep Sky
        array( 'gradient' => 'linear-gradient(135deg, #020617 0%, #030712 60%, #1e40af 100%)', 'accent' => '#38bdf8' ), // Cyber Blue
        array( 'gradient' => 'linear-gradient(135deg, #180828 0%, #3b0764 50%, #db2777 100%)', 'accent' => '#e879f9' ), // Neon Purple
        array( 'gradient' => 'linear-gradient(135deg, #0f172a 0%, #1c1917 50%, #78350f 100%)', 'accent' => '#f59e0b' ), // Molten Amber
    );
    
    // Check if custom metadata override exists
    $meta_gradient = get_post_meta( $id, 'poster_gradient', true );
    $meta_accent   = get_post_meta( $id, 'accent_color', true );
    
    if ( ! empty( $meta_gradient ) && ! empty( $meta_accent ) ) {
        return array( 'gradient' => $meta_gradient, 'accent' => $meta_accent );
    }
    
    // Match deterministically
    $index = $id % count( $palettes );
    return $palettes[$index];
}

/**
 * Robustly queries/recalculates the averaged review ratings and count for a movie or TV show.
 * Supports Blockter comments system or general plugin ratings stored in DB comments metrics.
 */
function insom_get_reviews_stats( $id ) {
    // 1. Check direct aggregated postmetas first
    $post_avg = get_post_meta( $id, 'blockter_rating', true ) 
                ?: get_post_meta( $id, 'user_rating', true ) 
                ?: get_post_meta( $id, '_blockter_rating', true )
                ?: get_post_meta( $id, 'comment_rating_average', true )
                ?: get_post_meta( $id, '_user_rating_average', true )
                ?: '';
                
    $post_count = get_post_meta( $id, 'blockter_reviews_count', true )
                  ?: get_post_meta( $id, 'blockter_votes', true )
                  ?: get_post_meta( $id, 'comment_rating_count', true )
                  ?: get_post_meta( $id, 'votes_count', true )
                  ?: get_post_meta( $id, 'votes', true )
                  ?: get_post_meta( $id, 'reviews_count', true )
                  ?: get_post_meta( $id, '_reviews_count', true )
                  ?: '';

    if ( ! empty( $post_avg ) && floatval( $post_avg ) > 0 ) {
        return array(
            'average'    => round( floatval( $post_avg ), 1 ),
            'count'      => ! empty( $post_count ) ? intval( $post_count ) : 1,
            'has_rating' => true
        );
    }

    // 2. Query comments of this post
    $comments = get_comments( array(
        'post_id' => $id,
        'status'  => 'approve'
    ) );

    if ( ! empty( $comments ) && is_array( $comments ) ) {
        $ratings = array();
        foreach ( $comments as $comment ) {
            $c_id = $comment->comment_ID;
            $all_c_meta = get_comment_meta( $c_id );
            
            $found_val = false;
            foreach ( array( 'comment_rating', 'rating', 'review_rating', 'rating_val', 'score' ) as $rk ) {
                if ( isset( $all_c_meta[$rk] ) ) {
                    $val = is_array( $all_c_meta[$rk] ) ? $all_c_meta[$rk][0] : $all_c_meta[$rk];
                    $val = floatval( $val );
                    if ( $val > 0 ) {
                        $ratings[] = $val;
                        $found_val = true;
                        break;
                    }
                }
            }
            
            if ( ! $found_val && ! empty( $all_c_meta ) && is_array( $all_c_meta ) ) {
                foreach ( $all_c_meta as $key => $values ) {
                    $key_l = strtolower( $key );
                    if ( strpos( $key_l, 'rating' ) !== false || strpos( $key_l, 'score' ) !== false || strpos( $key_l, 'rate' ) !== false ) {
                        $val = is_array( $values ) ? $values[0] : $values;
                        $val = floatval( $val );
                        if ( $val > 0 ) {
                            $ratings[] = $val;
                            break;
                        }
                    }
                }
            }
        }
        
        if ( ! empty( $ratings ) ) {
            $avg = array_sum( $ratings ) / count( $ratings );
            return array(
                'average'    => round( $avg, 1 ),
                'count'      => count( $ratings ),
                'has_rating' => true
            );
        }
    }

    return array(
        'average'    => 0,
        'count'      => 0,
        'has_rating' => false
    );
}

/**
 * Robustly sanitizes and encodes mixed structures for safe output to client JS.
 * Prevents invalid character sequences from causing empty JSON encoding responses.
 */
if ( ! function_exists( 'insom_safe_json_encode_diagnostic' ) ) {
    function insom_utf8_clean( $mixed ) {
        if ( is_array( $mixed ) ) {
            $clean = array();
            foreach ( $mixed as $key => $value ) {
                $clean[insom_utf8_clean( $key )] = insom_utf8_clean( $value );
            }
            return $clean;
        } elseif ( is_string( $mixed ) ) {
            if ( function_exists( 'mb_convert_encoding' ) ) {
                $mixed = mb_convert_encoding( $mixed, 'UTF-8', 'UTF-8' );
            } elseif ( function_exists( 'iconv' ) ) {
                $mixed = @iconv( 'UTF-8', 'UTF-8//IGNORE', $mixed );
            }
            return $mixed;
        }
        return $mixed;
    }

    function insom_safe_json_encode_diagnostic( $data, $fallback = '{}' ) {
        $clean_data = insom_utf8_clean( $data );
        $flags = JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
        if ( defined( 'JSON_PARTIAL_OUTPUT_ON_ERROR' ) ) {
            $flags |= JSON_PARTIAL_OUTPUT_ON_ERROR;
        }
        $encoded = json_encode( $clean_data, $flags );
        if ( $encoded === false ) {
            return $fallback;
        }
        return $encoded;
    }
}

/**
 * Super-robust recursive parser to extract cast/actor names from any mixed WP metadata,
 * supporting arrays, JSON objects/lists, serialized arrays, comma-delimited strings, or numeric term IDs.
 */
if ( ! function_exists( 'insom_extract_cast_names' ) ) {
    function insom_extract_cast_names( $value ) {
        if ( empty( $value ) ) {
            return array();
        }

        // If it's a serialized string, unserialize it first
        if ( is_string( $value ) && is_serialized( $value ) ) {
            $value = maybe_unserialize( $value );
        }

        // If it's a JSON string, decode it
        if ( is_string( $value ) && ( strpos( $value, '[' ) === 0 || strpos( $value, '{' ) === 0 ) ) {
            $decoded = json_decode( $value, true );
            if ( json_last_error() === JSON_ERROR_NONE ) {
                $value = $decoded;
            }
        }

        $names = array();

        if ( is_array( $value ) ) {
            foreach ( $value as $k => $v ) {
                if ( is_array( $v ) ) {
                    // Extract standard name/title fields from associative structures
                    if ( isset( $v['name'] ) && is_string( $v['name'] ) && ! empty( $v['name'] ) ) {
                        $names[] = trim( $v['name'] );
                    } elseif ( isset( $v['actor_name'] ) && is_string( $v['actor_name'] ) && ! empty( $v['actor_name'] ) ) {
                        $names[] = trim( $v['actor_name'] );
                    } elseif ( isset( $v['actor'] ) && is_string( $v['actor'] ) && ! empty( $v['actor'] ) ) {
                        $names[] = trim( $v['actor'] );
                    } elseif ( isset( $v['title'] ) && is_string( $v['title'] ) && ! empty( $v['title'] ) ) {
                        $names[] = trim( $v['title'] );
                    } elseif ( isset( $v['cast_name'] ) && is_string( $v['cast_name'] ) && ! empty( $v['cast_name'] ) ) {
                        $names[] = trim( $v['cast_name'] );
                    } else {
                        // Recursively search nested arrays
                        $names = array_merge( $names, insom_extract_cast_names( $v ) );
                    }
                } else if ( is_string( $v ) ) {
                    $cleaned = trim( $v );
                    if ( ! empty( $cleaned ) ) {
                        if ( strpos( $cleaned, ',' ) !== false && ! ( strpos( $cleaned, '[' ) === 0 || strpos( $cleaned, '{' ) === 0 ) ) {
                            $parts = explode( ',', $cleaned );
                            foreach ( $parts as $p ) {
                                $p_trim = trim( $p );
                                if ( ! empty( $p_trim ) && strlen( $p_trim ) > 1 ) {
                                    $names[] = $p_trim;
                                }
                            }
                        } else if ( strlen( $cleaned ) > 1 ) {
                            if ( strpos( $cleaned, '[' ) === 0 || strpos( $cleaned, '{' ) === 0 ) {
                                $names = array_merge( $names, insom_extract_cast_names( $cleaned ) );
                            } else {
                                $names[] = $cleaned;
                            }
                        }
                    }
                } else if ( is_numeric( $v ) ) {
                    $term = get_term( intval( $v ) );
                    if ( $term && ! is_wp_error( $term ) ) {
                        $names[] = $term->name;
                    } else {
                        $post = get_post( intval( $v ) );
                        if ( $post && in_array( $post->post_type, array('actor', 'ht_actor', 'wp_actor', 'producer', 'director') ) ) {
                            $names[] = $post->post_title;
                        }
                    }
                }
            }
        } else if ( is_string( $value ) ) {
            $cleaned = trim( $value );
            if ( strpos( $cleaned, ',' ) !== false && ! ( strpos( $cleaned, '[' ) === 0 || strpos( $cleaned, '{' ) === 0 ) ) {
                $parts = explode( ',', $cleaned );
                foreach ( $parts as $p ) {
                    $p_trim = trim( $p );
                    if ( ! empty( $p_trim ) && strlen( $p_trim ) > 1 ) {
                        $names[] = $p_trim;
                    }
                }
            } else if ( strlen( $cleaned ) > 1 ) {
                $names[] = $cleaned;
            }
        }

        $blacklist = array(
            'cast members', 'cast member', 'cast', 'actor', 'actors', 'star', 'stars', 'starring', 
            'director', 'directors', 'producer', 'producers', 'crew', 'talent', 
            'showrunner', 'creator', 'n/a', 'none', 'no cast', 'unknown', 'tba', 'tbd',
            'crew & guests'
        );
        $filtered_names = array();
        foreach ( $names as $n ) {
            $n_clean = trim( $n );
            if ( ! in_array( strtolower( $n_clean ), $blacklist ) ) {
                $filtered_names[] = $n_clean;
            }
        }
        return array_values( array_unique( array_filter( $filtered_names ) ) );
    }
}

// ==========================================
// 1.5. ADMIN MENU & SETTINGS BACKEND FOR SCHEDULE
// ==========================================
if ( is_admin() ) {
    add_action( 'admin_menu', 'insom_sched_add_admin_menu' );
    add_action( 'admin_init', 'insom_sched_settings_init' );
    add_action( 'admin_enqueue_scripts', function($hook) {
        if ( 'toplevel_page_insomniacs-schedule-settings' === $hook ) {
            wp_enqueue_media();
        }
    });
}

if ( ! function_exists( 'insom_sched_add_admin_menu' ) ) {
    function insom_sched_add_admin_menu() {
        add_menu_page(
            'Insomniacs Schedule',
            'Insomniacs Schedule',
            'manage_options',
            'insomniacs-schedule-settings',
            'insom_sched_settings_layout',
            'dashicons-calendar-alt',
            60
        );
    }
}

if ( ! function_exists( 'insom_sched_settings_init' ) ) {
    function insom_sched_settings_init() {
        register_setting( 'insom_sched_settings_group', 'insom_sched_post_types' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_title_1' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_title_2' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_description' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_accent_color' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_bg_color' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_fallback_bg' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_query_orderby' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_query_order' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_hide_past' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_excluded_posts' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_pinned_post' );
    }
}

if ( ! function_exists( 'insom_sched_settings_layout' ) ) {
    function insom_sched_settings_layout() {
        $post_types = get_option('insom_sched_post_types', 'ht_movie,ht_show,ht_tv_show');
        $title_1 = get_option('insom_sched_title_1', 'MOVIES & TV');
        $title_2 = get_option('insom_sched_title_2', 'SCHEDULE');
        $description = get_option('insom_sched_description', 'Interactive cinematic scheduler. Sync schedules directly to your personal planners and track upcoming releases.');
        $accent_color = get_option('insom_sched_accent_color', '#ef4444');
        $bg_color = get_option('insom_sched_bg_color', '#040406');
        $fallback_bg = get_option('insom_sched_fallback_bg', '');
        $orderby = get_option('insom_sched_query_orderby', 'date');
        $order = get_option('insom_sched_query_order', 'ASC');

        $hide_past = get_option('insom_sched_hide_past', 'yes_today');
        $excluded_posts = get_option('insom_sched_excluded_posts', '');
        if (is_array($excluded_posts)) {
            $excluded_ids = $excluded_posts;
        } else {
            $excluded_ids = !empty($excluded_posts) ? explode(',', $excluded_posts) : array();
        }
        $excluded_ids = array_map('trim', $excluded_ids);
        $pinned_post = get_option('insom_sched_pinned_post', '');

        if ( isset($_GET['settings-updated']) && $_GET['settings-updated'] ) {
            echo '<div class="notice notice-success is-dismissible"><p><strong>Insomniacs Schedule Configuration Synchronized Safely.</strong></p></div>';
        }
        ?>
        <div class="wrap" style="background: #0d0e12; color: #f1f1f1; padding: 25px; border-radius: 12px; max-width: 950px; margin-top: 20px; font-family: 'Segoe UI', system-ui, sans-serif; box-shadow: 0 10px 30px rgba(0,0,0,0.35);">
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #2d303b; padding-bottom: 20px; margin-bottom: 25px;">
                <div>
                    <h1 style="color: #fff; font-size: 28px; font-weight: 800; margin: 0; text-transform: uppercase; letter-spacing: -1px; text-shadow: 0 0 10px rgba(239,68,68,0.2);">⚡ Insomniacs Schedule Deck</h1>
                    <p style="color: #8b92a6; margin: 5px 0 0 0; font-size: 13px;">Manage colors, query parameters, landing page titles, and fallback artwork graphics for the Release Schedule Page Template.</p>
                </div>
                <div style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); padding: 8px 16px; border-radius: 30px; font-size: 11px; font-weight: 700; color: #ef4444; letter-spacing: 1px; text-transform: uppercase;">
                    Scheduler Core Active
                </div>
            </div>

            <form method="post" action="options.php" style="background: #15171e; padding: 25px; border-radius: 10px; border: 1px solid #232731;">
                <?php settings_fields( 'insom_sched_settings_group' ); ?>
                <?php do_settings_sections( 'insom_sched_settings_group' ); ?>

                <!-- SECTION 1: TITLE & DETAILS -->
                <h3 style="color: #ef4444; border-bottom: 1px solid rgba(239,68,68,0.15); padding-bottom: 8px; margin: 0 0 15px 0; font-size: 16px; text-transform: uppercase; letter-spacing: 1px;">📝 Section 1: Page Title & Custom Copy</h3>
                <table class="form-table" style="width: 100%; border-collapse: separate; border-spacing: 0 12px; margin-bottom: 30px;">
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Page Title Line 1</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Main header line.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_sched_title_1" value="<?php echo esc_attr($title_1); ?>" style="width: 100%; max-width: 500px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Page Title Line 2</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Highlighted header line (Red Glow).</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_sched_title_2" value="<?php echo esc_attr($title_2); ?>" style="width: 100%; max-width: 500px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Page Subtitle / Description</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Explanatory text under titles.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <textarea name="insom_sched_description" rows="3" style="width: 100%; max-width: 500px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 10px; border-radius: 6px; font-size: 13px; resize: vertical;" required><?php echo esc_textarea($description); ?></textarea>
                        </td>
                    </tr>
                </table>

                <!-- SECTION 2: GLOBAL COLORS & FALLBACK IMAGES -->
                <h3 style="color: #ef4444; border-bottom: 1px solid rgba(239,68,68,0.15); padding-bottom: 8px; margin: 30px 0 15px 0; font-size: 16px; text-transform: uppercase; letter-spacing: 1px;">🎨 Section 2: Global Styling & Fallback Artwork</h3>
                <table class="form-table" style="width: 100%; border-collapse: separate; border-spacing: 0 12px; margin-bottom: 30px;">
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Primary Accent Theme Color</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">The primary neon brand color.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="color" name="insom_sched_accent_color" value="<?php echo esc_attr($accent_color); ?>" style="width: 60px; height: 35px; border: none; background: transparent; cursor: pointer; vertical-align: middle;" />
                            <code style="background: #0d0e12; color: #ef4444; padding: 6px 10px; border-radius: 4px; margin-left: 10px; font-size: 12px; border: 1px solid #232731; vertical-align: middle;"><?php echo esc_html($accent_color); ?></code>
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">App Background Color</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Default page background color hex.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="color" name="insom_sched_bg_color" value="<?php echo esc_attr($bg_color); ?>" style="width: 60px; height: 35px; border: none; background: transparent; cursor: pointer; vertical-align: middle;" />
                            <code style="background: #0d0e12; color: #ffffff; padding: 6px 10px; border-radius: 4px; margin-left: 10px; font-size: 12px; border: 1px solid #232731; vertical-align: middle;"><?php echo esc_html($bg_color); ?></code>
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Fallback Poster Artwork Image</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Used if a movie or show does not specify a featured image thumbnail.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <div style="display: flex; gap: 8px; width: 100%; max-width: 500px; align-items: center;">
                                <input type="text" id="insom_sched_fallback_bg" name="insom_sched_fallback_bg" value="<?php echo esc_url($fallback_bg); ?>" style="flex: 1; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 12px;" />
                                <button type="button" class="insom-sched-media-upload-trigger button button-secondary" data-target="insom_sched_fallback_bg" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15) !important; color: #ffffff !important; padding: 4px 14px; border-radius: 6px; height: 35px; line-height: 25px;">
                                    Library...
                                </button>
                            </div>
                        </td>
                    </tr>
                </table>

                <!-- SECTION 3: CORE DATA & QUERY CODES -->
                <h3 style="color: #ef4444; border-bottom: 1px solid rgba(239,68,68,0.15); padding-bottom: 8px; margin: 30px 0 15px 0; font-size: 16px; text-transform: uppercase; letter-spacing: 1px;">💾 Section 3: Database Query & Custom Sorting</h3>
                <table class="form-table" style="width: 100%; border-collapse: separate; border-spacing: 0 12px; margin-bottom: 30px;">
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Target WordPress Post Types</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Comma-separated listing of custom post slugs (e.g. <code>ht_movie,ht_show,ht_tv_show</code>).</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_sched_post_types" value="<?php echo esc_attr($post_types); ?>" style="width: 100%; max-width: 500px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Database Query Order (Chronology)</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Ascending or Descending calendar listings feed.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <select name="insom_sched_query_order" style="background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px; width: 180px;">
                                <option value="ASC" <?php selected($order, 'ASC'); ?>>Ascending (ASC)</option>
                                <option value="DESC" <?php selected($order, 'DESC'); ?>>Descending (DESC)</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Query Sort Parameter (Order By)</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Variable determining sorting key query array.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <select name="insom_sched_query_orderby" style="background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px; width: 180px;">
                                <option value="date" <?php selected($orderby, 'date'); ?>>Published Date</option>
                                <option value="title" <?php selected($orderby, 'title'); ?>>Alphabetical Title</option>
                                <option value="ID" <?php selected($orderby, 'ID'); ?>>Specific ID</option>
                                <option value="menu_order" <?php selected($orderby, 'menu_order'); ?>>WordPress Menu Order</option>
                                <option value="rand" <?php selected($orderby, 'rand'); ?>>Shuffled / Random (rand)</option>
                            </select>
                        </td>
                    </tr>
                </table>

                <!-- SECTION 4: RELEASES FILTER, EXCLUSIONS & FEATURED PIN -->
                <h3 style="color: #ef4444; border-bottom: 1px solid rgba(239,68,68,0.15); padding-bottom: 8px; margin: 30px 0 15px 0; font-size: 16px; text-transform: uppercase; letter-spacing: 1px;">📌 Section 4: Releases Filtering, Manual Hiding & Countdown Pin</h3>
                <table class="form-table" style="width: 100%; border-collapse: separate; border-spacing: 0 12px; margin-bottom: 30px;">
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Auto-Hide Released Items</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Choose when to automatically remove items whose countdown hits zero/date is passed.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <select name="insom_sched_hide_past" style="background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px; width: 100%; max-width: 500px;">
                                <option value="yes_today" <?php selected($hide_past, 'yes_today'); ?>>Hide on Release Day & Past (as soon as countdown hits 0 / date <= Today)</option>
                                <option value="yes_past" <?php selected($hide_past, 'yes_past'); ?>>Hide after Release Day has passed (date < Today)</option>
                                <option value="no" <?php selected($hide_past, 'no'); ?>>Show All (Keep displaying past releases on the list)</option>
                            </select>
                        </td>
                    </tr>
                    
                    <?php
                    // Query active DB movies/TV shows to showcase for Pinning & Manual Exclusion
                    $post_types_arr = array_map('trim', explode(',', $post_types));
                    $all_db_posts_list = get_posts(array(
                        'post_type'      => $post_types_arr,
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'orderby'        => 'title',
                        'order'          => 'ASC'
                    ));

                    $display_options_posts = array();
                    if (!empty($all_db_posts_list)) {
                        foreach ($all_db_posts_list as $p_item) {
                            $display_options_posts[] = array('id' => (string)$p_item->ID, 'title' => $p_item->post_title . ' [WP Database]');
                        }
                    } else {
                        // Showcase dummy fallback options so page is instantly testable and manageable for Admin
                        $display_options_posts[] = array('id' => 'toy-story-5', 'title' => 'TOY STORY 5 [Mock Showcase]');
                        $display_options_posts[] = array('id' => 'house-of-the-dragon', 'title' => 'HOUSE OF THE DRAGON [Mock Showcase]');
                        $display_options_posts[] = array('id' => 'the-odyssey', 'title' => 'ODYSSEY [Mock Showcase]');
                    }
                    ?>
                    
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Featured / PIN Item</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Choose a specific movie or series to feature permanently as the default active countdown pinned item.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <select name="insom_sched_pinned_post" style="background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px; width: 100%; max-width: 500px;">
                                <option value="">None - Automatic (First Chronological Upcoming Release)</option>
                                <?php foreach ($display_options_posts as $opt_p): ?>
                                    <option value="<?php echo esc_attr($opt_p['id']); ?>" <?php selected($pinned_post, $opt_p['id']); ?>>
                                        <?php echo esc_html($opt_p['title']); ?> (ID: <?php echo esc_html($opt_p['id']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Manually Exclude / Hide Items</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Select which specific releases to manually hide from the schedule page (regardless of their date).</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <!-- Hidden default value to reset array options when all checkboxes are deselected -->
                            <input type="hidden" name="insom_sched_excluded_posts" value="" />
                            <div style="background: #0d0e12; border: 1px solid #2d303b; border-radius: 6px; max-height: 200px; overflow-y: auto; padding: 12px; max-width: 500px;">
                                <?php foreach ($display_options_posts as $opt_p): ?>
                                    <label style="display: flex; align-items: center; margin-bottom: 8px; color: #fff; font-size: 12px; cursor: pointer; user-select: none;">
                                        <input type="checkbox" name="insom_sched_excluded_posts[]" value="<?php echo esc_attr($opt_p['id']); ?>" <?php checked(in_array((string)$opt_p['id'], $excluded_ids)); ?> style="margin-right: 8px; cursor: pointer;" />
                                        <span><?php echo esc_html($opt_p['title']); ?> (ID: <?php echo esc_html($opt_p['id']); ?>)</span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </td>
                    </tr>
                </table>

                <div style="margin-top: 30px; border-top: 1px solid #2d303b; padding-top: 20px; text-align: right;">
                    <?php submit_button( 'Save Schedule Configuration', 'primary', 'submit', false, array( 'style' => 'background: #ef4444; color: #fff; border: none; font-weight: 800; text-transform: uppercase; padding: 12px 30px; font-size: 13px; border-radius: 6px; cursor: pointer; text-shadow: none; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.25);' ) ); ?>
                </div>
            </form>
        </div>

        <script>
        jQuery(document).ready(function($){
            $('.insom-sched-media-upload-trigger').click(function(e) {
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
        });
        </script>
        <?php
    }
}

// ==========================================
// 2. QUERY WORDPRESS RELEASES
// ==========================================

// Define the exact types from database settings
$post_types_opt = get_option('insom_sched_post_types', 'ht_movie,ht_show,ht_tv_show');
$detected_post_types = array_map('trim', explode(',', $post_types_opt));

// Build the array for the Admin Debugger (maintains compatibility with your original code)
$all_registered_public_types = array();
foreach ( $detected_post_types as $pt ) {
    $obj = new stdClass();
    $obj->name = $pt;
    $obj->label = ucfirst(str_replace('_', ' ', $pt));
    $all_registered_public_types[] = $obj;
}

// Set up the Query
$args = array(
    'post_type'      => $detected_post_types, 
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => get_option('insom_sched_query_orderby', 'date'),
    'order'          => get_option('insom_sched_query_order', 'ASC'),
);

$query = new WP_Query( $args );
$posts_data = array();
$today = date('Y-m-d');

// Diagnostic logs for WordPress Administrators
$cinematic_diagnostics = array(
    'post_types_queried'     => $detected_post_types,
    'all_registered_public'  => array(),
    'posts_diagnosed'        => array(),
);

if ( ! empty( $all_registered_public_types ) ) {
    foreach ( $all_registered_public_types as $pt_obj ) {
        $cinematic_diagnostics['all_registered_public'][] = array(
            'name'  => $pt_obj->name,
            'label' => $pt_obj->label,
        );
    }
}

if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
        $query->the_post();
        $id = get_the_ID();
        // --- ROBUST DATE RESOLUTION WITH TYPE-SPECIFIC CONDITION ---
        $fw_options = get_post_meta($id, 'fw_options', true);
        $post_type = get_post_type();
        $resolved_date = '';

        // Check if current post is a TV Show or Series based on your post types
        $is_tv_series = in_array($post_type, array('ht_show', 'ht_tv_show'));

        if ($is_tv_series && is_array($fw_options)) {
            // TV SPECIFIC: Target the nested 'air_date' inside 'seasons'
            array_walk_recursive($fw_options, function($value, $key) use (&$resolved_date) {
                if ($key === 'air_date' && !empty($value)) $resolved_date = $value;
            });
        } else if (is_array($fw_options)) {
            // MOVIE SPECIFIC: Target the nested 'release_date' inside 'fw_options'
            array_walk_recursive($fw_options, function($value, $key) use (&$resolved_date) {
                if ($key === 'release_date' && !empty($value)) $resolved_date = $value;
            });
        }
        $release_date_from_fw = $resolved_date;

        // Fallback sequence: 
        // 1. If TV/movie parsing above found a date, use it.
        // 2. Otherwise, check standard meta keys (handles movies & fallback for TV)
        // 3. Finally, fallback to publish date
        $resolved_date = $resolved_date ?: get_post_meta($id, 'release_date', true) ?: get_post_meta($id, 'ht_movie_release_date', true) ?: get_the_date('Y-m-d');
        
        // Calculate timestamp from the resolved date
        $timestamp = strtotime($resolved_date);
        
        // Update display variables
        $resolved_date = date('Y-m-d', $timestamp);
        $dayNum    = (int) date( 'j', $timestamp );
        $monthStr  = strtoupper( date( 'M', $timestamp ) );
        $monthNum  = (int) date( 'm', $timestamp );
        $yearNum   = (int) date( 'Y', $timestamp );
        $displayD  = date( 'j M', $timestamp );
        
        // Fallback or dynamic URL logic
        $cal_date_val = ( $resolved_date < $today ) ? $today : $resolved_date;

        // Diagnostic support variables
        $all_post_meta = get_post_meta($id);
        $chosen_meta_key = $release_date_from_fw ? 'fw_options[0][release_date]' : (get_post_meta($id, 'release_date', true) ? 'release_date' : (get_post_meta($id, 'ht_movie_release_date', true) ? 'ht_movie_release_date' : 'post_publish_date'));
        
        // Get custom metadata values
        $duration = get_post_meta( $id, 'duration', true );
        if ( empty( $duration ) ) {
            $duration = function_exists( 'fw_get_db_post_option' ) ? fw_get_db_post_option( $id, 'runtime' ) : '';
        }
        if ( empty( $duration ) ) {
            $duration = function_exists( 'fw_get_db_post_option' ) ? fw_get_db_post_option( $id, 'episode_runtime' ) : '';
            if ( ! empty( $duration ) ) {
                $duration = $duration . ' Min per Ep';
            }
        }
        if ( empty( $duration ) && function_exists( 'fw_get_db_post_option' ) ) {
            $seasons = fw_get_db_post_option( $id, 'seasons' );
            if ( ! empty( $seasons ) && is_array( $seasons ) ) {
                $eps_count = 0;
                foreach ( $seasons as $se_v ) {
                    if ( isset( $se_v['episodes'] ) && is_array( $se_v['episodes'] ) ) {
                        $eps_count += count( $se_v['episodes'] );
                    }
                }
                if ( $eps_count > 0 ) {
                    $duration = count($seasons) . ' Season' . (count($seasons) > 1 ? 's' : '') . ' (' . $eps_count . ' Ep' . ($eps_count > 1 ? 's' : '') . ')';
                } else {
                    $duration = count($seasons) . ' Season' . (count($seasons) > 1 ? 's' : '');
                }
            }
        }

        $rating = get_post_meta( $id, 'rating', true ) 
                  ?: get_post_meta( $id, 'certification', true ) 
                  ?: get_post_meta( $id, 'content_rating', true ) 
                  ?: get_post_meta( $id, 'age_rating', true ) 
                  ?: get_post_meta( $id, 'mpaa', true );
        
        if ( empty( $rating ) && function_exists( 'fw_get_db_post_option' ) ) {
            $rating = fw_get_db_post_option( $id, 'rating', true ) 
                      ?: fw_get_db_post_option( $id, 'certification', true ) 
                      ?: fw_get_db_post_option( $id, 'content_rating', true ) 
                      ?: fw_get_db_post_option( $id, 'age_rating', true ) 
                      ?: fw_get_db_post_option( $id, 'mpaa', true );
        }
        
        if ( empty( $rating ) && ! empty( $all_post_meta ) ) {
            foreach ( $all_post_meta as $mk => $mvals ) {
                $mk_lower = strtolower( $mk );
                if ( 
                    ( strpos( $mk_lower, 'certification' ) !== false || strpos( $mk_lower, 'age_rating' ) !== false || strpos( $mk_lower, 'mpaa' ) !== false || ( strpos( $mk_lower, 'rating' ) !== false && strpos( $mk_lower, 'blockter' ) === false && strpos( $mk_lower, 'user' ) === false ) ) 
                    && ! empty( $mvals ) 
                ) {
                    $cand = is_array( $mvals ) ? $mvals[0] : $mvals;
                    if ( is_string( $cand ) && strlen( $cand ) > 0 && strlen( $cand ) < 10 && ! is_numeric( $cand ) ) {
                        $rating = $cand;
                        break;
                    }
                }
            }
        }
        
        if ( empty( $rating ) ) {
            $rating = 'PG-13';
        }

        $tagline  = get_post_meta( $id, 'tagline', true ) ?: get_post_meta( $id, 'tagline', true );
        $score    = get_post_meta( $id, 'score', true ) ?: '8.5';
        $director = get_post_meta( $id, 'director', true ) ?: get_post_meta( $id, 'director_or_creator', true ) ?: 'Director';
        
        // Robust trailer URL resolution
        $format_to_embed = function( $val ) {
            $val = trim( $val );
            if ( empty( $val ) ) return '';
            
            // Check for relative iframe source or raw HTML
            if ( preg_match( '/src=["\']([^"\']+)["\']/', $val, $matches ) ) {
                $val = $matches[1];
            }
            
            // YouTube matching
            if ( preg_match( '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $val, $matches ) ) {
                return 'https://www.youtube.com/embed/' . $matches[1];
            }
            
            // Vimeo matching
            if ( preg_match( '/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)/', $val, $matches ) ) {
                return 'https://player.vimeo.com/video/' . $matches[3];
            }
            
            // YouTube alphanumeric hash (11 characters)
            if ( strlen( $val ) === 11 && preg_match( '/^[A-Za-z0-9_-]{11}$/', $val ) ) {
                return 'https://www.youtube.com/embed/' . $val;
            }
            
            if ( filter_var( $val, FILTER_VALIDATE_URL ) ) {
                return $val;
            }
            
            return '';
        };

        $extract_trailer_rec = null;
        $extract_trailer_rec = function( $data ) use ( &$extract_trailer_rec, $format_to_embed ) {
            if ( empty( $data ) ) return '';
            
            if ( is_string( $data ) && is_serialized( $data ) ) {
                $data = maybe_unserialize( $data );
            }
            
            if ( is_string( $data ) && ( strpos( $data, '[' ) === 0 || strpos( $data, '{' ) === 0 ) ) {
                $decoded = json_decode( $data, true );
                if ( json_last_error() === JSON_ERROR_NONE ) {
                    $data = $decoded;
                }
            }
            
            if ( is_array( $data ) ) {
                // High-priority keys at this specific nesting level
                $priority_keys = array( 'video_url', 'youtube_url', 'trailer_url', 'video', 'youtube', 'trailer', 'embed' );
                foreach ( $priority_keys as $pk ) {
                     if ( isset( $data[$pk] ) && ! empty( $data[$pk] ) ) {
                          $val = $data[$pk];
                          if ( is_array( $val ) ) {
                              foreach ( $val as $v_item ) {
                                  if ( ! empty( $v_item ) && is_string( $v_item ) ) {
                                      $url = $format_to_embed( $v_item );
                                      if ( ! empty( $url ) ) return $url;
                                  }
                              }
                          } elseif ( is_string( $val ) ) {
                              $url = $format_to_embed( $val );
                              if ( ! empty( $url ) ) return $url;
                          }
                     }
                }
                
                // Prioritize seasons' first list item or recursive season scan
                if ( isset( $data['seasons'] ) && is_array( $data['seasons'] ) ) {
                    foreach ( $data['seasons'] as $season ) {
                        $res = $extract_trailer_rec( $season );
                        if ( ! empty( $res ) ) return $res;
                    }
                }
                
                // General depth-first recursive walk
                foreach ( $data as $key => $val ) {
                    if ( $key === 'seasons' ) continue; // Checked already
                    $res = $extract_trailer_rec( $val );
                    if ( ! empty( $res ) ) return $res;
                }
            } elseif ( is_string( $data ) ) {
                $url = $format_to_embed( $data );
                if ( ! empty( $url ) ) return $url;
            }
            
            return '';
        };

        // Determine base url from WordPress meta properties
        $trailer_url = get_post_meta( $id, 'trailer_url', true ) 
                       ?: get_post_meta( $id, 'youtube_url', true ) 
                       ?: get_post_meta( $id, 'video_url', true ) 
                       ?: get_post_meta( $id, 'trailer', true ) 
                       ?: get_post_meta( $id, 'youtube', true ) 
                       ?: '';
                       
        // If meta was a simple URL, format it properly, otherwise scan fw_options!
        if ( ! empty( $trailer_url ) ) {
            $trailer_url = $format_to_embed( $trailer_url );
        }
        
        if ( empty( $trailer_url ) && ! empty( $fw_options ) ) {
            $trailer_url = $extract_trailer_rec( $fw_options );
        }
        
        // Fetch Cast Members (Support both custom arrays, serialized lists, comma separated text, taxonomy terms, or fw_options nested objects/structures)
        $cast_array = array();

        // --- STEP 1: DYNAMIC WORDPRESS TAXONOMY RESOLUTION ---
        // Dynamically query all taxonomies registered for the current post, sorting and prioritizing those matching "cast", "actor", "star", etc.
        try {
            $post_type_current = get_post_type($id);
            $registered_taxonomies = get_object_taxonomies($post_type_current);
            if (!empty($registered_taxonomies) && is_array($registered_taxonomies)) {
                $priority_taxes = array();
                $backup_taxes = array();
                
                foreach ($registered_taxonomies as $tax) {
                    $tax_lower = strtolower($tax);
                    if (
                        strpos($tax_lower, 'actor') !== false ||
                        strpos($tax_lower, 'cast') !== false ||
                        strpos($tax_lower, 'star') !== false ||
                        strpos($tax_lower, 'crew') !== false ||
                        strpos($tax_lower, 'talent') !== false ||
                        strpos($tax_lower, 'director') !== false
                    ) {
                        $priority_taxes[] = $tax;
                    } else if (
                        $tax_lower !== 'category' &&
                        $tax_lower !== 'post_tag' &&
                        $tax_lower !== 'genres' &&
                        $tax_lower !== 'genre' &&
                        $tax_lower !== 'movie_genre' &&
                        strpos($tax_lower, 'country') === false &&
                        strpos($tax_lower, 'year') === false &&
                        strpos($tax_lower, 'season') === false &&
                        strpos($tax_lower, 'quality') === false &&
                        strpos($tax_lower, 'format') === false &&
                        strpos($tax_lower, 'language') === false &&
                        strpos($tax_lower, 'status') === false &&
                        strpos($tax_lower, 'nav_menu') === false &&
                        strpos($tax_lower, 'post_format') === false
                    ) {
                        $backup_taxes[] = $tax;
                    }
                }
                
                $taxonomies_to_check = array_merge($priority_taxes, $backup_taxes);
                foreach ($taxonomies_to_check as $tax) {
                    $terms = wp_get_post_terms($id, $tax, array('fields' => 'names'));
                    if (!is_wp_error($terms) && !empty($terms)) {
                        $cleaned_terms = array_values(array_unique(array_filter(array_map('trim', $terms))));
                        if (!empty($cleaned_terms)) {
                            $cast_array = $cleaned_terms;
                            break;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            // Safe fallback try-catch to prevent plugin-breakages
        }

        // --- STEP 2: DIRECT 'CAST' POST METADATA LOOKUP ---
        if (empty($cast_array)) {
            $cast_meta = get_post_meta($id, 'cast', true);
            if (!empty($cast_meta)) {
                $cast_array = insom_extract_cast_names($cast_meta);
            }
        }

        // --- STEP 3: WILD-CARD POST METADATA DETECTOR ---
        // Iterates through ALL post meta keys, automatically recognizing keys containing "cast", "actor", "star", etc.
        if (empty($cast_array)) {
            $all_post_meta = get_post_meta($id);
            if (!empty($all_post_meta) && is_array($all_post_meta)) {
                $meta_keys_priority = array();
                $meta_keys_backup = array();
                
                foreach ($all_post_meta as $m_key => $m_values) {
                    $m_key_lower = strtolower($m_key);
                    if (
                        strpos($m_key_lower, 'cast') !== false ||
                        strpos($m_key_lower, 'actor') !== false ||
                        strpos($m_key_lower, 'star') !== false ||
                        strpos($m_key_lower, 'crew') !== false ||
                        strpos($m_key_lower, 'talent') !== false ||
                        strpos($m_key_lower, 'director') !== false
                    ) {
                        $meta_keys_priority[] = $m_key;
                    } else if (
                        strpos($m_key_lower, 'release') === false &&
                        strpos($m_key_lower, 'date') === false &&
                        strpos($m_key_lower, 'duration') === false &&
                        strpos($m_key_lower, 'genre') === false &&
                        strpos($m_key_lower, 'tagline') === false &&
                        strpos($m_key_lower, 'score') === false &&
                        strpos($m_key_lower, 'rating') === false &&
                        strpos($m_key_lower, 'trailer') === false &&
                        strpos($m_key_lower, 'poster') === false &&
                        strpos($m_key_lower, 'fw_options') === false &&
                        strpos($m_key_lower, 'view') === false &&
                        strpos($m_key_lower, '_wp_') !== 0 &&
                        strpos($m_key_lower, '_yoast') !== 0 &&
                        strpos($m_key_lower, 'rank_math') === false
                    ) {
                        $meta_keys_backup[] = $m_key;
                    }
                }
                
                $meta_keys_to_scan = array_merge($meta_keys_priority, $meta_keys_backup);
                foreach ($meta_keys_to_scan as $m_key) {
                    $val = $all_post_meta[$m_key];
                    if (is_array($val) && count($val) === 1) {
                        $val = $val[0];
                    }
                    $extracted = insom_extract_cast_names($val);
                    if (!empty($extracted)) {
                        $cast_array = $extracted;
                        break;
                    }
                }
            }
        }

        // --- STEP 4: RECURSIVE WILD-CARD FW_OPTIONS SCAN ---
        // Dynamically crawls deep inside Unyson/serialized frameworks for any matching subkey names
        if (empty($cast_array)) {
            $fw_options_for_cast = get_post_meta($id, 'fw_options', true);
            if (!empty($fw_options_for_cast) && is_array($fw_options_for_cast)) {
                array_walk_recursive($fw_options_for_cast, function($value, $key) use (&$cast_array) {
                    if (empty($cast_array) && is_string($key)) {
                        $key_lower = strtolower($key);
                        if (
                            strpos($key_lower, 'cast') !== false ||
                            strpos($key_lower, 'actor') !== false ||
                            strpos($key_lower, 'star') !== false ||
                            strpos($key_lower, 'crew') !== false ||
                            strpos($key_lower, 'talent') !== false ||
                            strpos($key_lower, 'director') !== false
                        ) {
                            $extracted = insom_extract_cast_names($value);
                            if (!empty($extracted)) {
                                $cast_array = $extracted;
                            }
                        }
                    }
                });
            }
        }

        // --- STEP 5: PRE-DEFINED FALLBACK SEQUENCE ---
        if (empty($cast_array)) {
            $possible_keys = array( 
                'movie_cast', 'cast_members', 'actors', 'actors_list', 'cast_list', 
                'cast_members_list', 'stars', 'starring', 'actor', 'custom_cast', 
                '_cast', '_actors', '_movie_cast', '_actors_list', 'dt_cast', 
                'amy_movie_actors', 'wp_movie_cast', 'wp_movie_actors', 'movie_actors'
            );
            $all_post_meta = get_post_meta($id);
            if (!empty($all_post_meta)) {
                foreach ($possible_keys as $pkey) {
                    if (isset($all_post_meta[$pkey]) && !empty($all_post_meta[$pkey])) {
                        $val = $all_post_meta[$pkey];
                        if (is_array($val) && count($val) === 1) {
                            $val = $val[0];
                        }
                        $extracted = insom_extract_cast_names($val);
                        if (!empty($extracted)) {
                            $cast_array = $extracted;
                            break;
                        }
                    }
                }
            }
        }

        // --- STEP 6: BACKUP TAXONOMIES LIST ---
        if (empty($cast_array)) {
            $tax_names = array( 
                'actor', 'actors', 'cast', 'movie_actor', 'movie_actors', 
                'crew', 'director', 'dtt_cast', 'amy_actor', 'wp_actor', 'ht_actor',
                'movie_genre', 'genres', 'genre'
            );
            foreach ($tax_names as $t_name) {
                $terms = wp_get_post_terms($id, $t_name, array('fields' => 'names'));
                if (!is_wp_error($terms) && !empty($terms)) {
                    $cast_array = array_values(array_unique(array_filter($terms)));
                    break;
                }
            }
        }
        
        // Fallback to empty array
        if (empty($cast_array)) {
            $cast_array = array();
        }
        
        // Fetch Genres (Supports WP taxonomy terms or custom field lists)
        $tax_genres  = wp_get_post_terms( $id, 'mv_genre', array( 'fields' => 'names' ) );
        if ( is_wp_error( $tax_genres ) || empty( $tax_genres ) ) {
            $tax_genres  = wp_get_post_terms( $id, 'genre', array( 'fields' => 'names' ) );
        }
        if ( is_wp_error( $tax_genres ) || empty( $tax_genres ) ) {
            $tax_genres  = wp_get_post_terms( $id, 'movie_genre', array( 'fields' => 'names' ) );
        }
        
        $meta_genres = get_post_meta( $id, 'genres', true ) ?: get_post_meta( $id, 'genre', true );
        
        if ( ! is_wp_error( $tax_genres ) && ! empty( $tax_genres ) ) {
            $genres = $tax_genres;
        } elseif ( ! empty( $meta_genres ) ) {
            $genres = is_array( $meta_genres ) ? $meta_genres : array_map( 'trim', explode( ',', $meta_genres ) );
        } else {
            $genres = array( get_post_type() == 'ht_movie' ? 'Movie' : 'TV Show' );
        }
        
        // Trivia Bullets
        $trivia_meta  = get_post_meta( $id, 'trivia', true );
        $trivia_array = ! empty( $trivia_meta ) ? ( is_array( $trivia_meta ) ? $trivia_meta : array_filter( array_map( 'trim', explode( "\n", $trivia_meta ) ) ) ) : array();

        // Get dynamic colors
        $colors = get_cinematic_colors( $id );

        // Determine Type with robust smart checking
        $post_type = get_post_type();
        $is_tv = ( 
            $post_type == 'ht_show' || 
            $post_type == 'ht_tv_show' || 
            strpos( $post_type, 'show' ) !== false || 
            strpos( $post_type, 'tv' ) !== false || 
            strpos( $post_type, 'series' ) !== false || 
            strpos( $post_type, 'season' ) !== false ||
            preg_match( '/\b(show|tv|series|episode|season)\b/i', $post_type )
        );
        
        // 1. Check duration metadata for episode/season keywords
        if ( ! $is_tv && ! empty( $duration ) ) {
            if ( preg_match( '/\b(episode|episodes|season|seasons|tv|show|series)\b/i', $duration ) ) {
                $is_tv = true;
            }
        }
        
        // 2. Check genres/taxonomies for TV categories
        if ( ! $is_tv && ! empty( $genres ) ) {
            foreach ( $genres as $gen ) {
                if ( preg_match( '/\b(tv|show|series|showcase)\b/i', $gen ) ) {
                    $is_tv = true;
                    break;
                }
            }
        }

        // 3. Check post title for TV/Show keywords (e.g. Bridgerton Season 3)
        if ( ! $is_tv ) {
            $title_lower = strtolower( get_the_title() );
            if ( preg_match( '/\b(season|episode|episodes|series|tv-show|tv show|tvseries|s\d{1,2}e\d{1,2})\b/i', $title_lower ) ) {
                $is_tv = true;
            }
        }

        // 4. Check post categories or tags for TV/Show indicators
        if ( ! $is_tv ) {
            $post_categories = wp_get_post_categories( $id, array( 'fields' => 'all' ) );
            if ( ! empty( $post_categories ) && ! is_wp_error( $post_categories ) ) {
                foreach ( $post_categories as $c ) {
                    if ( preg_match( '/\b(tv|show|series|season)\b/i', $c->name ) || preg_match( '/\b(tv|show|series|season)\b/i', $c->slug ) ) {
                        $is_tv = true;
                        break;
                    }
                }
            }
        }
        if ( ! $is_tv ) {
            $post_tags = wp_get_post_tags( $id, array( 'fields' => 'all' ) );
            if ( ! empty( $post_tags ) && ! is_wp_error( $post_tags ) ) {
                foreach ( $post_tags as $t ) {
                    if ( preg_match( '/\b(tv|show|series|season)\b/i', $t->name ) || preg_match( '/\b(tv|show|series|season)\b/i', $t->slug ) ) {
                        $is_tv = true;
                        break;
                    }
                }
            }
        }

        // 5. Check other custom fields for type signals (format, show_type, type)
        if ( ! $is_tv ) {
            $meta_type = get_post_meta( $id, 'type', true ) ?: get_post_meta( $id, 'show_type', true ) ?: get_post_meta( $id, 'format', true );
            if ( ! empty( $meta_type ) ) {
                if ( preg_match( '/\b(tv|show|series|season)\b/i', $meta_type ) ) {
                    $is_tv = true;
                }
            }
        }
        
        $type_formatted = $is_tv ? 'TV Series' : 'Movie';

        // Filter out duplicate or redundant type names from genres (e.g., 'Movie', 'TV Show', 'TV Series')
        $filtered_genres = array();
        if ( ! empty( $genres ) ) {
            foreach ( $genres as $g ) {
                $trimmed = trim( $g );
                $lower = strtolower( $trimmed );
                if ( 
                    $lower !== 'movie' && 
                    $lower !== 'movies' && 
                    $lower !== 'tv show' && 
                    $lower !== 'tv series' && 
                    $lower !== 'tv_show' && 
                    $lower !== 'tv' && 
                    $lower !== 'series' && 
                    $lower !== 'show' && 
                    $lower !== 'shows' && 
                    $lower !== 'ht_movie' && 
                    $lower !== 'ht_show' && 
                    $lower !== 'ht_tv_show'
                ) {
                    $filtered_genres[] = $trimmed;
                }
            }
        }
        if ( empty( $filtered_genres ) ) {
            // Set beautiful cinematic fallback genres based on ID
            $genres_options = array( 'Action', 'Sci-Fi', 'Drama', 'Adventure', 'Thriller', 'Mystery', 'Crime', 'Comedy' );
            $filtered_genres = array( $genres_options[ $id % count( $genres_options ) ] );
        }
        $genres = $filtered_genres;

        // Pre-process and sanitize raw description/synopsis with extensive custom-field fallbacks
        // Fetch raw description/synopsis with extensive custom-field fallbacks preferring content
        $raw_desc = '';
        if ( function_exists( 'fw_get_db_post_option' ) ) {
            $raw_desc = fw_get_db_post_option( $id, 'overview' );
        }
        if ( empty( $raw_desc ) ) {
            $raw_desc = get_post_field( 'post_content', $id );
        }
        if ( empty( $raw_desc ) ) {
            $raw_desc = get_post_field( 'post_excerpt', $id );
        }
        if ( empty( $raw_desc ) ) {
            $raw_desc = get_post_meta( $id, 'synopsis', true );
        }
        if ( empty( $raw_desc ) ) {
            $raw_desc = get_post_meta( $id, 'plot', true );
        }
        if ( empty( $raw_desc ) ) {
            $raw_desc = get_post_meta( $id, 'storyline', true );
        }
        if ( empty( $raw_desc ) ) {
            $raw_desc = get_post_meta( $id, 'story', true );
        }
        if ( empty( $raw_desc ) ) {
            $raw_desc = get_post_meta( $id, 'description', true );
        }
        if ( empty( $raw_desc ) ) {
            $raw_desc = get_the_excerpt( $id );
        }

        // Standard WordPress shortcode stripping
        $clean_desc = strip_shortcodes( $raw_desc );
        
        // Remove VC or page builder style tags safely
        $clean_desc = preg_replace( '/\[\/?[^\]]+\]/', '', $clean_desc );
        
        // Clean absolute video/social URLs from paragraph starts
        $clean_desc = preg_replace( '/\bhttps?:\/\/\S+/i', '', $clean_desc );

        // Decode HTML entities
        $clean_desc = html_entity_decode( $clean_desc, ENT_QUOTES, 'UTF-8' );

        // Condense spacing and trim
        $clean_desc = preg_replace( '/\s+/', ' ', $clean_desc );
        $clean_desc = trim( $clean_desc );

        // Fallback if cleaned text is blank
        if ( empty( $clean_desc ) || $clean_desc === ' ' || strtolower( $clean_desc ) === 'no story synopsis catalogued yet.' ) {
            $genre_text = ! empty( $genres ) ? implode( ', ', array_slice( $genres, 0, 2 ) ) : $type_formatted;
            $clean_desc = "An upcoming " . strtolower( $genre_text ) . " premiere titled " . get_the_title() . ". Stay tuned for official storyline synopsis, theatrical reviews, and production updates as the " . $displayD . " premiere approaches!";
        }

        $review_stats = insom_get_reviews_stats( $id );

        $posts_data[] = array(
            'id'                  => (string) $id,
            'wp_id'               => $id,
            'post_type'           => get_post_type(),
            'title'               => get_the_title(),
            'type'                => (get_post_type() === 'ht_movie') ? 'Movie' : 'TV Series',
            'originalReleaseDate' => $resolved_date, // <--- THIS MUST BE $resolved_date
            'cal_date'            => ( $resolved_date < $today ) ? $today : $resolved_date,
            'displayDate'         => $displayD,
            'month'               => $monthStr,
            'monthNum'            => $monthNum,
            'day'                 => $dayNum,
            'year'                => $yearNum,
            'description'         => $clean_desc,
            'duration'            => $duration ?: ( $type_formatted == 'Movie' ? '2h 15m' : '8 Episodes' ),
            'genre'               => $genres,
            'rating'              => $rating,
            'cast'                => $cast_array,
            'directorOrCreator'   => $director,
            'tagline'             => $tagline ?: '',
            'score'               => (float) $score,
            'review_rating'       => (float) $review_stats['average'],
            'review_count'        => (int) $review_stats['count'],
            'has_review_rating'   => (bool) $review_stats['has_rating'],
            'posterGradient'      => $colors['gradient'],
            'accentColor'         => $colors['accent'],
            'trivia'              => array_values( $trivia_array ),
            'permalink'           => get_permalink( $id ),
            'thumb'               => get_the_post_thumbnail_url( $id, 'large' ) ?: get_option('insom_sched_fallback_bg', ''),
            'trailerUrl'          => $trailer_url ?: '', // Dynamic YouTube/Vimeo Trailer embed URL
            'is_fav'              => ( isset( $_COOKIE['idc_favs'] ) && in_array( $id, explode( ',', $_COOKIE['idc_favs'] ) ) )
        );

        $dense_meta = array();
        if ( ! empty( $all_post_meta ) ) {
            foreach ( $all_post_meta as $mk => $mvs ) {
                $mv = isset($mvs[0]) ? $mvs[0] : '';
                if ( strlen( $mv ) > 200 ) {
                    $mv = substr( $mv, 0, 200 ) . '... (truncated)';
                }
                $dense_meta[$mk] = $mv;
            }
        }
        $cinematic_diagnostics['posts_diagnosed'][] = array(
            'id'              => $id,
            'title'           => get_the_title(),
            'post_type'       => get_post_type(),
            'chosen_key'      => $chosen_meta_key,
            'resolved_date'   => $resolved_date,
            'meta'            => $dense_meta
        );
    }
}
wp_reset_postdata();

// ==========================================
// 3. CHRONOLOGICAL SORT: Future dates first
// ==========================================
if ( ! empty( $posts_data ) ) {
    usort( $posts_data, function( $a, $b ) {
        $today = date('Y-m-d');
        
        // Check if dates are in the past
        $a_past = $a['originalReleaseDate'] < $today;
        $b_past = $b['originalReleaseDate'] < $today;

        // If one is in the past and the other is future, future comes first
        if ( $a_past && ! $b_past ) return 1;
        if ( ! $a_past && $b_past ) return -1;

        // If both are in the future or both in the past, sort by date ASC
        return strcmp( $a['originalReleaseDate'], $b['originalReleaseDate'] );
    } );
}

// Fallback Mock data array so page immediately lights up for the user when empty custom posts!
$is_using_mock_fallback = false;
if ( empty( $posts_data ) ) {
    $is_using_mock_fallback = true;
    $posts_data = array(
        array(
            'id' => 'toy-story-5',
            'title' => 'TOY STORY 5',
            'type' => 'Movie',
            'post_type' => 'ht_movie',
            'originalReleaseDate' => '2026-06-17',
            'displayDate' => '17 JUN',
            'month' => 'JUN',
            'monthNum' => 6,
            'day' => 17,
            'year' => 2026,
            'description' => 'Woody, Buzz, and the gang face their newest and most complex challenge yet when they are forced to compete with a brand-new generation of smart devices and artificial intelligence toys that threaten to replace traditional playthings forever.',
            'duration' => '1h 42m',
            'genre' => array('Animation', 'Adventure', 'Comedy', 'Family'),
            'rating' => 'G',
            'cast' => array('Tom Hanks', 'Tim Allen', 'Joan Cusack', 'Tony Hale'),
            'directorOrCreator' => 'Andrew Stanton',
            'tagline' => 'Toys vs Tech: The ultimate final playdate.',
            'score' => 8.9,
            'review_rating' => 8.7,
            'review_count' => 14,
            'has_review_rating' => true,
            'posterGradient' => 'linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #d97706 100%)',
            'accentColor' => '#f59e0b',
            'trivia' => array('The fifth installment in Pixar’s flagship franchise.', 'Explicitly tackles the digital age and children’s screen-time dependencies.'),
            'permalink' => esc_url( home_url( '/' ) ),
            'thumb' => '',
            'trailerUrl' => 'https://www.youtube.com/embed/Sg0DWeZbe-8', // Toy Story Teaser
            'is_fav' => false
        ),
        array(
            'id' => 'house-of-the-dragon',
            'title' => 'HOUSE OF THE DRAGON',
            'type' => 'TV Series',
            'post_type' => 'ht_show',
            'originalReleaseDate' => '2026-06-20',
            'displayDate' => '20 JUN',
            'month' => 'JUN',
            'monthNum' => 6,
            'day' => 20,
            'year' => 2026,
            'description' => 'The tragedy of the Dance of the Dragons reaches its fiery, devastating climax. The Green and Black factions mobilize every reserve, leading to fateful aerial dragon battles that will map the ultimate destiny of the Iron Throne.',
            'duration' => '8 Episodes',
            'genre' => array('Action', 'Adventure', 'Drama', 'Fantasy'),
            'rating' => 'TV-MA',
            'cast' => array('Emma D’Arcy', 'Matt Smith', 'Olivia Cooke', 'Steve Toussaint'),
            'directorOrCreator' => 'Ryan Condal',
            'tagline' => 'Fire will reign. Blood will outline the future.',
            'score' => 9.3,
            'review_rating' => 9.2,
            'review_count' => 45,
            'has_review_rating' => true,
            'posterGradient' => 'linear-gradient(135deg, #000000 0%, #450a0a 60%, #b91c1c 100%)',
            'accentColor' => '#ef4444',
            'trivia' => array('Season 3 covers the brutal turning-point conflicts of the Targaryen civil war.'),
            'permalink' => esc_url( home_url( '/' ) ),
            'thumb' => '',
            'trailerUrl' => 'https://www.youtube.com/embed/D0_Sg1g7P8E', // House of the Dragon trailer
            'is_fav' => false
        ),
        array(
            'id' => 'the-odyssey',
            'title' => 'ODYSSEY',
            'type' => 'Movie',
            'post_type' => 'ht_movie',
            'originalReleaseDate' => '2026-07-15',
            'displayDate' => '15 JUL',
            'month' => 'JUL',
            'monthNum' => 7,
            'day' => 15,
            'year' => 2026,
            'description' => 'A spectacular, high-concept sci-fi reimagining of Homer’s epic. After a localized anomaly swallows a state-of-the-art exploratory super-carrier, the crew must map an interactive path through uncharted hostiles and spatial sirens to reach Earth.',
            'duration' => '2h 45m',
            'genre' => array('Sci-Fi', 'Mystery', 'Adventure', 'Thriller'),
            'rating' => 'PG-13',
            'cast' => array('Cillian Murphy', 'Florence Pugh', 'Lupita Nyong’o', 'Dev Patel'),
            'directorOrCreator' => 'Denis Villeneuve',
            'tagline' => 'The universe is vast. The journey home is transcendent.',
            'score' => 9.4,
            'review_rating' => 0.0,
            'review_count' => 0,
            'has_review_rating' => false,
            'posterGradient' => 'linear-gradient(135deg, #020617 0%, #030712 60%, #1e40af 100%)',
            'accentColor' => '#38bdf8',
            'trivia' => array('Features a custom composed ambient soundtrack with spatial audio integration.', 'Entirely filmed with next-generation IMAX digital cameras.'),
            'permalink' => esc_url( home_url( '/' ) ),
            'thumb' => '',
            'trailerUrl' => 'https://www.youtube.com/embed/yS8Z9SGo2sc', // Interstellar cinematic feel
            'is_fav' => false
        )
    );
}

// ==========================================
// 4. EXCLUSIONS, PAST DATES FILTER & PINNING
// ==========================================
$hide_past_setting = get_option('insom_sched_hide_past', 'yes_today');
$excluded_posts_option = get_option('insom_sched_excluded_posts', '');
if (is_array($excluded_posts_option)) {
    $excluded_ids = $excluded_posts_option;
} else {
    $excluded_ids = !empty($excluded_posts_option) ? explode(',', $excluded_posts_option) : array();
}
$excluded_ids = array_map('trim', $excluded_ids);
$pinned_post_id = get_option('insom_sched_pinned_post', '');

$final_posts_data = array();
foreach ( $posts_data as $item ) {
    $item_id = (string) $item['id'];
    
    // Add is_pinned flag to the item array so client script knows to choose it as default
    $item['is_pinned'] = ($pinned_post_id !== '' && $pinned_post_id === $item_id);
    
    // If this item is explicitly PINNED, we bypass both manual exclusion AND automagic past-filter
    if ($item['is_pinned']) {
        $final_posts_data[] = $item;
        continue;
    }
    
    // Check manual exclusion
    if ( in_array($item_id, $excluded_ids) ) {
        continue;
    }
    
    // Check auto-hide released past items
    if ( $hide_past_setting === 'yes_today' && $item['originalReleaseDate'] <= $today ) {
        continue;
    }
    if ( $hide_past_setting === 'yes_past' && $item['originalReleaseDate'] < $today ) {
        continue;
    }
    
    $final_posts_data[] = $item;
}
$posts_data = $final_posts_data;

// Hook design assets, Tailwind CDN, Google Fonts, and custom style overrides into the WP head of the active theme
add_action( 'wp_head', function() {
    ?>
    <!-- Performance Optimization: Async DNS & TCP Handshakes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    <link rel="preconnect" href="https://unpkg.com">
    
    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script>
        // Ensure _nslDOMReady (Nextend Social Login) fallback to prevent Uncaught TypeErrors in console
        window._nslDOMReady = window._nslDOMReady || function(cb) {
            if (document.readyState === "complete" || document.readyState === "interactive") {
                try { cb(); } catch (e) { console.error(e); }
            } else {
                document.addEventListener("DOMContentLoaded", function() {
                    try { cb(); } catch (e) { console.error(e); }
                });
            }
        };

        // Suppress production warning from tailwind cdn to maintain pristine console logs
        (function() {
            const originalWarn = console.warn;
            console.warn = function(...args) {
                if (args[0] && typeof args[0] === 'string' && (args[0].includes('cdn.tailwindcss.com') || args[0].includes('tailwindcss.com/docs'))) {
                    return;
                }
                originalWarn.apply(console, args);
            };
        })();
    </script>

    <!-- Tailwind CSS dynamic framework CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons Vector API -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        tailwind.config = {
            important: '#insom-cinematic-app-wrapper',
            corePlugins: {
                preflight: false,
                container: false,
            },
            theme: {
                extend: {
                    colors: {
                        red: {
                            500: '#ef4444',
                            550: '#e11d48',
                            650: '#be123c',
                            850: '#7f1d1d',
                            905: '#991b1b',
                            950: '#450a0a',
                            955: '#3f0712',
                        },
                        neutral: {
                            405: '#a3a3af',
                            850: '#202026',
                        },
                        yellow: {
                            405: '#eab308',
                        }
                    },
                    spacing: {
                        '4.5': '1.125rem',
                        '13': '3.25rem',
                    },
                    fontSize: {
                        '4.5xl': ['2.5rem', { lineHeight: '1.1' }],
                        '5.5xl': ['3rem', { lineHeight: '1' }],
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'ui-monospace', 'monospace'],
                    }
                }
            }
        }
    </script>

    <style>
        /* Force dark background immediately using administered configuration settings */
        :root {
            --insom-sched-accent: <?php echo esc_attr( get_option('insom_sched_accent_color', '#ef4444') ); ?>;
            --insom-sched-bg: <?php echo esc_attr( get_option('insom_sched_bg_color', '#040406') ); ?>;
        }

        html, body {
            background-color: var(--insom-sched-bg) !important;
        }

        /* Safeguard WordPress parent theme wrappers to be full-width and remove constrained gutters for this template */
        body[class*="release-schedule-template-"],
        body.page-template-wordpress-release-schedule-template {
            background-color: var(--insom-sched-bg) !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Dynamic Accent Color CSS Overrides */
        .text-red-500, .group-hover\:text-red-450:hover {
            color: var(--insom-sched-accent) !important;
        }
        .bg-red-500, .bg-red-600, .bg-red-650 {
            background-color: var(--insom-sched-accent) !important;
        }
        .border-red-500 {
            border-color: var(--insom-sched-accent) !important;
        }
        .shadow-red-950\/40, .shadow-red-900\/40 {
            box-shadow: 0 4px 14px var(--insom-sched-accent)40 !important;
        }
        .drop-shadow-\[0_4px_12px_rgba\(239\,68\,68\,0\.35\)\] {
            filter: drop-shadow(0 4px 12px var(--insom-sched-accent)) !important;
        }
        
        body[class*="release-schedule-template-"] #primary,
        body[class*="release-schedule-template-"] #main,
        body[class*="release-schedule-template-"] .site-main,
        body[class*="release-schedule-template-"] .content-area,
        body[class*="release-schedule-template-"] #content,
        body[class*="release-schedule-template-"] .container,
        body[class*="release-schedule-template-"] .container-wrapper,
        body[class*="release-schedule-template-"] .content-wrapper,
        body.page-template-wordpress-release-schedule-template #primary,
        body.page-template-wordpress-release-schedule-template #main,
        body.page-template-wordpress-release-schedule-template .site-main,
        body.page-template-wordpress-release-schedule-template .content-area,
        body.page-template-wordpress-release-schedule-template #content,
        body.page-template-wordpress-release-schedule-template .container,
        body.page-template-wordpress-release-schedule-template .container-wrapper,
        body.page-template-wordpress-release-schedule-template .content-wrapper {
            max-width: 100% !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            float: none !important;
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }

        /* Scoped reset for the cinematic app wrapper to avoid breaking WP theme headers, logo, search, and login buttons */
        :where(#insom-cinematic-app-wrapper) *,
        :where(#insom-cinematic-app-wrapper) *::before,
        :where(#insom-cinematic-app-wrapper) *::after {
            box-sizing: border-box;
            border-width: 0;
            border-style: solid;
            border-color: rgba(255, 255, 255, 0.08);
        }
        :where(#insom-cinematic-app-wrapper) h1,
        :where(#insom-cinematic-app-wrapper) h2,
        :where(#insom-cinematic-app-wrapper) h3,
        :where(#insom-cinematic-app-wrapper) h4,
        :where(#insom-cinematic-app-wrapper) h5,
        :where(#insom-cinematic-app-wrapper) h6 {
            margin: 0;
            font-size: inherit;
            font-weight: inherit;
        }
        :where(#insom-cinematic-app-wrapper) a {
            color: inherit;
            text-decoration: inherit;
        }
        :where(#insom-cinematic-app-wrapper) button,
        :where(#insom-cinematic-app-wrapper) input,
        :where(#insom-cinematic-app-wrapper) select,
        :where(#insom-cinematic-app-wrapper) textarea {
            font-family: inherit;
            font-size: 100%;
            margin: 0;
            padding: 0;
            line-height: inherit;
            color: inherit;
            border-style: solid;
            border-width: 0;
            background: transparent;
        }
        :where(#insom-cinematic-app-wrapper) button {
            text-transform: none;
            cursor: pointer;
        }
        :where(#insom-cinematic-app-wrapper) img,
        :where(#insom-cinematic-app-wrapper) svg {
            display: block;
            max-width: 100%;
            height: auto;
        }
        :where(#insom-cinematic-app-wrapper) ul,
        :where(#insom-cinematic-app-wrapper) ol {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        /* Standalone Sandbox Overrides for WP Compatibility */
        #insom-cinematic-app-wrapper {
            background-color: #040406;
            color: #f5f5f7;
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
            max-width: 100%;
            position: relative;
            box-sizing: border-box;
            opacity: 0;
            transition: opacity 0.25s ease-in-out;
            padding-top: 6.5rem !important; /* Compact spacing clearing sticky WP headers on mobile viewports */
            padding-left: 1rem !important;
            padding-right: 1rem !important;
            padding-bottom: 2rem !important;
        }

        @media (min-width: 768px) {
            #insom-cinematic-app-wrapper {
                padding-top: 2.5rem !important; /* Elegant compact spacing from header on desktop viewports */
                padding-left: 2rem !important;
                padding-right: 2rem !important;
                padding-bottom: 4rem !important;
            }
        }
        
        .glow-overlay-red {
            background: radial-gradient(circle, rgba(239, 68, 68, 0.12) 0%, transparent 70%);
        }

        /* Seamless premium scrollbar */
        #insom-cinematic-app-wrapper ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        #insom-cinematic-app-wrapper ::-webkit-scrollbar-track {
            background: rgba(15, 15, 15, 0.8);
        }
        #insom-cinematic-app-wrapper ::-webkit-scrollbar-thumb {
            background: rgba(220, 38, 38, 0.35);
            border-radius: 4px;
        }
        #insom-cinematic-app-wrapper ::-webkit-scrollbar-thumb:hover {
            background: rgba(220, 38, 38, 0.65);
        }

        .modal-open-anim {
            animation: modalScale 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes modalScale {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        
        .toast-anim {
            animation: toastSlide 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes toastSlide {
            from { opacity: 0; transform: translateY(30px) scale(0.9); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Movie/TV Badge Glow & Hover Effects */
        .movie-badge-glow {
            display: inline-block !important;
            background-color: rgba(69, 10, 10, 0.25) !important;
            border-color: rgba(239, 68, 68, 0.3) !important;
            color: #f87171 !important;
            box-shadow: 0 0 10px rgba(239, 68, 68, 0.15) !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        .movie-badge-glow:hover {
            background-color: #dc2626 !important;
            border-color: #ef4444 !important;
            color: #ffffff !important;
            box-shadow: 0 0 18px rgba(239, 68, 68, 0.65), 0 0 30px rgba(239, 68, 68, 0.3) !important;
            transform: translateY(-2px) !important;
        }

        .tv-badge-glow {
            display: inline-block !important;
            background-color: rgba(30, 27, 75, 0.25) !important;
            border-color: rgba(59, 130, 246, 0.3) !important;
            color: #60a5fa !important;
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.15) !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        .tv-badge-glow:hover {
            background-color: #2563eb !important;
            border-color: #3b82f6 !important;
            color: #ffffff !important;
            box-shadow: 0 0 18px rgba(59, 130, 246, 0.65), 0 0 30px rgba(59, 130, 246, 0.3) !important;
            transform: translateY(-2px) !important;
        }

        /* Responsive metadata inline layout to prevent wrap / cut-offs on mobile */
        .card-meta-row {
            font-size: 8.5px !important;
            display: flex !important;
            align-items: center !important;
            flex-wrap: nowrap !important;
            white-space: nowrap !important;
            row-gap: 4px !important;
            column-gap: 4px !important;
        }
        @media (min-width: 360px) {
            .card-meta-row {
                font-size: 9px !important;
                column-gap: 5px !important;
            }
        }
        @media (min-width: 400px) {
            .card-meta-row {
                font-size: 9.5px !important;
                column-gap: 6px !important;
            }
        }
        @media (min-width: 480px) {
            .card-meta-row {
                font-size: 10px !important;
            }
        }

        .meta-badge {
            padding: 1px 4.5px !important;
            font-size: 8px !important;
            line-height: normal !important;
        }
        @media (min-width: 400px) {
            .meta-badge {
                padding: 1px 6px !important;
                font-size: 9px !important;
            }
        }
        @media (min-width: 480px) {
            .meta-badge {
                padding: 2px 8px !important;
                font-size: 9.5px !important;
            }
        }

        /* Default layout: extremely tight bounds (e.g. mobile portrait < 400px) */
        .genre-short {
            display: inline !important;
        }
        .genre-full {
            display: none !important;
        }
        .reviews-short {
            display: inline !important;
        }
        .reviews-full {
            display: none !important;
        }

        /* Standard mobile vertical stacked cards (400px - 639px) */
        @media (min-width: 400px) and (max-width: 639px) {
            .genre-short {
                display: none !important;
            }
            .genre-full {
                display: inline !important;
            }
            .reviews-short {
                display: none !important;
            }
            .reviews-full {
                display: inline !important;
            }
        }

        /* Horizontal card layout inside tight sidebar columns (640px - 1149px) */
        @media (min-width: 640px) and (max-width: 1149px) {
            .genre-short {
                display: inline !important;
            }
            .genre-full {
                display: none !important;
            }
            .reviews-short {
                display: inline !important;
            }
            .reviews-full {
                display: none !important;
            }
        }

        /* Broad layouts (desktop >= 1150px) where full content space is available */
        @media (min-width: 1150px) {
            .genre-short {
                display: none !important;
            }
            .genre-full {
                display: inline !important;
            }
            .reviews-short {
                display: none !important;
            }
            .reviews-full {
                display: inline !important;
            }
        }

        /* High-priority override styles to prevent WordPress theme conflicts on buttons and selected cards */
        #insom-cinematic-app-wrapper #format-filters {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 0.5rem !important; /* Strong gap of 8px when wrapping on mobile devices */
            padding: 0 !important;
            margin: 0 !important;
            box-sizing: border-box !important;
        }

        #insom-cinematic-app-wrapper #format-filters button {
            cursor: pointer !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
            box-sizing: border-box !important;
            box-shadow: none !important;
            margin: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            
            /* High-fidelity typography, borders, and margins overrides */
            height: auto !important;
            max-height: none !important;
            min-height: 0 !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            outline: none !important;
            border-style: solid !important;
            border-width: 1px !important;
            appearance: none !important;
            -webkit-appearance: none !important;

            /* Mobile-optimized sizing and paddings */
            font-size: 10px !important; 
            padding: 0.45rem 0.8rem !important;
            border-radius: 0.75rem !important; /* 12px rounded-xl */
        }

        @media (min-width: 640px) {
            #insom-cinematic-app-wrapper #format-filters button {
                padding: 0.5rem 1.1rem !important; 
                font-size: 11px !important;
                border-radius: 0.75rem !important; /* 12px rounded-xl */
            }
        }

        #insom-cinematic-app-wrapper #format-filters button.active-format {
            background-color: rgba(239, 68, 68, 0.15) !important;
            border-color: rgba(239, 68, 68, 0.8) !important;
            color: #ef4444 !important;
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.45) !important;
            font-weight: 800 !important;
            text-shadow: 0 0 3px rgba(239, 68, 68, 0.3) !important;
        }

        #insom-cinematic-app-wrapper #format-filters button.inactive-format {
            background-color: rgba(9, 9, 11, 0.55) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
            color: #a3a3a3 !important;
            font-weight: 700 !important;
        }

        #insom-cinematic-app-wrapper #format-filters button.inactive-format:hover {
            color: #ffffff !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
            background-color: rgba(24, 24, 27, 0.65) !important;
        }

        /* High-fidelity explicit style overrides for the dynamic search input box placeholder and values */
        #insom-cinematic-app-wrapper #search-input {
            font-family: inherit !important;
            box-sizing: border-box !important;
        }

        #insom-cinematic-app-wrapper #search-input::placeholder {
            color: #737373 !important;
            opacity: 1 !important;
        }

        #insom-cinematic-app-wrapper #search-input::-webkit-input-placeholder {
            color: #737373 !important;
            opacity: 1 !important;
        }

        #insom-cinematic-app-wrapper #search-input::-moz-placeholder {
            color: #737373 !important;
            opacity: 1 !important;
        }

        #insom-cinematic-app-wrapper #search-input:-ms-input-placeholder {
            color: #737373 !important;
            opacity: 1 !important;
        }

        #insom-cinematic-app-wrapper .release-card-focused {
            border: 1px solid rgba(239, 68, 68, 0.85) !important;
            background-color: rgba(4, 4, 6, 0.95) !important;
            box-shadow: 0 0 25px rgba(239, 68, 68, 0.50) !important;
            transform: scale(1.02) !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
            z-index: 10 !important;
            margin-bottom: 1.25rem !important; /* Force a clear responsive gap between release cards */
        }

        #insom-cinematic-app-wrapper .release-card-normal {
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            background-color: rgba(10, 10, 15, 0.45) !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
            margin-bottom: 1.25rem !important; /* Force a clear responsive gap between release cards */
        }
        
        #insom-cinematic-app-wrapper .release-card-normal:hover {
            border-color: rgba(239, 68, 68, 0.45) !important;
            background-color: rgba(18, 18, 24, 0.6) !important;
            transform: scale(1.005) !important;
        }

        /* Prevent last child inside list from trailing a bottom margin */
        #insom-cinematic-app-wrapper #release-cards-list > div:last-child {
            margin-bottom: 0px !important;
        }

        #insom-cinematic-app-wrapper .release-filter-row {
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            padding-bottom: 1.25rem !important;
            margin-bottom: 0.5rem !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        /* Ensure hidden is absolute */
        #insom-cinematic-app-wrapper .hidden {
            display: none !important;
        }

        /* High-priority modal & drawer overrides to prevent theme clipping, z-index overlays & cutoff buttons */
        #details-modal, #reminder-modal, #calendar-drawer {
            z-index: 999999 !important;
            overflow-y: auto !important; /* Enable backdrop scrolling if content is too tall */
        }
        
        #system-toast {
            z-index: 1000000 !important;
        }

        /* Prevent background scroll when any modal is open */
        body.modal-prevent-scroll {
            overflow: hidden !important;
            height: 100vh !important;
        }

        /* Ensure modal inner containers center nicely but stay fully scrollable */
        #details-modal .modal-open-anim, #reminder-modal .modal-open-anim {
            margin: auto !important;
            max-height: calc(100vh - 2rem) !important;
            display: flex !important;
            flex-direction: column !important;
            overflow: hidden !important; /* Keep the container neat */
        }

        /* Make sure inside of details-modal specs is scrollable with elegant padding-bottom */
        #details-modal .overflow-y-auto {
            max-height: none !important;
            flex: 1 !important;
            overflow-y: auto !important;
            padding-bottom: 2rem !important;
        }

        /* Add a high-contrast close button styling so it never disappears on any backdrop or theme */
        #details-modal button[onclick*="closeDetailsModal"] {
            background-color: rgba(10, 10, 10, 0.95) !important;
            border: 2px solid rgba(255, 255, 255, 0.45) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.75) !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
            cursor: pointer !important;
        }
        #details-modal button[onclick*="closeDetailsModal"]:hover {
            border-color: rgba(239, 68, 68, 1) !important;
            background-color: #ef4444 !important;
            color: #ffffff !important;
            transform: scale(1.1) !important;
        }

        #reminder-modal button[onclick*="closeReminderModal"] {
            background-color: rgba(10, 10, 10, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.25) !important;
            color: #ffffff !important;
            border-radius: 9999px !important;
            padding: 0.5rem !important;
            transition: all 0.2s ease !important;
            cursor: pointer !important;
        }
        #reminder-modal button[onclick*="closeReminderModal"]:hover {
            border-color: rgba(239, 68, 68, 0.8) !important;
            color: #ffffff !important;
            background-color: rgba(239, 68, 68, 0.25) !important;
            transform: scale(1.05) !important;
        }

        /* User custom design merges */
        nav.blockter-breadcrumb.flw { display: none; }
        form#searchmovie { display: none !important; }
        .max-w-7xl.mx-auto.grid.grid-cols-1.lg\:grid-cols-12.gap-8.relative.z-10.current-grid { margin-top: 1.5rem; }
        @media (min-width: 1024px) {
            .max-w-7xl.mx-auto.grid.grid-cols-1.lg\:grid-cols-12.gap-8.relative.z-10.current-grid { margin-top: 15vh; }
        }

        /* Dynamic Ambient Backdrop and Spotlight Styles */
        #dynamic-ambient-glow {
            transition: background 1.2s cubic-bezier(0.16, 1, 0.3, 1), opacity 1s ease;
        }
        #spotlight-modal {
            z-index: 1000001 !important;
        }
        .trivia-mask {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
    </style>
    <?php
} );

// Retrieve the dynamic WordPress site header
get_header();
?>

<div id="insom-cinematic-app-wrapper" class="p-4 md:p-8 relative">
    
    <!-- Dynamic Ambient Backdrop Full-Screen Glow -->
    <div id="dynamic-ambient-glow" class="absolute inset-0 pointer-events-none opacity-40 z-0" style="background: radial-gradient(circle at 50% 30%, #ef44441A 0%, #ef444405 50%, transparent 100%);"></div>
    
    <!-- Backing ambient red atmospheric glow vectors with clipping parent to prevent mobile layout overflow -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <div class="glow-overlay-red absolute top-1/4 -left-40 w-[450px] h-[450px] rounded-full pointer-events-none"></div>
        <div class="glow-overlay-red absolute bottom-1/4 -right-40 w-[450px] h-[450px] rounded-full pointer-events-none"></div>
    </div>

    <!-- Developer notices cleared for maximum production performance -->

    <!-- Main Outer Wrapper grid -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 relative z-10 current-grid">

        <!-- ==================== LEFT COLUMN: INTRO, HEADER ACTIONS, DESK CALENDAR, SMARTPHONE ==================== -->
        <div class="lg:col-span-5 flex flex-col gap-6">
            
            <!-- Company/Shortcode Title -->
            <div class="flex items-center gap-2.5 justify-center lg:justify-start">
                <div class="w-8 h-8 rounded-lg bg-red-600 flex items-center justify-center shadow-md shadow-red-900/40 select-none shrink-0">
                    <svg viewBox="0 0 100 100" class="w-5 h-5 fill-white">
                        <path d="M20,10 L80,10 C85,10 85,25 80,25 L65,25 L65,75 L80,75 C85,75 85,90 80,90 L20,90 C15,90 15,75 20,75 L35,75 L35,25 L20,25 C15,25 15,10 20,10 Z"></path>
                    </svg>
                </div>
                <span class="text-xs sm:text-sm font-extrabold tracking-[0.25em] sm:tracking-[0.4em] text-neutral-300 font-mono uppercase whitespace-nowrap">INSOMNIACS</span>
            </div>

            <!-- Page Title -->
            <div class="text-center lg:text-left">
                <h1 class="text-3.5xl sm:text-4.5xl md:text-5.5xl font-extrabold tracking-tight text-white leading-none"><?php echo esc_html( get_option('insom_sched_title_1', 'MOVIES & TV') ); ?></h1>
                <h1 class="text-4xl sm:text-5xl md:text-6.5xl font-black tracking-wide text-red-500 leading-none drop-shadow-[0_4px_12px_rgba(239,68,68,0.35)]"><?php echo esc_html( get_option('insom_sched_title_2', 'SCHEDULE') ); ?></h1>
                <p class="text-neutral-400 text-sm md:text-base mt-4 max-w-sm leading-relaxed mx-auto lg:mx-0">
                    <?php echo esc_html( get_option('insom_sched_description', 'Interactive cinematic scheduler. Sync schedules directly to your personal planners and track upcoming releases.') ); ?>
                </p>
            </div>

            <!-- Navigation Actions Segmented Console -->
            <div class="grid grid-cols-4 gap-2 bg-neutral-900/45 border border-neutral-800/60 p-3 rounded-2xl">
                <button onclick="scrollToCalendar()" class="flex flex-col items-center text-center p-2 rounded-xl hover:bg-neutral-850/60 transition-all cursor-pointer group">
                    <div class="p-2 text-red-500 rounded-lg group-hover:bg-red-950/20 transition-all">
                        <i data-lucide="layout-grid" class="w-4.5 h-4.5"></i>
                    </div>
                    <span class="text-[9px] font-bold text-neutral-400 mt-1 uppercase font-mono tracking-wider">Browse</span>
                </button>

                <button onclick="openReminderModal()" class="flex flex-col items-center text-center p-2 rounded-xl hover:bg-neutral-850/60 transition-all cursor-pointer group relative">
                    <div class="p-2 text-red-500 rounded-lg group-hover:bg-red-950/20 transition-all">
                        <i data-lucide="bell" class="w-4.5 h-4.5"></i>
                        <span id="reminder-pip" class="absolute top-1 right-2 w-1.5 h-1.5 bg-red-400 rounded-full hidden"></span>
                    </div>
                    <span class="text-[9px] font-bold text-neutral-400 mt-1 uppercase font-mono tracking-wider">Reminders</span>
                </button>

                <button onclick="toggleCalendarPanel()" class="flex flex-col items-center text-center p-2 rounded-xl hover:bg-neutral-850/60 transition-all cursor-pointer group relative">
                    <div class="p-2 text-red-500 rounded-lg group-hover:bg-red-950/20 transition-all">
                        <i data-lucide="calendar-check" class="w-4.5 h-4.5"></i>
                    </div>
                    <span id="calendar-badge" class="absolute top-1.5 right-2 bg-red-600 text-white font-mono text-[9px] font-bold px-1 rounded-full hidden">0</span>
                    <span class="text-[9px] font-bold text-neutral-400 mt-1 uppercase font-mono tracking-wider">Calendar</span>
                </button>

                <button onclick="toggleFavoritesFilter()" id="favs-btn" class="flex flex-col items-center text-center p-2 rounded-xl hover:bg-neutral-850/60 transition-all cursor-pointer group relative">
                    <div class="p-2 text-red-500 rounded-lg group-hover:bg-red-950/20 transition-all">
                        <i data-lucide="heart" id="fav-icon-global" class="w-4.5 h-4.5"></i>
                    </div>
                    <span id="favorites-badge" class="absolute top-1.5 right-2 bg-red-650 text-white font-mono text-[9px] font-bold px-1 rounded-full hidden">0</span>
                    <span class="text-[9px] font-bold text-neutral-400 mt-1 uppercase font-mono tracking-wider">Favourites</span>
                </button>
            </div>

            <!-- Promotion box banner -->
            <div class="flex items-center gap-4 bg-gradient-to-r from-red-950/30 to-transparent border border-red-900/25 rounded-2xl p-4">
                <div class="w-10 h-10 shrink-0 rounded-xl bg-red-950/50 border border-red-900/40 flex items-center justify-center text-red-500 font-mono">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                </div>
                <div>
                    <h5 class="text-xs font-bold text-neutral-200 tracking-wide uppercase">STAY AHEAD OF EVERY RELEASE</h5>
                    <p class="text-[11px] text-neutral-400 mt-0.5">Our direct Google Calendar template pipeline lets you secure coordinates with one tap.</p>
                </div>
            </div>

            <!-- ==================== CALENDAR CONTAINER: INTERACTIVE DESK CALENDAR ==================== -->
            <div class="relative w-full max-w-sm mx-auto" id="desk-calendar-container">
                <!-- Spindle top spiral rings wire bounds -->
                <div class="absolute -top-3 left-4 right-4 flex justify-between px-3 z-10">
                    <div class="flex flex-col items-center"><div class="w-1.5 h-6 bg-gradient-to-b from-neutral-600 via-neutral-400 to-neutral-700 rounded-full shadow-md"></div><div class="w-2.5 h-1 bg-black rounded-full opacity-50 -mt-0.5" ></div></div>
                    <div class="flex flex-col items-center"><div class="w-1.5 h-6 bg-gradient-to-b from-neutral-600 via-neutral-400 to-neutral-700 rounded-full shadow-md"></div><div class="w-2.5 h-1 bg-black rounded-full opacity-50 -mt-0.5" ></div></div>
                    <div class="flex flex-col items-center"><div class="w-1.5 h-6 bg-gradient-to-b from-neutral-600 via-neutral-400 to-neutral-700 rounded-full shadow-md"></div><div class="w-2.5 h-1 bg-black rounded-full opacity-50 -mt-0.5" ></div></div>
                    <div class="flex flex-col items-center"><div class="w-1.5 h-6 bg-gradient-to-b from-neutral-600 via-neutral-400 to-neutral-700 rounded-full shadow-md"></div><div class="w-2.5 h-1 bg-black rounded-full opacity-50 -mt-0.5" ></div></div>
                    <div class="flex flex-col items-center"><div class="w-1.5 h-6 bg-gradient-to-b from-neutral-600 via-neutral-400 to-neutral-700 rounded-full shadow-md"></div><div class="w-2.5 h-1 bg-black rounded-full opacity-50 -mt-0.5" ></div></div>
                    <div class="flex flex-col items-center"><div class="w-1.5 h-6 bg-gradient-to-b from-neutral-600 via-neutral-400 to-neutral-700 rounded-full shadow-md"></div><div class="w-2.5 h-1 bg-black rounded-full opacity-50 -mt-0.5" ></div></div>
                    <div class="flex flex-col items-center"><div class="w-1.5 h-6 bg-gradient-to-b from-neutral-600 via-neutral-400 to-neutral-700 rounded-full shadow-md"></div><div class="w-2.5 h-1 bg-black rounded-full opacity-50 -mt-0.5" ></div></div>
                    <div class="flex flex-col items-center"><div class="w-1.5 h-6 bg-gradient-to-b from-neutral-600 via-neutral-400 to-neutral-700 rounded-full shadow-md"></div><div class="w-2.5 h-1 bg-black rounded-full opacity-50 -mt-0.5" ></div></div>
                    <div class="flex flex-col items-center"><div class="w-1.5 h-6 bg-gradient-to-b from-neutral-600 via-neutral-400 to-neutral-700 rounded-full shadow-md"></div><div class="w-2.5 h-1 bg-black rounded-full opacity-50 -mt-0.5" ></div></div>
                    <div class="flex flex-col items-center"><div class="w-1.5 h-6 bg-gradient-to-b from-neutral-600 via-neutral-400 to-neutral-700 rounded-full shadow-md"></div><div class="w-2.5 h-1 bg-black rounded-full opacity-50 -mt-0.5" ></div></div>
                    <div class="flex flex-col items-center"><div class="w-1.5 h-6 bg-gradient-to-b from-neutral-600 via-neutral-400 to-neutral-700 rounded-full shadow-md"></div><div class="w-2.5 h-1 bg-black rounded-full opacity-50 -mt-0.5" ></div></div>
                    <div class="flex flex-col items-center"><div class="w-1.5 h-6 bg-gradient-to-b from-neutral-600 via-neutral-400 to-neutral-700 rounded-full shadow-md"></div><div class="w-2.5 h-1 bg-black rounded-full opacity-50 -mt-0.5" ></div></div>
                    <div class="flex flex-col items-center"><div class="w-1.5 h-6 bg-gradient-to-b from-neutral-600 via-neutral-400 to-neutral-700 rounded-full shadow-md"></div><div class="w-2.5 h-1 bg-black rounded-full opacity-50 -mt-0.5" ></div></div>
                    <div class="flex flex-col items-center"><div class="w-1.5 h-6 bg-gradient-to-b from-neutral-600 via-neutral-400 to-neutral-700 rounded-full shadow-md"></div><div class="w-2.5 h-1 bg-black rounded-full opacity-50 -mt-0.5" ></div></div>
                </div>

                <!-- Calendar Content Area card -->
                <div class="bg-neutral-900 border border-neutral-800 rounded-2xl shadow-2xl p-5 pt-7 text-neutral-100 overflow-hidden relative">
                    <!-- Gloss sheen surface highlight effect -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-neutral-800/10 to-white/5 pointer-events-none"></div>

                    <!-- Header month/brand labels with premium interactive dynamic controls -->
                    <div class="flex justify-between items-center mb-5 border-b border-neutral-800 pb-3">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-semibold tracking-wider text-neutral-400 font-mono uppercase">CALENDAR ENGINE</span>
                            <span class="text-xl font-bold tracking-widest text-red-550 font-mono select-none mt-1" id="calendar-header-month">SCHEDULE</span>
                        </div>
                        <div class="flex items-center gap-1.5 z-10">
                            <button onclick="prevCalendarMonth()" class="p-1 px-1.5 border border-neutral-800 bg-neutral-950/80 hover:bg-neutral-850 text-neutral-400 hover:text-white rounded transition cursor-pointer" title="Previous Month">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </button>
                            <button onclick="nextCalendarMonth()" class="p-1 px-1.5 border border-neutral-800 bg-neutral-950/80 hover:bg-neutral-850 text-neutral-400 hover:text-white rounded transition cursor-pointer" title="Next Month">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Week labels Mon to Sun -->
                    <div class="grid grid-cols-7 text-center gap-1 mb-3 text-neutral-400 font-bold text-[10px] sm:text-xs select-none">
                        <div>MON</div><div>TUE</div><div>WED</div><div>THU</div><div>FRI</div><div>SAT</div><div>SUN</div>
                    </div>

                    <!-- Dynamic Calendar Grid Day squares loaded via smart JavaScript -->
                    <div class="grid grid-cols-7 gap-y-2.5 gap-x-1 text-center font-mono text-xs sm:text-sm" id="calendar-grid-cells"></div>

                    <!-- Selected Day footer dashboard item representation -->
                    <div id="calendar-selection-footer" class="mt-4 pt-3 border-t border-neutral-800 flex items-center justify-between text-xs hidden">
                        <span class="text-neutral-400 font-mono uppercase">CALENDAR SIGNAL:</span>
                        <span id="calendar-selection-movie-name" class="text-white font-bold tracking-wide flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-550 animate-pulse"></span>
                            <span>NONE</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- ==================== PHONE PREVIEW GRAPHIC CONTAINER ==================== -->
            <div class="relative w-full max-w-sm mx-auto bg-neutral-950 border border-neutral-800 rounded-3xl p-3 shadow-xl">
                <!-- Top Notch Pill -->
                <div class="absolute top-4 left-1/2 -translate-x-1/2 w-20 h-4 bg-black rounded-full z-15 flex items-center justify-center">
                    <span class="w-1.5 h-1.5 rounded-full bg-neutral-800"></span>
                </div>

                <!-- Simulation interface inside frame -->
                <div class="bg-[#020204] border border-neutral-900 rounded-[22px] overflow-hidden p-4 pt-6 space-y-4 relative">
                    <div class="flex justify-between items-center text-[10px] font-mono text-neutral-500 select-none">
                        <span>INSOMNIAC BROADCAST</span>
                        <div class="flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-ping"></span>
                            <span>LIVE SYNC</span>
                        </div>
                    </div>

                    <!-- Inner reactive countdown details container -->
                    <div id="phone-dynamic-card" class="space-y-3"></div>
                </div>
            </div>

            <!-- ==================== CINE-SYNC INTELLIGENCE PROFILE ==================== -->
            <div class="relative w-full max-w-sm mx-auto bg-neutral-950 border border-neutral-850 rounded-3xl p-5 shadow-xl space-y-4">
                <div class="flex justify-between items-center pb-2 border-b border-neutral-900 border-dashed">
                    <div>
                        <div class="flex items-center gap-1.5 text-[8px] font-black tracking-widest text-red-500 font-mono uppercase">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                            <span>CINE-SYNC PROFILE CORE</span>
                        </div>
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider font-sans mt-0.5 font-extrabold">Quantum Personality analysis</h4>
                    </div>
                    <i data-lucide="cpu" class="w-4 h-4 text-neutral-500"></i>
                </div>

                <!-- Dynamic Frequency Profile Bars -->
                <div class="space-y-3">
                    <!-- Adrenaline Index -->
                    <div>
                        <div class="flex justify-between text-[9px] font-mono mb-1 select-none">
                            <span class="text-neutral-400 font-bold uppercase">ADRENALINE INDEX (ACTIONS)</span>
                            <span id="cinesync-bar-val-adrenaline" class="text-red-400 font-black font-extrabold">0%</span>
                        </div>
                        <div class="h-2 w-full bg-neutral-900 border border-neutral-850 rounded-full overflow-hidden">
                            <div id="cinesync-bar-fill-adrenaline" class="h-full bg-gradient-to-r from-red-650 via-pink-600 to-red-550 rounded-full transition-all duration-500" style="width: 0%;"></div>
                        </div>
                    </div>

                    <!-- Wormhole Index -->
                    <div>
                        <div class="flex justify-between text-[9px] font-mono mb-1 select-none">
                            <span class="text-neutral-400 font-bold uppercase">WORMHOLE INDEX (SCI-FI & SPACE)</span>
                            <span id="cinesync-bar-val-wormhole" class="text-cyan-400 font-black font-extrabold">0%</span>
                        </div>
                        <div class="h-2 w-full bg-neutral-900 border border-neutral-850 rounded-full overflow-hidden">
                            <div id="cinesync-bar-fill-wormhole" class="h-full bg-gradient-to-r from-cyan-600 to-indigo-500 rounded-full transition-all duration-500" style="width: 0%;"></div>
                        </div>
                    </div>

                    <!-- Harmony Index -->
                    <div>
                        <div class="flex justify-between text-[9px] font-mono mb-1 select-none">
                            <span class="text-neutral-400 font-bold uppercase">HARMONY INDEX (FAMILY & LIGHTS)</span>
                            <span id="cinesync-bar-val-harmony" class="text-amber-500 font-black font-extrabold">0%</span>
                        </div>
                        <div class="h-2 w-full bg-neutral-900 border border-neutral-850 rounded-full overflow-hidden">
                            <div id="cinesync-bar-fill-harmony" class="h-full bg-gradient-to-r from-amber-500 to-yellow-400 rounded-full transition-all duration-500" style="width: 0%;"></div>
                        </div>
                    </div>

                    <!-- Deep Cinema Index -->
                    <div>
                        <div class="flex justify-between text-[9px] font-mono mb-1 select-none">
                            <span class="text-neutral-400 font-bold uppercase">ATMOSPHERE INDEX (DRAMA & OTHER)</span>
                            <span id="cinesync-bar-val-drama" class="text-purple-400 font-black font-extrabold">0%</span>
                        </div>
                        <div class="h-2 w-full bg-neutral-900 border border-neutral-850 rounded-full overflow-hidden">
                            <div id="cinesync-bar-fill-drama" class="h-full bg-gradient-to-r from-purple-600 to-pink-550 rounded-full transition-all duration-500" style="width: 0%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Achievements/Badge Row -->
                <div class="pt-3 border-t border-neutral-900">
                    <p class="text-[9px] font-black tracking-widest text-neutral-400 font-mono uppercase mb-2">CYBERNETIC PREMIERE MEDALS</p>
                    <div class="grid grid-cols-5 gap-2" id="cine-medals-container">
                        <!-- Medal 1: Scout -->
                        <div id="medal-scout" class="relative group/medal flex flex-col items-center justify-center p-2.5 rounded-xl bg-neutral-900/40 border border-neutral-850 hover:border-neutral-700 transition duration-305 cursor-help" onmouseenter="showMedalTooltip('scout')" onmouseleave="hideMedalTooltip()">
                            <i data-lucide="compass" class="w-5 h-5 text-neutral-600 transition duration-300"></i>
                            <div class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-cyan-400 border border-black hidden" id="medal-scout-dot"></div>
                        </div>
                        <!-- Medal 2: Adrenaline -->
                        <div id="medal-adrenaline" class="relative group/medal flex flex-col items-center justify-center p-2.5 rounded-xl bg-neutral-900/40 border border-neutral-850 hover:border-neutral-700 transition duration-305 cursor-help" onmouseenter="showMedalTooltip('adrenaline')" onmouseleave="hideMedalTooltip()">
                            <i data-lucide="zap" class="w-5 h-5 text-neutral-600 transition duration-300"></i>
                            <div class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-red-500 border border-black hidden" id="medal-adrenaline-dot"></div>
                        </div>
                        <!-- Medal 3: Voyager -->
                        <div id="medal-voyager" class="relative group/medal flex flex-col items-center justify-center p-2.5 rounded-xl bg-neutral-900/40 border border-neutral-850 hover:border-neutral-700 transition duration-305 cursor-help" onmouseenter="showMedalTooltip('voyager')" onmouseleave="hideMedalTooltip()">
                            <i data-lucide="rocket" class="w-5 h-5 text-neutral-600 transition duration-300"></i>
                            <div class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-teal-400 border border-black hidden" id="medal-voyager-dot"></div>
                        </div>
                        <!-- Medal 4: Dreamweaver -->
                        <div id="medal-dreamweaver" class="relative group/medal flex flex-col items-center justify-center p-2.5 rounded-xl bg-neutral-900/40 border border-neutral-850 hover:border-neutral-700 transition duration-305 cursor-help" onmouseenter="showMedalTooltip('dreamweaver')" onmouseleave="hideMedalTooltip()">
                            <i data-lucide="sparkles" class="w-5 h-5 text-neutral-600 transition duration-300"></i>
                            <div class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-amber-500 border border-black hidden" id="medal-dreamweaver-dot"></div>
                        </div>
                        <!-- Medal 5: Commander -->
                        <div id="medal-commander" class="relative group/medal flex flex-col items-center justify-center p-2.5 rounded-xl bg-neutral-900/40 border border-neutral-850 hover:border-neutral-700 transition duration-305 cursor-help" onmouseenter="showMedalTooltip('commander')" onmouseleave="hideMedalTooltip()">
                            <i data-lucide="award" class="w-5 h-5 text-neutral-600 transition duration-300"></i>
                            <div class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-purple-400 border border-black hidden" id="medal-commander-dot"></div>
                        </div>
                    </div>
                </div>

                <!-- Live Description Box -->
                <div class="bg-neutral-900/40 border border-neutral-900 rounded-xl p-3 text-[11px] font-medium leading-relaxed font-sans text-neutral-400" id="cine-sync-message-box">
                    Awaiting coordinate scheduling. Add movies or shows to your planner below to build your Cinematic Sync frequency spectrum and unlock secure achievements!
                </div>
            </div>

        </div>

        <!-- ==================== RIGHT COLUMN: DYNAMIC LIST GRIDS, FILTER CARDS, SEARCH BAR ==================== -->
        <div class="lg:col-span-7 flex flex-col gap-5">
            
            <!-- Filters & Category segments selection row -->
            <div class="release-filter-row flex flex-col sm:flex-row gap-3 justify-between items-start sm:items-center pb-4 border-b border-neutral-900">
                <div class="flex items-center gap-2">
                    <div class="p-1 px-2.5 rounded-lg bg-red-950/20 border border-red-900/30 text-red-500">
                        <i data-lucide="calendar-days" class="w-4 h-4"></i>
                    </div>
                    <h2 class="text-lg md:text-xl font-bold text-white tracking-widest font-mono uppercase">COMING RELEASES</h2>
                </div>

                <div class="flex flex-wrap items-center gap-1.5" id="format-filters">
                    <button onclick="setFilterType('ALL')" id="filter-all" class="px-3 py-1.5 rounded-xl border text-[11px] font-bold tracking-wider transition-all bg-red-600/10 border-red-650 text-red-500 cursor-pointer active-format">ALL FORMATS</button>
                    <button onclick="setFilterType('Movie')" id="filter-movie" class="px-3 py-1.5 rounded-xl border text-[11px] font-bold tracking-wider transition-all bg-neutral-950/50 border-neutral-900 text-neutral-400 hover:text-neutral-200 cursor-pointer inactive-format">MOVIES</button>
                    <button onclick="setFilterType('TV Series')" id="filter-tv" class="px-3 py-1.5 rounded-xl border text-[11px] font-bold tracking-wider transition-all bg-neutral-950/50 border-neutral-900 text-neutral-400 hover:text-neutral-200 cursor-pointer inactive-format">TV SERIES</button>
                </div>
            </div>

            <!-- Enhanced Search block -->
            <div class="relative">
                <input 
                    type="text" 
                    id="search-input"
                    oninput="handleSearch(this.value)"
                    placeholder="Search release titles, genres, creators or cast members..." 
                    class="w-full bg-neutral-900/40 border border-neutral-850 rounded-2xl pl-11 pr-28 py-3 text-sm focus:outline-none focus:border-red-600 focus:ring-1 focus:ring-red-600 transition placeholder-neutral-500 text-neutral-100 font-sans"
                >
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-neutral-500">
                    <i data-lucide="search" class="w-4.5 h-4.5"></i>
                </div>
                <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-2">
                    <kbd class="hidden sm:inline-flex items-center gap-0.5 px-2 py-0.5 rounded border border-neutral-800 bg-neutral-950 text-[10px] font-mono text-neutral-500 select-none pointer-events-none">
                        <span>⌘</span><span>K</span>
                    </kbd>
                    <button onclick="openSpotlightModal()" class="text-[10px] font-mono font-black text-red-500 hover:text-white transition uppercase cursor-pointer" title="Open Spotlight Command Center">
                        LAUNCHER
                    </button>
                </div>
                <button onclick="clearSearch()" id="search-clear-btn" class="absolute right-28 top-1/2 -translate-y-1/2 text-neutral-500 hover:text-white hidden transition">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Dynamic Release Cards List container -->
            <div class="space-y-4" id="release-cards-list"></div>

            <!-- Curated Pagination UI Component -->
            <div id="release-cards-pagination" class="flex justify-center items-center mt-3"></div>

             <!-- Curated totals dynamic footline dashboard info -->
            <div class="p-4 bg-neutral-950/45 border border-neutral-900 rounded-2xl flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center mt-3">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                    <span class="text-xs text-neutral-400">
                        You curated <strong id="fav-count-meta" class="text-white font-mono">0</strong> favorites and scheduled <strong id="add-count-meta" class="text-white font-mono">0</strong> upcoming releases.
                    </span>
                </div>
                <div class="flex items-center gap-4 flex-wrap sm:flex-nowrap">
                    <button onclick="toggleCalendarPanel()" class="text-xs font-bold text-neutral-400 hover:text-white uppercase tracking-wider flex items-center gap-1 cursor-pointer font-mono transition-colors">
                        <span>OPEN SYSTEM PLANNER</span>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>
                    <button onclick="openFocusedItemWatchparty()" class="text-xs font-bold text-red-500 hover:text-red-400 uppercase tracking-wider flex items-center gap-1.5 cursor-pointer font-mono px-3 py-1.5 bg-red-950/30 border border-red-500/30 hover:border-red-500 rounded-xl transition-all duration-300 shadow-[0_0_15px_rgba(239,68,68,0.1)] hover:shadow-[0_0_20px_rgba(239,68,68,0.3)] hover:brightness-110">
                        <i data-lucide="users" class="w-4 h-4"></i>
                        <span>PLAN WATCH PARTY</span>
                    </button>
                </div>
            </div>

        </div>

    </div>

    <!-- ==================== SLIDEOUT & DRAWER CONTROL SYSTEM ==================== -->

    <!-- 1. INTEGRATED SIDEBAR CALENDAR / PLANNING DRAWER -->
    <div id="calendar-drawer" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-sm hidden flex justify-end">
        <div class="w-full max-w-md bg-neutral-950 border-l border-neutral-850 p-6 md:p-8 flex flex-col justify-between transform translate-x-full transition-transform duration-300" id="calendar-drawer-board">
            
            <div>
                <div class="flex justify-between items-center pb-4 border-b border-neutral-800">
                    <div class="flex items-center gap-2.5">
                        <i data-lucide="calendar" class="text-red-500 w-5 h-5"></i>
                        <h3 class="text-lg font-bold text-white uppercase tracking-wider">Release Planner</h3>
                    </div>
                    <button onclick="toggleCalendarPanel()" class="p-1 px-2 text-neutral-400 hover:text-white rounded-lg hover:bg-neutral-900 cursor-pointer">
                        <i data-lucide="x" class="w-4.5 h-4.5"></i>
                    </button>
                </div>

                <p class="text-xs text-neutral-400 mt-3 mb-5 leading-relaxed">
                    The following upcoming cinematics have been registered to your personal active release plan. Trigger Google Calendar API synchronization template tags instantly.
                </p>

                <!-- Plan nodes dynamically loaded -->
                <div id="drawer-items-list" class="space-y-3 overflow-y-auto max-h-[60vh] pr-1"></div>
            </div>

            <!-- Drawer actions desk footer -->
            <div id="drawer-footer-controls" class="pt-4 border-t border-neutral-850 mt-4 space-y-3">
                <div class="flex justify-between items-center text-xs text-neutral-400">
                    <span class="font-mono">TOTAL TRACKED:</span>
                    <span id="drawer-total-indicator" class="text-white font-mono font-bold">0</span>
                </div>
                <div class="flex gap-2">
                    <button onclick="clearAllCalendarItems()" class="px-3.5 py-3 border border-neutral-850 text-neutral-400 hover:bg-neutral-900 rounded-xl text-xs font-bold hover:text-white transition uppercase font-mono">CLEAR ALL</button>
                    <button onclick="triggerSynchronize()" class="flex-1 py-3 bg-red-650 text-white font-bold rounded-xl text-xs hover:bg-red-500 shadow-md flex items-center justify-center gap-1.5 transition active:scale-95 cursor-pointer font-mono">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                        <span id="sync-btn-text">SYNC PLANNERS</span>
                    </button>
                </div>
                <p class="text-[9px] text-neutral-500 text-center select-none font-mono tracking-wide">
                    SECURED COORDINATES BROADCAST NODE SYNC ACTIVE
                </p>
            </div>

        </div>
    </div>

    <!-- 2. SUBSCRIPTION / NOTIFICATION ALERT REQUEST FORM MODAL -->
    <div id="reminder-modal" class="fixed inset-0 z-50 bg-black/85 backdrop-blur-md hidden flex items-center justify-center p-4">
        <div class="relative w-full max-w-md max-h-[90vh] overflow-y-auto bg-neutral-900 border border-neutral-850 rounded-3xl p-6 shadow-2xl modal-open-anim">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-red-600 via-red-550 to-red-800"></div>

            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-red-950/40 rounded-2xl border border-red-900/30 text-red-500">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white uppercase tracking-wider">Set Release Alert</h3>
                        <p class="text-neutral-400 text-xs">Stay synchronized 24 hours prior</p>
                    </div>
                </div>
                <button onclick="closeReminderModal()" class="p-1 px-2 text-neutral-400 hover:text-white rounded-lg hover:bg-neutral-855 cursor-pointer">
                    <i data-lucide="x" class="w-4.5 h-4.5"></i>
                </button>
            </div>

            <div id="reminder-form-container">
                <form onsubmit="handleReminderSubmit(event)" class="space-y-4">
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest font-mono text-neutral-400 mb-1.5">Select Premiere</label>
                        <select id="reminder-movie-select" class="w-full bg-neutral-950 text-neutral-200 border border-neutral-805 rounded-xl px-3.5 py-3 text-sm focus:outline-none focus:border-red-650 transition font-mono"></select>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase tracking-widest font-mono text-neutral-400 mb-1.5">Subscriber Name</label>
                        <input type="text" id="reminder-name" required placeholder="E.g., Bruce Wayne" class="w-full bg-neutral-950 text-neutral-200 border border-neutral-805 rounded-xl px-3.5 py-3 text-sm focus:outline-none focus:border-red-650 placeholder-neutral-600 transition font-sans">
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase tracking-widest font-mono text-neutral-400 mb-1.5">Email Coordinates</label>
                        <div class="relative">
                            <input type="email" id="reminder-email" required placeholder="E.g., bruce@waynecorp.com" class="w-full bg-neutral-950 text-neutral-200 border border-neutral-805 rounded-xl pl-10 pr-3.5 py-3 text-sm focus:outline-none focus:border-red-655 placeholder-neutral-600 transition font-sans">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-neutral-500">
                                <i data-lucide="mail" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-neutral-950/60 p-3.5 rounded-xl border border-neutral-805 text-[11px] text-neutral-400 flex items-start gap-2 select-none">
                        <i data-lucide="smartphone" class="w-4 h-4 text-red-500 shrink-0 mt-0.5"></i>
                        <div>
                            <span class="font-bold text-neutral-200">Transmission Node:</span> Receive custom details coordinates 24 hours prior and immediately at active launch.
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 bg-red-600 hover:bg-red-500 text-white font-bold rounded-xl text-sm shadow-md active:scale-95 transition-all font-mono">ACTIVATE REMINDER</button>
                </form>
            </div>

            <!-- Success alert notification screen -->
            <div id="reminder-success-container" class="hidden flex flex-col items-center justify-center py-6 text-center">
                <div class="w-16 h-16 bg-emerald-950/50 border border-emerald-500/35 rounded-full flex items-center justify-center text-emerald-400 mb-4 shadow-[0_0_20px_rgba(16,185,129,0.3)] select-none">
                    <i data-lucide="check" class="w-8 h-8"></i>
                </div>
                <h4 class="text-sm font-extrabold text-white uppercase tracking-wider">Alert Registered</h4>
                <p class="text-neutral-300 text-xs mt-2 max-w-xs leading-relaxed">
                    Cinematic alert activated! A notification reminder is scheduled to be dispatched to:
                </p>
                <p class="text-emerald-400 font-bold font-mono text-xs mt-2.5 bg-emerald-900/10 border border-emerald-900/35 px-4 py-1.5 rounded-lg">
                    <span id="success-email-target">user@email.com</span>
                </p>
                
                <div class="w-full mt-6">
                    <button onclick="closeReminderModal()" class="w-full py-3 bg-neutral-800 hover:bg-neutral-750 text-neutral-200 border border-neutral-750/50 rounded-xl text-xs font-bold transition uppercase tracking-widest cursor-pointer active:scale-95">
                        Dismiss
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- 4. STREAMING CINEMATIC TRAILER LIGHTBOX MODAL -->
    <div id="trailer-modal" class="fixed inset-0 z-[1000002] bg-black/95 backdrop-blur-lg hidden flex items-center justify-center p-4 shadow-3xl" onclick="closeTrailerModal()">
        <div class="relative w-full max-w-4xl aspect-video overflow-hidden bg-neutral-950 border border-neutral-850 rounded-2xl shadow-[0_0_80px_rgba(239,68,68,0.30)] modal-open-anim" onclick="event.stopPropagation()">
            <button onclick="event.stopPropagation(); closeTrailerModal();" class="absolute top-4 right-4 z-50 p-2 bg-black/80 border border-neutral-800 text-neutral-400 hover:text-white rounded-full transition-colors cursor-pointer" title="Close Trailer">
                <i data-lucide="x" class="w-4.5 h-4.5"></i>
            </button>
            <div class="w-full h-full font-sans text-white" id="trailer-video-container"></div>
        </div>
    </div>

    <!-- EMPTY DATE RELEASE ALERT MODAL -->
    <div id="empty-date-modal" class="fixed inset-0 z-50 bg-black/85 backdrop-blur-md hidden flex items-center justify-center p-4 shadow-3xl animate-fade-in" onclick="closeEmptyDateModal()">
        <div class="relative w-full max-w-sm overflow-hidden bg-neutral-950 border border-neutral-850 rounded-3xl shadow-[0_0_50px_rgba(239,68,68,0.25)] modal-open-anim p-6" onclick="event.stopPropagation()">
            <div class="flex flex-col items-center text-center">
                <!-- Glow Circle with Off Icon -->
                <div class="w-14 h-14 rounded-full bg-red-950/40 border border-red-500/30 flex items-center justify-center text-red-400 mb-4 shadow-[0_0_20px_rgba(239,68,68,0.3)]">
                    <i data-lucide="calendar-off" class="w-6 h-6"></i>
                </div>
                
                <h3 id="empty-date-title" class="text-base font-bold text-white tracking-widest font-mono uppercase">No Releases</h3>
                <p id="empty-date-message" class="text-neutral-400 text-xs mt-3 leading-relaxed font-sans max-w-xs px-2"></p>
                
                <button onclick="closeEmptyDateModal()" class="mt-6 w-full py-2.5 bg-red-650 text-white font-bold rounded-xl text-xs hover:bg-red-500 transition active:scale-95 duration-200 cursor-pointer font-mono tracking-wider uppercase">
                    ACKNOWLEDGE
                </button>
            </div>
            <button onclick="closeEmptyDateModal()" class="absolute top-4 right-4 p-1 rounded-full bg-neutral-900/60 border border-neutral-800 text-neutral-500 hover:text-white transition-colors cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- 3. DETAILED CONTENT DESCRIPTION & SPECS BIG MODAL -->
    <div id="details-modal" class="fixed inset-0 z-50 bg-black/85 backdrop-blur-md hidden flex items-center justify-center p-4 shadow-3xl" onclick="closeDetailsModal()">
        <div class="relative w-full max-w-3xl max-h-[90vh] md:max-h-[85vh] flex flex-col overflow-hidden bg-neutral-950 border border-neutral-850 rounded-3xl shadow-[0_0_50px_rgba(239,68,68,0.2)] modal-open-anim" onclick="event.stopPropagation()">
            
            <!-- Graphic Linear Gradient Billboard header backdrop -->
            <div id="modal-banner" class="h-32 sm:h-44 shrink-0 w-full relative flex items-end p-4 sm:p-6">
                <div class="absolute inset-0 bg-gradient-to-t from-neutral-950 via-neutral-950/30 to-transparent pointer-events-none"></div>
                <div class="relative z-10 font-mono">
                    <div class="flex flex-wrap items-center gap-2 mb-1.5" id="modal-banner-type-wrapper">
                        <span id="modal-banner-type" class="inline-block px-2.5 py-1 text-[9.5px] leading-none font-black rounded-full font-mono tracking-widest uppercase cursor-pointer select-none border movie-badge-glow">MOVIE</span>
                        <div id="modal-banner-review" class="flex items-center"></div>
                    </div>
                    <h2 class="text-2.5xl md:text-3xl font-extrabold text-white tracking-widest font-sans leading-snug">
                        <a id="modal-banner-title" href="#" target="_blank" class="hover:underline hover:text-red-400 transition-colors">TITLE</a>
                    </h2>
                    <p id="modal-banner-tagline" class="text-neutral-300 text-xs md:text-sm italic font-light max-w-lg mt-0.5">"Film Tagline Line"</p>
                </div>
                <button onclick="event.stopPropagation(); closeDetailsModal();" class="absolute top-4 right-4 z-50 p-2 bg-neutral-900/80 border border-neutral-800 text-neutral-400 hover:text-white rounded-full transition-colors cursor-pointer" title="Close Details">
                    <i data-lucide="x" class="w-4.5 h-4.5"></i>
                </button>
            </div>

            <!-- specs grid -->
            <div class="p-5 sm:p-8 grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6 overflow-y-auto flex-1 min-h-0">
                
                <!-- Left panel: visual mini-card -->
                <div class="md:col-span-1 flex flex-col gap-4">
                    <!-- Action Bar - Ordered first on mobile for immediate visibility in viewport -->
                    <div id="modal-actions-bar" class="flex gap-2 order-1 md:order-2"></div>

                    <!-- Poster - Hidden on mobile viewports to prevent layout clutter, appears on small tablet upwards -->
                    <div id="modal-preview-poster" class="hidden sm:flex aspect-[3/4] rounded-2xl p-4 flex-col justify-between shadow-lg relative overflow-hidden order-2 md:order-1">
                        <div class="absolute inset-0 bg-black/15 pointer-events-none"></div>
                        <div class="flex justify-between items-start z-10">
                            <span id="modal-poster-rating" class="bg-black/70 border border-white/10 px-2 py-0.5 rounded text-[9px] font-mono font-bold text-white uppercase font-sans">PG-13</span>
                            <div class="flex items-center gap-1 bg-black/85 px-1.5 py-0.5 rounded text-yellow-405 text-xs font-bold border border-yellow-500/20 font-mono">
                                <span class="text-[9px] text-yellow-400">★</span>
                                <span id="modal-poster-score" class="text-white">9.2</span>
                            </div>
                        </div>
                        <div class="flex justify-center items-center h-28 my-auto z-10 select-none opacity-90 text-center">
                            <span id="modal-poster-text-logo" class="text-xl font-black text-white drop-shadow-[0_4px_8px_rgba(0,0,0,0.85)] truncate max-w-[150px] uppercase font-sans">TITLE</span>
                        </div>
                        
                        <div class="z-10 bg-black/85 border border-neutral-850 p-2 rounded-xl flex items-center justify-between">
                            <div>
                                <p class="text-[8px] text-neutral-500 font-mono">RELEASE </p>
                                <p id="modal-poster-releasedate" class="text-xs font-bold text-white font-mono">20 JUN</p>
                            </div>
                            <i data-lucide="calendar" class="w-4.5 h-4.5 text-red-500"></i>
                        </div>
                    </div>
                </div>

                <!-- Right panel: specs/synopsis -->
                <div class="md:col-span-2 flex flex-col gap-5">
                    <div>
                        <h4 class="text-xs uppercase tracking-widest font-mono text-neutral-450 mb-2 font-bold flex items-center gap-2">
                            <i data-lucide="notebook" class="w-3.5 h-3.5 text-red-500"></i>
                            <span>STORYLINE SINOPSIS</span>
                        </h4>
                        <p id="modal-details-plot" class="text-neutral-300 text-sm leading-relaxed"></p>
                    </div>

                    <div class="flex flex-wrap gap-1.5" id="modal-details-chips"></div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-neutral-900">
                        <div>
                            <h5 id="modal-details-director-label" class="text-[10px] uppercase tracking-wider font-mono text-neutral-500 font-bold mb-1 flex items-center gap-1.5 uppercase">
                                <i data-lucide="flame" class="w-3.5 h-3.5 text-red-500 font-mono"></i>
                                <span>DIRECTOR</span>
                            </h5>
                            <p id="modal-details-director-val" class="text-white text-sm font-semibold font-sans">Name</p>
                        </div>
                        <div>
                            <h5 class="text-[10px] uppercase tracking-wider font-mono text-neutral-500 font-bold mb-1 flex items-center gap-1.5">
                                <i data-lucide="users" class="w-3.5 h-3.5 text-red-500"></i>
                                <span>STARRING</span>
                            </h5>
                            <div id="modal-details-star-val" class="flex flex-wrap gap-1 mt-1 font-sans text-neutral-350 text-xs"></div>
                        </div>
                    </div>

                    <!-- Trivia -->
                    <div id="modal-details-trivia-block" class="pt-3 border-t border-neutral-900">
                        <h4 class="text-xs uppercase tracking-widest font-mono text-neutral-450 mb-2 font-bold flex items-center gap-2">
                            <i data-lucide="help-circle" class="w-3.5 h-3.5 text-red-500"></i>
                            <span>PRODUCTION TRIVIA</span>
                        </h4>
                        <div id="modal-details-trivia-list" class="space-y-1.5 h-auto"></div>
                    </div>

                    <!-- Watchparty Coordinator -->
                    <div class="pt-4 border-t border-neutral-900">
                        <div class="p-4 bg-gradient-to-r from-red-950/20 via-neutral-950 to-red-950/20 border border-red-500/20 rounded-2xl shadow-[0_0_25px_rgba(239,68,68,0.05)] mt-3">
                            <div class="flex items-center justify-between gap-4 flex-wrap pb-3 border-b border-neutral-900">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-red-950/50 border border-red-500/30 flex items-center justify-center text-red-500 shadow-[0_0_15px_rgba(239,68,68,0.2)] animate-pulse">
                                        <i data-lucide="users" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs uppercase tracking-widest font-mono text-white font-bold leading-none">
                                            Watch Party Planner
                                        </h4>
                                        <p class="text-[9px] text-neutral-500 font-sans mt-1">Host a live transmission event</p>
                                    </div>
                                </div>
                                <button onclick="toggleWatchparty()" id="wp-toggle-btn" class="flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold font-mono tracking-wider text-red-400 hover:text-white bg-red-950/30 border border-red-500/40 hover:border-red-400 rounded-lg hover:bg-gradient-to-r hover:from-red-600 hover:to-red-800 transition-all duration-300 cursor-pointer shadow-[0_0_10px_rgba(239,68,68,0.1)] hover:shadow-[0_0_15px_rgba(239,68,68,0.3)]">
                                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                                    <span id="wp-toggle-text">PLAN PARTY</span>
                                </button>
                            </div>
                            
                            <div id="modal-details-watchparty" class="hidden pt-3 transition-all duration-300">
                                <p class="text-[10px] text-neutral-400 mb-3.5 font-sans leading-relaxed">Select date, time window, and meeting medium to generate an interactive copyable cyber-broadcast invitation code.</p>
                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <label class="block text-[8px] font-mono text-neutral-500 uppercase tracking-wider mb-1">Channel Date</label>
                                        <input type="date" id="wp-date" class="w-full text-xs bg-neutral-900 border border-neutral-800 rounded px-2.5 py-1.5 text-white font-mono focus:border-red-500 outline-none focus:ring-1 focus:ring-red-500/50">
                                    </div>
                                    <div>
                                        <label class="block text-[8px] font-mono text-neutral-500 uppercase tracking-wider mb-1">Start Time</label>
                                        <input type="time" id="wp-time" value="20:00" class="w-full text-xs bg-neutral-900 border border-neutral-800 rounded px-2.5 py-1.5 text-white font-mono focus:border-red-500 outline-none focus:ring-1 focus:ring-red-500/50">
                                    </div>
                                </div>
                                <div class="mb-3.5">
                                    <label class="block text-[8px] font-mono text-neutral-500 uppercase tracking-wider mb-1">Transmission Host</label>
                                    <select id="wp-platform" class="w-full text-xs bg-neutral-900 border border-neutral-800 rounded px-2.5 py-1.5 text-white font-sans focus:border-red-500 outline-none focus:ring-1 focus:ring-red-500/50">
                                        <option value="Discord Cyber-Theater">Discord Cyber-Theater</option>
                                        <option value="Telegram Watch Channel">Telegram Watch Channel</option>
                                        <option value="Physical Cinema Hub">Physical Cinema Hub</option>
                                        <option value="Google Meet Streaming Box">Google Meet Streaming Box</option>
                                        <option value="Virtual Broadcast Node">Virtual Broadcast Node</option>
                                    </select>
                                </div>
                                <button onclick="generateAndCopyWatchparty()" class="w-full flex items-center justify-center gap-1.5 h-9 bg-gradient-to-r from-red-650 to-red-800 hover:from-red-500 hover:to-red-700 text-white transition-all py-1 px-3 rounded-lg text-xs font-bold tracking-wide cursor-pointer font-sans active:scale-95 duration-200 shadow-md shadow-red-950/40">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                                    <span class="font-mono text-[9px] tracking-widest uppercase">COPY INVITATION DIRECTLY</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- 5. SPOTLIGHT COMMAND CENTER MODAL -->
    <div id="spotlight-modal" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md hidden flex items-start justify-center p-4 md:p-12 shadow-3xl" onclick="closeSpotlightModal()">
        <div class="relative w-full max-w-2xl overflow-hidden bg-neutral-950 border border-neutral-850 rounded-3xl mt-12 flex flex-col max-h-[80vh] shadow-[0_0_50px_rgba(239,68,68,0.15)] modal-open-anim" onclick="event.stopPropagation()">
            
            <!-- Spotlight Input Header -->
            <div class="p-4 border-b border-neutral-900 relative flex items-center">
                <input 
                    type="text" 
                    id="spotlight-search-input"
                    oninput="handleSpotlightSearch(this.value)"
                    placeholder="Type to find titles, directors, genres, cast members..." 
                    class="w-full bg-transparent text-white text-base focus:outline-none placeholder-neutral-600 font-sans pl-10 pr-16"
                >
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-neutral-500">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </div>
                <button onclick="closeSpotlightModal()" class="absolute right-4 top-1/2 -translate-y-1/2 text-neutral-500 hover:text-white font-mono text-[10px] uppercase px-2 py-1 rounded bg-neutral-900 cursor-pointer">
                    ESC
                </button>
            </div>
            
            <!-- Dynamic Results Body -->
            <div id="spotlight-results" class="overflow-y-auto p-4 space-y-4">
                <!-- Group categories: Movies, TV Shows, Creators, Cast, Genres -->
                <div class="text-center py-8 text-neutral-500 text-sm font-sans select-none">
                    <p>Begin typing to execute cyber-fuzzy database searches...</p>
                    <div class="flex flex-wrap justify-center gap-1.5 mt-4">
                        <span class="px-2 py-0.5 rounded bg-neutral-900 text-[10px] font-mono text-neutral-400">#Villeneuve</span>
                        <span class="px-2 py-0.5 rounded bg-neutral-900 text-[10px] font-mono text-neutral-400">#Action</span>
                        <span class="px-2 py-0.5 rounded bg-neutral-900 text-[10px] font-mono text-neutral-400">#Cillian Murphy</span>
                        <span class="px-2 py-0.5 rounded bg-neutral-900 text-[10px] font-mono text-neutral-400">#Sci-Fi</span>
                    </div>
                </div>
            </div>
            
            <!-- Spotlight Footer with instructions -->
            <div class="p-3 bg-neutral-900 border-t border-neutral-850 text-[10px] font-mono text-neutral-500 flex justify-between items-center px-4 select-none">
                <div class="flex gap-4">
                    <span><kbd class="px-1.5 border border-neutral-800 bg-neutral-950 rounded">↵</kbd> Select</span>
                </div>
                <span>Fuzzy matching powered by client CDN database.</span>
            </div>
        </div>
    </div>

    <!-- 4. FLOATING SYSTEM ACTION TOAST ALERTS -->
    <div id="system-toast" class="fixed bottom-6 right-6 z-55 bg-neutral-950 border border-red-900/50 rounded-2xl p-4 shadow-2xl flex items-center gap-3 max-w-sm hidden transition">
        <div class="p-2 bg-red-950/40 rounded-xl border border-red-900/45 text-red-400">
            <i data-lucide="sparkles" class="w-4.5 h-4.5 animate-pulse"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p id="toast-text-msg" class="text-xs text-neutral-200 leading-snug font-medium font-sans"></p>
        </div>
        <button onclick="dismissToast()" class="text-neutral-500 hover:text-white cursor-pointer ml-1">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>

</div>

<!-- ==================== WEB APP DESK INTELLIGENCE SCRIPT ==================== -->
<script>
    // 1. Injected dynamic WordPress releases 
    const SCHEDULE_ITEMS = <?php echo insom_safe_json_encode_diagnostic( $posts_data, '[]' ); ?>;
    window.CINEMATIC_DIAGNOSTICS = <?php echo insom_safe_json_encode_diagnostic( $cinematic_diagnostics, '{}' ); ?>;
    console.log("Cinematic Diagnostics Initialized:", window.CINEMATIC_DIAGNOSTICS);
    
    // Sync scheduled and favorites from server if logged in
    const IS_USER_LOGGED_IN = <?php echo is_user_logged_in() ? 'true' : 'false'; ?>;
    <?php
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $fav_mv = array_map( 'sanitize_text_field', (array) get_user_meta( $user_id, 'favourite_mv_id' ) );
        $fav_sh = array_map( 'sanitize_text_field', (array) get_user_meta( $user_id, 'favourite_show_id' ) );
        $all_favs = array_values( array_filter( array_merge( $fav_mv, $fav_sh ) ) );
        $scheds = array_values( array_filter( array_map( 'sanitize_text_field', (array) get_user_meta( $user_id, 'insom_scheduled_releases' ) ) ) );
        ?>
        const serverFavs = <?php echo json_encode( $all_favs ); ?>;
        const serverScheds = <?php echo json_encode( $scheds ); ?>;
        <?php
    } else {
        ?>
        const serverFavs = [];
        const serverScheds = [];
        <?php
    }
    ?>

    // 2. Local State Management (Syncing Favorites/Calendar local variables)
    let favoritedIds = JSON.parse(localStorage.getItem('standalone_fav')) || [];
    let scheduledIds = JSON.parse(localStorage.getItem('standalone_scheduled')) || [];
    let registeredRemindersCount = JSON.parse(localStorage.getItem('standalone_reminder_count')) || 0;

    // Merge server stats into browser localStorage
    if (IS_USER_LOGGED_IN) {
        serverFavs.forEach(function(id) {
            var s = String(id);
            var n = Number(id);
            if (!favoritedIds.includes(s) && !favoritedIds.includes(n)) favoritedIds.push(s);
        });
        serverScheds.forEach(function(id) {
            var s = String(id);
            var n = Number(id);
            if (!scheduledIds.includes(s) && !scheduledIds.includes(n)) scheduledIds.push(s);
        });
        localStorage.setItem('standalone_fav', JSON.stringify(favoritedIds));
        localStorage.setItem('standalone_scheduled', JSON.stringify(scheduledIds));
    }

    // Cross-Component Real-Time Sync Listeners
    window.addEventListener('insom_planner_updated', function(e) {
        if (e.detail && Array.isArray(e.detail.list)) {
            scheduledIds = e.detail.list;
            if (typeof renderReleaseCards === 'function') renderReleaseCards();
            if (typeof populateDrawerList === 'function') populateDrawerList();
        }
    });

    window.addEventListener('storage', function(e) {
        if (e.key === 'standalone_scheduled' || e.key === 'insom_planner') {
            try {
                const list = JSON.parse(localStorage.getItem('standalone_scheduled') || localStorage.getItem('insom_planner')) || [];
                scheduledIds = list;
                if (typeof renderReleaseCards === 'function') renderReleaseCards();
                if (typeof populateDrawerList === 'function') populateDrawerList();
            } catch(err) {}
        }
    });
    
    // Import DB cookies into default client memory if present
    const idc_favs_cookie = "<?php echo isset($_COOKIE['idc_favs']) ? esc_attr($_COOKIE['idc_favs']) : ''; ?>";
    if (idc_favs_cookie) {
        const parsedCookieFavs = idc_favs_cookie.split(',');
        parsedCookieFavs.forEach(id => {
            if (id && !favoritedIds.includes(id)) {
                favoritedIds.push(id);
            }
        });
    }

    let activeFilterType = 'ALL';
    let searchQuery = '';
    let currentFocusedItem = SCHEDULE_ITEMS.find(item => item.is_pinned) || (SCHEDULE_ITEMS.length > 0 ? SCHEDULE_ITEMS[0] : null);
    let toastTimeout = null;

    // Teaser smartphone state simulations
    let phonePlaying = false;
    let phoneVolume = true;

    // Pagination state
    let currentPage = 1;
    const itemsPerPage = 10;

    // Calendar Engine dynamic browsing state
    let displayedCalendarMonth = currentFocusedItem ? (currentFocusedItem.monthNum - 1) : (new Date()).getMonth();
    let displayedCalendarYear = currentFocusedItem ? currentFocusedItem.year : (new Date()).getFullYear();

    // Dynamically browse to previous month in Calendar Engine
    function prevCalendarMonth() {
        displayedCalendarMonth--;
        if (displayedCalendarMonth < 0) {
            displayedCalendarMonth = 11;
            displayedCalendarYear--;
        }
        renderCalendar();
        lucide.createIcons();
    }

    // Dynamically browse to next month in Calendar Engine
    function nextCalendarMonth() {
        displayedCalendarMonth++;
        if (displayedCalendarMonth > 11) {
            displayedCalendarMonth = 0;
            displayedCalendarYear++;
        }
        renderCalendar();
        lucide.createIcons();
    }

    // 3. Render Desk Spiral Wirebound Calendar Grid Coordinates dynamically
    function renderCalendar() {
        const grid = document.getElementById('calendar-grid-cells');
        if (!grid) return;
        grid.innerHTML = '';

        // If no items exist, exit gracefully
        if (SCHEDULE_ITEMS.length === 0) {
            grid.innerHTML = '<div class="col-span-7 py-8 text-neutral-600 text-xs text-center font-mono uppercase">Empty Grid</div>';
            return;
        }

        const monthNames = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
        const activeMonthName = monthNames[displayedCalendarMonth];
        
        // Update header block title
        const calendarHeaderLabel = document.getElementById('calendar-header-month');
        if (calendarHeaderLabel) {
            calendarHeaderLabel.innerText = `${activeMonthName} ${displayedCalendarYear}`;
        }

        const firstDayOfMonth = new Date(displayedCalendarYear, displayedCalendarMonth, 1);
        let startDayOffset = firstDayOfMonth.getDay() - 1; // Mon offset (0: Mon, 6: Sun)
        if (startDayOffset < 0) startDayOffset = 6;

        const daysInMonth = new Date(displayedCalendarYear, displayedCalendarMonth + 1, 0).getDate();
        const daysInPrevMonth = new Date(displayedCalendarYear, displayedCalendarMonth, 0).getDate();

        const cells = [];

        // Prepend previous grayed-out dates
        for (let i = startDayOffset - 1; i >= 0; i--) {
            cells.push({ day: daysInPrevMonth - i, current: false, movieId: null });
        }

        // Add current month days
        for (let d = 1; d <= daysInMonth; d++) {
            // Check if any registered item lands exactly on this date
            const checkDateStr = `${displayedCalendarYear}-${String(displayedCalendarMonth + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
            const matchedItem = SCHEDULE_ITEMS.find(item => item.originalReleaseDate === checkDateStr);
            
            cells.push({
                day: d,
                current: true,
                movieId: matchedItem ? matchedItem.id : null
            });
        }

        // Append padding dates
        const totalCellsNeeded = Math.ceil(cells.length / 7) * 7;
        let nextMonthDay = 1;
        while (cells.length < totalCellsNeeded) {
            cells.push({ day: nextMonthDay++, current: false, movieId: null });
        }

        // Render each cell block
        cells.forEach(item => {
            const cell = document.createElement('div');
            cell.className = `aspect-square flex items-center justify-center relative select-none rounded-full cursor-pointer transition-all duration-300`;
            
            const isSelected = item.movieId && currentFocusedItem && String(currentFocusedItem.id) === String(item.movieId);
            
            const cellText = document.createElement('span');
            cellText.className = `text-xs z-10 font-mono ${item.current ? 'text-neutral-300' : 'text-neutral-600'} ${item.movieId ? 'font-bold text-white' : ''}`;
            cellText.innerText = item.day;
            cell.appendChild(cellText);

            if (item.movieId) {
                const marker = document.createElement('div');
                marker.className = `absolute inset-0.5 rounded-full border-2 transition-all duration-300 ${isSelected ? 'bg-red-600 border-red-400 scale-110 shadow-[0_0_12px_rgba(239,68,68,0.65)]' : 'border-red-650/40 hover:border-red-500 hover:bg-neutral-800/30'}`;
                cell.appendChild(marker);

                cell.onclick = () => {
                    const found = SCHEDULE_ITEMS.find(f => String(f.id) === String(item.movieId));
                    if (found) {
                        setFocusedItem(found);
                    }
                };

                // Floating mini-tooltip descriptions
                const tooltip = document.createElement('div');
                const filmObj = SCHEDULE_ITEMS.find(f => String(f.id) === String(item.movieId));
                tooltip.className = 'absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-32 hidden group-hover:block z-30 pointer-events-none scale-90 transition-all';
                tooltip.innerHTML = `
                    <div class="bg-black border border-red-950 rounded px-2 py-1 text-[9px] text-center text-white shadow-xl">
                        <p class="font-bold text-red-400 truncate uppercase font-sans">${filmObj.title}</p>
                        <p class="text-[8px] text-neutral-400 capitalize font-mono">${filmObj.type} • ${filmObj.displayDate}</p>
                    </div>
                    <div class="w-1.5 h-1.5 bg-black border-r border-b border-red-900 rotate-45 mx-auto -mt-1"></div>
                `;
                cell.className += ' group';
                cell.appendChild(tooltip);
            } else {
                // If clicked but no movies/TV shows are released on this day
                cell.onclick = () => {
                    const monthNamesLong = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                    let targetMonth = displayedCalendarMonth;
                    let targetYear = displayedCalendarYear;
                    
                    if (!item.current) {
                        if (item.day > 15) {
                            targetMonth--;
                            if (targetMonth < 0) {
                                targetMonth = 11;
                                targetYear--;
                            }
                        } else {
                            targetMonth++;
                            if (targetMonth > 11) {
                                targetMonth = 0;
                                targetYear++;
                            }
                        }
                    }
                    const clickedDateStr = `${monthNamesLong[targetMonth]} ${item.day}, ${targetYear}`;
                    openEmptyDateModal(clickedDateStr);
                };
            }

            grid.appendChild(cell);
        });

        // Update active selection bottom labels description
        const calendarFooter = document.getElementById('calendar-selection-footer');
        const footerMovieSpan = document.getElementById('calendar-selection-movie-name');
        if (calendarFooter && footerMovieSpan && currentFocusedItem) {
            calendarFooter.classList.remove('hidden');
            footerMovieSpan.innerHTML = `<a href="${currentFocusedItem.permalink || '#'}" target="_blank" onclick="event.stopPropagation(); if (this.getAttribute('href') && this.getAttribute('href') !== '#') { window.open(this.getAttribute('href'), '_blank'); } return false;" class="hover:underline hover:text-red-500 transition-colors">${currentFocusedItem.title}</a>`;
        } else if (calendarFooter) {
            calendarFooter.classList.add('hidden');
        }
    }

    // 4. Render Smartphone Mockup reactive live sync screens
    function renderSmartphone() {
        const phoneCard = document.getElementById('phone-dynamic-card');
        if (!phoneCard) return;

        if (!currentFocusedItem) {
            phoneCard.innerHTML = `<div class="text-center py-8 text-xs text-neutral-500 font-mono">Awaiting Sync Transmission Node...</div>`;
            return;
        }

        const imageBackgroundStyle = currentFocusedItem.thumb 
            ? `background-image: url('${currentFocusedItem.thumb}'); background-size: cover; background-position: center;` 
            : `background: ${currentFocusedItem.posterGradient};`;

        // Calculate dynamic real countdown metrics
        const releaseDateTime = new Date(currentFocusedItem.originalReleaseDate).getTime();
        const difference = releaseDateTime - new Date().getTime();
        
        let days = 0, hours = 0, mins = 0;
        if (difference > 0) {
            days = Math.floor(difference / (1000 * 60 * 60 * 24));
            hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            mins = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
        } else {
            // Already released or default placeholder countdown values
            days = 0;
            hours = 0;
            mins = 0;
        }

        phoneCard.innerHTML = `
            <div class="space-y-3">
                <div class="aspect-[16/9] w-full rounded-xl relative overflow-hidden flex items-end p-3 transition-all duration-500" style="${imageBackgroundStyle}">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/30 to-transparent pointer-events-none"></div>
                    
                    <div class="relative z-10 w-full flex justify-between items-end">
                        <div class="min-w-0 flex-1 mr-2">
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="inline-block text-[8px] px-1.5 py-0.5 rounded uppercase tracking-widest font-mono cursor-pointer pointer-events-auto select-none border ${currentFocusedItem.type === 'Movie' ? 'movie-badge-glow' : 'tv-badge-glow'}" onclick="event.stopPropagation(); setFilterType('${currentFocusedItem.type}')">${currentFocusedItem.type}</span>
                                ${currentFocusedItem.genre && currentFocusedItem.genre.length ? `
                                    <span class="inline-block text-[7.5px] text-neutral-400 font-mono select-none uppercase tracking-wider bg-neutral-900/40 px-1 py-0.5 rounded border border-neutral-800/10">${currentFocusedItem.genre.slice(0, 1).join('')}</span>
                                ` : ''}
                                ${currentFocusedItem.has_review_rating ? `
                                    <span class="flex items-center gap-0.5 text-emerald-400 font-extrabold bg-emerald-950/30 px-1.5 py-0.5 rounded border border-emerald-500/15 text-[7.5px] font-mono leading-none" title="${currentFocusedItem.review_count} user reviews">
                                        <i data-lucide="star" class="w-1.5 h-1.5 fill-emerald-400 text-emerald-400"></i>
                                        <span>${currentFocusedItem.review_rating}/10</span>
                                    </span>
                                ` : `
                                    <span class="flex items-center gap-0.5 text-neutral-450 bg-neutral-900/40 px-1.5 py-0.5 rounded border border-neutral-800 text-[7px] font-mono leading-none">
                                        <i data-lucide="star-off" class="w-1.5 h-1.5 text-neutral-500"></i>
                                        <span>No reviews</span>
                                    </span>
                                `}
                            </div>
                            <h4 class="text-[11px] font-black text-white uppercase tracking-wider line-clamp-1 mt-1 font-sans">
                                <a href="${currentFocusedItem.permalink || '#'}" target="_blank" onclick="event.stopPropagation(); if (this.getAttribute('href') && this.getAttribute('href') !== '#') { window.open(this.getAttribute('href'), '_blank'); } return false;" class="hover:underline hover:text-red-400 transition-colors">${currentFocusedItem.title}</a>
                            </h4>
                        </div>

                        <button onclick="event.stopPropagation(); playTrailer('${currentFocusedItem.trailerUrl || ''}', '${currentFocusedItem.title.replace(/'/g, "\\'")}')" class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center text-white cursor-pointer hover:bg-red-500 hover:scale-105 transition shadow-lg shrink-0" title="Watch Trailer">
                            <i data-lucide="play" class="w-3.5 h-3.5 fill-white text-white ml-0.5"></i>
                        </button>
                    </div>
                </div>

                <!-- Numerical Countdown Grid -->
                <div class="grid grid-cols-3 gap-1 bg-neutral-950 p-2 rounded-xl text-center font-mono border border-neutral-900">
                    <div>
                        <p class="text-sm font-bold text-white">${days}</p>
                        <p class="text-[8px] text-neutral-500 font-semibold uppercase font-mono">DAYS</p>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-white">${hours}</p>
                        <p class="text-[8px] text-neutral-500 font-semibold uppercase font-mono">HOURS</p>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-white">${mins}</p>
                        <p class="text-[8px] text-neutral-500 font-semibold uppercase font-mono">MINS</p>
                    </div>
                </div>

                <!-- Soundtrack active soundscape generator -->
                <div class="bg-neutral-950 border border-neutral-900 rounded-2xl p-3 flex flex-col gap-2.5 transition-all duration-300 shadow-[0_4px_20px_rgba(0,0,0,0.6)]">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-[8px] font-black tracking-widest text-red-500 font-mono uppercase">CINEMATIC SYNTH</p>
                            <p class="text-[10px] text-white font-sans font-medium mt-0.5 max-w-[120px] truncate" id="phone-soundscape-status">${phonePlaying ? (currentFocusedItem.genre && currentFocusedItem.genre[0] ? currentFocusedItem.genre[0] : 'Feature') + ' Ambient' : 'Engine Standing By'}</p>
                        </div>
                        
                        <!-- Soundwave bars visualizer -->
                        <div class="flex items-end gap-[3px] px-1 h-8 select-none">
                            <span class="soundwave-bar w-[3px] rounded-full bg-red-500 transition-all duration-100" style="height: 6px;"></span>
                            <span class="soundwave-bar w-[3px] rounded-full bg-red-500/80 transition-all duration-100" style="height: 12px;"></span>
                            <span class="soundwave-bar w-[3px] rounded-full bg-red-400/90 transition-all duration-100" style="height: 8px;"></span>
                            <span class="soundwave-bar w-[3px] rounded-full bg-red-500 transition-all duration-100" style="height: 14px;"></span>
                            <span class="soundwave-bar w-[3px] rounded-full bg-red-600 transition-all duration-100" style="height: 4px;"></span>
                            <span class="soundwave-bar w-[3px] rounded-full bg-red-550 transition-all duration-100" style="height: 10px;"></span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between border-t border-neutral-905 pt-2 gap-2 mt-0.5">
                        <div class="flex gap-1.5 shrink-0">
                            <!-- Play/Pause -->
                            <button onclick="togglePhonePlay()" class="w-7 h-7 rounded-lg bg-neutral-900 border border-neutral-800 flex items-center justify-center text-neutral-300 hover:text-white hover:bg-neutral-800 hover:border-neutral-750 transition cursor-pointer" title="Toggle Soundscape Stream">
                                <i data-lucide="${phonePlaying ? 'pause' : 'play'}" class="w-3.5 h-3.5 ${phonePlaying ? 'text-red-500 stroke-[2.5]' : 'text-neutral-400'}"></i>
                            </button>
                            <!-- Volume Mute -->
                            <button onclick="togglePhoneVolume()" class="w-7 h-7 rounded-lg bg-neutral-900 border border-neutral-800 flex items-center justify-center text-neutral-300 hover:text-white hover:bg-neutral-800 hover:border-neutral-750 transition cursor-pointer" title="Toggle Mute Volume">
                                <i data-lucide="${phoneVolume ? 'volume-2' : 'volume-x'}" class="w-3.5 h-3.5 ${phoneVolume ? 'text-red-400' : 'text-neutral-500'}"></i>
                            </button>
                        </div>
                        <span class="text-[8px] text-neutral-500 font-semibold font-mono uppercase text-right leading-none max-w-[90px] truncate select-none">${phonePlaying ? 'BROADCASTING LIVE' : 'SYNTHESIZER STANDBY'}</span>
                    </div>

                    <!-- Coordinate Watch Party CTA on Phone mockup -->
                    <button onclick="openFocusedItemWatchparty()" class="w-full py-2 bg-gradient-to-r from-red-600 to-red-800 text-white font-black rounded-lg text-[9px] hover:brightness-110 active:scale-98 transition flex items-center justify-center gap-1.5 shadow-[0_4px_12px_rgba(239,68,68,0.25)] select-none cursor-pointer uppercase tracking-wider font-mono">
                        <i data-lucide="users" class="w-3 h-3 text-red-300 animate-pulse"></i>
                        <span>🍿 Coordinate Watch Party</span>
                    </button>
                </div>
            </div>
        `;
        
        lucide.createIcons();
    }

    // ==========================================
    // Real-Time High-Fidelity Synthesized Cinematic Ambient Audio Engine
    // ==========================================
    const CineAudioEngine = {
        ctx: null,
        oscillators: [],
        gains: [],
        filter: null,
        mainGain: null,
        analyser: null,
        visualizing: false,
        pulseInterval: null,
        twinkleInterval: null,

        init() {
            if (this.ctx) return;
            const AudioContextClass = window.AudioContext || window.webkitAudioContext;
            if (!AudioContextClass) return;
            
            try {
                this.ctx = new AudioContextClass();
                
                // Create master fader gain node
                this.mainGain = this.ctx.createGain();
                this.mainGain.gain.setValueAtTime(0.0, this.ctx.currentTime);
                
                // Create real-time audio analysis module
                this.analyser = this.ctx.createAnalyser();
                this.analyser.fftSize = 32; // small size for high-frequency spectrum visualizer
                
                this.mainGain.connect(this.analyser);
                this.analyser.connect(this.ctx.destination);
            } catch (err) {
                console.warn("Web Audio Context could not initialize:", err);
            }
        },

        start(genreList) {
            this.init();
            if (!this.ctx) return;

            // Unlock suspended context state safely (browser policy requirement)
            if (this.ctx.state === 'suspended') {
                this.ctx.resume();
            }

            // Mute previous active oscillators to avoid harmonic piling
            this.stopAll();

            const primaryGenre = (genreList && genreList.length > 0) ? genreList[0].toLowerCase() : 'drama';
            const now = this.ctx.currentTime;

            if (primaryGenre.includes('sci-fi') || primaryGenre.includes('mystery')) {
                // Sci-Fi Vibe: Deep space cosmic drone with sweeping filter
                const osc1 = this.ctx.createOscillator();
                osc1.type = 'sawtooth';
                osc1.frequency.setValueAtTime(55, now); // A1 note

                const osc2 = this.ctx.createOscillator();
                osc2.type = 'triangle';
                osc2.frequency.setValueAtTime(55.3, now); // Detuned chorus effect

                const filter = this.ctx.createBiquadFilter();
                filter.type = 'lowpass';
                filter.frequency.setValueAtTime(140, now);
                filter.Q.setValueAtTime(5, now);

                // Sweep the lowpass filter cutoffs slowly using an LFO oscillator
                const lfo = this.ctx.createOscillator();
                lfo.frequency.setValueAtTime(0.12, now); // 0.12 Hz sweeping
                
                const lfoGain = this.ctx.createGain();
                lfoGain.gain.setValueAtTime(75, now);

                lfo.connect(lfoGain);
                lfoGain.connect(filter.frequency);

                const trackGain = this.ctx.createGain();
                trackGain.gain.setValueAtTime(0.08, now);

                osc1.connect(filter);
                osc2.connect(filter);
                filter.connect(trackGain);
                trackGain.connect(this.mainGain);

                osc1.start(now);
                osc2.start(now);
                lfo.start(now);

                this.oscillators.push(osc1, osc2, lfo);
                this.gains.push(trackGain, lfoGain);

            } else if (primaryGenre.includes('action') || primaryGenre.includes('adventure') || primaryGenre.includes('fantasy')) {
                // Action/Fantasy Vibe: Epic structural low-frequency heartbeat pulse
                const osc1 = this.ctx.createOscillator();
                osc1.type = 'triangle';
                osc1.frequency.setValueAtTime(65.4, now); // C2 base freq

                const osc2 = this.ctx.createOscillator();
                osc2.type = 'sawtooth';
                osc2.frequency.setValueAtTime(98.0, now); // G2 fifth chord interval

                const filter = this.ctx.createBiquadFilter();
                filter.type = 'lowpass';
                filter.frequency.setValueAtTime(110, now);

                const trackGain = this.ctx.createGain();
                trackGain.gain.setValueAtTime(0.01, now);

                osc1.connect(filter);
                osc2.connect(filter);
                filter.connect(trackGain);
                trackGain.connect(this.mainGain);

                osc1.start(now);
                osc2.start(now);

                // Periodic epic double click pulse simulation
                this.pulseInterval = setInterval(() => {
                    if (!this.ctx || this.ctx.state === 'suspended') return;
                    const pTime = this.ctx.currentTime;
                    try {
                        trackGain.gain.setValueAtTime(0.01, pTime);
                        trackGain.gain.exponentialRampToValueAtTime(0.14, pTime + 0.08);
                        trackGain.gain.exponentialRampToValueAtTime(0.01, pTime + 0.22);
                        trackGain.gain.exponentialRampToValueAtTime(0.09, pTime + 0.32);
                        trackGain.gain.exponentialRampToValueAtTime(0.005, pTime + 0.55);
                    } catch (e) {}
                }, 1600);

                this.oscillators.push(osc1, osc2);
                this.gains.push(trackGain);

            } else if (primaryGenre.includes('animation') || primaryGenre.includes('comedy') || primaryGenre.includes('family')) {
                // Animation Vibe: Ambient warm drone + sparkling pentatonic chime tones
                const osc1 = this.ctx.createOscillator();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(110, now); // A2 fundamental hum

                const filter = this.ctx.createBiquadFilter();
                filter.type = 'lowpass';
                filter.frequency.setValueAtTime(220, now);

                const trackGain = this.ctx.createGain();
                trackGain.gain.setValueAtTime(0.07, now);

                osc1.connect(filter);
                filter.connect(trackGain);
                trackGain.connect(this.mainGain);

                osc1.start(now);

                const notes = [220, 261.63, 293.66, 329.63, 392.00, 440.00]; // Pentatonic scales
                this.twinkleInterval = setInterval(() => {
                    if (!this.ctx || this.ctx.state === 'suspended') return;
                    const tTime = this.ctx.currentTime;
                    
                    try {
                        const spark = this.ctx.createOscillator();
                        spark.type = 'sine';
                        spark.frequency.setValueAtTime(notes[Math.floor(Math.random() * notes.length)] * (Math.random() > 0.5 ? 2 : 3), tTime);

                        const sparkGain = this.ctx.createGain();
                        sparkGain.gain.setValueAtTime(0.0, tTime);
                        sparkGain.gain.linearRampToValueAtTime(0.035, tTime + 0.1);
                        sparkGain.gain.exponentialRampToValueAtTime(0.001, tTime + 0.7);

                        spark.connect(sparkGain);
                        sparkGain.connect(this.mainGain);
                        
                        spark.start(tTime);
                        spark.stop(tTime + 0.85);
                    } catch (e) {}
                }, 950);

                this.oscillators.push(osc1);
                this.gains.push(trackGain);

            } else {
                // Default Vibe: Lush analog cinematic sound pad
                const osc1 = this.ctx.createOscillator();
                osc1.type = 'triangle';
                osc1.frequency.setValueAtTime(73.42, now); // D2 note

                const osc2 = this.ctx.createOscillator();
                osc2.type = 'triangle';
                osc2.frequency.setValueAtTime(110.0, now); // A2 fifth note

                const osc3 = this.ctx.createOscillator();
                osc3.type = 'sine';
                osc3.frequency.setValueAtTime(146.84, now); // D3 octave note

                const filter = this.ctx.createBiquadFilter();
                filter.type = 'lowpass';
                filter.frequency.setValueAtTime(180, now);
                filter.Q.setValueAtTime(3, now);

                const trackGain = this.ctx.createGain();
                trackGain.gain.setValueAtTime(0.12, now);

                osc1.connect(filter);
                osc2.connect(filter);
                osc3.connect(filter);
                filter.connect(trackGain);
                trackGain.connect(this.mainGain);

                osc1.start(now);
                osc2.start(now);
                osc3.start(now);

                this.oscillators.push(osc1, osc2, osc3);
                this.gains.push(trackGain);
            }

            // Smoothly ramp up master volume to prevent sharp visual pops & auditory clicks
            this.updateVolume();
            
            // Start reading real-time analysis
            this.startVisualizer();
        },

        updateVolume() {
            if (!this.mainGain || !this.ctx) return;
            const targetVolume = phoneVolume ? 0.38 : 0.0;
            try {
                this.mainGain.gain.linearRampToValueAtTime(targetVolume, this.ctx.currentTime + 0.5);
            } catch (e) {}
        },

        stopAll() {
            if (this.pulseInterval) {
                clearInterval(this.pulseInterval);
                this.pulseInterval = null;
            }
            if (this.twinkleInterval) {
                clearInterval(this.twinkleInterval);
                this.twinkleInterval = null;
            }

            this.oscillators.forEach(osc => {
                try { osc.stop(); } catch(e) {}
            });
            this.oscillators = [];
            this.gains = [];
        },

        pause() {
            if (this.mainGain && this.ctx) {
                try {
                    this.mainGain.gain.linearRampToValueAtTime(0.0, this.ctx.currentTime + 0.25);
                } catch (e) {}
                
                setTimeout(() => {
                    this.stopAll();
                }, 280);
            }
            this.visualizing = false;
        },

        startVisualizer() {
            if (this.visualizing) return;
            this.visualizing = true;

            const updateBars = () => {
                if (!this.visualizing || !this.analyser) {
                    // Turn bars down flat
                    const bars = document.querySelectorAll('.soundwave-bar');
                    if (bars.length > 0) {
                        bars.forEach((bar) => {
                            bar.style.height = '6px';
                        });
                    }
                    return;
                }

                const dataArray = new Uint8Array(this.analyser.frequencyBinCount);
                this.analyser.getByteFrequencyData(dataArray);

                const bars = document.querySelectorAll('.soundwave-bar');
                if (bars.length > 0) {
                    bars.forEach((bar, index) => {
                        const val = dataArray[index % dataArray.length] || 0;
                        // Map byte level 0-255 to custom heights: 4px to 28px
                        const mappedHeight = Math.max(4, Math.floor((val / 255) * 26) + 4);
                        bar.style.height = `${mappedHeight}px`;
                    });
                }

                requestAnimationFrame(updateBars);
            };

            requestAnimationFrame(updateBars);
        }
    };

    function togglePhonePlay() {
        phonePlaying = !phonePlaying;
        if (phonePlaying) {
            CineAudioEngine.start(currentFocusedItem ? currentFocusedItem.genre : []);
            summonToast("Synthesized Cinema Atmosphere active!");
        } else {
            CineAudioEngine.pause();
            summonToast("Atmography stream paused.");
        }
        renderSmartphone();
    }

    function togglePhoneVolume() {
        phoneVolume = !phoneVolume;
        CineAudioEngine.updateVolume();
        summonToast(phoneVolume ? "Ambient audio unmuted." : "Atmosphere audio muted.");
        renderSmartphone();
    }

    // 5. Render Movies & TV Schedule Cards on Right Container
    function renderReleaseCards() {
        const container = document.getElementById('release-cards-list');
        if (!container) return;
        container.innerHTML = '';

        if (SCHEDULE_ITEMS.length === 0) {
            container.innerHTML = `
                <div class="text-center py-20 bg-neutral-900/10 rounded-3xl border border-dashed border-neutral-850">
                    <p class="text-sm font-semibold text-neutral-400 font-sans">No releases found in database.</p>
                </div>
            `;
            const pagContainer = document.getElementById('release-cards-pagination');
            if (pagContainer) pagContainer.innerHTML = '';
            return;
        }

        // Apply Search details & dynamic case-insensitive/space-insensitive category taxonomy checks
        const filtered = SCHEDULE_ITEMS.filter(item => {
            const q = searchQuery.toLowerCase();
            const matchesSearch = (item.title ? item.title.toLowerCase().includes(q) : false) || 
                                  (item.genre ? item.genre.some(g => g.toLowerCase().includes(q)) : false) ||
                                  (item.directorOrCreator ? item.directorOrCreator.toLowerCase().includes(q) : false) ||
                                  (item.cast ? item.cast.some(c => c.toLowerCase().includes(q)) : false) ||
                                  (item.description ? item.description.toLowerCase().includes(q) : false) ||
                                  (item.tagline ? item.tagline.toLowerCase().includes(q) : false);
            
            let matchesType = false;
            if (activeFilterType === 'ALL') {
                matchesType = true;
            } else if (activeFilterType === 'FAVORITES') {
                matchesType = favoritedIds.includes(item.id);
            } else if (activeFilterType === 'Movie') {
                // Matches only your movie post type
                matchesType = (item.post_type === 'ht_movie');
            } else if (activeFilterType === 'TV Series') {
                // Matches both of your TV post types
                matchesType = (item.post_type === 'ht_show' || item.post_type === 'ht_tv_show');
            }
            return matchesSearch && matchesType;
        });

        if (filtered.length === 0) {
            container.innerHTML = `
                <div class="text-center py-16 bg-neutral-900/10 rounded-3xl border border-dashed border-neutral-850">
                    <p class="text-sm font-semibold text-neutral-400">No releases matches filter criteria.</p>
                    <button onclick="resetFilters()" class="mt-3 text-xs bg-red-600/10 border border-red-900/30 text-red-400 px-4 py-2 rounded-xl font-bold cursor-pointer hover:bg-red-600/20 font-mono">
                        RESET COORDS
                    </button>
                </div>
            `;
            const pagContainer = document.getElementById('release-cards-pagination');
            if (pagContainer) pagContainer.innerHTML = '';
            return;
        }

        // Pagination limit calculations
        const totalItems = filtered.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedItems = filtered.slice(startIndex, endIndex);

        paginatedItems.forEach(item => {
            const isFav = favoritedIds.includes(item.id);
            const isScheduled = scheduledIds.includes(item.id);
            const isFocused = currentFocusedItem && currentFocusedItem.id === item.id;

            const card = document.createElement('div');
            card.id = `release-card-${item.id}`;
            card.className = `relative flex flex-col sm:flex-row gap-4 border rounded-2xl p-4 cursor-pointer group transition-all duration-300 ${isFocused ? 'release-card-focused' : 'release-card-normal'}`;
            card.onmouseenter = () => { updateAmbientGlow(item.accentColor); };
            card.onmouseleave = () => { updateAmbientGlow(null); };
            card.onclick = () => {
                setFocusedItem(item);
                openDetailsModal(item.id);
            };

            // Fetch custom thumbnail from post attachments or fall back to gradient graphics
            const imageBackgroundStyle = item.thumb ? `background-image: url('${item.thumb}'); background-size: cover; background-position: center;` : `background: ${item.posterGradient};`;

            card.innerHTML = `
                <!-- Graphic Poster Panel with hover watch trigger -->
                <div class="w-full sm:w-40 md:w-48 aspect-[16/9] sm:aspect-[4/3] rounded-xl overflow-hidden relative p-3 flex flex-col justify-between shrink-0 group/poster cursor-pointer" style="${imageBackgroundStyle}" onclick="event.stopPropagation(); playTrailer('${item.trailerUrl || ''}', '${item.title.replace(/'/g, "\\'")}')" title="Play Trailer">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover/poster:opacity-100 flex flex-col gap-1 items-center justify-center transition-all duration-300 z-20 backdrop-blur-[2px]">
                        <div class="w-10 h-10 rounded-full bg-red-650 flex items-center justify-center text-white shadow-xl scale-95 group-hover/poster:scale-100 transition-all duration-300 active:scale-90">
                            <i data-lucide="play" class="w-4 h-4 fill-white text-white ml-0.5 animate-pulse"></i>
                        </div>
                        <span class="text-[8px] font-mono tracking-[0.2em] text-neutral-300 uppercase font-black">PLAY TRAILER</span>
                    </div>

                    <div class="absolute inset-0 bg-black/35 pointer-events-none group-hover/poster:opacity-0 transition-opacity"></div>

                    <div class="flex justify-between items-start z-10 pointer-events-none w-full">
                        <span class="bg-black/85 border border-white/5 px-2 py-0.5 rounded text-[8px] font-mono font-bold text-white uppercase tracking-wider">${item.rating}</span>
                        ${item.has_review_rating ? `
                            <div class="flex items-center gap-0.5 bg-black/75 px-1.5 py-0.5 rounded text-yellow-550 text-[9px] font-bold border border-yellow-500/10 font-mono">
                                <span class="text-yellow-405">★</span>
                                <span class="text-white">${item.review_rating}</span>
                            </div>
                        ` : ''}
                    </div>

                    <!-- Visual Central Text logo fallback if upload image empty -->
                    <div class="text-center font-extrabold text-white text-base tracking-tight select-none z-10 drop-shadow-[0_2px_6px_rgba(0,0,0,0.9)] leading-tight uppercase font-sans ${item.thumb ? 'hidden group-hover/poster:block' : ''}">
                        ${item.title.split(':')[0]}
                    </div>

                    <div class="z-10 flex gap-1 items-center overflow-hidden pointer-events-none group-hover/poster:hidden transition-all duration-300">
                        <span class="text-[8px] truncate px-1.5 py-0.5 rounded-full bg-black/60 text-neutral-200 border border-white/5 font-mono">${item.genre[0]} • ${item.duration}</span>
                    </div>
                </div>

                <!-- Core Details specs -->
                <div class="flex-1 min-w-0 flex flex-col justify-center gap-2">
                    <div>
                        <div class="card-meta-row gap-x-1.5 font-mono">
                            <span class="meta-badge uppercase font-black rounded-full border tracking-widest select-none cursor-pointer pointer-events-auto whitespace-nowrap ${
                                item.type === 'Movie' ? 'movie-badge-glow' : 'tv-badge-glow'
                            }" onclick="event.stopPropagation(); setFilterType('${item.type}')">
                                ${item.type}
                            </span>
                            <span class="text-neutral-700 font-mono">•</span>
                            <span class="text-neutral-450 font-medium font-mono whitespace-nowrap">
                                <span class="genre-short">${item.genre[0]}</span>
                                <span class="genre-full">${item.genre.slice(0, 2).join(' / ')}</span>
                            </span>
                            <span class="text-neutral-700 font-mono">•</span>

                            <!-- Dynamic Review Rating Badge Row -->
                            ${item.has_review_rating ? `
                                <span class="flex items-center gap-0.5 text-emerald-405 font-black font-mono leading-none whitespace-nowrap" title="${item.review_count} user reviews">
                                    <i data-lucide="star" class="w-2.5 h-2.5 fill-emerald-400 text-emerald-400 shrink-0"></i>
                                    <span class="whitespace-nowrap">
                                        ${item.review_rating}/10 
                                        <span class="reviews-short">(${item.review_count})</span>
                                        <span class="reviews-full">(${item.review_count} ${item.review_count === 1 ? 'review' : 'reviews'})</span>
                                    </span>
                                </span>
                            ` : `
                                <span class="flex items-center gap-0.5 text-neutral-500 font-mono leading-none whitespace-nowrap">
                                    <i data-lucide="star-off" class="w-2.5 h-2.5 text-neutral-600 shrink-0"></i>
                                    <span class="tracking-wide">No reviews</span>
                                </span>
                            `}
                        </div>
                        <h3 class="text-lg font-black text-white tracking-wide mt-1 uppercase font-sans leading-snug group-hover:text-red-450 transition-colors">
                            <a href="${item.permalink || '#'}" target="_blank" onclick="event.stopPropagation(); if (this.getAttribute('href') && this.getAttribute('href') !== '#') { window.open(this.getAttribute('href'), '_blank'); } return false;" class="hover:underline hover:text-red-500 transition-colors">${item.title}</a>
                        </h3>
                    </div>
                    <div class="text-neutral-400 text-xs line-clamp-2 leading-relaxed">"${item.tagline || item.description}"</div>
                </div>

                <!-- Right schedule control block -->
                <div class="w-full sm:w-auto sm:min-w-[130px] border-t sm:border-t-0 sm:border-l border-neutral-900 pt-3 sm:pt-0 sm:pl-4 flex flex-row sm:flex-col justify-between items-center sm:items-end gap-3 shrink-0">
                    <div class="flex items-center gap-3 text-right select-none font-mono">
                        <div class="flex flex-col items-end">
                            <span class="text-2.5xl sm:text-3.5xl font-extrabold text-white leading-none font-mono">${item.day}</span>
                            <span class="text-[11px] font-extrabold text-red-500 uppercase tracking-widest leading-none mt-1 font-mono">${item.month}</span>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-red-950/20 border border-red-955/45 flex items-center justify-center text-red-500">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5 flex-wrap xs:flex-nowrap justify-end" onclick="event.stopPropagation()">
                        <button onclick="toggleScheduleItem('${item.id}')" class="px-2.5 sm:px-3 py-2 text-[10px] font-black rounded-lg border flex items-center gap-1 transition-all cursor-pointer ${isScheduled ? 'bg-emerald-950/30 border-emerald-500/30 text-emerald-300' : 'bg-neutral-950/85 border-neutral-805 text-neutral-300 hover:border-neutral-700 hover:bg-neutral-900'}">
                            ${isScheduled 
                                ? `<i data-lucide="check" class="w-3 h-3 text-emerald-400 shrink-0"></i> <span>ADDED</span>` 
                                : `<i data-lucide="plus" class="w-3 h-3 shrink-0"></i> <span>CALENDAR</span>`
                            }
                        </button>

                        <button onclick="toggleFavoriteItem('${item.id}')" class="p-2 rounded-lg border transition-all cursor-pointer ${isFav ? 'bg-red-950/30 border-red-500/35 text-red-500' : 'bg-neutral-950/85 border-neutral-805 text-neutral-450 hover:border-neutral-600 hover:bg-neutral-900'}">
                            <i data-lucide="heart" class="w-3 h-3 ${isFav ? 'fill-red-500 text-red-550' : ''}"></i>
                        </button>

                        <button onclick="shareItem('${item.id}', '${item.title.replace(/'/g, "\\'")}')" class="p-2 bg-neutral-950/85 border border-neutral-805 hover:border-neutral-600 text-neutral-450 hover:text-white rounded-lg transition-all cursor-pointer" title="Share this release">
                            <i data-lucide="share-2" class="w-3 h-3"></i>
                        </button>

                        <!-- Direct watchparty planner trigger button on catalog card -->
                        <button onclick="openFocusedItemWatchparty('${item.id}')" class="p-2 bg-gradient-to-br from-red-950/40 to-red-900/20 border border-red-500/30 hover:border-red-500 text-red-400 hover:text-white rounded-lg transition-all cursor-pointer shadow-[0_0_10px_rgba(239,68,68,0.1)] hover:shadow-[0_0_15px_rgba(239,68,68,0.25)]" title="Plan a Watch Party!">
                            <i data-lucide="users" class="w-3.5 h-3.5 animate-pulse"></i>
                        </button>

                        <button onclick="openDetailsModal('${item.id}')" class="p-2 bg-neutral-950/85 border border-neutral-850 hover:border-red-905 text-neutral-405 hover:text-red-400 rounded-lg transition-all" title="View details">
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                </div>
            `;

            container.appendChild(card);
        });

        // Dynamic pagination controllers
        renderPagination(totalPages);

        // Sync visual telemetry indicators
        document.getElementById('fav-count-meta').innerText = favoritedIds.length;
        document.getElementById('add-count-meta').innerText = scheduledIds.length;
        
        const favBadge = document.getElementById('favorites-badge');
        if (favBadge) {
            favBadge.innerText = favoritedIds.length;
            if (favoritedIds.length > 0) favBadge.classList.remove('hidden');
            else favBadge.classList.add('hidden');
        }

        const calBadge = document.getElementById('calendar-badge');
        if (calBadge) {
            calBadge.innerText = scheduledIds.length;
            if (scheduledIds.length > 0) calBadge.classList.remove('hidden');
            else calBadge.classList.add('hidden');
        }

        lucide.createIcons();
    }

    // Secondary function to render pagination controls
    function renderPagination(totalPages) {
        const pagContainer = document.getElementById('release-cards-pagination');
        if (!pagContainer) return;
        pagContainer.innerHTML = '';

        if (totalPages <= 1) return;

        // Custom pagination card wrap
        const wrap = document.createElement('div');
        wrap.className = 'flex items-center justify-center gap-2 w-full pt-4 border-t border-neutral-900 mt-2';

        // Prev Button
        const prevBtn = document.createElement('button');
        prevBtn.className = `px-3.5 py-2 rounded-xl border border-neutral-850 text-[11px] font-bold font-mono tracking-wider transition-all bg-neutral-950/50 text-neutral-400 hover:text-white disabled:opacity-20 disabled:pointer-events-none cursor-pointer flex items-center gap-1`;
        prevBtn.disabled = currentPage === 1;
        prevBtn.innerHTML = `<i data-lucide="chevron-left" class="w-3.5 h-3.5"></i> PREV`;
        prevBtn.onclick = () => {
            if (currentPage > 1) {
                currentPage--;
                renderReleaseCards();
                scrollToReleaseHeadline();
            }
        };
        wrap.appendChild(prevBtn);

        // Page Number buttons render with smart sliding window and ellipses
        const pageNumbers = [];
        if (totalPages <= 7) {
            for (let i = 1; i <= totalPages; i++) {
                pageNumbers.push(i);
            }
        } else {
            // Smart sliding window of size 5 centered on currentPage
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, currentPage + 2);

            // Keep the window size strictly 5 if possible
            if (currentPage <= 3) {
                startPage = 1;
                endPage = 5;
            } else if (currentPage >= totalPages - 2) {
                startPage = totalPages - 4;
                endPage = totalPages;
            }

            // Left padding starting page elements with possible ellipsis
            if (startPage > 1) {
                pageNumbers.push(1);
                if (startPage > 3) {
                    pageNumbers.push(2);
                    pageNumbers.push('...');
                } else if (startPage === 3) {
                    pageNumbers.push(2);
                }
            }

            // Central page block
            for (let i = startPage; i <= endPage; i++) {
                if (!pageNumbers.includes(i)) {
                    pageNumbers.push(i);
                }
            }

            // Right padding ending page elements with possible ellipsis
            if (endPage < totalPages) {
                if (endPage < totalPages - 2) {
                    pageNumbers.push('...');
                    pageNumbers.push(totalPages - 1);
                    pageNumbers.push(totalPages);
                } else if (endPage === totalPages - 2) {
                    pageNumbers.push(totalPages - 1);
                    pageNumbers.push(totalPages);
                } else if (endPage === totalPages - 1) {
                    pageNumbers.push(totalPages);
                }
            }
        }

        // Generate DOM elements for pages
        pageNumbers.forEach(p => {
            if (p === '...') {
                const dotSpan = document.createElement('span');
                dotSpan.className = 'w-9 h-9 text-[11px] font-mono text-neutral-500 flex items-center justify-center select-none';
                dotSpan.innerText = '...';
                wrap.appendChild(dotSpan);
            } else {
                const pageBtn = document.createElement('button');
                const isActive = currentPage === p;
                pageBtn.className = `w-9 h-9 rounded-xl border text-[11px] font-mono font-bold tracking-wider transition-all flex items-center justify-center cursor-pointer ${isActive ? 'bg-red-650/10 border-red-650 text-red-500 shadow-[0_0_15px_rgba(239,68,68,0.15)]' : 'bg-neutral-950/30 border-neutral-900 text-neutral-400 hover:text-white'}`;
                pageBtn.innerText = p;
                pageBtn.onclick = () => {
                    currentPage = p;
                    renderReleaseCards();
                    scrollToReleaseHeadline();
                };
                wrap.appendChild(pageBtn);
            }
        });

        // Next Button
        const nextBtn = document.createElement('button');
        nextBtn.className = `px-3.5 py-2 rounded-xl border border-neutral-850 text-[11px] font-bold font-mono tracking-wider transition-all bg-neutral-950/50 text-neutral-400 hover:text-white disabled:opacity-20 disabled:pointer-events-none cursor-pointer flex items-center gap-1`;
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.innerHTML = `NEXT <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>`;
        nextBtn.onclick = () => {
            if (currentPage < totalPages) {
                currentPage++;
                renderReleaseCards();
                scrollToReleaseHeadline();
            }
        };
        wrap.appendChild(nextBtn);

        pagContainer.appendChild(wrap);
        
        // Compute and sync the visitor's real-time Cinematic Personality spectrum
        if (typeof updateCineSyncProfile === 'function') {
            updateCineSyncProfile();
        }
    }

    function scrollToReleaseHeadline() {
        const dest = document.getElementById('search-input');
        if (dest) dest.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function setFocusedItem(item) {
        currentFocusedItem = item;
        if (item) {
            displayedCalendarMonth = item.monthNum - 1;
            displayedCalendarYear = item.year;
            // Morph the active sound synthesizer seamlessly to match current item's genre!
            if (phonePlaying) {
                CineAudioEngine.start(item.genre);
            }
        }
        renderCalendar();
        renderSmartphone();
        renderReleaseCards();
    }

    function handleSearch(val) {
        searchQuery = val;
        currentPage = 1; // Reset page index
        const clearBtn = document.getElementById('search-clear-btn');
        if (val && clearBtn) {
            clearBtn.classList.remove('hidden');
        } else if (clearBtn) {
            clearBtn.classList.add('hidden');
        }
        renderReleaseCards();
    }

    function clearSearch() {
        const inp = document.getElementById('search-input');
        if (inp) inp.value = '';
        searchQuery = '';
        currentPage = 1; // Reset page index
        const clr = document.getElementById('search-clear-btn');
        if (clr) clr.classList.add('hidden');
        renderReleaseCards();
    }

    function setFilterType(type) {
        // Normalize type robustly to support case-insensitive and prefix variants
        let normalizedType = type;
        if (type && typeof type === 'string') {
            const lower = type.toLowerCase();
            if (lower === 'all') normalizedType = 'ALL';
            else if (lower === 'movie' || lower === 'ht_movie') normalizedType = 'Movie';
            else if (lower === 'tv series' || lower === 'tv show' || lower === 'ht_show' || lower === 'ht_tv_show' || lower.includes('tv') || lower.includes('series') || lower.includes('show')) normalizedType = 'TV Series';
            else if (lower === 'favorites') normalizedType = 'FAVORITES';
        }
        activeFilterType = normalizedType;
        currentPage = 1; // Reset page index
        
        const buttons = {
            'ALL': document.getElementById('filter-all'),
            'Movie': document.getElementById('filter-movie'),
            'TV Series': document.getElementById('filter-tv'),
            'FAVORITES': document.getElementById('favs-btn')
        };

        Object.keys(buttons).forEach(key => {
            const btn = buttons[key];
            if (!btn) return;
            if (key === normalizedType) {
                if (key === 'FAVORITES') {
                    btn.className = 'flex flex-col items-center text-center p-2 rounded-xl bg-red-950/20 border border-red-900/30 text-red-500 transition-all cursor-pointer group relative';
                } else {
                    btn.className = 'px-3 py-1.5 rounded-xl border text-[11px] font-bold tracking-wider transition-all bg-red-600/10 border-red-650 text-red-500 cursor-pointer active-format';
                }
            } else {
                if (key === 'FAVORITES') {
                    btn.className = 'flex flex-col items-center text-center p-2 rounded-xl hover:bg-neutral-850/60 transition-all cursor-pointer group relative';
                } else {
                    btn.className = 'px-3 py-1.5 rounded-xl border text-[11px] font-bold tracking-wider transition-all bg-neutral-950/50 border-neutral-900 text-neutral-400 hover:text-neutral-200 cursor-pointer inactive-format';
                }
            }
        });

        summonToast(`Filtered content list: ${normalizedType === 'ALL' ? 'All Content' : normalizedType === 'FAVORITES' ? 'Your Favorites' : normalizedType + 's'}`);
        renderReleaseCards();
    }

    function resetFilters() {
        searchQuery = '';
        activeFilterType = 'ALL';
        currentPage = 1;
        const inp = document.getElementById('search-input');
        if (inp) inp.value = '';
        const clr = document.getElementById('search-clear-btn');
        if (clr) clr.classList.add('hidden');
        setFilterType('ALL');
    }

    function toggleFavoritesFilter() {
        if (favoritedIds.length === 0) {
            summonToast("No items added to favorites listing yet.");
            return;
        }
        setFilterType('FAVORITES');
    }

    var guestAgreedToFav = localStorage.getItem('insom_guest_agreed') === 'true';
    var guestAgreedToSched = localStorage.getItem('insom_guest_agreed') === 'true';

    function showLoginQueryModal(message) {
        var modalId = 'insom-login-portal-modal';
        var modal = document.getElementById(modalId);
        if (!modal) {
            modal = document.createElement('div');
            modal.id = modalId;
            modal.style.position = 'fixed';
            modal.style.inset = '0';
            modal.style.zIndex = '999999';
            modal.style.display = 'flex';
            modal.style.alignItems = 'center';
            modal.style.justifyContent = 'center';
            modal.style.backgroundColor = 'rgba(0, 0, 0, 0.85)';
            modal.style.backdropFilter = 'blur(12px)';
            modal.style.padding = '16px';
            modal.style.fontFamily = 'monospace';
            
            modal.innerHTML = `
                <div style="background: #0d0e12; border: 1px solid rgba(0, 240, 255, 0.25); box-shadow: 0 0 30px rgba(0,240,255,0.15); border-radius: 16px; padding: 32px 24px; max-width: 440px; width: 100%; text-align: center; position: relative;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(57, 255, 20, 0.1); border: 1px solid #39ff14; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <span style="color: #39ff14; font-size: 20px; font-weight: bold;">🔑</span>
                    </div>
                    <h3 style="color: #fff; font-size: 14px; font-weight: 950; letter-spacing: 0.5px; margin-bottom: 12px; text-transform: uppercase; margin-top:0;">Member Access Required</h3>
                    <p id="insom-login-modal-msg" style="color: #8b92a6; font-size: 11px; line-height: 1.6; margin-bottom: 24px; text-align:center; padding:0 8px;"></p>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <a id="insom-login-btn" href="#" style="background: #39ff14; color: #000; font-weight: 900; font-size: 11px; text-transform: uppercase; padding: 12px; border-radius: 6px; text-decoration: none; display: block; border: 1px solid #39ff14; transition: all 0.2s; box-shadow: 0 0 10px rgba(57,255,20,0.3); text-align:center;">Secure Portal Login</a>
                        <button id="insom-guest-btn" style="background: transparent; color: #8b92a6; border: 1px solid rgba(255,255,255,0.08); font-size: 11px; font-weight: bold; text-transform: uppercase; padding: 12px; border-radius: 6px; cursor: pointer; transition: all 0.2s;">Continue as Guest (Saves Local only)</button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }
        
        document.getElementById('insom-login-modal-msg').innerText = message;
        
        var loginBtn = document.getElementById('insom-login-btn');
        if (loginBtn) {
            loginBtn.href = "<?php echo esc_url( wp_login_url( ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ) ); ?>";
        }

        var guestBtn = document.getElementById('insom-guest-btn');
        if (guestBtn) {
            guestBtn.onclick = function() {
                if (window.bypassLoginGateAndFav) {
                    window.bypassLoginGateAndFav();
                } else if (window.bypassLoginGateAndSched) {
                    window.bypassLoginGateAndSched();
                } else {
                    closeLoginQueryModal();
                }
            };
        }
        
        modal.style.display = 'flex';
    }

    function closeLoginQueryModal() {
        var modal = document.getElementById('insom-login-portal-modal');
        if (modal) {
            modal.style.display = 'none';
        }
        window.bypassLoginGateAndFav = null;
        window.bypassLoginGateAndSched = null;
    }

    // Toggle Favorite Action: Local variables + WordPress ajax synchronization
    function toggleFavoriteItem(id) {
        if (!IS_USER_LOGGED_IN && !guestAgreedToFav) {
            showLoginQueryModal("Wishlist your favorite upcoming movie releases! Log in to save your selection across any device, or click Continue as Guest to only save to this browser.");
            window.bypassLoginGateAndFav = function() {
                closeLoginQueryModal();
                localStorage.setItem('insom_guest_agreed', 'true');
                guestAgreedToFav = true;
                toggleFavoriteItem(id);
            };
            return;
        }

        var idStr = String(id);
        var idx = favoritedIds.indexOf(idStr);
        if (idx === -1) idx = favoritedIds.indexOf(Number(id));

        const itemObj = SCHEDULE_ITEMS.find(f => f.id == id);
        if (!itemObj) return;

        if (idx > -1) {
            favoritedIds.splice(idx, 1);
            summonToast(`Removed "${itemObj.title}" from favorites.`);
        } else {
            favoritedIds.push(idStr);
            summonToast(`Added "${itemObj.title}" to favorites!`);
        }
        
        localStorage.setItem('standalone_fav', JSON.stringify(favoritedIds));
        
        // Dynamic WordPress Cookie/DB Sync using jQuery AJAX
        if (typeof jQuery !== 'undefined' && (itemObj.wp_id || itemObj.id)) {
            jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", {
                action: "insom_v3_toggle_fav",
                post_id: itemObj.wp_id || itemObj.id
            }, function(response) {
                // DB success feedback if needed
            });
        }
        
        renderReleaseCards();
    }

    function toggleScheduleItem(id) {
        if (!IS_USER_LOGGED_IN && !guestAgreedToSched) {
            showLoginQueryModal("Schedule this release to your local countdown planners! Log in to sync it with your online profile, or click Continue as Guest to only save to this browser.");
            window.bypassLoginGateAndSched = function() {
                closeLoginQueryModal();
                localStorage.setItem('insom_guest_agreed', 'true');
                guestAgreedToSched = true;
                toggleScheduleItem(id);
            };
            return;
        }

        var idStr = String(id);
        var idx = scheduledIds.indexOf(idStr);
        if (idx === -1) idx = scheduledIds.indexOf(Number(id));

        const itemObj = SCHEDULE_ITEMS.find(f => f.id == id);
        if (!itemObj) return;

        if (idx > -1) {
            scheduledIds.splice(idx, 1);
            summonToast(`Removed "${itemObj.title}" from personal planner.`);
        } else {
            scheduledIds.push(idStr);
            summonToast(`Scheduled "${itemObj.title}" to your planners!`);
        }
        
        localStorage.setItem('standalone_scheduled', JSON.stringify(scheduledIds));
        
        // Dynamic WordPress Cookie/DB Sync using jQuery AJAX
        if (typeof jQuery !== 'undefined' && (itemObj.wp_id || itemObj.id)) {
            jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", {
                action: "insom_v3_toggle_schedule",
                post_id: itemObj.wp_id || itemObj.id
            }, function(response) {
                // DB success feedback
            });
        }

        renderReleaseCards();
    }

    // 6. Sidebar Planner Drawer Logic
    function toggleCalendarPanel() {
        const drawer = document.getElementById('calendar-drawer');
        const innerBoard = document.getElementById('calendar-drawer-board');
        if (!drawer || !innerBoard) return;

        if (drawer.classList.contains('hidden')) {
            populateDrawerList();
            drawer.classList.remove('hidden');
            setTimeout(() => {
                innerBoard.classList.remove('translate-x-full');
            }, 10);
        } else {
            innerBoard.classList.add('translate-x-full');
            setTimeout(() => {
                drawer.classList.add('hidden');
            }, 300);
        }
    }

    function populateDrawerList() {
        const list = document.getElementById('drawer-items-list');
        const dIndicator = document.getElementById('drawer-total-indicator');
        if (!list || !dIndicator) return;

        list.innerHTML = '';
        const items = SCHEDULE_ITEMS.filter(f => scheduledIds.includes(f.id));
        dIndicator.innerText = items.length;

        if (items.length === 0) {
            list.innerHTML = `
                <div class="text-center py-12 px-4 border border-dashed border-neutral-800 rounded-2xl select-none">
                    <i data-lucide="calendar" class="w-8 h-8 text-neutral-600 mx-auto mb-3"></i>
                    <p class="text-sm font-semibold text-neutral-300">Your Planner is Empty</p>
                    <p class="text-xs text-neutral-500 max-w-xs mx-auto mt-1 font-sans">
                        Click "CALENDAR" on release items cards to schedule premiere tracking!
                    </p>
                </div>
            `;
            const footerEl = document.getElementById('drawer-footer-controls');
            if (footerEl) footerEl.classList.add('opacity-50', 'pointer-events-none');
            lucide.createIcons();
            return;
        }

        const footerEl = document.getElementById('drawer-footer-controls');
        if (footerEl) footerEl.classList.remove('opacity-50', 'pointer-events-none');

        items.forEach(item => {
            const row = document.createElement('div');
            row.className = 'flex items-center gap-3 bg-neutral-900/60 border border-neutral-800 p-3.5 rounded-xl hover:border-neutral-700 transition duration-205';
            row.innerHTML = `
                <div class="w-1.5 h-10 rounded-full shrink-0" style="background: ${item.posterGradient}"></div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5 font-mono">
                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-red-950/40 border border-red-900/20 text-red-400">${item.type}</span>
                        <span class="text-[10px] text-neutral-500 font-semibold">${item.displayDate}</span>
                    </div>
                    <h4 class="text-sm font-bold text-white truncate mt-1 uppercase font-sans">
                        <a href="${item.permalink || '#'}" target="_blank" onclick="event.stopPropagation(); if (this.getAttribute('href') && this.getAttribute('href') !== '#') { window.open(this.getAttribute('href'), '_blank'); } return false;" class="hover:underline hover:text-red-400 transition-colors">${item.title}</a>
                    </h4>
                </div>
                <button onclick="downloadICSById('${item.id}')" class="p-1 px-1.5 text-neutral-500 hover:text-white rounded-lg transition shrink-0 cursor-pointer" title="Download ICS Calendar File">
                    <i data-lucide="download" class="w-4 h-4"></i>
                </button>
                <button onclick="removeCalendarItem('${item.id}')" class="p-1 px-1.5 text-neutral-500 hover:text-red-400 rounded-lg transition shrink-0 cursor-pointer" title="Delete from Planner">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            `;
            list.appendChild(row);
        });

        lucide.createIcons();
    }

    function removeCalendarItem(id) {
        const idx = scheduledIds.indexOf(id);
        if (idx > -1) {
            scheduledIds.splice(idx, 1);
            localStorage.setItem('standalone_scheduled', JSON.stringify(scheduledIds));
            populateDrawerList();
            renderReleaseCards();
            summonToast("Item removed.");
        }
    }

    function clearAllCalendarItems() {
        if (confirm("Are you sure you want to clear your planned timeline coordinates?")) {
            scheduledIds = [];
            localStorage.setItem('standalone_scheduled', JSON.stringify(scheduledIds));
            populateDrawerList();
            renderReleaseCards();
            summonToast("System planner cleared.");
        }
    }

    function triggerSynchronize() {
        const btnText = document.getElementById('sync-btn-text');
        if (!btnText) return;
        btnText.innerText = "SYNCHRONIZING ENGINE...";
        
        // Loop and build Google Calendar rendered URLs for immediate user redirection template checks
        const activeScheduled = SCHEDULE_ITEMS.filter(f => scheduledIds.includes(f.id));
        if (activeScheduled.length > 0) {
            setTimeout(() => {
                btnText.innerText = "CHANNELS BROADCASTED!";
                summonToast("Successfully generated planner subscription nodes.");
                
                // Let's redirect them to the first scheduled item's template link to be incredibly helpful!
                const firstSchedule = activeScheduled[0];
                const calDate = firstSchedule.originalReleaseDate.replace(/-/g, '');
                const desc = `Type: ${firstSchedule.type} | Release: ${firstSchedule.displayDate} | Synopsis: ${firstSchedule.tagline || firstSchedule.description}`;
                const calUrl = `https://www.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(firstSchedule.title)}&dates=${calDate}/${calDate}&details=${encodeURIComponent(desc)}`;
                
                window.open(calUrl, '_blank');
                
                setTimeout(() => {
                    btnText.innerText = "SYNC PLANNERS";
                }, 2000);
            }, 1200);
        } else {
            setTimeout(() => {
                btnText.innerText = "ADD ITEMS FIRST!";
                setTimeout(() => btnText.innerText = "SYNC PLANNERS", 2000);
            }, 800);
        }
    }

    // 7. Subscription Alert Modal Handlers
    function openReminderModal(preselectedId = null) {
        const modal = document.getElementById('reminder-modal');
        const select = document.getElementById('reminder-movie-select');
        if (!modal || !select) return;

        select.innerHTML = '';
        SCHEDULE_ITEMS.forEach(f => {
            const opt = document.createElement('option');
            opt.value = f.id;
            opt.innerText = `${f.title} (${f.displayDate})`;
            if (preselectedId && f.id === preselectedId) {
                opt.selected = true;
            } else if (!preselectedId && currentFocusedItem && f.id === currentFocusedItem.id) {
                opt.selected = true;
            }
            select.appendChild(opt);
        });

        modal.classList.remove('hidden');
        document.body.classList.add('modal-prevent-scroll');
    }

    let lastRegisteredReminderMovie = null;
    let lastRegisteredSubscriberName = '';
    let lastRegisteredSubscriberEmail = '';

    function closeReminderModal() {
        const modal = document.getElementById('reminder-modal');
        if (modal) modal.classList.add('hidden');
        document.body.classList.remove('modal-prevent-scroll');
        document.getElementById('reminder-form-container').classList.remove('hidden');
        document.getElementById('reminder-success-container').classList.add('hidden');
        const rc = document.getElementById('virtual-email-receipt-container');
        if (rc) rc.classList.add('hidden');
    }

    function handleReminderSubmit(e) {
        e.preventDefault();
        
        const movieSelect = document.getElementById('reminder-movie-select');
        const subscriberName = document.getElementById('reminder-name').value;
        const subscriberEmail = document.getElementById('reminder-email').value;
        
        const selectedId = movieSelect ? movieSelect.value : null;
        const selectedMovie = SCHEDULE_ITEMS.find(f => f.id === selectedId);
        
        if (!selectedMovie) {
            summonToast("Please select a valid release.");
            return;
        }

        // Cache coordinates for instant sandbox simulation receipt view
        lastRegisteredReminderMovie = selectedMovie;
        lastRegisteredSubscriberName = subscriberName;
        lastRegisteredSubscriberEmail = subscriberEmail;

        // Display targeting address
        const targetLabel = document.getElementById('success-email-target');
        if (targetLabel) targetLabel.innerText = subscriberEmail;

        document.getElementById('reminder-form-container').classList.add('hidden');
        document.getElementById('reminder-success-container').classList.remove('hidden');

        registeredRemindersCount++;
        localStorage.setItem('standalone_reminder_count', registeredRemindersCount);
        
        const pip = document.getElementById('reminder-pip');
        if (pip) pip.classList.remove('hidden');

        summonToast("Awaiting secure confirmation dispatch...");

        // Fire AJAX request to server-side WordPress mailer
        if (typeof jQuery !== 'undefined' && selectedMovie.title) {
            jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", {
                action: "insom_v3_send_reminder",
                email: subscriberEmail,
                name: subscriberName,
                movie_title: selectedMovie.title,
                movie_type: selectedMovie.type,
                movie_date: selectedMovie.originalReleaseDate || selectedMovie.displayDate,
                movie_duration: selectedMovie.duration || '',
                movie_genre: selectedMovie.genre ? selectedMovie.genre.join(', ') : '',
                movie_rating: selectedMovie.rating || '',
                movie_score: selectedMovie.score || '',
                movie_director: selectedMovie.directorOrCreator || '',
                movie_cast: selectedMovie.cast ? selectedMovie.cast.join(', ') : '',
                movie_tagline: selectedMovie.tagline || '',
                movie_description: selectedMovie.description || '',
                movie_gradient: selectedMovie.posterGradient || '',
                movie_accent: selectedMovie.accentColor || ''
            }, function(response) {
                if (response.success && response.data && response.data.mail_sent) {
                    summonToast("Styled confirmation transmission dispatched!");
                } else {
                    summonToast("Broadcast alert stored! Local routing fallback active.");
                }
            }).fail(function() {
                summonToast("Secure dispatch channel registered.");
            });
        }
    }



    // 8. Detailed Specs Backdrop Modals
    function openDetailsModal(id) {
        const modal = document.getElementById('details-modal');
        const item = SCHEDULE_ITEMS.find(f => f.id === id);
        if (!modal || !item) return;

        // Diagnostic support logging deleted/commented out to clean console log footprint
        // console.log(item.cast);

        const bannerEl = document.getElementById('modal-banner');
        if (item.thumb) {
            bannerEl.style.background = `url('${item.thumb}') center / cover no-repeat`;
        } else {
            bannerEl.style.background = item.posterGradient;
        }
        const bannerTypeEl = document.getElementById('modal-banner-type');
        if (bannerTypeEl) {
            bannerTypeEl.innerText = item.type;
            if (item.type === 'Movie') {
                bannerTypeEl.className = 'inline-block px-2.5 py-1 text-[9.5px] leading-none font-black rounded-full font-mono tracking-widest uppercase cursor-pointer select-none border movie-badge-glow';
            } else {
                bannerTypeEl.className = 'inline-block px-2.5 py-1 text-[9.5px] leading-none font-black rounded-full font-mono tracking-widest uppercase cursor-pointer select-none border tv-badge-glow';
            }
            bannerTypeEl.onclick = (e) => {
                e.stopPropagation();
                closeDetailsModal();
                setFilterType(item.type);
            };
        }
        const bannerReviewEl = document.getElementById('modal-banner-review');
        if (bannerReviewEl) {
            if (item.has_review_rating) {
                bannerReviewEl.innerHTML = `
                    <span class="flex items-center gap-1 text-emerald-400 font-extrabold bg-emerald-950/20 px-2 py-0.5 rounded-full border border-emerald-500/15 text-[9.5px] font-mono leading-none" title="${item.review_count} user reviews">
                        <i data-lucide="star" class="w-2.5 h-2.5 fill-emerald-400 text-emerald-400"></i>
                        <span>${item.review_rating}/10 (${item.review_count} ${item.review_count === 1 ? 'review' : 'reviews'})</span>
                    </span>
                `;
            } else {
                bannerReviewEl.innerHTML = `
                    <span class="flex items-center gap-1 text-neutral-450 bg-neutral-900/40 px-2 py-0.5 rounded-full border border-neutral-800 text-[9px] font-mono leading-none">
                        <i data-lucide="star-off" class="w-2.5 h-2.5 text-neutral-500"></i>
                        <span class="tracking-wide">No user reviews</span>
                    </span>
                `;
            }
        }
        const bannerTitleEl = document.getElementById('modal-banner-title');
        if (bannerTitleEl) {
            bannerTitleEl.innerText = item.title;
            bannerTitleEl.href = item.permalink || '#';
            bannerTitleEl.target = '_blank';
            bannerTitleEl.onclick = (e) => {
                e.preventDefault();
                e.stopPropagation();
                if (item.permalink && item.permalink !== '#') {
                    window.open(item.permalink, '_blank');
                }
                return false;
            };
        }
        document.getElementById('modal-banner-tagline').innerText = item.tagline ? `"${item.tagline}"` : `Premiere broadcast scheduled`;

        const poster = document.getElementById('modal-preview-poster');
        if (item.thumb) {
            poster.style.background = `url('${item.thumb}') center / cover no-repeat`;
        } else {
            poster.style.background = item.posterGradient;
        }
        document.getElementById('modal-poster-rating').innerText = item.rating;
        const modalPosterScoreNode = document.getElementById('modal-poster-score');
        if (modalPosterScoreNode) {
            const modalPosterScoreWrapper = modalPosterScoreNode.parentElement;
            if (item.has_review_rating) {
                modalPosterScoreNode.innerText = item.review_rating;
                modalPosterScoreWrapper.classList.remove('hidden');
            } else {
                modalPosterScoreWrapper.classList.add('hidden');
            }
        }
        document.getElementById('modal-poster-text-logo').innerText = item.title.split(':')[0];
        document.getElementById('modal-poster-releasedate').innerText = `${item.displayDate}, ${item.year}`;

        const dateInput = document.getElementById('wp-date');
        if (dateInput) {
            dateInput.value = item.originalReleaseDate;
        }
        const wpSec = document.getElementById('modal-details-watchparty');
        const btnText = document.getElementById('wp-toggle-text');
        const btn = document.getElementById('wp-toggle-btn');
        if (wpSec && btnText && btn) {
            wpSec.classList.remove('hidden');
            btnText.innerText = "COLLAPSE";
            btn.className = "flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold font-mono tracking-wider text-white bg-red-650 border border-red-400 rounded-lg hover:bg-neutral-800 transition-all duration-300 cursor-pointer shadow-[0_0_15px_rgba(239,68,68,0.4)]";
        }

        document.getElementById('modal-details-plot').innerHTML = item.description;

        const chips = document.getElementById('modal-details-chips');
        chips.innerHTML = `
            <div class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-neutral-900 border border-neutral-800 text-xs text-neutral-400 font-mono">
                <i data-lucide="clock" class="w-3.5 h-3.5 text-red-500"></i>
                <span>${item.duration}</span>
            </div>
        `;
        item.genre.forEach(g => {
            chips.innerHTML += `<span class="px-2.5 py-1 rounded-full bg-red-950/15 border border-red-900/30 text-xs text-red-400 select-none font-sans">${g}</span>`;
        });

        if (item.has_review_rating) {
            chips.innerHTML += `
                <div class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-990/20 border border-emerald-500/25 text-xs text-emerald-400 font-mono" title="${item.review_count} user reviews">
                    <i data-lucide="star" class="w-3.5 h-3.5 fill-emerald-400 text-emerald-400"></i>
                    <span>Review: ${item.review_rating}/10 (${item.review_count} ${item.review_count === 1 ? 'review' : 'reviews'})</span>
                </div>
            `;
        } else {
            chips.innerHTML += `
                <div class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-neutral-950/45 border border-neutral-850 text-xs text-neutral-500 font-mono">
                    <i data-lucide="star-off" class="w-3.5 h-3.5 text-neutral-600"></i>
                    <span>No user reviews</span>
                </div>
            `;
        }

        document.getElementById('modal-details-director-label').innerHTML = `
            <i data-lucide="flame" class="w-3.5 h-3.5 text-red-500"></i>
            <span>${item.type === 'Movie' ? 'DIRECTOR' : 'CREATOR & SHOWRUNNER'}</span>
        `;
        document.getElementById('modal-details-director-val').innerText = item.directorOrCreator;
        
        // Display cast as clickable links or simple text chips
        const castContainer = document.getElementById('modal-details-star-val');
        if (castContainer) {
            castContainer.innerHTML = ''; // Clear previous
            if (item.cast && item.cast.length > 0 && item.cast[0] !== 'Cast Members') {
                item.cast.forEach((actor) => {
                    const actorBtn = document.createElement('button');
                    actorBtn.className = 'inline-block bg-neutral-900 hover:bg-neutral-850 text-neutral-300 text-[10px] px-2 py-1 rounded mr-1 mb-1 font-mono transition-colors duration-200 cursor-pointer border border-neutral-850 hover:border-red-500/30';
                    actorBtn.innerText = actor.trim();
                    actorBtn.onclick = (e) => {
                        e.stopPropagation();
                        closeDetailsModal();
                        const inp = document.getElementById('search-input');
                        if (inp) {
                            inp.value = actor.trim();
                            handleSearch(actor.trim());
                            scrollToReleaseHeadline();
                        }
                    };
                    castContainer.appendChild(actorBtn);
                });
            } else {
                castContainer.innerText = 'No cast information available.';
            }
        }

        const tBlock = document.getElementById('modal-details-trivia-block');
        const tList = document.getElementById('modal-details-trivia-list');
        tList.innerHTML = '';
        if (item.trivia && item.trivia.length > 0) {
            tBlock.classList.remove('hidden');
            item.trivia.forEach((tr, index) => {
                tList.innerHTML += `
                    <div class="relative overflow-hidden group/trivia bg-neutral-900/40 p-3.5 border border-neutral-800 rounded-xl cursor-pointer hover:border-red-500/40 hover:bg-neutral-900/60 transition duration-300 mt-2" onclick="revealTrivia(this)">
                        <div class="trivia-mask absolute inset-0 bg-neutral-950/95 backdrop-blur-sm flex items-center justify-center gap-2 transition-all duration-500 font-mono text-[9px] tracking-widest text-neutral-400 select-none z-10 border border-neutral-850">
                            <i data-lucide="lock" class="w-3.5 h-3.5 text-red-500/70 animate-pulse"></i>
                            <span class="font-extrabold text-neutral-300 group-hover/trivia:text-red-400">DECRYPT SPECIFICATION [${index + 1}]</span>
                        </div>
                        <p class="text-xs text-neutral-200 leading-relaxed font-sans select-text">${tr}</p>
                    </div>
                `;
            });
        } else {
            tBlock.classList.add('hidden');
        }

        const isFav = favoritedIds.includes(item.id);
        const isSch = scheduledIds.includes(item.id);
        const actionbar = document.getElementById('modal-actions-bar');
        
        // Dynamically extract the movie's unique accent color (or default to Insomniac Red)
        const accentColor = item.accentColor || '#ef4444';
        
        // Add single post link out button if they want to read full WP post specs (stacked cleanly)
        const wpLinkButton = item.permalink && item.permalink !== '#' ? `
            <a href="${item.permalink}" target="_blank" onclick="event.stopPropagation(); window.open(this.href, '_blank'); return false;" class="flex-1 flex items-center justify-center gap-1.5 h-10 rounded-xl border border-neutral-800 text-neutral-350 hover:bg-neutral-900 hover:text-white transition cursor-pointer text-xs transition-all duration-300 active:scale-95" title="View Full Article">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                <span class="font-mono text-[9px] tracking-wider uppercase font-semibold">LINK</span>
            </a>
        ` : '';

        // Stack play-trailer elegantly to avoid text wrapping or squashing on small viewports
        actionbar.className = "flex flex-col gap-2 w-full mt-2 order-1 md:order-2";
        actionbar.innerHTML = `
            <button onclick="playTrailer('${item.trailerUrl || ''}', '${item.title.replace(/'/g, "\\'")}')" 
                    style="background-color: ${accentColor}; border-color: ${accentColor}40; box-shadow: 0 4px 16px ${accentColor}35;" 
                    class="w-full flex items-center justify-center gap-2 h-11 hover:brightness-110 active:scale-98 text-white rounded-xl text-xs font-black tracking-widest transition-all cursor-pointer select-none font-sans uppercase border transition-all duration-300">
                <i data-lucide="play" class="w-4 h-4 fill-white text-white"></i>
                <span class="font-mono">PLAY TRAILER</span>
            </button>
            <div class="flex gap-2 w-full">
                <button onclick="toggleScheduleItem('${item.id}'); openDetailsModal('${item.id}');" class="flex-1 flex items-center justify-center gap-1.5 h-10 rounded-xl border text-xs font-bold transition-all cursor-pointer transition-all duration-300 active:scale-95 ${isSch ? 'bg-emerald-600/20 border-emerald-500/50 text-emerald-300' : 'bg-neutral-900 border-neutral-800 text-neutral-200 hover:border-neutral-700 hover:bg-neutral-845'}" title="Add to Planner">
                    <i data-lucide="calendar" class="w-4 h-4 ${isSch ? 'fill-emerald-400' : ''}"></i>
                    <span class="sm:hidden font-mono text-[9px] tracking-wider uppercase">${isSch ? 'ADDED' : 'PLAN'}</span>
                </button>
                <button onclick="downloadICSById('${item.id}')" class="flex-1 flex items-center justify-center gap-1.5 h-10 rounded-xl border bg-neutral-900 border-neutral-800 text-neutral-300 hover:border-neutral-700 hover:text-white hover:bg-neutral-800 transition cursor-pointer text-xs font-bold transition-all duration-300 active:scale-95" title="Download ICS Calendar File">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span class="sm:hidden font-mono text-[9px] tracking-wider uppercase">ICS</span>
                </button>
                <button onclick="toggleFavoriteItem('${item.id}'); openDetailsModal('${item.id}');" class="flex-1 flex items-center justify-center gap-1.5 h-10 rounded-xl border transition-all cursor-pointer transition-all duration-300 active:scale-95 ${isFav ? 'bg-red-650/20 border-red-500/50 text-red-450 font-bold' : 'bg-neutral-900 border-neutral-810 text-neutral-450 hover:text-white hover:bg-neutral-850'}" title="Favorite">
                    <i data-lucide="heart" class="w-4 h-4 ${isFav ? 'fill-red-500 text-red-550' : ''}"></i>
                    <span class="sm:hidden font-mono text-[9px] tracking-wider uppercase">FAV</span>
                </button>
                <button onclick="shareItem('${item.id}', '${item.title.replace(/'/g, "\\'")}')" class="flex-1 flex items-center justify-center gap-1.5 h-10 rounded-xl border bg-neutral-900 border-neutral-810 text-neutral-450 hover:text-white hover:border-neutral-700 hover:bg-neutral-850 transition cursor-pointer text-xs font-bold transition-all duration-300 active:scale-95" title="Share this release">
                    <i data-lucide="share-2" class="w-4 h-4"></i>
                    <span class="sm:hidden font-mono text-[9px] tracking-wider uppercase">SHARE</span>
                </button>
                ${wpLinkButton}
            </div>
        `;

        modal.classList.remove('hidden');
        document.body.classList.add('modal-prevent-scroll');
        lucide.createIcons();
    }

    function closeDetailsModal() {
        const modal = document.getElementById('details-modal');
        if (modal) modal.classList.add('hidden');
        document.body.classList.remove('modal-prevent-scroll');
    }

    window.shareItem = function(id, title) {
        const urlObj = new URL(window.location.href);
        urlObj.searchParams.set('release', id);
        const shareUrl = urlObj.toString();

        const shareData = {
            title: `Check out ${title} on Insomniacs Cinematic Release Schedule`,
            text: `Stay coordinated for the premiere of ${title}!`,
            url: shareUrl
        };

        if (navigator.share) {
            navigator.share(shareData)
                .catch((error) => console.log('Error sharing:', error));
        } else {
            // Fallback: Copy to clipboard
            navigator.clipboard.writeText(shareUrl)
                .then(() => {
                    summonToast(`Link for "${title}" successfully copied to clipboard!`);
                })
                .catch(() => {
                    summonToast("Failed to copy link. Please retrieve it from address bar.");
                });
        }
    }

    function openEmptyDateModal(dateStr) {
        const modal = document.getElementById('empty-date-modal');
        const msg = document.getElementById('empty-date-message');
        if (modal && msg) {
            msg.innerText = `No movie or TV show releases scheduled for ${dateStr}.`;
            modal.classList.remove('hidden');
            document.body.classList.add('modal-prevent-scroll');
            lucide.createIcons();
        }
    }

    function closeEmptyDateModal() {
        const modal = document.getElementById('empty-date-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('modal-prevent-scroll');
        }
    }

    function playTrailer(url, title) {
        if (!url) {
            summonToast("Trailer preview is currently not available for this release.");
            return;
        }
        const container = document.getElementById('trailer-video-container');
        if (!container) return;
        
        let embedUrl = getTrailerEmbedUrl(url);
        container.innerHTML = `<iframe class="w-full h-full rounded-2xl" src="${embedUrl}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>`;
        
        const trailerModal = document.getElementById('trailer-modal');
        if (trailerModal) trailerModal.classList.remove('hidden');
        document.body.classList.add('modal-prevent-scroll');
        
        summonToast(`Channeling cinematic stream channel for "${title}"...`);
    }

    function closeTrailerModal() {
        const trailerModal = document.getElementById('trailer-modal');
        if (trailerModal) trailerModal.classList.add('hidden');
        
        const container = document.getElementById('trailer-video-container');
        if (container) container.innerHTML = '';
        
        const detailsModal = document.getElementById('details-modal');
        if (detailsModal && detailsModal.classList.contains('hidden')) {
            document.body.classList.remove('modal-prevent-scroll');
        }
    }

    function getTrailerEmbedUrl(url) {
        if (!url) return '';
        let regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        let match = url.match(regExp);
        if (match && match[2].length == 11) {
            return 'https://www.youtube.com/embed/' + match[2] + '?autoplay=1&rel=0';
        }
        let vimeoReg = /vimeo\.com\/(?:video\/)?([0-9]+)/;
        let vimeoMatch = url.match(vimeoReg);
        if (vimeoMatch && vimeoMatch[1]) {
            return 'https://player.vimeo.com/video/' + vimeoMatch[1] + '?autoplay=1';
        }
        return url;
    }

    // 9. Status Notifying Toast Controllers
    function summonToast(message) {
        const toast = document.getElementById('system-toast');
        const text = document.getElementById('toast-text-msg');
        if (!toast || !text) return;
        
        text.innerText = message;
        toast.classList.remove('hidden');
        toast.className = toast.className.replace(' toast-anim', '') + ' toast-anim';

        if (toastTimeout) clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
            dismissToast();
        }, 4000);
    }

    function dismissToast() {
        const toast = document.getElementById('system-toast');
        if (toast) toast.classList.add('hidden');
    }

    function scrollToCalendar() {
        summonToast("Retrieving Desk Calendar coordinate alignments...");
        const dest = document.getElementById('desk-calendar-container');
        if (dest) dest.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // ==========================================
    // INJECTED PRESET MODERN INTERACTIVE FEATURES
    // ==========================================

    // Feature 5: Cine-Sync Intelligence Profile System
    function updateCineSyncProfile() {
        const scheduledItems = SCHEDULE_ITEMS.filter(item => scheduledIds.includes(item.id));
        const totalScheduled = scheduledItems.length;

        // Baseline analysis is calculated on the user's scheduled list,
        // or falls back to ALL items if they haven't scheduled any yet. This ensures
        // the client interface never shows "0% empty values", but instead populates
        // a beautiful quantum analysis of the entire library.
        const analysisItems = totalScheduled > 0 ? scheduledItems : SCHEDULE_ITEMS;

        let adrenalineCount = 0;
        let wormholeCount = 0;
        let harmonyCount = 0;
        let dramaCount = 0;

        analysisItems.forEach(item => {
            const genres = (item.genre || []).map(g => g.toLowerCase());
            let matched = false;
            
            if (genres.some(g => g.includes('action') || g.includes('adventure') || g.includes('thriller') || g.includes('crime') || g.includes('war') || g.includes('fantasy'))) {
                adrenalineCount++;
                matched = true;
            }
            if (genres.some(g => g.includes('sci-fi') || g.includes('mystery') || g.includes('space') || g.includes('horror'))) {
                wormholeCount++;
                matched = true;
            }
            if (genres.some(g => g.includes('animation') || g.includes('comedy') || g.includes('family') || g.includes('children'))) {
                harmonyCount++;
                matched = true;
            }
            if (genres.some(g => g.includes('drama') || g.includes('romance') || g.includes('biography') || g.includes('history') || g.includes('documentary')) || !matched) {
                dramaCount++;
            }
        });

        const totalScores = adrenalineCount + wormholeCount + harmonyCount + dramaCount || 1;
        const adrenalinePct = Math.round((adrenalineCount / totalScores) * 100);
        const wormholePct = Math.round((wormholeCount / totalScores) * 100);
        const harmonyPct = Math.round((harmonyCount / totalScores) * 100);
        const dramaPct = Math.round((dramaCount / totalScores) * 100);

        const fillAdrenaline = document.getElementById('cinesync-bar-fill-adrenaline');
        const valAdrenaline = document.getElementById('cinesync-bar-val-adrenaline');
        if (fillAdrenaline && valAdrenaline) {
            fillAdrenaline.style.width = `${adrenalinePct}%`;
            valAdrenaline.innerText = `${adrenalinePct}%`;
        }

        const fillWormhole = document.getElementById('cinesync-bar-fill-wormhole');
        const valWormhole = document.getElementById('cinesync-bar-val-wormhole');
        if (fillWormhole && valWormhole) {
            fillWormhole.style.width = `${wormholePct}%`;
            valWormhole.innerText = `${wormholePct}%`;
        }

        const fillHarmony = document.getElementById('cinesync-bar-fill-harmony');
        const valHarmony = document.getElementById('cinesync-bar-val-harmony');
        if (fillHarmony && valHarmony) {
            fillHarmony.style.width = `${harmonyPct}%`;
            valHarmony.innerText = `${harmonyPct}%`;
        }

        const fillDrama = document.getElementById('cinesync-bar-fill-drama');
        const valDrama = document.getElementById('cinesync-bar-val-drama');
        if (fillDrama && valDrama) {
            fillDrama.style.width = `${dramaPct}%`;
            valDrama.innerText = `${dramaPct}%`;
        }

        const isScoutUnlocked = totalScheduled >= 1;
        const isAdrenalineUnlocked = totalScheduled > 0 && adrenalineCount >= 2;
        const isVoyagerUnlocked = totalScheduled > 0 && wormholeCount >= 1;
        const isDreamweaverUnlocked = totalScheduled > 0 && harmonyCount >= 1;
        const isCommanderUnlocked = totalScheduled >= 3;

        const updateMedalState = (id, isUnlocked, activeClass, inactiveClass) => {
            const el = document.getElementById(id);
            if (!el) return;
            const iconEl = el.querySelector('i');
            const dotEl = document.getElementById(`${id}-dot`);

            if (isUnlocked) {
                el.className = `relative group/medal flex flex-col items-center justify-center p-2.5 rounded-xl border transition-all duration-500 hover:scale-110 cursor-help ${activeClass}`;
                if (iconEl) {
                    iconEl.classList.remove('text-neutral-600');
                    iconEl.classList.add('animate-pulse');
                }
                if (dotEl) dotEl.classList.remove('hidden');
            } else {
                el.className = `relative group/medal flex flex-col items-center justify-center p-2.5 rounded-xl bg-neutral-900/45 border border-dashed border-neutral-850 hover:border-neutral-800 transition-all duration-300 cursor-help ${inactiveClass}`;
                if (iconEl) {
                    iconEl.classList.add('text-neutral-600');
                    iconEl.classList.remove('animate-pulse');
                }
                if (dotEl) dotEl.classList.add('hidden');
            }
        };

        updateMedalState('medal-scout', isScoutUnlocked, 'bg-cyan-950/20 border-cyan-500/40 text-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]', 'text-neutral-500');
        updateMedalState('medal-adrenaline', isAdrenalineUnlocked, 'bg-red-950/20 border-red-500/40 text-red-500 shadow-[0_0_15px_rgba(239,68,68,0.15)]', 'text-neutral-500');
        updateMedalState('medal-voyager', isVoyagerUnlocked, 'bg-teal-950/20 border-teal-500/40 text-teal-400 shadow-[0_0_15px_rgba(20,184,166,0.15)]', 'text-neutral-500');
        updateMedalState('medal-dreamweaver', isDreamweaverUnlocked, 'bg-amber-950/20 border-amber-500/40 text-amber-500 shadow-[0_0_15px_rgba(245,158,11,0.15)]', 'text-neutral-500');
        updateMedalState('medal-commander', isCommanderUnlocked, 'bg-purple-950/20 border-purple-500/40 text-purple-400 shadow-[0_0_15px_rgba(168,85,247,0.15)]', 'text-neutral-500');

        const msgBox = document.getElementById('cine-sync-message-box');
        if (msgBox) {
            if (totalScheduled === 0) {
                let text = `<strong>Database Baseline Signature:</strong> Analyzing all ${SCHEDULE_ITEMS.length} listed movies and TV shows. `;
                
                if (adrenalinePct > 35) {
                    text += `The overall spectrum is highly focused on the <strong>Adrenaline Action Matrix</strong> (${adrenalinePct}%). `;
                } else if (wormholePct > 35) {
                    text += `The overall spectrum is aligned with the <strong>Stellar Cosmic Scout</strong> frequency (${wormholePct}%). `;
                } else if (harmonyPct > 35) {
                    text += `The logs indicate high <strong>Harmonic Empathy & Lighthearted Storyteller</strong> signatures (${harmonyPct}%). `;
                } else if (dramaPct > 35) {
                    text += `The overall spectrum is tuned to <strong>Deep Narrative & Cerebral Atmosphere</strong> (${dramaPct}%). `;
                } else {
                    text += `The collection presents a balanced <strong>Quantum Generalist</strong> signature. `;
                }
                text += `<span class="block mt-2 text-neutral-500 text-[9.5px]">Cybernetic medals secured: <strong>0/5</strong>. Add releases to your agenda below to customize your live Cine-Sync quantum profile!</span>`;
                msgBox.innerHTML = text;
            } else {
                let text = `Your core cinematic sequence is <strong>${totalScheduled} releases strong</strong>. `;
                
                if (adrenalinePct > 35) {
                    text += `Your spectrum is highly configured to the <strong>Adrenaline Action Matrix</strong>. You seek risk, heavy stunts, and grand survival arcs. `;
                } else if (wormholePct > 35) {
                    text += `Your core matches the <strong>Stellar Cosmic Scout</strong> frequency. You are drawn to anomalous space timelines and tech mysteries. `;
                } else if (harmonyPct > 35) {
                    text += `Your logs indicate high <strong>Harmonic Empathy & Lighthearted Storyteller</strong> scores, valuing comfort and beautiful design. `;
                } else if (dramaPct > 35) {
                    text += `Your spectrum is tuned to <strong>Deep Narrative & Cerebral Atmosphere</strong>. You treasure intense acting, ratings, and heavy thematic structures. `;
                } else {
                    text += `You possess a beautifully balanced <strong>Quantum Generalist</strong> signature spanning multiple cinematic genres. `;
                }

                const unlockedNum = (isScoutUnlocked?1:0) + (isAdrenalineUnlocked?1:0) + (isVoyagerUnlocked?1:0) + (isDreamweaverUnlocked?1:0) + (isCommanderUnlocked?1:0);
                if (unlockedNum === 5) {
                    text += `<span class="block mt-2 text-red-100 font-extrabold tracking-wide font-mono text-[9px] uppercase bg-red-950/20 border border-red-900/30 p-1.5 rounded text-center animate-pulse">ALL SECURE CYBER MEDALS UNLOCKED! COMMAND LEVEL ACHIEVED!</span>`;
                } else {
                    text += `<span class="block mt-1 text-neutral-500 text-[9.5px]">Cybernetic medals secured: <strong>${unlockedNum}/5</strong>. Hover medals to see specifications!</span>`;
                }
                msgBox.innerHTML = text;
            }
            lucide.createIcons();
        }
    }

    window.showMedalTooltip = function(type) {
        const msgBox = document.getElementById('cine-sync-message-box');
        if (!msgBox) return;

        let title = '';
        let desc = '';
        let req = '';
        let glowColorClass = '';

        switch (type) {
            case 'scout':
                title = 'TEMPORAL SCOUT';
                desc = 'First coordinate transmission successfully established.';
                req = 'Requirement: Plan 1+ movie/show.';
                glowColorClass = 'text-cyan-400';
                break;
            case 'adrenaline':
                title = 'APEX ADRENALINE';
                desc = 'High affinity for explosive action, severe stunts, and heavy combat sequences.';
                req = 'Requirement: Plan 2+ Action/Thriller items.';
                glowColorClass = 'text-red-500';
                break;
            case 'voyager':
                title = 'NEXUS VOYAGER';
                desc = 'Attuned to anomalous wormholes, cybernetic empires, and deep cosmic space travel.';
                req = 'Requirement: Plan 1+ Sci-Fi/Mystery items.';
                glowColorClass = 'text-teal-400';
                break;
            case 'dreamweaver':
                title = 'DREAMWEAVER';
                desc = 'Drawn toward gorgeous stylized imagery, wit, harmony, and heartwarming narratives.';
                req = 'Requirement: Plan 1+ Animation/Family/Comedy items.';
                glowColorClass = 'text-amber-500';
                break;
            case 'commander':
                title = 'SYNC COMMANDER';
                desc = 'Elite synchronizer. Your timeline coordinates host an massive grid of futures.';
                req = 'Requirement: Plan 3+ items simultaneously.';
                glowColorClass = 'text-purple-400';
                break;
        }

        msgBox.innerHTML = `
            <div class="space-y-1">
                <p class="text-xs font-black tracking-widest ${glowColorClass} uppercase font-mono">${title}</p>
                <p class="text-[11px] text-white leading-relaxed font-sans">${desc}</p>
                <p class="text-[9.5px] text-neutral-500 font-semibold font-mono uppercase">${req}</p>
            </div>
        `;
        lucide.createIcons();
    }

    window.hideMedalTooltip = function() {
        updateCineSyncProfile();
    }

    // Feature 1: Dynamic Ambient Canvas Glow
    function updateAmbientGlow(color) {
        const glowDiv = document.getElementById('dynamic-ambient-glow');
        if (!glowDiv) return;
        if (!color) {
            color = (currentFocusedItem && currentFocusedItem.accentColor) ? currentFocusedItem.accentColor : '#ef4444';
        }
        glowDiv.style.background = `radial-gradient(circle at 50% 30%, ${color}1C 0%, ${color}03 55%, transparent 100%)`;
    }

    // Feature 2: High-Fidelity .ICS Calendar Invitation Exporter
    function downloadICS(item) {
        if (!item) return;
        const title = item.title;
        const rawDesc = item.tagline || item.description;
        const desc = `${rawDesc}\n\nType/Format: ${item.type}\nDirector/Creator: ${item.directorOrCreator}\nCast members: ${item.cast.join(', ')}\n\nStay Synced directly via: ${item.permalink || window.location.href}`;
        
        // Formulate date
        const dateStr = item.originalReleaseDate.replace(/-/g, '');
        const dtStart = `VALUE=DATE:${dateStr}`;
        const dtEnd = `VALUE=DATE:${dateStr}`;

        const icsLines = [
            "BEGIN:VCALENDAR",
            "VERSION:2.0",
            "PRODID:-//Insomniacs Cinematic Hub//NONSGML Calendar//EN",
            "BEGIN:VEVENT",
            `SUMMARY:[Cinema Premiere] ${title}`,
            `DESCRIPTION:${desc.replace(/\n/g, '\\n').replace(/,/g, '\\,')}`,
            `DTSTART;${dtStart}`,
            `DTEND;${dtEnd}`,
            "STATUS:CONFIRMED",
            "SEQUENCE:0",
            "END:VEVENT",
            "END:VCALENDAR"
        ];

        const blob = new Blob([icsLines.join("\r\n")], { type: 'text/calendar;charset=utf-8' });
        const trigger = document.createElement('a');
        trigger.href = URL.createObjectURL(blob);
        trigger.download = `insomniac-premiere-${item.id}.ics`;
        document.body.appendChild(trigger);
        trigger.click();
        document.body.removeChild(trigger);
        summonToast(`ICS event reminder compiled for ${title}!`);
    }

    function downloadICSById(id) {
        const item = SCHEDULE_ITEMS.find(f => f.id === id);
        if (item) {
            downloadICS(item);
        } else {
            summonToast("Failed to compile calendar file. Item metadata unresolvable.");
        }
    }

    // Feature 3: Interactive Trivia Scratch-to-Reveal
    function revealTrivia(el) {
        const mask = el.querySelector('.trivia-mask');
        if (mask) {
            mask.style.opacity = '0';
            mask.style.transform = 'scale(1.05) translateY(-5px)';
            setTimeout(() => {
                mask.style.display = 'none';
            }, 400);
            summonToast("Cinephile blueprint decrypted successfully.");
        }
    }

    // Feature 4: Dynamic Watchparty Coordinator
    function toggleWatchparty() {
        const wpSec = document.getElementById('modal-details-watchparty');
        const btnText = document.getElementById('wp-toggle-text');
        const btn = document.getElementById('wp-toggle-btn');
        if (wpSec && btnText && btn) {
            const isHidden = wpSec.classList.toggle('hidden');
            if (isHidden) {
                btnText.innerText = "PLAN PARTY";
                btn.className = "flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold font-mono tracking-wider text-red-400 hover:text-white bg-red-950/30 border border-red-500/40 hover:border-red-400 rounded-lg hover:bg-gradient-to-r hover:from-red-600 hover:to-red-800 transition-all duration-300 cursor-pointer shadow-[0_0_10px_rgba(239,68,68,0.1)] hover:shadow-[0_0_15px_rgba(239,68,68,0.3)]";
            } else {
                btnText.innerText = "COLLAPSE";
                btn.className = "flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold font-mono tracking-wider text-white bg-red-650 border border-red-400 rounded-lg hover:bg-red-500 transition-all duration-300 cursor-pointer shadow-[0_0_15px_rgba(239,68,68,0.4)]";
            }
        }
    }

    function openFocusedItemWatchparty(id) {
        if (id) {
            const found = SCHEDULE_ITEMS.find(x => x.id === id);
            if (found) {
                setFocusedItem(found);
            }
        }
        if (currentFocusedItem) {
            openDetailsModal(currentFocusedItem.id);
            setTimeout(() => {
                const wpSec = document.getElementById('modal-details-watchparty');
                const btnText = document.getElementById('wp-toggle-text');
                const btn = document.getElementById('wp-toggle-btn');
                if (wpSec && btnText && btn) {
                    wpSec.classList.remove('hidden');
                    btnText.innerText = "COLLAPSE";
                    btn.className = "flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold font-mono tracking-wider text-white bg-red-650 border border-red-400 rounded-lg hover:bg-neutral-800 transition-all duration-300 cursor-pointer shadow-[0_0_15px_rgba(239,68,68,0.4)]";
                    wpSec.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }, 150);
        } else {
            summonToast("Please select a movie/show from the schedule first!");
        }
    }

    function generateAndCopyWatchparty() {
        if (!currentFocusedItem) return;
        const dateVal = document.getElementById('wp-date').value || currentFocusedItem.originalReleaseDate;
        const timeVal = document.getElementById('wp-time').value || "20:00";
        const platVal = document.getElementById('wp-platform').value || "Discord Cyber-Theater";
        
        const inviteMsg = `🎬 CYBERNETIC WATCHPARTY COORDINATED! 🎬\n` + 
                          `====================================\n` +
                          `🎥 Release item: ${currentFocusedItem.title.toUpperCase()}\n` +
                          `📅 Schedule Watch Date: ${dateVal} at ${timeVal} GMT\n` +
                          `🌐 Channel: ${platVal}\n` +
                          `✨ "${currentFocusedItem.tagline || currentFocusedItem.description}"\n` +
                          `====================================\n` +
                          `Access full synchronized index coordinates: ${currentFocusedItem.permalink || window.location.href}\n` +
                          `Stay synchronized with global entertainment developments! 📡`;
        
        navigator.clipboard.writeText(inviteMsg).then(() => {
            summonToast("Watchparty invite formatted and copied directly to clipboard!");
        }).catch(() => {
            const el = document.createElement('textarea');
            el.value = inviteMsg;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            summonToast("Watchparty coordinates template generated & copied fallback style!");
        });
    }

    // Feature 5: Spotlight Command Center
    let spotlightSearchVal = '';

    function openSpotlightModal() {
        const modal = document.getElementById('spotlight-modal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('modal-prevent-scroll');
            const inp = document.getElementById('spotlight-search-input');
            if (inp) {
                inp.value = '';
                inp.focus();
            }
            handleSpotlightSearch('');
        }
    }

    function closeSpotlightModal() {
        const modal = document.getElementById('spotlight-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('modal-prevent-scroll');
        }
    }

    function handleSpotlightSearch(val) {
        const resultsContainer = document.getElementById('spotlight-results');
        if (!resultsContainer) return;

        const q = val.trim().toLowerCase();
        if (!q) {
            resultsContainer.innerHTML = `
                <div class="text-center py-10 text-neutral-500 text-sm font-sans select-none">
                    <p class="font-semibold text-neutral-450">Fuzzy Search Command Center</p>
                    <p class="text-[11px] text-neutral-600 mt-1 max-w-sm mx-auto">Directly search movie titles, genres, actors, rating groups, and directors.</p>
                </div>
            `;
            return;
        }

        const movies = [];
        const tvshows = [];
        const directors = [];
        const castMembers = [];
        const genres = [];

        SCHEDULE_ITEMS.forEach(item => {
            const matchesTitle = item.title ? item.title.toLowerCase().includes(q) : false;
            const matchesDirector = item.directorOrCreator ? item.directorOrCreator.toLowerCase().includes(q) : false;
            const matchesCast = item.cast ? item.cast.some(c => c.toLowerCase().includes(q)) : false;
            const matchesGenre = item.genre ? item.genre.some(g => g.toLowerCase().includes(q)) : false;
            const matchesPlot = item.description ? item.description.toLowerCase().includes(q) : false;

            if (matchesTitle || matchesPlot) {
                if (item.type === 'Movie') movies.push(item);
                else tvshows.push(item);
            }
            if (matchesDirector) {
                directors.push({ item, name: item.directorOrCreator });
            }
            if (matchesCast) {
                const actName = item.cast.find(c => c.toLowerCase().includes(q)) || 'Cast member';
                castMembers.push({ item, name: actName });
            }
            if (matchesGenre) {
                const gName = item.genre.find(g => g.toLowerCase().includes(q)) || 'Genre';
                genres.push({ item, tag: gName });
            }
        });

        if (movies.length === 0 && tvshows.length === 0 && directors.length === 0 && castMembers.length === 0 && genres.length === 0) {
            resultsContainer.innerHTML = `
                <div class="text-center py-14 text-neutral-500 text-xs font-mono select-none">
                    <i data-lucide="search-code" class="w-8 h-8 text-neutral-700 mx-auto mb-3"></i>
                    <span>NO MATCHING TRANSMISSION RESULTS FOUND</span>
                </div>
            `;
            lucide.createIcons();
            return;
        }

        let html = '';

        const makeRow = (item, prefix = '', label = '') => `
            <div onclick="selectSpotlightResult('${item.id}')" class="flex justify-between items-center p-3 border border-neutral-900 rounded-xl bg-neutral-900/40 hover:bg-neutral-900/80 hover:border-red-500/30 transition duration-150 cursor-pointer group">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-1.5 h-8 rounded-full shrink-0" style="background: ${item.posterGradient}"></div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-white uppercase truncate font-sans group-hover:text-red-400 transition-colors">${item.title}</p>
                        <p class="text-[9px] text-neutral-500 font-semibold font-mono uppercase mt-0.5">${prefix} ${label || item.type}</p>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <span class="text-[10px] font-mono text-neutral-600 group-hover:text-neutral-400 transition-colors uppercase mr-1">${item.displayDate}</span>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-neutral-700 group-hover:text-red-500 transition-colors"></i>
                </div>
            </div>
        `;

        if (movies.length > 0) {
            html += `<h4 class="text-[9px] font-black text-red-500 uppercase tracking-widest mb-2 font-mono">Movies matching query (${movies.length})</h4>`;
            html += `<div class="space-y-1.5">${movies.map(item => makeRow(item)).join('')}</div>`;
        }

        if (tvshows.length > 0) {
            html += `<h4 class="text-[9px] font-black text-red-500 uppercase tracking-widest mt-4 mb-2 font-mono">TV Series matching query (${tvshows.length})</h4>`;
            html += `<div class="space-y-1.5">${tvshows.map(item => makeRow(item)).join('')}</div>`;
        }

        if (directors.length > 0) {
            html += `<h4 class="text-[9px] font-black text-neutral-500 uppercase tracking-widest mt-4 mb-2 font-mono">Creators & Directors matching query (${directors.length})</h4>`;
            html += `<div class="space-y-1.5">${directors.map(d => makeRow(d.item, 'Director/Creator:', d.name)).join('')}</div>`;
        }

        if (castMembers.length > 0) {
            html += `<h4 class="text-[9px] font-black text-neutral-500 uppercase tracking-widest mt-4 mb-2 font-mono">Cast members matching query</h4>`;
            html += `<div class="space-y-1.5">${castMembers.map(c => makeRow(c.item, 'Starring:', c.name)).join('')}</div>`;
        }

        if (genres.length > 0) {
            html += `<h4 class="text-[9px] font-black text-neutral-500 uppercase tracking-widest mt-4 mb-2 font-mono">Genre matching query</h4>`;
            html += `<div class="space-y-1.5">${genres.map(g => makeRow(g.item, 'Genre Tag:', g.tag)).join('')}</div>`;
        }

        resultsContainer.innerHTML = html;
        lucide.createIcons();
    }

    function selectSpotlightResult(id) {
        closeSpotlightModal();
        const item = SCHEDULE_ITEMS.find(f => f.id === id);
        if (item) {
            setFocusedItem(item);
            openDetailsModal(id);
        }
    }

    // Global Key Handlers for Spotlights & Modal Dismissals
    window.addEventListener('keydown', (e) => {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            openSpotlightModal();
        }
        if (e.key === 'Escape') {
            closeSpotlightModal();
            closeDetailsModal();
            closeReminderModal();
        }
    });

    let appInitialized = false;
    function initAll() {
        if (appInitialized) return;
        
        // Safety guard: wait until dependencies are ready to compile the dashboard
        if (typeof tailwind === 'undefined' || typeof lucide === 'undefined') {
            setTimeout(initAll, 10);
            return;
        }

        appInitialized = true;
        renderCalendar();
        renderSmartphone();
        renderReleaseCards();
        lucide.createIcons();
        
        // Reveal the application template after compiling components to avoid Flash of Unstyled Content (FOUC)
        const app = document.getElementById('insom-cinematic-app-wrapper');
        if (app) {
            app.style.opacity = '1';
        }

        // Deep-linking automatic modal open support for shared releases
        const urlParams = new URLSearchParams(window.location.search);
        const releaseId = urlParams.get('release');
        if (releaseId) {
            const matchedItem = SCHEDULE_ITEMS.find(f => f.id === releaseId);
            if (matchedItem) {
                setTimeout(() => {
                    setFocusedItem(matchedItem);
                    openDetailsModal(releaseId);
                }, 150);
            }
        }
    }

    // Run as fast as humanly possible (under 1 second)
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        initAll();
    } else {
        document.addEventListener('DOMContentLoaded', initAll);
    }

    // Fallback trigger to ensure initialization occurs regardless of document load bounds
    window.addEventListener('load', () => {
        initAll();
    });
</script>
<?php
// Retrieve the dynamic WordPress site footer
get_footer();
?>
