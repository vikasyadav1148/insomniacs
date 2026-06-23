<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">
	<?php if ( have_comments() ) :/*comment list*/ ?>
		<h3 class="comment-total-title">
			<?php
				printf( /* WPCS: XSS OK.*/
					esc_html__( _nx( 'Comment (1)', 'Comments (%1$s)', get_comments_number(), 'comments title', 'blockter') ),
					number_format_i18n( get_comments_number() ),
					'<span>' . get_the_title() . '</span>'
				);
			?>
		</h3>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<nav id="comment-nav-above" class="navigation comment-navigation">
			<h3 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'blockter'); ?></h3>
			<div class="nav-previous"><?php previous_comments_link( esc_html__( ' Older Comments', 'blockter') ); ?></div>
			<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments ', 'blockter') ); ?></div>
		</nav>
		<?php endif; ?>

		<div class="comment-list flw">
			<?php wp_list_comments( array( 'callback' => 'blockter_comment_list' ) ); ?>
		</div>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<nav id="comment-nav-below" class="navigation comment-navigation">
			<h3 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'blockter'); ?></h3>
			<div class="nav-previous"><?php previous_comments_link( esc_html__( ' Older Comments', 'blockter') ); ?></div>
			<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments ', 'blockter') ); ?></div>
		</nav>
		<?php endif; ?>

	<?php endif; ?>
	<?php if ( ! comments_open() ) :/*comment disable*/ ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'blockter'); ?></p>
	<?php else :/*comment form*/
		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );
	$fields = array(
		'author' =>
			'<div class="flex-item">' .
				'<div class="name">' .
					'<label for="author">' . esc_html__( 'Your Name', 'blockter' ) . '</label>' .
					'<input id="author" type="text" name="author" value="' . esc_attr( $commenter['comment_author'] ) . '" ' . $aria_req . '>' .
				'</div>' .
				'<div class="mail">' .
					'<label for="email">' . esc_html__( 'Your E-mail', 'blockter' ) . '</label>' .
					'<input id="email" type="email" name="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" ' . $aria_req . '>' .
				'</div>' .
			'</div>',
	);

		$args = array(
			'title_reply'          => esc_html__( 'Leave a comment', 'blockter'),
			'fields' => apply_filters( 'comment_form_default_fields', $fields ),
			'comment_field' =>  '<textarea id="comment" placeholder=" ' . esc_attr__( 'Your Comments', 'blockter' ) . ' " name="comment" '.$aria_req.'>' . '</textarea>',
			'comment_notes_before' => '',
			'label_submit'         => esc_html__( 'submit', 'blockter'),
		);
		comment_form( $args ); ?>
	<?php endif; ?>
</div>
