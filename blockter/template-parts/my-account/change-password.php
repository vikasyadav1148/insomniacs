<?php
/* Get user info. */
global $current_user, $wp_roles, $post;
$error = array();
/* Update password */
if ( !empty( $_REQUEST['action'] ) && $_REQUEST['action'] == 'update-pass' ) {
	/* Update user password. */
	if ( !empty($_REQUEST['oldpass']) && !empty($_REQUEST['pass1'] ) && !empty( $_REQUEST['pass2'] ) ) {
		if(wp_check_password($_REQUEST['oldpass'], $current_user->user_pass, $current_user->ID) == true){
			if ( $_REQUEST['pass1'] == $_REQUEST['pass2'] )
				wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => esc_attr( $_REQUEST['pass1'] ) ) );
			else
				$error[] = __('The passwords you entered do not match.  Your password was not updated.', 'blockter');
		}else{
			$error[] = esc_html__('The old password does not match', 'blockter');
		}
		if ( count($error) == 0 ) {
			wp_redirect( get_permalink().'?updated=true' ); exit;
		}
	}
}
?>
<div id="change-pass" class="form-style-1">
	<h3><?php esc_html_e('Change password for','blockter'); ?> <?php echo esc_html( $current_user->user_login ); ?></h3>
	<form method="post" id="updatepass" action="<?php the_permalink(); ?>">
		<div class="row">
			<div class="col-md-12 form-password">
				<label for="oldpass"><?php _e('Old Password *', 'blockter'); ?> </label>
				<input class="text-input" name="oldpass" type="password" id="oldpass" />
			</div><!-- .form-password -->
			<div class="col-md-12 form-password">
				<label for="pass1"><?php _e('Password *', 'blockter'); ?> </label>
				<input class="text-input" name="pass1" type="password" id="pass1" />
			</div><!-- .form-password -->
			<div class="col-md-12 form-password">
				<label for="pass2"><?php _e('Repeat Password *', 'blockter'); ?></label>
				<input class="text-input" name="pass2" type="password" id="pass2" />
			</div><!-- .form-password -->
			<div class="col-md-12 form-submit">
				<button name="updatepass" type="submit" id="updatepass" class="submit btn-main" ><?php _e('Update', 'blockter'); ?></button>
				<?php wp_nonce_field( 'update-pass_'. $current_user->ID ) ?>
				<input name="action" type="hidden" id="action" value="update-pass" />
			</div><!-- .form-submit -->
		</div>
	</form>
</div><!-- #change-pass -->
