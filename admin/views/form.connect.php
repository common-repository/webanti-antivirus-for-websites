<div class="wrap">
	<h1><?php _e('Connect with Webanti', 'webanti'); ?></h1>

	<?php if ( $content->websiteInfo['webantiScannerStatus'] != 1 || empty($content->websiteInfo['webantiPlanName']) ) { ?>
	<div class="updated notice">
		<p>
			<?php _e('Website is registered in Webanti. Enter website API KEY for connect.', 'webanti'); ?>
		</p>
	</div>
	<?php } ?>

	<form method="post">
	    <table class="form-table">
	        <tr valign="top">
	        	<th scope="row"><?php _e('API KEY', 'webanti'); ?></th>
	        	<td><input type="text" name="WEBANTI_CUSTOMER_APIKEY" value="<?php echo esc_attr( get_option('WEBANTI_CUSTOMER_APIKEY') ); ?>" required/></td>
	        </tr>
	    </table>
		<p class="submit">
			<button type="submit" name="btnConnect" value="1" class="button button-primary">
				<?php _e('Connect', 'webanti'); ?>
			</button>
		</p>
	</form>
</div>