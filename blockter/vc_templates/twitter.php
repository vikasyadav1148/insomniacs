<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
?>
<div class="theme-twitter-item">
    <h4 class="tw-title"><?php echo esc_html($tw_title); ?></h4>
    <div class="twitter-it tweet"  id="<?php  echo esc_html($id); ?>"></div>
</div>