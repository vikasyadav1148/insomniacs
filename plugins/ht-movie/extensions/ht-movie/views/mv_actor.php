<?php
/**
 * The template for displaying single Actor
 */
get_header();
?>
		<?php
			global $post;
			$current_tax = get_queried_object();
			$term_id = $current_tax->term_id;
			$tax_slug = $current_tax->slug;
			$tax_name = $current_tax->name;
			$tax_des = $current_tax->description;
			$banner_img = get_template_directory_uri().'/images/actorsingle-bg.jpg';
			$cast_terms = fw_get_db_term_option($term_id, 'mv_actor');
			if(isset($cast_terms['dateofbirth']) && $cast_terms['dateofbirth'] !=''){
				$dateofbirth = $cast_terms['dateofbirth'];
			}
			if(isset($cast_terms['gender']) && $cast_terms['gender'] != ''){
				$gender = $cast_terms['gender'];
			}
			if(isset($cast_terms['country']) && $cast_terms['country'] != ''){
				$country = $cast_terms['country'];
			}
			if(isset($cast_terms['knowfor']) && $cast_terms['knowfor'] != ''){
				$knowfor = $cast_terms['knowfor'];
			}
			if(isset($cast_terms['biography']) && $cast_terms['biography'] != ''){
				$biography = $cast_terms['biography'];
			}
			if(isset($cast_terms['facebook_link']) && $cast_terms['facebook_link'] != ''){
				$facebook_link = $cast_terms['facebook_link'];
			}
			if(isset($cast_terms['twitter_link']) && $cast_terms['twitter_link'] != ''){
				$twitter_link = $cast_terms['twitter_link'];
			}
			if(isset($cast_terms['instagram_link']) && $cast_terms['instagram_link'] != ''){
				$instagram_link = $cast_terms['instagram_link'];
			}
			$movie = new WP_Query(array(
				'post_type' => array( 'ht_movie', 'ht_show'),
				'posts_per_page' => -1,
				'paged' => $paged,
				'post_status'=> 'publish',
				'tax_query' => array(
					array(
					'taxonomy' => 'mv_actor',
					'field' => 'slug',
					'terms' => $tax_slug,
					)
				)
			));
		?>
		<div class="movie-banner" style="background-image: url('<?php echo esc_url($banner_img); ?>');">
		</div>
		<div class="actor_single movie_single">
			<div class="container">
				<div class="movie-single">
					<div class="row">
						<div class="col-md-4">
							<div class="actor-thumbnail sticky-sb-movie">
								<?php
									if ( array_key_exists( 'avatar_url', $cast_terms ) && ($cast_terms['avatar_url'] != '') ) {
										$actor_thumbnail_url = $cast_terms['avatar_url'];
								?>
										<img src="<?php echo esc_url( $actor_thumbnail_url ); ?>" alt="<?php echo esc_attr__( 'Actor Avatar', 'blockter' ); ?>">
								<?php
									} elseif ( array_key_exists( 'avatar', $cast_terms ) && ($cast_terms['avatar'] != '') ) {

										$actor_thumbnailID = $cast_terms['avatar']['attachment_id'];
										echo wp_get_attachment_image($actor_thumbnailID, 'blockter-cast-thumbnail');

									} else {
								?>
										<div class="no-image"></div>
								<?php
									}
								?>
							</div>
						</div>
						<div class="col-md-8">
							<div class="actor-sinlge-content movie-single-content main-content">
								<div class="actor-infor">
									<h1 class="mv-title"><?php esc_html_e($tax_name); ?></h1>
									<?php if(isset($knowfor) && $knowfor != ''): ?>
										<span ><?php esc_html_e($knowfor); ?></span>
									<?php endif; ?>
									<div class="actor-social-links">
										<?php if(isset($facebook_link) && $facebook_link != ''): ?>
											<a href="<?php echo esc_url($facebook_link); ?>"><i class="ion-social-facebook"></i></a>
										<?php endif; ?>
										<?php if(isset($twitter_link) && $twitter_link != ''): ?>
											<a href="<?php echo esc_url($twitter_link); ?>"><i class="ion-social-twitter"></i></a>
										<?php endif; ?>
										<?php if(isset($instagram_link) && $instagram_link != ''): ?>
											<a href="<?php echo esc_url($instagram_link); ?>"><i class="ion-social-instagram-outline"></i></a>
										<?php endif; ?>
									</div>
								</div>
								<div class="movie-tab actor-tab">
								   <div class="tabs">
										<nav class="main-nav">
											<!-- tab links -->
											<ul class="tab-links tabs-mv">
												<li class="active"><a href="#biography"><?php esc_html_e(' biography', 'blockter'); ?></a></li>
												<li><a href="#filmography"><?php esc_html_e('filmography', 'blockter'); ?></a></li>
											</ul>
										</nav>
										<div class="tab-contents">
											<div id="biography" class="tab active">
												<div class="bio-description">
													<div class="sub-mv-title">
														<h2><?php echo esc_html__("Biography of", 'blockter'); ?></h2>
														<h4><?php esc_html_e($tax_name); ?></h4>
															<?php  if($tax_des != ''): ?>
																<p><?php echo apply_filters('the_content', $tax_des); ?></p>
															<?php endif; ?>
													</div>
												</div>
												<div class="cast-infor">
													<?php if(isset($dateofbirth) && $dateofbirth != ''): ?>
														<div class="overview-sb-it">
															<h6><?php esc_html_e('Date of Birth: ', 'blockter'); ?></h6>
															<span class="white-text"><?php esc_html_e($dateofbirth); ?></span>
														</div>
													<?php endif; ?>
													<?php if(isset($gender) && $gender != ''): ?>
														<div class="overview-sb-it">
															<h6><?php esc_html_e('Gender: ', 'blockter'); ?></h6>
															<span class="white-text"><?php esc_html_e($gender); ?></span>
														</div>
													<?php endif; ?>
													<?php if(isset($country) && $country != ''): ?>
														<div class="overview-sb-it">
															<h6><?php esc_html_e('Place of Birth: ', 'blockter'); ?></h6>
															<span class="white-text"><?php esc_html_e($country); ?></span>
														</div>
													<?php endif; ?>
												</div>
											</div>
											<div id="filmography" class="tab">
											    <h2><?php echo esc_html__("Filmography", 'blockter'); ?></h2>
												<?php
													if( $movie->have_posts() ):
														while ( $movie->have_posts() ): $movie->the_post();
														$thumbnail_id = get_post_thumbnail_id( $post->ID );
														$release_date = fw_get_db_post_option( $post->ID, 'release_date' );
														$first_air_date = fw_get_db_post_option( $post->ID, 'first_air_date' );
												?>
													<div class="movie-item">
														<div class="inner-it">
															<div class="movie-thumbnail">
																	<?php if( ! empty( $thumbnail_id) ) : ?>
																		<a href="<?php the_permalink(); ?>">
																			<?php echo wp_get_attachment_image($thumbnail_id, 'blockter-poster-movie-item-small');?>
																		</a>
																	<?php endif; ?>
															</div>
															<div class="movie-content">
																<h6 class="mv-title"><a itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
															</div>
														</div>
														<?php if ( $release_date != '' ) : ?>
															<p class="release-date"><?php esc_html_e( $release_date ); ?></p>
														<?php elseif ( $first_air_date != '' ) : ?>
															<p class="release-date"><?php esc_html_e( $first_air_date ); ?></p>
														<?php endif; ?>
													</div>
													<?php endwhile; ?>
												<?php endif; ?>
											</div>
										</div>
								   </div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clear-both"></div>
<?php get_footer(); ?>
