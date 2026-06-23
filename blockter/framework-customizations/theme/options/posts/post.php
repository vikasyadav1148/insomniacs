<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );}

$post_options = array(
	// custom post title
	'p_custom_title' => array(
		'type' => 'box',
		'title' => false,
		'options' => array(
			'spc_opt' => array(
				'label' => false,
				'desc'  => false,
				'type'  => 'multi-picker',
				'picker' => array(
					'gadget' => array(
						'label'   => esc_html__( 'Custom Page Title', 'blockter' ),
						'type'    => 'switch',
						'left-choice' => array(
							'label' => esc_html__( 'No', 'blockter' ),
							'value' => 'no',
						),
						'right-choice' => array(
							'label' => esc_html__( 'Yes', 'blockter' ),
							'value' => 'yes',
						),
						'value' => 'no',
					),
				),
				'choices' => array(
					'yes' => array(
						'spc_title' => array(
							'label' => esc_html__( 'Alternative Title', 'blockter' ),
							'desc'  => esc_html__( 'This will replace heading post title', 'blockter' ),
							'type'  => 'text',
						),
						'textarea_header' => array(
							'label' => esc_html__( 'Custom Text Header', 'blockter' ),
							'desc'  => esc_html__( 'White some text (optional)', 'blockter' ),
							'type'  => 'textarea',
							'value' => '',
						),
					),
				),
			),
			'custom_bg' => array(
				'label'        => esc_html__( 'Set Featured Image as Post Background Image', 'blockter' ),
				'type'         => 'switch',
				'left-choice'  => array(
					'label' => esc_html__( 'No', 'blockter' ),
					'value' => 'no',
				),
				'right-choice' => array(
					'label' => esc_html__( 'Yes', 'blockter' ),
					'value' => 'yes',
				),
				'value'        => 'no',
			),
		),
	),

	'p_image' => array(
		'title'   => esc_html__( 'Image', 'blockter' ),
		'type'    => 'tab',
		'options' => array(
			'data_image' => array(
				'label'   => esc_html__( 'Image upload', 'blockter' ),
				'desc'   => esc_html__( 'Choose image', 'blockter' ),
				'type'    => 'upload',
			),
		),
	),
	'p_galley' => array(
		'title'   => esc_html__( 'Gallery', 'blockter' ),
		'type'    => 'tab',
		'options' => array(
			'data_gallery' => array(
				'label'   => esc_html__( 'Image upload', 'blockter' ),
				'desc'   => esc_html__( 'Choose image(s)', 'blockter' ),
				'type'    => 'multi-upload',
			),
		),
	),
	'p_video' => array(
		'title'   => esc_html__( 'Video', 'blockter' ),
		'type'    => 'tab',
		'options' => array(
			'data_video_type' => array(
				'label'   => esc_html__( 'Video type', 'blockter' ),
				'desc'   => esc_html__( 'Support Youtube and Vimeo', 'blockter' ),
				'type'    => 'short-select',
				'choices' => array(
					'youtube' => esc_html__( 'Youtube', 'blockter' ),
					'vimeo' => esc_html__( 'Vimeo', 'blockter' ),
				),
				'value' => 'vimeo',
			),
			'data_video' => array(
				'label'   => esc_html__( 'Video url', 'blockter' ),
				'desc'   => wp_kses_post( 'Enter url of video.<br>Ex: https://vimeo.com/<b>139450138</b><br>https://www.youtube.com/watch?v=<b>f0halO_QpGQ</b>', 'blockter' ),
				'type'    => 'text',
				'value' => '',
			),
		),
	),
	'p_link' => array(
		'title'   => esc_html__( 'Link', 'blockter' ),
		'type'    => 'tab',
		'options' => array(
			'data_title' => array(
				'label'   => esc_html__( 'Title', 'blockter' ),
				'desc'   => esc_html__( 'Enter your title', 'blockter' ),
				'type'    => 'text',
			),
			'data_icon' => array(
				'label'   => esc_html__( 'Icon', 'blockter' ),
				'desc'   => esc_html__( 'Choose icon', 'blockter' ),
				'type'    => 'icon-v2',
			),
			'data_link' => array(
				'label'   => esc_html__( 'Link', 'blockter' ),
				'desc'   => esc_html__( 'Enter your url here', 'blockter' ),
				'type'    => 'text',
			),
			'data_background' => array(
				'label'   => esc_html__( 'Background image', 'blockter' ),
				'desc'   => esc_html__( 'Choose image', 'blockter' ),
				'type'    => 'upload',
			),
		),
	),
	'p_audio' => array(
		'title'   => esc_html__( 'Audio', 'blockter' ),
		'type'    => 'tab',
		'options' => array(
			'data_audio' => array(
				'label'   => esc_html__( 'Audio', 'blockter' ),
				'desc'   => esc_html__( 'Enter audio url', 'blockter' ),
				'type'    => 'text',
			),
		),
	),
	'p_quote' => array(
		'title'   => esc_html__( 'Quote', 'blockter' ),
		'type'    => 'tab',
		'options' => array(
			'data_quote_bg' => array(
				'label'   => esc_html__( 'Quote background image', 'blockter' ),
				'desc'   => esc_html__( 'Choose image', 'blockter' ),
				'type'    => 'upload',
			),
			'subtitle_quote' => array(
				'label'   => esc_html__( 'Sub title', 'blockter' ),
				'desc'   => esc_html__( 'Enter your subtitle here', 'blockter' ),
				'type'    => 'text',
			),
			'data_quote' => array(
				'label'   => esc_html__( 'Quote', 'blockter' ),
				'desc'   => esc_html__( 'Enter your quote here', 'blockter' ),
				'type'    => 'text',
			),
			'author_quote' => array(
				'label'   => esc_html__( 'Author', 'blockter' ),
				'desc'   => esc_html__( 'Enter your author here', 'blockter' ),
				'type'    => 'text',
			),
		),
	),
	'stories_id' => array(
		'type' => 'box',
		'options' => array(
			'option_id'  => array( 'type' => 'text' ),
		),
		'title' => esc_html__( 'Sub Title', 'blockter' ),
		'attr' => array(
			'class' => 'custom-class',
			'data-foo' => 'bar',
		),

		//'context' => 'normal|advanced|side',
		//'priority' => 'default|high|core|low',
	),
);

$options = array(
	'post_layout_box' => array(
		'title'   => esc_html__( 'Post Customizing', 'blockter' ),
		'type'    => 'box',
		'options' => $post_options,
	),
);
