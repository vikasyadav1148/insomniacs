<?php
/**
 * The sidebar containing the celebrity widget area.
 *
 */

if ( ! is_active_sidebar( 'sidebar_celebrity' ) ) :
	return;
endif;

?>
<div id="secondary" class="widget-area sidebar sidebar-celebrity" role="complementary">
	<?php dynamic_sidebar( 'sidebar_celebrity' ); ?>
</div>
