<?php //phpcs:ignore

$attrs         = vc_map_get_attributes( $this->getShortcode(), $atts );
$style         = $attrs['movie_style'];
$data          = $attrs['data'];
$term_slug     = 'data_' . $data;
$terms         = $attrs[ $term_slug ];
$inline_css    = $attrs['inline_css'];
$class         = $attrs['class'];
$hidden_all    = $attrs['display_tab_all'];
$css_animation = $attrs['css_animation'];
$movie_per_tab = (int) $attrs['movie_per_tab'];
$movie_per_row = $attrs['movie_per_row'];
$tab_id        = uniqid( 'tabid-' );
$drop_id       = uniqid( 'dropid-' );
$class_to_filter = vc_shortcode_custom_css_class( $inline_css, ' ' ) . $this->getExtraClass( $class ) . $this->getCSSAnimation( $css_animation );
$all_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$class_fw        = ( 'movie-tab-style-fw' === $style ) ? ' full' : '';

$this_page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$args      = array(
	'paged'               => $this_page,
	'post_type'           => 'ht_movie',
	'post_status'         => 'publish',
	'posts_per_page'      => $movie_per_tab,
	'ignore_sticky_posts' => 1,
	'tax_query'           => array(
		array(
			'field'            => 'slug',
			'include_children' => true,
			'operator'         => 'IN',
		)

	),
);
if ($data === 'trending') {

    $trend_raw = $attrs['data_trending'] ?? 'movie-day';

    $parts = explode('-', $trend_raw);

    $trend_type   = $parts[0] ?? 'movie';
    $trend_period = $parts[1] ?? 'day';

    $trending_meta = '_trending_' . $trend_type . '_' . $trend_period;

    $args['meta_key'] = $trending_meta;
    $args['orderby']  = 'meta_value_num';
    $args['order']    = 'ASC';

    $args['meta_query'] = [
        [
            'key'     => $trending_meta,
            'compare' => 'EXISTS'
        ]
    ];
}

switch ( $data ) {
	case 'collections':
		$args['tax_query'][0]['taxonomy'] = 'mv_collection';
		break;
	case 'casts':
		$args['tax_query'][0]['taxonomy'] = 'mv_actor';
		break;
	case 'trending':
		$args['tax_query'][0]['taxonomy'] = 'mv_trending';
		break;
	default:
		$args['tax_query'][0]['taxonomy'] = 'mv_genre';
}

$current_tax = $args['tax_query'][0]['taxonomy'];

if ( ! empty( $terms ) ) {
	$args['tax_query'][0]['terms'] = explode( ',', $terms );
	$list_term                  = explode( ',', $terms );
} else {
	$terms = get_terms( $current_tax );
	foreach ( $terms as $ter ) {
		$list_term[] = $ter->slug;
	}
	$args['tax_query'][0]['terms'] = $list_term;
}

if ( 'no' !== $hidden_all ) {
	$movies = new WP_Query( $args );
} else {

	$args['tax_query'][0]['terms'] = array( $list_term[0] );

	$movies = new WP_Query( $args );
}


if ( $movies->have_posts() ) {
	?>
<div class="flw container-async" id="<?php echo esc_attr( $tab_id ); ?>"
	data-paged="<?php echo esc_attr( $movie_per_tab ); ?>" data-row="<?php echo esc_attr( $movie_per_row ); ?>">
	<div class="category-filter flw">
		<!-- Buttons for desktop -->
		<div class="category-buttons">
			<?php if ( 'no' !== $hidden_all ) : ?>
			<button data-filter="<?php echo esc_attr( $current_tax ); ?>" data-term="all-terms" data-page="1"
				data-media="movie" class="active">
				<?php echo esc_html__( 'All', 'blockter' ); ?>
			</button>
			<?php endif; ?>

			<?php foreach ( $list_term as $index => $ter ) :
				if(trim($ter) === 'movie-day') {
						$terText = 'Trending Today';
				}else if(trim($ter) === 'movie-week') {
						$terText = 'Trending This Week';
				} 
				 ?>
			<button data-filter="<?php echo esc_attr( $current_tax ); ?>"
				data-term="<?php echo esc_attr( trim($ter) ); ?>" data-page="1" data-media="movie"
				class="<?php echo esc_attr( $active ); ?>">
				<?php echo $data=='trending' ? esc_html( $terText ) : esc_html( trim($ter) ); ?>
			</button>
			<?php endforeach; ?>
		</div>

		<!-- Dropdown for mobile devices -->
		<select id="categoryDropdown" class="category-dropdown" aria-label="Category filter">
			<option value="all-terms" data-filter="<?php echo esc_attr( $current_tax ); ?>" data-page="1"
				data-media="movie" data-term="all-terms" class="active">All</option>
			<?php foreach ( $list_term as $index => $ter ) : ?>
			<option data-filter="<?php echo esc_attr( $current_tax ); ?>"
				data-term="<?php echo esc_attr( trim($ter) ); ?>" data-page="1" data-media="movie"
				value="<?php echo esc_attr( trim($ter) ); ?>" class="<?php echo esc_attr( $active ); ?>">
				<?php echo esc_html( trim($ter) ); ?>
			</option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="category-content flw<?php echo esc_attr( $class_fw ); ?>">
		<div class="movie-grid-items ht-grid ht-grid-<?php echo esc_attr( $movie_per_row ); ?>">
			<?php
				while ( $movies->have_posts() ) {
					$movies->the_post();

					$thumbnail_id = get_post_thumbnail_id( get_the_ID() );
					?>
			<div class="ht-grid-item">
				<div class="movie-grid-it">
					<div class="movie-thumbnail">
						<a href="<?php echo esc_url( get_the_permalink() ); ?>">
							<?php
								if ( ! empty( $thumbnail_id ) ) :

									echo wp_kses(
										wp_get_attachment_image( $thumbnail_id, 'blockter-poster-movie-item-fw' ),
										array(
											'img' => array(
												'width' => array(),
												'hight' => array(),
												'src'   => array(),
												'alt'   => array(),
												'class' => array(),
											),
										)
									);
								else :
									?>
							<img src="<?php echo esc_url( get_template_directory_uri() . '/images/poster.png' ); ?>"
								alt="<?php echo esc_attr( 'Poster Placeholder' ); ?>">
							<?php
								endif;
								?>
							<span class="readmore-btn">
								<?php echo esc_html__( 'Read More', 'blockter' ); ?>
								<i class="ion-android-arrow-dropright"></i>
							</span>
						</a>
					</div>
					<div class="movie-content">
						<h6 class="mv-title">
							<a itemprop="url" href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
							</a>
						</h6>
						<?php
								$feedback = fw()->extensions->get( 'feedback' );
								if ( null !== $feedback ) :
									if ( comments_open() && get_comments_number() ) :
										?>
						<div class="rate-average">
							<div class="left-it">
								<span class="fa fa-star icon"></span>
								<div class="inner-cmt-infor">
									<?php $average = fw_ext_feedback_stars_get_post_rating(); ?>
									<div class="rate-num">
										<span>
											<?php echo esc_html( number_format( $average['average'], 0 ) ); ?>
										</span>
										<span class="sm-text">
											<?php echo esc_html__( '/', 'blockter' ); ?>
										</span>
										<span class="sm-text">
											<?php
														$star = fw_ext_feedback_stars_get_post_detailed_rating( $post->ID );
														echo esc_html( count( $star['stars'] ) );
														?>
										</span>
									</div>
								</div>
							</div>
						</div>
						<?php
									endif;
								endif;
								?>
					</div>
				</div>
			</div>
			<?php
				}
				wp_reset_postdata();
				?>
		</div>
		<?php blockter_ajax_pager( $movies, $this_page, 'movie' ); ?>
	</div>

</div>
<?php
}