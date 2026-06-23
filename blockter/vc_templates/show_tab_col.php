<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $inline_css, ' ' ) . $this->getExtraClass( $class ) . $this->getCSSAnimation( $css_animation );
$all_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$hidden_all    = $atts['display_tab_all'];

/*current page*/
$page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
/*set columns*/
global $post;
$args = array(
    'post_type' => 'ht_show',
    'posts_per_page' => $movie_per_tab,

);
$query = new WP_Query();
$query->query( $args );
if ( ! $query ) {
    return;
}

if ( $query->have_posts() ) :
    $a = array(
        'tax'      => 'mv_collection', // Taxonomy
        'terms'    => $data_collection, // Get specific taxonomy terms only
        'active'   => false, // Set active term by ID
        'per_page' => $movie_per_tab // How many posts per page
    );

    $tab_id = uniqid('tabid-');
    $drop_id = uniqid('dropid-');
    $terms  = get_terms($a['tax']);
    $term_curr = $a['terms'];
    $items = explode(',',$term_curr);

?>
    <div
        class="flw container-async"
        id="<?php echo esc_attr( $tab_id ); ?>"
        data-paged="<?php echo esc_attr($a['per_page']); ?>"
    >
    <?php if ( '' == $term_curr ): ?>
        <div class="category-filter flw">
            <button
                data-filter="<?php echo esc_attr($terms[0]->taxonomy); ?>"
                data-term="all-terms"
                data-page="1"
                data-media="show"
                class="active"
            >
                <?php esc_html_e('All', 'blockter'); ?>
            </button>
            <?php foreach ($terms as $term) : ?>
            <button
                data-filter="<?php echo esc_attr($term->taxonomy); ?>"
                data-term="<?php echo esc_attr($term->slug); ?>"
                data-page="1"
                data-media="show"
            >
                <?php echo esc_html($term->name); ?>
            </button>
            <?php endforeach; ?>
        </div>
        <!--for mobile version-->
        <div class="jompesh filter-cate-mobile" id="<?php echo esc_attr($drop_id);?>">
            <div class="flex-parent-cate">
                <div class="category-filter flw dropdown">
                    <button class="dropbtn"  data-page="1"><?php echo esc_html__("SORT BY", 'blockter'); ?></button>
                    <ul class="dropdown-content">
                        <li>
                            <button
                                data-filter="<?php echo esc_attr($terms[0]->taxonomy); ?>"
                                data-term="all-terms"
                                data-page="1"
                                data-media="show"
                            >
                                <?php esc_html_e('All', 'blockter'); ?>
                            </button>
                        </li>
                        <?php foreach ($terms as $term) : ?>
                            <li>
                                <button
                                    data-filter="<?php echo esc_attr($term->taxonomy); ?>"
                                    data-term="<?php echo esc_attr($term->slug); ?>"
                                    data-page="1"
                                    data-media="show"
                                >
                                    <?php echo esc_html($term->name); ?>
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="category-content flw"></div>
    <?php else: ?>
        <div class="category-filter flw">
          <?php if ( 'no' !== $hidden_all ) : ?>

            <button
                data-filter="<?php echo esc_attr($terms[0]->taxonomy); ?>"
                data-term="all-terms"
                data-page="1"
                data-media="show"
                class="active"
            >
                <?php esc_html_e('All', 'blockter'); ?>
            </button>
            <?php endif; ?>
            <?php foreach ($items as $item) : ?>

                <button
                    data-filter="<?php echo esc_attr('mv_collection'); ?>"
                    data-term="<?php echo esc_attr($item); ?>"
                    data-media="show"
                >
                    <?php echo esc_html($item); ?>
                </button>
            <?php endforeach; ?>
        </div>
        <!--for mobile version-->
        <div class="filter-cate-mobile" id="<?php echo esc_attr($drop_id);?>" >
            <div class="flex-parent-cate">
                <div class="category-filter flw dropdown">
                    <button class="dropbtn" data-page="1"><?php echo esc_html__("SORT BY", 'blockter'); ?></button>
                    <ul class="dropdown-content">
                          <?php if ( 'no' !== $hidden_all ) : ?>
                        <li>
                            <button
                                data-filter="<?php echo esc_attr($terms[0]->taxonomy); ?>"
                                data-term="all-terms"
                                data-page="1"
                                data-media="show"
                            >
                                <?php esc_html_e('All', 'blockter'); ?>
                            </button>
                        </li>
                        <?php endif; ?>
                        <?php foreach ($items as $item) : ?>
                            <li>
                                <button
                                    data-filter="<?php echo esc_attr('mv_collection'); ?>"
                                    data-term="<?php echo esc_attr($item); ?>"
                                    data-media="show"
                                >
                                    <?php echo esc_html($item); ?>
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="category-content flw">
            <div class="movie-grid-items  ht-grid ht-grid-4">

                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <?php  $thumbnail_id = get_post_thumbnail_id( get_the_ID() ); ?>
                        <div class="ht-grid-item">
                            <div class="movie-grid-it">
                                <div class="movie-thumbnail">
                                    <a href="<?php echo esc_url( get_the_permalink() ); ?>">
                                    <?php
                                    if ( ! empty( $thumbnail_id ) ) :

                                        echo wp_kses(
                                            wp_get_attachment_image( $thumbnail_id, 'blockter-poster-movie-item-fw' ),
                                            array(
                                                'img' => array(
                                                    'width' => array(),
                                                    'hight' => array(),
                                                    'src'   => array(),
                                                    'alt'   => array(),
                                                    'class' => array(),
                                                ),
                                            )
                                        );
                                    else :
                                        ?>
                                        <img src="<?php echo esc_url( get_template_directory_uri() . '/images/poster.png' ); ?>" alt="<?php echo esc_attr( 'Poster Placeholder' ); ?>">
                                        <?php
                                    endif;
                                    ?>
                                        <span class="readmore-btn">
                                            <?php echo esc_html__( 'Read More', 'blockter' ); ?>
                                            <i class="ion-android-arrow-dropright"></i>
                                        </span>
                                    </a>
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
                        </div>
                    <?php endwhile; ?>
                </div>
                <?php blockter_ajax_pager($query,$page, $media = 'show'); ?>
        </div>
    <?php endif; ?>
    </div>
<?php endif; ?>