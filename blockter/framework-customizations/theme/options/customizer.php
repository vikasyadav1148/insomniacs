<?php

$options = array(
    /*header layout*/
    'ht_header' => array(
        'type' => 'box',
        'title' => esc_html__('Header Layout', 'blockter'),
        'wp-customizer-args' => array(
            'priority' => 2,
        ),
        'options' => array(
            'header_1' => array(
                'type' => 'box',
                'title' => esc_html__('Layout 1', 'blockter'),
                'options' => array(
                )
            ),
            'header_2' => array(
                'type' => 'box',
                'title' => esc_html__('Layout 2', 'blockter'),
                'options' => array(
                )
            ),
            'header_3' => array(
                'type' => 'box',
                'title' => esc_html__('Layout 3', 'blockter'),
                'options' => array(
                )
            ),
        ),
    ),
);
