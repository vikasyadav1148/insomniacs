<?php
/**
 * Option metabox for Movie post
 */

 $options = array(
    'metabox' => array(
        'type' => 'box',
        'title' => esc_attr__('Movie Detail', 'blockter'), 
        'priority' => 'high', 
        'options' => array(
            'tab1' => array(
                'title' => esc_attr__('General', 'blockter'), 
                'type' => 'tab', 
                'options' => array(
                    'tagline' => array(
                        'type' => 'text', 
                        'label' => esc_attr__('Tagline', 'blockter')
                    ),
                    'overview' => array(
                        'type' => 'wp-editor', 
                        'label' => esc_attr__('Overview', 'blockter')
                    ),
                    'directors'      => array(
                        'type'       => 'text',
                        'label'      => __( 'Director', 'blockter' ),
                        'population' => 'taxonomy',
                        'source'     => 'mv_actor',
                       
                    ),
                    'writers' => array(
                        'type'       => 'text',
                        'label'      => __( 'Writer', 'blockter' ),
                        'population' => 'taxonomy',
                        'source'     => 'mv_actor',
                    ),
                    'release_date' => array(
                        'type' => 'text', 
                        'label' => esc_attr__('Release Date', 'blockter')
                    ),
                    'runtime' => array(
                        'type' => 'short-text', 
                        'label' => esc_attr__('Runtime', 'blockter')
                    ),
                    'production' => array(
                        'type' => 'text', 
                        'label' => esc_attr__('Production', 'blockter')
                    ),
                    'country' => array(
                        'type' => 'text', 
                        'label' => esc_attr__('Country', 'blockter')
                    ),
                    'languages' => array(
                        'type' => 'text', 
                        'label' => esc_attr__('Languages', 'blockter')
                    ),
                )
            ),
            'tab2' => array(
                'title' => esc_attr__('Media', 'blockter'), 
                'type' => 'tab', 
                'options' => array(
                    'banner' => array(
                        'type' => 'upload', 
                        'label' => esc_attr__('Banner', 'blockter'), 
                        'images_only' => true
                    ),
                    'gallery' => array(
                        'type' => 'multi-upload', 
                        'label' => esc_attr__('Gallery', 'blockter'), 
                        'images_only' => true
                    ),
                    'video' => array(
                        'type' => 'addable-option', 
                        'label' => esc_attr__('Video Youtube URLs', 'blockter'),
                        'desc' => __('Enter Youtube video key, link this: ue80QwXMRHg', 'blockter')
                    ),
                    'hosted_videos' => array(
                        'type'  => 'addable-box',
                        'help'  => __('This option will replace Youtube video URLs', 'blockter'),
                        'box-options' => array(
                            'movie_title' => array( 
                                'type' => 'text' 
                            ),
                            'movie_url' => array( 
                                'type' => 'text' 
                            ),
                            'movie_thumb' => array(
                                'type'  => 'upload',
                                'value' => array(
                                    /*
                                    'attachment_id' => '9',
                                    'url' => '//site.com/wp-content/uploads/2014/02/whatever.jpg'
                                    */
                                    // if value is set in code, it is not considered and not used
                                    // because there is no sense to set hardcode attachment_id
                                ),
                                'label' => __('Thumbnail', 'blockter'),
                                'desc'  => __('Set the movie thumbnail', 'blockter'),
                                /**
                                 * If set to `true`, the option will allow to upload only images, and display a thumb of the selected one.
                                 * If set to `false`, the option will allow to upload any file from the media library.
                                 */
                                'images_only' => true,
                            )
                        ),
                        'template' => 'Hello {{- movie_title }}', // box title
                        'limit' => 0, // limit the number of boxes that can be added
                        'add-button-text' => __('Add', 'blockter'),
                        'sortable' => true,
                        'label' => esc_attr__('Hosted Video URLs', 'blockter'),
                        'desc' => __('Enter link of the video here, support: .mp4 & .webm', 'blockter')
                    ),
                    'iframe_videos' => array(
                        'type'  => 'addable-box',
                        'help'  => __('This option will re  place Youtube video URLs & Hosted URL', 'blockter'),
                        'box-options' => array(
                            'movie_title' => array( 
                                'type' => 'text' 
                            ),
                            'movie_iframe' => array( 
                                'type' => 'textarea' 
                            ),
                            'movie_thumb' => array(
                                'type'  => 'upload',
                                'value' => array(
                                    /*
                                    'attachment_id' => '9',
                                    'url' => '//site.com/wp-content/uploads/2014/02/whatever.jpg'
                                    */
                                    // if value is set in code, it is not considered and not used
                                    // because there is no sense to set hardcode attachment_id
                                ),
                                'label' => __('Thumbnail', 'blockter'),
                                'desc'  => __('Set the movie thumbnail', 'blockter'),
                                /**
                                 * If set to `true`, the option will allow to upload only images, and display a thumb of the selected one.
                                 * If set to `false`, the option will allow to upload any file from the media library.
                                 */
                                'images_only' => true,
                            )
                        ),
                        'template' => 'Hello {{- movie_title }}', // box title
                        'limit' => 0, // limit the number of boxes that can be added
                        'add-button-text' => __('Add', 'blockter'),
                        'sortable' => true,
                        'label' => esc_attr__('Iframe Video Code', 'blockter'),
                        'desc' => __('Embed Iframe of the video here', 'blockter')
                    )
                )
            ),
            'tab3' => array(
                'title' => esc_attr__('Button', 'blockter'), 
                'type' => 'tab', 
                'options' => array(
                    'button_1_text' => array(
                        'type' => 'short-text', 
                        'label' => esc_attr__('Button 1 Text', 'blockter')
                    ),
                    'button_1_url' => array(
                        'type' => 'text', 
                        'label' => esc_attr__('Button 1 URL', 'blockter')
                    ),
                    'button_2_text' => array(
                        'type' => 'short-text', 
                        'label' => esc_attr__('Button 2 Text', 'blockter')
                    ),
                    'button_2_url' => array(
                        'type' => 'text', 
                        'label' => esc_attr__('Button 2 URL', 'blockter')
                    )
                )
            ),
        )
    )
 );