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
                    'overview' => array(
                        'type' => 'wp-editor', 
                        'label' => esc_attr__('Overview', 'blockter')
                    ),
                    'creators'      => array(
                        'type'       => 'text',
                        'label'      => __( 'Creator', 'blockter' ),
                        'population' => 'taxonomy',
                        'source'     => 'mv_actor',
                    ),
                    'first_air_date' => array(
                        'type' => 'text', 
                        'label' => esc_attr__('First Air Date', 'blockter')
                    ),
                    'episode_runtime' => array(
                        'type' => 'short-text', 
                        'label' => esc_attr__('Episode Runtime', 'blockter')
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
                        'label' => esc_attr__('Video URLs', 'blockter'),
                        'desc' => __('Enter Youtube video key, link this: ue80QwXMRHg', 'blockter')
                    ),
                )
            ),
            'tab3' => array(
                'title' => esc_attr__('Season', 'blockter'), 
                'type' => 'tab', 
                'options' => array(
                    'seasons' => array(
                        'type'  => 'addable-box',
                        'value' => array(
                            array(
                                'season_number' => '',
                                'episode_count' => '',
                                'air_date'      => '',
                                'overview'      => '',
                                'poster_path'   => ''
                            ),
                        ),
                        'label' => __('All Seasons', 'blockter'),
                        'box-options' => array(
                            'season_number' => array( 'type' => 'text' ),
                            'episode_count' => array( 'type' => 'text' ),
                            'air_date'      => array( 'type' => 'text' ),
                            'overview'      => array( 'type' => 'textarea' ),
                            'poster_path'   => array( 'type' => 'text' ),
                        ),
                        'template' => 'Season {{- season_number }}', // box title
                        'limit' => 0, // limit the number of boxes that can be added
                        'add-button-text' => __('Add New Season', 'blockter'),
                        'sortable' => true,
                    )
                )
            ),
            'tab4' => array(
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