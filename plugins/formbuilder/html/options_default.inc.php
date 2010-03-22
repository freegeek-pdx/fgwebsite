<?php formbuilder_admin_nav('forms'); ?>
<fieldset class="options metabox-holder">

	<div class="info-box-formbuilder postbox">
		<h3 class="info-box-title hndle"><?php _e('Current Forms', 'formbuilder'); ?></h3>
		<div class="inside">
		<p><?php _e('These are the forms that you currently have running on your blog.', 'formbuilder'); ?>
		<a href="<?php echo FB_ADMIN_PLUGIN_PATH; ?>&fbaction=newForm"><?php printf(__('Click here%s to create a new form', 'formbuilder'), '</a>'); ?>.</p>

		<table class="widefat">
			<tr valign="top">
				<th><?php _e('ID #', 'formbuilder'); ?></th>
				<th><?php _e('Name', 'formbuilder'); ?></th>
				<th><?php _e('Subject', 'formbuilder'); ?></th>
				<th><?php _e('Recipient', 'formbuilder'); ?></th>
				<th><?php _e('Actions', 'formbuilder'); ?></th>
			</tr>
			<?php
				// Build the list of current forms:
				$sql = "SELECT * FROM " . FORMBUILDER_TABLE_FORMS;
				$objForms = $wpdb->get_results($sql);
				$alt = false;

				if(is_array($objForms)) foreach($objForms as $form)
				{
					if($alt == false) {
						$alt = true;
						$class = "alternate";
					}
					else
					{
						$class = "";
						$alt = false;
					}
			?>
			<tr valign="top" class="<?php echo $class; ?>">
				<td><acronym title="<?php printf(__("Manually include this form with %s in the page/post content.", 'formbuilder'), "[formbuilder:" . $form->id . "]"); ?>"><?php echo $form->id; ?></acronym></td>
				<td><?php echo $form->name; ?></td>
				<td><?php echo $form->subject; ?></td>
				<td><?php echo $form->recipient; ?></td>
				<td>
					<a href="<?php echo FB_ADMIN_PLUGIN_PATH; ?>&fbaction=editForm&fbid=<?php echo $form->id; ?>"><?php _e('Edit', 'formbuilder'); ?></a>
					 |
					<a href="<?php echo FB_ADMIN_PLUGIN_PATH; ?>&fbaction=copyForm&fbid=<?php echo $form->id; ?>"><?php _e('Copy', 'formbuilder'); ?></a>
					 |
					<a href="<?php echo FB_ADMIN_PLUGIN_PATH; ?>&fbaction=removeForm&fbid=<?php echo $form->id; ?>" onclick="return(confirm('<?php _e('Are you sure you want to delete this form?', 'formbuilder'); ?>'));"><?php _e('Remove', 'formbuilder'); ?></a>
				</td>
			</tr>
			<?php } ?>
		</table>
		</div>
	</div>
	
	<div class="info-box-formbuilder postbox">
		<h3 class="info-box-title hndle"><?php _e('Current Autoresponses', 'formbuilder'); ?></h3>
		<div class="inside">
		<p><?php _e('These are the autoresponses that you have available to use with your forms.', 'formbuilder'); ?>
		<a href="<?php echo FB_ADMIN_PLUGIN_PATH; ?>&fbaction=newResponse"><?php printf(__('Click here%s to create a new autoresponse.', 'formbuilder'), '</a>'); ?></p>

		<table class="widefat">
			<tr valign="top">
				<th><?php _e('Name', 'formbuilder'); ?></th>
				<th><?php _e('Subject', 'formbuilder'); ?></th>
				<th><?php _e('Actions', 'formbuilder'); ?></th>
			</tr>
			<?php
				// Build the list of current forms:
				$sql = "SELECT * FROM " . FORMBUILDER_TABLE_RESPONSES;
				$objResponses = $wpdb->get_results($sql);

				if(is_array($objResponses)) foreach($objResponses as $autoresponse)
				{
					if($alt == false) {
						$alt = true;
						$class = "alternate";
					}
					else
					{
						$class = "";
						$alt = false;
					}

			?>
			<tr valign="top" class="<?php echo $class; ?>">
				<td><?php echo $autoresponse->name; ?></td>
				<td><?php echo $autoresponse->subject; ?></td>
				<td>
					<a href="<?php echo FB_ADMIN_PLUGIN_PATH; ?>&fbaction=editResponse&fbid=<?php echo $autoresponse->id; ?>"><?php _e('Edit', 'formbuilder'); ?></a>
					 |
					<a href="<?php echo FB_ADMIN_PLUGIN_PATH; ?>&fbaction=copyResponse&fbid=<?php echo $autoresponse->id; ?>"><?php _e('Copy', 'formbuilder'); ?></a>
					 |
					<a href="<?php echo FB_ADMIN_PLUGIN_PATH; ?>&fbaction=removeResponse&fbid=<?php echo $autoresponse->id; ?>" onclick="return(confirm('<?php _e('Are you sure you want to delete this autoresponse?', 'formbuilder'); ?>'));"><?php _e('Remove', 'formbuilder'); ?></a>
				</td>
			</tr>
			<?php } ?>
		</table>
		</div>
	</div>
	
	<div class='clear' />

</fieldset>
