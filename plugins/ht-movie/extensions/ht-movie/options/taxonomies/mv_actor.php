<?php if ( ! defined( 'FW' ) ) { die( 'Forbidden' );}

$options = array(
    'avatar' => array(
        'type' => 'upload',
        'label' => esc_attr__('Avatar', 'blockter')
    ),
    'avatar_url' => array(
        'type' => 'text',
        'label' => esc_attr__('Avatar URL', 'blockter')
    ),
    'dateofbirth'=> array(
        'type'=>'text',
        'label' => esc_attr__('Date of Birth', 'blockter')
    ),
    'gender'=> array(
        'type'=>'text',
        'label' => esc_attr__('Gender', 'blockter')
    ),
    'country'=> array(
        'type'=>'text',
        'label' => esc_attr__('Place of Birth', 'blockter')
    ),
    'biography'=> array(
        'type'=>'textarea',
        'label' => esc_attr__('Biography', 'blockter')
    ),
    'knowfor'=> array(
        'type'=>'text',
        'label' => esc_attr__('Know for', 'blockter')
    ),
    'facebook_link'=> array(
        'type'=>'text',
        'label' => esc_attr__('Facebook Link', 'blockter')
    ),
    'twitter_link'=> array(
        'type'=>'text',
        'label' => esc_attr__('Twitter Link', 'blockter')
    ),
    'instagram_link'=> array(
        'type'=>'text',
        'label' => esc_attr__('Instagram Link', 'blockter')
    )
);

