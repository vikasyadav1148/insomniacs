<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$casts_array = '';
if ( 'auto' === $data && $count ) {
	$casts       = get_terms( 'mv_actor' );
	$casts_array = array_slice( $casts, 0, $count, true );
} elseif ( $data_cast_slugs ) {
	$cast_slugs = explode( ', ', $data_cast_slugs );
}
?>
<div class="theme-cast-items">
	<h4 class="cast-title"><?php echo esc_html( $cast_title ); ?></h4>

	<?php
	if ( $casts_array && count( $casts_array ) > 0 ) {
		foreach ( $casts_array as $cast ) {
			$cast_id      = $cast->term_id;
			$cast_name    = $cast->name;
			$cast_url     = get_term_link( $cast );
			$cast_options = fw_get_db_term_option( $cast_id, 'mv_actor' );

			blockter_casts_list( $cast_name, $cast_url, $cast_options );
		}
	} elseif ( $cast_slugs && count( $cast_slugs ) > 0 ) {
		foreach ( $cast_slugs as $cast_slug ) {
			$cast         = get_term_by( 'slug', $cast_slug, 'mv_actor' );
			$cast_id      = $cast->term_id;
			$cast_name    = $cast->name;
			$cast_url     = get_term_link( $cast );
			$cast_options = fw_get_db_term_option( $cast_id, 'mv_actor' );

			blockter_casts_list( $cast_name, $cast_url, $cast_options );
		}
	}
	?>
</div><!-- .theme-cast-items -->
