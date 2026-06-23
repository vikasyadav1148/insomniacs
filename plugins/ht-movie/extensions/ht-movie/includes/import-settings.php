<?php
/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */

/**
 * custom option and settings
 */
function ht_movie_import_settings_init() {
    // register a new setting for "Import Movie" page
    register_setting( 'import-movie', 'import_movies' );

    // register a new section in the "wporg" page
    add_settings_section(
    'wporg_section_developers',
    __( 'The Matrix has you.', 'wporg' ),
    'wporg_section_developers_cb',
    'wporg'
    );

    // register a new field in the "wporg_section_developers" section, inside the "wporg" page
    add_settings_field(
    'wporg_field_pill', // as of WP 4.6 this value is used only internally
    // use $args' label_for to populate the id inside the callback
    __( 'Pill', 'wporg' ),
    'wporg_field_pill_cb',
    'wporg',
    'wporg_section_developers',
    [
    'label_for' => 'wporg_field_pill',
    'class' => 'wporg_row',
    'wporg_custom_data' => 'custom',
    ]
    );
}

/**
 * register our ht_movie_import_settings_init to the admin_init action hook
 */
add_action( 'admin_init', 'ht_movie_import_settings_init' );

/**
 * custom option and settings:
 * callback functions
 */

// developers section cb

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function wporg_section_developers_cb( $args ) {
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', 'wporg' ); ?></p>
    <?php
}

// pill field cb

// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function wporg_field_pill_cb( $args ) {
    // get the value of the setting we've registered with register_setting()
    $options = get_option( 'wporg_options' );
    // output the field
    ?>
    <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
    data-custom="<?php echo esc_attr( $args['wporg_custom_data'] ); ?>"
    name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
    >
    <option value="red" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'red', false ) ) : ( '' ); ?>>
    <?php esc_html_e( 'red pill', 'wporg' ); ?>
    </option>
    <option value="blue" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'blue', false ) ) : ( '' ); ?>>
    <?php esc_html_e( 'blue pill', 'wporg' ); ?>
    </option>
    </select>
    <p class="description">
    <?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'wporg' ); ?>
    </p>
    <p class="description">
    <?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'wporg' ); ?>
    </p>
    <?php
}

/**
 * top level menu
 */
function ht_movie_import_options_page() {
		$submenu_pages = array(

    	// View All TV Shows
    	array(
    		'parent_slug' => 'edit.php?post_type=ht_movie',
    		'page_title'  => 'HT TV Show',
    		'menu_title'  => 'All TV Show',
    		'capability'  => 'manage_options',
    		'menu_slug'   => 'edit.php?post_type=ht_show',
    		'function'    => null
    	),

    	// Add New TV Show
    	array(
    		'parent_slug' => 'edit.php?post_type=ht_movie',
    		'page_title'  => 'Add New TV Show',
    		'menu_title'  => 'Add New TV Show',
    		'capability'  => 'manage_options',
    		'menu_slug'   => 'post-new.php?post_type=ht_show',
    		'function'    => null
    	),

    );

    foreach ($submenu_pages as $submenu ) {
    	add_submenu_page(
    		$submenu['parent_slug'],
    		$submenu['page_title'],
    		$submenu['menu_title'],
    		$submenu['capability'],
    		$submenu['menu_slug'],
    		$submenu['function']
    	);
    }

    // add top level menu page
    add_submenu_page(
    	'edit.php?post_type=ht_movie',
    	'Import movies',
    	'Import Movie / TV Show',
    	'manage_options',
    	'import_movie',
    	'ht_movie_import_options_page_html'
  	);
}

/**
 * register our ht_movie_import_options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'ht_movie_import_options_page' );

/**
 * top level menu:
 * callback functions
 */
function ht_movie_import_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
    return;
    }
    // Render Import Setting View
    $import_settings_html = plugin_dir_path(__FILE__). '../views/import/import_settings_html.php';
    echo fw_render_view($import_settings_html);
}
