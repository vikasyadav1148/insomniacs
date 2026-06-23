<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $inline_css, ' ' ) . $this->getExtraClass( $class ) . $this->getCSSAnimation( $css_animation );
$all_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

/*set columns*/
global $post;
$args = array(
    'post_type' => 'ht_show',
);
$query = new WP_Query();
$query->query( $args );
if ( ! $query ) {
    return;
}

if ( $query->have_posts() ) :
    $a = array(
        'tax'      => 'mv_genre', // Taxonomy
        'terms'    => $data_generes, // Get specific taxonomy terms only
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
        <div class="filter-cate-mobile" id="<?php echo esc_attr($drop_id);?>">
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
            <button
                data-filter="<?php echo esc_attr($terms[0]->taxonomy); ?>"
                data-term="all-terms"
                data-page="1"
                data-media="show"
                class="active"
            >
                <?php esc_html_e('All', 'blockter'); ?>
            </button>
            <?php foreach ($items as $item) : ?>
                <button
                    data-filter="<?php echo esc_attr('mv_genre'); ?>"
                    data-term="<?php echo esc_attr($item); ?>"
                    data-media="show"
                >
                    <?php echo esc_html($item); ?>
                </button>
            <?php endforeach; ?>
        </div>
        <!--for mobile version-->
        <div class="jompesh filter-cate-mobile" id="<?php echo esc_attr($drop_id);?>" >
            <div class="flex-parent-cate">
                <div class="category-filter flw dropdown">
                    <button class="dropbtn" data-page="1"><?php echo esc_html__("SORT BY", 'blockter'); ?></button>
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
                        <?php foreach ($items as $item) : ?>
                            <li>
                                <button
                                    data-filter="<?php echo esc_attr('mv_genre'); ?>"
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
        <div class="category-content flw"></div>
    <?php endif; ?>
    </div>
<?php endif; ?>
