<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $inline_css, ' ' ) . $this->getExtraClass( $class ) . $this->getCSSAnimation( $css_animation );
$all_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$img = wp_get_attachment_image_src($atts["img"], 'full');
// if(empty($color1))
//     $color1 = '#0040b1';
// if(empty($color2))
//     $color2 = '#b20efd';


$thumbnail = fw_resize($img[0], 370, 430, true);

?>

<div class="theme-landing-item">
   
    <div class="sc-landing-img">        
        <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php esc_attr_e('Landing image', 'blockter'); ?>">
    </div>
	<a href="<?php echo esc_url($url); ?>" class="sc-landing-link"><span class="land-title"><?php echo esc_html($title); ?></span></a>
    
</div>