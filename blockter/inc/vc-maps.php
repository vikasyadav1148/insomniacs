<?php

if(function_exists('vc_map')){
	add_action( 'init', 'blockter_vc_elements' );
}

/*theme shortcode*/
if(!function_exists('blockter_vc_elements')){
    function blockter_vc_elements(){
		/*video control*/
		vc_map(array(
			'name' => esc_html__( 'Video', 'blockter' ),
			'icon' => get_template_directory_uri() . '/images/vc/video.png',
			'base' => 'ht_video',
			'category' => esc_html__('Blockter Theme', 'blockter'),
			'params' => array(
				/*source*/
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Source', 'blockter' ),
					'admin_label' => true,
					'param_name' => 'source',
					'value' => array(
						esc_html__( 'Youtube', 'blockter' ) => 'youtube',
						esc_html__( 'Vimeo', 'blockter' ) => 'vimeo',
					),
					'std' => 'youtube'
				),
				/*video id*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Video ID', 'blockter' ),
					'description' => esc_html__( 'Enter video ID, Ex: https://www.youtube.com/watch?v=22iu8byk5C8, ID = 22iu8byk5C8; https://vimeo.com/156602623, ID = 156602623', 'blockter' ),
					'admin_label' => true,
					'param_name' => 'vid',
					'value' => '22iu8byk5C8'
				),
				/*INCLIDE BY DEFAULT*/
				vc_map_add_css_animation(),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Class', 'blockter' ),
					'description' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'blockter'),
					'admin_label' => true,
					'param_name' => 'class',
				),
				array(
					'type' => 'css_editor',
					'heading'=> esc_html__( 'CSS', 'blockter' ),
					'param_name' => 'inline_css',
					'group' => esc_html__( 'Design Options', 'blockter' ),
				),
			)
		));
		/*get case categories*/
		$categories_arr = array();
		$categories = get_terms( 'fw-portfolio-category' );
		if( !empty( $categories ) && ! is_wp_error( $categories ) ) {
			foreach( $categories as $key ) {
				$categories_arr[] = array( 'label' => $key->name, 'value' => $key->slug );
			}
		}
		/*get case movies*/
		$movies_arr = array();
		$args_mv = new WP_Query( array(
			'post_type'      => 'ht_movie',
			'posts_per_page' => -1
		));
		$data_mv = $args_mv->posts;
		if( !empty( $args_mv ) && ! is_wp_error( $args_mv ) ) {
			foreach( $data_mv as $key_mv ) {
				$movies_arr[] = array( 'label' => $key_mv->post_title, 'value' => $key_mv->ID );
			}
		}

		/*get case posts*/
		$posts_arr = array();
		$args = new WP_Query( array(
			'post_type'      => 'fw-portfolio',
			'posts_per_page' => -1
		));
		$data = $args->posts;
		if( !empty( $args ) && ! is_wp_error( $args ) ) {
			foreach( $data as $key ) {
				$posts_arr[] = array( 'label' => $key->post_title, 'value' => $key->ID );
			}
		}
		vc_map(array(
				'name' => esc_html__( 'Case Studies', 'blockter' ),
				'base' => 'case',
				'category' => esc_html__('Blockter Theme', 'blockter'),
				'params' => array(
					/*column*/
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Columns', 'blockter' ),
						'description' => esc_html__( 'Select post per row', 'blockter' ),
						'param_name' => 'column',
						'admin_label' => true,
						'value' => array(2, 3, 4),
						'std' => '3'
					),
					/*filter*/
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Enable filter', 'blockter' ),
						'param_name' => 'filter',
						'admin_label' => true,
						'value' => array(
							esc_html__('Yes', 'blockter') => 'yes'
						),
						'std' => 'yes'
					),
					/*source data*/
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Pick post by', 'blockter' ),
						'param_name' => 'source',
						'admin_label' => true,
						'value' => array(
							esc_html__('Single post', 'blockter') => 'posts',
							esc_html__('Categories', 'blockter') => 'cats'
						),
						'std' => 'posts'
					),

					/*categories data*/
					array(
						'type' => 'autocomplete',
						'heading' => esc_html__( 'Select category(s)', 'blockter' ),
						'param_name' => 'cats_data',
						'dependency' => array(
							'element' => 'source',
							'value' => 'cats'
						),
						'settings' => array(
							'multiple' => true,
							'min_length' => 1,
							'groups' => true,
							// In UI show results grouped by groups, default false
							'unique_values' => true,
							// In UI show results except selected. NB! You should manually check values in backend, default false
							'display_inline' => true,
							// In UI show results inline view, default false (each value in own line)
							'delay' => 500,
							// delay for search. default 500
							'auto_focus' => true,
							// auto focus input, default true
							'no_hide' => true,
							'sortable' => true,
							'values' => $categories_arr
						),
						'description' => esc_html__( 'Enter categories', 'blockter' ),
					),
					/*posts data*/
					array(
						'type' => 'autocomplete',
						'heading' => esc_html__( 'Select post(s)', 'blockter' ),
						'param_name' => 'posts_data',
						'dependency' => array(
							'element' => 'source',
							'value' => 'posts'
						),
						'settings' => array(
							'multiple' => true,
							'min_length' => 1,
							'groups' => true,
							// In UI show results grouped by groups, default false
							'unique_values' => true,
							// In UI show results except selected. NB! You should manually check values in backend, default false
							'display_inline' => true,
							// In UI show results inline view, default false (each value in own line)
							'delay' => 500,
							// delay for search. default 500
							'auto_focus' => true,
							// auto focus input, default true
							'no_hide' => true,
							'sortable' => true,
							'values' => $posts_arr
						),
						'description' => esc_html__( 'Enter posts', 'blockter' ),
					),
					/*hover background color*/
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Custom hover background?', 'blockter' ),
						'param_name' => 'effect',
						'admin_label' => true,
						'value' => array(
							esc_html__('Yes', 'blockter') => 'yes'
						),
						'std' => 'no'
					),
					array(
						'type' => 'colorpicker',
						'heading' => esc_html__( 'Color 1', 'blockter' ),
						'param_name' => 'color1',
						'dependency' => array(
							'element' => 'effect',
							'value' => 'yes'
						),
						'edit_field_class' => 'vc_col-sm-6',
						'value' => ''
					),
					array(
						'type' => 'colorpicker',
						'heading' => esc_html__( 'Color 2', 'blockter' ),
						'param_name' => 'color2',
						'dependency' => array(
							'element' => 'effect',
							'value' => 'yes'
						),
						'edit_field_class' => 'vc_col-sm-6',
						'value' => ''
					),
					/*INCLIDE BY DEFAULT*/
					vc_map_add_css_animation(),
					array(
						'type' => 'textfield',
						'heading' => esc_html__('Class', 'blockter' ),
						'description' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'blockter'),
						'admin_label' => true,
						'param_name' => 'class',
					),
					array(
						'type' => 'css_editor',
						'heading'=> esc_html__( 'CSS', 'blockter' ),
						'param_name' => 'inline_css',
						'group' => esc_html__( 'Design Options', 'blockter' ),
					),
				)
				));
		/*blog news*/
		vc_map( array(
			'name' => esc_html__( 'Blog News', 'blockter' ),
			'description' => esc_html__( 'Add a Blog News', 'blockter' ),
			'base' => 'news',
			'category' => esc_html__('Blockter Theme', 'blockter'),
			'params' => array(
				/*blog style*/
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Style', 'blockter' ),
					'param_name' => 'blog_style',
					'value'      => array(
						esc_html__('Style 1', 'blockter') => 'consult-blog-style-1',
						esc_html__('Style 2  Without Thubnail', 'blockter') => 'consult-blog-style-2',
						esc_html__('Style 3', 'blockter') => 'consult-blog-style-3',
					),
					'std'        => 'consult-blog-style-1',
					'admin_label' => true,
				),
				/*count*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Count', 'blockter' ),
					'description' => esc_html__('Enter post count', 'blockter'),
					'param_name' => 'count',
					'admin_label' => true,
					'value' => 4
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
					'type' => 'dropdown',
					'heading' => esc_html__( 'Columns', 'blockter' ),
					'description' => esc_html__('Select post per row', 'blockter'),
					'param_name' => 'blog_ppr',
					'admin_label' => true,
					'value' => array(1, 2,3,4),
					'std' => 4
				),
				/*INCLIDE BY DEFAULT*/
				vc_map_add_css_animation(),
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
					'param_name' => 'inline_css',
					'group'      => esc_html__( 'Design Options', 'blockter' ),
				),
			),
		));

	    /*for movie filter*/
	    $genres = get_terms( 'mv_genre' );
	    $collections = get_terms( 'mv_collection' );
	    $casts = get_terms( 'mv_actor' );
		//$trending = get_terms( 'mv_trending' );
		$taxonomy = 'mv_trending'; // Replace with your taxonomy, e.g., 'product_cat'
		$parent_slug = 'movie';
		$parent_term = get_term_by('slug', $parent_slug, $taxonomy);
		$trending = array();
		if ($parent_term) {
   
				$child_terms = get_terms(array(
					'taxonomy'   => $taxonomy,
					'parent'     => $parent_term->term_id, // Use the ID here
					'hide_empty' => false,                // Show empty terms
				));

    // Output or use the child terms
			if (!empty($child_terms) && !is_wp_error($child_terms)) {
				foreach ($child_terms as $term) {
					$trending[] = $term;
				}
			}
		}
		$parent_slug = 'tv';
		$parent_term = get_term_by('slug', $parent_slug, $taxonomy);
		$trending_tv = array();
		if ($parent_term) {
   
				$child_terms = get_terms(array(
					'taxonomy'   => $taxonomy,
					'parent'     => $parent_term->term_id, // Use the ID here
					'hide_empty' => false,                // Show empty terms
				));

    // Output or use the child terms
			if (!empty($child_terms) && !is_wp_error($child_terms)) {
				foreach ($child_terms as $term) {
					$trending_tv[] = $term;
				}
			}
		}


	    $collection_arr = $genres_arr = $posts_arr  = $casts_arr = $trending_arr = $trending_tv_arr = $composite_arr = array();
	    /*get movie genres*/
	    if( !empty( $genres ) && ! is_wp_error( $genres ) ) {
		    foreach( $genres as $key ) {
			    $genres_arr[] = array( 'label' => $key->name, 'value' => $key->slug );
		    }
	    }
	    /*get movie collection*/
	    if( !empty( $collections ) && ! is_wp_error( $collections) ) {
		    foreach( $collections as $key ) {
			    $collection_arr[] = array( 'label' => $key->name, 'value' => $key->slug );
		    }
	    }
	    /*get movie casts*/
	    if( !empty( $casts ) && ! is_wp_error( $casts) ) {
		    foreach( $casts as $key ) {
			    $casts_arr[] = array( 'label' => $key->name, 'value' => $key->slug );
		    }
	    }
		/*get movie trending*/
		if( !empty( $trending ) && ! is_wp_error( $trending) ) {
			foreach( $trending as $key ) {
				$trending_arr[] = array( 'label' => $key->name, 'value' => $key->slug );
			}
		}
		/*get tv trending*/
		if( !empty( $trending_tv ) && ! is_wp_error( $trending_tv) ) {
			foreach( $trending_tv as $key ) {
				$trending_tv_arr[] = array( 'label' => $key->name, 'value' => $key->slug );
			}
		}

		/*get composite trending*/
		$composite_arr =[
			['label' => 'Day', 'value' => 'day'],
			['label' => 'Week', 'value' => 'week'],
		];

	    /*get movie posts*/
	    $args = new WP_Query( array(
		    'post_type'      => 'ht_movie',
		    'posts_per_page' => -1
	    ));

	    $data = $args->posts;
	    if( !empty( $args ) && ! is_wp_error( $args ) ) {
		    foreach( $data as $key ) {
			    $posts_arr[] = array( 'label' => $key->post_title, 'value' => $key->post_name );
		    }
	    }

	    /*get TV Show posts*/
	    $args = new WP_Query( array(
		    'post_type'      => 'ht_show',
		    'posts_per_page' => -1
	    ));

	    $tv_show = $args->posts;
	    if( !empty( $args ) && ! is_wp_error( $args ) ) {
		    foreach( $data as $key ) {
			    $posts_arr[] = array( 'label' => $key->post_title, 'value' => $key->post_name );
		    }
	    }

		$show_arr = array();
		$args_mv = new WP_Query( array(
			'post_type'      => 'ht_show',
			'posts_per_page' => -1
		));
		$data_mv = $args_mv->posts;
		if( !empty( $args_mv ) && ! is_wp_error( $args_mv ) ) {
			foreach( $data_mv as $key_mv ) {
				$show_arr[] = array( 'label' => $key_mv->post_title, 'value' => $key_mv->ID );
			}
		}

		/*casts*/
		vc_map( array(
			'name' => esc_html__( 'Casts', 'blockter' ),
			'description' => esc_html__( 'Add a cast', 'blockter' ),
			'base' => 'casts',
			'category' => esc_html__('Blockter Theme', 'blockter'),
			'params' => array(
				/*title*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'blockter' ),
					'description' => esc_html__( 'Enter your title', 'blockter' ),
					'param_name' => 'cast_title',
					'admin_label' => true,
					'value' => 'Spotlight Celebrities',
				),

				array(
					'type' => 'dropdown',
					'heading' => esc_html__('Narrow data source', 'blockter'),
					'param_name' => 'data',
					'admin_label' => true,
					'value' => array(
						esc_html__('Enter number of celebrities', 'blockter') => 'auto',
						esc_html__('Enter celebrities manually', 'blockter') => 'manual',
					),
					'std' => 'auto',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Number of celebrities', 'blockter' ),
					'param_name' => 'count',
					'admin_label' => true,
					'value' => 4,
					'dependency' => array(
						'element' => 'data',
						'value' => 'auto',
					),
				),

				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter celebrities', 'blockter' ),
					'param_name' => 'data_cast_slugs',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $casts_arr,
					),
					'dependency' => array(
						'element' => 'data',
						'value' => 'manual',
					),
				),

				/*INCLUDE BY DEFAULT*/
				vc_map_add_css_animation(),
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
					'param_name' => 'inline_css',
					'group'      => esc_html__( 'Design Options', 'blockter' ),
				),
			),
		));
		/*casts grid*/
		vc_map( array(
			'name' => esc_html__( 'Casts Grid Items', 'blockter' ),
			'description' => esc_html__( 'Add a cast grid', 'blockter' ),
			'base' => 'casts_grid',
			'category' => esc_html__('Blockter Theme', 'blockter'),
			'params' => array(
				/*cast item style*/
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Style', 'blockter' ),
					'param_name' => 'cast_style',
					'value'      => array(
						esc_html__('Style 1', 'blockter') => 'cast-style-1',
					),
					'std'        => 'cast-style-1',
					'admin_label' => true,
				),
				/*count*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Count', 'blockter' ),
					'description' => esc_html__('Enter post count', 'blockter'),
					'param_name' => 'count',
					'admin_label' => true,
					'value' => 4
				),
				/*INCLIDE BY DEFAULT*/
				vc_map_add_css_animation(),
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
					'param_name' => 'inline_css',
					'group'      => esc_html__( 'Design Options', 'blockter' ),
				),
			),
		));

		/*movie tab*/
		vc_map( array(
			'name' => esc_html__( 'Movie Tab', 'blockter' ),
			'description' => esc_html__( 'Add a Movie', 'blockter' ),
			'base' => 'movie_tab',
			'category' => esc_html__('Blockter Theme', 'blockter'),
			'params' => array(
				/*style*/
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Movie Tab Style', 'blockter' ),
					'param_name' => 'movie_style',
					'value'      => array(
						esc_html__('Style 1', 'blockter') => 'movie-tab-style-1',
						esc_html__('Style FullWidth', 'blockter') => 'movie-tab-style-fw',
					),
					'std'        => 'movie-tab-style-1',
					'admin_label' => true,
				),
				/*data source*/
				array(
					'type' => 'dropdown',
					'heading' => esc_html__('Narrow data source', 'blockter'),
					'param_name' => 'data',
					'admin_label' => true,
					'value' => array(
						esc_html__('Genres', 'blockter') => 'genres',
						esc_html__('Collections', 'blockter') => 'collections',
						esc_html__('Casts', 'blockter') => 'casts',
						esc_html__('Trending', 'blockter') => 'trending',
					),
					'std' => 'genres'
				),
				/*genres*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter genres', 'blockter' ),
					'param_name' => 'data_genres',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $genres_arr
					),
					'dependency' => array(
						'element' => 'data',
						'value' => 'genres'
					)
				),
				/*collection*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter collection', 'blockter' ),
					'param_name' => 'data_collections',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $collection_arr
					),
					'dependency' => array(
						'element' => 'data',
						'value' => 'collections'
					)
				),
				/*cast*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter casts', 'blockter' ),
					'param_name' => 'data_casts',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $casts_arr
					),
					'dependency' => array(
						'element' => 'data',
						'value' => 'casts'
					)
				),
				/*trending*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter trending', 'blockter' ),
					'param_name' => 'data_trending',
					'settings' => array(
						'multiple' => false,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $trending_arr
					),
					'dependency' => array(
						'element' => 'data',
						'value' => 'trending'					
						)
				),

				/*movie per tab*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Movie display per tab', 'blockter' ),
					'description' => esc_html__('Select post per tab', 'blockter'),
					'param_name' => 'movie_per_tab',
					'admin_label' => true,
					'std' => 4
				),

				/*movie per row*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Movie display per row', 'blockter' ),
					'description' => esc_html__('Select post per row', 'blockter'),
					'param_name' => 'movie_per_row',
					'admin_label' => true,
					'std' => 4
				),
				/*tab All*/
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Show #All', 'blockter' ),
					'param_name' => 'display_tab_all',
					'value' => array(
						esc_html__( 'No', 'blockter' ) => 'no'
					),
					'std' => 'yes'
				),
				/*INCLIDE BY DEFAULT*/
				vc_map_add_css_animation(),
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
					'param_name' => 'inline_css',
					'group'      => esc_html__( 'Design Options', 'blockter' ),
				),
			),
		));
		/*movie tab selected by movie*/
		vc_map( array(
			'name' => esc_html__( 'Movie Tab selected by movie', 'blockter' ),
			'description' => esc_html__( 'Add a Movie', 'blockter' ),
			'base' => 'movie_tab_bymovie',
			'category' => esc_html__('Blockter Theme', 'blockter'),
			'params' => array(
				/*style*/
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Movie Tab Style', 'blockter' ),
					'param_name' => 'movie_style',
					'value'      => array(
						esc_html__('Style 1', 'blockter') => 'movie-tab-style-1',
						esc_html__('Style FullWidth', 'blockter') => 'movie-tab-style-fw',
					),
					'std'        => 'movie-tab-style-1',
					'admin_label' => true,
				),
				/*Show Filter*/
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Show Filter', 'blockter' ),
					'param_name' => 'display_filter',
					'value' => array(
						esc_html__( 'Yes', 'blockter' ) => 'yes'
					),
					'std' => 'no'
				),
				/*data source*/
				array(
					'type' => 'dropdown',
					'heading' => esc_html__('Narrow data source', 'blockter'),
					'param_name' => 'data',
					'admin_label' => true,
					'value' => array(
						esc_html__('Genres', 'blockter') => 'generes',
						esc_html__('Collections', 'blockter') => 'collections',
						esc_html__('Casts', 'blockter') => 'casts',
						esc_html__('Trending', 'blockter') => 'trending',
					),
					'std' => 'generes'
				),
				/*movies*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter movies', 'blockter' ),
					'param_name' => 'data_movies',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $movies_arr
					),
				),
				/*movie per tab*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Movie display per tab', 'blockter' ),
					'description' => esc_html__('Select post per row', 'blockter'),
					'param_name' => 'movie_per_tab',
					'admin_label' => true,
					'std' => 4
				),
				/*tab All*/
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Show #All', 'blockter' ),
					'param_name' => 'display_tab_all',
					'value' => array(
						esc_html__( 'No', 'blockter' ) => 'no'
					),
					'std' => 'yes'
				),
				/*INCLIDE BY DEFAULT*/
				vc_map_add_css_animation(),
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
					'param_name' => 'inline_css',
					'group'      => esc_html__( 'Design Options', 'blockter' ),
				),
			),
		));
		/*movie tab full width*/
		vc_map( array(
			'name' => esc_html__( 'Movie Tab Full Width', 'blockter' ),
			'description' => esc_html__( 'Add a Movie', 'blockter' ),
			'base' => 'movie_tab_fw',
			'category' => esc_html__('Blockter Theme', 'blockter'),
			'params' => array(
				/*data source*/
				array(
					'type' => 'dropdown',
					'heading' => esc_html__('Narrow data source', 'blockter'),
					'param_name' => 'data',
					'admin_label' => true,
					'value' => array(
						esc_html__('Genres', 'blockter') => 'generes',
						esc_html__('Collections', 'blockter') => 'collections',
						esc_html__('Casts', 'blockter') => 'casts',
						esc_html__('Trending', 'blockter') => 'trending',
					),
					'std' => 'generes'
				),
				/*genres*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter genres', 'blockter' ),
					'param_name' => 'data_generes',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $genres_arr
					),
					'dependency' => array(
						'element' => 'data',
						'value' => 'generes'
					)
				),
				/*collection*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter collection', 'blockter' ),
					'param_name' => 'data_collections',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $collection_arr
					),
					'dependency' => array(
						'element' => 'data',
						'value' => 'collections'
					)
				),
				/*cast*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter casts', 'blockter' ),
					'param_name' => 'data_casts',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $casts_arr
					),
					'dependency' => array(
						'element' => 'data',
						'value' => 'casts'
					)
				),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter trending', 'blockter' ),
					'param_name' => 'data_trending',
					'settings' => array(
						'multiple' => false,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $trending_arr
					),
					'dependency' => array(
						'element' => 'data',
						'value' => 'trending'
					)
				),
				/*movie per tab*/
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Movie display per tab', 'blockter' ),
					'description' => esc_html__('Select post per row', 'blockter'),
					'param_name' => 'movie_per_tab',
					'admin_label' => true,
					'value' => array(4,6),
					'std' => 6
				),
				/*tab All*/
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Show #All', 'blockter' ),
					'param_name' => 'display_tab_all',
					'value' => array(
						esc_html__( 'No', 'blockter' ) => 'no'
					),
					'std' => 'yes'
				),
				/*INCLIDE BY DEFAULT*/
				vc_map_add_css_animation(),
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
					'param_name' => 'inline_css',
					'group'      => esc_html__( 'Design Options', 'blockter' ),
				),
			),
		));
		/*tv show tab*/
		vc_map( array(
			'name'        => esc_html__( 'TV Show Tab', 'blockter' ),
			'description' => esc_html__( 'Add a tv show', 'blockter' ),
			'base'        => 'show_tab',
			'category'    => esc_html__('Blockter Theme', 'blockter'),
			'params'      => array(
				/*style*/
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Show Tab Style', 'blockter' ),
					'param_name' => 'movie_style',
					'value'      => array(
						esc_html__('Style 1', 'blockter') => 'movie-tab-style-1',
						esc_html__('Style FullWidth', 'blockter') => 'movie-tab-style-fw',
					),
					'std'        => 'movie-tab-style-1',
					'admin_label' => true,
				),
				/*data source*/
				array(
					'type' => 'dropdown',
					'heading' => esc_html__('Narrow data source', 'blockter'),
					'param_name' => 'data',
					'admin_label' => true,
					'value' => array(
						esc_html__('Genres', 'blockter') => 'genres',
						esc_html__('Collections', 'blockter') => 'collections',
						esc_html__('Casts', 'blockter') => 'casts',
						esc_html__('Trending', 'blockter') => 'trending',
					),
					'std' => 'genres'
				),
				/*genres*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter genres', 'blockter' ),
					'param_name' => 'data_genres',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $genres_arr
					),
					'dependency' => array(
						'element' => 'data',
						'value' => 'genres'
					)
				),
				/*collection*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter collection', 'blockter' ),
					'param_name' => 'data_collections',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $collection_arr
					),
					'dependency' => array(
						'element' => 'data',
						'value' => 'collections'
					)
				),
				/*cast*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter casts', 'blockter' ),
					'param_name' => 'data_casts',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $casts_arr
					),
					'dependency' => array(
						'element' => 'data',
						'value' => 'casts'
					)
				),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter trending', 'blockter' ),
					'param_name' => 'data_trending',
					'settings' => array(
						'multiple' => false,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $trending_tv_arr
					),
					'dependency' => array(
						'element' => 'data',
						'value' => 'trending'
					)
				),
				/*show per tab*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Show display per tab', 'blockter' ),
					'description' => esc_html__('Select post per row', 'blockter'),
					'param_name' => 'movie_per_tab',
					'admin_label' => true,
					'std' => 4
				),
				/*movie per row*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Movie display per row', 'blockter' ),
					'description' => esc_html__('Select post per row', 'blockter'),
					'param_name' => 'movie_per_row',
					'admin_label' => true,
					'std' => 4
				),
				/*tab All*/
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Show #All', 'blockter' ),
					'param_name' => 'display_tab_all',
					'value' => array(
						esc_html__( 'No', 'blockter' ) => 'no'
					),
					'std' => 'yes'
				),
				/*INCLIDE BY DEFAULT*/
				vc_map_add_css_animation(),
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
					'param_name' => 'inline_css',
					'group'      => esc_html__( 'Design Options', 'blockter' ),
				),
			),
		) );
		/*tv show tab selected by tv show movie*/
		vc_map( array(
			'name' => esc_html__( 'TV Show Tab selected by TV Show', 'blockter' ),
			'description' => esc_html__( 'Add a TV Show', 'blockter' ),
			'base' => 'tv_show_tab_by_tv_show',
			'category' => esc_html__('Blockter Theme', 'blockter'),
			'params' => array(
				/*style*/
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'TV Show Tab Style', 'blockter' ),
					'param_name' => 'movie_style',
					'value'      => array(
						esc_html__('Style 1', 'blockter') => 'movie-tab-style-1',
						esc_html__('Style FullWidth', 'blockter') => 'movie-tab-style-fw',
					),
					'std'        => 'movie-tab-style-1',
					'admin_label' => true,
				),
				/*Show Filter*/
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Show Filter', 'blockter' ),
					'param_name' => 'display_filter',
					'value' => array(
						esc_html__( 'No', 'blockter' ) => 'yes'
					),
					'std' => 'no'
				),
				/*data source*/
				array(
					'type' => 'dropdown',
					'heading' => esc_html__('Narrow data source', 'blockter'),
					'param_name' => 'data',
					'admin_label' => true,
					'value' => array(
						esc_html__('Genres', 'blockter') => 'generes',
						esc_html__('Collections', 'blockter') => 'collections',
						esc_html__('Casts', 'blockter') => 'casts',
						esc_html__('Trending', 'blockter') => 'trending',
					),
					'std' => 'generes'
				),
				/*movies*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter TV Show', 'blockter' ),
					'param_name' => 'data_movies',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $show_arr
					),
				),
				/*movie per tab*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Movie display per tab', 'blockter' ),
					'description' => esc_html__('Select post per row', 'blockter'),
					'param_name' => 'movie_per_tab',
					'admin_label' => true,
					'std' => 4
				),
				/*tab All*/
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Show #All', 'blockter' ),
					'param_name' => 'display_tab_all',
					'value' => array(
						esc_html__( 'No', 'blockter' ) => 'no'
					),
					'std' => 'yes'
				),
				/*INCLIDE BY DEFAULT*/
				vc_map_add_css_animation(),
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
					'param_name' => 'inline_css',
					'group'      => esc_html__( 'Design Options', 'blockter' ),
				),
			),
		));
		vc_map( array(
			'name'        => esc_html__( 'TV Show Tab by Collection', 'blockter' ),
			'description' => esc_html__( 'Add a tv show by collection', 'blockter' ),
			'base'        => 'show_tab_col',
			'category'    => esc_html__('Blockter Theme', 'blockter'),
			'params'      => array(
				/*style*/
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Show Tab Style', 'blockter' ),
					'param_name' => 'movie_style',
					'value'      => array(
						esc_html__('Style 1', 'blockter') => 'movie-tab-style-1',
						esc_html__('Style FullWidth', 'blockter') => 'movie-tab-style-fw',
					),
					'std'        => 'movie-tab-style-1',
					'admin_label' => true,
				),
				/*genres*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter Collection', 'blockter' ),
					'param_name' => 'data_collection',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $collection_arr
					)
				),
				/*show per tab*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Show display per tab', 'blockter' ),
					'description' => esc_html__('Select post per row', 'blockter'),
					'param_name' => 'movie_per_tab',
					'admin_label' => true,
					'std' => 4
				),
					/*tab All*/
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Show #All', 'blockter' ),
					'param_name' => 'display_tab_all',
					'value' => array(
						esc_html__( 'No', 'blockter' ) => 'no'
					),
					'std' => 'yes'
				),
				/*INCLIDE BY DEFAULT*/
				vc_map_add_css_animation(),
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
					'param_name' => 'inline_css',
					'group'      => esc_html__( 'Design Options', 'blockter' ),
				),
			),
		) );
		/*tv show tab full width*/
		vc_map( array(
			'name'        => esc_html__( 'TV Show Tab FullWidth', 'blockter' ),
			'description' => esc_html__( 'Add a tv show', 'blockter' ),
			'base'        => 'show_tab_fw',
			'category'    => esc_html__('Blockter Theme', 'blockter'),
			'params'      => array(
				/*style*/
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Show Tab Style', 'blockter' ),
					'param_name' => 'movie_style',
					'value'      => array(
						esc_html__('Style 1', 'blockter') => 'movie-tab-style-1',
						esc_html__('Style FullWidth', 'blockter') => 'movie-tab-style-fw',
					),
					'std'        => 'movie-tab-style-1',
					'admin_label' => true,
				),
				/*genres*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter genres', 'blockter' ),
					'param_name' => 'data_generes',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $genres_arr
					)
				),
				/*show per tab*/
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Show display per tab', 'blockter' ),
					'description' => esc_html__('Select post per row', 'blockter'),
					'param_name' => 'movie_per_tab',
					'admin_label' => true,
					'value' => array(4,6),
					'std' => 4,
				),
				/*INCLIDE BY DEFAULT*/
				vc_map_add_css_animation(),
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
					'param_name' => 'inline_css',
					'group'      => esc_html__( 'Design Options', 'blockter' ),
				),
			),
		) );
		/*movie trailer vertical*/
		vc_map( array(
			'name' => esc_html__( 'Movie Trailer Vertical', 'blockter' ),
			'description' => esc_html__( 'Add a Movie Trailer', 'blockter' ),
			'base' => 'movie_trailer_vertical',
			'category' => esc_html__('Blockter Theme', 'blockter'),
			'params' => array(
				/*count*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Count', 'blockter' ),
					'description' => esc_html__('Enter post count', 'blockter'),
					'param_name' => 'count',
					'admin_label' => true,
					'value' => 6
				),
				/*columns*/
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Columns', 'blockter' ),
					'description' => esc_html__('Select post per row', 'blockter'),
					'param_name' => 'movie_ppr',
					'admin_label' => true,
					'value' => array(1),
					'std' => 1
				),
				/*data source*/
				array(
					'type' => 'dropdown',
					'heading' => esc_html__('Narrow data source', 'blockter'),
					'param_name' => 'data',
					'admin_label' => true,
					'value' => array(
						esc_html__('All', 'blockter') => 'all',
						esc_html__('Genres', 'blockter') => 'genres',
						esc_html__('Collections', 'blockter') => 'collections',
					),
					'std' => 'all'
				),
				/*genres*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter genres', 'blockter' ),
					'param_name' => 'data_genres',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $genres_arr
					),
					'dependency' => array(
						'element' => 'data',
						'value' => 'genres'
					)
				),
				/*collection*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter collection', 'blockter' ),
					'param_name' => 'data_collections',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $collection_arr
					),
					'dependency' => array(
						'element' => 'data',
						'value' => 'collections'
					)
				),
				/*order by*/
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
				/*sort by*/
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

				/*INCLIDE BY DEFAULT*/
				vc_map_add_css_animation(),
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
					'param_name' => 'inline_css',
					'group'      => esc_html__( 'Design Options', 'blockter' ),
				),
			),
		));
		/*vertical show trailer*/
		vc_map( array(
			'name' => esc_html__( 'TV Show Trailer Vertical', 'blockter' ),
			'description' => esc_html__( 'Add a Vertical TV Show Trailer', 'blockter' ),
			'base' => 'show_trailer_vertical',
			'category' => esc_html__('Blockter Theme', 'blockter'),
			'params' => array(
				/*count*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Count', 'blockter' ),
					'description' => esc_html__('Enter post count', 'blockter'),
					'param_name' => 'count',
					'admin_label' => true,
					'value' => 6
				),
				/*columns*/
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Columns', 'blockter' ),
					'description' => esc_html__('Select post per row', 'blockter'),
					'param_name' => 'movie_ppr',
					'admin_label' => true,
					'value' => array(1),
					'std' => 1
				),
				/*INCLIDE BY DEFAULT*/
				vc_map_add_css_animation(),
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
					'param_name' => 'inline_css',
					'group'      => esc_html__( 'Design Options', 'blockter' ),
				),
			),
		));
		/*movie trailer horiozontal*/
		vc_map( array(
			'name' => esc_html__( 'Movie Trailer Horizontal', 'blockter' ),
			'description' => esc_html__( 'Add a Movie Trailer', 'blockter' ),
			'base' => 'movie_trailer_horizontal',
			'category' => esc_html__('Blockter Theme', 'blockter'),
			'params' => array(
				/*count*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Count', 'blockter' ),
					'description' => esc_html__('Enter post count', 'blockter'),
					'param_name' => 'count',
					'admin_label' => true,
					'value' => 6
				),
				/*columns*/
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Columns', 'blockter' ),
					'description' => esc_html__('Select post per row', 'blockter'),
					'param_name' => 'movie_ppr',
					'admin_label' => true,
					'value' => array(1),
					'std' => 1
				),
				/*data source*/
				array(
					'type' => 'dropdown',
					'heading' => esc_html__('Narrow data source', 'blockter'),
					'param_name' => 'data',
					'admin_label' => true,
					'value' => array(
						esc_html__('All', 'blockter') => 'all',
						esc_html__('Genres', 'blockter') => 'genres',
						esc_html__('Collections', 'blockter') => 'collections',
					),
					'std' => 'all'
				),
				/*genres*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter genres', 'blockter' ),
					'param_name' => 'data_genres',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $genres_arr
					),
					'dependency' => array(
						'element' => 'data',
						'value' => 'genres'
					)
				),
				/*collection*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter collection', 'blockter' ),
					'param_name' => 'data_collections',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $collection_arr
					),
					'dependency' => array(
						'element' => 'data',
						'value' => 'collections'
					)
				),
				/*order by*/
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
				/*sort by*/
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
				/*INCLIDE BY DEFAULT*/
				vc_map_add_css_animation(),
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
					'param_name' => 'inline_css',
					'group'      => esc_html__( 'Design Options', 'blockter' ),
				),
			),
		));
		/*horiozontal tv show trailer*/
		vc_map( array(
			'name' => esc_html__( 'TV Show Trailer Horizontal', 'blockter' ),
			'description' => esc_html__( 'Add a horiozontal TV Show Trailer', 'blockter' ),
			'base' => 'show_trailer_horizontal',
			'category' => esc_html__('Blockter Theme', 'blockter'),
			'params' => array(
				/*count*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Count', 'blockter' ),
					'description' => esc_html__('Enter post count', 'blockter'),
					'param_name' => 'count',
					'admin_label' => true,
					'value' => 6
				),
				/*columns*/
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Columns', 'blockter' ),
					'description' => esc_html__('Select post per row', 'blockter'),
					'param_name' => 'movie_ppr',
					'admin_label' => true,
					'value' => array(1),
					'std' => 1
				),
				/*INCLIDE BY DEFAULT*/
				vc_map_add_css_animation(),
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
					'param_name' => 'inline_css',
					'group'      => esc_html__( 'Design Options', 'blockter' ),
				),
			),
		));
		/*movie slider*/
		vc_map( array(
			'name' => esc_html__( 'Movie Slider', 'blockter' ),
			'description' => esc_html__( 'Add a Movie Slider', 'blockter' ),
			'base' => 'movie_slider',
			'category' => esc_html__('Blockter Theme', 'blockter'),
			'params' => array(
				/*style*/
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Style', 'blockter' ),
					'param_name' => 'movie_style',
					'value'      => array(
						esc_html__('Layout 01', 'blockter') => 'movie-slider-style-1',
						esc_html__('Layout 02', 'blockter') => 'movie-slider-style-2',
						esc_html__('Layout 03', 'blockter') => 'movie-slider-style-3',
					),
					'std'        => 'movie-slider-style-1',
					'admin_label' => true,
				),
				/*data source*/
				array(
					'type' => 'dropdown',
					'heading' => esc_html__('Narrow data source', 'blockter'),
					'param_name' => 'data',
					'admin_label' => true,
					'value' => array(
						esc_html__('Genres', 'blockter') => 'generes',
						esc_html__('Collections', 'blockter') => 'collections',
						esc_html__('Trending', 'blockter') => 'trending',
						esc_html__('All', 'blockter') => 'post',
					),
					'std' => 'post'
				),
				/*genres*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter genres', 'blockter' ),
					'param_name' => 'data_generes',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $genres_arr
					),
					'dependency' => array(
						'element' => 'data',
						'value' => 'generes'
					)
				),
				/*collection*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter collection', 'blockter' ),
					'param_name' => 'data_collections',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $collection_arr
					),
					'dependency' => array(
						'element' => 'data',
						'value' => 'collections'
					)
				),
				/*trending*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter trending', 'blockter' ),
					'param_name' => 'data_trending',
					'settings' => array(
						'multiple' => false,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $trending_arr
					),
					'dependency' => array(
						'element' => 'data',
						'value' => 'trending'
					)
				),
				/*count*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Count', 'blockter' ),
					'description' => esc_html__('Enter movie count', 'blockter'),
					'param_name' => 'count',
					'admin_label' => true,
					'value' => 6
				),
				/*columns*/
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Columns', 'blockter' ),
					'description' => esc_html__('Select movie per row', 'blockter'),
					'param_name' => 'movie_ppr',
					'admin_label' => true,
					'value' => array(1,4),
					'std' => 4
				),
				/*order by*/
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
				/*sort by*/
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
				/*INCLIDE BY DEFAULT*/
				vc_map_add_css_animation(),
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
					'param_name' => 'inline_css',
					'group'      => esc_html__( 'Design Options', 'blockter' ),
				),
			),
		));
		/*tv show slider*/
		vc_map( array(
			'name' => esc_html__( 'TV Show Slider', 'blockter' ),
			'description' => esc_html__( 'Add a TV Show Slider', 'blockter' ),
			'base' => 'show_slider',
			'category' => esc_html__('Blockter Theme', 'blockter'),
			'params' => array(
				/*style*/
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Style', 'blockter' ),
					'param_name' => 'movie_style',
					'value'      => array(
						esc_html__('Layout 01', 'blockter') => 'movie-slider-style-1',
						esc_html__('Layout 02', 'blockter') => 'movie-slider-style-2',
						esc_html__('Layout 03', 'blockter') => 'movie-slider-style-3',
					),
					'std'        => 'movie-slider-style-1',
					'admin_label' => true,
				),
				/*genres*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter genres', 'blockter' ),
					'param_name' => 'data_generes',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $genres_arr
					),
				),
				/*count*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Count', 'blockter' ),
					'description' => esc_html__('Enter movie count', 'blockter'),
					'param_name' => 'count',
					'admin_label' => true,
					'value' => 6
				),
				/*columns*/
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Columns', 'blockter' ),
					'description' => esc_html__('Select movie per row', 'blockter'),
					'param_name' => 'movie_ppr',
					'admin_label' => true,
					'value' => array(1,4),
					'std' => 4
				),
				/*INCLIDE BY DEFAULT*/
				vc_map_add_css_animation(),
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
					'param_name' => 'inline_css',
					'group'      => esc_html__( 'Design Options', 'blockter' ),
				),
			),
		));
		/*tv show trending slider*/
		vc_map( array(
			'name' => esc_html__( 'TV Show Trending Slider', 'blockter' ),
			'description' => esc_html__( 'Add a TV Show Trending Slider', 'blockter' ),
			'base' => 'show_trending_slider',
			'category' => esc_html__('Blockter Theme', 'blockter'),
			'params' => array(
				/*style*/
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Style', 'blockter' ),
					'param_name' => 'movie_style',
					'value'      => array(
						esc_html__('Layout 01', 'blockter') => 'movie-slider-style-1',
						esc_html__('Layout 02', 'blockter') => 'movie-slider-style-2',
						esc_html__('Layout 03', 'blockter') => 'movie-slider-style-3',
					),
					'std'        => 'movie-slider-style-1',
					'admin_label' => true,
				),
				/*trending*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter trending', 'blockter' ),
					'param_name' => 'data_trending',
					'settings' => array(
						'multiple' => false,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $trending_tv_arr
					),
				),
				/*count*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Count', 'blockter' ),
					'description' => esc_html__('Enter movie count', 'blockter'),
					'param_name' => 'count',
					'admin_label' => true,
					'value' => 6
				),
				/*columns*/
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Columns', 'blockter' ),
					'description' => esc_html__('Select movie per row', 'blockter'),
					'param_name' => 'movie_ppr',
					'admin_label' => true,
					'value' => array(1,4),
					'std' => 4
				),
				/*INCLIDE BY DEFAULT*/
				vc_map_add_css_animation(),
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
					'param_name' => 'inline_css',
					'group'      => esc_html__( 'Design Options', 'blockter' ),
				),
			),
		));
		/*Composite trending slider*/
		vc_map( array(
			'name' => esc_html__( 'Composite Trending Slider', 'blockter' ),
			'description' => esc_html__( 'Trending Slider with Movie and TV Show', 'blockter' ),
			'base' => 'composite_trending_slider',
			'category' => esc_html__('Blockter Theme', 'blockter'),
			'params' => array(
				/*style*/
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Style', 'blockter' ),
					'param_name' => 'movie_style',
					'value'      => array(
						esc_html__('Layout 01', 'blockter') => 'movie-slider-style-1',
						esc_html__('Layout 02', 'blockter') => 'movie-slider-style-2',
						esc_html__('Layout 03', 'blockter') => 'movie-slider-style-3',
					),
					'std'        => 'movie-slider-style-1',
					'admin_label' => true,
				),
				/*trending*/
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Enter trending type', 'blockter' ),
					'param_name' => 'data_trending',
					'settings' => array(
						'multiple' => false,
						'min_length' => 1,
						'unique_values' => true,
						'delay' => 200,
						'values' => $composite_arr
					),
				),
				/*count*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Count', 'blockter' ),
					'description' => esc_html__('Enter trending count per type', 'blockter'),
					'param_name' => 'count',
					'admin_label' => true,
					'value' => 6
				),
				/*columns*/
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Columns', 'blockter' ),
					'description' => esc_html__('Select movie per row', 'blockter'),
					'param_name' => 'movie_ppr',
					'admin_label' => true,
					'value' => array(1,4),
					'std' => 4
				),
				/*INCLIDE BY DEFAULT*/
				vc_map_add_css_animation(),
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
					'param_name' => 'inline_css',
					'group'      => esc_html__( 'Design Options', 'blockter' ),
				),
			),
		));
		/*brand logo*/
		vc_map( array(
			'name'     => esc_html__( 'Brand Logo', 'blockter' ),
			'base'     => 'brand',
			'category' => esc_html__( 'Blockter Theme', 'blockter' ),
			'params'   => array(
				/*style*/
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Style', 'blockter' ),
					'admin_label' => true,
					'param_name' => 'brand_logo_style',
					'value' => array(
						esc_html__('Style 1', 'blockter') => 'brand-logo-1',
						esc_html__('Style 2', 'blockter') => 'brand-logo-2',
					),
					'std' => 'brand-logo-1'
				),
				/*title*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'blockter' ),
					'description' => esc_html__( 'Enter Logo Brand title', 'blockter' ),
					'param_name' => 'brand_logo_title',
					'admin_label' => true,
					'value' => 'This is my title',
				),
				/*image attach*/
				array(
					'type'        => 'attach_images',
					'heading'     => esc_html__('Images', 'blockter' ),
					'description' => esc_html__('Choose your images', 'blockter'),
					'param_name'  => 'imgs',
				),
				/*INCLIDE BY DEFAULT*/
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
					'param_name' => 'inline_css',
					'group'      => esc_html__( 'Design Options', 'blockter' ),
				),
			)
		));
		/*tabs*/
		vc_map( array(
			'name'     => esc_html__( 'Tabs', 'blockter' ),
			'base'     => 'tabs',
			'category' => esc_html__( 'Blockter Theme', 'blockter' ),
			'params'   => array(
				array(
					'type' => 'param_group',
					'heading' => esc_html__( 'Tabs content', 'blockter' ),
					'param_name' => 'list',
					'value' => urlencode( json_encode( array(
						array(
							'nav' => 'JAVASCRIPT',
							'title' => 'Javascript',
							'content' => 'Lorem ipsum dolor sit amet, nec in adipiscing purus luctus, urna pell en tesque fringilla vel, non sed arcu integer, mauris ullamcorper ante ut non torquent. Justo praesent, vivamus eleifend torquent, nec in adipiscing purus luctus, urna pellentesque mauris ullamcorper ante ut non torquent fringilla suspendisse.',
						),
						array(
							'nav' => 'HTML5',
							'title' => 'HTML5',
							'content' => 'Lorem ipsum dolor sit amet, nec in adipiscing purus luctus, urna pell en tesque fringilla vel, non sed arcu integer, mauris ullamcorper ante ut non torquent. Justo praesent, vivamus eleifend torquent, nec in adipiscing purus luctus, urna pellentesque mauris ullamcorper ante ut non torquent fringilla suspendisse.',
						),
						array(
							'nav' => 'CSS3',
							'title' => 'CSS3',
							'content' => 'Lorem ipsum dolor sit amet, nec in adipiscing purus luctus, urna pell en tesque fringilla vel, non sed arcu integer, mauris ullamcorper ante ut non torquent. Justo praesent, vivamus eleifend torquent, nec in adipiscing purus luctus, urna pellentesque mauris ullamcorper ante ut non torquent fringilla suspendisse.',
						),
						array(
							'nav' => 'PHP',
							'title' => 'PHP',
							'content' => 'Lorem ipsum dolor sit amet, nec in adipiscing purus luctus, urna pell en tesque fringilla vel, non sed arcu integer, mauris ullamcorper ante ut non torquent. Justo praesent, vivamus eleifend torquent, nec in adipiscing purus luctus, urna pellentesque mauris ullamcorper ante ut non torquent fringilla suspendisse.',
						),
						array(
							'nav' => 'MYSQL',
							'title' => 'MYSQL',
							'content' => 'Lorem ipsum dolor sit amet, nec in adipiscing purus luctus, urna pell en tesque fringilla vel, non sed arcu integer, mauris ullamcorper ante ut non torquent. Justo praesent, vivamus eleifend torquent, nec in adipiscing purus luctus, urna pellentesque mauris ullamcorper ante ut non torquent fringilla suspendisse.',
						),
					))),
					'params' => array(
						array(
							'type' => 'textfield',
							'heading' => esc_html__( 'Nav', 'blockter' ),
							'param_name' => 'nav',
							'edit_field_class' => 'vc_col-sm-6',
							'admin_label' => true,
						),
						array(
							'type' => 'checkbox',
							'heading' => esc_html__( 'Custom Nav', 'blockter' ),
							'param_name' => 'nav_cus',
							'value' => array(
								esc_html__( 'Yes', 'blockter' ) => 'yes'
							),
							'std' => 'no'
						),
						array(
							'type' => 'colorpicker',
							'heading' => esc_html__( 'Text Color', 'blockter' ),
							'param_name' => 'txt_color',
							'dependency' => array(
								'element' => 'nav_cus',
								'value' => 'yes'
							),
							'value' => '',
							'edit_field_class' => 'vc_col-sm-6',
						),
						array(
							'type' => 'colorpicker',
							'heading' => esc_html__( 'Background Color', 'blockter' ),
							'param_name' => 'bg_color',
							'dependency' => array(
								'element' => 'nav_cus',
								'value' => 'yes'
							),
							'value' => '',
							'edit_field_class' => 'vc_col-sm-6',
						),
						array(
							'type' => 'textfield',
							'heading' => esc_html__( 'Title Content', 'blockter' ),
							'param_name' => 'title',
							'edit_field_class' => 'vc_col-sm-6',
							'admin_label' => true,
						),
						array(
							'type' => 'textarea',
							'heading' => esc_html__( 'Content', 'blockter' ),
							'param_name' => 'content',
						),
					),
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
					'param_name' => 'inline_css',
					'group'      => esc_html__( 'Design Options', 'blockter' ),
				)
			)
		));
		/*lading page*/
		vc_map( array(
			'name'     => esc_html__( 'Landing', 'blockter' ),
			'base'     => 'landing',
			'category' => esc_html__( 'Blockter Theme', 'blockter' ),
			'params'   => array(
				/*image attach*/
				array(
					'type'        => 'attach_image',
					'heading'     => esc_html__('Images', 'blockter' ),
					'description' => esc_html__('Choose your images', 'blockter'),
					'param_name'  => 'img',
				),
				/*link*/
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__('Url', 'blockter' ),
					'description' => esc_html__('Enter your url', 'blockter'),
					'param_name'  => 'url',
					'admin_label' => true,
					'value' => '#'
				),
				/*title*/
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__('Title', 'blockter' ),
					'description' => esc_html__('Enter the title', 'blockter'),
					'param_name'  => 'title',
					'admin_label' => true,
					'value' => 'Layout'
				),
				/*desc*/
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__('Description', 'blockter' ),
					'description' => esc_html__('Write some text', 'blockter'),
					'param_name'  => 'desc',
					'admin_label' => true,
					'value' => 'LAUNCH NOW'
				),
				/*color 1*/
				// array(
				// 	'type'        => 'colorpicker',
				// 	'heading'     => esc_html__('Custom hover color 1', 'blockter' ),
				// 	'param_name'  => 'color1',
				// 	'value' => ''
				// ),
				/*color 2*/
				// array(
				// 	'type'        => 'colorpicker',
				// 	'heading'     => esc_html__('Custom hover color 2', 'blockter' ),
				// 	'param_name'  => 'color2',
				// 	'value' => ''
				// ),
				/*INCLIDE BY DEFAULT*/
				vc_map_add_css_animation(),
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
					'param_name' => 'inline_css',
					'group'      => esc_html__( 'Design Options', 'blockter' ),
				),
			)
		));

		/*iconbox carousel container*/
		vc_map( array(
			'name' => esc_html__( 'Twitter Carousel', 'blockter' ),
			'base' => 'twitter_carousel',
			'category' => esc_html__('Blockter Theme', 'blockter'),
			'description' => esc_html__( 'Add a Carousel of Twiiter', 'blockter' ),
			'show_settings_on_create' => false,
			'content_element' => true,
			'is_container' => true,
			'js_view' => 'VcColumnView',
			'as_parent' => array(
				'only' => 'twitter',
			),
			'params' => array(
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
			),
		));

		/*lightbox-video*/

		vc_map( array(
			'name' => esc_html__( 'Video Lightbox', 'blockter' ),
			'base' => 'lightbox_video',
			'category' => esc_html__('Blockter Theme', 'blockter'),
			'description' => esc_html__( 'Add a Video Lightbox', 'blockter' ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Video Url', 'blockter' ),
					'param_name' => 'light_url',
					'description' => esc_html__( 'Enter video url here. This options support Vimeo and Youtube. Ex: https://vimeo.com/137213101', 'blockter' ),
					'value' => 'https://www.youtube.com/watch?v=RH3OxVFvTeg',
					'admin_label' => true
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Left heading', 'blockter' ),
					'param_name' => 'left_heading',
					'value' => 'Create a',
					'admin_label' => true
				),
				/*heading*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Right heading', 'blockter' ),
					'param_name' => 'right_heading',
					'value' => 'new Life',
					'admin_label' => true
				),
				/*heading color*/
				array(
					'type'       => 'colorpicker',
					'heading'    => esc_html__( 'Color', 'blockter' ),
					'param_name' => 'color',
					'value' => '#ffffff',
				),
				/*heading font size*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Font Size', 'blockter' ),
					'param_name' => 'font_size',
					'value' => '72px',
					'description' => esc_html__('Enter font size. Ex: "30px", "1.5rem" or "2em"', 'blockter'),
				),
				/*icon*/
				array(
					'type' => 'iconpicker',
					'heading' => esc_html__( 'Icon play', 'blockter' ),
					'param_name' => 'light_icon',
					'value' => 'fa fa-play',
					'description' => esc_html__( 'Select icon from library.', 'blockter' ),
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
			),
		));
		/*countdown*/
        vc_map( array(
            'name' => esc_html__( 'Countdown Time', 'blockter' ),
            'base' => 'countdown',
            'category' => esc_html__('Blockter Theme', 'blockter'),
            'description' => esc_html__( 'Add a Countdown Time', 'blockter' ),
            'content_element' => true,
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__( 'Time', 'blockter' ),
                    'param_name' => 'time',
                    'value' => '10/27/2018 14:30:00',
                    'description' => esc_html__( 'Enter datetime. For example: 10/20/2020 17:30:00', 'blockter' ),
                ),
                array(
                    'type' => 'colorpicker',
                    'param_name' => 'time_color',
                    'value' => '#000',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__( 'Day text', 'blockter' ),
                    'param_name' => 'days',
                    'value' => 'Days',
                ),
                array(
                    'type' => 'colorpicker',
                    'param_name' => 'day_color',
                    'value' => '#000',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__( 'Hours text', 'blockter' ),
                    'param_name' => 'hours',
                    'value' => 'Hours',
                ),
                array(
                    'type' => 'colorpicker',
                    'param_name' => 'hours_color',
                    'value' => '#000',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__( 'Minute text', 'blockter' ),
                    'param_name' => 'minutes',
                    'value' => 'Minutes',
                ),
                array(
                    'type' => 'colorpicker',
                    'param_name' => 'minute_color',
                    'value' => '#000',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__( 'Second text', 'blockter' ),
                    'param_name' => 'seconds',
                    'value' => 'Seconds',
                ),
                array(
                    'type' => 'colorpicker',
                    'param_name' => 'second_color',
                    'value' => '#000',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__( 'URL', 'blockter' ),
                    'param_name' => 'url',
                    'description' => esc_html__('Go to the URL when end countdown (Optional)', 'blockter'),
                    'value' => '',
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
            ),
		));
		/*facebook*/
		vc_map(array(
			'name' => esc_html__( 'Facebook', 'blockter' ),
			'base' => 'facebook',
			'category'=> esc_html__('Blockter Theme', 'blockter'),
			'description' => esc_html__( 'Add a Facebook', 'blockter' ),
			'content_element' => true,
			'params'=> array(
				/*title*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'blockter' ),
					'description' => esc_html__( 'Enter your title', 'blockter' ),
					'param_name' => 'fb_title',
					'admin_label' => true,
					'value' => 'Find us on Facebook',
				),
				/*data src*/
				array(
					'type' => 'textarea',
					'heading' => esc_html__( 'Src of iframe', 'blockter' ),
					'param_name' => 'link',
					'value' => 'https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fhaintheme%2F%3Ffref%3Dts&tabs=timeline&width=350&height=315px&small_header=true&adapt_container_width=false&hide_cover=false&show_facepile=true&appId',
				),
				/*height iframe*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Height', 'blockter' ),
					'description' => esc_html__('Enter empty space height (Note: CSS measurement units allowed).', 'blockter'),
					'param_name' => 'height',
					'value' => '315',
				),
				/*width iframe*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Width', 'blockter' ),
					'description' => esc_html__('Enter empty space width (Note: CSS measurement units allowed).', 'blockter'),
					'param_name' => 'width',
					'value' => '300',
				),
			),
		));
		/*twitter*/
		vc_map(array(
			'name' => esc_html__( 'Twitter', 'blockter' ),
			'base' => 'twitter',
			'category'=> esc_html__('Blockter Theme', 'blockter'),
			'description' => esc_html__( 'Add a Twitter', 'blockter' ),
			'content_element' => true,
			'params'=> array(
				/*title*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'blockter' ),
					'description' => esc_html__( 'Enter your title', 'blockter' ),
					'param_name' => 'tw_title',
					'admin_label' => true,
					'value' => 'Tweet to us',
				),
				/*id*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter the ID', 'blockter' ),
					'param_name' => 'id',
					'value' => '599202861751410688',
				),
			),
		));
    }
}