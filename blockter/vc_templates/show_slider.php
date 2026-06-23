<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

/*set columns*/
global $post;
$query = null;   // re-sets query
$temp = $query;  // re-sets query
$args = array(
    'post_type'      => 'ht_show',
    'mv_genre'       => $data_generes,
    'posts_per_page' => $count
);

$query = new WP_Query();
$query->query( $args );

if ( ! $query ) {
    return;
}

if(empty($count))
    $count = -1;
?>
<?php $feedback = fw()->extensions->get( 'feedback' );?>
<?php if( $query->have_posts() ): ?>
    <div class="row movie-slider-items <?php echo esc_attr($movie_style); ?>">
        <?php if($movie_style == 'movie-slider-style-1'): ?>
            <div class="movie-grid-items">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <?php  $thumbnail_id = get_post_thumbnail_id($post->ID); ?>
                    <div class="movie-grid-it">
                        <div class="movie-thumbnail">
                                <?php if(!empty($thumbnail_id)): ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php echo wp_get_attachment_image($thumbnail_id, array(320, 400));?>
                                        <span class="readmore-btn"><?php echo esc_html__("Read more", 'blockter');?><i class="ion-android-arrow-dropright"></i></span>
                                    </a>

                                <?php endif; ?>
                        </div>
                        <div class="movie-content">
                            <div class="movie-genres">
                                <?php
                                    $taxonomies = wp_get_post_terms($post->ID, 'mv_genre');
                                    foreach($taxonomies as $tax){
                                        $tax_url = get_term_link($tax);
                                        ?>
                                        <a href="<?php echo esc_url($tax_url);?>"><span class="<?php echo esc_html($tax->slug); ?>"><?php echo esc_html($tax->name); ?></span></a>
                                        <?php
                                    }
                                ?>
                            </div>
                            <h6 class="mv-title"><a itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
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
                                                    echo count($star['stars']);?>
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
        <?php  else: ?>
            <div class="movie-grid-items">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <?php  $thumbnail_id = get_post_thumbnail_id($post->ID);
                            $release_date = fw_get_db_post_option($post->ID, 'release_date');
                            $runtime = fw_get_db_post_option($post->ID, 'runtime');
                            $tagline = fw_get_db_post_option($post->ID, 'tagline');
                            $permalink  = urlencode( get_the_permalink() );
                            $title      = urlencode( get_the_title() );
                            $video = fw_get_db_post_option($post->ID, 'video');
                    ?>
                    <div class="movie-grid-it-style-2">
                        <div class="row">
                            <?php if($movie_style == 'movie-slider-style-2'):?>
                            <div class="col-md-9">
                            <?php elseif($movie_style == 'movie-slider-style-3'): ?>
                            <div class="col-md-12">
                            <?php  endif;?>
                                <div class="movie-content">
                                    <div class="movie-genres">
                                        <?php
                                            $taxonomies = wp_get_post_terms($post->ID, 'mv_genre');
                                            foreach($taxonomies as $tax){
                                                $tax_url = get_term_link($tax);
                                                ?>
                                                <a href="<?php echo esc_url($tax_url);?>"><span class="<?php echo esc_html($tax->slug); ?>"><?php echo esc_html($tax->name); ?></span></a>
                                                <?php
                                            }
                                        ?>
                                    </div>
                                    <h2 class="mv-title"><a itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                    <div class="share-buttons">
                                        <?php if(!empty($video)): ?>
                                            <div class="trailer-btn">
                                                <?php  $lastVideoId = end($video);  ?>
                                                <a href="https://www.youtube.com/watch?v=<?php echo esc_attr($lastVideoId); ?>" class="item item-2 redbtn fancybox-media hvr-grow"> <span class="ion-play icon-btn"></span><span><?php echo esc_html__("Watch Trailer", 'blockter');?></span></a>
                                            </div>
                                        <?php endif; ?>

                                        <div class="social-share">
                                            <span class="icon-btn ion-android-share-alt"></span><span><?php echo esc_html__("share", 'blockter'); ?></span>
                                            <div class="social-links">
                                                <span class="fb-share">
                                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr($permalink)?>" target="_blank" class="icon ion-social-facebook"></a>
                                                </span>
                                                <span class="tw-share">
                                                    <a href="http://twitter.com/home?status=<?php echo esc_attr($title) ?>%20<?php echo esc_attr($permalink)?>" target="_blank" class="icon ion-social-twitter"></a>
                                                </span>
                                                <span class="googleplus-share">
                                                    <a href="https://plus.google.com/share?url=<?php echo esc_attr($permalink)?>" target="_blank" class="icon ion-social-googleplus"></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-child-items">
                                        <?php if($feedback != null): ?>
                                        <?php if(comments_open() && get_comments_number()): ?>
                                            <div class="flex-child-it">
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
                                                                    echo count($star['stars']);?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif;?>
                                        <?php endif; ?>
                                        <?php if(!empty($runtime)): ?>
                                            <div class="flex-child-it">
                                                <span><?php esc_html_e('Run Time: ', 'blockter'); ?></span>
                                                <span class="white-text"><?php echo esc_html($runtime); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <?php if(!empty($tagline)): ?>
                                            <div class="flex-child-it">
                                                <span><?php esc_html_e('Tagline:', 'blockter'); ?></span>
                                                <span class="white-text"><?php echo esc_html($tagline); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <?php if(!empty($release_date)): ?>
                                            <div class="flex-child-it">
                                                <span><?php esc_html_e('Release Date: ', 'blockter'); ?></span>
                                                <span class="white-text"><?php echo esc_html($release_date); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($movie_style == 'movie-slider-style-2'): ?>
                                        <a href="<?php the_permalink(); ?>" class="readmore-btn"><?php echo esc_html__("More Detail", 'blockter');?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if($movie_style == 'movie-slider-style-2'): ?>
                                <div class="col-md-3">
                                    <div class="movie-thumbnail">
                                        <?php if(!empty($thumbnail_id)): ?>
                                            <a href="<?php the_permalink(); ?>">
                                                <?php echo wp_get_attachment_image($thumbnail_id, array(320, 400));?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
