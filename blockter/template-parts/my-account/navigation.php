<?php
/*Log out*/
$user_id = '';
if ( is_user_logged_in() ) {
	$log_items = '<a href="' . wp_logout_url( home_url() ) . '">' . esc_html__( 'Log Out', 'blockter' ) . '</a>';
	$user = wp_get_current_user();
	$user_id = (int) $user->data->ID;
	$member_since = mysql2date( 'F Y', $user->user_registered );
} else {
	$log_items = '<a href="' . wp_login_url() . '">' . esc_html__( 'Log In', 'blockter' ) . '</a>';
	$member_since = '';
}



// Account navigation labels.
$menu_items = array(
	'my-account'      => array( 'label' => __( 'My Profile', 'blockter' ), 'icon' => 'ion-person' ),
	'my-planner'      => array( 'label' => 'Cinema Release Planner', 'icon' => 'ion-ios-calendar-outline' ),
	'my-movies'       => array( 'label' => 'Favourite Movies', 'icon' => 'ion-film-marker' ),
	'my-shows'        => array( 'label' => __( 'Favourite Series', 'blockter' ), 'icon' => 'ion-monitor' ),
	'my-watchlists'   => array( 'label' => __( 'My Lists', 'blockter' ), 'icon' => 'ion-ios-bookmarks-outline' ),
	'my-viewing-history' => array( 'label' => __( 'Viewing History', 'blockter' ), 'icon' => 'ion-clock' ),
	'change-password' => array( 'label' => __( 'Change Password', 'blockter' ), 'icon' => 'ion-locked' ),
	'logout'          => array( 'label' => __( 'Logout', 'blockter' ), 'icon' => 'ion-log-out' ),
);
?>
<div class="user-info-tab">
	<div class="ins-account-profile-card">
		<div class="user-img">
			<?php echo get_avatar( $user_id, 120 ); ?>
		</div><!-- user-img -->
		<?php if ( is_user_logged_in() ) : ?>
			<h3 class="ins-account-name"><?php echo esc_html( $user->display_name ); ?></h3>
			<p class="ins-account-meta"><?php echo esc_html( 'Member since ' . $member_since ); ?></p>
		<?php endif; ?>
	</div>

	<nav class="user-fav ins-account-nav">
		<ul class="tab-links">
			<?php foreach ( $menu_items as $endpoint => $item ) : ?>
				<li class="<?php echo blockter_get_account_menu_item_classes( $endpoint ); ?>">
					<a href="<?php echo esc_url( blockter_get_account_endpoint_url( $endpoint ) ); ?>"><span class="ins-nav-icon <?php echo esc_attr( $item['icon'] ); ?>"></span><span><?php echo esc_html( $item['label'] ); ?></span></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav><!-- .user-fav -->

	<div class="ins-account-help-card">
		<h4><?php esc_html_e( 'Need help?', 'blockter' ); ?></h4>
		<p><?php esc_html_e( 'Visit your account center to manage details and watchlist settings.', 'blockter' ); ?></p>
		<a href="<?php echo esc_url( blockter_get_account_endpoint_url( 'my-account' ) ); ?>" class="ins-account-help-btn"><?php esc_html_e( 'View Help Center', 'blockter' ); ?></a>
	</div>

</div><!-- .user-info-tab -->
