<?php

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

// $img = wp_get_attachment_image_src($atts[""], 'large');
$allclass     = $this->getExtraClass( $class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

$heading_style = array();
if(!empty($color) ||  ! empty( $font_size ) ):
    $heading_style[] = 'style="color: ' . esc_attr($color) . '; font-size: '.esc_attr($font_size).';"';
endif;
$heading_style = implode( ' ', $heading_style );

?>

<div class="consult-light-box flw <?php echo strlen( $css_class ) > 0 ? ' ' . trim( esc_attr( $css_class ) ) : ''; ?> <?php echo esc_attr( $allclass ); ?>">
	<?php if(!empty($left_heading) && !empty($right_heading) ) : ?>

		<?php echo '<h1 '.esc_attr($heading_style).';">'.esc_html($left_heading).'</h1>'; ?>
			<a href="<?php echo esc_url($light_url); ?>" class="consult-lightbox-popup <?php echo esc_attr($light_icon); ?>"></a>
		<?php echo '<h1 '.esc_attr($heading_style).';">'.esc_html($right_heading).'</h1>'; ?>

	<?php else : ?>
		<a href="<?php echo esc_url($light_url); ?>" class="consult-lightbox-popup <?php echo esc_attr($light_icon); ?>"></a>
	<?php endif; ?>
</div>