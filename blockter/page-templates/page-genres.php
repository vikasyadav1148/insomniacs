<?php
/*
Template Name: Genre Listing Page
*/

get_header(); ?>

<div class="genre-page">
    <div class="genre-list">
        <?php
        // Get all terms in the genre taxonomy
        $genres = get_terms(array(
            'taxonomy' => 'mv_genre', // Your custom taxonomy for genres
            'hide_empty' => true, // Set to false if you want to show genres with no posts
        ));

        if (!empty($genres) && !is_wp_error($genres)) :
            foreach ($genres as $genre) : ?>
                <div class="genre-item">
                    <a href="<?php echo esc_url(get_term_link($genre)); ?>">
                        <div class="genre-content">
                            <h2><?php echo esc_html($genre->name); ?></h2>
                            <p><?php echo esc_html($genre->description); ?></p> <!-- Description of the genre -->
                        </div>
                    </a>
                </div>
            <?php endforeach;
        else : ?>
            <p>No genres available.</p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
