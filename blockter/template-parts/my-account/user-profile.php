<?php
global $current_user, $wp_roles, $post;
$error = array();
/* If profile was saved, update profile. */
if ( !empty( $_REQUEST['action'] ) && $_REQUEST['action'] == 'update-user' ) {

	/* Update user information. */
	if ( !empty( $_REQUEST['url'] ) )
		wp_update_user( array ('ID' => $current_user->ID, 'user_url' => esc_attr( $_REQUEST['url'] )));
	if ( !empty( $_REQUEST['email'] ) ){
		if (!is_email(esc_attr( $_REQUEST['email'] )))
			$error[] = __('The Email you entered is not valid.  please try again.', 'blockter');
		elseif(email_exists(esc_attr( $_REQUEST['email'] )) != $current_user->ID )
			$error[] = __('This email is already used by another user.  try a different one.', 'blockter');
		else{
			wp_update_user( array ('ID' => $current_user->ID, 'user_email' => esc_attr( $_REQUEST['email'] )));
		}
	}

	if ( !empty( $_REQUEST['first-name'] ) )
		update_user_meta( $current_user->ID, 'first_name', esc_attr( $_REQUEST['first-name'] ) );
	if ( !empty( $_REQUEST['last-name'] ) )
		update_user_meta($current_user->ID, 'last_name', esc_attr( $_REQUEST['last-name'] ) );
	if ( !empty( $_REQUEST['display_name'] ) )
		wp_update_user(array('ID' => $current_user->ID, 'display_name' => esc_attr( $_REQUEST['display_name'] )));
	update_user_meta($current_user->ID, 'display_name' , esc_attr( $_REQUEST['display_name'] ));
	if ( !empty( $_REQUEST['description'] ) )
		update_user_meta( $current_user->ID, 'description', esc_attr( $_REQUEST['description'] ) );

	/* Redirect so the page will show updated info.*/
	/*I am not Author of this Code- i dont know why but it worked for me after changing below line to if ( count($error) == 0 ){ */
	if ( count($error) == 0 ) {
		//action hook for plugins and extra fields saving
		do_action('edit_user_profile_update', $current_user->ID);
		wp_redirect( get_permalink().'?updated=true' ); exit;
	}
}
?>
<div id="user-profile" class="form-style-1">
	<h3><?php esc_html_e('Update Information for','blockter'); ?> <?php echo esc_html( $current_user->user_login ); ?></h3>
	<?php if ( isset($_GET['updated']) && $_GET['updated'] == 'true' )  : ?> <div id="message" class="updated"><p>Your profile has been updated.</p></div> <?php endif; ?>
	<?php if ( count($error) > 0 ) echo '<p class="error">' . implode("<br />", $error) . '</p>'; ?>
	<form method="post" id="updatepass" action="<?php the_permalink(); ?>">
		<div class="row">
			<div class="col-md-6">
				<label for="first-name"><?php _e('User Name', 'blockter'); ?></label>
				<input class="text-input" name="user-name" type="text" id="user-name" disabled value="<?php echo esc_attr($current_user->user_login); ?>" />
			</div><!-- .form-username -->
			<div class="col-md-6">
				<label for="email"><?php _e('E-mail *', 'blockter'); ?></label>
				<input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
			</div><!-- .form-email -->
		</div>
		<div class="row">
			<p class="col-md-6">
				<label for="first-name"><?php _e('First Name', 'blockter'); ?></label>
				<input class="text-input" name="first-name" type="text" id="first-name" value="<?php the_author_meta( 'first_name', $current_user->ID ); ?>" />
			</p><!-- .form-username -->
			<p class="col-md-6">
				<label for="last-name"><?php _e('Last Name', 'blockter'); ?></label>
				<input class="text-input" name="last-name" type="text" id="last-name" value="<?php the_author_meta( 'last_name', $current_user->ID ); ?>" />
			</p><!-- .form-username -->
		</div>

		<div class="row">
			<!-- .form-display_name -->
			<div class="col-md-6">
				<label for="display_name"><?php _e('Display name publicly as', 'blockter') ?></label>
				<select name="display_name" id="display_name"><br/>
					<?php
					$public_display = array();
					$public_display['display_nickname']  = $current_user->nickname;
					$public_display['display_username']  = $current_user->user_login;

					if ( !empty($current_user->first_name) )
						$public_display['display_firstname'] = $current_user->first_name;

					if ( !empty($current_user->last_name) )
						$public_display['display_lastname'] = $current_user->last_name;

					if ( !empty($current_user->first_name) && !empty($current_user->last_name) ) {
						$public_display['display_firstlast'] = $current_user->first_name . ' ' . $current_user->last_name;
						$public_display['display_lastfirst'] = $current_user->last_name . ' ' . $current_user->first_name;
					}

					if ( ! in_array( $current_user->display_name, $public_display ) ) // Only add this if it isn't duplicated elsewhere
						$public_display = array( 'display_displayname' => $current_user->display_name ) + $public_display;

					$public_display = array_map( 'trim', $public_display );
					$public_display = array_unique( $public_display );

					foreach ( $public_display as $id => $item ) {
						?>
						<option <?php selected( $current_user->display_name, $item ); ?>><?php echo esc_html( $item ); ?></option>
						<?php
					}
					?>
				</select>
			</div><!-- .form-display_name -->

			<div class="col-md-6 form-url">
				<label for="url"><?php _e('Website', 'blockter'); ?></label>
				<input class="text-input" name="url" type="text" id="url" value="<?php the_author_meta( 'user_url', $current_user->ID ); ?>" />
			</div><!-- .form-url -->

		</div>
		<?php
		//action hook for plugin and extra fields
		//22/03/2026.  This line commented out by Ivan Yankovyi because of critical error that happens in some of the plugins. Probably must be uncommented, but I'm not sure that this is required there at all.
		//do_action('edit_user_profile',$current_user);		
		?>
		<p class="form-submit">
			<button name="updateuser" type="submit" id="updateuser" class="submit btn-main"><?php esc_html_e('Update', 'blockter'); ?></button>
			<?php wp_nonce_field( 'update-user_'. $current_user->ID ) ?>
			<input name="action" type="hidden" id="action" value="update-user" />
		</p><!-- .form-submit -->


	</form><!-- #updatepass -->
</div><!-- #user-profile -->
