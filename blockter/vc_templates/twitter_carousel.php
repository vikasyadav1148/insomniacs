<?php

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$allclass     = $this->getExtraClass( $class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

?>

<div class="theme-twitter-carousel flw <?php echo strlen( $css_class ) > 0 ? ' ' . trim( esc_attr( $css_class ) ) : ''; ?> <?php echo esc_attr( $allclass ); ?>">

	<?php
		if ( ! empty( $content ) ) {
			echo wpb_js_remove_wpautop( $content );
		}
	?>
</div>