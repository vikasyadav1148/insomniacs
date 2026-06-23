<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package blockter
 */

if ( ! is_active_sidebar( 'blog-widget' ) ) {
	return;
}
?>

<div id="theme-right-sidebar" class="widget-area flw" role="complementary">
	<?php dynamic_sidebar( 'blog-widget' ); ?>

	<?php if ( is_singular( 'post' ) ) :
		global $post;

		/* --- Related Collections --- */
		$post_collections = get_the_terms( $post->ID, 'mv_collection' );
		if ( $post_collections && ! is_wp_error( $post_collections ) ) : ?>
		<div class="widget sb-related-collections">
			<h3 class="widget-title"><?php esc_html_e( 'Collections', 'blockter' ); ?></h3>
			<ul class="sb-related-list">
				<?php foreach ( $post_collections as $col ) : ?>
				<li>
					<a href="<?php echo esc_url( get_term_link( $col ) ); ?>">
						<?php echo esc_html( $col->name ); ?>
					</a>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif;

		/* --- Related Movies (by shared genre) --- */
		$genre_ids = wp_get_object_terms( $post->ID, 'mv_genre', array( 'fields' => 'ids' ) );
		if ( ! empty( $genre_ids ) && ! is_wp_error( $genre_ids ) ) :
			$related_movies = new WP_Query( array(
				'post_type'      => 'ht_movie',
				'posts_per_page' => 5,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
				'tax_query'      => array(
					array(
						'taxonomy' => 'mv_genre',
						'field'    => 'term_id',
						'terms'    => $genre_ids,
						'operator' => 'IN',
					),
				),
			) );
			if ( $related_movies->have_posts() ) : ?>
		<div class="widget sb-related-movies">
			<h3 class="widget-title"><?php esc_html_e( 'Related Movies', 'blockter' ); ?></h3>
			<ul class="sb-related-list sb-related-movies-list">
				<?php while ( $related_movies->have_posts() ) : $related_movies->the_post();
					$thumb_id = get_post_thumbnail_id( $post->ID ); ?>
				<li class="sb-movie-item">
					<?php if ( $thumb_id ) : ?>
					<a href="<?php the_permalink(); ?>" class="sb-movie-thumb">
						<?php echo wp_get_attachment_image( $thumb_id, 'blockter-poster-movie-item-small' ); ?>
					</a>
					<?php endif; ?>
					<a href="<?php the_permalink(); ?>" class="sb-movie-title"><?php the_title(); ?></a>
				</li>
				<?php endwhile; wp_reset_postdata(); ?>
			</ul>
		</div>
		<?php endif;
		endif;
	endif; ?>
</div>