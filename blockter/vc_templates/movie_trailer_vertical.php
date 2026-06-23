<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$class_to_filter  = '';
$class_to_filter .= vc_shortcode_custom_css_class( $inline_css, ' ' ) . $this->getExtraClass( $class ) . $this->getCSSAnimation( $css_animation );
$all_class        = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$data_collections = explode(',',$data_collections);
$data_genres      = explode(',',$data_genres);
$youtube_api_key  = fw_get_db_ext_settings_option( 'ht-movie', 'youtube-api-key', NULL );
$trailer_id       = uniqid('trailer-');
/*set columns*/

$query = null;   // re-sets query
$temp = $query;  // re-sets query

switch ($data) {
	case 'collections':
		$args = array(
			'post_type' => 'ht_movie',
			'showposts' => $count,
			'orderby' => $order_by,
			'order' => $sort_order,
					'tax_query' => array(
					array(
							'taxonomy' => 'mv_collection',
							'field' => 'slug',
							'terms'    => $data_collections,
					),
			),
		);
		break;
	case 'genres':
		$args = array(
			'post_type' => 'ht_movie',
			'showposts' => $count,
			'orderby' => $order_by,
			'order' => $sort_order,
					'tax_query' => array(
					array(
						'taxonomy' => 'mv_genre',
						'field' => 'slug',
						'terms'    => $data_genres,
					),
			),
		);
		break;
	default:
		$args = array(
			'post_type' => 'ht_movie',
			'showposts' => $count,
			'orderby' => $order_by,
			'order' => $sort_order
		);
}

$query = new WP_Query( $args );

if ( ! $query ) {
	return;
}

if(empty($count)) {
	$count = -1;
}

if( $query->have_posts() ) :
?>
	<div id="<?php echo esc_attr( $trailer_id ); ?>" class="row movie-trailer-items">
		<div class="trailer-item">
			<div class="slider-for-2 video-ft">
				<?php
				while( $query->have_posts() ) :
					$query->the_post();

						$video = fw_get_db_post_option( get_the_ID(), 'video' );
						if( ! empty( $video ) ) :
							$lastVideoId = end($video);
							?>
							<div>
								<iframe class="item-video" src="https://www.youtube.com/embed/<?php echo esc_html( $lastVideoId ); ?>" data-src="https://www.youtube.com/embed/<?php echo esc_html( $lastVideoId ); ?>"></iframe>
							</div>
						<?php endif; ?>
				<?php endwhile;?>
			</div>
			<div class="slider-nav-2 thumb-ft">
				<?php
					while( $query->have_posts() ) :
					$query->the_post();

					$video = fw_get_db_post_option( get_the_ID(), 'video' );
					if( ! empty( $video ) ) :
						$lastVideoId = end( $video );
						$thumbURL = 'http://img.youtube.com/vi/' . $lastVideoId . '/0.jpg';

						$url = "https://www.googleapis.com/youtube/v3/videos?part=id%2Csnippet&id=" . $lastVideoId . "&key=" . $youtube_api_key . "&fields=items(id,snippet(title),statistics)&part=snippet,statistics";

						$ch = curl_init( $url );
						curl_setopt( $ch, CURLOPT_URL, $url );
						curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
						curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 4 );
						$result = curl_exec( $ch );
						$results = json_decode( $result );

						if(empty($results->error)) :

								curl_close($ch);

								foreach($results as $result){

									if ( $result[0] && $result[0]->snippet ) {
										$title =  $result[0]->snippet->title;
									} else {
										echo esc_html__( 'No Title', 'blockter'  );

									}
								}
							?>

							<div class="item">
									<div class="vd-it">
											<a class="fancybox-media hvr-grow" href="https://www.youtube.com/watch?v=<?php echo esc_attr($lastVideoId); ?>"><?php  echo '<img src="'.esc_url($thumbURL).'" alt="trailer-img"/>'; ?></a>
											<span class="vd-title">
													<?php echo esc_html($title); ?>
											</span>
									</div>
							</div>
						<?php endif; ?>
					<?php endif; ?>
			<?php endwhile;?>
			</div>
		</div>
	</div>
<?php endif; ?>