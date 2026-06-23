<?php

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $inline_css, ' ' ) . $this->getExtraClass( $class );
$all_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$list = (array) vc_param_group_parse_atts( $list );

?>

<div class="theme-tabs<?php echo esc_attr( $all_class ); ?>">
	<div class="theme-tab-nav">
	<?php
		foreach($list as $key => $value):
			$id = str_replace(' ', '', $value['nav']);

			$style = array();
			$output = '';
			if(isset($value['nav_cus']) && $value['nav_cus'] == 'yes'){
				if(!empty($value['txt_color'])){
					$style[] = 'color: ' . $value['txt_color'];
				}
				if(!empty($value['bg_color'])){
					$style[] = 'background-color: ' . $value['bg_color'];
					$style[] = 'border-color: ' . $value['bg_color'];
				}
				if(!empty($value['txt_color']) || !empty($value['bg_color']))
				$output = 'style="' . implode(';', $style) . '"';
			}


			if($key == 0){
				echo '<a href="#content-' . esc_attr($id). '" class="theme-current-nav" ' . $output . '>' . esc_html($value['nav']) . '</a>';
			}else{
				echo '<a href="#content-' . esc_attr($id). '" ' . $output . '>' . esc_html($value['nav']) . '</a>';
			}
		endforeach;
	?>
	</div>
	<div class="theme-tab-wrap">
		<?php
			foreach($list as $key):
				$id = str_replace(' ', '', $key['nav']);
		?>
		<div id="content-<?php echo esc_attr($id); ?>" class="theme-tabs-content">
			<strong><?php echo esc_html($key['title']); ?></strong>
			<p><?php echo esc_html($key['content']); ?></p>
		</div>
		<?php endforeach; ?>
	</div>
</div>