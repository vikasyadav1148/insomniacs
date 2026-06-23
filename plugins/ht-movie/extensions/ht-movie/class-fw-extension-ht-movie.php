<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

class FW_Extension_HT_Movie extends FW_Extension {
	private $mv_post_type = 'ht_movie';
	private $mv_slug = 'movie';

	private $genre_tax = 'mv_genre';
	private $genre_tax_slug = 'genre';

	private $collection_tax = 'mv_collection';
	private $collection_tax_slug = 'collection';

	private $actor_tax = 'mv_actor';
	private $actor_tax_slug = 'actor';

	private $show_post_type = 'ht_show';
	private $show_slug = 'show';

	public function _init() {
		$this->define_slugs();

		add_action( 'init', array( $this, '_action_register_movie_post_type' ) );
		add_action( 'init', array( $this, '_action_register_show_post_type' ) );
		add_action( 'init', array( $this, '_action_register_genere_taxonomy' ) );
		add_action( 'init', array( $this, '_action_register_collection_taxonomy' ) );
		add_action( 'init', array( $this, '_action_register_actor_taxonomy' ) );

		if ( is_admin() ) {
			$this->add_admin_actions();
			$this->add_filter_actions();
			$this->save_permalink_structure();
		}
	}

	public function define_slugs() {
		$this->mv_slug = $this->get_db_data(
			'permalinks/post_type=ht_movie',
			FW_Request::POST(
				'fw_ext_ht_movie_slug',
				apply_filters( 'fw_ext_ht_movie_slug', $this->mv_slug )
			)
		);
		$this->show_slug = $this->get_db_data(
			'permalinks/post_type=ht_show',
			FW_Request::POST(
				'fw_ext_ht_show_slug',
				apply_filters( 'fw_ext_ht_show_slug', $this->show_slug )
			)
		);
		$this->genre_tax_slug = $this->get_db_data(
			'permalinks/taxonomy=mv_genre',
			FW_Request::POST(
				'fw_ext_ht_movie_genre_slug',
				apply_filters( 'fw_ext_ht_movie_genre_slug', $this->genre_tax_slug )
			)
		);
		$this->actor_tax_slug = $this->get_db_data(
			'permalinks/taxonomy=mv_actor',
			FW_Request::POST(
				'fw_ext_ht_movie_actor_slug',
				apply_filters( 'fw_ext_ht_movie_actor_slug', $this->actor_tax_slug )
			)
		);
		$this->collection_tax_slug = $this->get_db_data(
			'permalinks/taxonomy=mv_collection',
			FW_Request::POST(
				'fw_ext_ht_movie_collection_slug',
				apply_filters( 'fw_ext_ht_movie_collection_slug', $this->collection_tax_slug )
			)
		);
		$this->show_slug = apply_filters( 'fw_ext_ht_show_slug', $this->show_slug );
	}

	public function add_admin_actions() {
		add_action( 'admin_init', array( $this, '_action_add_permalink_in_settings' ) );
		// add_action( 'admin_init',  array( $this, 'ht_movie_add_movie_caps' ) );
	}

	/**
	 * @internal
	 **/
	public function _action_add_permalink_in_settings() {
		add_settings_field(
			'fw_ext_ht_movie_base_slug',
			__( 'Movie base', 'fw' ),
			array( $this, '_movie_slug_input' ),
			'permalink',
			'optional'
		);

		add_settings_field(
			'fw_ext_ht_show_base_slug',
			__( 'TV Show base', 'fw' ),
			array( $this, '_show_slug_input' ),
			'permalink',
			'optional'
		);

		add_settings_field(
			'fw_ext_ht_movie_genre_slug',
			__( 'Movie genre base', 'fw' ),
			array( $this, '_genre_slug_input' ),
			'permalink',
			'optional'
		);

		add_settings_field(
			'fw_ext_ht_movie_actor_slug',
			__( 'Movie actor base', 'fw' ),
			array( $this, '_actor_slug_input' ),
			'permalink',
			'optional'
		);

		add_settings_field(
			'fw_ext_ht_movie_collection_slug',
			__( 'Movie collection base', 'fw' ),
			array( $this, '_collection_slug_input' ),
			'permalink',
			'optional'
		);
	}

	public function _movie_slug_input() {
		?>
		<input type="text" name="fw_ext_ht_movie_slug" value="<?php echo $this->mv_slug; ?>">
		<code>/my-movie</code>
		<?php
	}

	public function _show_slug_input() {
		?>
		<input type="text" name="fw_ext_ht_show_slug" value="<?php echo $this->show_slug; ?>">
		<code>/my-show</code>
		<?php
	}

	public function _genre_slug_input() {
		?>
		<input type="text" name="fw_ext_ht_movie_genre_slug" value="<?php echo $this->genre_tax_slug; ?>">
		<code>/my-movies-genre</code>
		<?php
	}

	public function _actor_slug_input() {
		?>
		<input type="text" name="fw_ext_ht_movie_actor_slug" value="<?php echo $this->actor_tax_slug; ?>">
		<code>/my-actor</code>
		<?php
	}

	public function _collection_slug_input() {
		?>
		<input type="text" name="fw_ext_ht_movie_collection_slug" value="<?php echo $this->collection_tax_slug; ?>">
		<code>/my-collection</code>
		<?php
	}

	public function save_permalink_structure() {
		if ( ! isset( $_POST['permalink_structure'] ) && ! isset( $_POST['category_base'] ) ) {
			return;
		}

		$this->set_db_data(
			'permalinks/post_type=ht_movie',
			FW_Request::POST(
				'fw_ext_ht_movie_slug',
				apply_filters( 'fw_ext_ht_movie_slug', $this->mv_slug )
			)
		);
		$this->set_db_data(
			'permalinks/post_type=ht_show',
			FW_Request::POST(
				'fw_ext_ht_show_slug',
				apply_filters( 'fw_ext_ht_show_slug', $this->show_slug )
			)
		);
		$this->set_db_data(
			'permalinks/taxonomy=mv_genre',
			FW_Request::POST(
				'fw_ext_ht_movie_genre_slug',
				apply_filters( 'fw_ext_' . $this->mv_post_type . '_taxonomy_slug', $this->genre_tax_slug )
			)
		);
		$this->set_db_data(
			'permalinks/taxonomy=mv_actor',
			FW_Request::POST(
				'fw_ext_ht_movie_actor_slug',
				apply_filters( 'fw_ext_' . $this->mv_post_type . '_taxonomy_slug', $this->actor_tax_slug )
			)
		);
		$this->set_db_data(
			'permalinks/taxonomy=mv_collection',
			FW_Request::POST(
				'fw_ext_ht_movie_collection_slug',
				apply_filters( 'fw_ext_' . $this->mv_post_type . '_taxonomy_slug', $this->collection_tax_slug )
			)
		);
	}

	public function add_filter_actions() {
		add_filter( 'fw_post_options', array( $this, '_filter_admin_add_ht_movie_options' ), 10, 2 );
		add_filter( 'fw_post_options', array( $this, '_filter_admin_add_ht_show_options' ), 10, 2 );
		add_filter( 'fw_taxonomy_options', array( $this, '_filter_admin_add_tax_options' ), 10, 2 );
	}

	/**
	 * @internal
	 */
	public function _action_register_movie_post_type() {

		$post_names = apply_filters(
			'fw_ext_ht_movie_post_type_name', array(
				'singular' => __( 'Movie', 'blockter' ),
				'plural'   => __( 'HT Movie', 'blockter' ),
			)
		);

		register_post_type(
			$this->mv_post_type, array(
				'labels'             => array(
					'name'               => $post_names['plural'], // __( 'Portfolio', 'fw' ),
					'singular_name'      => $post_names['singular'], // __( 'Portfolio course', 'fw' ),
					'add_new'            => __( 'Add New Movie', 'fw' ),
					'add_new_item'       => sprintf( __( 'Add New %s', 'fw' ), $post_names['singular'] ),
					'edit'               => __( 'Edit', 'fw' ),
					'edit_item'          => sprintf( __( 'Edit %s', 'fw' ), $post_names['singular'] ),
					'new_item'           => sprintf( __( 'New %s', 'fw' ), $post_names['singular'] ),
					'all_items'          => sprintf( __( 'All %s', 'fw' ), $post_names['singular'] ),
					'view'               => sprintf( __( 'View %s', 'fw' ), $post_names['singular'] ),
					'view_item'          => sprintf( __( 'View %s', 'fw' ), $post_names['singular'] ),
					'search_items'       => sprintf( __( 'Search %s', 'fw' ), $post_names['plural'] ),
					'not_found'          => sprintf( __( 'No %s Found', 'fw' ), $post_names['plural'] ),
					'not_found_in_trash' => sprintf( __( 'No %s Found In Trash', 'fw' ), $post_names['plural'] ),
					'parent_item_colon'  => '', /* text for parent types */
				),
				'description'        => __( 'Create a movie', 'blockter' ),
				'public'             => true,
				'show_ui'            => true,
				// 'show_in_menu'       => 'edit.php?post_type=' . $this->lessons,
				'publicly_queryable' => true,
				/* queries can be performed on the front end */
				'has_archive'        => true,
				'rewrite'            => array(
					'slug' => $this->mv_slug,
				),
				// 'menu_position'      => 6,
				'show_in_nav_menus'  => true,
				'show_in_rest'       => true,
				'menu_icon'          => 'dashicons-format-video',
				'hierarchical'       => false,
				'query_var'          => true,
				/* Sets the query_var key for this post type. Default: true - set to $post_type */
				'supports'           => array(
					'title', /* Text input field to create a post title. */
					'editor',
					'thumbnail', /* Displays a box for featured image. */
					'author',
					'page-attributes', /* Enables menu_order for "Story order" in collections. */
				),
				// 'capability_type' => array('ht_movie', 'ht_movies'),
				// 'capabilities' => array(
				// 	'publish_posts' => 'publish_ht_movies',
				// 	'edit_posts' => 'edit_ht_movies',
				// 	'edit_others_posts' => 'edit_others_ht_movies',
				// 	'delete_posts' => 'delete_ht_movies',
				// 	'delete_others_posts' => 'delete_others_ht_movies',
				// 	'read_private_posts' => 'read_private_ht_movies',
				// 	'edit_post' => 'edit_ht_movie',
				// 	'delete_post' => 'delete_ht_movie',
				// 	'read_post' => 'read_ht_movie',
				// ),
			)
		);
	}

	// function ht_movie_add_movie_caps() {
	// 	// Movie
	// 	$role = get_role( 'administrator' );

	// 	$role->add_cap( 'edit_ht_movie' );
	// 	$role->add_cap( 'edit_ht_movies' );
	// 	$role->add_cap( 'edit_others_ht_movies' );
	// 	$role->add_cap( 'publish_ht_movies' );
	// 	$role->add_cap( 'read_ht_movie' );
	// 	$role->add_cap( 'read_private_ht_movies' );
	// 	$role->add_cap( 'delete_ht_movie' );

	// 	$role->add_cap('manage_cast');
	// 	$role->add_cap('edit_cast');
	// 	$role->add_cap('delete_cast');
	// 	$role->add_cap('assign_cast');



	// 	$author_role = get_role( 'author' );

	// 	$author_role->add_cap( 'edit_ht_movie' );
	// 	$author_role->add_cap( 'edit_ht_movies' );
	// 	// $author_role->add_cap( 'edit_others_ht_movies' );
	// 	$author_role->add_cap( 'publish_ht_movies' );
	// 	$author_role->add_cap( 'read_ht_movie' );
	// 	$author_role->add_cap( 'read_private_ht_movies' );
	// 	$author_role->add_cap( 'delete_ht_movie' );

	// 	//TV
	// 	$tv_role = get_role( 'administrator' );

	// 	$tv_role->add_cap( 'edit_ht_tv' );
	// 	$tv_role->add_cap( 'edit_ht_tvs' );
	// 	$tv_role->add_cap( 'edit_others_ht_tvs' );
	// 	$tv_role->add_cap( 'publish_ht_tvs' );
	// 	$tv_role->add_cap( 'read_ht_tv' );
	// 	$tv_role->add_cap( 'read_private_ht_tvs' );
	// 	$tv_role->add_cap( 'delete_ht_tv' );

	// }

	public function _action_register_show_post_type() {

		$post_names = apply_filters(
			'fw_ext_ht_show_post_type_name', array(
				'singular' => __( 'TV Show', 'blockter' ),
				'plural'   => __( 'HT TV Show', 'blockter' ),
			)
		);

		register_post_type( $this->show_post_type, array(
			'labels'             => array(
				'name'               => $post_names['plural'], // __( 'Portfolio', 'fw' ),
				'singular_name'      => $post_names['singular'], // __( 'Portfolio course', 'fw' ),
				'add_new'            => __( 'Add New TV Show', 'fw' ),
				'add_new_item'       => sprintf( __( 'Add New %s', 'fw' ), $post_names['singular'] ),
				'edit'               => __( 'Edit', 'fw' ),
				'edit_item'          => sprintf( __( 'Edit %s', 'fw' ), $post_names['singular'] ),
				'new_item'           => sprintf( __( 'New %s', 'fw' ), $post_names['singular'] ),
				'all_items'          => sprintf( __( 'All %s', 'fw' ), $post_names['singular'] ),
				'view'               => sprintf( __( 'View %s', 'fw' ), $post_names['singular'] ),
				'view_item'          => sprintf( __( 'View %s', 'fw' ), $post_names['singular'] ),
				'search_items'       => sprintf( __( 'Search %s', 'fw' ), $post_names['plural'] ),
				'not_found'          => sprintf( __( 'No %s Found', 'fw' ), $post_names['plural'] ),
				'not_found_in_trash' => sprintf( __( 'No %s Found In Trash', 'fw' ), $post_names['plural'] ),
				'parent_item_colon'  => '', /* text for parent types */
			),
			'description'        => __( 'Create a show', 'blockter' ),
			'public'             => true,
			'show_ui'            => true,
			// 'show_in_menu'       => 'edit.php?post_type=ht_movie',
			'show_in_menu'       => false,
			'publicly_queryable' => true,
			'has_archive'        => true,
			'rewrite'            => array(
				'slug' => $this->show_slug,
			),
			'show_in_nav_menus'  => true,
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-video-alt3',
			'hierarchical'       => false,
			'query_var'          => true,
			/* Sets the query_var key for this post type. Default: true - set to $post_type */
			'supports'           => array(
				'title', /* Text input field to create a post title. */
				'editor',
				'thumbnail', /* Displays a box for featured image. */
				'author',
				'page-attributes', /* Enables menu_order for "Story order" in collections. */
			),
			// 'capability_type' => array('ht_tv', 'ht_tvs'),
			// 'capabilities' => array(
			// 	'publish_posts' => 'publish_ht_tvs',
			// 	'edit_posts' => 'edit_ht_tvs',
			// 	'edit_others_posts' => 'edit_others_ht_tvs',
			// 	'delete_posts' => 'delete_ht_tvs',
			// 	'delete_others_posts' => 'delete_others_ht_tvs',
			// 	'read_private_posts' => 'read_private_ht_tvs',
			// 	'edit_post' => 'edit_ht_tv',
			// 	'delete_post' => 'delete_ht_tv',
			// 	'read_post' => 'read_ht_tv',
			// ),
		) );
	}

	public function _action_register_genere_taxonomy() {
		$genere_name = apply_filters(
			'fw_ext_genere_tax_name', array(
				'singular' => __( 'Genre', 'blockter' ),
				'plural'   => __( 'Genres', 'blockter' ),
			)
		);

		$labels = array(
			'name'              => sprintf(
				_x( '%s', 'taxonomy general name', 'fw' ),
				$genere_name['plural']
			),
			'singular_name'     => sprintf(
				_x( '%s', 'taxonomy singular name', 'fw' ),
				$genere_name['singular']
			),
			'search_items'      => sprintf( __( 'Search %s', 'fw' ), $genere_name['plural'] ),
			'all_items'         => sprintf( __( 'All %s', 'fw' ), $genere_name['plural'] ),
			'parent_item'       => sprintf( __( 'Parent %s', 'fw' ), $genere_name['singular'] ),
			'parent_item_colon' => sprintf( __( 'Parent %s:', 'fw' ), $genere_name['singular'] ),
			'edit_item'         => sprintf( __( 'Edit %s', 'fw' ), $genere_name['singular'] ),
			'update_item'       => sprintf( __( 'Update %s', 'fw' ), $genere_name['singular'] ),
			'add_new_item'      => sprintf( __( 'Add New %s', 'fw' ), $genere_name['singular'] ),
			'new_item_name'     => sprintf( __( 'New %s Name', 'fw' ), $genere_name['singular'] ),
			'menu_name'         => sprintf( __( '%s', 'fw' ), $genere_name['plural'] ),
		);
		$args   = array(
			'labels'            => $labels,
			'public'            => true,
			'hierarchical'      => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'show_in_nav_menus' => true,
			'show_in_rest'       => true,
			'show_tagcloud'     => true,
			'rewrite'           => array(
				'slug' => $this->genre_tax_slug,
			),
		);

		register_taxonomy( $this->genre_tax, array( esc_attr( $this->mv_post_type ), esc_attr( $this->show_post_type ) ), $args );
	}

	public function _action_register_collection_taxonomy() {
		$collection_name = apply_filters(
			'fw_ext_collection_tax_name', array(
				'singular' => __( 'Collection', 'blockter' ),
				'plural'   => __( 'Collections', 'blockter' ),
			)
		);

		$labels = array(
			'name'              => sprintf(
				_x( '%s', 'taxonomy general name', 'fw' ),
				$collection_name['plural']
			),
			'singular_name'     => sprintf(
				_x( '%s', 'taxonomy singular name', 'fw' ),
				$collection_name['singular']
			),
			'search_items'      => sprintf( __( 'Search %s', 'fw' ), $collection_name['plural'] ),
			'all_items'         => sprintf( __( 'All %s', 'fw' ), $collection_name['plural'] ),
			'parent_item'       => sprintf( __( 'Parent %s', 'fw' ), $collection_name['singular'] ),
			'parent_item_colon' => sprintf( __( 'Parent %s:', 'fw' ), $collection_name['singular'] ),
			'edit_item'         => sprintf( __( 'Edit %s', 'fw' ), $collection_name['singular'] ),
			'update_item'       => sprintf( __( 'Update %s', 'fw' ), $collection_name['singular'] ),
			'add_new_item'      => sprintf( __( 'Add New %s', 'fw' ), $collection_name['singular'] ),
			'new_item_name'     => sprintf( __( 'New %s Name', 'fw' ), $collection_name['singular'] ),
			'menu_name'         => sprintf( __( '%s', 'fw' ), $collection_name['plural'] ),
		);
		$args   = array(
			'labels'            => $labels,
			'public'            => true,
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'show_in_nav_menus' => true,
			'show_in_rest'       => true,
			'show_tagcloud'     => false,
			'rewrite'           => array(
				'slug' => $this->collection_tax_slug,
			),
		);

		register_taxonomy( $this->collection_tax, array( esc_attr( $this->mv_post_type ), $this->show_post_type ), $args );
	}

	public function _action_register_actor_taxonomy() {
		$actor_name = apply_filters(
			'actor', array(
				'singular' => __( 'Cast', 'blockter' ),
				'plural'   => __( 'Casts', 'blockter' ),
			)
		);

		$labels = array(
			'name'              => sprintf(
				_x( '%s', 'taxonomy general name', 'fw' ),
				$actor_name['plural']
			),
			'singular_name'     => sprintf(
				_x( '%s', 'taxonomy singular name', 'fw' ),
				$actor_name['singular']
			),
			'search_items'      => sprintf( __( 'Search %s', 'fw' ), $actor_name['plural'] ),
			'all_items'         => sprintf( __( 'All %s', 'fw' ), $actor_name['plural'] ),
			'parent_item'       => sprintf( __( 'Parent %s', 'fw' ), $actor_name['singular'] ),
			'parent_item_colon' => sprintf( __( 'Parent %s:', 'fw' ), $actor_name['singular'] ),
			'edit_item'         => sprintf( __( 'Edit %s', 'fw' ), $actor_name['singular'] ),
			'update_item'       => sprintf( __( 'Update %s', 'fw' ), $actor_name['singular'] ),
			'add_new_item'      => sprintf( __( 'Add New %s', 'fw' ), $actor_name['singular'] ),
			'new_item_name'     => sprintf( __( 'New %s Name', 'fw' ), $actor_name['singular'] ),
			'menu_name'         => sprintf( __( '%s', 'fw' ), $actor_name['plural'] ),
		);
		$args   = array(
			'labels'            => $labels,
			'public'            => true,
			'hierarchical'      => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'show_in_nav_menus' => true,
			'show_in_rest'       => true,
			'show_tagcloud'     => true,
//			'capabilities' => array(
//				'manage_terms' => 'manage_cast',
//				'edit_terms' => 'edit_cast',
//				'delete_terms' => 'delete_cast',
//				'assign_terms' => 'assign_cast',
//			),
			'rewrite'           => array(
				'slug' => $this->actor_tax_slug,
			),
		);

		register_taxonomy( $this->actor_tax, array( esc_attr( $this->mv_post_type ), esc_attr( $this->show_post_type ) ), $args );
	}

	/**
	 * Add Metabox
	 * to movie post type
	 *
	 * @param [type] $options
	 * @param [type] $mv_post_type
	 * @return void
	 */
	public function _filter_admin_add_ht_movie_options( $options, $mv_post_type ) {
		if ( $mv_post_type === $this->mv_post_type ) {
			$options[] = array(
				$this->get_options( 'posts/' . $mv_post_type, $options = array() )
			);
		}

		return $options;
	}

	public function _filter_admin_add_ht_show_options( $options, $mv_post_type ) {
		if ( $mv_post_type === $this->show_post_type ) {
			$options[] = array(
				$this->get_options( 'posts/' . $mv_post_type, $options = array() )
			);
		}

		return $options;
	}

	/**
	 * Add Actor metabox
	 *
	 * @param [type] $options
	 * @param [type] $actor_tax
	 * @return void
	 */
	public function _filter_admin_add_tax_options( $options, $actor_tax ) {
		if ( $actor_tax === $this->actor_tax ) {
			$options[] = array(
				$this->get_options( 'taxonomies/' . $actor_tax, $options = array() ),
			);
		}

		return $options;
	}

	public function get_mv_post_type_name() {
		return $this->mv_post_type;
	}

	public function get_show_post_type_name() {
		return $this->show_post_type;
	}

	public function get_genere_tax_name() {
		return $this->genre_tax;
	}
	public function get_collection_tax_name() {
		return $this->collection_tax;
	}
	public function get_actor_tax_name() {
		return $this->actor_tax;
	}
}
