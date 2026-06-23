<?php
if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'background_image' => array(
		'type'  => 'upload',
		'label' => esc_attr__( 'Background Image', 'blockter' ),
	),
);
