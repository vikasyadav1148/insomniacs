<?php
/**
 * The template for displaying TV Show (ht_show) archive pages.
 */
get_header();

global $post, $wp_query;
$paged      = max( 1, (int) get_query_var( 'paged' ) );
$total      = (int) $wp_query->found_posts;
$per_page   = (int) $wp_query->get( 'posts_per_page' );
$total_page = (int) $wp_query->max_num_pages;
?>
<!-- show list items -->
<main id="main" class="page_content flw blog-standard movie-list">
	<div class="container">
		<div class="row flex-center">
			<?php if ( is_active_sidebar( 'blog-widget' ) ) : ?>
			<div class="col-md-9">
				<div class="celebrity-topbar-filter">
					<div class="celebrity-result-count">
						<?php
						if ( $total <= $per_page || -1 === $per_page ) {
							printf( _n( 'Showing all %s series', 'Showing all %s series', $total, 'blockter' ), $total );
						} else {
							printf( _nx( 'Found <strong>%s series</strong> in total', 'Found <strong>%s series</strong> in total', $total, 'blockter' ), $total );
						}
						?>
					</div>
					<div class="filter-right">
						<div class="celebrity-view btn-group">
							<a href="#" class="ion-ios-list-outline current list"></a>
							<a href="#" class="ion-grid grid"></a>
						</div>
					</div>
				</div>

				<div class="theme-movie-items list-group movie-items row">
					<?php if ( have_posts() ) : while ( have_posts() ) : the_post();
						$thumbnail_id   = get_post_thumbnail_id( $post->ID );
						$tagline        = fw_get_db_post_option( $post->ID, 'tagline' );
						$overview       = fw_get_db_post_option( $post->ID, 'overview' );
						$first_air_date = fw_get_db_post_option( $post->ID, 'first_air_date' );
						$runtime        = fw_get_db_post_option( $post->ID, 'runtime' );
						$director       = fw_get_db_post_option( $post->ID, 'director' );
						$actor_list     = wp_get_post_terms( $post->ID, 'mv_actor', array( 'fields' => 'names' ) );
						$actor_lists    = get_the_terms( $post->ID, 'mv_actor' );
						$feedback       = fw()->extensions->get( 'feedback' );
					?>
					<div class="col-md-12 col-sm-12 col-xs-12 item list-group-item">
						<div class="movie-item">
							<div class="movie-thumbnail no100width">
								<?php if ( ! empty( $thumbnail_id ) ) : ?>
								<a href="<?php the_permalink(); ?>">
									<?php echo wp_get_attachment_image( $thumbnail_id, 'blockter-poster-movie-item' ); ?>
									<span class="readmore-btn"><?php echo esc_html__( 'Read more', 'blockter' ); ?><i class="ion-android-arrow-dropright"></i></span>
								</a>
								<?php endif; ?>
							</div>
							<div class="movie-content">
								<h6 class="mv-title"><a itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
								<?php if ( $feedback !== null && comments_open() && get_comments_number() ) : ?>
								<div class="rate-average">
									<div class="left-it">
										<span class="fa fa-star icon"></span>
										<div class="inner-cmt-infor">
											<?php $average = fw_ext_feedback_stars_get_post_rating(); ?>
											<div class="rate-num">
												<span><?php esc_html_e( $average['average'] ); ?></span>
												<span class="sm-text"><?php echo esc_html__( '/', 'blockter' ); ?></span>
												<span class="sm-text"><?php
													$star = fw_ext_feedback_stars_get_post_detailed_rating( $post->ID );
													echo count( $star['stars'] ); ?>
												</span>
											</div>
										</div>
									</div>
								</div>
								<?php endif; ?>
								<div class="mv-list-content">
									<?php if ( ! empty( $overview ) ) : ?>
									<div class="mv-des"><?php echo wp_kses_post( $overview ); ?></div>
									<?php endif; ?>
									<div class="flex-it movie-details">
										<?php if ( ! empty( $runtime ) ) : ?>
										<span><?php echo esc_html__( 'Episode runtime: ', 'blockter' ); ?><?php esc_html_e( $runtime ); ?></span>
										<?php endif; ?>
										<?php if ( ! empty( $tagline ) ) : ?>
										<span><?php echo esc_html__( 'Tagline: ', 'blockter' ); ?><?php esc_html_e( $tagline ); ?></span>
										<?php endif; ?>
										<?php if ( ! empty( $first_air_date ) ) : ?>
										<span><?php echo esc_html__( 'First aired: ', 'blockter' ); ?><?php esc_html_e( $first_air_date ); ?></span>
										<?php endif; ?>
									</div>
									<?php if ( ! empty( $director ) ) : ?>
									<p class="mv-directors"><?php echo esc_html__( 'Creator: ', 'blockter' ); ?><span class="link-color"><?php esc_html_e( $director ); ?></span></p>
									<?php endif; ?>
									<?php if ( ! empty( $actor_list ) && ! empty( $actor_lists ) ) : ?>
									<p class="mv-stars">
										<span><?php esc_html_e( 'Stars: ', 'blockter' ); ?></span>
										<?php foreach ( $actor_lists as $item ) : ?>
										<a href="<?php echo esc_url( get_term_link( $item ) ); ?>"><?php echo esc_html( $item->name ); ?></a>
										<?php endforeach; ?>
									</p>
									<?php endif; ?>
								</div><!-- .mv-list-content -->
							</div>
						</div>
					</div>
					<?php endwhile;
					else : get_template_part( 'content', 'none' );
					endif; ?>
				</div>
				<?php if ( $total_page > 1 ) { blockter_paging_nav(); } ?>
			</div>
			<div class="col-md-3">
				<?php get_sidebar(); ?>
			</div>

			<?php else : ?>

			<div class="col-md-9 not-active-sidebar">
				<div class="celebrity-topbar-filter">
					<div class="celebrity-result-count">
						<?php
						if ( $total <= $per_page || -1 === $per_page ) {
							printf( _n( 'Showing all %s series', 'Showing all %s series', $total, 'blockter' ), $total );
						} else {
							printf( _nx( 'Found <strong>%s series</strong> in total', 'Found <strong>%s series</strong> in total', $total, 'blockter' ), $total );
						}
						?>
					</div>
					<div class="filter-right">
						<div class="celebrity-view btn-group">
							<a href="#" class="ion-ios-list-outline current list"></a>
							<a href="#" class="ion-grid grid"></a>
						</div>
					</div>
				</div>

				<div class="theme-movie-items list-group movie-items row">
					<?php if ( have_posts() ) : while ( have_posts() ) : the_post();
						$thumbnail_id   = get_post_thumbnail_id( $post->ID );
						$tagline        = fw_get_db_post_option( $post->ID, 'tagline' );
						$overview       = fw_get_db_post_option( $post->ID, 'overview' );
						$first_air_date = fw_get_db_post_option( $post->ID, 'first_air_date' );
						$runtime        = fw_get_db_post_option( $post->ID, 'runtime' );
						$director       = fw_get_db_post_option( $post->ID, 'director' );
						$actor_list     = wp_get_post_terms( $post->ID, 'mv_actor', array( 'fields' => 'names' ) );
						$actor_lists    = get_the_terms( $post->ID, 'mv_actor' );
						$feedback       = fw()->extensions->get( 'feedback' );
					?>
					<div class="col-md-12 col-sm-12 col-xs-12 item list-group-item">
						<div class="movie-item">
							<div class="movie-thumbnail no100width">
								<?php if ( ! empty( $thumbnail_id ) ) : ?>
								<a href="<?php the_permalink(); ?>">
									<?php echo wp_get_attachment_image( $thumbnail_id, 'blockter-poster-movie-item' ); ?>
									<span class="readmore-btn"><?php echo esc_html__( 'Read more', 'blockter' ); ?><i class="ion-android-arrow-dropright"></i></span>
								</a>
								<?php endif; ?>
							</div>
							<div class="movie-content">
								<h6 class="mv-title"><a itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
								<?php if ( $feedback !== null && comments_open() && get_comments_number() ) : ?>
								<div class="rate-average">
									<div class="left-it">
										<span class="fa fa-star icon"></span>
										<div class="inner-cmt-infor">
											<?php $average = fw_ext_feedback_stars_get_post_rating(); ?>
											<div class="rate-num">
												<span><?php esc_html_e( $average['average'] ); ?></span>
												<span class="sm-text"><?php echo esc_html__( '/', 'blockter' ); ?></span>
												<span class="sm-text"><?php
													$star = fw_ext_feedback_stars_get_post_detailed_rating( $post->ID );
													echo count( $star['stars'] ); ?>
												</span>
											</div>
										</div>
									</div>
								</div>
								<?php endif; ?>
								<div class="mv-list-content">
									<?php if ( ! empty( $overview ) ) : ?>
									<div class="mv-des"><?php echo wp_kses_post( $overview ); ?></div>
									<?php endif; ?>
									<div class="flex-it movie-details">
										<?php if ( ! empty( $runtime ) ) : ?>
										<span><?php echo esc_html__( 'Episode runtime: ', 'blockter' ); ?><?php esc_html_e( $runtime ); ?></span>
										<?php endif; ?>
										<?php if ( ! empty( $tagline ) ) : ?>
										<span><?php echo esc_html__( 'Tagline: ', 'blockter' ); ?><?php esc_html_e( $tagline ); ?></span>
										<?php endif; ?>
										<?php if ( ! empty( $first_air_date ) ) : ?>
										<span><?php echo esc_html__( 'First aired: ', 'blockter' ); ?><?php esc_html_e( $first_air_date ); ?></span>
										<?php endif; ?>
									</div>
									<?php if ( ! empty( $director ) ) : ?>
									<p class="mv-directors"><?php echo esc_html__( 'Creator: ', 'blockter' ); ?><span class="link-color"><?php esc_html_e( $director ); ?></span></p>
									<?php endif; ?>
									<?php if ( ! empty( $actor_list ) && ! empty( $actor_lists ) ) : ?>
									<p class="mv-stars">
										<span><?php esc_html_e( 'Stars: ', 'blockter' ); ?></span>
										<?php foreach ( $actor_lists as $item ) : ?>
										<a href="<?php echo esc_url( get_term_link( $item ) ); ?>"><?php echo esc_html( $item->name ); ?></a>
										<?php endforeach; ?>
									</p>
									<?php endif; ?>
								</div><!-- .mv-list-content -->
							</div>
						</div>
					</div>
					<?php endwhile;
					else : get_template_part( 'content', 'none' );
					endif; ?>
				</div>
				<?php if ( $total_page > 1 ) { blockter_paging_nav(); } ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>
