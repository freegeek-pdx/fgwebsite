<?php formbuilder_admin_nav('settings'); ?>
<fieldset class="options metabox-holder">

	<div id="formbuilder-admin-left">
	
		<?php
			if(!isset($results_page)) $results_page = new formbuilder_xml_db_results();
			$results_page->show_dashboard();
		?>
		<div class="info-box-formbuilder postbox">
			<h3 class="info-box-title hndle"><?php _e('Spam Blocker', 'formbuilder'); ?></h3>
			<div class="inside">
			<p><?php printf(__('The FormBuilder can employ a %sspam blocking system%s in order to prevent automated computers from spamming the forms.  To accomplish this we put a field on the form that has been hidden, so that computers can see it, but people cannot.  If the field has been filled out when the form is submitted, the system will assume that a spammer is attempting to use the form, and will ignore the submission.', 'formbuilder'), '<a href="http://truthmedia.com/2008/06/27/feature-reverse-captcha/" title="Read More: FormBuilder Spam Blocking" target="_blank">', '</a>'); ?></p>
			<p><?php _e('In order to help make this spam blocker more effective, you can specify a name for this spam blocker that will be used when it is displayed in the code of your form.', 'formbuilder'); ?></p>
			<?php
				if(isset($_POST['formBuilder_Spam_Blocker'])) {
					$spam_blocker = clean_field_name($_POST['formBuilder_Spam_Blocker']);
					if($spam_blocker != "")	update_option('formbuilder_spam_blocker', $spam_blocker);
				}
				$spam_blocker = get_option('formbuilder_spam_blocker');
			?>
			<form action="<?php echo FB_ADMIN_PLUGIN_PATH; ?>&fbaction=settings" method="POST">
				<input type="text" name="formBuilder_Spam_Blocker" value="<?php echo $spam_blocker; ?>" />
				<input type="submit" name="Submit" value="<?php _e('Save', 'formbuilder'); ?>" />
			</form>
			</div>
		</div>
	
		<div class="info-box-formbuilder postbox">
			<?php
				$alternate_email = get_option('formbuilder_alternate_email_handling');
				if(isset($_POST['formBuilder_Alternate_Email'])) {
					$alternate_email = $_POST['formBuilder_Alternate_Email'];
					if($alternate_email != "")	update_option('formbuilder_alternate_email_handling', $alternate_email);
				}
			?>
			<h3 class="info-box-title hndle"><?php _e('Alternate Email Handling: ', 'formbuilder'); ?><?php 
				if($alternate_email == 'Enabled') _e('Enabled', 'formbuilder');
				else _e('Disabled', 'formbuilder');
			?></h3>
			<div class="inside">
			<p><?php _e('If you are having difficulty receiving email sent in on your forms, try setting this to enable Alternate Email Handling.', 'formbuilder'); ?></p>
			<form action="<?php echo FB_ADMIN_PLUGIN_PATH; ?>&fbaction=settings" method="POST">
				<select name="formBuilder_Alternate_Email">
					<option value="Disabled" <?php if($alternate_email == "Disabled") echo "selected"; ?>><?php _e('Disabled', 'formbuilder'); ?></option>
					<option value="Enabled" <?php if($alternate_email == "Enabled") echo "selected"; ?>><?php _e('Enabled', 'formbuilder'); ?></option>
				</select>
				<input type="submit" name="Submit" value="<?php _e('Save', 'formbuilder'); ?>" />
			</form>
			</div>
		</div>


		<div class="info-box-formbuilder postbox">
			<?php
				$formbuilder_excessive_links = get_option('formbuilder_excessive_links');
				if(isset($_POST['formbuilder_excessive_links'])) {
					$formbuilder_excessive_links = $_POST['formbuilder_excessive_links'];
					if($formbuilder_excessive_links != "")	update_option('formbuilder_excessive_links', $formbuilder_excessive_links);
				}
			?>
			<h3 class="info-box-title hndle"><?php _e('Excessive Link Checking: ', 'formbuilder'); ?><?php 
				if($formbuilder_excessive_links == 'Enabled') _e('Enabled', 'formbuilder');
				else _e('Disabled', 'formbuilder');
			?></h3>
			<div class="inside">
			<p><?php _e('If desired, you can have all submitted form data checked for excessive numbers of links, as defined in the WordPress discussion options.  (See "Comment Moderation")  If excessive numbers of links are found the form will still be sent, however the subject line will be modified to indicate POSSIBLE SPAM.', 'formbuilder'); ?></p>
			<form action="<?php echo FB_ADMIN_PLUGIN_PATH; ?>&fbaction=settings" method="POST">
				<select name="formbuilder_excessive_links">
					<option value="Disabled" <?php if($formbuilder_excessive_links == "Disabled") echo "selected"; ?>><?php _e('Disabled', 'formbuilder'); ?></option>
					<option value="Enabled" <?php if($formbuilder_excessive_links == "Enabled") echo "selected"; ?>><?php _e('Enabled', 'formbuilder'); ?></option>
				</select>
				<input type="submit" name="Submit" value="<?php _e('Save', 'formbuilder'); ?>" />
			</form>
			</div>
		</div>

	
	
	</div>
	<div id='formbuilder-admin-right'>	
	
		<div class="info-box-formbuilder postbox">
			<?php
				$custom_css = get_option('formBuilder_custom_css');
				if(isset($_POST['formBuilder_Custom_CSS'])) {
					$custom_css = $_POST['formBuilder_Custom_CSS'];
					if($custom_css != "")	update_option('formBuilder_custom_css', $custom_css);
				}
			?>
			<h3 class="info-box-title hndle"><?php _e('Standard CSS: ', 'formbuilder'); ?><?php 
				if($custom_css == 'Enabled') _e('Enabled', 'formbuilder');
				else _e('Disabled', 'formbuilder');
			?></h3>
			<div class="inside">
			<p><?php _e('The FormBuilder comes with a standard CSS formatting file which can be used in the event that your template does not have support for the FormBuilder fields.  By default, use of this standard CSS is enabled, but in the event that you wish to use custom CSS, or a template which already supports the FormBuilder system, you may disable the built-in CSS formatting by selecting your preference below.', 'formbuilder'); ?></p>
			<form action="<?php echo FB_ADMIN_PLUGIN_PATH; ?>&fbaction=settings" method="POST">
				<select name="formBuilder_Custom_CSS">
					<option value="Enabled" <?php if($custom_css == "Enabled") echo "selected"; ?>><?php _e('Enabled', 'formbuilder'); ?></option>
					<option value="Disabled" <?php if($custom_css == "Disabled") echo "selected"; ?>><?php _e('Disabled', 'formbuilder'); ?></option>
				</select>
				<input type="submit" name="Submit" value="<?php _e('Save', 'formbuilder'); ?>" />
			</form>
			</div>
		</div>

		<div class="info-box-formbuilder postbox">
			<?php
				$ip_capture = get_option('formBuilder_IP_Capture');
				if(isset($_POST['formBuilder_IP_Capture'])) {
					$ip_capture = $_POST['formBuilder_IP_Capture'];
					if($ip_capture != "")	update_option('formBuilder_IP_Capture', $ip_capture);
				}
				if(!$ip_capture) $ip_capture = 'Disabled';
			?>
			<h3 class='info-box-title hndle'>IP Capture: <?php 
				if($ip_capture == 'Enabled') _e('Enabled', 'formbuilder');
				else _e('Disabled', 'formbuilder');
			?></h3>
			<div class="inside">
				<p><?php _e('FormBuilder can now attempt to capture the IP address of visitors who fill in forms on your website.  This information can be used for security purposes, or to report harassment problems to the appropriate authorities.', 'formbuilder'); ?></p>
				
				<form action="<?php echo FB_ADMIN_PLUGIN_PATH; ?>&fbaction=settings" method="POST">
					<select name="formBuilder_IP_Capture">
						<option value="Enabled" <?php if($ip_capture == "Enabled") echo "selected"; ?>><?php _e('Enabled', 'formbuilder'); ?></option>
						<option value="Disabled" <?php if($ip_capture == "Disabled") echo "selected"; ?>><?php _e('Disabled', 'formbuilder'); ?></option>
					</select>
					<input type="submit" name="Submit" value="<?php _e('Save', 'formbuilder'); ?>" />
				</form>
				
			</div>
		</div>
		
		<div class="info-box-formbuilder postbox">
			<?php
				$formbuilder_blacklist = get_option('formbuilder_blacklist');
				if(isset($_POST['formbuilder_blacklist'])) {
					$formbuilder_blacklist = $_POST['formbuilder_blacklist'];
					if($formbuilder_blacklist != "")	update_option('formbuilder_blacklist', $formbuilder_blacklist);
				}
			?>
			<h3 class="info-box-title hndle"><?php _e('Blacklist Checking: ', 'formbuilder'); ?><?php 
				if($formbuilder_blacklist == 'Enabled') _e('Enabled', 'formbuilder');
				else _e('Disabled', 'formbuilder');
			?></h3>
			<div class="inside">
			<p><?php _e('If desired, you can have all submitted form data checked against the blacklisted words set in the WordPress discussion settings.  If blacklisted words are used, the form will not be sent and an error will be shown to the visitor.  You can edit the blacklisted phrases in the WordPress discussion settings.', 'formbuilder'); ?></p>
			<form action="<?php echo FB_ADMIN_PLUGIN_PATH; ?>&fbaction=settings" method="POST">
				<select name="formbuilder_blacklist">
					<option value="Disabled" <?php if($formbuilder_blacklist == "Disabled") echo "selected"; ?>><?php _e('Disabled', 'formbuilder'); ?></option>
					<option value="Enabled" <?php if($formbuilder_blacklist == "Enabled") echo "selected"; ?>><?php _e('Enabled', 'formbuilder'); ?></option>
				</select>
				<input type="submit" name="Submit" value="<?php _e('Save', 'formbuilder'); ?>" />
			</form>
			</div>
		</div>

	
	
		<div class="info-box-formbuilder postbox">
			<?php
				$formbuilder_greylist = get_option('formbuilder_greylist');
				if(isset($_POST['formbuilder_greylist'])) {
					$formbuilder_greylist = $_POST['formbuilder_greylist'];
					if($formbuilder_greylist != "")	update_option('formbuilder_greylist', $formbuilder_greylist);
				}
			?>
			<h3 class="info-box-title hndle"><?php _e('Greylist Checking: ', 'formbuilder'); ?><?php 
				if($formbuilder_greylist == 'Enabled') _e('Enabled', 'formbuilder');
				else _e('Disabled', 'formbuilder');
			?></h3>
			<div class="inside">
			<p><?php _e('If desired, you can have all submitted form data checked against the greylisted words set in the WordPress discussion settings.  (See "Comment Moderation")  If greylisted words are used, the form will still be sent, however the subject line will be modified to indicate POSSIBLE SPAM.', 'formbuilder'); ?></p>
			<form action="<?php echo FB_ADMIN_PLUGIN_PATH; ?>&fbaction=settings" method="POST">
				<select name="formbuilder_greylist">
					<option value="Disabled" <?php if($formbuilder_greylist == "Disabled") echo "selected"; ?>><?php _e('Disabled', 'formbuilder'); ?></option>
					<option value="Enabled" <?php if($formbuilder_greylist == "Enabled") echo "selected"; ?>><?php _e('Enabled', 'formbuilder'); ?></option>
				</select>
				<input type="submit" name="Submit" value="<?php _e('Save', 'formbuilder'); ?>" />
			</form>
			</div>
		</div>

	
	
		<div class="info-box-formbuilder postbox">
			<h3 class="info-box-title hndle"><?php _e('Uninstall FormBuilder', 'formbuilder'); ?></h3>
			<div class="inside">
			<p><?php printf(__('If for some reason you need to uninstall the FormBuilder plugin, you may %sclick here to uninstall the FormBuilder tables from your database%s.', 'formbuilder'), "<a href='" . FB_ADMIN_PLUGIN_PATH . "&fbaction=uninstall'>", '</a>'); ?></p>
			<p>
			</div>
		</div>
	
	</div>
	
	<div class='clear' />

</fieldset>
