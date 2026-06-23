<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $inline_css, ' ' ) . $this->getExtraClass( $class );
$all_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

if(!empty($imgs)):

$gallery = shortcode_atts(array('imgs' => 'imgs',), $atts );
$image_ids=explode(',',$gallery['imgs']);

?>
<div class="theme-brand-logo <?php echo esc_attr($brand_logo_style); ?> flw<?php echo esc_attr( $all_class ); ?> ">
		
	<?php
		echo '<h3 class="brand-logo-title">' .$brand_logo_title. '</h3>';
		foreach ($image_ids as $key => $value) {
			$single_img = wp_get_attachment_image_src($value, "full");
			echo '<div class="brand-logo-item text-center">';
			echo '<img src="'.esc_url($single_img[0]).'" alt="'.esc_html__('Brand Logo image', 'blockter').'">';
			echo '</div>';
		}
	?>
</div>
<?php endif; ?>