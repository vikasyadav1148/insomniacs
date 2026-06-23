<?php
/**
 * Template Part: My Cinema Release Planner
 * Description: High-fidelity, premium interactive dashboard showing scheduled releases, wishlists, and followed actors, with dynamic countdown timers.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user = wp_get_current_user();
$user_id = $user->ID;

// Resilient array extraction helper function
if ( ! function_exists( 'insom_extract_meta_values' ) ) {
    function insom_extract_meta_values( $user_id, $meta_key ) {
        if ( ! $user_id ) return array();
        $raw = get_user_meta( $user_id, $meta_key );
        $values = array();
        if ( is_array( $raw ) ) {
            foreach ( $raw as $item ) {
                if ( empty( $item ) ) continue;
                $unserialized = maybe_unserialize( $item );
                if ( is_array( $unserialized ) ) {
                    $values = array_merge( $values, $unserialized );
                } else {
                    if ( is_string( $item ) && strpos( $item, ',' ) !== false ) {
                        $values = array_merge( $values, explode( ',', $item ) );
                    } else {
                        $values[] = $item;
                    }
                }
            }
        } elseif ( ! empty( $raw ) ) {
            $unserialized = maybe_unserialize( $raw );
            if ( is_array( $unserialized ) ) {
                $values = array_merge( $values, $unserialized );
            } else {
                if ( is_string( $raw ) && strpos( $raw, ',' ) !== false ) {
                    $values = array_merge( $values, explode( ',', $raw ) );
                } else {
                    $values[] = $raw;
                }
            }
        }
        return array_values( array_unique( array_filter( array_map( 'sanitize_text_field', $values ) ) ) );
    }
}

// Fetch Favorite (Wishlisted) Movie IDs and TV series IDs from both User Meta and Guest Cookies
$fav_mv = insom_extract_meta_values( $user_id, 'favourite_mv_id' );
$fav_sh = insom_extract_meta_values( $user_id, 'favourite_show_id' );
$all_wishlist_ids_raw = array_merge( $fav_mv, $fav_sh );

$favs_cookie = isset( $_COOKIE['idc_favs'] ) ? sanitize_text_field( $_COOKIE['idc_favs'] ) : '';
$favs_from_cookie = ! empty( $favs_cookie ) ? array_filter( array_map('trim', explode( ',', $favs_cookie ) ) ) : array();
$all_wishlist_ids_raw = array_unique( array_merge( $all_wishlist_ids_raw, array_map('strval', $favs_from_cookie) ) );

$all_wishlist_ids = array_values( array_filter( array_map( 'intval', $all_wishlist_ids_raw ) ) );
$mock_wishlist_ids = array_diff( $all_wishlist_ids_raw, array_map( 'strval', $all_wishlist_ids ) );

// Fetch Scheduled Countdown Release IDs from both User Meta and Guest Cookies
$scheduled_ids_raw = insom_extract_meta_values( $user_id, 'insom_scheduled_releases' );

$sched_cookie = isset( $_COOKIE['insom_scheduled'] ) ? sanitize_text_field( $_COOKIE['insom_scheduled'] ) : '';
$sched_from_cookie = ! empty( $sched_cookie ) ? array_filter( array_map('trim', explode( ',', $sched_cookie ) ) ) : array();
$scheduled_ids_raw = array_unique( array_merge( $scheduled_ids_raw, array_map('strval', $sched_from_cookie) ) );

$scheduled_ids = array_values( array_filter( array_map( 'intval', $scheduled_ids_raw ) ) );
$mock_scheduled_ids = array_diff( $scheduled_ids_raw, array_map( 'strval', $scheduled_ids ) );

// Fetch Followed Actor Term Slugs from both User Meta and Guest Cookies
$followed_meta = insom_extract_meta_values( $user_id, 'insom_followed_actors' );

$follows_cookie = isset( $_COOKIE['insom_followed_actors'] ) ? sanitize_text_field( $_COOKIE['insom_followed_actors'] ) : '';
$follows_from_cookie = ! empty( $follows_cookie ) ? array_filter( array_map('trim', explode( ',', $follows_cookie ) ) ) : array();

$followed_actor_slug_js = array_values( array_unique( array_filter( array_merge( $followed_meta, $follows_from_cookie ) ) ) );

// Define Mock Release Catalog to maintain companion synchronization
$mock_catalog = array(
    'toy-story-5' => array(
        'id'        => 'toy-story-5',
        'title'     => 'TOY STORY 5',
        'type'      => 'Movie',
        'date'      => '2026-06-17',
        'thumb'     => 'https://images.unsplash.com/photo-1608889175123-8ec330b86f84?q=80&w=600&auto=format&fit=crop',
        'permalink' => home_url('/coming-soon-movies-and-tv-shows-calendar/'),
        'genres'    => array('Animation', 'Adventure', 'Comedy', 'Family'),
    ),
    'house-of-the-dragon' => array(
        'id'        => 'house-of-the-dragon',
        'title'     => 'HOUSE OF THE DRAGON',
        'type'      => 'TV Series',
        'date'      => '2026-06-20',
        'thumb'     => 'https://images.unsplash.com/photo-1618336753974-aae8e04506aa?q=80&w=600&auto=format&fit=crop',
        'permalink' => home_url('/coming-soon-movies-and-tv-shows-calendar/'),
        'genres'    => array('Action', 'Adventure', 'Drama', 'Fantasy'),
    ),
    'the-odyssey' => array(
        'id'        => 'the-odyssey',
        'title'     => 'ODYSSEY',
        'type'      => 'Movie',
        'date'      => '2026-07-15',
        'thumb'     => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=600&auto=format&fit=crop',
        'permalink' => home_url('/coming-soon-movies-and-tv-shows-calendar/'),
        'genres'    => array('Sci-Fi', 'Mystery', 'Adventure', 'Thriller'),
    ),
);

// Setup WP_Query for Live Countdown Cards (Scheduled Release items)
$scheduled_items_data = array();
if ( ! empty( $scheduled_ids ) ) {
    $sched_query = new WP_Query( array(
        'post_type'      => array( 'ht_movie', 'ht_show' ),
        'post__in'       => $scheduled_ids,
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ) );

    if ( $sched_query->have_posts() ) {
        while ( $sched_query->have_posts() ) {
            $sched_query->the_post();
            $pid = get_the_ID();
            $ptype = get_post_type($pid);

            // Fetch Date
            $r_date = get_post_meta( $pid, 'release_date', true );
            if ( empty( $r_date ) ) {
                $r_date = get_post_meta( $pid, 'originalReleaseDate', true );
            }
            if ( empty( $r_date ) ) {
                $r_date = get_post_meta( $pid, 'movie_date', true );
            }
            if ( empty( $r_date ) ) {
                $r_date = date('Y-m-d', strtotime('+7 days')); // fallback
            }

            $img_url = get_the_post_thumbnail_url( $pid, 'medium' );
            if ( empty( $img_url ) ) {
                $img_url = get_template_directory_uri() . '/images/placeholder.jpg'; // or standard empty
            }

            $scheduled_items_data[] = array(
                'id'        => $pid,
                'title'     => get_the_title(),
                'type'      => ( $ptype === 'ht_movie' ) ? 'Movie' : 'TV Series',
                'date'      => $r_date,
                'thumb'     => $img_url,
                'permalink' => get_permalink(),
                'genres'    => wp_get_post_terms( $pid, 'mv_genre', array( 'fields' => 'names' ) ),
            );
        }
        wp_reset_postdata();
    }
}

// Append Mock Scheduled items
if ( ! empty( $mock_scheduled_ids ) ) {
    foreach ( $mock_scheduled_ids as $mid ) {
        if ( isset( $mock_catalog[$mid] ) ) {
            $scheduled_items_data[] = $mock_catalog[$mid];
        }
    }
}

// Setup WP_Query for Wishlisted Items Cards
$wishlist_items_data = array();
if ( ! empty( $all_wishlist_ids ) ) {
    $wish_query = new WP_Query( array(
        'post_type'      => array( 'ht_movie', 'ht_show' ),
        'post__in'       => $all_wishlist_ids,
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ) );

    if ( $wish_query->have_posts() ) {
        while ( $wish_query->have_posts() ) {
            $wish_query->the_post();
            $pid = get_the_ID();
            $ptype = get_post_type($pid);

            $r_date = get_post_meta( $pid, 'release_date', true );
            if ( empty($r_date) ) {
                $r_date = get_post_meta( $pid, 'originalReleaseDate', true );
            }
            if ( empty($r_date) ) {
                $r_date = get_post_meta( $pid, 'movie_date', true );
            }

            $img_url = get_the_post_thumbnail_url( $pid, 'medium' );
            if ( empty( $img_url ) ) {
                $img_url = 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?q=80&w=600&auto=format&fit=crop';
            }

            $wishlist_items_data[] = array(
                'id'        => $pid,
                'title'     => get_the_title(),
                'type'      => ( $ptype === 'ht_movie' ) ? 'Movie' : 'TV Series',
                'date'      => $r_date ?: 'TBA',
                'thumb'     => $img_url,
                'permalink' => get_permalink(),
                'genres'    => wp_get_post_terms( $pid, 'mv_genre', array( 'fields' => 'names' ) ),
            );
        }
        wp_reset_postdata();
    }
}

// Append Mock Wishlist items
if ( ! empty( $mock_wishlist_ids ) ) {
    foreach ( $mock_wishlist_ids as $mid ) {
        if ( isset( $mock_catalog[$mid] ) ) {
            $wishlist_items_data[] = $mock_catalog[$mid];
        }
    }
}

// Fetch Followed Actor Objects
$followed_actors_data = array();
if ( ! empty( $followed_actor_slug_js ) ) {
    foreach ( $followed_actor_slug_js as $slug ) {
        $term = get_term_by( 'slug', $slug, 'mv_actor' );
        if ( $term && ! is_wp_error( $term ) ) {
            $avatar_url = '';
            
            // First, try direct term meta
            $avatar_url = get_term_meta( $term->term_id, 'insom_actor_image', true );
            
            if ( empty( $avatar_url ) && function_exists( 'fw_get_db_term_option' ) ) {
                $term_options = fw_get_db_term_option( $term->term_id, 'mv_actor' );
                if ( ! empty( $term_options ) ) {
                    if ( isset( $term_options['avatar_url'] ) && ! empty( $term_options['avatar_url'] ) ) {
                        $avatar_url = $term_options['avatar_url'];
                    } elseif ( isset( $term_options['avatar'] ) && isset( $term_options['avatar']['attachment_id'] ) && ! empty( $term_options['avatar']['attachment_id'] ) ) {
                        $avatar_url = wp_get_attachment_url( $term_options['avatar']['attachment_id'] );
                    } elseif ( isset( $term_options['actor_image'] ) && ! empty( $term_options['actor_image']['url'] ) ) {
                        $avatar_url = $term_options['actor_image']['url'];
                    }
                }
            }
            
            if ( empty( $avatar_url ) ) {
                // Secondary check for other possible custom field mappings
                $avatar_url = get_term_meta( $term->term_id, 'actor_image_url', true );
            }

            if ( empty( $avatar_url ) ) {
                $avatar_url = 'https://secure.gravatar.com/avatar/646c2435c24e65ec8912ebbcf83586cd?s=120&d=mm&r=g'; 
            } elseif ( function_exists( 'blockter_cache_external_image' ) ) {
                $avatar_url = blockter_cache_external_image( $avatar_url );
            }

            $followed_actors_data[] = array(
                'id'        => $term->term_id,
                'name'      => $term->name,
                'slug'      => $term->slug,
                'permalink' => get_term_link( $term ),
                'avatar'    => $avatar_url,
                'count'     => $term->count,
            );
        }
    }
}
?>

<div class="cinema-planner-container" style="font-family: monospace; color: #f3f4f6; padding: 20px 0;">

    <!-- Title and Subtitle -->
    <div style="margin-bottom: 32px; border-left: 3px solid #dcf836; padding-left: 16px;">
        <h2 style="color: #fff; font-size: 22px; font-weight: 900; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 4px 0;">CINEMA RELEASE PLANNER</h2>
        <p style="color: #8b92a6; font-size: 11px; margin: 0; text-transform: uppercase;">Manage your tailored countdown pipelines, wishlisted items, and followed talents</p>
    </div>

    <!-- stats grid row -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 40px;">
        <!-- Card 1 -->
        <div style="background: rgba(18, 18, 22, 0.4); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 20px; transition: border 0.2s;" onmouseover="this.style.borderColor='rgba(57, 255, 20, 0.25)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'">
            <span style="color: #39ff14; font-size: 20px;">⏰</span>
            <div style="color: #8b92a6; font-size: 11px; text-transform: uppercase; margin-top: 12px; font-weight: bold;">Countdown Pipelines</div>
            <div id="stats-sched-count" style="color: #fff; font-size: 28px; font-weight: 900; margin-top: 4px;"><?php echo count($scheduled_items_data); ?></div>
        </div>
        <!-- Card 2 -->
        <div style="background: rgba(18, 18, 22, 0.4); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 20px; transition: border 0.2s;" onmouseover="this.style.borderColor='rgba(220, 248, 54, 0.25)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'">
            <span style="color: #dcf836; font-size: 20px;">💖</span>
            <div style="color: #8b92a6; font-size: 11px; text-transform: uppercase; margin-top: 12px; font-weight: bold;">Wishlisted Releases</div>
            <div id="stats-wish-count" style="color: #fff; font-size: 28px; font-weight: 900; margin-top: 4px;"><?php echo count($wishlist_items_data); ?></div>
        </div>
        <!-- Card 3 -->
        <div style="background: rgba(18, 18, 22, 0.4); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 20px; transition: border 0.2s;" onmouseover="this.style.borderColor='rgba(0, 240, 255, 0.25)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'">
            <span style="color: #00f0ff; font-size: 20px;">⭐</span>
            <div style="color: #8b92a6; font-size: 11px; text-transform: uppercase; margin-top: 12px; font-weight: bold;">Follow Radars</div>
            <div id="stats-actors-count" style="color: #fff; font-size: 28px; font-weight: 900; margin-top: 4px;"><?php echo count($followed_actors_data); ?></div>
        </div>
    </div>

    <!-- MAIN INTERACTIVE PLANNERS GRID: THREE COLUMN / STACKED BENTO SECTIONS -->
    <div style="display: flex; flex-direction: column; gap: 48px;">
        
        <!-- SECTION 1: LIVE COUNTDOWN TICKERS (SCHEDULED) -->
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 8px;">
                <span style="color: #39ff14; font-size: 14px;">●</span>
                <h3 style="color: #fff; font-size: 14px; font-weight: bold; text-transform: uppercase; margin: 0; letter-spacing: 0.5px;">ACTIVE PLANNER COUNTDOWNS</h3>
            </div>

            <?php if ( empty($scheduled_items_data) ) : ?>
                <div id="empty-sched-slate" style="background: rgba(18, 18, 22, 0.2); border: 1px dashed rgba(255,255,255,0.08); border-radius: 12px; padding: 40px 24px; text-align: center; color: #8b92a6; font-size: 11px;">
                    <span style="display: block; font-size: 32px; margin-bottom: 12px;">📆</span>
                    No upcoming releases scheduled. Head to the <a href="<?php echo esc_url( home_url('/coming-soon-movies-and-tv-shows-calendar/') ); ?>" style="color: #39ff14; font-weight: bold; text-decoration: underline;">Coming Soon Calendar</a> and toggle some tracks to watch them tick down in real-time!
                </div>
            <?php else : ?>
                <div id="sched-cards-grid" style="display: flex; flex-direction: column; gap: 16px;">
                    <?php foreach ( $scheduled_items_data as $item ) : ?>
                        <div id="sched-row-<?php echo $item['id']; ?>" style="background: rgba(18, 18, 22, 0.55); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; overflow: hidden; display: flex; align-items: center; padding: 16px; gap: 20px; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.4)'; this.style.transform='translateY(-1px)';" onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)';" class="sched-card-interactive">
                            <!-- Movie Image thumbnail -->
                            <a href="<?php echo esc_url($item['permalink']); ?>" style="width: 70px; height: 100px; border-radius: 6px; overflow: hidden; flex-shrink: 0; border: 1px solid rgba(255,255,255,0.08); display: block; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                                <img src="<?php echo esc_url($item['thumb']); ?>" style="width: 100%; height: 100%; object-fit: cover;" referrerPolicy="no-referrer" />
                            </a>
                            
                            <!-- Movie details -->
                            <div style="flex-grow: 1;">
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                    <span style="background: rgba(220, 248, 54, 0.1); color: #dcf836; font-size: 9px; font-weight: bold; padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">
                                        <?php echo esc_html($item['type']); ?>
                                    </span>
                                    <?php if ( ! empty($item['genres']) ) : ?>
                                        <span style="color: #8b92a6; font-size: 9px;">
                                            <?php echo esc_html(implode(' / ', array_slice($item['genres'], 0, 2))); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo esc_url($item['permalink']); ?>" style="color: #fff; text-decoration: none; font-size: 14px; font-weight: bold; transition: color 0.2s;" onmouseover="this.style.color='#39ff14'" onmouseout="this.style.color='#fff'">
                                    <?php echo esc_html($item['title']); ?>
                                </a>
                                <div style="color: #8b92a6; font-size: 11px; margin-top: 6px; display: flex; align-items: center; gap: 4px;">
                                    📅 Release: <strong style="color: #eee;"><?php echo date('M d, Y', strtotime($item['date'])); ?></strong>
                                </div>
                            </div>

                            <!-- Live ticking countdown clock -->
                            <div style="flex-shrink: 0; background: #0c0d10; border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 12px 16px; text-align: center; min-width: 180px;">
                                <div style="color: #39ff14; font-size: 9px; font-weight: bold; letter-spacing: 0.5px; margin-bottom: 6px; text-transform: uppercase;">LIVE TIMER PIPELINE</div>
                                <div class="planner-timer-clock-num" data-target-date="<?php echo esc_attr($item['date']); ?>" id="ticker-clock-<?php echo $item['id']; ?>" style="color: #fff; font-size: 14px; font-weight: bold; letter-spacing: 0.5px;">
                                    Initializing...
                                </div>
                            </div>

                            <!-- Action triggers -->
                            <div style="flex-shrink: 0; display: flex; flex-direction: column; gap: 6px;">
                                <a href="<?php echo esc_url($item['permalink']); ?>" style="background: rgba(255,255,255,0.04); color: #fff; font-size: 10px; font-weight: bold; text-decoration: none; text-transform: uppercase; padding: 8px 12px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.08); text-align: center; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='rgba(255,255,255,0.04)'">VIEW PREVIEW</a>
                                <button onclick="untrackPlannerRelease('<?php echo esc_js($item['id']); ?>')" style="background: transparent; color: #ef4444; font-size: 10px; font-weight: bold; text-transform: uppercase; padding: 8px 12px; border-radius: 6px; border: 1px solid rgba(239, 68, 68, 0.15); cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='rgba(239, 68, 68, 0.08)'" onmouseout="this.style.background='transparent'">UNTRACK</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- SECTION 2: WISHLISTED RELEASES GRID -->
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 8px;">
                <span style="color: #dcf836; font-size: 14px;">●</span>
                <h3 style="color: #fff; font-size: 14px; font-weight: bold; text-transform: uppercase; margin: 0; letter-spacing: 0.5px;">WISHLISTED CALENDAR RELEASES</h3>
            </div>

            <?php if ( empty($wishlist_items_data) ) : ?>
                <div id="empty-wish-slate" style="background: rgba(18, 18, 22, 0.2); border: 1px dashed rgba(255,255,255,0.08); border-radius: 12px; padding: 40px 24px; text-align: center; color: #8b92a6; font-size: 11px;">
                    <span style="display: block; font-size: 32px; margin-bottom: 12px;">💖</span>
                    No releases wishlisted yet. Tap "WISHLIST" on the movies/shows within the <a href="<?php echo esc_url( home_url('/coming-soon-movies-and-tv-shows-calendar/') ); ?>" style="color: #dcf836; font-weight: bold; text-decoration: underline;">Release Calendar</a> to capture them here!
                </div>
            <?php else : ?>
                <div id="wishlist-cards-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 16px;">
                    <?php foreach ( $wishlist_items_data as $item ) : ?>
                        <div id="wish-card-<?php echo $item['id']; ?>" style="background: rgba(18, 18, 22, 0.4); border: 1px solid rgba(255,255,255,0.05); border-radius: 10px; overflow: hidden; display: flex; flex-direction: column; position: relative;" class="wish-card-interactive">
                            <!-- Image Poster Area -->
                            <div style="position: relative; width: 100%; padding-top: 140%; border-bottom: 1px solid rgba(255,255,255,0.05); overflow: hidden;">
                                <a href="<?php echo esc_url($item['permalink']); ?>" style="position: absolute; inset: 0; display: block; z-index: 1;">
                                    <img src="<?php echo esc_url($item['thumb']); ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" referrerPolicy="no-referrer" />
                                </a>
                                <span style="position: absolute; top: 6px; left: 6px; background: rgba(0,0,0,0.75); color: #dcf836; font-size: 8px; font-weight: bold; padding: 2px 5px; border-radius: 4px; text-transform: uppercase; z-index: 2; pointer-events: none;">
                                    <?php echo esc_html($item['type']); ?>
                                </span>
                                <!-- Quick Unwish Button overlay -->
                                <button onclick="unwishRelease('<?php echo esc_js($item['id']); ?>')" style="position: absolute; top: 6px; right: 6px; width: 22px; height: 22px; border-radius: 50%; background: rgba(10,10,12,0.85); border: 1px solid rgba(255,255,255,0.15); color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 11px; cursor: pointer; z-index: 3; transition: all 0.2s;" onmouseover="this.style.background='#ef4444'; this.style.color='#fff';" onmouseout="this.style.background='rgba(10,10,12,0.85)'; this.style.color='#ef4444';">×</button>
                            </div>
                            
                            <!-- Texts info -->
                            <div style="padding: 10px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                                <div>
                                    <h4 style="margin: 0; font-size: 11px; font-weight: bold; text-transform: uppercase; line-height: 1.4; max-height: 2.8em; overflow: hidden;">
                                        <a href="<?php echo esc_url($item['permalink']); ?>" style="color: #fff; text-decoration: none;" onmouseover="this.style.color='#dcf836'" onmouseout="this.style.color='#fff'">
                                            <?php echo esc_html($item['title']); ?>
                                        </a>
                                    </h4>
                                </div>
                                <div style="color: #8b92a6; font-size: 9px; margin-top: 6px; font-weight: bold;">
                                    <?php echo ($item['date'] !== 'TBA') ? date('M Y', strtotime($item['date'])) : 'TBA'; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- SECTION 3: FOLLOWED TALENTS ROSTER -->
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 8px;">
                <span style="color: #00f0ff; font-size: 14px;">●</span>
                <h3 style="color: #fff; font-size: 14px; font-weight: bold; text-transform: uppercase; margin: 0; letter-spacing: 0.5px;">FOLLOWED TALENT RADARS</h3>
            </div>

            <?php if ( empty($followed_actors_data) ) : ?>
                <div id="empty-actors-slate" style="background: rgba(18, 18, 22, 0.2); border: 1px dashed rgba(255,255,255,0.08); border-radius: 12px; padding: 40px 24px; text-align: center; color: #8b92a6; font-size: 11px;">
                    <span style="display: block; font-size: 32px; margin-bottom: 12px;">🌟</span>
                    No actors followed. Head to any actor profile page and click the "FOLLOW" radar button to populate your roster feed!
                </div>
            <?php else : ?>
                <div id="actors-cards-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 16px;">
                    <?php foreach ( $followed_actors_data as $actor ) : ?>
                        <div id="actor-card-<?php echo $actor['id']; ?>" style="background: rgba(18, 18, 22, 0.55); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 18px 12px; text-align: center; position: relative; display: flex; flex-direction: column; align-items: center; transition: all 0.3s;" onmouseover="this.style.boxShadow='0 4px 20px rgba(0, 240, 255, 0.1)'; this.style.borderColor='rgba(0, 240, 255, 0.25)';" onmouseout="this.style.boxShadow='none'; this.style.borderColor='rgba(255,255,255,0.06)';" class="actor-card-interactive">
                            <!-- Unfollow Button overlay -->
                            <button onclick="unfollowActor('<?php echo esc_js($actor['slug']); ?>', <?php echo $actor['id']; ?>)" style="position: absolute; top: 12px; right: 12px; background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.15); border-radius: 4px; color: #ef4444; font-size: 8px; font-weight: bold; cursor: pointer; padding: 4px 8px; line-height: 1; transition: all 0.2s; letter-spacing: 0.5px; text-transform: uppercase;" onmouseover="this.style.background='#ef4444'; this.style.color='#fff';" onmouseout="this.style.background='rgba(239, 68, 68, 0.05)'; this.style.color='#ef4444';">UNFOLLOW</button>

                            <!-- Circular image avatar -->
                            <a href="<?php echo esc_url($actor['permalink']); ?>" style="width: 80px; height: 80px; border-radius: 50%; overflow: hidden; border: 2px solid rgba(0,240,255,0.15); margin-top: 24px; margin-bottom: 12px; display: block; transition: all 0.3s; box-shadow: 0 4px 10px rgba(0,0,0,0.35);" onmouseover="this.style.borderColor='#00f0ff'; this.style.transform='scale(1.05)';" onmouseout="this.style.borderColor='rgba(0,240,255,0.15)'; this.style.transform='scale(1)';">
                                <img src="<?php echo esc_url($actor['avatar']); ?>" style="width: 100%; height: 100%; object-fit: cover;" referrerPolicy="no-referrer" />
                            </a>

                            <!-- Name -->
                            <h4 style="margin: 0; font-size: 11px; font-weight: bold; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; width: 100%; text-transform: uppercase; min-height: 1.5em; letter-spacing: 0.5px;">
                                <a href="<?php echo esc_url($actor['permalink']); ?>" style="color: #fff; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#00f0ff'" onmouseout="this.style.color='#fff'">
                                    <?php echo esc_html($actor['name']); ?>
                                </a>
                            </h4>

                            <div style="color: #8b92a6; font-size: 9px; margin-top: 4px; font-weight: bold; letter-spacing: 0.5px; text-transform: uppercase; background: rgba(255,255,255,0.03); padding: 2px 8px; border-radius: 10px; display: inline-block;">
                                <?php echo esc_html(($actor['count'] ? $actor['count'] : '0') . ' Works'); ?>
                            </div>

                            <!-- View Actor link -->
                            <a href="<?php echo esc_url($actor['permalink']); ?>" style="background: rgba(0, 240, 255, 0.03); border: 1px solid rgba(0, 240, 255, 0.15); color: #00f0ff; font-size: 10px; font-weight: bold; text-decoration: none; text-transform: uppercase; padding: 8px 16px; border-radius: 6px; margin-top: 14px; display: inline-block; width: 100%; transition: all 0.2s; letter-spacing: 0.5px; box-sizing: border-box;" onmouseover="this.style.background='rgba(0, 240, 255, 0.15)'; this.style.borderColor='#00f0ff'; this.style.boxShadow='0 0 10px rgba(0, 240, 255, 0.2)';" onmouseout="this.style.background='rgba(0, 240, 255, 0.03)'; this.style.borderColor='rgba(0, 240, 255, 0.15)'; this.style.boxShadow='none';">OPEN PROFILE</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- CLIENT COUNTDOWN CONTROLLER / AJAX SYNC SCRIPT -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Clean up any colliding host-only cookies that might have been set by JS in the past, to let the PHP/wildcard domains take full control
    document.cookie = "insom_followed_actors=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
    
    // Ticking Live Countdowns Engine
    const tickerContainers = document.querySelectorAll(".planner-timer-clock-num");
    
    function tickPlannerClocks() {
        const now = new Date().getTime();
        
        tickerContainers.forEach(container => {
            const targetDateStr = container.getAttribute("data-target-date");
            if (!targetDateStr) return;
            
            const releaseTime = new Date(targetDateStr).getTime();
            const diff = releaseTime - now;
            
            if (diff <= 0) {
                container.innerHTML = "<span style='color: #ef4444; font-weight: 900; letter-spacing: 0.5px;'>RELEASED / PREMIERED!</span>";
            } else {
                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const secs = Math.floor((diff % (1000 * 60)) / 1000);
                
                container.innerText = `${days}d : ${String(hours).padStart(2, '0')}h : ${String(mins).padStart(2, '0')}m : ${String(secs).padStart(2, '0')}s`;
            }
        });
    }
    
    // Initial and periodic run of ticks
    tickPlannerClocks();
    setInterval(tickPlannerClocks, 1000);
});

// UNIX AJAX Action triggers:
function untrackPlannerRelease(postId) {
    if (confirm("Are you sure you want to untrack this countdown pipeline release?")) {
        // Visual optimistic deletion
        const row = document.getElementById(`sched-row-${postId}`);
        if (row) {
            row.style.opacity = '0';
            row.style.transform = 'scale(0.95)';
            setTimeout(() => {
                row.remove();
                
                // Decrement count in stats
                const countEl = document.getElementById("stats-sched-count");
                if (countEl) {
                    const currentCount = parseInt(countEl.innerText) || 0;
                    countEl.innerText = Math.max(0, currentCount - 1);
                    if (Math.max(0, currentCount - 1) === 0) {
                        const grid = document.getElementById("sched-cards-grid");
                        if (grid) {
                            grid.innerHTML = `
                                <div id="empty-sched-slate" style="background: rgba(18, 18, 22, 0.2); border: 1px dashed rgba(255,255,255,0.08); border-radius: 12px; padding: 40px 24px; text-align: center; color: #8b92a6; font-size: 11px;">
                                    <span style="display: block; font-size: 32px; margin-bottom: 12px;">📆</span>
                                    No upcoming releases scheduled. Head to the <a href="<?php echo esc_url( home_url('/coming-soon-movies-and-tv-shows-calendar/') ); ?>" style="color: #39ff14; font-weight: bold; text-decoration: underline;">Coming Soon Calendar</a> and toggle some tracks!
                                </div>
                            `;
                        }
                    }
                }
            }, 250);
        }

        // Call database sync
        if (typeof jQuery !== 'undefined') {
            jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", {
                action: "insom_v3_toggle_schedule",
                post_id: postId
            });
            
            // Sync local storage so calendar page is on exact same note
            let localScheds = JSON.parse(localStorage.getItem('standalone_scheduled')) || [];
            localScheds = localScheds.filter(id => id != postId);
            localStorage.setItem('standalone_scheduled', JSON.stringify(localScheds));
        }
    }
}

function unwishRelease(postId) {
    // Visual optimistic deletion
    const card = document.getElementById(`wish-card-${postId}`);
    if (card) {
        card.style.opacity = '0';
        card.style.transform = 'scale(0.9)';
        setTimeout(() => {
            card.remove();
            
            // Decrement count in stats
            const countEl = document.getElementById("stats-wish-count");
            if (countEl) {
                const currentCount = parseInt(countEl.innerText) || 0;
                countEl.innerText = Math.max(0, currentCount - 1);
                if (Math.max(0, currentCount - 1) === 0) {
                    const grid = document.getElementById("wishlist-cards-grid");
                    if (grid) {
                        grid.innerHTML = `
                            <div id="empty-wish-slate" style="background: rgba(18, 18, 22, 0.2); border: 1px dashed rgba(255,255,255,0.08); border-radius: 12px; padding: 40px 24px; text-align: center; color: #8b92a6; font-size: 11px;">
                                <span style="display: block; font-size: 32px; margin-bottom: 12px;">💖</span>
                                No releases wishlisted yet. Tap "WISHLIST" on the movies/shows within the <a href="<?php echo esc_url( home_url('/coming-soon-movies-and-tv-shows-calendar/') ); ?>" style="color: #dcf836; font-weight: bold; text-decoration: underline;">Release Calendar</a> to capture them here!
                            </div>
                        `;
                    }
                }
            }
        }, 220);
    }

    // Call database sync
    if (typeof jQuery !== 'undefined') {
        jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", {
            action: "insom_v3_toggle_fav",
            post_id: postId
        });
        
        // Sync local storage so calendar page is on exact same note
        let localFavs = JSON.parse(localStorage.getItem('standalone_fav')) || [];
        localFavs = localFavs.filter(id => id != postId);
        localStorage.setItem('standalone_fav', JSON.stringify(localFavs));
    }
}

function unfollowActor(actorSlug, termId) {
    if (confirm("Are you sure you want to stop following this actor?")) {
        // Visual optimistic deletion
        const card = document.getElementById(`actor-card-${termId}`);
        if (card) {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => {
                card.remove();
                
                // Decrement count in stats
                const countEl = document.getElementById("stats-actors-count");
                if (countEl) {
                    const currentCount = parseInt(countEl.innerText) || 0;
                    countEl.innerText = Math.max(0, currentCount - 1);
                    if (Math.max(0, currentCount - 1) === 0) {
                        const grid = document.getElementById("actors-cards-grid");
                        if (grid) {
                            grid.innerHTML = `
                                <div id="empty-actors-slate" style="background: rgba(18, 18, 22, 0.2); border: 1px dashed rgba(255,255,255,0.08); border-radius: 12px; padding: 40px 24px; text-align: center; color: #8b92a6; font-size: 11px;">
                                    <span style="display: block; font-size: 32px; margin-bottom: 12px;">🌟</span>
                                    No actors followed. Head to any actor profile page and click the "FOLLOW" radar button to populate your roster feed!
                                </div>
                            `;
                        }
                    }
                }
            }, 220);
        }

        // Call database sync (PHP sets the cookie on the correct domain wildcard path)
        if (typeof jQuery !== 'undefined') {
            jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", {
                action: "insom_v3_toggle_actor_follow",
                actor_slug: actorSlug
            });
            
            // Sync localStorage as well
            let localFollows = JSON.parse(localStorage.getItem('standalone_followed_actors')) || [];
            localFollows = localFollows.filter(function(slug) { return slug !== actorSlug; });
            localStorage.setItem('standalone_followed_actors', JSON.stringify(localFollows));
        }
    }
}
</script>
