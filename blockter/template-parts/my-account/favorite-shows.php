<?php
global $current_user, $wp_roles, $post;
/* Favourite TV shows */
$args_all_show = array(
	'post_type' => 'ht_show',
	'orderby' => 'name',
	'order' => 'ASC',
	'posts_per_page' => 4,
	'paged' => $paged,
);
$fav_show = get_user_meta( $current_user->ID ,'favourite_show_id');
if (empty($fav_show)) {
	$fav_s = array(
		'post__in' => array(0),
	);
} else {
	$fav_s = array(
		'post__in' => $fav_show,
	);
}

$fav_args_show = array_merge(
	$args_all_show,
	$fav_s
);

$fav_show = new WP_Query();
$fav_show->query( $fav_args_show );

$paged_fav_show    = max( 1, $fav_show->get( 'paged' ) );
$per_page_fav_show = $fav_show->get( 'posts_per_page' );
$total_fav_show    = $fav_show->found_posts;
$first_fav_show    = ( $per_page_fav_show * $paged_fav_show ) - $per_page_fav_show + 1;
$last_fav_show     = min( $total_fav_show, $fav_show->get( 'posts_per_page' ) * $paged_fav_show );
?>
<div id="favourite-shows">
	<!-- top bar filter -->
	<?php if( $fav_show->have_posts() ):  ?>
		<div class="celebrity-topbar-filter">
			<div class="celebrity-result-count">
				<?php

				if ( $total_fav_show <= $per_page_fav_show || -1 === $per_page_fav_show ) {
					/* translators: %d: total results */
					printf( _n( 'Showing the single result', 'Showing all %d movies', $total_fav_show, 'blockter' ), $total_fav_show );
				} else {
					/* translators: 1: first result 2: last result 3: total results */
					printf( _nx( 'Showing the single result', 'Found <strong>%3$d movies</strong> in total', $total_fav_show, 'with first and last result', 'blockter' ), $first_fav_show, $last_fav_show, $total_fav_show );
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
						$modifications = array();
						if( !empty($sortby) && $sortby == 'default' ) {
							$modifications = array(
								'orderby' => 'ID',
								'order' => 'ASC'
							);
						}
						if( !empty($sortby) && $sortby == 'post_title' ) {
							$modifications = array(
								'orderby' => 'title',
								'order' => 'ASC'
							);
						}
						$args = array_merge(
							$fav_show->query_vars,
							$modifications
						);

						$fav_show = new WP_Query( $args );
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
			<?php

			while($fav_show->have_posts()): $fav_show->the_post();
				$thumbnail_id = get_post_thumbnail_id($post->ID);
				$tagline = fw_get_db_post_option($post->ID, 'tagline');
				$overview = fw_get_db_post_option($post->ID, 'overview');
				$episode_runtime = fw_get_db_post_option($post->ID, 'episode_runtime');
				$first_air_date = fw_get_db_post_option($post->ID, 'first_air_date');
				$creators = fw_get_db_post_option($post->ID, 'creators');
				$actor_lists = get_the_terms( $post->ID, 'mv_actor' );
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
							<?php $feedback = fw()->extensions->get( 'feedback' );?>
							<?php if($feedback != null): ?>
								<?php if(comments_open() && get_comments_number()): ?>
									<div class="rate-average">
										<div class="left-it">
											<span class="fa fa-star icon"></span>
											<div class="inner-cmt-infor">
												<?php  $average = fw_ext_feedback_stars_get_post_rating();?>
												<div class="rate-num">
													<span><?php echo esc_html(number_format($average['average']),0); ?></span>
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
									<div class="mv-des"><?php echo wp_kses_post($overview);?></div>
								<?php endif; ?>
								<div class="flex-it movie-details">
									<?php if(!empty($runtime)): ?>
										<span><?php echo esc_html__("Episode Runtime: ", 'blockter'); ?><?php echo esc_html($episode_runtime);?></span>
									<?php endif; ?>
									<?php if(!empty($tagline)): ?>
										<span><?php echo esc_html__("Tagline: ", 'blockter'); ?><?php echo esc_html($tagline);?></span>
									<?php endif; ?>
									<?php if(!empty($first_air_date)): ?>
										<span><?php echo esc_html__("First Air Date: ", 'blockter'); ?><?php echo esc_html($first_air_date);?></span>
									<?php endif; ?>
								</div>
								<?php if(!empty($creators)): ?>
									<p class="mv-directors"><?php echo esc_html__("Creators: ", 'blockter') ?><span class="link-color"><?php echo esc_html($creators);?></span></p>
								<?php endif; ?>
								<?php if(!empty($actor_lists)): ?>
									<p class="mv-stars">
										<span><?php echo esc_html__('Stars: ', 'blockter'); ?></span>
										<?php foreach($actor_lists as $item): ?>
											<?php $ac_name = $item->name;  $ac_url = get_term_link($item); ?>
											<a href="<?php echo esc_url($ac_url);?>"><?php echo esc_html($ac_name);?></a>
										<?php endforeach; ?>
									</p>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			<?php
			endwhile;
			?>
		</div>
		<?php
		if ( $fav_show->max_num_pages > 1 ) :
			?>
			<nav class="movie-pagination">
				<div class="pagination-left">
					<span><?php echo esc_html__("TV shows per page: ", 'blockter'); ?></span>
					<span><?php echo esc_html__("5 shows", 'blockter'); ?></span>
				</div>
				<div class="pagination-right">
					<?php
					if (!$current_fav_show = get_query_var('paged')) :
						$current_fav_show = 1;
					endif;
					?>
					<div class="page-text">
						<span><?php echo esc_html__('Page', 'blockter');?></span>
						<span><?php echo esc_html($current_fav_show);?></span>
						<span><?php echo esc_html__('of', 'blockter')?></span>
						<span><?php echo esc_html($fav_show->max_num_pages); ?></span>
						<span><?php echo esc_html__(':', 'blockter')?></span>
					</div>
					<?php
					echo paginate_links( array(
						'base'         => str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) ),
						'format'       => '',
						'current'      => $current_fav_show,
						'total'        => $fav_show->max_num_pages,
						'prev_text'    => '&nbsp;',
						'next_text'    => '&nbsp;',
						'type'         => 'list',
					) );
					?>
				</div>

			</nav>
		<?php endif ?>
	<?php
	else :
		echo esc_html__("You do not have any favourite TV shows.",'blockter');
	endif;
	/*reset query*/
	wp_reset_postdata();
	?>
</div><!-- #favourite-shows -->
