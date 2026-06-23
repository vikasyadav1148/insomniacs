<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $inline_css, ' ' ) . $this->getExtraClass( $class ) . $this->getCSSAnimation( $css_animation );
$all_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$data_movies = explode(',', $data_movies);
$data_terms = array();

/*current page*/
$page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
/*get post*/
global $post;
$args = array(
    'post_type' => 'ht_movie',
    'post_status'    => 'publish',
    'paged' => $page,
    'posts_per_page' => $movie_per_tab,
    'post__in' => $data_movies,
);

$qry = new WP_Query();
$qry->query( $args );
if ( ! $qry ) {
    return;
}

$class_fw = '';
if($movie_style == 'movie-tab-style-fw') {
    $class_fw = ' full';
}

?>
<?php if( $qry->have_posts() ): ?>
    <?php
    switch ($data) {

        case 'collections':
            $data_tax = 'mv_collection';
            $mv_terms = wp_get_object_terms( $data_movies, $data_tax );
            if(isset($mv_terms)) {
                foreach ($mv_terms as $mv_term) {
                    $data_terms[] .= $mv_term->slug;
                }
            }
            $a = array(
                'tax'      => $data_tax, // Taxonomy
                'terms'    => $data_terms, // Get specific taxonomy terms only
                'active'   => false, // Set active term by ID
                'per_page' => $movie_per_tab, // How many posts per page
                'postin'   => $data_movies
            );
            break;
        case 'casts':
            $data_tax = 'mv_actor';
            $mv_terms = wp_get_object_terms( $data_movies, $data_tax );
            if(isset($mv_terms)) {
                foreach ($mv_terms as $mv_term) {
                    $data_terms[] .= $mv_term->slug;
                }
            }
            $a = array(
                'tax'      => $data_tax, // Taxonomy
                'terms'    => $data_terms, // Get specific taxonomy terms only
                'active'   => false, // Set active term by ID
                'per_page' => $movie_per_tab, // How many posts per page
                'postin'   => $data_movies
            );
            break;
        default:
            $data_tax = 'mv_genre';
            $mv_terms = wp_get_object_terms( $data_movies, $data_tax );
            if(isset($mv_terms)) {
                foreach ($mv_terms as $mv_term) {
                    $data_terms[] .= $mv_term->slug;
                }
            }
            $a = array(
                'tax'      => $data_tax, // Taxonomy
                'terms'    => $data_terms, // Get specific taxonomy terms only
                'active'   => false, // Set active term by ID
                'per_page' => $movie_per_tab, // How many posts per page
                'postin'   => $data_movies
            );
    }

    ?>

    <?php
        $tab_id = uniqid('tabid-');
        $drop_id = uniqid('dropid-');
    ?>

    <div class="flw container-async" id="<?php echo esc_attr( $tab_id ); ?>" data-paged="<?php echo esc_attr($a['per_page']); ?>">
        <?php  $terms  = get_terms($a['tax']);
                $items = $a['terms'];
                $postins = $a['postin'];
                $postin = implode(',', $postins);
        ?>
        <?php if($items == '') : ?>
        	<?php if ( $display_filter == 'yes' ): ?>
	            <div class="category-filter flw<?php echo esc_attr($class_fw); ?>">
	                <?php if( $display_tab_all != 'no') : ?>
	                <button data-filter="<?php echo esc_attr($terms[0]->taxonomy); ?>" data-term="all-terms" data-page="1" data-media="movie" class="active"><?php esc_html_e('All', 'blockter'); ?></button>
	                <?php endif; ?>
	                <?php foreach ($terms as $term) : ?>
	                <button data-filter="<?php echo esc_attr($term->taxonomy); ?>" data-term="<?php echo esc_attr($term->slug); ?>" data-page="1" data-media="movie" data-postin="<?php echo esc_attr($postin); ?>"><?php echo esc_html($term->name); ?></button>
	                <?php endforeach; ?>
	            </div>
	        <?php endif; ?>
            <!--for mobile version-->
            <div class="jompesh filter-cate-mobile" id="<?php echo esc_attr($drop_id);?>">
				<div class="flex-parent-cate">
					<div class="category-filter flw dropdown">
						<button class="dropbtn" data-page="1"><?php echo esc_html__("SORT BY", 'blockter'); ?></button>
						<ul class="dropdown-content">
                            <?php if( $display_tab_all != 'no') : ?>
                                <li><button data-filter="<?php echo esc_attr($terms[0]->taxonomy); ?>" data-term="all-terms" data-page="1" data-media="movie"><?php esc_html_e('All', 'blockter'); ?></button></li>
                            <?php endif; ?>
							<?php foreach ($terms as $term) : ?>
                                <li><button data-filter="<?php echo esc_attr($term->taxonomy); ?>" data-term="<?php echo esc_attr($term->slug); ?>" data-page="1" data-media="movie"><?php echo esc_html($term->name); ?></button></li>
							<?php endforeach; ?>
						</ul>
		            </div>
				</div>
			</div>
            <div class="category-content specific<?php echo esc_attr($class_fw); ?>">
                <div class="movie-grid-items">
                    <?php while ($qry->have_posts()) : $qry->the_post(); ?>
                        <?php  $thumbnail_id = get_post_thumbnail_id($post->ID); ?>
                        <div class="movie-grid-it">
                            <div class="movie-thumbnail">
                                    <?php if(!empty($thumbnail_id)): ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php echo wp_get_attachment_image($thumbnail_id, 'blockter-poster-movie-item-fw');?>
                                            <span class="readmore-btn"><?php echo esc_html__("Read more", 'blockter');?><i class="ion-android-arrow-dropright"></i></span>
                                        </a>

                                    <?php endif; ?>
                            </div>
                            <div class="movie-content">
                                <h6 class="mv-title"><a itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
                                <?php $feedback = fw()->extensions->get( 'feedback' ); ?>
                                <?php if($feedback != null): ?>
                                <?php if(comments_open() && get_comments_number()): ?>
                                    <div class="rate-average">
                                        <div class="left-it">
                                            <span class="fa fa-star icon"></span>
                                            <div class="inner-cmt-infor">
                                                <?php   $average = fw_ext_feedback_stars_get_post_rating();?>
                                                <div class="rate-num">
                                                    <span><?php echo esc_html(number_format($average['average']),0); ?></span>
                                                    <span class="sm-text"><?php echo esc_html__("/", 'blockter'); ?></span>
                                                    <span class="sm-text"><?php
                                                    $star = fw_ext_feedback_stars_get_post_detailed_rating($post->ID);
                                                    echo count($star['stars']);
                                                    ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <?php blockter_ajax_pager($qry,$page, $media = 'movie'); ?>
            </div>
        <?php else: ?>
        	<?php if ( $display_filter == 'yes' ): ?>
	            <div class="category-filter flw">
	                <?php if( $display_tab_all != 'no') : ?>
	                <button data-filter="<?php echo esc_attr($terms[0]->taxonomy); ?>" data-term="all-terms" data-page="1" data-media="movie" class="active"><?php esc_html_e('All', 'blockter'); ?></button>
	                <?php endif; ?>
	                <?php foreach ($items as $item) : ?>
	                    <?php if($data == 'generes'): ?>
	                        <button  data-filter="<?php echo esc_attr('mv_genre'); ?>" data-term="<?php echo esc_attr($item); ?>" data-media="movie" data-postin="<?php echo esc_attr($postin); ?>"><?php echo esc_html($item); ?></button>
	                    <?php elseif($data == 'collections'): ?>
	                        <button  data-filter="<?php echo esc_attr('mv_collection'); ?>" data-term="<?php echo esc_attr($item); ?>" data-media="movie" data-postin="<?php echo esc_attr($postin); ?>"><?php echo esc_html($item); ?></button>
	                    <?php else: ?>
	                        <button  data-filter="<?php echo esc_attr('mv_actor'); ?>" data-term="<?php echo esc_attr($item); ?>" data-media="movie" data-postin="<?php echo esc_attr($postin); ?>"><?php echo esc_html($item); ?></button>
	                    <?php endif; ?>
	                <?php endforeach; ?>
	            </div>
	        <?php endif; ?>
            <!--for mobile version-->
			<div class="filter-cate-mobile" id="<?php echo esc_attr($drop_id);?>" >
				<div class="flex-parent-cate">
					<div class="category-filter flw dropdown">
						<button class="dropbtn" data-page="1"><?php echo esc_html__("SORT BY", 'blockter'); ?></button>
						<ul class="dropdown-content">
                            <?php if( $display_tab_all != 'no') : ?>
                                <li><button data-filter="<?php echo esc_attr($terms[0]->taxonomy); ?>" data-term="all-terms" data-page="1" data-media="movie"><?php esc_html_e('All', 'blockter'); ?></button></li>
                            <?php endif; ?>
							<?php foreach ($items as $item) : ?>
                                <?php if($data == 'generes'): ?>
                                <li><button data-filter="<?php echo esc_attr('mv_genre'); ?>" data-term="<?php echo esc_attr($item); ?>" data-media="movie"><?php echo esc_html($item); ?></button></li>
                                <?php elseif($data == 'collections'): ?>
                                <li><button data-filter="<?php echo esc_attr('mv_collection'); ?>" data-term="<?php echo esc_attr($item); ?>" data-media="movie"><?php echo esc_html($item); ?></button></li>
                            <?php else: ?>
                                <li><button data-filter="<?php echo esc_attr('mv_actor'); ?>" data-term="<?php echo esc_attr($item); ?>" data-media="movie"><?php echo esc_html($item); ?></button></li>
                                <?php endif; ?>
							<?php endforeach; ?>
						</ul>
		            </div>
				</div>
			</div>
            <div class="category-content specific<?php echo esc_attr($class_fw); ?>">
                <div class="movie-grid-items">
                    <?php while ($qry->have_posts()) : $qry->the_post(); ?>
                        <?php  $thumbnail_id = get_post_thumbnail_id($post->ID); ?>
                        <div class="movie-grid-it">
                            <div class="movie-thumbnail">
                                    <?php if(!empty($thumbnail_id)): ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php echo wp_get_attachment_image($thumbnail_id, 'blockter-poster-movie-item-fw');?>
                                            <span class="readmore-btn"><?php echo esc_html__("Read more", 'blockter');?><i class="ion-android-arrow-dropright"></i></span>
                                        </a>

                                    <?php endif; ?>
                            </div>
                            <div class="movie-content">
                                <h6 class="mv-title"><a itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
                                <?php $feedback = fw()->extensions->get( 'feedback' ); ?>
                                <?php if($feedback != null): ?>
                                <?php if(comments_open() && get_comments_number()): ?>
                                    <div class="rate-average">
                                        <div class="left-it">
                                            <span class="fa fa-star icon"></span>
                                            <div class="inner-cmt-infor">
                                                <?php   $average = fw_ext_feedback_stars_get_post_rating();?>
                                                <div class="rate-num">
                                                    <span><?php echo esc_html(number_format($average['average']),0); ?></span>
                                                    <span class="sm-text"><?php echo esc_html__("/", 'blockter'); ?></span>
                                                    <span class="sm-text"><?php
                                                    $star = fw_ext_feedback_stars_get_post_detailed_rating($post->ID);
                                                    echo count($star['stars']);
                                                    ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <?php blockter_ajax_pager($qry,$page, $media = 'movie'); ?>
            </div>

        <?php endif; ?>
    </div>
<?php
    endif;
?>
