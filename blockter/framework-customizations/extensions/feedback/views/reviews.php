<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}
/**
 * The template for displaying Reviews
 *
 * The area of the page that contains reviews and the review form.
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the reviews.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>

		<h3 class="comments-title comment-total-title">
			<?php
			printf( _n( 'Comment (1)', 'Comments (%1$s)', get_comments_number(), 'blockter' ),
				number_format_i18n( get_comments_number() ), get_the_title() );
			?>
		</h3>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
				<h1 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'blockter' ); ?></h1>

				<div
					class="nav-previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'blockter' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'blockter' ) ); ?></div>
			</nav><!-- #comment-nav-above -->
		<?php endif; // Check for comment navigation. ?>

		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'walker'      => fw_ext_feedback_get_listing_walker(),
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 45,
			) );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
				<h1 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'blockter' ); ?></h1>

				<div
					class="nav-previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'blockter' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'blockter' ) ); ?></div>
			</nav><!-- #comment-nav-below -->
		<?php endif; // Check for comment navigation. ?>

		<?php if ( ! comments_open() ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'blockter' ); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments() ?>
	<?php
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );
		$args = array(
		'title_reply'          => esc_html__( 'Leave a Review', 'blockter'),
		'comment_field' =>  '<label>Review</label> <textarea id="comment" name="comment" '.$aria_req.'>' . '</textarea>',
		'label_submit'         => esc_html__( 'Post Review', 'blockter'),
		);
	?>
	<?php comment_form($args); ?>

</div><!-- #comments -->
