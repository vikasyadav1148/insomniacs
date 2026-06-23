<?php
/**
 * Theme functions file
 */

/**
 * Resilient array extraction helper function
 */
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

/**
 * Enqueue parent theme styles first
 * Replaces previous method using @import
 * <http://codex.wordpress.org/Child_Themes>
 */

require_once get_template_directory() . '/inc/init.php';

/**
 * TGM Plugin Activation
 */
{
    require_once get_template_directory() . '/TGM-Plugin-Activation/class-tgm-plugin-activation.php';
    require_once get_template_directory() . '/TGM-Plugin-Activation/recommend_plugins.php';
}

/**
 * Recommend the Kirki plugin
 */
require get_template_directory() . '/inc/include-kirki.php';

/**
 * Load the Kirki Fallback class
 */
require get_template_directory() . '/inc/kirki-fallback.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';
/**
 * Normalize custom post type labels for movies and series.
 * This affects breadcrumbs, archives, and other label-based outputs.
 */
function blockter_normalize_movie_show_labels( $args, $post_type ) {
    if ( $post_type === 'ht_movie' ) {
        if ( isset( $args['labels'] ) && is_array( $args['labels'] ) ) {
            $args['labels']['name']       = 'Movies';
            $args['labels']['menu_name']  = 'Movies';
        }
    } elseif ( $post_type === 'ht_show' ) {
        if ( isset( $args['labels'] ) && is_array( $args['labels'] ) ) {
            $args['labels']['name']       = 'Series';
            $args['labels']['menu_name']  = 'Series';
        }
    }

    return $args;
}
add_filter( 'register_post_type_args', 'blockter_normalize_movie_show_labels', 10, 2 );

/**
 * Extend mv_genre and mv_collection taxonomies to standard blog posts.
 * Runs after the plugin has registered both taxonomies.
 */
function blockter_extend_movie_taxonomies_to_posts() {
    register_taxonomy_for_object_type( 'mv_genre', 'post' );
    register_taxonomy_for_object_type( 'mv_collection', 'post' );
}
/**
 * Get the URL for a network term page by provider name (e.g. from TMDB).
 * Uses the first word of the provider name and finds a term whose name or slug contains that word.
 *
 * @param string $provider_name Provider display name (e.g. "Netflix", "Amazon Prime Video").
 * @return string|false Term archive URL or false if no matching term.
 */
function blockter_get_network_link_for_provider( $provider_name ) {
    if ( empty( $provider_name ) || ! taxonomy_exists( 'networks' ) ) {
        return false;
    }
    $parts = preg_split( '/\s+/', trim( $provider_name ), 2 );
    $first_word = isset( $parts[0] ) ? $parts[0] : '';
    if ( $first_word === '' ) {
        return false;
    }
    $first_word_slug = sanitize_title( $first_word );
    $terms = get_terms( array(
        'taxonomy'   => 'networks',
        'hide_empty' => false,
    ) );
    if ( empty( $terms ) || is_wp_error( $terms ) ) {
        return false;
    }
    $first_word_lower = strtolower( $first_word );
    $first_word_slug_lower = strtolower( $first_word_slug );
    foreach ( $terms as $term ) {
        $name_contains = ( strpos( strtolower( $term->name ), $first_word_lower ) !== false );
        $slug_contains = ( strpos( strtolower( $term->slug ), $first_word_slug_lower ) !== false );
        if ( $name_contains || $slug_contains ) {
            $link = get_term_link( $term );
            return is_wp_error( $link ) ? false : $link;
        }
    }
    return false;
}
/**
 * Generate HTML list of streaming providers, linking each to its network term page when available.
 *
 * @param array $providers Array of provider objects with logo_path and provider_name.
 * @return string HTML list items.
 */
function blockter_generate_provider_list( $providers ) {
    $provider_list = '';
    foreach ( $providers as $provider ) {
        $logo_url = 'https://image.tmdb.org/t/p/w500' . $provider->logo_path;
        $name    = isset( $provider->provider_name ) ? $provider->provider_name : '';
        $link    = blockter_get_network_link_for_provider( $name );
        $img     = '<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $name ) . ' logo" style="width:50px;height:auto;"/>';
        if ( $link ) {
            $provider_list .= '<li><a href="' . esc_url( $link ) . '">' . $img . '</a></li>';
        } else {
            $provider_list .= '<li>' . $img . '</li>';
        }
    }
    return $provider_list;
}
function get_movie_details_shortcode($atts) {
    // Shortcode attributes (optional)
    $atts = shortcode_atts(array(
        'id' => '',
        'slug' => '',
    ), $atts);

    $movie_title = $atts['slug'];
    $movie_id = $atts['id'];
    
    // Check if title is provided
    if (empty($movie_id)) {
        echo 'Please provide a movie ID.';
        return;
    }

    // Replace with your TMDb API key
    $api_key =  fw_get_db_ext_settings_option( 'ht-movie', 'api-key', NULL );
    $tmdb_id = get_post_meta($movie_id, '_tmdb_id', true);

         $url = "https://api.themoviedb.org/3/movie/$tmdb_id?api_key=$api_key";
    // Send search request using wp_remote_get
    $search_response = wp_remote_get($url);

    if (is_wp_error($search_response)) {
        $error_message = $search_response->get_error_message();
        echo "Error: $error_message";
        return;
    }

    $details_data = json_decode(wp_remote_retrieve_body($search_response));

    // Display Budget and Revenue
    $budget = !empty($details_data->budget) ? number_format($details_data->budget, 2) . '$' : 'Not Declared';
    $revenue = !empty($details_data->revenue) ? number_format($details_data->revenue, 2) . '$' : 'Not Declared';
    $vote_average = !empty($details_data->vote_average) ? number_format($details_data->vote_average, 1) : 'N/A';
    $vote_count = !empty($details_data->vote_count) ? $details_data->vote_count : 'No';
    $movie_popularity = !empty($details_data->popularity) ? $details_data->popularity : 'Not Declared';

       // Prepare spoken languages
       $spoken_languages = '';
       if (!empty($details_data->spoken_languages)) {
           foreach ($details_data->spoken_languages as $language) {
               $spoken_languages .= $language->english_name . ', ';
           }
           $spoken_languages = rtrim($spoken_languages, ', ');  // Remove trailing comma
       } else {
           $spoken_languages = 'Not Declared';
       }


    echo "<div class='movie-details'>
            <p><strong>Budget:</strong> $budget</p>
            <p><strong>Revenue:</strong> $revenue</p>
            <p><strong>Vote Average:</strong> $vote_average/10</p>
            <p><strong>Vote Count:</strong> $vote_count Votes</p>
            <p><strong>Movie Popularity:</strong>  $movie_popularity</p>
            <p><strong>Spoken Languages:</strong> $spoken_languages</p>
          </div>";
}
add_shortcode('movie_details', 'get_movie_details_shortcode');
function get_show_details_shortcode($atts) {
    // Shortcode attributes (optional)
     $atts = shortcode_atts(array(
        'id' => '',
        'slug' => '',
    ), $atts);

    $tv_show_title = $atts['slug'];
    $tv_show_id = $atts['id'];

    // Check if title is provided
    if (empty($tv_show_id)) {
        echo 'Please provide a TV show ID.';
        return;
    }

    // Replace with your TMDb API key
        $api_key =  fw_get_db_ext_settings_option( 'ht-movie', 'api-key', NULL );
        $tmdb_id = get_post_meta($tv_show_id, '_tmdb_id', true);
    // Search endpoint URL
    $url = "https://api.themoviedb.org/3/tv/$tmdb_id?api_key=$api_key";
    // Send search request using wp_remote_get
    $search_response = wp_remote_get($url);

    if (is_wp_error($search_response)) {
        $error_message = $search_response->get_error_message();
        echo "Error: $error_message";
        return;
    }

    $details_data = json_decode(wp_remote_retrieve_body($search_response));

     // Prepare spoken languages
     $spoken_languages = '';
     if (!empty($details_data->spoken_languages)) {
         foreach ($details_data->spoken_languages as $language) {
             $spoken_languages .= $language->english_name . ', ';
         }
         $spoken_languages = rtrim($spoken_languages, ', ');  // Remove trailing comma
     } else {
         $spoken_languages = 'Not Declared';
     }

    // Display Budget and Revenue
    // $budget = !empty($details_data->budget) ? number_format($details_data->budget, 2) : 'Not Declared';
    // $revenue = !empty($details_data->revenue) ? number_format($details_data->revenue, 2) : 'Not Declared';
    $vote_average = !empty($details_data->vote_average) ? number_format($details_data->vote_average, 1) : 'N/A';
    $vote_count = !empty($details_data->vote_count) ? $details_data->vote_count : 'No';
    $show_popularity = !empty($details_data->popularity) ? $details_data->popularity : 'Not Declared';
    $show_status = !empty($details_data->status) ? $details_data->status : 'Not Declared';
    $show_type = !empty($details_data->type) ? $details_data->type : 'Not Declared';
    



    echo "<div class='tv-show-details'>
        <p><strong>Vote Average:</strong> $vote_average/10</p>
            <p><strong>Vote Count:</strong> $vote_count Votes</p>
            <p><strong>Show Popularity:</strong> $show_popularity </p>
            <p><strong>Show Status:</strong> $show_status</p>
            <p><strong>Show Type:</strong> $show_type</p>
            <p><strong>Spoken Languages:</strong> $spoken_languages</p>
          </div>";
}
add_shortcode('tv_show_details', 'get_show_details_shortcode');

function now_playing_movies_shortcode( $atts ) {
    // Your TMDB API key - you can replace 'your_api_key_here' with your actual TMDB API key
    $api_key = fw_get_db_ext_settings_option( 'ht-movie', 'api-key', NULL );

    // API URL to get "Now Playing" movies
    $url = "https://api.themoviedb.org/3/movie/now_playing?api_key=" . $api_key . "&language=en-US&page=1";

    // Fetch data from the TMDB API
    $response = wp_remote_get( $url );
    $movies = json_decode( wp_remote_retrieve_body( $response ), true );

    // Check if there are movies in the response
    if ( empty( $movies['results'] ) ) {
        return '<p>No movies found.</p>';
    }

    // Start output buffering
    ob_start();
    ?>
<div class="row movie-slider-items movie-slider-style-1">
    <div class="movie-grid-items">
        <?php foreach ( $movies['results'] as $movie ) : 
                $movie_id = $movie['id'];
                $title = $movie['title'];
                $overview = $movie['overview'];
                $release_date = $movie['release_date'];
                $poster_path = 'https://image.tmdb.org/t/p/w300' . $movie['poster_path'];
                $genres = $movie['genre_ids']; // Array of genre IDs
            ?>
        <div class="movie-grid-it">
            <div class="movie-thumbnail">
                <a href="https://www.themoviedb.org/movie/<?php echo esc_attr( $movie_id ); ?>" target="_blank">
                    <img src="<?php echo esc_url( $poster_path ); ?>" alt="<?php echo esc_attr( $title ); ?>">
                    <span class="readmore-btn"><?php echo esc_html__( "Read more", 'blockter' ); ?><i
                            class="ion-android-arrow-dropright"></i></span>
                </a>
            </div>
            <div class="movie-content">
                <div class="movie-genres">
                    <?php foreach ( $genres as $genre_id ) : ?>
                    <span class="genre"><?php echo esc_html( get_genre_name( $genre_id ) ); ?></span>
                    <?php endforeach; ?>
                </div>
                <h6 class="mv-title"><a href="https://www.themoviedb.org/movie/<?php echo esc_attr( $movie_id ); ?>"
                        target="_blank"><?php echo esc_html( $title ); ?></a></h6>
                <!-- <p class="overview"><?php echo esc_html( $overview ); ?></p>
                <p class="release-date">
                    <?php echo esc_html__( 'Release Date: ', 'blockter' ) . esc_html( $release_date ); ?></p> -->
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php
    // Return the buffered output
    return ob_get_clean();
}
add_shortcode( 'now_playing_movies', 'now_playing_movies_shortcode' );
function vc_map_now_playing_movies() {
    vc_map( array(
        'name'        => __( 'Now Playing Movies', 'blockter' ),
        'base'        => 'now_playing_movies',  // The shortcode name
        'description' => __( 'Displays now playing movies from TMDB', 'blockter' ),
        'category'    => __( 'Blockter Theme', 'blockter' ),
        'icon'        => 'icon-wpb-film', // Optional: Add an icon for the block
        'params'      => array(
            array(
                'type'        => 'textfield',
                'heading'     => __( 'TMDB API Key', 'blockter' ),
                'param_name'  => 'tmdb_api_key',
                'description' => __( 'Enter your TMDB API key here.', 'blockter' ),
            ),
        ),
    ) );
}
add_action( 'vc_before_init', 'vc_map_now_playing_movies' );
// Helper function to get the genre name from the genre ID (example)
function get_genre_name( $genre_id ) {
    // Add an array of genre IDs and their corresponding names
    $genres = array(
        28 => 'Action',
        12 => 'Adventure',
        16 => 'Animation',
        35 => 'Comedy',
        80 => 'Crime',
        99 => 'Documentary',
        18 => 'Drama',
        10751 => 'Family',
        14 => 'Fantasy',
        36 => 'History',
        27 => 'Horror',
        10402 => 'Music',
        9648 => 'Mystery',
        10749 => 'Romance',
        878 => 'Science Fiction',
        10770 => 'TV Movie',
        53 => 'Thriller',
        10752 => 'War',
        37 => 'Western'
    );

    return isset( $genres[ $genre_id ] ) ? $genres[ $genre_id ] : 'Unknown';
}
/**
 * [collection id="123"]
 * Renders a grid/list of all movies & shows belonging to a given mv_collection term,
 * ordered by release date ascending. Includes thumbnail, title, short description,
 * and top-3 cast members. A toggle switches between list and grid layouts.
 */
function blockter_collection_shortcode( $atts ) {
    $term_id = 0;
    $order_type = 'release'; 
    $is_keyword = false;

    // 1. ATTRIBUTE PARSING
    if ( is_array( $atts ) ) {
        foreach ( $atts as $key => $val ) {
            // Check if user specifically requested a Keyword ID
            if ($key === 'keyword' && is_numeric($val)) {
                $term_id = intval($val);
                $is_keyword = true;
                continue;
            }

            // Detect ID (Standalone number or id="123")
            if ( is_numeric( $val ) ) {
                $term_id = intval( $val );
            } elseif ( is_numeric( $key ) ) {
                $term_id = intval( $key );
            }
            
            // Capture the order (e.g., release_asc for chronological)
            if ( in_array($val, ['newfirst', 'modified', 'newadded', 'release', 'release_asc']) ) {
                $order_type = $val;
            }
        }
    }

    $atts = shortcode_atts( array( 
        'id'      => $term_id, 
        'keyword' => $is_keyword ? $term_id : 0, 
        'order'   => $order_type 
    ), $atts, 'collection' );

    // Decide which taxonomy to use
    $final_id = intval( $atts['keyword'] ?: $atts['id'] );
    $taxonomy = $atts['keyword'] ? 'mv_keyword' : 'mv_collection';
    $order_type = $atts['order'];

    if ( ! $final_id ) return '';

    $term = get_term( $final_id, $taxonomy );
    if ( ! $term || is_wp_error( $term ) ) return '';

    // 2. FETCH THE FULL LIST (No limit, to get all 63+ titles)
    $query = new WP_Query( array(
        'post_type'      => array( 'ht_movie', 'ht_show' ),
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $final_id,
            ),
        ),
    ) );

    if ( ! $query->have_posts() ) return '';
    $posts = $query->posts;

    // 3. CHRONOLOGICAL SORTING
    usort( $posts, function( $a, $b ) use ( $order_type ) {
        if ( $order_type === 'modified' ) {
            return strtotime( $b->post_modified ) - strtotime( $a->post_modified );
        }
        if ( $order_type === 'newadded' ) {
            return strtotime( $b->post_date ) - strtotime( $a->post_date );
        }
        
        $ts_a = strtotime( (string) fw_get_db_post_option( $a->ID, 'release_date' ) ) ?: 0;
        $ts_b = strtotime( (string) fw_get_db_post_option( $b->ID, 'release_date' ) ) ?: 0;
        
        // CHRONOLOGICAL: Oldest First (The "Watch Order" mode)
        if ( $order_type === 'release_asc' ) {
            return $ts_a - $ts_b;
        }

        // DEFAULT: Newest First
        return $ts_b - $ts_a;
    } );

    $uid = 'sc-col-' . $final_id . '-' . substr( md5( uniqid() ), 0, 6 );
    ob_start();
    ?>
    <div class="sc-collection-wrap" id="<?php echo esc_attr( $uid ); ?>">
        <div class="sc-collection-toolbar">
            <h3 class="sc-collection-title"><?php echo esc_html( $term->name ); ?></h3>
            <div class="sc-collection-toggle">
                <button class="sc-btn-list active" title="List view"><i class="ion-ios-list-outline"></i></button>
                <button class="sc-btn-grid" title="Grid view"><i class="ion-grid"></i></button>
            </div>
        </div>
        <div class="sc-collection-items sc-view-list">
            <?php foreach ( $posts as $item ) :
                $permalink    = get_permalink( $item->ID );
                $thumb_id     = get_post_thumbnail_id( $item->ID );
                $release_date = fw_get_db_post_option( $item->ID, 'release_date' );
                $overview     = fw_get_db_post_option( $item->ID, 'overview' );
                // $desc_short   = $overview ? mb_strimwidth( wp_strip_all_tags( $overview ), 0, 200, '&hellip;' ) : '';
                // Check if the function exists before calling it
                if ( function_exists('mb_strimwidth') ) {
                    $desc_short = $overview ? mb_strimwidth( wp_strip_all_tags( $overview ), 0, 200, '&hellip;' ) : '';
                } else {
                    // Fallback: use a basic substring function if mbstring is missing
                    $clean_text = wp_strip_all_tags( $overview );
                    $desc_short = ( strlen( $clean_text ) > 200 ) ? substr( $clean_text, 0, 200 ) . '&hellip;' : $clean_text;
                }
            ?>
            <div class="sc-collection-item">
                <div class="sc-item-thumb">
                    <a href="<?php echo esc_url( $permalink ); ?>">
                        <?php if ( $thumb_id ) echo wp_get_attachment_image( $thumb_id, 'medium' ); else echo '<div class="sc-no-thumb"></div>'; ?>
                    </a>
                </div>
                <div class="sc-item-content">
                    <h4 class="sc-item-title">
                        <a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $item->post_title ); ?></a>
                    </h4>
                    <?php if ( $desc_short ) : ?>
                        <p class="sc-item-desc"><?php echo esc_html( $desc_short ); ?></p>
                    <?php endif; ?>
                    <?php if ( $release_date ) : ?>
                        <p class="sc-item-date"><span>Release: </span><?php echo esc_html($release_date); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; wp_reset_postdata(); ?>
        </div>
    </div>
    <script>
    (function(){
        var wrap = document.getElementById('<?php echo esc_js($uid); ?>');
        if(!wrap) return;
        var listBtn = wrap.querySelector('.sc-btn-list'), gridBtn = wrap.querySelector('.sc-btn-grid'), items = wrap.querySelector('.sc-collection-items');
        listBtn.addEventListener('click', function(){ items.classList.replace('sc-view-grid', 'sc-view-list'); listBtn.classList.add('active'); gridBtn.classList.remove('active'); });
        gridBtn.addEventListener('click', function(){ items.classList.replace('sc-view-list', 'sc-view-grid'); gridBtn.classList.add('active'); listBtn.classList.remove('active'); });
    })();
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode( 'collection', 'blockter_collection_shortcode' );

// ==========================
// DISCOVERY AJAX LOAD
// ==========================
// ==========================
// DISCOVERY PAGE (AJAX)
// ==========================

// Load Movies
add_action('wp_ajax_load_movies', 'load_movies');
add_action('wp_ajax_nopriv_load_movies', 'load_movies');

function load_movies() {

    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $genre = isset($_POST['genre']) ? $_POST['genre'] : '';

    $args = [
        'post_type' => ['ht_movie', 'ht_show'],
        'posts_per_page' => 12,
        'paged' => $paged,
    ];

    // Genre filter
    if (!empty($genre)) {
        $args['tax_query'][] = [
            'taxonomy' => 'mv_genre',
            'field' => 'slug',
            'terms' => $genre
        ];
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {

        while ($query->have_posts()) {
            $query->the_post();

            echo '<div class="movie-card">';
            echo '<a href="'.get_permalink().'">';

            if (has_post_thumbnail()) {
                echo get_the_post_thumbnail(get_the_ID(), 'medium');
            }

            echo '<h4>'.get_the_title().'</h4>';
            echo '</a>';
            echo '</div>';
        }

    } else {
        echo '<p>No results found</p>';
    }

    wp_die();
}


// Load Genres
add_action('wp_ajax_get_genres', 'get_genres');
add_action('wp_ajax_nopriv_get_genres', 'get_genres');

function get_genres() {

    $terms = get_terms([
        'taxonomy' => 'mv_genre',
        'hide_empty' => false
    ]);

    foreach ($terms as $term) {
        echo '<div class="filter-item" data-slug="'.$term->slug.'">'.$term->name.'</div>';
    }

    wp_die();
}

// Hide the header search bar only on the My Account page
function blockter_hide_header_search_on_my_account() {
    if ( is_page( 'my-account' ) ) {
        echo '<style>
            .header-search-form {
                display:none;
            }
        </style>';
    }
}
add_action( 'wp_head', 'blockter_hide_header_search_on_my_account', 999 );



// =====================================
// NETWORKS GRID SHORTCODE
// =====================================
function networks_grid_shortcode() {

    // Get terms from 'networks' taxonomy (limit to 6)
    $terms = get_terms([
        'taxonomy'   => 'networks',
        'hide_empty' => true,   // Only show terms with posts
        'number'     => 6,      // Limit output (good for performance)
    ]);

    // Return message if no terms found or error
    if (empty($terms) || is_wp_error($terms)) {
        return '<p>No networks found.</p>';
    }

    // Start output buffering
    ob_start(); ?>

    <div class="networks-grid-section">
        <h2 class="section-title">Networks</h2>

        <div class="networks-grid">
            <?php foreach ($terms as $term) :

                // Get term archive link
                $link = get_term_link($term);

                // Get ACF field (logo image)
                $logo_url = get_field('network_logo', $term);
            ?>

                <!-- Network Card -->
                <a href="<?php echo esc_url($link); ?>"
                   class="network-card"
                   title="<?php echo esc_attr($term->name); ?>">

                    <?php if ($logo_url) : ?>
                        <!-- Show logo if available -->
                        <img src="<?php echo esc_url($logo_url); ?>"
                             alt="<?php echo esc_attr($term->name); ?>"
                             loading="lazy" />
                    <?php endif; ?>

                    <!-- Fallback text (always visible currently) -->
                    <span class="network-name-fallback">
                        <?php echo esc_html($term->name); ?>
                    </span>

                </a>

            <?php endforeach; ?>
        </div>
    </div>

    <?php
    return ob_get_clean(); // Return buffered HTML
}
add_shortcode('networks_grid', 'networks_grid_shortcode');


// =====================================
// COLLECTIONS GRID SHORTCODE — Style C
// =====================================
function collections_grid_shortcode() {
    $collection_ids = [
        12237, // Harry Potter
        7904,  // Lord of the Rings
        11424, // Avatar
    ];

    $terms = get_terms([
        'taxonomy'   => 'mv_collection',
        'hide_empty' => true,
        'include'    => $collection_ids,
        'orderby'    => 'include',
    ]);

    if (empty($terms) || is_wp_error($terms)) return '';

    ob_start();
    ?>
    <div class="collection-sec">
        <div class="collection-sec__grid">
            <?php foreach ($terms as $term) :
                $link       = get_term_link($term);
                $fw_options = get_term_meta($term->term_id, 'fw_options', true);
                $fw_data    = maybe_unserialize($fw_options);
                $thumb_url  = isset($fw_data['background_image']['url'])
                    ? $fw_data['background_image']['url'] : '';
            ?>
                <a href="<?php echo esc_url($link); ?>" class="collection-sec__card">

                    <span class="collection-sec__accent"></span>

                    <?php if ($thumb_url) : ?>
                        <img class="collection-sec__img"
                             src="<?php echo esc_url($thumb_url); ?>"
                             alt="<?php echo esc_attr($term->name); ?>"
                             loading="lazy" />
                    <?php else : ?>
                        <div class="collection-sec__no-img"></div>
                    <?php endif; ?>

                    <div class="collection-sec__overlay"></div>

                    <div class="collection-sec__content">
                        <span class="collection-sec__count"><?php echo intval($term->count); ?> titles</span>
                        <span class="collection-sec__name"><?php echo esc_html($term->name); ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('collections_grid', 'collections_grid_shortcode');




// =====================================
// KEYWORDS GRID SHORTCODE
// =====================================
function keywords_grid_shortcode($atts) {
    // 1. Setup Attributes
    $atts = shortcode_atts(array(
        'number' => 10,
        'order'  => 'count', // Default: most used keywords
    ), $atts, 'keywords_grid');

    $orderby = 'count';
    $order   = 'DESC';

    // 2. Sorting Logic
    if ($atts['order'] === 'name') {
        $orderby = 'name';
        $order   = 'ASC';
    } elseif ($atts['order'] === 'newest') {
        $orderby = 'id';
        $order   = 'DESC';
    }

    // Get the terms based on user preference
    $terms = get_terms([
        'taxonomy'   => 'mv_keyword',
        'hide_empty' => false,
        'number'     => intval($atts['number']),
        'orderby'    => $orderby,
        'order'      => $order,
    ]);

    if (empty($terms) || is_wp_error($terms)) return '';

    ob_start(); ?>
    <div class="keywords-grid-section">
        <div class="keywords-grid">
            <?php foreach ($terms as $term) : 
                $link = get_term_link($term);
            ?>
                <a href="<?php echo esc_url($link); ?>" class="keyword-card">
                    <?php echo esc_html($term->name); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('keywords_grid', 'keywords_grid_shortcode');


// =====================================
// GENRES GRID SHORTCODE
// =====================================
function genres_grid_shortcode() {

    $terms = get_terms([
        'taxonomy'   => 'mv_genre',
        'hide_empty' => true,
        'number'     => 5,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ]);

    if (empty($terms) || is_wp_error($terms)) return '';

    ob_start();

    $first = true;
    ?>
    <div class="genres-grid-section">
        <div class="cb-binge-layout">

            <?php foreach ($terms as $term) :
                $link       = get_term_link($term);
                $fw_options = get_term_meta($term->term_id, 'fw_options', true);
                $fw_data    = maybe_unserialize($fw_options);
                $thumb_url  = isset($fw_data['background_image']['url'])
                    ? $fw_data['background_image']['url'] : '';
            ?>

                <?php if ($first) : ?>
                <!-- BIG CARD -->
                <a href="<?php echo esc_url($link); ?>" class="cb-card cb-card-large">
                    <?php if ($thumb_url) : ?>
                        <img src="<?php echo esc_url($thumb_url); ?>"
                             alt="<?php echo esc_attr($term->name); ?>"
                             loading="lazy"/>
                    <?php else : ?>
                        <div class="cb-no-img"></div>
                    <?php endif; ?>
                    <div class="cb-overlay"></div>
                    <div class="cb-content">
                        <span class="cb-count"><?php echo $term->count; ?> titles</span>
                        <span class="cb-name"><?php echo esc_html($term->name); ?></span>
                    </div>
                </a>
                <!-- SMALL CARDS COLUMN -->
                <div class="cb-cards-small">
                <?php $first = false; else : ?>

                <!-- SMALL CARD -->
                <a href="<?php echo esc_url($link); ?>" class="cb-card cb-card-small">
                    <?php if ($thumb_url) : ?>
                        <img src="<?php echo esc_url($thumb_url); ?>"
                             alt="<?php echo esc_attr($term->name); ?>"
                             loading="lazy"/>
                    <?php else : ?>
                        <div class="cb-no-img"></div>
                    <?php endif; ?>
                    <div class="cb-overlay"></div>
                    <div class="cb-content">
                        <span class="cb-count"><?php echo $term->count; ?> titles</span>
                        <span class="cb-name"><?php echo esc_html($term->name); ?></span>
                    </div>
                </a>

                <?php endif; ?>

            <?php endforeach; ?>

            </div><!-- .cb-cards-small -->
        </div><!-- .cb-binge-layout -->
    </div>

    <?php
    return ob_get_clean();
}
add_shortcode('genres_grid', 'genres_grid_shortcode');
function stars_slider_custom() {

    static $swiper_loaded = false;

    // Swiper assets
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js', array(), null, true);

    // ✅ Load JS only once
    if (!$swiper_loaded) {

        wp_add_inline_script('swiper-js', '
        window.addEventListener("load", function () {

          if (typeof Swiper === "undefined") return;

          document.querySelectorAll(".stars-section").forEach(function(section) {

            const slider = section.querySelector(".stars-slider");
            const nextBtn = section.querySelector(".control-btn.next");
            const prevBtn = section.querySelector(".control-btn.prev");

            if (!slider || !nextBtn || !prevBtn) return;

            new Swiper(slider, {
              spaceBetween: 16,
              loop: false,                 // ❌ no repeat
              speed: 600,                 // smooth animation
              watchOverflow: true,        // disables if less slides

              navigation: {
                nextEl: nextBtn,
                prevEl: prevBtn,
              },

              breakpoints: {
                0: {
                  slidesPerView: 2,
                  spaceBetween: 12
                },
                768: {
                  slidesPerView: 3,
                  spaceBetween: 16
                },
                1024: {
                  slidesPerView: 5,
                  spaceBetween: 16
                }
              }
            });

          });

        });
        ');

        $swiper_loaded = true;
    }

    // Actor Data
    $actors = [
        ["name"=>"Zendaya","img"=>"https://image.tmdb.org/t/p/w300_and_h450_bestv2/3WdOloHpjtjL96uVOhFRRCcYSwq.jpg"],
        ["name"=>"Jaafar Jackson","img"=>"https://image.tmdb.org/t/p/w300_and_h450_bestv2/1C5M3HHFohOnp1PwRDTustVJcVc.jpg"],
        ["name"=>"Tom Hanks","img"=>"https://image.tmdb.org/t/p/w300_and_h450_bestv2/xndWFsBlClOJFRdhSt4NBwiPq2o.jpg"],
        ["name"=>"Milly Alcock","img"=>"https://image.tmdb.org/t/p/w300_and_h450_bestv2/deSE6C5LlgBCYcIoMDcfBoubEx3.jpg"],
        ["name"=>"Robert De Niro","img"=>"https://image.tmdb.org/t/p/w300_and_h450_bestv2/cT8htcckIuyI1Lqwt1CvD02ynTh.jpg"],
        ["name"=>"Anna Sawai","img"=>"https://image.tmdb.org/t/p/w300_and_h450_bestv2/6uFaCOupDTPRnTiedveTUvjOikC.jpg"],
        ["name"=>"Priyanka Chopra Jonas","img"=>"https://image.tmdb.org/t/p/w300_and_h450_bestv2/9n2n3fUFI553HKH0CEAb2TPLtCx.jpg"],
        ["name"=>"Sigourney Weaver","img"=>"https://image.tmdb.org/t/p/w300_and_h450_bestv2/wTSnfktNBLd6kwQxgvkqYw6vEon.jpg"],
        ["name"=>"Samuel L. Jackson","img"=>"https://image.tmdb.org/t/p/w300_and_h450_bestv2/nCJJ3NVksYNxIzEHcyC1XziwPVj.jpg"],
        ["name"=>"Jenna Ortega","img"=>"https://image.tmdb.org/t/p/w300_and_h450_bestv2/dQb6COORkv8liT5FMtXdHuvjUsb.jpg"]
    ];

    ob_start();
    ?>

    <div class="stars-section">

      <div class="stars-header">
        <div class="control-nav">
          <button class="control-btn prev">&#10094;</button>
          <button class="control-btn next">&#10095;</button>
        </div>
      </div>

      <div class="swiper stars-slider">
        <div class="swiper-wrapper">

        <?php foreach ($actors as $index => $actor): 
            $slug = sanitize_title($actor['name']);
            $link = home_url('/actor/' . $slug);
        ?>

        <div class="swiper-slide">
          <a href="<?php echo esc_url($link); ?>" class="star-card">

            <img 
              src="<?php echo esc_url($actor['img']); ?>" 
              alt="<?php echo esc_attr($actor['name']); ?>"
              loading="lazy"
            >

            <?php if ($index % 2 == 0): ?>
              <div class="badge">TRENDING</div>
            <?php endif; ?>

            <div class="overlay"></div>

            <div class="content">
              <h4><?php echo esc_html($actor['name']); ?></h4>
              <p>Featured Actor</p>
            </div>

          </a>
        </div>

        <?php endforeach; ?>

        </div>
      </div>

    </div>

    <?php
    return ob_get_clean();
}

add_shortcode('stars_slider', 'stars_slider_custom');
// ================================
// 🔥 LATEST MOVIES & TV SHORTCODE (FINAL)
// ================================
function latest_movies_tv_slider() {

    $args = array(
        'post_type'      => array('ht_movie', 'ht_show'),
        'posts_per_page' => 12,
        'post_status'    => 'publish'
    );

    $query = new WP_Query($args);

    if (!$query->have_posts()) return '';

    ob_start();
    ?>

    <div class="latest-movies-wrap"> <!-- ✅ changed to class (important for multiple use) -->

      <div class="lm-header">
        <div class="control-nav">
          <button class="control-btn prev">&#10094;</button>
          <button class="control-btn next">&#10095;</button>
        </div>
      </div>

      <div class="lm-slider-outer">
        <div class="lm-slider-track">

        <?php while ($query->have_posts()): $query->the_post();

            if (!has_post_thumbnail()) continue;

            $image  = get_the_post_thumbnail_url(get_the_ID(), 'medium');
            $title  = get_the_title();
            $rating = get_post_meta(get_the_ID(), 'blockter_rating', true);
            $type   = (get_post_type() === 'ht_movie') ? 'Movie' : 'TV Series';
        ?>

        <a href="<?php the_permalink(); ?>" class="lm-card">

          <div class="lm-poster-wrap">
            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy">

            <?php if (!empty($rating)): ?>
              <div class="lm-rating">
                <span class="lm-star">★</span>
                <?php echo esc_html($rating); ?>
              </div>
            <?php endif; ?>
          </div>

          <div class="lm-info">
            <p class="lm-post-title"><?php echo esc_html($title); ?></p>
            <span class="lm-type"><?php echo esc_html($type); ?></span>
          </div>

        </a>

        <?php endwhile; wp_reset_postdata(); ?>

        </div>
      </div>

    </div>

    <!-- ✅ FIXED JS -->
    <script>
    document.addEventListener("DOMContentLoaded", function () {

      document.querySelectorAll(".latest-movies-wrap").forEach(function(wrap){

        const track = wrap.querySelector(".lm-slider-track");
        const next  = wrap.querySelector(".control-btn.next");
        const prev  = wrap.querySelector(".control-btn.prev");

        if (!track || !next || !prev) return;

        let offset = 0;

        function getCardWidth() {
          const card = track.querySelector(".lm-card");
          if (!card) return 0;

          const style = window.getComputedStyle(track);
          const gap = parseInt(style.columnGap || style.gap || 16);

          return card.offsetWidth + gap;
        }

        function getMaxScroll() {
          return track.scrollWidth - track.parentElement.offsetWidth;
        }

        function updateSlider() {
          track.style.transform = "translateX(-" + offset + "px)";
        }

        next.addEventListener("click", function () {
          const cardW = getCardWidth();
          const max   = getMaxScroll();

          offset += cardW * 2;

          if (offset > max) offset = max;

          updateSlider();
        });

        prev.addEventListener("click", function () {
          const cardW = getCardWidth();

          offset -= cardW * 2;

          if (offset < 0) offset = 0;

          updateSlider();
        });

        window.addEventListener("resize", function () {
          offset = 0;
          updateSlider();
        });

      });

    });
    </script>

    <?php
    return ob_get_clean();
}

add_shortcode('latest_movies_tv', 'latest_movies_tv_slider');

// SHORT CATEGORY FUNCTION
function ins_short_cat($name){
    $map = [
        'news & returning tv' => 'TV',
        'entertainment buzz' => 'Buzz',
        'movie news' => 'Movie',
        'tv news' => 'TV',
        'tv series' => 'TV',
        'breaking news' => 'News',
        'latest news' => 'News'
    ];

    $key = strtolower(trim($name));

    if(isset($map[$key])){
        return $map[$key];
    }

    // fallback → last word
    if(strlen($name) > 12){
        $words = explode(' ', $name);
        return end($words);
    }

    return $name;
}


// SHORTCODE: [ins_featured]
function insomniacs_featured_layout() {
    ob_start();

    $query = new WP_Query([
        'posts_per_page' => 4,
        'ignore_sticky_posts' => true
    ]);

    if ($query->have_posts()) :

        $count = 0;

        echo '<div class="ins-featured-wrap">';

        while ($query->have_posts()) : $query->the_post();

            // BIG LEFT POST
            if ($count == 0) {
                ?>
                <div class="ins-featured-left">
                    <a href="<?php the_permalink(); ?>" class="ins-big-card">
                        <?php the_post_thumbnail('large'); ?>
                        <div class="ins-overlay">
                            <span class="ins-badge">FEATURED</span>
                            <h2><?php the_title(); ?></h2>
                            <p><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
                        </div>
                    </a>
                </div>

                <div class="ins-featured-right">
                <?php
            } else {
                // RIGHT POSTS
                $cat = get_the_category();
                $cat_name = !empty($cat) ? ins_short_cat($cat[0]->name) : '';
                ?>
                <a href="<?php the_permalink(); ?>" class="ins-small-card">
                    <?php the_post_thumbnail('medium'); ?>
                    <div class="ins-overlay">
                        <?php if($cat_name): ?>
                            <span class="ins-badge small"><?php echo esc_html($cat_name); ?></span>
                        <?php endif; ?>
                        <h3><?php the_title(); ?></h3>
                    </div>
                </a>
                <?php
            }

            $count++;
        endwhile;

        echo '</div>'; // right
        echo '</div>'; // wrap

        wp_reset_postdata();

    endif;

    return ob_get_clean();
}
add_shortcode('ins_featured', 'insomniacs_featured_layout');



// SHORTCODE: [insomniacs_trending]

function insomniacs_trending_shortcode() {
    ob_start();

    $query = new WP_Query([
        'posts_per_page' => 5,
        'orderby' => 'comment_count',
        'order' => 'DESC',
        'ignore_sticky_posts' => true
    ]);

    if ($query->have_posts()) :

        echo '<div id="insomniacs-trending">';


        echo '<ul class="itrend-list">';

        $count = 1;

        // ✅ FIXED HOT POSITIONS (Figma style)
        $hot_indexes = [0,1,4];

        $i = 0;

        while ($query->have_posts()) : $query->the_post();

            $cat = get_the_category();
            $cat_name = !empty($cat) ? ins_short_cat($cat[0]->name) : '';

            echo '<li>';
            echo '<a href="'.get_permalink().'" class="itrend-item">';

            echo '<span class="itrend-num">'.$count.'</span>';

            echo '<div class="itrend-info">';
            echo '<p class="itrend-post-title">'.get_the_title().'</p>';

            if($cat_name){
                echo '<span class="itrend-cat">'.$cat_name.'</span>';
            }

            echo '</div>';

            // 🔥 HOT badge (fixed positions)
            if(in_array($i, $hot_indexes)){
                echo '<span class="itrend-hot">HOT</span>';
            }

            echo '</a>';
            echo '</li>';

            $count++;
            $i++;

        endwhile;

        echo '</ul>';
        echo '</div>';

        wp_reset_postdata();

    endif;

    return ob_get_clean();
}

add_shortcode('insomniacs_trending', 'insomniacs_trending_shortcode');
// add_action('wp_loaded', function() {
//     ob_start(function($buffer) {
//         // 1. Fix the Author Field (Swap creator for author and Thing for Person)
//         $buffer = preg_replace('/itemprop="creator">/', 'itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name">', $buffer);
//         $buffer = str_replace('</strong>', '</span></strong>', $buffer);
        
//         // 2. Fix the Text Field
//         $buffer = str_replace('itemprop="about"', 'itemprop="text"', $buffer);
        
//         // 3. Fix Nesting (Wrap the Comment inside a CreativeWork)
//         $buffer = str_replace(
//             'itemtype="http://schema.org/Comment"', 
//             'itemtype="http://schema.org/Comment" itemscope itemtype="http://schema.org/CreativeWork"', 
//             $buffer
//         );
        
//         return $buffer;
//     });
// });

// ==========================================
// UNIFIED TEMPLATE REDIRECT CONTROLLER
// Handles Secure HTML Schema Updates & Homepage Settings
// ==========================================
add_action('template_redirect', function() {

    // 1. Handle Homepage Comment Settings Safely Upfront
    if (is_front_page() || is_home()) {
        add_filter('comments_open', '__return_false', 20);
        add_filter('pings_open', '__return_false', 20);
        remove_action('comment_form', 'comment_form');
    }

    // 2. 🚫 SECURE GUARD FOR SITEMAPS/FEEDS: Exit early before initializing output buffers
    if (
        is_admin() ||
        is_feed() ||
        (defined('REST_REQUEST') && REST_REQUEST) ||
        (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'sitemap') !== false) ||
        (isset($_SERVER['REQUEST_URI']) && substr($_SERVER['REQUEST_URI'], -4) === '.xml')
    ) {
        return;
    }

    // 3. Initialize Secure Output Buffering to Correct Schema Fields Safely
    ob_start(function($buffer) {
        // Stop processing if payload is empty or lacks foundational HTML roots
        if (empty($buffer) || false === strpos($buffer, '<html')) {
            return $buffer;
        }

        // Fix Schema Author Field dynamically inside structural strong elements
        if (strpos($buffer, 'itemprop="creator"') !== false) {
            $buffer = preg_replace(
                '/<strong[^>]*itemprop="creator">([^<]*)<\/strong>/i',
                '<strong itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name">$1</span></strong>',
                $buffer
            );
        }

        // Correct malformed property names inside core item scopes
        if (strpos($buffer, 'itemprop="about"') !== false) {
            $buffer = str_replace('itemprop="about"', 'itemprop="text"', $buffer);
        }

        // Clean up recursive structure errors inside comment elements safely
        if (strpos($buffer, 'itemtype="http://schema.org/Comment"') !== false) {
            $buffer = str_replace(
                'itemtype="http://schema.org/Comment"',
                'itemscope itemtype="http://schema.org/Comment"',
                $buffer
            );
        }

        return $buffer;
    });
});

add_filter('comments_template', 'disable_homepage_comments_template');
function disable_homepage_comments_template($template) {
    if (is_front_page() || is_home()) {
        return dirname(__FILE__) . '/empty-comments.php';
    }
    return $template;
}

function ht_rankmath_video_sitemap($videos, $post_id) {
    $video = fw_get_db_post_option($post_id, 'video');
    if (empty($video)) {
        return $videos;
    }

    $youtube_id = '';
    if (is_array($video)) {
        $last = end($video);
        if (is_string($last)) {
            $youtube_id = trim($last);
        } elseif (is_array($last) && !empty($last['movie_iframe'])) {
            preg_match(
                '/(?:youtube\.com\/embed\/|youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/',
                $last['movie_iframe'],
                $matches
            );
            if (!empty($matches[1])) {
                $youtube_id = $matches[1];
            }
        }
    } elseif (is_string($video)) {
        $youtube_id = trim($video);
    }

    if (!$youtube_id) {
        return $videos;
    }

    $thumb = "https://img.youtube.com/vi/{$youtube_id}/hqdefault.jpg";
    $youtube_api_key = fw_get_db_ext_settings_option('ht-movie', 'api-key', NULL);
    $url = add_query_arg([
        'part' => 'snippet',
        'id'   => $youtube_id,
        'key'  => $youtube_api_key,
    ], 'https://www.googleapis.com/youtube/v3/videos');

    $response = wp_remote_get($url, ['timeout' => 10]);
    $title = get_the_title($post_id);

    if (!is_wp_error($response)) {
        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (!empty($body['items'][0]['snippet']['title'])) {
            $title = $body['items'][0]['snippet']['title'];
        }
    }

    $videos[] = [
        'content_loc'   => "https://www.youtube.com/watch?v={$youtube_id}",
        'title'         => wp_strip_all_tags($title),
        'description'   => wp_strip_all_tags(get_the_excerpt($post_id)),
        'thumbnail_loc' => esc_url($thumb),
    ];

    return $videos;
}

add_filter('rank_math/sitemap/video_post_types', function($post_types) {

    $post_types[] = 'ht_movie';
    $post_types[] = 'ht_show';
    return array_unique($post_types);

});
//generate_video_schema
add_filter('rank_math/sitemap/video_data', 'blockter_video_sitemap_data', 10, 2);
function blockter_video_sitemap_data($videos, $post_id) {

    if (!in_array(get_post_type($post_id), ['ht_movie', 'ht_show'])) {
        return $videos;
    }
        $video_ids = fw_get_db_post_option($post_id, 'video');
    if (empty($video_ids) || !is_array($video_ids)) {
        return $videos;
    }
     $video_cache = get_post_meta($post_id, '_youtube_video_cache', true);
      if (!is_array($video_cache)) {
        $video_cache = [];
    }
     $youtube_api_key = fw_get_db_ext_settings_option( 'ht-movie', 'youtube-api-key', NULL );
      foreach ($video_ids as $video_id) {

        if (empty($video_id)) {
            continue;
        }
        $title = '';
        $description = '';
         if (
            !empty($video_cache[$video_id]['title']) &&
            !empty($video_cache[$video_id]['description'])
        ) {

            $title = $video_cache[$video_id]['title'];
            $description = $video_cache[$video_id]['description'];

        } else {
             $url = add_query_arg([
                'part' => 'snippet',
                'id'   => $video_id,
                'key'  => $youtube_api_key
            ], 'https://www.googleapis.com/youtube/v3/videos');

            $response = wp_remote_get($url, [
                'timeout' => 10
            ]);
             if (is_wp_error($response)) {
                continue;
            }
              $body = json_decode(
                wp_remote_retrieve_body($response),
                true
            );

            if (empty($body['items'][0]['snippet'])) {
                continue;
            }
             $snippet = $body['items'][0]['snippet'];
            $title = $snippet['title'] ?? '';
            $description = $snippet['description'] ?? '';
             $video_cache[$video_id] = [
                'title' => $title,
                'description' => $description
            ];

        }
        if (empty($title)) {
            continue;
        }
         $thumbnail = "https://img.youtube.com/vi/{$video_id}/hqdefault.jpg";
          $videos[] = [

            'title' => wp_strip_all_tags($title),

            'description' => wp_strip_all_tags($description),

            'thumbnail_loc' => esc_url($thumbnail),

            'content_loc' => "https://www.youtube.com/watch?v={$video_id}",

            'player_loc' => "https://www.youtube.com/embed/{$video_id}",

            'publication_date' => get_the_date('c', $post_id),

            'family_friendly' => 'yes'
        ];



      }
       update_post_meta($post_id, '_youtube_video_cache', $video_cache);

     return $videos;

}
add_filter('rank_math/video/parser_content', 'blockter_rankmath_video_parser', 10, 2);
function blockter_rankmath_video_parser($content, $post) {
    if (!in_array($post->post_type, ['ht_movie', 'ht_show'])) {
        return $content;
    }
    $videos = fw_get_db_post_option($post->ID, 'video');
     if (empty($videos) || !is_array($videos)) {
        return $content;
    }
     foreach ($videos as $video_id) {

        if (empty($video_id)) {
            continue;
        }

        $content .= "\n https://www.youtube.com/watch?v={$video_id} \n";
    }

    return $content;
}


// Enable the 'Skip Image' button in Image Attributes Pro > Bulk Updater > Tools
add_filter( 'iaff_debug_mode', '__return_true' );

//Lightspeed Cache Compatibility: Clear cache when a movie or show is updated
add_filter('litespeed_purge_tags', function($purge_tags, $is_private) {
if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'LSCWP_CTRL=purge') !== false) {
do_action('litespeed_debug2', 'Perserve manual purge action');
return $purge_tags;
}
return ['_nothing'];
}, 10, 2);


require_once get_template_directory() . '/functions-hero-integration.php';
require_once get_template_directory() . '/functions-schedule-integration.php';
require_once get_template_directory() . '/smooth-scroll-fix.php';
/**
 * Insomniacs Actor Profile Dynamic Taxonomy Integration
 */
if ( file_exists( get_template_directory() . '/taxonomy-mv_actor.php' ) ) {
    require_once get_template_directory() . '/taxonomy-mv_actor.php';
}

// Integrate premium high-fidelity single actor templates and backend administration
if ( file_exists( get_template_directory() . '/actor-page-template.php' ) ) {
    require_once get_template_directory() . '/actor-page-template.php';
}

// ==========================================================
// INSOMNIACS RELEASE PLANNER & ACTOR FOLLOW SYNC GATEWAYS
// ==========================================================

if ( ! function_exists( 'insom_v3_toggle_schedule_handler' ) ) {
    function insom_v3_toggle_schedule_handler() {
        if ( isset( $_POST['post_id'] ) ) {
            $post_id = sanitize_text_field( $_POST['post_id'] );
            $scheduled_cookie = isset( $_COOKIE['insom_scheduled'] ) ? sanitize_text_field( $_COOKIE['insom_scheduled'] ) : '';
            $scheduled = ! empty( $scheduled_cookie ) ? explode( ',', $scheduled_cookie ) : array();

            if ( ( $key = array_search( $post_id, $scheduled ) ) !== false ) {
                unset( $scheduled[$key] );
            } else {
                $scheduled[] = $post_id;
            }

            $cookie_val = implode( ',', $scheduled );
            setcookie( 'insom_scheduled', $cookie_val, time() + ( 30 * 24 * 60 * 60 ), '/', COOKIE_DOMAIN, is_ssl(), false );
            
            if ( is_user_logged_in() ) {
                $user_id = get_current_user_id();
                $meta_key = 'insom_scheduled_releases';
                $existing_sched = array_map( 'sanitize_text_field', (array) get_user_meta( $user_id, $meta_key ) );
                if ( in_array( $post_id, $existing_sched, true ) ) {
                    delete_user_meta( $user_id, $meta_key, $post_id );
                } else {
                    add_user_meta( $user_id, $meta_key, $post_id );
                }
            }
            
            wp_send_json_success( array(
                'post_id'   => $post_id,
                'scheduled' => array_values( $scheduled )
            ) );
        } else {
            wp_send_json_error( 'Invalid post parameters.' );
        }
        wp_die();
    }
    add_action( 'wp_ajax_insom_v3_toggle_schedule', 'insom_v3_toggle_schedule_handler' );
    add_action( 'wp_ajax_nopriv_insom_v3_toggle_schedule', 'insom_v3_toggle_schedule_handler' );
}

if ( ! function_exists( 'insom_v3_toggle_fav_handler' ) ) {
    function insom_v3_toggle_fav_handler() {
        if ( isset( $_POST['post_id'] ) ) {
            $post_id = sanitize_text_field( $_POST['post_id'] );
            $favs_cookie = isset( $_COOKIE['idc_favs'] ) ? sanitize_text_field( $_COOKIE['idc_favs'] ) : '';
            $favs = ! empty( $favs_cookie ) ? explode( ',', $favs_cookie ) : array();

            if ( ( $key = array_search( $post_id, $favs ) ) !== false ) {
                unset( $favs[$key] );
            } else {
                $favs[] = $post_id;
            }

            $cookie_val = implode( ',', $favs );
            setcookie( 'idc_favs', $cookie_val, time() + ( 30 * 24 * 60 * 60 ), '/', COOKIE_DOMAIN, is_ssl(), false );
            
            if ( is_user_logged_in() ) {
                $user_id = get_current_user_id();
                if ( is_numeric( $post_id ) ) {
                    $post_id_val = intval( $post_id );
                    $post_obj = get_post( $post_id_val );
                    if ( $post_obj ) {
                        $meta_key = ( $post_obj->post_type === 'ht_show' ) ? 'favourite_show_id' : 'favourite_mv_id';
                        $existing_favs = array_map( 'intval', (array) get_user_meta( $user_id, $meta_key ) );
                        if ( in_array( $post_id_val, $existing_favs, true ) ) {
                            delete_user_meta( $user_id, $meta_key, $post_id_val );
                        } else {
                            add_user_meta( $user_id, $meta_key, $post_id_val );
                        }
                    }
                } else {
                    $mock_type = 'ht_movie';
                    if ( $post_id === 'house-of-the-dragon' ) {
                        $mock_type = 'ht_show';
                    }
                    $meta_key = ( $mock_type === 'ht_show' ) ? 'favourite_show_id' : 'favourite_mv_id';
                    $existing_favs = array_map( 'sanitize_text_field', (array) get_user_meta( $user_id, $meta_key ) );
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

if ( ! function_exists( 'insom_v3_toggle_actor_follow_handler' ) ) {
    function insom_v3_toggle_actor_follow_handler() {
        if ( isset( $_POST['actor_slug'] ) ) {
            $actor_slug = sanitize_text_field( $_POST['actor_slug'] );
            
            $follows_cookie = isset( $_COOKIE['insom_followed_actors'] ) ? sanitize_text_field( $_COOKIE['insom_followed_actors'] ) : '';
            $follows = ! empty( $follows_cookie ) ? array_filter( array_map('trim', explode( ',', $follows_cookie ) ) ) : array();

            if ( ( $key = array_search( $actor_slug, $follows ) ) !== false ) {
                unset( $follows[$key] );
                $status = 'unfollowed';
            } else {
                $follows[] = $actor_slug;
                $status = 'followed';
            }

            $cookie_val = implode( ',', $follows );
            $cookie_domain = defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '';
            setcookie( 'insom_followed_actors', $cookie_val, time() + ( 30 * 24 * 60 * 60 ), '/', $cookie_domain, is_ssl(), false );
            
            if ( is_user_logged_in() ) {
                $user_id = get_current_user_id();
                $meta_key = 'insom_followed_actors';
                
                // Resilient meta extraction using our global parent/child helper
                $existing_follows = insom_extract_meta_values( $user_id, $meta_key );

                if ( in_array( $actor_slug, $existing_follows, true ) ) {
                    $existing_follows = array_diff( $existing_follows, array( $actor_slug ) );
                } else {
                    $existing_follows[] = $actor_slug;
                }
                
                // Clear all legacy database rows (including serialized or duplicates) for this meta key
                delete_user_meta( $user_id, $meta_key );
                
                // Reinstall clean individual rows to ensure perfect lookups and database searching support
                foreach ( array_unique( $existing_follows ) as $slug ) {
                    if ( ! empty( $slug ) ) {
                        add_user_meta( $user_id, $meta_key, $slug );
                    }
                }
            }
            
            wp_send_json_success( array(
                'actor_slug' => $actor_slug,
                'status'     => $status,
                'follows'    => array_values( $follows )
            ) );
        } else {
            wp_send_json_error( 'Invalid actor parameter.' );
        }
        wp_die();
    }
    add_action( 'wp_ajax_insom_v3_toggle_actor_follow', 'insom_v3_toggle_actor_follow_handler' );
    add_action( 'wp_ajax_nopriv_insom_v3_toggle_actor_follow', 'insom_v3_toggle_actor_follow_handler' );
}

// AUTOMATIC GUEST-TO-USER CLOUD SYNC ON LOGIN
add_action( 'wp_login', function( $user_login, $user ) {
    $user_id = $user->ID;

    // 1. Sync Favorites
    $favs_cookie = isset( $_COOKIE['idc_favs'] ) ? sanitize_text_field( $_COOKIE['idc_favs'] ) : '';
    if ( ! empty( $favs_cookie ) ) {
        $favs = explode( ',', $favs_cookie );
        foreach ( $favs as $post_id ) {
            $post_id = sanitize_text_field( trim( $post_id ) );
            if ( ! empty( $post_id ) ) {
                if ( is_numeric( $post_id ) ) {
                    $pid_val = intval( $post_id );
                    $post_obj = get_post( $pid_val );
                    if ( $post_obj ) {
                        $meta_key = ( $post_obj->post_type === 'ht_show' ) ? 'favourite_show_id' : 'favourite_mv_id';
                        $existing = array_map( 'intval', (array) get_user_meta( $user_id, $meta_key ) );
                        if ( ! in_array( $pid_val, $existing, true ) ) {
                            add_user_meta( $user_id, $meta_key, $pid_val );
                        }
                    }
                } else {
                    $mock_type = 'ht_movie';
                    if ( $post_id === 'house-of-the-dragon' ) {
                        $mock_type = 'ht_show';
                    }
                    $meta_key = ( $mock_type === 'ht_show' ) ? 'favourite_show_id' : 'favourite_mv_id';
                    $existing = array_map( 'sanitize_text_field', (array) get_user_meta( $user_id, $meta_key ) );
                    if ( ! in_array( $post_id, $existing, true ) ) {
                        add_user_meta( $user_id, $meta_key, $post_id );
                    }
                }
            }
        }
    }

    // 2. Sync Scheduled
    $sched_cookie = isset( $_COOKIE['insom_scheduled'] ) ? sanitize_text_field( $_COOKIE['insom_scheduled'] ) : '';
    if ( ! empty( $sched_cookie ) ) {
        $scheds = explode( ',', $sched_cookie );
        foreach ( $scheds as $post_id ) {
            $post_id = sanitize_text_field( trim( $post_id ) );
            if ( ! empty( $post_id ) ) {
                $existing = array_map( 'sanitize_text_field', (array) get_user_meta( $user_id, 'insom_scheduled_releases' ) );
                if ( ! in_array( $post_id, $existing, true ) ) {
                    add_user_meta( $user_id, 'insom_scheduled_releases', $post_id );
                }
            }
        }
    }

    // 3. Sync Followed Actors
    $follows_cookie = isset( $_COOKIE['insom_followed_actors'] ) ? sanitize_text_field( $_COOKIE['insom_followed_actors'] ) : '';
    if ( ! empty( $follows_cookie ) ) {
        $follows = explode( ',', $follows_cookie );
        foreach ( $follows as $actor_slug ) {
            $actor_slug = sanitize_text_field( trim( $actor_slug ) );
            if ( ! empty( $actor_slug ) ) {
                $existing = (array) get_user_meta( $user_id, 'insom_followed_actors' );
                if ( ! in_array( $actor_slug, $existing, true ) ) {
                    add_user_meta( $user_id, 'insom_followed_actors', $actor_slug );
                }
            }
        }
    }
}, 10, 2 );