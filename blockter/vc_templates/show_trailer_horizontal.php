<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $inline_css, ' ' ) . $this->getExtraClass( $class ) . $this->getCSSAnimation( $css_animation );
$all_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
/*set columns*/
global $post;
$query = null;   // re-sets query
$temp  = $query;  // re-sets query
$args  = array(
	'post_type' => 'ht_show',
	'showposts' => $count,
);
$query = new WP_Query( $args );

if ( ! $query ) {
	return;
}

if( empty( $count ) ) {
	$count = -1;
}

$horizontal_trailer_id = uniqid( 'trailer-' );
$youtube_api_key       = fw_get_db_ext_settings_option( 'ht-movie', 'youtube-api-key', NULL );
?>
<?php if( $query->have_posts() ): ?>
	<div id="<?php echo esc_attr( $horizontal_trailer_id ); ?>" class="row movie-trailer-items js-horizontal-trailer">
			<div class="trailer-item-horizontal">
				<div class="slider-for video-ft">
					<?php
						while($query->have_posts()) :
							$query->the_post();
							$video       = fw_get_db_post_option(get_the_ID(), 'video');
							if ($video) {
								$lastVideoId = end($video);
								?>
								<div>
									<iframe class="item-video" src="https://www.youtube.com/embed/<?php echo esc_html($lastVideoId); ?>" data-src="https://www.youtube.com/embed/<?php echo esc_html($lastVideoId); ?>"></iframe>
								</div>
					<?php 
							}
						endwhile; 
					?>
				</div>
				<div class="slider-nav thumb-ft">
					<?php
						while( $query->have_posts() ) :
							$query->the_post();

							$video       = fw_get_db_post_option( get_the_ID(), 'video');
							if( ! empty( $video ) ) :
								$lastVideoId = end($video);
								// Generate youtube thumbnail url
								$thumbURL    = 'http://img.youtube.com/vi/' . $lastVideoId . '/0.jpg';
								$url         = "https://www.googleapis.com/youtube/v3/videos?part=id%2Csnippet&id=".$lastVideoId . "&key=" . $youtube_api_key . "&fields=items(id,snippet(title),statistics)&part=snippet,statistics";
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
