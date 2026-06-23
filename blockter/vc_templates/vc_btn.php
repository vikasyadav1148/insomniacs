<?php
$style = $shape = $color = $size = $custom_background = $custom_text = $align = $link = $title = $button_block = $el_class = $outline_custom_color = $outline_custom_hover_background =
$outline_custom_hover_text = $add_icon = $i_align = $i_type = $i_icon_entypo = $i_icon_fontawesome = $i_icon_linecons = $i_icon_pixelicons = $i_icon_typicons = $css = $css_animation = '';
$gradient_color_1 = $gradient_color_2 = $gradient_custom_color_1 = $gradient_custom_color_2 = $gradient_text_color = $color1 = $color2 = $text_color = $wtf = '';
$custom_onclick = $custom_onclick_code = '';
$a_href = $a_title = $a_target = $a_rel = '';
$styles = array();
$icon_wrapper = false;
$icon_html = false;
$attributes = array();

$colors = array(
    'blue' => '#5472d2',
    'turquoise' => '#00c1cf',
    'pink' => '#fe6c61',
    'violet' => '#8d6dc4',
    'peacoc' => '#4cadc9',
    'chino' => '#cec2ab',
    'mulled-wine' => '#50485b',
    'vista-blue' => '#75d69c',
    'orange' => '#f7be68',
    'sky' => '#5aa1e3',
    'green' => '#6dab3c',
    'juicy-pink' => '#f4524d',
    'sandy-brown' => '#f79468',
    'purple' => '#b97ebb',
    'black' => '#2a2a2a',
    'grey' => '#ebebeb',
    'white' => '#ffffff',
);

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

//parse link
$link = ( '||' === $link ) ? '' : $link;
$link = vc_build_link( $link );
$use_link = false;
if ( strlen( $link['url'] ) > 0 ) {
	$use_link = true;
	$a_href = $link['url'];
	$a_title = $link['title'];
	$a_target = $link['target'];
	$a_rel = $link['rel'];
}

$wrapper_classes = array(
	'vc_btn3-container',
	$this->getExtraClass( $el_class ),
	$this->getCSSAnimation( $css_animation ),
	'vc_btn3-' . $align,
);

$button_classes = array(
	'vc_general',
	'vc_btn3',
	'vc_btn3-size-' . $size,
	'vc_btn3-shape-' . $shape,
	'vc_btn3-style-' . $style,
);

$button_html = $title;

if ( '' === trim( $title ) ) {
	$button_classes[] = 'vc_btn3-o-empty';
	$button_html = '<span class="vc_btn3-placeholder">&nbsp;</span>';
}
if ( 'true' === $button_block && 'inline' !== $align ) {
	$button_classes[] = 'vc_btn3-block';
}
if ( 'true' === $add_icon ) {
	$button_classes[] = 'vc_btn3-icon-' . $i_align;
	vc_icon_element_fonts_enqueue( $i_type );

	if ( isset( ${'i_icon_' . $i_type} ) ) {
		if ( 'pixelicons' === $i_type ) {
			$icon_wrapper = true;
		}
		$icon_class = ${'i_icon_' . $i_type};
	} else {
		$icon_class = 'fa fa-adjust';
	}

	if ( $icon_wrapper ) {
		$icon_html = '<i class="vc_btn3-icon"><span class="vc_btn3-icon-inner ' . esc_attr( $icon_class ) . '"></span></i>';
	} else {
		$icon_html = '<i class="vc_btn3-icon ' . esc_attr( $icon_class ) . '"></i>';
	}

	if ( 'left' === $i_align ) {
		$button_html = $icon_html . ' ' . $button_html;
	} else {
		$button_html .= ' ' . $icon_html;
	}
}

if ( 'custom' === $style ) {
	if ( $custom_background ) {
		$styles[] = vc_get_css_color( 'background-color', $custom_background );
	}

	if ( $custom_text ) {
		$styles[] = vc_get_css_color( 'color', $custom_text );
	}

	if ( ! $custom_background && ! $custom_text) {
		$button_classes[] = 'vc_btn3-color-grey';
	}
} elseif ( 'outline-custom' === $style ) {
	if ( $outline_custom_color ) {
		$styles[] = vc_get_css_color( 'border-color', $outline_custom_color );
		$styles[] = vc_get_css_color( 'color', $outline_custom_color );
		$attributes[] = 'onmouseleave="this.style.borderColor=\'' . $outline_custom_color . '\'; this.style.backgroundColor=\'transparent\'; this.style.color=\'' . $outline_custom_color . '\'"';
	} else {
		$attributes[] = 'onmouseleave="this.style.borderColor=\'\'; this.style.backgroundColor=\'transparent\'; this.style.color=\'\'"';
	}

	$onmouseenter = array();
	if ( $outline_custom_hover_background ) {
		$onmouseenter[] = 'this.style.borderColor=\'' . $outline_custom_hover_background . '\';';
		$onmouseenter[] = 'this.style.backgroundColor=\'' . $outline_custom_hover_background . '\';';
	}
	if ( $outline_custom_hover_text ) {
		$onmouseenter[] = 'this.style.color=\'' . $outline_custom_hover_text . '\';';
	}
	if ( $onmouseenter ) {
		$attributes[] = 'onmouseenter="' . implode( ' ', $onmouseenter ) . '"';
	}

	if ( ! $outline_custom_color && ! $outline_custom_hover_background && ! $outline_custom_hover_text ) {
		$button_classes[] = 'vc_btn3-color-inverse';

		foreach ( $button_classes as $k => $v ) {
			if ( 'vc_btn3-style-outline-custom' === $v ) {
				unset( $button_classes[ $k ] );
				break;
			}
		}
		$button_classes[] = 'vc_btn3-style-outline';
	}
} elseif ( 'gradient' === $style || 'gradient-custom' === $style ) {

	$gradient_color_1 = $colors[$gradient_color_1];
	$gradient_color_2 = $colors[$gradient_color_2];

	$button_text_color = "#fff";
	if('gradient-custom' === $style ){
		$gradient_color_1 = $gradient_custom_color_1;
		$gradient_color_2 = $gradient_custom_color_2;
		$button_text_color = $gradient_text_color;
	}

	$gradient_css = array();
	$gradient_css[] = 'color: ' . $button_text_color;
	$gradient_css[] = 'border: none';
	$gradient_css[] = 'background-color: ' . $gradient_color_1;
	$gradient_css[] = 'background-image: -webkit-linear-gradient(left, ' . $gradient_color_1 . ' 0%, ' . $gradient_color_2 . ' 50%,' . $gradient_color_1 . ' 100%)';
	$gradient_css[] = 'background-image: linear-gradient(to right, ' . $gradient_color_1 . ' 0%, ' . $gradient_color_2 . ' 50%,' . $gradient_color_1 . ' 100%)';
	$gradient_css[] = '-webkit-transition: all .2s ease-in-out';
	$gradient_css[] = 'transition: all .2s ease-in-out';
	$gradient_css[] = 'background-size: 200% 100%';

	// hover css
	$gradient_css_hover = array();
	$gradient_css_hover[] = 'color: ' . $button_text_color;
	$gradient_css_hover[] = 'background-color: ' . $gradient_color_2;
	$gradient_css_hover[] = 'border: none';
	$gradient_css_hover[] = 'background-position: 100% 0';

	$uid = uniqid();
	echo '<style>.vc_btn3-style-' . $style . '.vc_btn-gradient-btn-' . $uid . ':hover{' . implode( ';',
			$gradient_css_hover ) . ';' . '}</style>';
	echo '<style>.vc_btn3-style-' . $style . '.vc_btn-gradient-btn-' . $uid . '{' . implode( ';',
			$gradient_css ) . ';' . '}</style>';
	$button_classes[] = 'vc_btn-gradient-btn-' . $uid;
	$attributes[] = 'data-vc-gradient-1="' . $gradient_color_1 . '"';
	$attributes[] = 'data-vc-gradient-2="' . $gradient_color_2 . '"';
} elseif ('theme-style-default-btn' == $style) {
	$button_classes[] = 'theme-style-default-btn theme-btn-animation';
} elseif ('theme-style-custom' == $style) {
	if(!empty($text_color)):
		/*ide*/
		$ide = array();
		$ide[] = 'color: ' . $text_color;

		if(!empty($wtf)){
			$ide[] = 'background: ' . $wtf;
		}else{
			$ide[] = 'background: transparent';
			$ide[] = 'padding-left: 0';
			$ide[] = 'padding-right: 0';
			$ide[] = 'padding-bottom: 5px';
		}
		/*check line*/
		if($line == 'yes'){
			$ide[] = '-webkit-border-image: -webkit-gradient(linear, 100% 0, 0 0, from(' . $color2 . '), to(' . $color1 . ')) 100 10%';
			$ide[] = '-webkit-border-image: -webkit-linear-gradient(right, ' . $color2 . ', ' . $color1 . ') 100 10%';
			$ide[] = 'border-image: linear-gradient(to right, ' . $color1 . ', ' . $color2 . ') 1 10%';
			$ide[] = 'border-bottom: 3px';
			$ide[] = 'border-style: solid';
		}

		/*hover*/
		$hover = array();
		$hover[] = 'color: ' . $text_color;

		/*check line*/
		if($line == 'yes'){
			$hover[] = '-webkit-border-image: -webkit-gradient(linear, 100% 0, 0 0, from(' . $color2 . '), to(' . $color1 . ')) 100 10%';
			$hover[] = '-webkit-border-image: -webkit-linear-gradient(right, ' . $color2 . ', ' . $color1 . ') 100 10%';
			$hover[] = 'border-image: linear-gradient(to right, ' . $color1 . ', ' . $color2 . ') 1 10%';
			$hover[] = 'border-bottom: 3px';
			$hover[] = 'border-style: solid';
		}

		/*print css*/
		$uid = uniqid();

		if(!empty($wtf)){
			$button_classes[] = 'theme-btn-material theme-btn-id-' . $uid;
		}else{
			$button_classes[] = 'theme-btn-no-material theme-btn-no-bg theme-btn-id-' . $uid;
		}

		echo '<style>';
		echo '.vc_btn3-style-' . $style . '.theme-btn-id-' . $uid . '{' . implode( ';', $ide ) . ';}.vc_btn3-style-' . $style . '.theme-btn-id-' . $uid . ':hover, .vc_btn3-style-' . $style . '.theme-btn-id-' . $uid . ':focus{' . implode( ';', $hover ) . ';}';
		echo '</style>';
		

	endif;

}else {
	$button_classes[] = 'vc_btn3-color-' . $color;
}

if ( $styles ) {
	$attributes[] = 'style="' . implode( ' ', $styles ) . '"';
}

$class_to_filter = implode( ' ', array_filter( $wrapper_classes ) );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

if ( $button_classes ) {
	$button_classes = esc_attr( apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $button_classes ) ), $this->settings['base'], $atts ) );
	$attributes[] = 'class="' . trim( $button_classes ) . '"';
}

if ( $use_link ) {
	$attributes[] = 'href="' . trim( $a_href ) . '"';
	$attributes[] = 'title="' . esc_attr( trim( $a_title ) ) . '"';
	$attributes[] = 'aria-label="' . esc_attr( trim( $a_title ) ) . '"';
	if ( ! empty( $a_target ) ) {
		$attributes[] = 'target="' . esc_attr( trim( $a_target ) ) . '"';
	}
	if ( ! empty( $a_rel ) ) {
		$attributes[] = 'rel="' . esc_attr( trim( $a_rel ) ) . '"';
	}
}

if ( ! empty( $custom_onclick ) && $custom_onclick_code ) {
	$attributes[] = 'onclick="' . esc_attr( $custom_onclick_code ) . '"';
}

$attributes = implode( ' ', $attributes );

?>
<div class="<?php echo trim( esc_attr( $css_class ) ) ?>">
	<?php
		if ( $use_link ) {
			echo '<a ' . $attributes . '>' . $button_html . '</a>';
		} else {
			echo '<button ' . $attributes . '>' . $button_html . '</button>';
		}
	?>
</div>