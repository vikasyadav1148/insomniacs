<?php
if ( ! is_user_logged_in() ) {
    echo esc_html__( 'You must be logged in to manage watchlists.', 'blockter' );
    return;
}
?>
<div class="ins-account-watchlists">
    <h2><?php echo esc_html__( 'My Lists', 'blockter' ); ?></h2>
    <?php echo do_shortcode('[ins_watchlists_dashboard]'); ?>
</div>
