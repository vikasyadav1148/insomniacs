<?php
/*
Plugin Name: HT Movie
Plugin URI:  http://haintheme.com
Description: This module help to manage movie/tv show database
Version:     1.9
Author:      Haintheme
Author URI:  http://haintheme.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: blockter
*/




/**
 * Register ht-movie extension
 * @param  [type]
 * @return [type]
 */
define( 'HT_MOVIE_VER', '1.9' );
function _ht_filter_my_plugin_extensions_2($locations) {
    $locations[dirname(__FILE__) . '/extensions']
    =
    plugin_dir_url( __FILE__ ) . 'extensions';

    return $locations;
}
add_filter('fw_extensions_locations', '_ht_filter_my_plugin_extensions_2');
