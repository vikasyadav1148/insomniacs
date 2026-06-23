<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

/**
 * Output a review in the HTML5 format.
 *
 * @var object $comment Comment to display.
 * @var int $depth Depth of comment.
 * @var array $args An array of arguments.
 * @var bool $has_children
 * @var int $stars_number
 * @var int $rate
 */
$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
?>
<<?php echo esc_html($tag); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $has_children ? 'parent' : '' ); ?>>
<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
	<footer class="comment-meta">
		<div class="flex-it">
			<div class="flex-it-inner">
				<div class="comment-author vcard">
					<?php if ( 0 != $args['avatar_size'] ) {
						echo get_avatar( $comment, $args['avatar_size'] );
					} ?>
				</div>
				<!-- .comment-author -->
				<div class="comment-content">
					<div class="flex-it-ava">
						<?php echo '<b class="fn">' . get_comment_author_link() . '</b>'?>
						<div class="comment-metadata">
							<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID, $args ) ); ?>">
								<time datetime="<?php comment_time( 'c' ); ?>">
									<?php printf( _x( '- %1$s at %2$s', '1: date, 2: time', 'blockter'), get_comment_date(), get_comment_time() ); ?>
								</time>
							</a>
						</div>
					</div>
					<!--Rating-->
					<?php if(!empty($rate)) : ?>
						<div class="wrap-rating listing">
							<div class="rating">
								<?php
								for ( $i = 1; $i <= $stars_number; $i ++ ) {
									$voted = ( $i <= round( $rate ) ) ? ' voted' : '';
									echo '<span class="fa fa-star' . esc_attr($voted) . '" data-vote="' . esc_attr($i) . '"></span>';
								}
								?>
							</div>
						</div>
					<?php endif; ?>
					<!--/Rating-->
				</div>
			</div>
			<?php edit_comment_link( esc_html__( '+ Edit', 'blockter' ), '<span class="edit-link">', '</span>' ); ?>
		</div>
		<?php comment_text(); ?>
		<!-- .comment-content -->
		<!-- .comment-metadata -->
	</footer>
	<!-- .comment-meta -->
</article><!-- .comment-body -->