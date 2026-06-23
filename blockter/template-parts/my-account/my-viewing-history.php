<?php
if ( ! is_user_logged_in() ) {
    echo esc_html__( 'You must be logged in to view watch history.', 'blockter' );
    return;
}
?>
<div class="ins-account-watchlists">
    <h2><?php echo esc_html__( 'Viewing History', 'blockter' ); ?></h2>
    <?php echo do_shortcode('[ins_viewing_history]'); ?>
</div>
