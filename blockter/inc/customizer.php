<?php
/***Theme Customizer***/
/*Add the theme configuration*/
Blockter_Kirki::add_config( 'blockter', array(
 	'option_type' => 'theme_mod',
 	'capability'  => 'edit_theme_options',
));
/*Add the general section*/
Blockter_Kirki::add_section( 'c_general', array(
 	'title'      => esc_attr__( 'General', 'blockter'),
 	'priority'   => 1,
 	'capability' => 'edit_theme_options',
));

/*Add breadcrumbs section*/
Blockter_Kirki::add_section( 'c_crumbs', array(
 	'title'      => esc_attr__( 'Page Breadcrumbs', 'blockter'),
 	'capability' => 'edit_theme_options',
	 'priority'   => 3,
));

/*Add blog section*/
Blockter_Kirki::add_section( 'blog', array(
 	'title'      => esc_attr__( 'Blog', 'blockter'),
 	'priority'   => 4,
 	'capability' => 'edit_theme_options',
));

/*Add footer section*/
Blockter_Kirki::add_section( 'c_footer', array(
 	'title'      => esc_attr__( 'Footer', 'blockter'),
 	'priority'   => 5,
 	'capability' => 'edit_theme_options',
));

/*Add color section*/
Blockter_Kirki::add_section( 'color', array(
 	'title'      => esc_attr__( 'Colors', 'blockter'),
 	'priority'   => 17,
 	'capability' => 'edit_theme_options',
));

/*Add typo section*/
Blockter_Kirki::add_section( 'typo', array(
 	'title'      => esc_attr__( 'Typography', 'blockter'),
 	'capability' => 'edit_theme_options',
	 'priority'   => 18,
));
/*COLOR=================================================================================================*/
/*primary color*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'color-alpha',
	'settings'  => 'primary_color',
	'label'     => esc_attr__( 'Primary color', 'blockter' ),
	'section'   => 'color',
	'default' => '#dcf836',
	'transport' => 'auto',
	'output'      => array(
		array(
			'element' =>array(
				'blockquote:before',
				'.blog-post-info .post-tit a',
				'.post-author-info .author-name',
				'.post-author-info-single .author-name',
				'.movie-grid-items .movie-grid-it:hover .movie-content .mv-title a',
				'article.post .blog-post-content .post-tit a:hover, article .sc-blog-item .blog-post-content .post-tit a:hover',
				'.consult-blog-sc .sc-blog-item:hover .post-tit a',
				'.btn-text-link a:hover',
				'.widget_recent_celebrity .widget_recent_celebrity_item:hover .celebrity-summary a, .theme-cast-items .widget_recent_celebrity_item:hover .celebrity-summary a, .widget_recent_celebrity .cast-item:hover .celebrity-summary a, .theme-cast-items .cast-item:hover .celebrity-summary a',
				'.category-filter .active, .category-filter button:hover',
				'.category-pagination li .current, .category-pagination li a:hover',
				'.theme-footer .theme-footer-widget .row .widget_nav_menu ul li a:hover',
				'.widget_mailchimp .footer-email-submit:hover',
				'.theme-footer .coppy-right .flex-ft-item a:hover',
				'.theme-footer .coppy-right .scroll-to-top:hover span',
				'.celebrity-topbar-filter .celebrity-view .current, .celebrity-topbar-filter .celebrity-view a:hover',
				'.celebrity-list-item .theme-celebrity-items .item:hover .celebrity-infor .celebrity-name a',
				'.celebrity-pagination .current, .celebrity-pagination a:hover',
				'.paging-navigation span:hover, .paging-navigation a:hover',
				'.paging-navigation .current',
				'.movie_single .movie-single-content .tab-links li.active a, .movie_single .movie-single-content .tab-links li:hover a',
				'.movie_single .movie-single-content .overview-sb-it a:hover',
				'.movie_single .movie-single-content #media .videos .vd-it:hover .vd-title',
				'.movie_single .movie-single-content .actor-list-items .ac-it a.actor-name:hover',
				'.widget-area .widget_tag_cloud a:hover',
				'.widget-area .widget_categories ul li a:hover',
				'.widget-area .blockter_widget_genres ul li a:hover',
				'.widget-area .blockter_widget_collections ul li a:hover',

			),
			'property' => 'color'
		),
		array(
			'element' =>array(
				'theadding th',
				'.blog-btn-more:before',
				'.blog-btn-more:hover:before',
				'.widget_search .search-submit',
				'.tagcloud a:hover',
				'.movie-slider-items.movie-slider-style-2 .movie-grid-it-style-2 .movie-content .readmore-btn:hover',
				'.movie-slider-items.movie-slider-style-3 .movie-grid-it-style-2 .movie-content .readmore-btn:hover',
				'.theme-twitter-carousel .slick-dots li.slick-active',
				'.movie_single .movie-poster .movie-btns .yellowbtn',
				'.consult-comment-related .submit:hover',
			),
			'property' => 'background-color'
		),
		array(
			'element' => array(
				'.widget_mailchimp .footer-email-label input:focus',
				'.paging-navigation span:hover, .paging-navigation a:hover',
				'.paging-navigation .current',
				'.movie_single .movie-single-content .tab-links li.active a, .movie_single .movie-single-content .tab-links li:hover a',
				'.consult-comment-related textarea:focus, .consult-comment-related input:focus',
				'.category-content.loading',

			),
			'property' => 'border-color'
		)
	),
));
/*secondary color*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'color-alpha',
	'settings'  => 'secondary_color',
	'label'     => esc_attr__( 'Secondary color', 'blockter' ),
	'section'   => 'color',
	'default' => '#ffffff',
	'transport' => 'auto',
	'output'      => array(
		array(
			'element' => array(
				'h1','h2', 'h3', 'h4', 'h5', 'h6',
				'.category-filter button',
				'.blockter-breadcrumb .page-title',
				'.movie-grid-items .movie-grid-it .movie-content .mv-title a',
				'.theme-movie-items .movie-item .movie-content .mv-title a',
				'.theme-cast-items .cast-item .celebrity-summary a',
				'.movie-trailer-items .vd-title',
				'.movie-slider-items.movie-slider-style-2 .movie-grid-it-style-2 .movie-content .mv-title a',
				'.movie-slider-items.movie-slider-style-3 .movie-grid-it-style-2 .movie-content .mv-title a',
				'.movie-slider-items.movie-slider-style-2 .movie-grid-it-style-2 .movie-content .readmore-btn',
				'.movie-slider-items.movie-slider-style-3 .movie-grid-it-style-2 .movie-content .readmore-btn',
				'.celebrity-list-item .theme-celebrity-items .item .celebrity-name a',
				'.widget-area .widget-title',
				'.theme-footer .footer-widget-title',
				'.consult-comment-related .comment-total-title',
				'.consult-comment-related .comment-reply-title',
				'.widget_recent_celebrity .widget_recent_celebrity_item .celebrity-summary a',
				'.consult-comment-related .submit',
				'.single article .post-tit',
				'.consult-comment-related .comment-list .comment-item .comment-content .comment-author-name',
				'.consult-comment-related .comment-list .comment .comment-content .comment-author-name',
				'.movie_single .movie-single-content #media .videos .vd-it .vd-title',
				'ol.comment-list .comment .comment-body .comment-meta .flex-it .comment-content .flex-it-ava .fn',
				''
			),
			'property' => 'color'
		),
	),
));
/*tertiary color*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'color-alpha',
	'settings'  => 'tertiary_color',
	'label'     => esc_attr__( 'Tertiary color', 'blockter' ),
	'section'   => 'color',
	'default' => '#020d18',
	'transport' => 'auto',
	'output'      => array(
		array(
			'element' => array(
				'.blog-post-info .blog-post-date span.date-day',
				'.theme-blog-news .post-tit a:hover',
				'.blog-post-info .post-tit a:hover',
				'.contact-page-info li span[class*="fa-"]:before',
				'.crumbs a:hover',
				'.contact-page-info li span[class*="ion-"]:before',
				'.movie_single .movie-poster .movie-btns .yellowbtn',
				'.consult-comment-related .submit:hover',
				'.movie-slider-items.movie-slider-style-2 .movie-grid-it-style-2 .movie-content .readmore-btn:hover, .movie-slider-items.movie-slider-style-3 .movie-grid-it-style-2 .movie-content .readmore-btn:hover',
			),
			'property' => 'color'
		),
		array(
			'element' => array(
				'.vc_btn3-style-theme-style-default-btn.theme-style-default-btn',
				'#blockter-blog-sidebar .widget_tag_cloud a',
				'.footer-email-form-widget button',
				'.theme-tags a',
				'.page-error-btn',
				'.cf7-style-7 .cf7-col input[type="submit"]',
				'.cf7-style-8 .cf7-col input[type="submit"]',
				'.theme-slider-btn',
				'.theme-topbar .topbar-right .topbar-btn',
				'.blog-btn-more',
				'.tagcloud a',
				'.blog-standard, .blog-grid, .page-background',

			),
			'property' => 'background-color'
		),
		array(
			'element' => array(
				'.header-search-form',
			),
			'property' => 'border-color'
		),
	),
));
/*sub heading color*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'color-alpha',
	'settings'  => 'subheading_color',
	'label'     => esc_attr__( 'Sub Heading color', 'blockter' ),
	'section'   => 'color',
	'default' => '#4280bf',
	'transport' => 'auto',
	'output'      => array(
		array(
			'element' => array(
				'.widget-area .widget_recent_post_thumbnail .blog-recent-post-thumbnail-sumary .post-tt a',
				'.movie_single .movie-single-content .sub-mv-title h4',
			),
			'property' => 'color'
		),
	),
));

/*sub heading color*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'color-alpha',
	'settings'  => 'titleheading_color',
	'label'     => esc_attr__( 'Sub Heading color', 'blockter' ),
	'section'   => 'color',
	'default' => '#dedede',
	'transport' => 'auto',
	'output'      => array(
		array(
			'element' => array(
				'article.post .blog-post-content .post-tit a, article .sc-blog-item .blog-post-content .post-tit a',
			),
			'property' => 'color'
		),
	),
));
/**/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'color',
	'settings'  => 'button_color',
	'label'     => esc_attr__( 'Button color', 'blockter' ),
	'section'   => 'color',
	'default' => '#dd003f',
	'transport' => 'auto',
	'output'      => array(
		array(
			'element' => array(
				'.form-style-1 .btn-main',
				'.wrapper #loginform p .button.button-primary',
				'.wrapper .close',
				'header .theme-menu-box .menu-flex-box .theme-wrap-primary-menu .primary-menu-right .login-btn a',
				'header .theme-menu-box .menu-flex-box .theme-wrap-primary-menu .primary-menu-right .logout-btn a',
				'.movie-grid-items .movie-grid-it .readmore-btn',
				'.theme-movie-items .movie-item .readmore-btn',
				'.movie_single .movie-poster .movie-btns .redbtn',
				'.movie-slider-items.movie-slider-style-2 .movie-grid-it-style-2 .movie-content .readmore-btn',
				'.movie-slider-items.movie-slider-style-3 .movie-grid-it-style-2 .movie-content .readmore-btn',
				'.consult-comment-related .submit, .comments-area .submit',
			),
			'property' => 'background-color'
		),
		array(
			'element' => array(
				'.widget_mailchimp .footer-email-submit',
				'.widget_mailchimp .footer-email-submit::after',
			),
			'property' => 'color'
		),
	),
));

/*TYPO=================================================================================================*/
/*body font*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'typography',
	'settings'  => 'typo_body',
	'label'     => esc_attr__( 'Body font', 'blockter' ),
	'section'   => 'typo',
	'transport' => 'auto',
	'default'     => array(
		'font-family'    => 'Nunito',
		'variant'        => 'regular',
		'subsets'        => array( 'latin-ext' ),
		'color'          => '#abb7c4',
		'font-size'      => '14px',
		'line-height'    => '28px',
		'letter-spacing' => '0',
		// 'text-transform' => 'none',
	),
	'output'      => array(
		array(
			'element' => array(
				'body',
				'body p',
				'.widget-area .widget_categories ul li a',
				'.widget-area .blockter_widget_genres ul li a',
				'.widget-area .blockter_widget_collections ul li a',
				'.widget-area .widget_tag_cloud a',
			),
		),
	),
));
/*heading font*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'typography',
	'settings'  => 'typo_heading',
	'label'     => esc_attr__( 'Heading font', 'blockter' ),
	'section'   => 'typo',
	'transport' => 'auto',
	'default'     => array(
		'font-family'    => 'Dosis',
		'variant'        => 'bold',
		'subsets'        => array( 'latin-ext' ),
		// 'color'          => '#ffffff',
		'letter-spacing' => '0',
		// 'text-transform' => 'none',
	),
	'output'      => array(
		array(
			'element' =>array(
				'h1','h2', 'h3', 'h4', 'h5', 'h6',
				'article.post .blog-post-content .post-tit a, article .sc-blog-item .blog-post-content .post-tit a',
				'.widget-area .widget_recent_post_thumbnail .blog-recent-post-thumbnail-sumary dt a',
				'.category-filter button',
				'.movie-grid-items .movie-grid-it .movie-content .mv-title a',
				'.widget_recent_celebrity .widget_recent_celebrity_item .celebrity-summary a, .theme-cast-items .widget_recent_celebrity_item .celebrity-summary a, .widget_recent_celebrity .cast-item .celebrity-summary a, .theme-cast-items .cast-item .celebrity-summary a',
				'article.post .blog-post-content .post-tit a, article .sc-blog-item .blog-post-content .post-tit a',
				'header .theme-primary-menu > li > a',
				'.movie_single .movie-single-content .tab-links li a',
				'.widget_mailchimp .footer-email-submit',
				'.celebrity-list-item .theme-celebrity-items .item .celebrity-name a',
				'.consult-comment-related .comment-list .comment-item .comment-content .comment-author-name, .consult-comment-related .comment-list .comment .comment-content .comment-author-name',
				'.consult-comment-related .submit',
				'.movie-trailer-items .vd-title',
				'.view-all-btn a'
			),
		),
	),
));
/*h1*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'typography',
	'settings'  => 'typo_h1',
	'label'     => esc_attr__( 'H1', 'blockter' ),
	'section'   => 'typo',
	'transport' => 'auto',
	'default'     => array(
		'font-size'      => '48px',
	),
	'output'      => array(
		array(
			'element' => 'h1',
		),
	),
));
/*h2*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'typography',
	'settings'  => 'typo_h2',
	'label'     => esc_attr__( 'H2', 'blockter' ),
	'section'   => 'typo',
	'transport' => 'auto',
	'default'     => array(
		'font-size'      => '42px',
	),
	'output'      => array(
		array(
			'element' => 'h2',
		),
	),
));
/*h3*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'typography',
	'settings'  => 'typo_h3',
	'label'     => esc_attr__( 'H3', 'blockter' ),
	'section'   => 'typo',
	'transport' => 'auto',
	'default'     => array(
		'font-size'      => '36px',
	),
	'output'      => array(
		array(
			'element' => 'h3',
		),
	),
));
/*h4*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'typography',
	'settings'  => 'typo_h4',
	'label'     => esc_attr__( 'H4', 'blockter' ),
	'section'   => 'typo',
	'transport' => 'auto',
	'default'     => array(
		'font-size'      => '24px',
	),
	'output'      => array(
		array(
			'element' => 'h4',
		),
	),
));
/*h5*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'typography',
	'settings'  => 'typo_h5',
	'label'     => esc_attr__( 'H5', 'blockter' ),
	'section'   => 'typo',
	'transport' => 'auto',
	'default'     => array(
		'font-size'      => '20px',
	),
	'output'      => array(
		array(
			'element' => 'h5',
		),
	),
));
/*h6*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'typography',
	'settings'  => 'typo_h6',
	'label'     => esc_attr__( 'H6', 'blockter' ),
	'section'   => 'typo',
	'transport' => 'auto',
	'default'     => array(
		'font-size'      => '18px',
	),
	'output'      => array(
		array(
			'element' => 'h6',
		),
	),
));

/*HEADER LAYOUT=================================================================================================*/

/*Header layout 1 ==================================================*/
/*label*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'custom',
	'settings'  => 'kirki_label_header_layout1',
	'label'     => esc_attr__( 'Header Background', 'blockter' ),
	'section'   => 'header_1',
	'partial_refresh' => array(
		'hd1_edit_location' => array(
			'selector'        => '#hd1-edit-location',
			'render_callback' => 'blockter_header_edit_location',
		),
	),
));
/*background menu*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'color-alpha',
	'settings'  => 'menu1_bg',
	'label'     => esc_attr__( 'Background', 'blockter' ),
	'section'   => 'header_1',
	'transport' => 'auto',
	'default' => '#020d18',
	'output' => array(
		array(
			'element' => array(
				'.header-layout-1 .theme-menu-box',
				// '.header-layout-1 .theme-primary-menu > li:not(.menu-item-has-mega-menu) .sub-menu',
			),
			'function' => 'css',
			'property' => 'background-color'
		)
	),
));
/*label*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'custom',
	'settings'  => 'kirki_label_header_layout1_menu',
	'label'     => esc_attr__( 'Menu', 'blockter' ),
	'section'   => 'header_1',
));
/*text color*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'color-alpha',
	'settings'  => 'text_color_1',
	'label'     => esc_attr__( 'Text color', 'blockter' ),
	'section'   => 'header_1',
	'transport' => 'auto',
	'default' => '#abb7c4',
	'output' => array(
		array(
			'element' => array(
				'.header-layout-1  .theme-primary-menu > li > a',
				'.header-layout-1 .theme-menu-box  #ht-btn-search:before',
				'.header-layout-1  .theme-primary-menu > li:not(.menu-item-has-mega-menu) .sub-menu a',
			),
			'function' => 'css',
			'property' => 'color'
		)
	),
));
/*text heading highlight color*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'color-alpha',
	'settings'  => 'text_menu_highlight_1',
	'label'     => esc_attr__( 'Text highlight color', 'blockter' ),
	'section'   => 'header_1',
	'transport' => 'auto',
	'default' => '#dcf836',
	'output' => array(
		array(
			'element' => array(
				'.header-layout-1 .theme-primary-menu > li:hover > a',
				'.header-layout-1 .theme-primary-menu > li.current-menu-item > a',
				'.header-layout-1 .theme-primary-menu > li.current-menu-ancestor > a',
				'.header-layout-1 .theme-primary-menu > li:not(.menu-item-has-mega-menu) .sub-menu a:hover',
				'.header-layout-1 .theme-menu-box #ht-btn-search:hover:before',
			),
			'function' => 'css',
			'property' => 'color'
		),
		array(
			'element' => array(
                '.header-layout-1 .theme-primary-menu > li:not(.menu-item-has-mega-menu) .sub-menu',
			),
			'function' => 'css',
			'property' => 'border-color'
		),
	),
));
/*option to turn on/off ajax movie search funtion*/
//menu stick
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'switch',
	'settings'  => 'ajax_movie_search',
	'label'     => esc_attr__( 'Turn on Search Movie Ajax?', 'blockter' ),
	'section'   => 'header_1',
	'default'   => '0',
	'choices' => array(
		'on' => esc_attr__( 'On', 'blockter' ),
		'off' => esc_attr__( 'Off', 'blockter' ),
	),
));
/*Header layout 2 ==================================================*/

/*label*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'custom',
	'settings'  => 'kirki_label_header_layout2',
	'label'     => esc_attr__( 'Header Background', 'blockter' ),
	'section'   => 'header_2',
));
/*background menu*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'color-alpha',
	'settings'  => 'menu2_bg',
	'label'     => esc_attr__( 'Background', 'blockter' ),
	'section'   => 'header_2',
	'transport' => 'auto',
	'default' => 'rgba(255,255,255,0)',
	'output' => array(
		array(
			'element' => array(
				'.header-layout-2 .theme-menu-box',
				// '.header-layout-2 .theme-menu-box .theme-primary-menu > li:not(.menu-item-has-mega-menu) .sub-menu',
			),
			'function' => 'css',
			'property' => 'background-color'
		)
	),
));

/*label*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'custom',
	'settings'  => 'kirki_label_header_layout2_menu',
	'label'     => esc_attr__( 'Menu', 'blockter' ),
	'section'   => 'header_2',
));
/*text color*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'color-alpha',
	'settings'  => 'text_color_2',
	'label'     => esc_attr__( 'Text color', 'blockter' ),
	'section'   => 'header_2',
	'transport' => 'auto',
	'default' => '#abb7c4',
	'output' => array(
		array(
			'element' => array(
				'.header-layout-2 .theme-primary-menu > li > a, .header-layout-2 .theme-menu-box #ht-btn-search:before',
				'.header-layout-2 .theme-primary-menu > li:not(.menu-item-has-mega-menu) .sub-menu a',
			),
			'function' => 'css',
			'property' => 'color'
		)
	),
));
/*text heading highlight color*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'color-alpha',
	'settings'  => 'text_menu_highlight_2',
	'label'     => esc_attr__( 'Text highlight color', 'blockter' ),
	'section'   => 'header_2',
	'transport' => 'auto',
	'default' => '#dcf836',
	'output' => array(
		array(
			'element' => array(
				'.header-layout-2 .theme-primary-menu > li:hover > a',
				'.header-layout-2 .theme-primary-menu > li.current-menu-item > a',
				'.header-layout-2 .theme-primary-menu > li.current-menu-ancestor > a',
				'.header-layout-2 .theme-primary-menu > li:not(.menu-item-has-mega-menu) .sub-menu a:hover',
				'.header-layout-2 .theme-menu-box #ht-btn-search:hover:before',
			),
			'function' => 'css',
			'property' => 'color'
		),
		array(
			'element' => array(
                '.header-layout-2 .theme-primary-menu > li:not(.menu-item-has-mega-menu) .sub-menu',
			),
			'function' => 'css',
			'property' => 'border-color'
		),
	),
));
/*Header layout 3 ==================================================*/
/*label*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'custom',
	'settings'  => 'kirki_label_header_layout3',
	'label'     => esc_attr__( 'Header Background', 'blockter' ),
	'section'   => 'header_3',
));
/*background menu*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'color-alpha',
	'settings'  => 'menu3_bg',
	'label'     => esc_attr__( 'Background', 'blockter' ),
	'section'   => 'header_3',
	'transport' => 'auto',
	'default' => 'rgba(255,255,255,0)',
	'output' => array(
		array(
			'element' => array(
				'.header-layout-3 .theme-menu-box',
				// '.header-layout-3 .theme-menu-box .theme-primary-menu > li:not(.menu-item-has-mega-menu) .sub-menu',
			),
			'function' => 'css',
			'property' => 'background-color'
		)
	),
));
/*label*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'custom',
	'settings'  => 'kirki_label_header_layout3_menu',
	'label'     => esc_attr__( 'Menu', 'blockter' ),
	'section'   => 'header_3',
));
/*text color*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'color-alpha',
	'settings'  => 'text_color_3',
	'label'     => esc_attr__( 'Text color', 'blockter' ),
	'section'   => 'header_3',
	'transport' => 'auto',
	'default' => '#abb7c4',
	'output' => array(
		array(
			'element' => array(
				'.header-layout-3 .theme-primary-menu > li > a, .header-layout-3 .theme-menu-box #ht-btn-search:before',
				'.header-layout-3 .theme-primary-menu > li:not(.menu-item-has-mega-menu) .sub-menu a',
			),
			'function' => 'css',
			'property' => 'color'
		)
	),
));
/*text heading highlight color*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'color-alpha',
	'settings'  => 'text_menu_highlight_3',
	'label'     => esc_attr__( 'Text highlight color', 'blockter' ),
	'section'   => 'header_3',
	'transport' => 'auto',
	'default' => '#dcf836',
	'output' => array(
		array(
			'element' => array(
				'.header-layout-3 .theme-primary-menu > li:hover > a',
				'.header-layout-3 .theme-primary-menu > li.current-menu-item > a',
				'.header-layout-3 .theme-primary-menu > li.current-menu-ancestor > a',
				'.header-layout-3 .theme-primary-menu > li:not(.menu-item-has-mega-menu) .sub-menu a:hover',
				'.header-layout-3 .theme-menu-box #ht-btn-search:hover:before',
			),
			'function' => 'css',
			'property' => 'color'
		),
		array(
			'element' => array(
                '.header-layout-3 .theme-primary-menu > li:not(.menu-item-has-mega-menu) .sub-menu',
			),
			'function' => 'css',
			'property' => 'border-color'
		),
	),
));
/*GENERAL===================================================================================================*/
/*header layout*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'select',
	'settings'  => 'header_layout_cfg',
	'label'     => esc_attr__( 'Header Layout', 'blockter' ),
	'section'   => 'c_general',
	'default'   => 'layout-2',
	'description' => esc_attr__('Choose Header Preset Select your main header preset here to apply for all pages', 'blockter'),
	'choices' => array(
		'layout-1' => esc_attr__('Layout 1', 'blockter'),
		'layout-2' => esc_attr__('Layout 2', 'blockter'),
		'layout-3' => esc_attr__('Layout 3', 'blockter'),
	)
));

/*logo image*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'image',
	'settings'  => 'logo_img',
	'label'     => esc_attr__( 'Logo image', 'blockter' ),
	'description' => esc_attr__('Select logo image your website', 'blockter'),
	'section'   => 'c_general',
));

/*loading effect*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'switch',
	'settings'  => 'loading',
	'label'     => esc_attr__( 'Loading effect', 'blockter' ),
	'section'   => 'c_general',
	'default'   => '0',
	'description' => esc_attr__('This option shows animated page loader', 'blockter'),
	'choices' => array(
		'off' => esc_attr__('Off', 'blockter'),
		'on' => esc_attr__('On', 'blockter'),
	)
));

/* Movies/TV Shows per page */
Blockter_Kirki::add_field( 'blockter', array(
	'type'     => 'number',
	'settings' => 'movies_list_per_page',
	'label'    => esc_attr__( 'Movies/TV Shows List per page', 'blockter' ),
	'section'  => 'c_general',
	'default'  => 5,
	'choices'  => array(
		'min'  => -1,
		'step' => 1,
	),
) );

Blockter_Kirki::add_field( 'blockter', array(
	'type'     => 'number',
	'settings' => 'movies_grid_per_page',
	'label'    => esc_attr__( 'Movies/TV Shows Grid per page', 'blockter' ),
	'section'  => 'c_general',
	'default'  => 8,
	'choices'  => array(
		'min'  => -1,
		'step' => 1,
	),
) );

/*BREADCRUMBS===================================================================================================*/

/*en/disable page breadcumbs*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'switch',
	'settings'  => 'c_page_header',
	'label'     => esc_attr__( 'Display', 'blockter' ),
	'section'   => 'c_crumbs',
	'default'   => '1',
	'transport' => 'refresh',
	'priority'  => 2,
	'choices' => array(
		'yes' => esc_attr__('Yes', 'blockter'),
		'no' => esc_attr__('No', 'blockter'),
	),
	'partial_refresh' => array(
		'bread_edit_location' => array(
			'selector'        => '#bread-edit-location',
			'render_callback' => 'blockter_bread_edit_location',
		),
	),
));

/*header text color*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'color-alpha',
	'default' => '#ffffff',
	'label'     => esc_attr__( 'Text color', 'blockter' ),
	'settings'  => 'c_header_text_color',
	'section'   => 'c_crumbs',
	'priority'  => 6,
	'transport' => 'auto',
	'output' => array(
		array(
			'element' => '.bread',
			'function' => 'css',
			'property' => 'color'
		)
	),
	'active_callback'  => array(
		array(
			'setting'  => 'c_page_header',
			'operator' => '==',
			'value'    => '1',
		),
	)
));


/*header text align*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'        => 'select',
	'label'     => esc_attr__( 'Text align', 'blockter' ),
	'settings'    => 'c_header_text_align',
	'section'     => 'c_crumbs',
	'default'     => 'text-center',
	'priority'    => 8,
	'multiple'    => 1,
	'choices'     => array(
		'text-center' => esc_attr__( 'Default: Center', 'blockter' ),
		'text-left' => esc_attr__( 'Left', 'blockter' ),
		'text-right' => esc_attr__( 'Right', 'blockter' ),
	),
	'active_callback'  => array(
		array(
			'setting'  => 'c_page_header',
			'operator' => '==',
			'value'    => '1',
		),
	)
));


/*breadcrumbs display*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'switch',
	'settings'  => 'c_crumbs',
	'section'   => 'c_crumbs',
	'label'     => esc_attr__( 'Navigation bar', 'blockter' ),
	'priority'  => 10,
	'default'     => '0',
	'choices' => array(
		'on'  => esc_attr__( 'On', 'blockter' ),
		'off' => esc_attr__( 'Off', 'blockter' )
	),
	'active_callback'  => array(
		array(
			'setting'  => 'c_page_header',
			'operator' => '==',
			'value'    => '1',
		),
	)
));


/*header text*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'textarea',
	'settings'  => 'c_header_text',
	'label'     => esc_attr__( 'Header description', 'blockter' ),
	'description'     => esc_attr__( 'This will display under breadcrumbs (optional)', 'blockter' ),
	'section'   => 'c_crumbs',
	'priority'  => 15,
	'active_callback'  => array(
		array(
			'setting'  => 'c_page_header',
			'operator' => '==',
			'value'    => '1',
		),
	)
));


/*background breadcrumb*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'radio',
	'settings'  => 'c_header_bg',
	'label'     => esc_attr__( 'Background', 'blockter' ),
	'description' => esc_attr__('If select background image option, the theme recommends a header size of at least 1170 width pixels', 'blockter'),
	'section'   => 'c_crumbs',
	'default'   => 'bg_color',
	'priority'  => 30,
	'choices'   => array(
		'bg_image' => esc_attr__( 'Use Image', 'blockter' ),
		'bg_color' => esc_attr__( 'Use Solid Color', 'blockter' ),
	),
	'active_callback'  => array(
		array(
			'setting'  => 'c_page_header',
			'operator' => '==',
			'value'    => '1',
		),
	),
));
//use img
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'cropped_image',
	'settings'  => 'c_header_bg_image',
	'label'     => esc_attr__( 'Upload Image', 'blockter' ),
	'section'   => 'c_crumbs',
	'width'	=> 1920,
	'height' => 570,
	'description'   => esc_attr__( 'Upload background image of page header here!', 'blockter' ),
	'priority'  => 40,
	'output'     => array(
		array(
			'element' => '.blockter-breadcrumb',
			'function' => 'css',
			'property' => 'background-image',
		)
	),
	'active_callback'  => array(
		array(
			'setting'  => 'c_page_header',
			'operator' => '==',
			'value'    => '1',
		),
		array(
			'setting'  => 'c_header_bg',
			'operator' => '==',
			'value'    => 'bg_image',
		),
	)
));
//use css
Blockter_Kirki::add_field( 'blockter', array(
  'type'        => 'color',
	'settings'    => 'c_header_bg_color',
	'label'       => esc_attr__( 'Select Color', 'blockter' ),
	'section'     => 'c_crumbs',
	'default'     => '#0c2238',
 	'transport'   => 'auto',
	'priority'    => 50,
	'output'     => array(
		array(
			'element' => '.blockter-breadcrumb',
			'function' => 'css',
			'property' => 'background-color',
		)
	),
  	'active_callback'  => array(
		array(
			'setting'  => 'c_page_header',
			'operator' => '==',
			'value'    => '1',
		),
		array(
			'setting'  => 'c_header_bg',
			'operator' => '!=',
			'value'    => 'bg_image',
		),
	)
));


/*BLOG==========================================================================================================*/
/*label*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'custom',
	'settings'  => 'kirki_label_blog_info',
	'label'     => esc_attr__( 'This option only for Posts page', 'blockter' ),
	'section'   => 'blog',
	'priority'  => 1,
));

/*blog title*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'text',
	'settings'  => 'b_header_title',
	'label'     => esc_attr__( 'Header Title', 'blockter' ),
	'default'   => esc_attr__( 'My Blog!', 'blockter' ),
	'section'   => 'blog',
	'priority'  => 5,
));

/*header text*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'textarea',
	'settings'  => 'b_header_text',
	'label'     => esc_attr__( 'Header description', 'blockter' ),
	'description'     => esc_attr__( 'This will display under breadcrumbs (optional)', 'blockter' ),
	'default'   => esc_attr__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur suscipit nulla ligula, nec tincid unt tortor pulvinar a. Proin nunc leo, imperdiet nec risus non.', 'blockter' ),
	'section'   => 'blog',
	'priority'  => 10,
));

/*FOOTER==========================================================================================================*/
/*footer display*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'switch',
	'label'     => esc_attr__( 'Footer widget', 'blockter' ),
	'settings'  => 'c_footer_display',
	'section'   => 'c_footer',
	'default'   => '1',
	'choices' => array(
		'on' => esc_attr__('On', 'blockter'),
		'off' => esc_attr__('Off', 'blockter'),
	)
));

/*footer background*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'radio',
	'settings'  => 'c_footer_bg',
	'label'     => esc_attr__( 'Background', 'blockter' ),
	'description' => esc_attr__('If select background image option, the theme recommends a header size of at least 1170 width pixels', 'blockter'),
	'section'   => 'c_footer',
	'default'   => 'ft_color',
	'choices'   => array(
		'ft_image' => esc_attr__( 'Use Image', 'blockter' ),
		'ft_color' => esc_attr__( 'Use Solid Color', 'blockter' ),
	),
	'active_callback'  => array(
		array(
			'setting'  => 'c_footer_display',
			'operator' => '==',
			'value'    => '1',
		),
	),
));
//use img
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'image',
	'settings'  => 'ft_bg_img',
	'label'     => esc_attr__( 'Upload Image', 'blockter' ),
	'section'   => 'c_footer',
	'description'   => esc_attr__( 'Upload background image of page header here!', 'blockter' ),
	'output' => array(
		array(
			'element' => '.theme-footer',
			'function' => 'css',
			'property' => 'background-image'
		)
	),
	'active_callback'  => array(
		array(
			'setting'  => 'c_footer_display',
			'operator' => '==',
			'value'    => '1',
		),
		array(
			'setting'  => 'c_footer_bg',
			'operator' => '==',
			'value'    => 'ft_image',
		),
	)
));
//use css
Blockter_Kirki::add_field( 'blockter', array(
  'type'        => 'color',
	'settings'    => 'ft_bg_color',
	'label'       => esc_attr__( 'Select Color', 'blockter' ),
	'section'     => 'c_footer',
	'default'     => '#06121f',
 	'transport'   => 'auto',
	'output' => array(
		array(
			'element' => '.theme-footer',
			'function' => 'css',
			'property' => 'background-color'
		)
	),
  	'active_callback'  => array(
		array(
			'setting'  => 'c_footer_display',
			'operator' => '==',
			'value'    => '1',
		),
		array(
			'setting'  => 'c_footer_bg',
			'operator' => '==',
			'value'    => 'ft_color',
		),
	)
));
/*label*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'custom',
	'settings'  => 'kirki_label_copyright',
	'label'     => esc_attr__( 'Copyright', 'blockter' ),
	'section'   => 'c_footer',
));

/*copyright background*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'color-alpha',
	'settings'  => 'c_copyright_bg',
	'label'     => esc_attr__( 'Background color', 'blockter' ),
	'section'   => 'c_footer',
	'default'   => '#0f1c27',
	'transport' => 'auto',
	'output' => array(
		array(
			'element' => '.coppy-right',
			'function' => 'css',
			'property' => 'background-color'
		)
	),
	'partial_refresh' => array(
		'footer_edit_location' => array(
			'selector'        => '#footer-edit-location',
			'render_callback' => 'blockter_footer_edit_location',
		),
	),
));

/*copiright*/
Blockter_Kirki::add_field( 'blockter', array(
	'type'      => 'textarea',
	'settings'  => 'c_copyright',
	'section'   => 'c_footer',
	'default' => '&copy; 2018 Blockter. All Rights Reserved.',
));
