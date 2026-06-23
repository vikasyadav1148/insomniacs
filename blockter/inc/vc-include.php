<?php
/*parent shortcode*/
if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    /*iconbox carousel*/
    class WPBakeryShortCode_twitter_carousel extends WPBakeryShortCodesContainer {
    }
}

/*child shortcode*/
if ( class_exists( 'WPBakeryShortCode' ) ) {
    /*video*/
    class WPBakeryShortCode_ht_video extends WPBakeryShortCode {
	}
    /*case studies*/
    class WPBakeryShortCode_case extends WPBakeryShortCode {
	}
    /*blog news*/
    class WPBakeryShortCode_news extends WPBakeryShortCode {
	}
    /*brand logo*/
    class WPBakeryShortCode_brand extends WPBakeryShortCode {
	}
    /*tabs*/
    class WPBakeryShortCode_tabs extends WPBakeryShortCode {
    }
    /*landing*/
    class WPBakeryShortCode_landing extends WPBakeryShortCode {
    }
    /*facebook*/
    class WPBakeryShortCode_facebook extends WPBakeryShortCode {
    }
    /*twitter*/
    class WPBakeryShortCode_twitter extends WPBakeryShortCode {
    }
    /*movie tab with filter*/
    class WPBakeryShortCode_movie_tab extends WPBakeryShortCode {
    }
    /*movie tab with filter selected by movie*/
    class WPBakeryShortCode_movie_tab_bymovie extends WPBakeryShortCode {
    }
    /*Show tab with filter selected by movie*/
    class WPBakeryShortCode_tv_show_tab_by_tv_show extends WPBakeryShortCode {
    }
    /*show tab with filter*/
    class WPBakeryShortCode_show_tab extends WPBakeryShortCode {
    }
    /*movie tab full width with filter*/
     class WPBakeryShortCode_movie_tab_fw extends WPBakeryShortCode {
    }
    /*movie tab full width with filter*/
     class WPBakeryShortCode_show_tab_fw extends WPBakeryShortCode {
    }

    class WPBakeryShortCode_show_tab_col extends WPBakeryShortCode {
    }
    /*movie trailer vertical*/
    class WPBakeryShortCode_movie_trailer_vertical extends WPBakeryShortCode {
    }
    /*vertical show trailer*/
    class WPBakeryShortCode_show_trailer_vertical extends WPBakeryShortCode {
    }
    /*movie trailer horizontal*/
     class WPBakeryShortCode_movie_trailer_horizontal extends WPBakeryShortCode {
    }
    /*horizontal show trailer*/
     class WPBakeryShortCode_show_trailer_horizontal extends WPBakeryShortCode {
    }
    /*movie main slider*/
    class WPBakeryShortCode_movie_slider extends WPBakeryShortCode {
    }
    /* show main slider*/
    class WPBakeryShortCode_show_slider extends WPBakeryShortCode {
    }
    /*casts*/
    class WPBakeryShortCode_casts extends WPBakeryShortCode {
    }
    /*casts grid*/
    class WPBakeryShortCode_casts_grid extends WPBakeryShortCode {
    }
    /*countdown*/
    class WPBakeryShortCode_countdown extends WPBakeryShortCode {
        public function __construct( $settings ) {
            parent::__construct( $settings );
            $this->countdown_js();
        }
        public function countdown_js() {
            wp_enqueue_script( 'countdown-js', get_template_directory_uri() . '/js/countdown.min.js' );
        }
    }
    /*lightbox video*/
    class WPBakeryShortCode_lightbox_video extends WPBakeryShortCode {
    }
}

/*incluce vc-maps*/
require_once get_template_directory() . '/inc/vc-maps.php';

/*disable VC auto update*/
function blockter_vc_disable_updater() {
	vc_manager()->disableUpdater();
}
add_action( 'vc_before_init', 'blockter_vc_disable_updater' );

/*add new option on default shortcode Visual Composer*/
if ( class_exists( 'WPBakeryVisualComposerAbstract' ) ) {

    /*vc_btn*/
	$theme_button = array(
        /*style*/
		array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Style', 'blockter' ),
            'description' => esc_html__( 'Select button display style.', 'blockter' ),
            'param_name' => 'style',
            'weight' => 20,
            'value' => array(
                esc_html__( 'Modern', 'blockter' ) => 'modern',
                esc_html__( 'Classic', 'blockter' ) => 'classic',
                esc_html__( 'Flat', 'blockter' ) => 'flat',
                esc_html__( 'Outline', 'blockter' ) => 'outline',
                esc_html__( '3d', 'blockter' ) => '3d',
                esc_html__( 'Custom', 'blockter' ) => 'custom',
                esc_html__( 'Outline custom', 'blockter' ) => 'outline-custom',
                esc_html__( 'Gradient', 'blockter' ) => 'gradient',
                esc_html__( 'Gradient Custom', 'blockter' ) => 'gradient-custom',
                esc_html__( 'Theme Style', 'blockter' ) => 'theme-style-default-btn',
                esc_html__( 'Theme Style Custom', 'blockter' ) => 'theme-style-custom',
            ),
            'std' => 'theme-style-default-btn'
        ),
        /*text color*/
        array(
            'type' => 'colorpicker',
            'std' => '#ffffff',
            'heading' => esc_html__( 'Text Color', 'blockter' ),
            'param_name' => 'text_color',
            'edit_field_class' => 'vc_col-sm-6',
            'dependency' => array(
                'element' => 'style',
                'value' => 'theme-style-custom'
            ),
            'weight' => 19,
        ),
        /*background color*/
        array(
            'type' => 'colorpicker',
            'std' => '#3b8cff',
            'heading' => esc_html__( 'Background Color', 'blockter' ),
            'param_name' => 'wtf',
            'edit_field_class' => 'vc_col-sm-6',
            'dependency' => array(
                'element' => 'style',
                'value' => 'theme-style-custom'
            ),
            'weight' => 18,
        ),
        /*line*/
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Line Color?', 'blockter' ),
            'param_name' => 'line',
            'dependency' => array(
                'element' => 'style',
                'value' => 'theme-style-custom'
            ),
            'value' => array(
                esc_html__( 'Yes', 'blockter' ) => 'yes',
            ),
            'weight' => 16,
            'std' => 'no'
        ),
        array(
            'type' => 'colorpicker',
            'std' => '#1d7bff',
            'heading' => esc_html__( 'Start Color', 'blockter' ),
            'param_name' => 'color1',
            'edit_field_class' => 'vc_col-sm-6',
            'dependency' => array(
                'element' => 'line',
                'value' => 'yes'
            ),
            'weight' => 15,
        ),
        array(
            'type' => 'colorpicker',
            'std' => '#59f1ff',
            'heading' => esc_html__( 'End Color', 'blockter' ),
            'param_name' => 'color2',
            'edit_field_class' => 'vc_col-sm-6',
            'dependency' => array(
                'element' => 'line',
                'value' => 'yes'
            ),
            'weight' => 14,
        ),
        /*shape*/
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Shape', 'blockter' ),
            'description' => esc_html__( 'Select button shape.', 'blockter' ),
            'param_name' => 'shape',
            'value' => array(
                esc_html__( 'Rounded', 'blockter' ) => 'rounded',
                esc_html__( 'Square', 'blockter' ) => 'square',
                esc_html__( 'Round', 'blockter' ) => 'round',
            ),
            'std' => 'square'
        ),
        /*disable color option on Theme Style Custom*/
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Color', 'blockter' ),
            'param_name' => 'color',
            'description' => esc_html__( 'Select button color.', 'blockter' ),
            'param_holder_class' => 'vc_colored-dropdown vc_btn3-colored-dropdown',
            'weight' => 2,
            'value' => array(
                    esc_html__( 'Classic Grey', 'blockter' ) => 'default',
                    esc_html__( 'Classic Blue', 'blockter' ) => 'primary',
                    esc_html__( 'Classic Turquoise', 'blockter' ) => 'info',
                    esc_html__( 'Classic Green', 'blockter' ) => 'success',
                    esc_html__( 'Classic Orange', 'blockter' ) => 'warning',
                    esc_html__( 'Classic Red', 'blockter' ) => 'danger',
                    esc_html__( 'Classic Black', 'blockter' ) => 'inverse',
                ) + getVcShared( 'colors-dashed' ),
            'std' => 'grey',
            'dependency' => array(
                'element' => 'style',
                'value_not_equal_to' => array(
                    'custom',
                    'outline-custom',
                    'gradient',
                    'gradient-custom',
                    'theme-style-custom',
                    'theme-style-default-btn'
                ),
            ),
        ),
	);
	vc_add_params( 'vc_btn', $theme_button );

    /*vc_tta_accordion*/
    $theme_accordion = array(
        /*style*/
        array(
			'type' => 'dropdown',
			'param_name' => 'style',
			'value' => array(
				esc_html__( 'Classic', 'blockter' ) => 'classic',
				esc_html__( 'Modern', 'blockter' ) => 'modern',
				esc_html__( 'Flat', 'blockter' ) => 'flat',
				esc_html__( 'Outline', 'blockter' ) => 'outline',
                esc_html__( 'Theme Style 1', 'blockter' ) => 'theme-style-1',
                esc_html__( 'Theme Style 2', 'blockter' ) => 'theme-style-2',
                esc_html__( 'Theme Style 3', 'blockter' ) => 'theme-style-3',
			),
            'std' => 'theme-style-1',
			'heading' => esc_html__( 'Style', 'blockter' ),
			'description' => esc_html__( 'Select accordion display style.', 'blockter' ),
		),
        /*icon*/
        array(
			'type' => 'dropdown',
			'param_name' => 'c_icon',
			'value' => array(
				esc_html__( 'None', 'blockter' ) => '',
				esc_html__( 'Chevron', 'blockter' ) => 'chevron',
				esc_html__( 'Plus', 'blockter' ) => 'plus',
				esc_html__( 'Triangle', 'blockter' ) => 'triangle',
                esc_html__( 'Theme Style', 'blockter' ) => 'theme-style',
			),
			'std' => 'theme-style',
			'heading' => esc_html__( 'Icon', 'blockter' ),
			'description' => esc_html__( 'Select accordion navigation icon.', 'blockter' ),
		),
        /*disable color for theme-style*/
        array(
			'type' => 'dropdown',
			'param_name' => 'color',
			'value' => getVcShared( 'colors-dashed' ),
			'std' => 'grey',
			'heading' => esc_html__( 'Color', 'blockter' ),
			'description' => esc_html__( 'Select accordion color.', 'blockter' ),
			'param_holder_class' => 'vc_colored-dropdown',
            'dependency' => array(
                'element' => 'style',
                'value_not_equal_to' => array('theme-style-1','theme-style-2','theme-style-3')
            ),
		),
    );
    vc_add_params('vc_tta_accordion', $theme_accordion);

    /*vc_pie*/
    $theme_pie = array(
        array(
			'type' => 'colorpicker',
			'heading' => esc_html__( 'Text color', 'blockter' ),
            'description' => esc_html__( 'Select text color.', 'blockter' ),
			'param_name' => 'text_color',
            'weight' => 1
		),
    );
    vc_add_params('vc_pie', $theme_pie);

    /*vc_tta_accordion*/
    $theme_tour = array(
        /*style*/
        array(
			'type' => 'dropdown',
			'param_name' => 'style',
			'value' => array(
				esc_html__( 'Classic', 'blockter' ) => 'classic',
				esc_html__( 'Modern', 'blockter' ) => 'modern',
				esc_html__( 'Flat', 'blockter' ) => 'flat',
				esc_html__( 'Outline', 'blockter' ) => 'outline',
                esc_html__( 'Theme Style', 'blockter' ) => 'theme-tour-style',
			),
			'heading' => esc_html__( 'Style', 'blockter' ),
			'description' => esc_html__( 'Select tour display style.', 'blockter' ),
		),
        array(
			'type' => 'dropdown',
			'param_name' => 'color',
			'heading' => esc_html__( 'Color', 'blockter' ),
			'description' => esc_html__( 'Select tour color.', 'blockter' ),
			'value' => getVcShared( 'colors-dashed' ),
			'std' => 'grey',
			'param_holder_class' => 'vc_colored-dropdown',
            'dependency' => array(
                'element' => 'style',
                'value_not_equal_to' => array(
                    'theme-tour-style',
                ),
            ),
		),
    );
    vc_add_params('vc_tta_tour', $theme_tour);

}
