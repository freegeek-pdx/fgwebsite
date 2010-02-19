<?php if (isset($updated_message)) : ?>
<div id="message" class="updated fade"><p><strong><?php echo $updated_message; ?></strong></p></div>
<?php endif; ?>
<div class="wrap">
	<h2><?php _e('Contact Form 7', 'wpcf7'); ?></h2>
	<ul class="cfmenu">
	<?php foreach ($contact_forms as $k => $v) : ?>
		<li><?php if ($k == $current) echo '&raquo; '; ?>
			<a href="<?php echo $base_url . '?page=' . $page . '&contactform=' . $k ?>">
			<?php echo $v['title']; ?></a></li>
	<?php endforeach; ?>
		<li class="addnew">
			<?php if ($unsaved) echo '&raquo; '; ?>
			<a href="<?php echo $base_url . '?page=' . $page . '&contactform=new'; ?>">
			<?php _e('Add new', 'wpcf7'); ?></a></li>
	</ul>
</div>
<?php if ($cf) : ?>
<div class="wrap relative">
	<form method="post" action="<?php echo $base_url . '?page=' . $page . '&contactform=' . $current; ?>" id="wpcf7-admin-form-element">
		<?php wp_nonce_field('wpcf7-save_' . $current); ?>
		<input type="hidden" id="wpcf7-id" name="wpcf7-id" value="<?php echo $current; ?>" />
		<input type="text" id="wpcf7-title" name="wpcf7-title" size="40" value="<?php echo htmlspecialchars($cf['title']); ?>" />

		<div class="cfdiv">
			<?php if (! $unsaved) : ?>
			<p class="tagcode">
			<?php _e('Copy and paste this code into your post content.', 'wpcf7'); ?><br />
			<input type="text" id="contact-form-anchor-text" onfocus="this.select();" readonly="readonly" />
			</p>
			<?php endif; ?>

			<div class="fieldset" id="form-content-fieldset"><div class="legend"><?php _e('Form', 'wpcf7'); ?></div>
				<textarea id="wpcf7-form" name="wpcf7-form" cols="100" rows="16"><?php echo htmlspecialchars($cf['form']); ?></textarea>
			</div>

			<div class="fieldset"><div class="legend"><?php _e('Mail', 'wpcf7'); ?></div>
				<div class="mail-field">
					<label for="wpcf7-mail-recipient"><?php _e('To:', 'wpcf7'); ?></label><br />
					<input type="text" id="wpcf7-mail-recipient" name="wpcf7-mail-recipient" class="wide" size="70" value="<?php echo htmlspecialchars($cf['mail']['recipient']); ?>" />
				</div>
				<div class="mail-field">
					<label for="wpcf7-mail-sender"><?php _e('From:', 'wpcf7'); ?></label><br />
					<input type="text" id="wpcf7-mail-sender" name="wpcf7-mail-sender" class="wide" size="70" value="<?php echo htmlspecialchars($cf['mail']['sender']); ?>" />
				</div>
				<div class="mail-field">
					<label for="wpcf7-mail-subject"><?php _e('Subject:', 'wpcf7'); ?></label><br />
					<input type="text" id="wpcf7-mail-subject" name="wpcf7-mail-subject" class="wide" size="70" value="<?php echo htmlspecialchars($cf['mail']['subject']); ?>" />
				</div>
				<div class="mail-field">
					<label for="wpcf7-mail-body"><?php _e('Message body:', 'wpcf7'); ?></label><br />
					<textarea id="wpcf7-mail-body" name="wpcf7-mail-body" cols="100" rows="16"><?php echo htmlspecialchars($cf['mail']['body']); ?></textarea>
				</div>
			</div>

			<div class="fieldset"><div class="legend"><?php _e('Mail (2)', 'wpcf7'); ?></div>
				<input type="checkbox" id="wpcf7-mail-2-active" name="wpcf7-mail-2-active" value="1"<?php echo ($cf['mail_2']['active']) ? ' checked="checked"' : ''; ?> />
				<label for="wpcf7-mail-2-active"><?php _e('Use mail (2)', 'wpcf7'); ?></label>
				<div class="mail-field">
					<label for="wpcf7-mail-2-recipient"><?php _e('To:', 'wpcf7'); ?></label><br />
					<input type="text" id="wpcf7-mail-2-recipient" name="wpcf7-mail-2-recipient" class="wide" size="70" value="<?php echo htmlspecialchars($cf['mail_2']['recipient']); ?>" />
				</div>
				<div class="mail-field">
					<label for="wpcf7-mail-2-sender"><?php _e('From:', 'wpcf7'); ?></label><br />
					<input type="text" id="wpcf7-mail-2-sender" name="wpcf7-mail-2-sender" class="wide" size="70" value="<?php echo htmlspecialchars($cf['mail_2']['sender']); ?>" />
				</div>
				<div class="mail-field">
					<label for="wpcf7-mail-2-subject"><?php _e('Subject:', 'wpcf7'); ?></label><br />
					<input type="text" id="wpcf7-mail-2-subject" name="wpcf7-mail-2-subject" class="wide" size="70" value="<?php echo htmlspecialchars($cf['mail_2']['subject']); ?>" />
				</div>
				<div class="mail-field">
					<label for="wpcf7-mail-2-body"><?php _e('Message body:', 'wpcf7'); ?></label><br />
					<textarea id="wpcf7-mail-2-body" name="wpcf7-mail-2-body" cols="100" rows="16"><?php echo htmlspecialchars($cf['mail_2']['body']); ?></textarea>
				</div>
			</div>

			<input type="hidden" id="wpcf7-options-recipient" name="wpcf7-options-recipient" value="<?php echo htmlspecialchars($cf['options']['recipient']); ?>" />

			<p class="submit">
				<input type="submit" class="cfsave" name="wpcf7-save" value="<?php _e('Save', 'wpcf7'); ?>" />
			</p>
		</div>
			
		<?php if (! $unsaved) : ?>
		<div class="delete-link"><?php $delete_nonce = wp_create_nonce('wpcf7-delete_' . $current); ?>
			<input type="submit" name="wpcf7-delete" value="<?php _e('Delete this contact form', 'wpcf7'); ?>"
				<?php echo "onclick=\"if (confirm('" . js_escape(__("You are about to delete this contact form.\n  'Cancel' to stop, 'OK' to delete.", 'wpcf7')) . "')) {this.form._wpnonce.value = '$delete_nonce'; return true;} return false;\""; ?> />
		</div>
		<?php endif; ?>
	</form>
</div>
<?php endif; ?>