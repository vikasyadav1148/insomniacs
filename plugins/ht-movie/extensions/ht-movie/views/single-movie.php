<?php
/**
 * The template for displaying single Movie
 */

global $current_user;
wp_get_current_user();

get_header();
?>
<?php if(have_posts()) : ?>
<?php while(have_posts()) : the_post(); ?>
<?php
		/**
		 * Movie metaboxes
		 */
		//General
        
		// $xyz = fetch_movie_provider_details($post->ID, 'movie_id');
		// echo '<pre>';print_r($xyz);


		

		$youtube_api_key = fw_get_db_ext_settings_option( 'ht-movie', 'youtube-api-key', NULL );
		$blockter_rate = get_post_meta( $post->ID, 'blockter_rating', true );
		$tagline = fw_get_db_post_option($post->ID, 'tagline');
		$overview = fw_get_db_post_option($post->ID, 'overview');
		$release_date = fw_get_db_post_option($post->ID, 'release_date');
		$runtime = fw_get_db_post_option($post->ID, 'runtime');
		$production = fw_get_db_post_option($post->ID, 'production');
		$country = fw_get_db_post_option($post->ID, 'country');
		$languages = fw_get_db_post_option($post->ID, 'languages');
		$directors = fw_get_db_post_option($post->ID, 'directors');
		$writers = fw_get_db_post_option($post->ID, 'writers');

		//Media
		$poster = get_the_post_thumbnail();
		$banner = fw_get_db_post_option($post->ID, 'banner');
		$gallery = fw_get_db_post_option($post->ID, 'gallery');
		$video = fw_get_db_post_option($post->ID, 'video');
		$hosted_videos = fw_get_db_post_option($post->ID, 'hosted_videos');
		$iframe_videos = fw_get_db_post_option($post->ID, 'iframe_videos');

		//Button
		$button_1_text = fw_get_db_post_option($post->ID, 'button_1_text');
		$button_1_url = fw_get_db_post_option($post->ID, 'button_1_url');
		$button_2_text = fw_get_db_post_option($post->ID, 'button_2_text');
		$button_2_url = fw_get_db_post_option($post->ID, 'button_2_url');
		$actor_lists = wp_get_object_terms(
			$post->ID,
			'mv_actor',
			array(
				'orderby' => 'term_id',
				'order' => 'ASC',
			)
		);
		$genre_lists = get_the_terms( $post->ID, 'mv_genre');
		$permalink  = urlencode( get_the_permalink() );
		$title      = urlencode( get_the_title() );
		$feedback = fw()->extensions->get( 'feedback' );
        $keyword_lists=get_the_terms( $post->ID, 'mv_keyword');

		$template_style = fw_get_db_ext_settings_option( 'ht-movie', 'template-style', 'style-1' );
		$vc_overview = fw_get_db_ext_settings_option( 'ht-movie', 'vc_overview');
		
		if($banner != ''){
			$attachment_id = $banner['attachment_id'];
			$image_url = wp_get_attachment_url($attachment_id);
			?>
<div class="movie-banner" style="background-image: url('<?php echo esc_url($image_url); ?>');">
</div>
<?php
		}else{
			$banner_img = get_template_directory_uri().'/images/banner-bg.jpg';
			?>
<div class="movie-banner" style="background-image: url('<?php echo esc_url($banner_img); ?>');">
</div>

<?php
		}
	?>

<div class="movie_single">
    <div class="container">
        <div class="movie-single">
            <div class="row">
                <div class="col-md-4">
                    <div class="movie-poster sticky-sb-movie">
                        <?php echo get_the_post_thumbnail( get_the_ID(), 'blockter-poster-movie-single', array( 'loading' => 'eager', 'data-no-lazy' => '1', 'fetchpriority' => 'high' ) ); ?>
                        <div class="movie-btns">
                            <?php if($hosted_videos == null || empty($hosted_videos) ): // If hosted video doesn't exist ?>
                            <?php 
										// If frame video exist
										if( !empty($iframe_videos) && $iframe_videos != null )  :
											echo ht_movie_iframe_movie_trailer($iframe_videos); //echo hosted video 
										elseif(!empty($video)) : //If youtube video exist ?>
                            <div class="btn-transform transform-vertical red">
                                <?php
												$lastVideoId = end($video);
											?>
                                <div><a href="#" class="item item-1 redbtn"> <i
                                            class="ion-play"></i><?php echo esc_html__("Watch Trailer", 'blockter'); ?></a>
                                </div>
                                <div><a href="https://www.youtube.com/watch?v=<?php echo esc_attr($lastVideoId); ?>"
                                        class="item item-2 redbtn fancybox-media hvr-grow"><i class="ion-play"></i></a>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php elseif( !empty($hosted_videos)) : echo ht_movie_hosted_movie_trailer($hosted_videos); //echo hosted video ?>
                            <?php endif;?>

                            <?php if(!empty($button_1_text)): ?>
                            <div class="btn-transform transform-vertical">
                                <div><a href="<?php echo esc_url($button_1_url); ?>" class="item item-1 yellowbtn"> <i
                                            class="ion-card"></i> <?php esc_html_e($button_1_text); ?></a></div>
                                <div><a href="<?php echo esc_url($button_1_url); ?>" class="item item-2 yellowbtn"><i
                                            class="ion-card"></i></a></div>
                            </div>
                            <?php endif; ?>
                            <?php if(!empty($button_2_text)): ?>
                            <div class="btn-transform transform-vertical btn-ticket-2" style="margin-top: 15px; ">
                                <div><a href="<?php echo esc_url($button_2_url); ?>" class="item item-1 yellowbtn"> <i
                                            class="ion-card"></i> <?php esc_html_e($button_2_text); ?></a></div>
                                <div><a href="<?php echo esc_url($button_2_url); ?>" class="item item-2 yellowbtn"><i
                                            class="ion-card"></i></a></div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="movie-single-content main-content">
                        <h1 class="mv-title"><?php single_post_title(); ?></h1>
                      
                        <?php
                      //  $tmdbId=get_post_meta($post->ID,'_tmdb_id',true);
                       
                         echo  do_shortcode('[ht_movie_providers id="'.$post->ID.'" type="movie"]');
                         ?>
                        <div class="share-buttons">
                            <div class="favorite-btn">
                                <?php if ( is_user_logged_in() ) : ?>
                                <?php
                                            $meta_key = 'favourite_mv_id';
                                            $current_fav_mv_id = get_user_meta(  $current_user->ID, $meta_key );
                                            $new_fav_mv_id = get_the_ID();
                                            if( in_array($new_fav_mv_id,$current_fav_mv_id) ) {
                                                $fav_text = 'Favourited';
                                                $fav_class = 'favourited';
                                            } else {
                                                $fav_text = 'Favourite';
                                                $fav_class = '';
                                            }
                                        ?>
                                <button data-user="<?php echo $current_user->ID; ?>" data-post="<?php the_ID(); ?>" class="<?php echo esc_attr($fav_class); ?>">
                                    <i class="ion-heart icon-btn"></i>
                                    <span class="favourite-text"><?php echo esc_html($fav_text); ?></span>
                                </button>
                                <?php else : ?>
                                <button type="button" class="ins-login-required" data-login-url="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>">
                                    <i class="ion-heart icon-btn"></i>
                                    <span class="favourite-text"><?php echo esc_html__( 'Favourite', 'blockter' ); ?></span>
                                </button>
                                <?php endif; ?>
                            </div>
                            <div class="ins-add-list-wrap add-to-list-btn">
                                <?php if ( is_user_logged_in() ) : ?>
                                <button type="button" id="ins-open-add-list" class="ins-inline-action ins-action-tile" onclick="return window.insWatchlistsOpenAddList ? window.insWatchlistsOpenAddList(event) : (event.preventDefault(), event.stopPropagation(), event.stopImmediatePropagation && event.stopImmediatePropagation(), (function(m){ if(m){ m.style.display = window.getComputedStyle(m).display === 'none' ? 'block' : 'none'; } })(document.getElementById('ins-add-to-list-modal')), false);">
                                    <i class="icon-btn ion-plus-round"></i>
                                    <span class="favourite-text">Add to List</span>
                                </button>
                                <div id="ins-add-to-list-modal" class="ins-add-to-list-modal" style="display:none;">
                                    <button type="button" class="ins-modal-close" aria-label="Close list chooser" onclick="event.preventDefault(); event.stopPropagation(); this.closest('.ins-add-to-list-modal').style.display='none'; return false;">&times;</button>
                                    <div class="ins-modal-title">Add to your watchlist</div>
                                    <select id="ins-list-select"><option value="">Select list</option></select>
                                    <div class="ins-add-actions"><button type="button" class="ins-btn" id="ins-add-current-item">Add</button></div>
                                    <div id="ins-inline-create-form" class="ins-inline-create-form" style="display:none;">
                                        <input type="text" id="ins-inline-list-name" placeholder="New list name" />
                                        <button type="button" class="ins-btn" id="ins-inline-create-submit">Save</button>
                                    </div>
                                </div>
                                <?php else : ?>
                                <a class="ins-inline-action ins-action-tile ins-login-required" href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" data-login-url="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>">
                                    <i class="icon-btn ion-plus-round"></i>
                                    <span class="favourite-text">Add to List</span>
                                </a>
                                <?php endif; ?>
                            </div>
                            <div class="social-share">
                                <span
                                    class="icon-btn ion-android-share-alt"></span><span><?php echo esc_html__("share", 'blockter'); ?></span>
                                <div class="social-links">
                                    <span class="fb-share">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr($permalink)?>"
                                            target="_blank" rel="noopener noreferrer" class="icon ion-social-facebook"></a>
                                    </span>
                                    <span class="tw-share">
                                        <a href="http://twitter.com/home?status=<?php echo esc_attr($title) ?>%20<?php echo esc_attr($permalink)?>"
                                            target="_blank" rel="noopener noreferrer" class="icon ion-social-twitter"></a>
                                    </span>
                                    <span class="linkedin-share">
                                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo esc_attr($permalink)?>"
                                            target="_blank" rel="noopener noreferrer" class="icon ion-social-linkedin"></a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php if($feedback != null): ?>
                        <?php if(comments_open() && get_comments_number()): ?>
                        <style>
                        .movie_single .movie-single-content .streaming-block {
                            border-bottom: 1px solid rgba(255,255,255,.08);
                        }
                        .movie_single .movie-single-content .rate-average .left-it .inner-cmt-infor .rv {
                            display: block;
                            color: #dd003f;
                            font-weight: 700;
                            margin-top: 2px;
                        }
                        @media (max-width: 767px) {
                            .movie_single .movie-single-content .streaming-group {
                                flex-wrap: nowrap !important;
                                gap: 6px !important;
                            }
                            .movie_single .movie-single-content .streaming-group img {
                                width: 34px !important;
                                height: 34px !important;
                            }
                        }
                        </style>
                        <div class="rate-average">
                            <div class="left-it">
                                <span class="fa fa-star icon"></span>
                                <div class="inner-cmt-infor">
                                    <?php $average = fw_ext_feedback_stars_get_post_rating(); ?>
                                    <div class="rate-num">
                                        <span><?php esc_html_e(number_format($average['average']),0); ?></span>
                                        <span class="sm-text"><?php echo esc_html__("/", 'blockter'); ?></span>
                                        <span class="sm-text"><?php
														$star = fw_ext_feedback_stars_get_post_detailed_rating($post->ID);
														echo count($star['stars']);?>
                                        </span>
                                    </div>
                                    <span class="rv"><?php
                                        $review_count = (int) get_comments_number();
                                        echo esc_html( sprintf( _n( '%s Review', '%s Reviews', $review_count, 'blockter' ), number_format_i18n( $review_count ) ) );
                                    ?></span>
                                </div>
                            </div>
                            <div class="right-it">
                                <span
                                    class="rate-title"><?php echo esc_html__("Rate this movie: ", 'blockter'); ?></span>
                                <span class="rate-stars"><?php echo fw_ext_feedback($post->ID); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>
                        <div class="movie-tab">
                            <div class="tabs">
                                <nav class="main-nav">
                                    <!-- tab links -->
                                    <ul class="tab-links tabs-mv">
                                        <li class="active"><a
                                                href="#overview"><?php esc_html_e('Overview', 'blockter'); ?></a></li>
                                        <li><a href="#media"><?php esc_html_e('Media', 'blockter'); ?></a></li>
                                        <li><a href="#cast"><?php esc_html_e('Cast', 'blockter'); ?></a></li>
                                        <li><a
                                                href="#moviesrelated"><?php esc_html_e('Related Movies', 'blockter'); ?></a>
                                        </li>
                                        <li><a href="#collections"><?php esc_html_e('Collections', 'blockter'); ?></a></li>
                                        <li><a href="#reviews"><?php esc_html_e('Reviews', 'blockter'); ?></a></li>
                                    </ul>
                                </nav>
                                <div class="tab-content">
                                    <?php if ( $template_style == 'style-1' ) : ?>

                                    <div id="overview" class="tab active">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="overview-left-ct">
                                                    <h2><?php esc_html_e('Overview', 'blockter');; ?></h2>
                                                    <?php
																if($vc_overview == 'enable'){
																	the_content(); 
																}else{
																	echo do_shortcode($overview);
																}
															?>
                                                </div>
                                                <div class="overview-sb">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="overview-sb-it">
                                                                <?php if(!empty($release_date)): ?>
                                                                <h6><?php esc_html_e('Release Date: ', 'blockter'); ?>
                                                                </h6>
                                                                <span
                                                                    class="white-text"><?php echo esc_html($release_date); ?></span>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="overview-sb-it">
                                                                <?php if(!empty($runtime)): ?>
                                                                <h6><?php esc_html_e('Run Time: ', 'blockter'); ?></h6>
                                                                <span
                                                                    class="white-text"><?php echo esc_html($runtime); ?></span>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="overview-sb-it">
                                                                <?php if(!empty($tagline)): ?>
                                                                <h6><?php esc_html_e('Tagline:', 'blockter'); ?></h6>
                                                                <span
                                                                    class="white-text"><?php echo esc_html($tagline); ?></span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="overview-sb-it">
                                                                <?php if(!empty($directors)): ?>
                                                                <h6><?php esc_html_e('Director: ', 'blockter'); ?></h6>
                                                                <span
                                                                    class="white-text"><?php echo esc_html($directors); ?></span>
                                                                <?php endif;?>
                                                            </div>
                                                            <div class="overview-sb-it">
                                                                <?php if(!empty($writers)): ?>
                                                                <h6><?php esc_html_e('Writer: ', 'blockter'); ?></h6>
                                                                <span
                                                                    class="white-text"><?php echo esc_html($writers); ?></span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="overview-sb-it">
                                                                <?php if(!empty($actor_lists)): ?>
                                                                <h6><?php esc_html_e('Stars: ', 'blockter'); ?></h6>
                                                                <?php foreach($actor_lists as $item): ?>
                                                                <?php $ac_name = $item->name;  $ac_url = get_term_link($item); ?>
                                                                <a
                                                                    href="<?php echo esc_url($ac_url);?>"><?php esc_html_e($ac_name);?></a>
                                                                <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="overview-sb-it">
                                                                <?php if(!empty($genre_lists)): ?>
                                                                <h6><?php esc_html_e('Genres: ', 'blockter'); ?></h6>
                                                                <?php foreach($genre_lists as $item): ?>
                                                                <?php $genre_name = $item->name;  $genre_url = get_term_link($item); ?>
                                                                <a
                                                                    href="<?php echo esc_url($genre_url);?>"><?php esc_html_e($genre_name);?></a>
                                                                <?php endforeach; ?>
                                                                <?php endif; ?>
                                                                
                                                            </div>
                                                            <!-- <div class="overview-sb-it">
                                                                <?php echo do_shortcode('[movie_details id="'.get_the_ID().'" slug="'.get_the_title().'"]'); ?>
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="cast-it">
                                                <div class="ov-it-title">
                                                    <h2><?php esc_html_e('Review', 'blockter'); ?></h2>
                                                    <a href="#reviews" class="media-tab"><?php esc_html_e('View all reviews', 'blockter'); ?></a>
                                                </div>

                                                <div class="consult-comment-related flw">
                                                    <?php
                                                    $args = array (
                                                        'post_id' => $post->ID,
                                                        'status' => 'approve',
                                                        'number' => '1'
                                                    );
                                                    $comments = get_comments( $args );
                                                    if ( !empty( $comments ) ) :
                                                    echo '<ol class="comment-list">';
                                                    foreach( $comments as $comment ) :
                                                    ?>
                                                    <li id="comment-<?php echo esc_attr( $comment->comment_ID ); ?>"
                                                        class="comment">
                                                        <article
                                                            id="div-comment-<?php echo esc_attr( $comment->comment_ID ); ?>"
                                                            class="comment-body">
                                                            <footer class="comment-meta">
                                                                <div class="flex-it">
                                                                    <div class="flex-it-inner">

                                                                        <div class="comment-author vcard">
                                                                            <?php echo get_avatar( $comment, 45 ) ?>
                                                                        </div><!-- .comment-author -->

                                                                        <div class="comment-content">
                                                                            <div class="flex-it-ava">
                                                                                <b class="fn"><?php echo esc_html( $comment->comment_author ); ?></b>
                                                                                <div class="comment-metadata">
                                                                                    <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
                                                                                        <time datetime="<?php echo get_comment_time( 'c', $comment->comment_ID ); ?>">
                                                                                            <?php
                                                                                            printf(
                                                                                                _x( '- %1$s at %2$s', '1: date, 2: time', 'blockter'),
                                                                                                get_comment_date( "d F Y", $comment->comment_ID ),
                                                                                                get_comment_time( "g:i a", $comment->comment_ID )
                                                                                            );
                                                                                            ?>
                                                                                        </time>
                                                                                    </a>
                                                                                </div><!-- .comment-metadata -->
                                                                            </div><!-- .flex-it-ava -->
                                                                            <?php if ( $feedback != null ) : ?>
                                                                            <span class="rate-stars"><?php echo fw_ext_feedback( $post->ID ); ?></span>
                                                                            <?php endif; ?>
                                                                        </div><!-- .comment-content -->

                                                                    </div><!-- .flex-it-inner -->
                                                                </div><!-- .flex-it -->

                                                                <p><?php echo esc_html( $comment->comment_content ); ?></p>
                                                            </footer><!-- .comment-meta -->
                                                        </article><!-- .comment-body -->
                                                    </li>
                                                    <?php
                                                    endforeach;
                                                    echo '</ol>';
                                                    endif;
                                                    ?>
                                                </div>
                                            </div><!-- .cast-it -->
                                        </div>
                                    </div>

                                    </div><!-- #overview -->

                                    <?php else : ?>

                                    <div id="overview" class="tab active">
                                        <div class="row">
                                            <div class="col-md-8">

                                                <div class="overview-left-ct">
                                                    <h2><?php esc_html_e('Overview', 'blockter');; ?></h2>
                                                    <p class="overview-ct"><?php 
								if($vc_overview == 'enable'){
									the_content(); 
								}else{
									echo do_shortcode($overview);
								}
							?></p>
                                                </div><!-- .overview-left-ct -->

                                                <div class="media-it">
                                                    <?php if(!empty($video)): ?>
                                                    <div class="ov-it-title">
                                                        <h2><?php esc_html_e('Media', 'blockter'); ?></h2>
                                                        <a href="#media" class="media-tab">View all media</a>
                                                    </div>
                                                    <?php endif;?>
                                                    <div class="videos">
                                                        <?php
									if(!empty($iframe_videos) && $iframe_videos != null ){
										echo ht_movie_iframe_media($iframe_videos, true);
									}elseif(!empty($hosted_videos) && $hosted_videos != null){
										echo ht_movie_hosted_media($hosted_videos, true);
									}else{
										$videoId =is_array($video) && !empty(($video)) ? end($video) : $video;
                                       // $videoId = $video[0];
										// Generate youtube thumbnail url
										$thumbURL = 'https://img.youtube.com/vi/'.$videoId.'/0.jpg';

										// Display thumbnail image
										$url = "https://www.googleapis.com/youtube/v3/videos?part=id%2Csnippet&id=".$videoId."&key=" . $youtube_api_key . "&fields=items(id,snippet(title),statistics)&part=snippet,statistics";

										$ch = curl_init();
										curl_setopt($ch,CURLOPT_URL,$url);
										curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
										curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);
										$result = curl_exec($ch);
										$results = json_decode($result);
										if(empty($results->error)) :

											 curl_close($ch);
											 foreach($results as $result){

												if ( $result[0] && $result[0]->snippet ) {
													$title =  $result[0]->snippet->title;
												} else {
													$title = esc_html__( 'No Title', 'blockter'  );

												}
											}
										endif;
										echo ht_movie_youtube_media($videoId, $thumbURL, $title);
									}
                                	
                                ?>

                                                    </div>
                                                </div><!-- .media-it -->

                                                <div class="cast-it">
                                                    <?php if(!empty($actor_lists)): ?>
                                                    <div class="ov-it-title">
                                                        <h2><?php esc_html_e('Cast', 'blockter'); ?></h2>
                                                        <a href="#cast"
                                                            class="media-tab"><?php esc_html_e('View all cast', 'blockter'); ?></a>
                                                    </div>
                                                    <?php endif; ?>
                                                    <?php if($actor_lists == false):?>
                                                    <?php else: ?>
                                                    <div class="actor-list-items flex-it">
                                                        <div class="actor-list-left">
                                                            <?php
	                                  $actor_lists_1 = array_slice($actor_lists, 0, 4);
	                                  if(!empty($actor_lists_1)){
	                                    foreach( $actor_lists_1 as $term ){
	                                      $term_id = $term->term_id;
	                                      $act_name = $term->name;
	                                      $term_url = get_term_link($term);
	                                      ?>
                                                            <div class="ac-it">
                                                                <div class="act-img">
                                                                    <?php $avatar_attr = fw_get_db_term_option($term_id, 'mv_actor');?>
                                                                    <?php
	                                          	if ( array_key_exists( 'avatar_url', $avatar_attr ) && ($avatar_attr['avatar_url'] != '') ) :
	                                          		$actor_thumbnail_url = $avatar_attr['avatar_url'];
	                                          ?>
                                                                    <a href="<?php echo esc_url($term_url);?>">
                                                                        <img src="<?php echo esc_url( $actor_thumbnail_url ); ?>"
                                                                            alt="<?php echo esc_attr__( 'Actor Avatar', 'blockter' ); ?>">
                                                                    </a>
                                                                    <?php elseif ( array_key_exists( 'avatar', $avatar_attr ) && ($avatar_attr['avatar'] != '') ) : ?>
                                                                    <?php $att_id = $avatar_attr['avatar']['attachment_id'];?>
                                                                    <a href="<?php echo esc_url($term_url);?>">
                                                                        <?php echo wp_get_attachment_image($att_id, array(40, 40));?>
                                                                    </a>
                                                                    <?php else: ?>
                                                                    <a class="actor-img"
                                                                        href="<?php echo esc_url($term_url);?>">
                                                                        <div class="no-image"></div>
                                                                    </a>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <a class="actor-name"
                                                                    href="<?php echo esc_url($term_url);?>"><?php esc_html_e($term->name); ?></a>
                                                            </div>
                                                            <?php
	                                    }
	                                  }
	                                ?>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                </div><!-- .cast-it -->


                                            </div><!-- .col -->
                                            <div class="col-md-4">

                                                <div class="overview-sb-it">
                                                    <?php if(!empty($directors)): ?>
                                                    <h6><?php esc_html_e('Director: ', 'blockter'); ?></h6>
                                                    <span class="white-text"><?php echo esc_html($directors); ?></span>
                                                    <?php endif;?>
                                                </div>

                                                <div class="overview-sb-it">
                                                    <?php if(!empty($writers)): ?>
                                                    <h6><?php esc_html_e('Writer: ', 'blockter'); ?></h6>
                                                    <span class="white-text"><?php echo esc_html($writers); ?></span>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="overview-sb-it">
                                                    <?php if(!empty($release_date)): ?>
                                                    <h6><?php esc_html_e('Release Date: ', 'blockter'); ?></h6>
                                                    <span
                                                        class="white-text"><?php echo esc_html($release_date); ?></span>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="overview-sb-it">
                                                    <?php if(!empty($runtime)): ?>
                                                    <h6><?php esc_html_e('Run Time: ', 'blockter'); ?></h6>
                                                    <span class="white-text"><?php echo esc_html($runtime); ?></span>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="overview-sb-it">
                                                    <?php if(!empty($tagline)): ?>
                                                    <h6><?php esc_html_e('Tagline:', 'blockter'); ?></h6>
                                                    <span class="white-text"><?php echo esc_html($tagline); ?></span>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="overview-sb-it">
                                                    <?php if(!empty($genre_lists)): ?>
                                                    <h6><?php esc_html_e('Genres: ', 'blockter'); ?></h6>
                                                    <?php foreach($genre_lists as $item): ?>
                                                    <?php $genre_name = $item->name;  $genre_url = get_term_link($item); ?>
                                                    <a
                                                        href="<?php echo esc_url($genre_url);?>"><?php esc_html_e($genre_name);?></a>
                                                    <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                                 <div class="overview-sb-it">
                                                          <?php if(!empty($keyword_lists)): ?>
                                                    <h6><?php esc_html_e('Keywords: ', 'blockter'); ?></h6>
                                                    <?php foreach($keyword_lists as $item): ?>
                                                    <?php $keywords_name = $item->name;  $keyword_url = get_term_link($item); ?>
                                                    <a
                                                        href="<?php echo esc_url($keyword_url);?>"><?php esc_html_e($keywords_name);?></a>
                                                    <?php endforeach; ?>
                                                    <?php endif; ?>
                                                 </div>   

                                                <div class="overview-sb-it">
                                                    <h6>Financials and Popularity</h6>
                                                    <?php echo do_shortcode('[movie_details id="'.get_the_ID().'" slug="'.get_the_title().'"]'); ?>
                                                </div>
                                            </div><!-- .col -->
                                        </div><!-- .row -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="cast-it">
                                                <div class="ov-it-title">
                                                    <h2><?php esc_html_e('Review', 'blockter'); ?></h2>
                                                    <a href="#reviews" class="media-tab"><?php esc_html_e('View all reviews', 'blockter'); ?></a>
                                                </div>

                                                <div class="consult-comment-related flw">
                                                    <?php
                                                    $args = array (
                                                        'post_id' => $post->ID,
                                                        'status' => 'approve',
                                                        'number' => '1'
                                                    );
                                                    $comments = get_comments( $args );
                                                    if ( !empty( $comments ) ) :
                                                    echo '<ol class="comment-list">';
                                                    foreach( $comments as $comment ) :
                                                    ?>
                                                    <li id="comment-<?php echo esc_attr( $comment->comment_ID ); ?>"
                                                        class="comment">
                                                        <article
                                                            id="div-comment-<?php echo esc_attr( $comment->comment_ID ); ?>"
                                                            class="comment-body">
                                                            <footer class="comment-meta">
                                                                <div class="flex-it">
                                                                    <div class="flex-it-inner">

                                                                        <div class="comment-author vcard">
                                                                            <?php echo get_avatar( $comment, 45 ) ?>
                                                                        </div><!-- .comment-author -->

                                                                        <div class="comment-content">
                                                                            <div class="flex-it-ava">
                                                                                <b class="fn"><?php echo esc_html( $comment->comment_author ); ?></b>
                                                                                <div class="comment-metadata">
                                                                                    <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
                                                                                        <time datetime="<?php echo get_comment_time( 'c', $comment->comment_ID ); ?>">
                                                                                            <?php
                                                                                            printf(
                                                                                                _x( '- %1$s at %2$s', '1: date, 2: time', 'blockter'),
                                                                                                get_comment_date( "d F Y", $comment->comment_ID ),
                                                                                                get_comment_time( "g:i a", $comment->comment_ID )
                                                                                            );
                                                                                            ?>
                                                                                        </time>
                                                                                    </a>
                                                                                </div><!-- .comment-metadata -->
                                                                            </div><!-- .flex-it-ava -->
                                                                            <?php if ( $feedback != null ) : ?>
                                                                            <span class="rate-stars"><?php echo fw_ext_feedback( $post->ID ); ?></span>
                                                                            <?php endif; ?>
                                                                        </div><!-- .comment-content -->

                                                                    </div><!-- .flex-it-inner -->
                                                                </div><!-- .flex-it -->

                                                                <p><?php echo esc_html( $comment->comment_content ); ?></p>
                                                            </footer><!-- .comment-meta -->
                                                        </article><!-- .comment-body -->
                                                    </li>
                                                    <?php
                                                    endforeach;
                                                    echo '</ol>';
                                                    endif;
                                                    ?>
                                                </div>
                                            </div><!-- .cast-it -->
                                        </div>
                                    </div>

                                    </div><!-- #overview -->

                                    <?php endif; ?>
                                    <div id="media" class="tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="media-it">
                                                    <?php if(!empty($video)): ?>
                                                    <div class="ov-it-title">
                                                        <h2><?php esc_html_e('Videos', 'blockter'); ?></h2>
                                                    </div>
                                                    <?php endif;?>
                                                    <div class="videos">
                                                         <?php
																		if ( ! empty($video) ) :
																			$videoId =end($video);
                                                                           
																		  // Generate youtube thumbnail url
																		  $thumbURL = 'https://img.youtube.com/vi/'.$videoId.'/0.jpg';

																		  // Display thumbnail image
																		  $url = "https://www.googleapis.com/youtube/v3/videos?part=id%2Csnippet&id=".$videoId."&key=" . $youtube_api_key . "&fields=items(id,snippet(title),statistics)&part=snippet,statistics";

																		  $ch = curl_init();
																		  curl_setopt($ch,CURLOPT_URL,$url);
																		  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
																		  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);
																		  $result = curl_exec($ch);

																		$results = json_decode($result);
																		if(empty($results->error)) :

																			 curl_close($ch);
																			 foreach($results as $result){

																				if ( $result[0] && $result[0]->snippet ) {
																					$title =  $result[0]->snippet->title;
																				} else {
																					$title = esc_html__( 'No Title', 'blockter'  );

																				}
																			}
																		endif;
																		?>
                                                        <div class="vd-it">
                                                            <a class="fancybox-media hvr-grow"
                                                                href="https://www.youtube.com/watch?v=<?php echo esc_attr($videoId); ?>"><?php  echo '<img src="'.$thumbURL.'"/>'; ?></a>
                                                            <span class="vd-title">
                                                                <?php esc_html_e($title); ?>
                                                            </span>
                                                        </div>
                                                        <?php
																		endif;
																	  ?>
                                                    </div>
                                                </div>
                                                <div class="media-it">
                                                    <?php if(!empty($gallery)): ?>
                                                    <div class="ov-it-title">
                                                        <h2><?php esc_html_e('Photos', 'blockter'); ?></h2>
                                                    </div>
                                                    <?php endif; ?>
                                                    <div class="gallery">
                                                        <?php
																//display all images
																if(!empty($gallery)){
																	foreach ($gallery as $item) {
																		$attachment_id = $item['attachment_id'];
																		$url = $item['url'];
																		?>
                                                        <a href="<?php echo esc_url($url); ?>" class="img-lightbox"
                                                            data-fancybox-group="gallery"><?php echo wp_get_attachment_image($attachment_id, array(150, 150)); ?></a>
                                                        <?php
																	}
																}
															?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- #media -->

                                    <div id="cast" class="tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="cast-it">
                                                    <?php if(!empty($actor_lists)): ?>
                                                    <div class="ov-it-title">
                                                        <h2><?php esc_html_e('Cast', 'blockter'); ?></h2>
                                                    </div>
                                                    <?php endif; ?>
                                                    <?php if($actor_lists == false):?>
                                                    <?php else: ?>
                                                    <div class="actor-list-items flex-it">
                                                        <div class="actor-list-left">
                                                            <?php
																	$actor_lists_1 = array_slice($actor_lists, 0, 4);
																	if(!empty($actor_lists_1)){
																		foreach( $actor_lists_1 as $term ){
																			$term_id = $term->term_id;
																			$act_name = $term->name;
																			$term_url = get_term_link($term);
																			?>
                                                            <div class="ac-it">
                                                                <div class="act-img">
                                                                    <?php $avatar_attr = fw_get_db_term_option($term_id, 'mv_actor');?>
                                                                    <?php
	                                          	if ( array_key_exists( 'avatar_url', $avatar_attr ) && ($avatar_attr['avatar_url'] != '') ) :
	                                          		$actor_thumbnail_url = $avatar_attr['avatar_url'];
	                                          ?>
                                                                    <a href="<?php echo esc_url($term_url);?>">
                                                                        <img src="<?php echo esc_url( $actor_thumbnail_url ); ?>"
                                                                            alt="<?php echo esc_attr__( 'Actor Avatar', 'blockter' ); ?>">
                                                                    </a>
                                                                    <?php elseif ( array_key_exists( 'avatar', $avatar_attr ) && ($avatar_attr['avatar'] != '') ) : ?>
                                                                    <?php $att_id = $avatar_attr['avatar']['attachment_id'];?>
                                                                    <a href="<?php echo esc_url($term_url);?>">
                                                                        <?php echo wp_get_attachment_image($att_id, array(40, 40));?>
                                                                    </a>
                                                                    <?php else: ?>
                                                                    <a class="actor-img"
                                                                        href="<?php echo esc_url($term_url);?>">
                                                                        <div class="no-image"></div>
                                                                    </a>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <a class="actor-name"
                                                                    href="<?php echo esc_url($term_url);?>"><?php esc_html_e($term->name); ?></a>
                                                            </div>
                                                            <?php
																		}
																	}
																?>
                                                        </div>
                                                        <div class="actor-list-right">
                                                            <?php
																	$actor_lists_2 = array_slice($actor_lists, 4, 8);
																	if(!empty($actor_lists_2)){
																		foreach( $actor_lists_2 as $term ){
																			$term_id = $term->term_id;
																			$act_name = $term->name;
																			$term_url = get_term_link($term);
																			?>
                                                            <div class="ac-it">
                                                                <div class="act-img">
                                                                    <?php $avatar_attr = fw_get_db_term_option($term_id, 'mv_actor');?>
                                                                    <?php
	                                          	if ( array_key_exists( 'avatar_url', $avatar_attr ) && ($avatar_attr['avatar_url'] != '') ) :
	                                          		$actor_thumbnail_url = $avatar_attr['avatar_url'];
	                                          ?>
                                                                    <a href="<?php echo esc_url($term_url);?>">
                                                                        <img src="<?php echo esc_url( $actor_thumbnail_url ); ?>"
                                                                            alt="<?php echo esc_attr__( 'Actor Avatar', 'blockter' ); ?>">
                                                                    </a>
                                                                    <?php elseif ( array_key_exists( 'avatar', $avatar_attr ) && ($avatar_attr['avatar'] != '') ) : ?>
                                                                    <?php $att_id = $avatar_attr['avatar']['attachment_id'];?>
                                                                    <a href="<?php echo esc_url($term_url);?>">
                                                                        <?php echo wp_get_attachment_image($att_id, array(40, 40));?>
                                                                    </a>
                                                                    <?php else: ?>
                                                                    <a class="actor-img"
                                                                        href="<?php echo esc_url($term_url);?>">
                                                                        <div class="no-image"></div>
                                                                    </a>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <a class="actor-name"
                                                                    href="<?php echo esc_url($term_url);?>"><?php esc_html_e($term->name); ?></a>
                                                            </div>
                                                            <?php
																		}
																	}
																?>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- #cast -->

                                    <div id="moviesrelated" class="tab">
                                        <div class="row">
                                            <?php
													/*get related movies by category id*/
													$custom_taxterms = wp_get_object_terms( $post->ID, 'mv_genre', array('fields' => 'ids') );
													$movie = new WP_Query(array(
														'post_type' => 'ht_movie',
														'posts_per_page' => 5,
														'paged' => $paged,
														'orderby' => 'asc',
														'tax_query'=> array(
															array(
																'taxonomy'=> 'mv_genre',
																'field' => 'id',
																'terms'=> $custom_taxterms
															)
														),
														'post__not_in' => array ($post->ID),
													));
												?>
                                            <div class="col-md-12">
                                                <?php global $post;
													if( $movie->have_posts() ): ?>
                                                <div class="sub-mv-title">
                                                    <h2><?php echo esc_html__("Related Movies To", 'blockter'); ?></h2>
                                                    <h4><?php single_post_title();?></h4>
                                                </div>
                                                <?php endif; ?>
                                                <div class="related-movie-items">
                                                    <!-- movie list items -->
                                                    <div class="theme-movie-items row">
                                                        <?php
															/*query*/
															global $post;
															if( $movie->have_posts() ):
																while($movie->have_posts()): $movie->the_post();
																$thumbnail_id = get_post_thumbnail_id($post->ID);
																$directors = fw_get_db_post_option($post->ID, 'directors');
																$actor_lists = get_the_terms( $post->ID, 'mv_actor' );
																$tagline = fw_get_db_post_option($post->ID, 'tagline');
																$overview = fw_get_db_post_option($post->ID, 'overview');
																$release_date = fw_get_db_post_option($post->ID, 'release_date');
																$runtime = fw_get_db_post_option($post->ID, 'runtime');
															?>
                                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                                            <div class="movie-item">
                                                                <div class="movie-thumbnail">
                                                                    <?php if(!empty($thumbnail_id)): ?>
                                                                    <a href="<?php the_permalink(); ?>">
                                                                        <?php echo wp_get_attachment_image($thumbnail_id, 'blockter-poster-movie-item');?>
                                                                    </a>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="movie-content">
                                                                    <h6 class="mv-title"><a itemprop="url"
                                                                            href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                                    </h6>
                                                                    <?php if($feedback != null): ?>
                                                                    <?php if(comments_open() && get_comments_number()): ?>
                                                                    <div class="rate-average">
                                                                        <div class="left-it">
                                                                            <span class="fa fa-star icon"></span>
                                                                            <div class="inner-cmt-infor">
                                                                                <?php   $average = fw_ext_feedback_stars_get_post_rating();?>
                                                                                <div class="rate-num">
                                                                                    <span><?php esc_html_e($average['average']); ?></span>
                                                                                    <span
                                                                                        class="sm-text"><?php echo esc_html__("/", 'blockter'); ?></span>
                                                                                    <span class="sm-text"><?php
																									$star = fw_ext_feedback_stars_get_post_detailed_rating($post->ID);
																									echo count($star['stars']);?>
                                                                                    </span>
                                                                                </div>
                                                                                <span class="rv"><?php
                                                                                    $review_count = (int) get_comments_number();
                                                                                    echo esc_html( sprintf( _n( '%s Review', '%s Reviews', $review_count, 'blockter' ), number_format_i18n( $review_count ) ) );
                                                                                ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php endif;?>
                                                                    <?php endif; ?>
                                                                    <?php if(!empty($overview)): ?>
                                                                    <div class="mv-des">
                                                                        <?php echo ($overview);?>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                    <div class="flex-it movie-details">
                                                                        <?php if(!empty($runtime)): ?>
                                                                        <span><?php echo esc_html__("Run time: ", 'blockter'); ?><?php esc_html_e($runtime);?></span>
                                                                        <?php endif; ?>
                                                                        <?php if(!empty($tagline)): ?>
                                                                        <span><?php echo esc_html__("Tagline: ", 'blockter'); ?><?php esc_html_e($tagline);?></span>
                                                                        <?php endif; ?>
                                                                        <?php if(!empty($release_date)): ?>
                                                                        <span><?php echo esc_html__("Release: ", 'blockter'); ?><?php esc_html_e($release_date);?></span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <?php if(!empty($directors) && !is_array($directors)): ?>
                                                                    <p class="mv-directors">
                                                                        <span><?php echo esc_html__("Director: ", 'blockter') ?></span>
                                                                        <span
                                                                            class="white-text"><?php echo esc_html($directors); ?></span>
                                                                    </p>
                                                                    <?php endif; ?>
                                                                    <?php if(!empty($actor_lists)): ?>
                                                                    <p class="mv-stars">
                                                                        <span><?php esc_html_e('Stars: ', 'blockter'); ?></span>
                                                                        <?php foreach($actor_lists as $item): ?>
                                                                        <?php $ac_name = $item->name;  $ac_url = get_term_link($item); ?>
                                                                        <a
                                                                            href="<?php echo esc_url($ac_url);?>"><?php esc_html_e($ac_name);?></a>
                                                                        <?php endforeach; ?>
                                                                    </p>
                                                                    <?php endif; ?>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
																endwhile;
															endif;
															/*reset query*/
															wp_reset_postdata();
															?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- #moviesrelated -->

                                    <div id="collections" class="tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php
                                                    $collection_terms = get_the_terms( $post->ID, 'mv_collection' );
                                                    if ( $collection_terms && ! is_wp_error( $collection_terms ) ) :
                                                ?>
                                                <div class="sub-mv-title">
                                                    <h2><?php echo esc_html__( 'Collections', 'blockter' ); ?></h2>
                                                </div>
                                                    <?php foreach ( $collection_terms as $term ) :
                                                        $term_link = get_term_link( $term );
                                                        if ( is_wp_error( $term_link ) ) continue;
                                                    ?>
                                                    <h6><a href="<?php echo esc_url( $term_link ); ?>"><?php echo esc_html( $term->name ); ?></a></h6>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                <div class="sub-mv-title">
                                                    <h2><?php echo esc_html__( 'Collections', 'blockter' ); ?></h2>
                                                    <h4><?php single_post_title(); ?></h4>
                                                </div>
                                                <p><?php echo esc_html__( 'This movie is not part of any collection.', 'blockter' ); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div><!-- #collections -->

                                    <div id="reviews" class="tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="sub-mv-title">
                                                    <h2><?php echo esc_html__("Reviews for", 'blockter'); ?></h2>
                                                    <h4><?php single_post_title();?></h4>
                                                </div>
                                                <div class="consult-comment-related flw">
                                                    <?php if($feedback != null): ?>
                                                    <?php /*comment*/
																if ( comments_open() || get_comments_number() ) {
																	comments_template();
																} ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- #reviews -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

?>
<div class="clear-both"></div>
<?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>
