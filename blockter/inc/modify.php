<?php
vc_map( array(
			'name'     => esc_html__( 'Blog News', 'blockter' ),
			'description' => esc_html__( 'Add a Blog News', 'blockter' ),
			'base'     => 'news',
			'icon' => get_template_directory_uri() . '/images/vc/news.png',
			'category' => esc_html__( 'Health Guide Theme', 'blockter' ),
			'params'   => array(
				/*blog style*/
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Style', 'blockter' ),
					'param_name' => 'blog_style',
					'value'      => array(
						esc_html__('Style 1', 'blockter') => 'consult-blog-style-1',
						esc_html__('Style 2', 'blockter') => 'consult-blog-style-2',
						esc_html__('Style 3', 'blockter') => 'consult-blog-style-3',
						esc_html__('Style 4', 'blockter') => 'consult-blog-style-4',
						esc_html__('Style 5', 'blockter') => 'consult-blog-style-5',
					),
					'std'        => 'consult-blog-style-1',
					'admin_label' => true,
				),
				/*count*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Count', 'blockter' ),
					'description' => esc_html__('Enter post count', 'blockter'),
					'param_name' => 'post_count',
					'admin_label' => true,
					'value' => 3
				),
				array(
					'type'       => 'checkbox',
					'heading'    => esc_html__( 'Ignore sticky posts?', 'blockter' ),
					'param_name' => 'ignore_sticky_posts',
					'value'      => array(esc_html__('Yes', 'blockter') => 'yes'),
					'std' => 'yes',
				),
				/*thumbnail*/
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Display Thumbnail Image?', 'blockter' ),
					'param_name' => 'thumb',
					'value' => array(
						esc_html__('Yes','blockter') => 'yes'
					),
					'std' => 'yes'
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Order by', 'blockter' ),
					'param_name' => 'order_by',
					'value'      => array(
						esc_html__('Default', 'blockter') => 'none',
						esc_html__('Date', 'blockter') => 'date',
						esc_html__('ID', 'blockter') => 'ID',
						esc_html__('Name', 'blockter') => 'name',
						esc_html__('Author', 'blockter') => 'author',
						esc_attr__('Title', 'blockter') => 'title',
						esc_html__('Modified', 'blockter') => 'modified',
						esc_html__('Random', 'blockter') => 'rand',
						esc_html__('Comment count', 'blockter') => 'comment_count',
						esc_html__('Menu order', 'blockter') => 'menu_order',
					),
					'std'        => 'none',
					'admin_label' => true,
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Sort order', 'blockter' ),
					'param_name' => 'sort_order',
					'value'      => array(
						esc_html__('Default', 'blockter') => '',
						esc_html__('Ascending', 'blockter') => 'ASC',
						esc_html__('Descending', 'blockter') => 'DESC',
					),
					'std'        => '',
					'admin_label' => true,
				),
				/*columns*/
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Posts Per Row', 'blockter' ),
					'param_name' => 'blog_ppr',
					'value'      => array(1, 2, 3, 4),
					'std'        => 3,
					'admin_label' => true,
				),
				array(
			        'type'        => 'textfield',
			        'heading'     => esc_html__('Class', 'blockter' ),
			        'description' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'blockter'),
			        'admin_label' => true,
			        'param_name'  => 'class',
		        ),
		        array(
			        'type'       => 'css_editor',
			        'heading'    => esc_html__( 'CSS', 'blockter' ),
			        'param_name' => 'css',
			        'group'      => esc_html__( 'Design Options', 'blockter' ),
		        ),
			)
		) );
 ?>