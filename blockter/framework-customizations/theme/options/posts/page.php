<?php if ( ! defined( 'FW' ) ) { die( 'Forbidden' );}

$page_options = array(
    /*header*/
    'page_header' => array(
        'title'   => esc_html__('Header', 'blockter'),
        'type'    => 'tab',
        'options' => array(
            /*header layout*/
            'page_header_layout' => array(
                'label' => false,
                'desc' => false,
                'type' => 'multi-picker',
                'picker' => array(
                    'gadget' => array(
                        'label' => esc_html__('Layout', 'blockter'),
                        'type' => 'short-select',
                        'choices' => array(
                            'default' => esc_html__('Default', 'blockter'),
                            'layout-1' => esc_html__('Layout 1', 'blockter'),
                            'layout-2' => esc_html__('Layout 2', 'blockter'),
                            'layout-3' => esc_html__('Layout 3', 'blockter'),
                        ),
                        'value' => 'default',
                    )
                ),
                'choices' => array(
                    'layout-2' => array(
                        'layout-2-bg-menu' => array(
                            'type' => 'rgba-color-picker',
                            'label' => esc_html__('Menu background', 'blockter'),
                            'desc' => esc_html__('Choose color', 'blockter'),
                            'value' => 'rgba(255,255,255,0)'
                        ),
                        'layout-2-menu-txt-color' => array(
                            'type' => 'color-picker',
                            'label' => esc_html__('Heading menu text color', 'blockter'),
                            'desc' => esc_html__('Choose color', 'blockter'),
                            'value' => ''
                        ),
                        'layout-2-btn-bg' => array(
                            'type' => 'color-picker',
                            'label' => esc_html__('Button background', 'blockter'),
                            'desc' => esc_html__('Choose color', 'blockter'),
                            'value' => ''
                        ),
                        'layout-2-btn-color' => array(
                            'type' => 'color-picker',
                            'label' => esc_html__('Button text color', 'blockter'),
                            'desc' => esc_html__('Choose color', 'blockter'),
                            'value' => ''
                        ),
                    ),
                ),
            ),
            /*logo*/
            'p_lg' => array(
                'label' => false,
                'desc' => false,
                'type' => 'multi-picker',
                'picker' => array(
                    'gadget' => array(
                        'type' => 'short-select',
                        'label' => esc_html__('Logo', 'blockter'),
                        'desc' => false,
                        'choices' => array(
                            'default' => esc_html__('Default', 'blockter'),
                            'custom' => esc_html__('Custom', 'blockter'),
                        ),
                        'value' => 'default'
                    )
                ),
                'choices' => array(
                    'custom' => array(
                        'lg_data' => array(
                            'label' => esc_html__('Choose image', 'blockter'),
                            'type' => 'upload',
                            'images_only' => true
                        )
                    )
                ),
            ),
            /*crumbs header*/
            'p_page_header' => array(
                'label'   => false,
                'desc'   => false,
                'type'    => 'multi-picker',
                'picker' => array(
                    'gadget' => array(
                        'label' => esc_html__('Breadcrumbs', 'blockter'),
                        'type' => 'short-select',
                        'choices' => array(
                            'default' => esc_html__('Default', 'blockter'),
                            '1' => esc_html__('Custom', 'blockter'),
                            'no' => esc_html__('Disable', 'blockter'),
                        ),
                        'value' => 'default',
                    ),
                ),
                'choices' => array(
                    '1' => array(
                        'page_header_text_color' => array(
                            'label' => esc_html__('Text color', 'blockter'),
                            'desc' => esc_html__('Display text color of page breadcumbs', 'blockter'),
                            'type' => 'color-picker',
                            'value' => '#ffffff'
                        ),
                        'page_header_title' => array(
                            'label' => esc_html__('Alternative Title', 'blockter'),
                            'desc' => esc_html__('This will replace heading page title', 'blockter'),
                            'type' => 'text',
                            'value' => 'This is my title!'
                        ),
                        'page_header_text' => array(
                            'label' => esc_html__('Text Header', 'blockter'),
                            'desc' => esc_html__('This will display under breadcrumbs (optional)', 'blockter'),
                            'type' => 'textarea',
                        ),
                        'page_header_bg' => array(
                            'label' => false,
                            'desc' => false,
                            'type' => 'multi-picker',
                            'picker' => array(
                                'gadget' => array(
                                    'label' => esc_html__('Background Style', 'blockter'),
                                    'desc' => esc_html__('If select background image option, the theme recommends a header size of at least 1170 width pixels', 'blockter'),
                                    'type' => 'select',
                                    'choices' => array(
                                        'img_bg' => esc_html__('Use Image', 'blockter'),
                                        'color_bg' => esc_html__('Use Solid Color', 'blockter'),
                                    ),
                                    'value' => 'color_bg'
                                )
                            ),
                            'choices' => array(
                                'img_bg' => array(
                                    'img_bg_data' => array(
                                        'label' => esc_html__('Single Upload (Images Only)', 'blockter'),
                                        'type' => 'upload'
                                    )
                                ),
                                'color_bg' => array(
                                    'color_bg_data' => array(
                                        'label' => esc_html__('Background Color', 'blockter'),
                                        'type' => 'color-picker',
                                        'value' => '#e9eceb'
                                    )
                                )
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    /*footer*/
    'page_footer' => array(
        'title'   => esc_html__('Footer', 'blockter'),
        'type'    => 'tab',
        'options' => array(
            'footer_data' => array(
                'label' => esc_html__('Layout', 'blockter'),
                'type' => 'short-select',
                'choices' => array(
                    'default' => esc_html__('Default', 'blockter'),
                    'enable' => esc_html__('Enable', 'blockter'),
                    'disable' => esc_html__('Disable', 'blockter'),
                ),
                'value' => 'default',
            ),
            'p_copyright' => array(
                'label' => esc_html__('Copyright', 'blockter'),
                'type' => 'short-select',
                'choices' => array(
                    'default' => esc_html__('Default', 'blockter'),
                    'disable' => esc_html__('Disable', 'blockter'),
                ),
                'value' => 'default',
            )
        ),
    ),
);
$options = array(
    'page_layout_box' => array(
        'title'   => esc_html__( 'Page Customizing', 'blockter'),
        'type'    => 'box',
        'options' => $page_options
    ),
);
