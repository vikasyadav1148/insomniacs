<?php
		get_header();
		/*query*/
		global $post;
		$current_tax = get_queried_object();
		$tax_slug = $current_tax->slug;
		$sortby = isset( $_GET['sortby'] ) ? sanitize_key( $_GET['sortby'] ) : 'default';

		$base_args = array(
			'post_type'      => array( 'ht_movie', 'ht_show' ),
			'posts_per_page' => -1,
			'paged'          => $paged,
			'post_status'    => 'publish',
			'tax_query'      => array(
				array(
					'taxonomy' => 'mv_collection',
					'field'    => 'slug',
					'terms'    => $tax_slug,
				),
			),
		);

		switch ( $sortby ) {
			case 'post_title':
				$base_args['orderby'] = 'title';
				$base_args['order']   = 'ASC';
				break;
			case 'story_order':
				$base_args['orderby'] = 'menu_order';
				$base_args['order']   = 'ASC';
				break;
			case 'release_year':
			case 'release_date':
				// Fetch all; we sort by full release date in PHP (stored in Unyson options).
				$base_args['orderby'] = 'date';
				$base_args['order']   = 'ASC';
				break;
			default:
				$base_args['orderby'] = 'ID';
				$base_args['order']   = 'ASC';
				break;
		}

		$movie = new WP_Query( $base_args );

		// Sort by full release date in PHP when that option is selected (release_date is in post options).
		if ( ( 'release_year' === $sortby || 'release_date' === $sortby ) && $movie->have_posts() ) {
			$posts_with_dates = array();
			foreach ( $movie->posts as $p ) {
				$rd = function_exists( 'fw_get_db_post_option' ) ? fw_get_db_post_option( $p->ID, 'release_date' ) : '';
				$ts = 0;
				if ( ! empty( $rd ) ) {
					$parsed = strtotime( (string) $rd );
					$ts = ( false !== $parsed ) ? $parsed : 0;
				}
				$posts_with_dates[] = array( 'post' => $p, 'ts' => $ts );
			}
			usort( $posts_with_dates, function ( $a, $b ) {
				if ( $a['ts'] !== $b['ts'] ) {
					return $a['ts'] - $b['ts'];
				}
				return $a['post']->ID - $b['post']->ID;
			} );
			$movie->posts = array_map( function ( $item ) { return $item['post']; }, $posts_with_dates );
			$movie->post_count = count( $movie->posts );
		}
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
												$paged    = max( 1, $movie->get( 'paged' ) );
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
												<form class="celebrity-sorting" method="get">
													<span><?php echo esc_html__("Sort By:", 'blockter'); ?></span>
													<select name="sortby" class="consult-dropdown-list">
														<?php
															$orderby_options = array(
																'default'       => __( 'Default', 'blockter' ),
																'post_title'    => __( 'Title', 'blockter' ),
																'release_date'  => __( 'Release date', 'blockter' ),
																'story_order'   => __( 'Story order (chronological)', 'blockter' ),
															);
															foreach ( $orderby_options as $value => $label ) {
																echo '<option ' . selected( $sortby, $value, false ) . ' value="' . esc_attr( $value ) . '">' . esc_html( $label ) . '</option>';
															}
														?>
													</select>
												</form>
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
														wp_reset_postdata();
														?>
										</div>
								</div>
								<div class="col-md-4">
										<?php get_sidebar('movie'); ?>
								</div>
						<?php else: ?>
								<div class="col-md-9 not-active-sidebar">
										<div class="celebrity-topbar-filter">
											<div class="celebrity-result-count">
												<?php
												$paged    = max( 1, $movie->get( 'paged' ) );
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
												<form class="celebrity-sorting" method="get">
													<span><?php echo esc_html__("Sort By:", 'blockter'); ?></span>
													<select name="sortby" class="consult-dropdown-list">
														<?php
															$orderby_options_no_sidebar = array(
																'default'       => __( 'Default', 'blockter' ),
																'post_title'    => __( 'Title', 'blockter' ),
																'release_date'  => __( 'Release date', 'blockter' ),
																'story_order'   => __( 'Story order (chronological)', 'blockter' ),
															);
															foreach ( $orderby_options_no_sidebar as $value => $label ) {
																echo '<option ' . selected( $sortby, $value, false ) . ' value="' . esc_attr( $value ) . '">' . esc_html( $label ) . '</option>';
															}
														?>
													</select>
												</form>
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
														wp_reset_postdata();
														?>
										</div>
								</div>
						<?php endif; ?>
				</div>
		</div>
</main>

<?php get_footer(); ?>
