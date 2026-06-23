<?php
/*
Template Name: Networks Page
*/
get_header(); ?>

<style>
    .filter-pill-container {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin: 20px 0 40px 0;
    }
    .filter-pill {
        background: #1a1a1a;
        color: #888;
        padding: 8px 20px;
        border-radius: 25px;
        border: 1px solid #333;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 14px;
    }
    .filter-pill:hover {
        border-color: #e50914;
        color: #fff;
    }
    .filter-pill.active {
        background: #e50914;
        color: #fff;
        border-color: #e50914;
        box-shadow: 0 0 15px rgba(229, 9, 20, 0.3);
    }
    .region-group { transition: opacity 0.4s ease; }
</style>

<div class="collections-page">
    
    <div class="filter-pill-container">
        <div class="filter-pill active" data-region="all">All Regions</div>
        <div class="filter-pill" data-region="uk">🇬🇧 UK Only</div>
        <div class="filter-pill" data-region="us">🇺🇸 US Only</div>
    </div>

    <div id="uk-wrapper" class="region-group">
        <h2 class="region-separator">🇬🇧 UK Streaming Networks</h2>
        <div class="collections-list">
            <?php
            $uk_networks = get_terms(array(
                'taxonomy' => 'networks',
                'child_of' => get_term_by('slug', 'uk', 'networks')->term_id,
                'hide_empty' => false, 
            ));
            if (!empty($uk_networks) && !is_wp_error($uk_networks)) :
                foreach ($uk_networks as $collection) :
                    $image_id = get_term_meta($collection->term_id, 'thumbnail_id', true);
                    $image_url = wp_get_attachment_image_src($image_id, 'full');
            ?>
            <div class="collection-item">
                <a href="<?php echo esc_url(get_term_link($collection)); ?>">
                    <?php if ($image_url): ?><img src="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr($collection->name); ?>" /><?php endif; ?>
                    <div class="collection-content"><h2><?php echo esc_html($collection->name); ?></h2></div>
                </a>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>

    <div id="region-spacer" style="height: 40px; border-bottom: 1px solid #222; margin-bottom: 40px;"></div>

    <div id="us-wrapper" class="region-group">
        <h2 class="region-separator">🇺🇸 US Streaming Networks</h2>
        <div class="collections-list">
            <?php
            $us_networks = get_terms(array(
                'taxonomy' => 'networks',
                'child_of' => get_term_by('slug', 'us', 'networks')->term_id,
                'hide_empty' => false, 
            ));
            if (!empty($us_networks) && !is_wp_error($us_networks)) :
                foreach ($us_networks as $collection) :
                    $image_id = get_term_meta($collection->term_id, 'thumbnail_id', true);
                    $image_url = wp_get_attachment_image_src($image_id, 'full');
            ?>
            <div class="collection-item">
                <a href="<?php echo esc_url(get_term_link($collection)); ?>">
                    <?php if ($image_url): ?><img src="<?php echo esc_url($image_url[0]); ?>" alt="<?php echo esc_attr($collection->name); ?>" /><?php endif; ?>
                    <div class="collection-content"><h2><?php echo esc_html($collection->name); ?></h2></div>
                </a>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.filter-pill').forEach(pill => {
    pill.addEventListener('click', function() {
        // Handle Active State
        document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
        this.classList.add('active');

        // Handle Filtering
        var region = this.getAttribute('data-region');
        var uk = document.getElementById('uk-wrapper');
        var us = document.getElementById('us-wrapper');
        var spacer = document.getElementById('region-spacer');

        if (region === 'uk') {
            uk.style.display = 'block';
            us.style.display = 'none';
            spacer.style.display = 'none';
        } else if (region === 'us') {
            uk.style.display = 'none';
            us.style.display = 'block';
            spacer.style.display = 'none';
        } else {
            uk.style.display = 'block';
            us.style.display = 'block';
            spacer.style.display = 'block';
        }
    });
});
</script>

<?php get_footer(); ?>