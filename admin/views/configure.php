<div id="webanti_configuration" class="wrap">
	<div class="logo">
		<img src="<?php echo plugins_url( 'images/logo-webanti.png', dirname(__FILE__) ); ?>" alt="Webanti" height="40"/>
		<a href="https://app.webanti.com" class="sign-in" target="_blank">
			<?php _e( 'Sign in to Webanti', 'webanti' ); ?>
		</a>
		<div class="clearfix"></div>
	</div>
	<div class="info">
		<table>
			<tr>
				<th><?php _e( 'Scanner status', 'webanti' ); ?></th>
				<th><?php _e( 'Website status', 'webanti' ); ?></th>
				<th><?php _e( 'User plan', 'webanti' ); ?></th>
				<th><?php _e( 'Plan expires', 'webanti' ); ?></th>
			</tr>
			<tr>
				<td>
					<?php if ( $content->websiteInfo['webantiScannerStatus'] == 1 ) { ?>
						<span class="ok"><?php _e( 'Installed', 'webanti' ); ?></span>
					<?php } else { ?>
						<span class="error"><?php _e( 'Not installed', 'webanti' ); ?></span>
					<?php } ?>
				</td>
				<td>
					<?php if ( $content->websiteInfo['webantiWebsiteStatus'] == 1 ) { ?>
						<span class="ok"><?php _e( 'Active', 'webanti' ); ?></span>
					<?php } ?>
					<?php if ( $content->websiteInfo['webantiWebsiteStatus'] == 3 ) { ?>
						<span class="error"><?php _e( 'Pause', 'webanti' ); ?></span>
					<?php } ?>
					<?php if ( $content->websiteInfo['webantiWebsiteStatus'] == 99 ) { ?>
						<span class="error"><?php _e( 'Unregistered', 'webanti' ); ?></span>
					<?php } ?>
				</td>
				<td>
					<?php if ( !empty($content->websiteInfo['webantiPlanName']) ) { ?>
						<?php echo $content->websiteInfo['webantiPlanName']; ?><br>
						<a href="https://app.webanti.com" class="upgrade-plan" target="_blank">
							<?php _e( 'Upgrade plan', 'webanti' ); ?>
						</a>
					<?php } else { ?>
						---
					<?php } ?>
				</td>
				<td>
					<?php if ( !empty($content->websiteInfo['webantiPlanExpireDate']) ) { ?>
						<?php echo $content->websiteInfo['webantiPlanExpireDate']; ?>
					<?php } else { ?>
						---
					<?php } ?>
				</td>
			</tr>
		</table>
	</div>

	<?php if ( isset($content->websiteInfo['dynamicContent']) && !empty($content->websiteInfo['dynamicContent']) ) { ?>
	<div class="dynamic-content">
		<?php echo $content->websiteInfo['dynamicContent']; ?>
	</div>
	<?php } ?>

	<div class="box-body">
		<p><?php _e( 'Everyone agrees that prevention is better than cure. This also applies to the security of websites. Number of cybercrime incidents involving hackers’ attacks and their malicious software increase every year. Malware, ransomware, backdoor – the list is long. Fortunately, there are effective methods of preventing them. Check how Webanti will take care of your website security.', 'webanti' ); ?></p>
		<p><?php _e( 'Webanti is an antivirus software, which provides real-time protection of your website and in case of detection dangerous events webmaster is instantly warned.', 'webanti' ); ?></p>
		<p><?php _e( 'Are you interested? You can try Webanti protection for free. Our customer support is ready to help you 24/7.', 'webanti' ); ?></p>
		<p><?php _e( 'Trust our specialists and start effectively secure your website. Try Webanti today!', 'webanti' ); ?></p>
	</div>
</div>