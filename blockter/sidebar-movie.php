<?php
/**
 * The sidebar containing the celebrity widget area.
 *
 */

if ( ! is_active_sidebar( 'sidebar_movie' ) ) :
	return;
endif;

?>
<div id="secondary" class="widget-area sidebar sidebar-movie" role="complementary">
	<?php dynamic_sidebar( 'sidebar_movie' ); ?>
</div>
