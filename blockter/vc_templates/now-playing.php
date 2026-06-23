<?php
function now_playing_movies_shortcode( $atts ) {
// Fetch TMDB Now Playing movies
$tmdb_api_key = '15dca2c1c7736e21a054130987eb007b';
$now_playing_url = 'https://api.themoviedb.org/3/movie/now_playing?api_key=' . $tmdb_api_key . '&language=en-GB&page=1';

$response = wp_remote_get($now_playing_url);
$movies = json_decode(wp_remote_retrieve_body($response), true);

ob_start();

// Ensure the API returned results
if (!empty($movies['results'])): ?>
<div class="row movie-slider-items movie-slider-style-1">
    <div class="movie-grid-items">
        <?php foreach ($movies['results'] as $movie): 
                $movie_id = $movie['id'];
                $title = $movie['title'];
                $overview = $movie['overview'];
                $release_date = $movie['release_date'];
                $poster_path = 'https://image.tmdb.org/t/p/w300' . $movie['poster_path'];
                $genres = $movie['genre_ids']; // Array of genre IDs
            ?>
        <div class="movie-grid-it">
            <div class="movie-thumbnail">
                <a href="https://www.themoviedb.org/movie/<?php echo esc_attr($movie_id); ?>" target="_blank">
                    <img src="<?php echo esc_url($poster_path); ?>" alt="<?php echo esc_attr($title); ?>">
                    <span class="readmore-btn"><?php echo esc_html__("Read more", 'blockter'); ?><i
                            class="ion-android-arrow-dropright"></i></span>
                </a>
            </div>
            <div class="movie-content">
                <div class="movie-genres">
                    <?php foreach ($genres as $genre_id): ?>
                    <!-- Map genre ID to name -->
                    <span class="genre"><?php echo esc_html(get_genre_name($genre_id)); ?></span>
                    <?php endforeach; ?>
                </div>
                <h6 class="mv-title"><a href="https://www.themoviedb.org/movie/<?php echo esc_attr($movie_id); ?>"
                        target="_blank"><?php echo esc_html($title); ?></a></h6>
                <p class="overview"><?php echo esc_html($overview); ?></p>
                <p class="release-date">
                    <?php echo esc_html__('Release Date: ', 'blockter') . esc_html($release_date); ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php endif; 
 return ob_get_clean(); // Return the buffered output
                    }

                    add_shortcode( 'now_playing_movies', 'now_playing_movies_shortcode' );

?>