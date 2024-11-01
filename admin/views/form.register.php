<div class="wrap">
	<h1><?php _e('Sign up to Webanti', 'webanti'); ?></h1>
	<div class="error notice">
		<p>
			<?php _e('Website is not registered in Webanti. Enter your e-mail address to register or add the page to make your Account.', 'webanti'); ?>
		</p>
	</div>

	<form method="post">
	    <table class="form-table">
	        <tr valign="top">
	        	<th scope="row"><?php _e('E-mail address', 'webanti'); ?></th>
	        	<td><input type="text" name="WEBANTI_CUSTOMER_EMAIL" value="<?php echo esc_attr( get_option('WEBANTI_CUSTOMER_EMAIL') ); ?>" /></td>
	        </tr>
	        <tr valign="top">
			<th scope="row">
				<td> 
					<fieldset>
						<legend class="screen-reader-text">
							<span><?php _e('Terms and the Privacy Policy', 'webanti'); ?></span>
						</legend>
						<label for="terms">
							<input name="terms" type="checkbox" id="terms" value="1">
							<?php _e('I accept the Terms and the Privacy Policy of Webanti.', 'webanti'); ?>
						</label>
					</fieldset>
				</td>
			</tr>
	    </table>
		<p class="submit">
			<button type="submit" name="btnRegister" value="1" class="button button-primary">
				<?php _e('Register', 'webanti'); ?>
			</button>
		</p>
	</form>
</div>