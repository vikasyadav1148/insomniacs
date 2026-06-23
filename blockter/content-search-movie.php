<?php
$posts_per_page = get_theme_mod( 'movies_grid_per_page', 8 ) ? get_theme_mod( 'movies_grid_per_page', 8 ) : 8;
$movie_settings = blockter_setup_movie_settings( 'ht_movie', $posts_per_page );

if ( $movie_settings['movie']->have_posts() ) :
	?>
	<div class="movie-wrapper">
		<?php blockter_render_movie_filter( $movie_settings['total_movies'], $movie_settings['orderby_options'], $movie_settings['sortby'], 'grid' ); ?>
		<div class="theme-movie-items row list-group movie-items">
			<?php
			while ( $movie_settings['movie']->have_posts() ) :
				$movie_settings['movie']->the_post();
				blockter_render_movie_content( 'grid' );
			endwhile;
			?>
		</div>

		<div class="clear-both"></div>

		<!-- Pagination -->
		<div class="row">
			<?php
			$total_pages = 'rating' === $movie_settings['sortby']
				? ceil( $movie_settings['total_movies'] / $movie_settings['posts_per_page'] )
				: $movie_settings['movie']->max_num_pages;
			blockter_render_movie_pagination( $total_pages, $movie_settings['posts_per_page'], $movie_settings['paged'] );
			wp_reset_postdata();
			?>
		</div><!-- .row -->
	</div><!-- .movie-wrapper -->
	<?php
else :
	get_template_part( 'content', 'none' );
endif;
?>
