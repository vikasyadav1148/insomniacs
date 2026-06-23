<?php
global $current_user, $wp_roles, $post;

$paged = max( 1, absint( get_query_var( 'paged' ) ) );
$fav_show_ids = array_map( 'absint', (array) get_user_meta( $current_user->ID, 'favourite_show_id' ) );
$fav_show_ids = array_values( array_filter( $fav_show_ids ) );

$show = new WP_Query(
	array(
		'post_type'           => 'ht_show',
		'orderby'             => 'post__in',
		'order'               => 'ASC',
		'posts_per_page'      => 4,
		'paged'               => $paged,
		'ignore_sticky_posts' => true,
		'post__in'            => empty( $fav_show_ids ) ? array( 0 ) : $fav_show_ids,
	)
);

$paged_shows    = max( 1, $show->get( 'paged' ) );
$per_page_shows = $show->get( 'posts_per_page' );
$total_show     = $show->found_posts;
$first_show     = ( $per_page_shows * $paged_shows ) - $per_page_shows + 1;
$last_show      = min( $total_show, $show->get( 'posts_per_page' ) * $paged_shows );
?>
<div id="my-shows">
	<?php if ( $show->have_posts() ) : ?>
		<div class="celebrity-topbar-filter">
			<div class="celebrity-result-count">
				<?php
				if ( $total_show <= $per_page_shows || -1 === $per_page_shows ) {
					printf( _n( 'Showing the single result', 'Showing all %d shows', $total_show, 'blockter' ), $total_show );
				} else {
					printf( _nx( 'Showing the single result', 'Found <strong>%3$d shows</strong> in total', $total_show, 'with first and last result', 'blockter' ), $first_show, $last_show, $total_show );
				}
				?>
			</div>
			<div class="filter-right">
				<form class="celebrity-sorting">
					<span><?php echo esc_html__( 'Sort By:', 'blockter' ); ?></span>
					<select name="sortby" class="consult-dropdown-list">
						<?php
						$orderby_options = array(
							'default'    => 'Default',
							'post_title' => 'Title',
						);
						$sortby = array_key_exists( 'sortby', $_GET ) ? sanitize_text_field( wp_unslash( $_GET['sortby'] ) ) : '';
						foreach ( $orderby_options as $value => $label ) {
							echo '<option ' . selected( $sortby, $value, false ) . ' value="' . esc_attr( $value ) . '">' . esc_html( $label ) . '</option>';
						}
						$modifications = array();
						if ( 'default' === $sortby ) {
							$modifications = array( 'orderby' => 'post__in', 'order' => 'ASC' );
						}
						if ( 'post_title' === $sortby ) {
							$modifications = array( 'orderby' => 'title', 'order' => 'ASC' );
						}
						if ( ! empty( $modifications ) ) {
							$args = array_merge( $show->query_vars, $modifications );
							$show = new WP_Query( $args );
						}
						?>
					</select>
					<input type="hidden" name="paged" value="1">
				</form>
				<div class="celebrity-view btn-group">
					<a href="#" class="ion-ios-list-outline current list"></a>
					<a href="#" class="ion-grid grid"></a>
				</div>
			</div>
		</div>
		<div class="theme-movie-items row list-group movie-items">
			<?php while ( $show->have_posts() ) : $show->the_post(); ?>
				<?php
				$thumbnail_id    = get_post_thumbnail_id( $post->ID );
				$tagline         = fw_get_db_post_option( $post->ID, 'tagline' );
				$overview        = fw_get_db_post_option( $post->ID, 'overview' );
				$episode_runtime = fw_get_db_post_option( $post->ID, 'episode_runtime' );
				$first_air_date  = fw_get_db_post_option( $post->ID, 'first_air_date' );
				$creators        = fw_get_db_post_option( $post->ID, 'creators' );
				$actor_lists     = get_the_terms( $post->ID, 'mv_actor' );
				?>
				<div class="col-md-12 col-sm-12 col-xs-12 item list-group-item">
					<div class="movie-item">
						<div class="movie-thumbnail">
							<?php if ( ! empty( $thumbnail_id ) ) : ?>
								<a href="<?php the_permalink(); ?>">
									<?php echo wp_get_attachment_image( $thumbnail_id, 'blockter-poster-movie-item' ); ?>
									<span class="readmore-btn"><?php echo esc_html__( 'Read more', 'blockter' ); ?><i class="ion-android-arrow-dropright"></i></span>
								</a>
							<?php endif; ?>
						</div>
						<div class="movie-content">
							<h6 class="mv-title"><a itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
							<?php $feedback = fw()->extensions->get( 'feedback' ); ?>
							<?php if ( null !== $feedback && comments_open() && get_comments_number() ) : ?>
								<div class="rate-average"><div class="left-it"><span class="fa fa-star icon"></span><div class="inner-cmt-infor"><?php $average = fw_ext_feedback_stars_get_post_rating(); ?><div class="rate-num"><span><?php echo esc_html( number_format( $average['average'] ) ); ?></span><span class="sm-text"><?php echo esc_html__( '/', 'blockter' ); ?></span><span class="sm-text"><?php $star = fw_ext_feedback_stars_get_post_detailed_rating( $post->ID ); echo esc_html( count( $star['stars'] ) ); ?></span></div></div></div></div>
							<?php endif; ?>
							<div class="mv-list-content">
								<?php if ( ! empty( $overview ) ) : ?><div class="mv-des"><?php echo wp_kses_post( $overview ); ?></div><?php endif; ?>
								<div class="flex-it movie-details">
									<?php if ( ! empty( $episode_runtime ) ) : ?><span><?php echo esc_html__( 'Episode Runtime: ', 'blockter' ) . esc_html( $episode_runtime ); ?></span><?php endif; ?>
									<?php if ( ! empty( $tagline ) ) : ?><span><?php echo esc_html__( 'Tagline: ', 'blockter' ) . esc_html( $tagline ); ?></span><?php endif; ?>
									<?php if ( ! empty( $first_air_date ) ) : ?><span><?php echo esc_html__( 'First Air Date: ', 'blockter' ) . esc_html( $first_air_date ); ?></span><?php endif; ?>
								</div>
								<?php if ( ! empty( $creators ) ) : ?><p class="mv-directors"><?php echo esc_html__( 'Creators: ', 'blockter' ); ?><span class="link-color"><?php echo esc_html( $creators ); ?></span></p><?php endif; ?>
								<?php if ( ! empty( $actor_lists ) ) : ?><p class="mv-stars"><span><?php echo esc_html__( 'Stars: ', 'blockter' ); ?></span><?php foreach ( $actor_lists as $item ) : ?><a href="<?php echo esc_url( get_term_link( $item ) ); ?>"><?php echo esc_html( $item->name ); ?></a><?php endforeach; ?></p><?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			<?php endwhile; ?>
		</div>
		<?php if ( $show->max_num_pages > 1 ) : ?>
			<nav class="movie-pagination">
				<div class="pagination-left"><span><?php echo sprintf( esc_html__( 'TV shows per page: %d shows', 'blockter' ), $show->get( 'posts_per_page' ) ); ?></span></div>
				<div class="pagination-right">
					<?php $current_show = max( 1, absint( get_query_var( 'paged' ) ) ); ?>
					<div class="page-text"><span><?php echo esc_html__( 'Page', 'blockter' ); ?></span><span><?php echo esc_html( $current_show ); ?></span><span><?php echo esc_html__( 'of', 'blockter' ); ?></span><span><?php echo esc_html( $show->max_num_pages ); ?></span><span><?php echo esc_html__( ':', 'blockter' ); ?></span></div>
					<?php echo paginate_links( array( 'base' => str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) ), 'format' => '', 'current' => $current_show, 'total' => $show->max_num_pages, 'prev_text' => '&nbsp;', 'next_text' => '&nbsp;', 'type' => 'list' ) ); ?>
				</div>
			</nav>
		<?php endif; ?>
	<?php else : ?>
		<?php echo esc_html__( 'You do not have any favourite TV shows.', 'blockter' ); ?>
	<?php endif; wp_reset_postdata(); ?>
</div>
