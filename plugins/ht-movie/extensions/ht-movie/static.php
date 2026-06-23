<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

if ( is_admin() ) {
	/**
	 * @var HT Movie $ht_movie
	 */
    $ht_movie = fw()->extensions->get( 'ht-movie' );
    $only = array(
		'only' => array(
			array('id' => 'ht_movie_page_import_movie')
		)
	);
    //fw_print($screen);
    if(fw_current_screen_match($only)){
			// CSS
			wp_enqueue_style(
							'font-awesome',
							$ht_movie->locate_css_URI( 'font-awesome' ),
							array(),
							$ht_movie->manifest->get_version()
			);
					wp_enqueue_style(
							'bulma',
							$ht_movie->locate_css_URI( 'bulma' ),
							array(),
							$ht_movie->manifest->get_version()
			);
			wp_enqueue_style(
							'vue-multiselect.min',
							$ht_movie->locate_css_URI( 'vue-multiselect.min' ),
							array(),
							$ht_movie->manifest->get_version()
			);

			// Javascript
			wp_enqueue_script(
				'vue',
				$ht_movie->locate_js_URI( 'vue' ),
				array(),
				$ht_movie->manifest->get_version(),
				true
			);
			wp_enqueue_script(
				'vendor',
				$ht_movie->locate_js_URI( 'vendor' ),
				array(),
				$ht_movie->manifest->get_version(),
				true
			);
			wp_enqueue_script(
				'import-movie',
				$ht_movie->locate_js_URI( 'app' ),
				array('vue'),
				time(),//$ht_movie->manifest->get_version(),
				true
			);
			wp_localize_script(
				'import-movie',
				'importMovieLocalize',
				array(
					'nonce' => wp_create_nonce( 'wp_rest' ),
					'rest_url' => esc_url_raw( rest_url() ),
					'ajax_url' => admin_url( 'admin-ajax.php' ),

					'api_key' => fw_get_db_ext_settings_option( 'ht-movie', 'api-key', NULL ),
					'template_style' => fw_get_db_ext_settings_option( 'ht-movie', 'template-style', 'style-1' ),
					'language' => fw_get_db_ext_settings_option( 'ht-movie', 'language-option', 'en' ),
					'import_cast' => fw_get_db_ext_settings_option( 'ht-movie', 'import-cast', 'enable' ),
					'import_genre' => fw_get_db_ext_settings_option( 'ht-movie', 'import-genre', 'enable' ),
					'import_collection' => fw_get_db_ext_settings_option( 'ht-movie', 'import-collection', 'enable' ),
					'import_poster' => fw_get_db_ext_settings_option( 'ht-movie', 'import-poster', 'enable' ),
					'import_banner' => fw_get_db_ext_settings_option( 'ht-movie', 'import-banner', 'enable' ),
					'import_trailer' => fw_get_db_ext_settings_option( 'ht-movie', 'import-trailer', 'enable' ),
					'import_tagline' => fw_get_db_ext_settings_option( 'ht-movie', 'import-tagline', 'enable' ),
					'import_overview' => fw_get_db_ext_settings_option( 'ht-movie', 'import-overview', 'enable' ),
					'import_director' => fw_get_db_ext_settings_option( 'ht-movie', 'import-director', 'enable' ),
					'import_writer' => fw_get_db_ext_settings_option( 'ht-movie', 'import-writer', 'enable' ),
					'import_release_date' => fw_get_db_ext_settings_option( 'ht-movie', 'import-release-date', 'enable' ),
					'import_runtime' => fw_get_db_ext_settings_option( 'ht-movie', 'import-runtime', 'enable' ),
					'import_production' => fw_get_db_ext_settings_option( 'ht-movie', 'import-production', 'enable' ),
					'import_country' => fw_get_db_ext_settings_option( 'ht-movie', 'import-country', 'enable' ),
					'import_language' => fw_get_db_ext_settings_option( 'ht-movie', 'import-language', 'enable' ),

					'import_tv_season' => fw_get_db_ext_settings_option( 'ht-movie', 'import-tv-season', 'enable' ),
					'import_tv_cast' => fw_get_db_ext_settings_option( 'ht-movie', 'import-tv-cast', 'enable' ),
					'import_tv_genre' => fw_get_db_ext_settings_option( 'ht-movie', 'import-tv-genre', 'enable' ),
					'import_tv_poster' => fw_get_db_ext_settings_option( 'ht-movie', 'import-tv-poster', 'enable' ),
					'import_tv_banner' => fw_get_db_ext_settings_option( 'ht-movie', 'import-tv-banner', 'enable' ),
					'import_tv_trailer' => fw_get_db_ext_settings_option( 'ht-movie', 'import-tv-trailer', 'enable' ),
					'import_tv_overview' => fw_get_db_ext_settings_option( 'ht-movie', 'import-tv-overview', 'enable' ),
					'import_tv_creator' => fw_get_db_ext_settings_option( 'ht-movie', 'import-creator', 'enable' ),
					'import_first_air_date' => fw_get_db_ext_settings_option( 'ht-movie', 'import-first-air-date', 'enable' ),
					'import_episode_runtime' => fw_get_db_ext_settings_option( 'ht-movie', 'import-episode-runtime', 'enable' ),
					'import_tv_production' => fw_get_db_ext_settings_option( 'ht-movie', 'import-tv-production', 'enable' ),
					'import_tv_country' => fw_get_db_ext_settings_option( 'ht-movie', 'import-tv-country', 'enable' ),
					'import_tv_language' => fw_get_db_ext_settings_option( 'ht-movie', 'import-tv-language', 'enable' ),
					'import_castmoreinfo' => fw_get_db_ext_settings_option( 'ht-movie', 'import-castmoreinfo', 'enable' ),
				)
			);
    }
}
