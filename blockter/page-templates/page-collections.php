<?php
/*
Template Name: Movie Collections Page
*/

get_header(); ?>

<div class="collections-page">
    <div class="collections-list">
        <?php
        // Get all terms in the mv_collection taxonomy
        $collections = get_terms(array(
            'taxonomy' => 'mv_collection',
            'hide_empty' => true, // Set to false if you want to show collections with no posts
        ));

        if (!empty($collections) && !is_wp_error($collections)) :
            foreach ($collections as $collection) : ?>
        <div class="collection-item">
            <a href="<?php echo esc_url(get_term_link($collection)); ?>">
                <div class="collection-content">
                    <h2><?php echo esc_html($collection->name); ?></h2>
                    <p><?php echo esc_html($collection->description); ?></p> <!-- Description of the collection -->
                </div>
            </a>
        </div>
        <?php endforeach;
        else : ?>
        <p>No collections available.</p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>