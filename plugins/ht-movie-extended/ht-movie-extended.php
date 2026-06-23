<?php
/*
Plugin Name: HT Movie Extened
Plugin URI: https://insomniacs.party
Description: HT Movie Extened
Version: 1.0.0
Requires at least: 4.5
Tested up to: 5.8.2
Author: Shirso
Author URI: https://www.example.com/
Text Domain: hte
Domain Path: /languages/
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if (!defined('HTE_PLUGIN_DIR'))
    define( 'HTE_PLUGIN_DIR', dirname(__FILE__) );
if (!defined('HTE_PLUGIN_ROOT_PHP'))
    define( 'HTE_PLUGIN_ROOT_PHP', dirname(__FILE__).'/'.basename(__FILE__)  );
if(!defined('HTE_PLUGIN_ABSOLUTE_PATH'))
    define('HTE_PLUGIN_ABSOLUTE_PATH',plugin_dir_url(__FILE__));
if (!defined('HTE_PLUGIN_ADMIN_DIR'))
    define( 'HTE_PLUGIN_ADMIN_DIR', dirname(__FILE__) . '/admin' );
if (!defined('HTE_TEXT_DOMAIN'))
    define( 'HTE_TEXT_DOMAIN', 'hte' );
class HT_Movie_Extended {
     public function __construct() {
         $this->includes();
     }
      private function includes() {
         require_once HTE_PLUGIN_DIR . '/vendor/autoload.php';
         require_once HTE_PLUGIN_ADMIN_DIR . '/class-admin.php';
      }

public static function ht_tmdb_create_tables() {
    global $wpdb;

    $table = $wpdb->prefix . 'ht_tmdb_queue';
    $charset_collate = $wpdb->get_charset_collate();

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $sql = "CREATE TABLE $table (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        tmdb_id BIGINT UNSIGNED NOT NULL,
        type VARCHAR(10) NOT NULL,
        status VARCHAR(20) DEFAULT 'pending',
        priority TINYINT DEFAULT 0,
        attempts TINYINT DEFAULT 0,
        last_attempt DATETIME NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY tmdb_id (tmdb_id),
        KEY status (status),
        KEY type (type)
    ) $charset_collate;";

    dbDelta($sql);
}
}
register_activation_hook(__FILE__, ['HT_Movie_Extended','ht_tmdb_create_tables']);

new HT_Movie_Extended();