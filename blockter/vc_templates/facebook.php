<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
?>
<div class="theme-facebook-item">
    <h4 class="fb-title"><?php echo esc_html($fb_title); ?></h4>
    <iframe src="<?php echo esc_url($link); ?>" data-src="<?php echo esc_url($link); ?>" width="<?php  echo esc_html($width); ?>" height="<?php echo esc_html($height); ?>" style="border:none;overflow:hidden" ></iframe>
</div>