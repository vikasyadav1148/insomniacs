<?php
	get_header();
	/*query*/
	global $post;
	$current_tax = get_queried_object();
	$tax_slug = $current_tax->slug;
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args = array(
		'post_type' => array( 'ht_movie', 'ht_show' ),
		'posts_per_page' => 15,
		'ignore_sticky_posts' => 1,
		'post_status' => 'publish',
		'paged' => $paged,
		'page' => $paged,
		'order' => 'ASC',
		'tax_query' => array(
			array(
			'taxonomy' => 'mv_genre',
			'field' => 'slug',
			'terms' => $tax_slug,
			)
		)
	);
	if( !empty($sortby) && $sortby == 'default' ) {
		$args['orderby'] = 'ID';
	}
	if( !empty($sortby) && $sortby == 'post_title' ) {
		$args['orderby'] = 'title';
	}

	$movie = new WP_Query( $args );

	$total_page = $movie->max_num_pages;
?>
<!-- movie list items -->
<main id="main" class="page_content flw blog-standard movie-list">
	<div class="container">
		<div class="row flex-center">
			<?php if(is_active_sidebar('sidebar_movie')): ?>
			<div class="col-md-8">
				<div class="celebrity-topbar-filter">
					<div class="celebrity-result-count">
						<?php
						$paged    = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
						$per_page = $movie->get( 'posts_per_page' );
						$total    = $movie->found_posts;
						$first    = ( $per_page * $paged ) - $per_page + 1;
						$last     = min( $total, $movie->get( 'posts_per_page' ) * $paged );
						$rating = '3';
						if ( $total <= $per_page || -1 === $per_page ) {
							/* translators: %d: total results */
							printf( _n( 'Showing all %s movie', 'Showing all %s movies', $total, 'blockter' ), $total );
						} else {
							/* translators: 1: first result 2: last result 3: total results */
							printf( _nx( 'Found <strong>%s movie</strong> in total', 'Found <strong>%s movies</strong> in total', $total, 'blockter' ),  $total );
						}
						?>
					</div>
					<!-- sort by -->
					<div class="filter-right">
						<form class="celebrity-sorting">
							<span><?php echo esc_html__("Sort By:", 'blockter'); ?></span>
							<select name="sortby" class="consult-dropdown-list">
								<?php
									$orderby_options = array(
										'default' => 'Default',
										'post_title' => 'Title',
									);
									$sortby = array_key_exists('sortby', $_GET) ? $_GET['sortby'] : "";
									foreach( $orderby_options as $value => $label ) {
										echo "<option ".selected($sortby, $value )." value=".esc_attr($value).">".esc_attr($label)."</option>";
									}
									if( !empty($sortby) && $sortby == 'default' ) {
										$args['orderby'] = 'ID';
									}
									if( !empty($sortby) && $sortby == 'post_title' ) {
										$args['orderby'] = 'title';
									}

									$movie = new WP_Query( $args );

								?>
							</select>

						</form>
						<div class="celebrity-view btn-group">
							<a href="#" class="ion-ios-list-outline list"></a>
							<a href="#" class="ion-grid grid current"></a>
						</div>
					</div>
				</div>
				<div class="theme-movie-items list-group movie-items row">
					<?php
					if( $movie->have_posts() ):
					while($movie->have_posts()): $movie->the_post();
					$thumbnail_id = get_post_thumbnail_id($post->ID);
					$tagline = fw_get_db_post_option($post->ID, 'tagline');
					$overview = fw_get_db_post_option($post->ID, 'overview');
					$release_date = fw_get_db_post_option($post->ID, 'release_date');
					$runtime = fw_get_db_post_option($post->ID, 'runtime');
					$production = fw_get_db_post_option($post->ID, 'production');
					$country = fw_get_db_post_option($post->ID, 'country');
					$languages = fw_get_db_post_option($post->ID, 'languages');
					$director = fw_get_db_post_option($post->ID, 'director');
					$writer = fw_get_db_post_option($post->ID, 'writer');
					$genre_list = wp_get_post_terms($post->ID, 'mv_genre', array("fields" => "names"));
					$actor_list = wp_get_post_terms($post->ID, 'mv_actor', array("fields" => "names"));
					$actor_lists = get_the_terms( $post->ID, 'mv_actor' );
					$genre_lists = get_the_terms( $post->ID, '  ');
					$feedback = fw()->extensions->get( 'feedback' );
					?>
					<div class="col-md-12 col-sm-12 col-xs-12 item grid-group-item">
						<div class="movie-item">
							<div class="movie-thumbnail">
								<?php if(!empty($thumbnail_id)): ?>
								<a href="<?php the_permalink(); ?>">
									<?php echo wp_get_attachment_image($thumbnail_id, 'blockter-poster-movie-item');?>
									<span class="readmore-btn"><?php echo esc_html__("Read more", 'blockter');?><i class="ion-android-arrow-dropright"></i></span>
								</a>
								<?php endif; ?>
							</div>
							<div class="movie-content">
								<h6 class="mv-title"><a itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
								<?php if($feedback != null): ?>
								<?php if(comments_open() && get_comments_number()): ?>
								<div class="rate-average">
									<div class="left-it">
										<span class="fa fa-star icon"></span>
										<div class="inner-cmt-infor">
											<?php  $average = fw_ext_feedback_stars_get_post_rating();?>
											<div class="rate-num">
												<span><?php esc_html_e($average['average']); ?></span>
												<span class="sm-text"><?php echo esc_html__("/", 'blockter'); ?></span>
												<span class="sm-text"><?php
													$star = fw_ext_feedback_stars_get_post_detailed_rating($post->ID);
													echo count($star['stars']);?>
												</span>
											</div>
										</div>
									</div>
								</div>
								<?php endif;?>
								<?php endif; ?>
								<div class="mv-list-content">
									<?php if(!empty($overview)): ?>
									<div class="mv-des">
										<?php echo wp_kses_post($overview);?>
									</div>
									<?php endif; ?>
									<div class="flex-it movie-details">
										<?php if(!empty($runtime)): ?>
										<span><?php echo esc_html__("Run time: ", 'blockter'); ?><?php esc_html_e($runtime);?></span>
										<?php endif; ?>
										<?php if(!empty($tagline)): ?>
										<span><?php echo esc_html__("Tagline: ", 'blockter'); ?><?php esc_html_e($tagline);?></span>
										<?php endif; ?>
										<?php if(!empty($release_date)): ?>
										<span><?php echo esc_html__("Release: ", 'blockter'); ?><?php esc_html_e($release_date);?></span>
										<?php endif; ?>
									</div>
									<?php if(!empty($director)): ?>
									<p class="mv-directors"><?php echo esc_html__("Director: ", 'blockter') ?><span class="link-color"><?php esc_html_e($director);?></span></p>
									<?php endif; ?>
									<?php if(!empty($actor_list)): ?>
									<p class="mv-stars">
										<span><?php esc_html_e('Stars: ', 'blockter'); ?></span>
										<?php foreach($actor_lists as $item): ?>
										<?php $ac_name = $item->name;  $ac_url = get_term_link($item); ?>
										<a href="<?php echo esc_url($ac_url);?>"><?php echo esc_html($ac_name);?></a>
										<?php endforeach; ?>
									</p>
									<?php endif; ?>
									</div><!-- .mv-list-content -->
								</div>
							</div>
						</div>
						<?php
						endwhile;

						endif;

						?>
					</div>
						<?php
							if ( $total_page > 1 ) {
								ht_movie_pagination($total_page);
							}
							wp_reset_postdata();
						?>
				</div>
				<div class="col-md-4">
					<?php get_sidebar('movie'); ?>
				</div>
				<?php else: ?>
				<div class="col-md-9 not-active-sidebar">
					<div class="celebrity-topbar-filter">
						<div class="celebrity-result-count">
							<?php
							$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
							$per_page = $movie->get( 'posts_per_page' );
							$total    = $movie->found_posts;
							$rating = '3';
							if ( $total <= $per_page || -1 === $per_page ) {
								/* translators: %d: total results */
								printf( _n( 'Showing all %s movie', 'Showing all %s movies', $total, 'blockter' ), $total );
							} else {
								/* translators: 1: first result 2: last result 3: total results */
								printf( _nx( 'Found <strong>%s movie</strong> in total', 'Found <strong>%s movies</strong> in total', $total, 'blockter' ),  $total );
							}
							?>
						</div>
						<!-- sort by -->
						<div class="filter-right">
							<form class="celebrity-sorting">
								<span><?php echo esc_html__("Sort By:", 'blockter'); ?></span>
								<select name="sortby" class="consult-dropdown-list">
									<?php

										$orderby_options = array(
											'default' => 'Default',
											'post_title' => 'Title',
										);
										$sortby = array_key_exists('sortby', $_GET) ? $_GET['sortby'] : "";
										foreach( $orderby_options as $value => $label ) {
											echo "<option ".selected($sortby, $value )." value=".esc_attr($value).">".esc_attr($label)."</option>";
										}

										if( !empty($sortby) && $sortby == 'default' ) {
											$args['orderby'] = 'ID';
										}
										if( !empty($sortby) && $sortby == 'post_title' ) {
											$args['orderby'] = 'title';
										}

										$movie = new WP_Query( $args );

									?>
								</select>
							</form>
							<?php

							?>

							<div class="celebrity-view btn-group">
								<a href="#"  class="ion-ios-list-outline current list"></a>
								<a href="#"  class="ion-grid grid"></a>
							</div>
						</div>
					</div>

					<div class="theme-movie-items list-group movie-items row">
						<?php
						if( $movie->have_posts() ):
						while($movie->have_posts()): $movie->the_post();
						$thumbnail_id = get_post_thumbnail_id($post->ID);
						$tagline = fw_get_db_post_option($post->ID, 'tagline');
						$overview = fw_get_db_post_option($post->ID, 'overview');
						$release_date = fw_get_db_post_option($post->ID, 'release_date');
						$runtime = fw_get_db_post_option($post->ID, 'runtime');
						$production = fw_get_db_post_option($post->ID, 'production');
						$country = fw_get_db_post_option($post->ID, 'country');
						$languages = fw_get_db_post_option($post->ID, 'languages');
						$director = fw_get_db_post_option($post->ID, 'director');
						$writer = fw_get_db_post_option($post->ID, 'writer');
						$genre_list = wp_get_post_terms($post->ID, 'mv_genre', array("fields" => "names"));
						$actor_list = wp_get_post_terms($post->ID, 'mv_actor', array("fields" => "names"));
						$actor_lists = get_the_terms( $post->ID, 'mv_actor' );
						$genre_lists = get_the_terms( $post->ID, '  ');
						$feedback = fw()->extensions->get( 'feedback' );
						?>
						<div class="col-md-12 col-sm-12 col-xs-12 item list-group-item">
							<div class="movie-item">
								<div class="movie-thumbnail">
									<?php if(!empty($thumbnail_id)): ?>
									<a href="<?php the_permalink(); ?>">
										<?php echo wp_get_attachment_image($thumbnail_id, 'blockter-poster-movie-item');?>
										<span class="readmore-btn"><?php echo esc_html__("Read more", 'blockter');?><i class="ion-android-arrow-dropright"></i></span>
									</a>
									<?php endif; ?>
								</div>
								<div class="movie-content">
									<h6 class="mv-title"><a itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
									<?php if($feedback != null): ?>
									<?php if(comments_open() && get_comments_number()): ?>
									<div class="rate-average">
										<div class="left-it">
											<span class="fa fa-star icon"></span>
											<div class="inner-cmt-infor">
												<?php  $average = fw_ext_feedback_stars_get_post_rating();?>
												<div class="rate-num">
													<span><?php esc_html_e($average['average']); ?></span>
													<span class="sm-text"><?php echo esc_html__("/", 'blockter'); ?></span>
													<span class="sm-text"><?php
														$star = fw_ext_feedback_stars_get_post_detailed_rating($post->ID);
														echo count($star['stars']);?>
													</span>
												</div>
											</div>
										</div>
									</div>
									<?php endif;?>
									<?php endif; ?>
									<div class="mv-list-content">
										<?php if(!empty($overview)): ?>
										<div class="mv-des">
											<?php echo wp_kses_post($overview);?>
										</div>
										<?php endif; ?>
										<div class="flex-it movie-details">
											<?php if(!empty($runtime)): ?>
											<span><?php echo esc_html__("Run time: ", 'blockter'); ?><?php esc_html_e($runtime);?></span>
											<?php endif; ?>
											<?php if(!empty($tagline)): ?>
											<span><?php echo esc_html__("Tagline: ", 'blockter'); ?><?php esc_html_e($tagline);?></span>
											<?php endif; ?>
											<?php if(!empty($release_date)): ?>
											<span><?php echo esc_html__("Release: ", 'blockter'); ?><?php esc_html_e($release_date);?></span>
											<?php endif; ?>
										</div>
										<?php if(!empty($director)): ?>
										<p class="mv-directors"><?php echo esc_html__("Director: ", 'blockter') ?><span class="link-color"><?php esc_html_e($director);?></span></p>
										<?php endif; ?>
										<?php if(!empty($actor_list)): ?>
										<p class="mv-stars">
											<span><?php esc_html_e('Stars: ', 'blockter'); ?></span>
											<?php foreach($actor_lists as $item): ?>
											<?php $ac_name = $item->name;  $ac_url = get_term_link($item); ?>
											<a href="<?php echo esc_url($ac_url);?>"><?php echo esc_html($ac_name);?></a>
											<?php endforeach; ?>
										</p>
										<?php endif; ?>
										</div><!-- .mv-list-content -->
									</div>
								</div>
							</div>
							<?php
							endwhile;


							endif;
							/*reset query*/
							
							?>
						</div>
						<?php
							if ( $total_page > 1 ) {
								ht_movie_pagination($total_page);
							}
							wp_reset_postdata();
						?>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</main>
		<?php get_footer(); ?>