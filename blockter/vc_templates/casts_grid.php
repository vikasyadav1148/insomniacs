<?php
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);
/*set columns*/
?>
<div class="theme-celebrity-items grid-group movie-items <?php echo esc_attr($cast_style); ?>">
    <?php 
    $terms_casts = get_terms('mv_actor', array(
        'orderby' => 'term_order', // Order by term order
        'order' => 'ASC', // Ascending order, or 'DESC' for descending
        'hide_empty' => false // Show even terms without posts
    ));
    $terms = array_slice($terms_casts, 0, $count, true);
    foreach ($terms as $term):
        $term_id = $term->term_id;
        $cast_name = $term->name;
        $cast_url = get_term_link($term);
        
        // Retrieve term meta data
        $cast_terms = fw_get_db_term_option($term_id, 'mv_actor');
        
        // Extract details from term meta
        $avatar_url = isset($cast_terms['avatar_url']) ? $cast_terms['avatar_url'] : '';
        $dateofbirth = isset($cast_terms['dateofbirth']) ? $cast_terms['dateofbirth'] : '';
        $gender = isset($cast_terms['gender']) ? $cast_terms['gender'] : '';
        $country = isset($cast_terms['country']) ? $cast_terms['country'] : '';
        $facebook_link = isset($cast_terms['facebook_link']) ? $cast_terms['facebook_link'] : '';
        $twitter_link = isset($cast_terms['twitter_link']) ? $cast_terms['twitter_link'] : '';
        $instagram_link = isset($cast_terms['instagram_link']) ? $cast_terms['instagram_link'] : '';
    ?>
    <div class="celebrity-grid-item celeb-list-it item grid-group-item">
        <?php if ($avatar_url): ?>
        <a class="celebrity-img" href="<?php echo esc_url($cast_url); ?>">
            <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($cast_name); ?>">
        </a>
        <?php else: ?>
        <a class="celebrity-img" href="<?php echo esc_url($cast_url); ?>">
            <div class="no-image">No Image Available</div>
        </a>
        <?php endif; ?>
        <div class="celebrity-infor">
            <h4 class="celebrity-name">
                <a class="actor-name" href="<?php echo esc_url($cast_url); ?>"><?php echo esc_html($cast_name); ?></a>
            </h4>
            <?php if (!empty($cast_terms['knowfor'])): ?>
            <span class="ceb-knowfor">
                <?php echo esc_html($cast_terms['knowfor']); ?>
            </span>
            <?php endif; ?>
            <?php if (!empty($cast_terms['country'])): ?>
            <span class="ceb-country">
                <?php echo esc_html($cast_terms['country']); ?>
            </span>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>