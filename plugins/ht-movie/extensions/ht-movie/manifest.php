<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$manifest = array();

$manifest['name']        = esc_html__( 'HT Movie', 'blockter' );
$manifest['description'] = esc_html__(
	'This extension an advanced movie library managing plugin '
	.' to turn your WordPress Blog into a Movie Library.',
	'blockter'
);
$manifest['version'] = '1.0';
$manifest['thumbnail'] = 'fa fa-film';
$manifest['display'] = true;
$manifest['standalone'] = true;
