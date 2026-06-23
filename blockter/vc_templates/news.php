<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $inline_css, ' ' ) . $this->getExtraClass( $class ) . $this->getCSSAnimation( $css_animation );
$all_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
/*set columns*/
$col = '';
if($blog_ppr == 1){
    $col = 12;
}elseif($blog_ppr == 2){
    $col = 6;
}elseif($blog_ppr == 3){
    $col = 4;
}else{
    $col = 3;
}
global $post;
$sticky = $ignore_sticky_posts == 'yes' ? true : false;
$query = null;   // re-sets query
$temp = $query;  // re-sets query
$args = array(
    'post_type' => 'post',
    'showposts' => $count,
    'ignore_sticky_posts' => $sticky,
    'orderby' => $order_by,
    'order' => $sort_order
);
$query = new WP_Query();
$query->query( $args );

if ( ! $query ) {
    return;
}

if(empty($count))
    $count = -1;

if(empty($color1))
    $color1 = 'rgba(1,83,229,0.75)';
if(empty($color2))
    $color2 = 'rgba(183,12,255,0.75)';

?>
<?php if( $query->have_posts() ): ?>
    <div class="row">
        <div class="consult-blog-sc <?php echo esc_attr($blog_style); ?> flw">
            <?php
                /*query*/
                
                $query = new WP_Query('post_type=post&post_status=publish&ignore_sticky_posts=true&posts_per_page='.$count);
                while($query->have_posts()): $query->the_post();
                    /*thumbnail id*/
                    $thumbnail_id = get_post_thumbnail_id($post->ID);
            ?>
            
            <article class="blog-news-item  col-md-<?php echo esc_attr($col); ?>" itemid="<?php echo get_permalink(); ?>" itemscope itemtype="http://schema.org/BlogPosting">
                <div class="sc-blog-item" itemprop="mainEntityOfPage">
                    <?php if($blog_style == 'consult-blog-style-1'): ?>
                        <?php if($thumb == 'yes' && !empty($thumbnail_id)): ?>
                            <div class="sc-blog-image">
                                <a href="<?php the_permalink(); ?>" class="sc-blog-link">
                                    <?php  
                                        echo wp_get_attachment_image($thumbnail_id, 'medium' );
                                    ?>
                                </a>                        
                            </div>
                        <?php endif; ?>
                    <?php elseif($blog_style == 'consult-blog-style-3'): ?>
                        <?php if($thumb == 'yes' && !empty($thumbnail_id)): ?>
                            <div class="sc-blog-image">
                                <a href="<?php the_permalink(); ?>" class="sc-blog-link">
                                    <?php  
                                            echo wp_get_attachment_image($thumbnail_id, 'medium' );
                                    ?>
                                </a>                        
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="blog-post-content">
                        <h2 class="post-tit" itemprop="headline">
                            <?php if(is_single()): ?>
                                <?php the_title(); ?>
                                <div class="blog-post-infor posted_date">
                                    <?php echo blockter_post_date(); ?>
                                </div>
                            <?php else: ?>
                                <a itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            <?php endif; ?>
                            <?php /*sticky post*/
                                if(empty($thumbnail_id)):
                                    if(is_sticky()){
                                        echo '<span class="theme-sticky">'.esc_html__('STICKY', 'blockter').'</span>';
                                    }
                                endif;
                            ?>
                        </h2>
                        <ul class="blog-post-info">
                            <?php if($blog_style == 'consult-blog-style-1'): ?>
                                <li class="posted_date">
                                    <?php echo blockter_post_date(); ?>
                                </li>
                                <?php elseif($blog_style == 'consult-blog-style-2' || $blog_style == 'consult-blog-style-3'): ?>
                                <li>
                                    <?php echo time_ago(); ?>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <?php if($blog_style == 'consult-blog-style-1' || $blog_style == 'consult-blog-style-3'): ?>
                            <div class="blog-post-sumary" itemprop="description"><?php the_excerpt(); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
            <?php
                endwhile;
            ?>
        </div>
    </div>
<?php 
    endif;
    $query = null;
    $query = $temp; // Reset
?>