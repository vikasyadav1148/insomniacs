<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

/**
 * JSON-LD Structured Data
 *
 * Outputs Schema.org JSON-LD markup for:
 *   - ht_movie  single posts  → Movie
 *   - ht_show   single posts  → TVSeries
 *   - mv_actor  term archives → Person
 *   - mv_collection term archives → MovieSeries
 *   - standard  blog posts    → NewsArticle
 */

// ──────────────────────────────────────────────────────────────
// Main dispatcher – hooked early in wp_head
// ──────────────────────────────────────────────────────────────

function blockter_structured_data_output() {

	$schema = null;

	if ( is_singular( 'ht_movie' ) ) {
		$schema = blockter_schema_movie();
	} elseif ( is_singular( 'ht_show' ) ) {
		$schema = blockter_schema_tv_series();
	} elseif ( is_singular( 'post' ) ) {
		$schema = blockter_schema_news_article();
	} elseif ( is_tax( 'mv_actor' ) ) {
		$schema = blockter_schema_person();
	} elseif ( is_tax( 'mv_collection' ) ) {
		$schema = blockter_schema_movie_series();
	}

	if ( empty( $schema ) ) {
		return;
	}

	echo '<script type="application/ld+json">' . "\n";
	echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
	echo "\n" . '</script>' . "\n";
}
add_action( 'wp_head', 'blockter_structured_data_output', 5 );

// ──────────────────────────────────────────────────────────────
// Helpers
// ──────────────────────────────────────────────────────────────

/**
 * Read a single Unyson fw_options field from post meta.
 * Falls back to raw get_post_meta when Unyson is inactive.
 *
 * @param int    $post_id
 * @param string $key
 * @return mixed
 */
function blockter_sd_get_post_option( $post_id, $key ) {
	if ( function_exists( 'fw_get_db_post_option' ) ) {
		return fw_get_db_post_option( $post_id, $key );
	}
	$fw_options = get_post_meta( $post_id, 'fw_options', true );
	return isset( $fw_options[ $key ] ) ? $fw_options[ $key ] : '';
}

/**
 * Read Unyson fw_options from a taxonomy term.
 * Falls back to empty array when Unyson is inactive.
 *
 * @param int    $term_id
 * @param string $taxonomy
 * @return array
 */
function blockter_sd_get_term_option( $term_id, $taxonomy ) {
	if ( function_exists( 'fw_get_db_term_option' ) ) {
		return (array) fw_get_db_term_option( $term_id, $taxonomy );
	}
	return array();
}

/**
 * Convert a runtime in minutes to an ISO 8601 duration string.
 *
 * @param  mixed $minutes
 * @return string|null  e.g. "PT1H45M", "PT30M", or null if invalid.
 */
function blockter_sd_iso_duration( $minutes ) {
	$minutes = intval( $minutes );
	if ( $minutes <= 0 ) {
		return null;
	}
	$h = floor( $minutes / 60 );
	$m = $minutes % 60;
	if ( $h > 0 && $m > 0 ) {
		return 'PT' . $h . 'H' . $m . 'M';
	}
	if ( $h > 0 ) {
		return 'PT' . $h . 'H';
	}
	return 'PT' . $m . 'M';
}

/**
 * Return taxonomy term names for a post as a plain array.
 *
 * @param int    $post_id
 * @param string $taxonomy
 * @return array
 */
function blockter_sd_term_names( $post_id, $taxonomy ) {
	$terms = get_the_terms( $post_id, $taxonomy );
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return array();
	}
	return wp_list_pluck( $terms, 'name' );
}

/**
 * Return AggregateRating data for a post, or null if none available.
 *
 * @param int $post_id
 * @return array|null
 */
function blockter_sd_aggregate_rating( $post_id ) {
	if (
		! function_exists( 'fw_ext_feedback_stars_get_post_rating' ) ||
		! function_exists( 'fw_ext_feedback_stars_get_post_detailed_rating' )
	) {
		return null;
	}

	if ( ! comments_open( $post_id ) || ! get_comments_number( $post_id ) ) {
		return null;
	}

	$average     = fw_ext_feedback_stars_get_post_rating( $post_id );
	$detail      = fw_ext_feedback_stars_get_post_detailed_rating( $post_id );
	$rating_val  = isset( $average['average'] ) ? round( floatval( $average['average'] ), 1 ) : 0;
	$count       = intval( get_comments_number( $post_id ) );
	$best_rating = isset( $detail['stars'] ) ? count( $detail['stars'] ) : 5;

	if ( $rating_val <= 0 || $count < 1 ) {
		return null;
	}

	return array(
		'@type'       => 'AggregateRating',
		'ratingValue' => $rating_val,
		'ratingCount' => $count,
		'bestRating'  => $best_rating,
		'worstRating' => 1,
	);
}

/**
 * Build an array of Person nodes from a comma-separated names string.
 *
 * @param  string $names_string  e.g. "Christopher Nolan, Emma Thomas"
 * @return array
 */
function blockter_sd_person_list( $names_string ) {
	if ( empty( $names_string ) ) {
		return array();
	}
	$names  = array_filter( array_map( 'trim', explode( ',', $names_string ) ) );
	$result = array();
	foreach ( $names as $name ) {
		$result[] = array( '@type' => 'Person', 'name' => $name );
	}
	return $result;
}

// ──────────────────────────────────────────────────────────────
// Schema builders
// ──────────────────────────────────────────────────────────────

/**
 * Movie schema for singular ht_movie posts.
 *
 * @return array
 */
function blockter_schema_movie() {
	$id           = get_the_ID();
	$title        = get_the_title( $id );
	$url          = get_permalink( $id );
	$overview     = blockter_sd_get_post_option( $id, 'overview' );
	$runtime      = blockter_sd_get_post_option( $id, 'runtime' );
	$release_date = blockter_sd_get_post_option( $id, 'release_date' );
	$directors    = blockter_sd_get_post_option( $id, 'directors' );
	$genres       = blockter_sd_term_names( $id, 'mv_genre' );
	$poster_url   = get_the_post_thumbnail_url( $id, 'full' );

	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Movie',
		'name'     => $title,
		'url'      => $url,
	);

	if ( ! empty( $overview ) ) {
		$schema['description'] = wp_strip_all_tags( $overview );
	}
	if ( ! empty( $poster_url ) ) {
		$schema['image'] = $poster_url;
	}
	if ( ! empty( $release_date ) ) {
		$schema['datePublished'] = $release_date;
	}
	$duration = blockter_sd_iso_duration( $runtime );
	if ( $duration ) {
		$schema['duration'] = $duration;
	}

	// Director(s)
	$director_list = blockter_sd_person_list( $directors );
	if ( ! empty( $director_list ) ) {
		$schema['director'] = count( $director_list ) === 1 ? $director_list[0] : $director_list;
	}

	// Cast (mv_actor taxonomy terms)
	$actor_terms = get_the_terms( $id, 'mv_actor' );
	if ( ! empty( $actor_terms ) && ! is_wp_error( $actor_terms ) ) {
		$actors = array();
		foreach ( $actor_terms as $actor ) {
			$entry     = array( '@type' => 'Person', 'name' => $actor->name );
			$actor_url = get_term_link( $actor );
			if ( ! is_wp_error( $actor_url ) ) {
				$entry['url'] = $actor_url;
			}
			$actors[] = $entry;
		}
		$schema['actor'] = $actors;
	}

	if ( ! empty( $genres ) ) {
		$schema['genre'] = $genres;
	}

	$rating = blockter_sd_aggregate_rating( $id );
	if ( $rating ) {
		$schema['aggregateRating'] = $rating;
	}
	// =========================
	// TRAILER VIDEOOBJECT
	// =========================
	$video_object = blockter_sd_video_object($id);

	if ($video_object) {
		$schema['trailer'] = $video_object;
	}

	return $schema;
}
function blockter_sd_video_object($post_id) {

	$video = blockter_sd_get_post_option($post_id, 'video');

	if (empty($video)) {
		return null;
	}

	$youtube_id = '';
	$video_title = get_the_title($post_id) . ' Trailer';

	// =========================
	// EXTRACT YOUTUBE ID
	// =========================
	if (is_array($video)) {

		$last = end($video);

		// iframe format
		if (is_array($last) && !empty($last['movie_iframe'])) {

			preg_match(
				'/(?:youtube\.com\/embed\/|youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/',
				$last['movie_iframe'],
				$matches
			);

			if (!empty($matches[1])) {
				$youtube_id = $matches[1];
			}

			if (!empty($last['movie_title'])) {
				$video_title = $last['movie_title'];
			}
		}

		// direct youtube ID
		elseif (is_string($last)) {
			$youtube_id = trim($last);
		}
	}

	elseif (is_string($video)) {
		$youtube_id = trim($video);
	}

	if (!$youtube_id) {
		return null;
	}

	// =========================
	// DESCRIPTION
	// =========================
	$description = blockter_sd_get_post_option($post_id, 'overview');

	if (!$description) {
		$description = get_the_excerpt($post_id);
	}

	return array(

		'@type' => 'VideoObject',

		'name' => wp_strip_all_tags($video_title),

		'description' => wp_strip_all_tags($description),

		'thumbnailUrl' => array(
			"https://img.youtube.com/vi/{$youtube_id}/hqdefault.jpg"
		),

		'uploadDate' => get_the_date('c', $post_id),

		'embedUrl' => "https://www.youtube.com/embed/{$youtube_id}",

		'contentUrl' => "https://www.youtube.com/watch?v={$youtube_id}",

		'publisher' => array(
			'@type' => 'Organization',
			'name' => get_bloginfo('name')
		)
	);
}
/**
 * TVSeries schema for singular ht_show posts.
 *
 * @return array
 */
function blockter_schema_tv_series() {
	$id              = get_the_ID();
	$title           = get_the_title( $id );
	$url             = get_permalink( $id );
	$overview        = blockter_sd_get_post_option( $id, 'overview' );
	$first_air_date  = blockter_sd_get_post_option( $id, 'first_air_date' );
	$episode_runtime = blockter_sd_get_post_option( $id, 'episode_runtime' );
	$creators        = blockter_sd_get_post_option( $id, 'creators' );
	$genres          = blockter_sd_term_names( $id, 'mv_genre' );
	$poster_url      = get_the_post_thumbnail_url( $id, 'full' );

	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'TVSeries',
		'name'     => $title,
		'url'      => $url,
	);

	if ( ! empty( $overview ) ) {
		$schema['description'] = wp_strip_all_tags( $overview );
	}
	if ( ! empty( $poster_url ) ) {
		$schema['image'] = $poster_url;
	}
	if ( ! empty( $first_air_date ) ) {
		$schema['startDate'] = $first_air_date;
	}

	// Episode duration stored as single integer (minutes)
	$duration = blockter_sd_iso_duration( $episode_runtime );
	if ( $duration ) {
		$schema['timeRequired'] = $duration;
	}

	// Creators mapped to director (CreativeWork property available on TVSeries)
	$creator_list = blockter_sd_person_list( $creators );
	if ( ! empty( $creator_list ) ) {
		$schema['director'] = count( $creator_list ) === 1 ? $creator_list[0] : $creator_list;
	}

	// Cast (mv_actor taxonomy terms)
	$actor_terms = get_the_terms( $id, 'mv_actor' );
	if ( ! empty( $actor_terms ) && ! is_wp_error( $actor_terms ) ) {
		$actors = array();
		foreach ( $actor_terms as $actor ) {
			$entry     = array( '@type' => 'Person', 'name' => $actor->name );
			$actor_url = get_term_link( $actor );
			if ( ! is_wp_error( $actor_url ) ) {
				$entry['url'] = $actor_url;
			}
			$actors[] = $entry;
		}
		$schema['actor'] = $actors;
	}

	if ( ! empty( $genres ) ) {
		$schema['genre'] = $genres;
	}

	$rating = blockter_sd_aggregate_rating( $id );
	if ( $rating ) {
		$schema['aggregateRating'] = $rating;
	}
	// =========================
	// TRAILER VIDEOOBJECT
	// =========================
	$video_object = blockter_sd_video_object($id);

	if ($video_object) {
		$schema['trailer'] = $video_object;
	}
	return $schema;
}

/**
 * Person schema for mv_actor taxonomy term pages.
 *
 * @return array|null
 */
function blockter_schema_person() {
	$term = get_queried_object();
	if ( ! $term || ! isset( $term->term_id ) ) {
		return null;
	}

	$url        = get_term_link( $term );
	$cast_opts  = blockter_sd_get_term_option( $term->term_id, 'mv_actor' );

	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Person',
		'name'     => $term->name,
		'url'      => is_wp_error( $url ) ? get_home_url() : $url,
	);

	// Use Biography from term options when available; fallback to term description.
	$desc_source = '';
	if ( ! empty( $cast_opts['biography'] ) ) {
		$desc_source = $cast_opts['biography'];
	} elseif ( ! empty( $term->description ) ) {
		$desc_source = $term->description;
	}
	if ( $desc_source !== '' ) {
		$schema['description'] = wp_strip_all_tags( $desc_source );
	}

	// Photo
	if ( ! empty( $cast_opts['avatar_url'] ) ) {
		$schema['image'] = $cast_opts['avatar_url'];
	} elseif ( ! empty( $cast_opts['avatar']['attachment_id'] ) ) {
		$img_src = wp_get_attachment_image_url( intval( $cast_opts['avatar']['attachment_id'] ), 'full' );
		if ( $img_src ) {
			$schema['image'] = $img_src;
		}
	}

	if ( ! empty( $cast_opts['country'] ) ) {
		$schema['nationality'] = array(
			'@type' => 'Country',
			'name'  => $cast_opts['country'],
		);
	}

	if ( ! empty( $cast_opts['knowfor'] ) ) {
		$schema['jobTitle'] = $cast_opts['knowfor'];
	}

	return $schema;
}

/**
 * MovieSeries schema for mv_collection taxonomy term pages.
 *
 * @return array|null
 */
function blockter_schema_movie_series() {
	$term = get_queried_object();
	if ( ! $term || ! isset( $term->term_id ) ) {
		return null;
	}

	$url       = get_term_link( $term );
	$coll_opts = blockter_sd_get_term_option( $term->term_id, 'mv_collection' );

	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'MovieSeries',
		'name'     => $term->name,
		'url'      => is_wp_error( $url ) ? get_home_url() : $url,
	);

	if ( ! empty( $term->description ) ) {
		$schema['description'] = wp_strip_all_tags( $term->description );
	}

	// Collection background image
	if ( ! empty( $coll_opts['background_image']['url'] ) ) {
		$schema['image'] = $coll_opts['background_image']['url'];
	}

	// List every movie that belongs to this collection
	$movies_query = new WP_Query( array(
		'post_type'      => 'ht_movie',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'fields'         => 'ids',
		'no_found_rows'  => true,
		'tax_query'      => array(
			array(
				'taxonomy' => 'mv_collection',
				'field'    => 'term_id',
				'terms'    => $term->term_id,
			),
		),
	) );

	if ( ! empty( $movies_query->posts ) ) {
		$parts = array();
		foreach ( $movies_query->posts as $movie_id ) {
			$poster_url   = get_the_post_thumbnail_url( $movie_id, 'full' );
			$release_date = blockter_sd_get_post_option( $movie_id, 'release_date' );
			$directors    = blockter_sd_get_post_option( $movie_id, 'directors' );

			$part = array(
				'@type' => 'Movie',
				'name'  => get_the_title( $movie_id ),
				'url'   => get_permalink( $movie_id ),
			);
			if ( ! empty( $poster_url ) ) {
				$part['image'] = $poster_url;
			}
			if ( ! empty( $release_date ) ) {
				$part['dateCreated'] = $release_date;
			}
			$director_list = blockter_sd_person_list( $directors );
			if ( ! empty( $director_list ) ) {
				$part['director'] = count( $director_list ) === 1 ? $director_list[0] : $director_list;
			}
			$parts[] = $part;
		}
		$schema['hasPart'] = $parts;
		wp_reset_postdata();
	}

	return $schema;
}

/**
 * NewsArticle schema for singular standard blog posts.
 *
 * @return array
 */
function blockter_schema_news_article() {
	$id            = get_the_ID();
	$title         = get_the_title( $id );
	$url           = get_permalink( $id );
	$excerpt       = get_the_excerpt( $id );
	$date_pub      = get_the_date( 'c', $id );
	$date_mod      = get_the_modified_date( 'c', $id );
	$author_id     = intval( get_post_field( 'post_author', $id ) );
	$author_name   = get_the_author_meta( 'display_name', $author_id );
	$thumbnail_url = get_the_post_thumbnail_url( $id, 'full' );
	$site_name     = get_bloginfo( 'name' );
	$logo_url      = get_theme_mod( 'logo_img', '' );

	$schema = array(
		'@context'      => 'https://schema.org',
		'@type'         => 'NewsArticle',
		'headline'      => $title,
		'url'           => $url,
		'datePublished' => $date_pub,
		'dateModified'  => $date_mod,
		'author'        => array(
			'@type' => 'Person',
			'name'  => $author_name,
		),
		'publisher'     => array(
			'@type' => 'Organization',
			'name'  => $site_name,
		),
	);

	if ( ! empty( $excerpt ) ) {
		$schema['description'] = wp_strip_all_tags( $excerpt );
	}
	if ( ! empty( $thumbnail_url ) ) {
		$schema['image'] = $thumbnail_url;
	}
	if ( ! empty( $logo_url ) ) {
		$schema['publisher']['logo'] = array(
			'@type' => 'ImageObject',
			'url'   => $logo_url,
		);
	}

	return $schema;
}
