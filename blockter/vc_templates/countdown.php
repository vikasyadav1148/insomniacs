<?php

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$allclass     = $this->getExtraClass( $class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
// var
$time_cl = isset($time_color) && $time_color != '' ? $time_color : '#000';
$day_cl = isset($day_color) && $day_color != '' ? $day_color : '#000';
$hours_cl = isset($hours_color) && $hours_color != '' ? $hours_color : '#000';
$minute_cl = isset($minute_color) && $minute_color != '' ? $minute_color : '#000';
$second_cl = isset($second_color) && $second_color != '' ? $second_color : '#000';
?>
<div class="consult-countdown" style="color: <?php echo esc_attr($time_cl); ?>">
    <div class="ht-box-cms">
        <span class="ht-time ht-days">00</span>
        <p style="color: <?php echo esc_attr($day_cl); ?>"><?php echo esc_html($days); ?></p>
    </div>
    <div class="ht-box-cms">
        <span class="ht-time ht-hours">00</span>
        <p style="color: <?php echo esc_attr($hours_cl); ?>"><?php echo esc_html($hours); ?></p>
    </div>
    <div class="ht-box-cms">
        <span class="ht-time ht-minutes">00</span>
        <p style="color: <?php echo esc_attr($minute_cl); ?>"><?php echo esc_html($minutes); ?></p>
    </div>
    <div class="ht-box-cms">
        <span class="ht-time ht-seconds">00</span>
        <p style="color: <?php echo esc_attr($second_cl); ?>"><?php echo esc_html($seconds); ?></p>
    </div>
</div>