<?php
/**
 * The template used for displaying page content
 */
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/WebPage">
	<div class="flw">
	<?php
		the_content();
		wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'blockter' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
		) );
	?>
	</div>
</div>
