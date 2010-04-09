<?php formbuilder_admin_nav('edit form'); ?>
<form name="form1" method="post" class="formBuilderForm" action="<?php echo FB_ADMIN_PLUGIN_PATH; ?>&fbaction=editForm&fbid=<?php echo $form_id; ?>">

	<h3 class="info-box-title"><?php _e('Form Details', 'formbuilder'); ?></h3>
	<fieldset class="options">
		<p><?php _e('You may use these controls to modify a form on your blog.', 'formbuilder'); ?></p>

		<table width="100%" cellspacing="2" cellpadding="5" class="widefat">
			<tr valign="top">
				<td>
				<h4><?php _e('Form Controls', 'formbuilder'); ?>:</h4>

				<?php
						foreach($fields as $field)
						{
							if($field['Field'] != "id") formbuilder_display_form_field($field);
						}
				?>
				</td>
			</tr>
			<tr valign="top">
				<td>
					<input type="submit" name="Save" value="<?php _e('Save Form', 'formbuilder'); ?>">
				</td>
			</tr>
			<tr valign="top">
				<td>
				<h4><?php _e('Fields', 'formbuilder'); ?>:</h4>
				<?php
					#$related = $tableFields->search_rows("$form_id", "form_id", "display_order ASC");
					$sql = "SELECT * FROM " . FORMBUILDER_TABLE_FIELDS . " WHERE form_id = $form_id ORDER BY display_order ASC;";
					$related = $wpdb->get_results($sql, ARRAY_A);
					if($related)
					{
						$counter = 0;
						foreach($related as $fields)
						{
							$counter++;
							$tableRowID = $fields['id'];
							#$fields = $tableFields->load_row_details($tableRowID);

							echo "<p style='background-color: #E5F3FF;'><a name='field_$tableRowID'></a>" . __('Field #', 'formbuilder') . $counter . " " . __('Options', 'formbuilder') . ": " .
									"<input type='submit' name='fieldAction[" . $tableRowID . "]' value='" . __("Add Another", 'formbuilder') . "' title='" . __('Add another field where this one is now.', 'formbuilder') . "' > " .
									"<input type='submit' name='fieldAction[" . $tableRowID . "]' value='" . __("Delete", 'formbuilder') . "' title='" . __('Delete this field.', 'formbuilder') . "' > " .
									"<input type='submit' name='fieldAction[" . $tableRowID . "]' value='" . __("Move Up", 'formbuilder') . "' title='" . __('Move this field up one.', 'formbuilder') . "' > " .
									"<input type='submit' name='fieldAction[" . $tableRowID . "]' value='" . __("Move Down", 'formbuilder') . "' title='" . __('Move this field down one.', 'formbuilder') . "' > " .
									"</p>\n";

							foreach($fields as $key=>$value)
							{
								$field = array();
								
								$field['Field'] = $key;
												
								if(!isset($_POST['formbuilder'][$key]))
									$field['Value'] = $value;
								else
									$field['Value'] = $_POST['formbuilder'][$key];
										
								// Add a brief explanation to specific fields of how to enter the data.
								if($field['Field'] == "field_type") {
									$field['Title'] = __('Select the type of field that you wish to have shown in this location.', 'formbuilder');
									$field['HelpText'] = 
											__("Select the type of field that you wish to have shown in this location.  Most of them require a field name and label.  Field value is optional.", 'formbuilder') . "\\n";
									foreach($all_field_types as $field_type_name=>$field_type_help)
									{
										$field['Type'] .= "'" . $field_type_name . "',";
										$field['HelpText'] .= "\\n\\n" . $field_type_name . ": " . $field_type_help;
									}
									

									// Alter field_type field from text area to enum to allow for selection box.
									$field['Type'] = "enum(" . trim($field['Type'], ' ,') . ")";

								}

								if($field['Field'] == "field_name") {
									$field['Title'] = __('Enter a name for this field.  Should be only letters and underscores.', 'formbuilder');
									$field['HelpText'] = __("Enter a name for this field.  Should be only letters and underscores.  This field will come through in the email something like this:", 'formbuilder') .
											"\\n\\n" . __("FIELD NAME: The data entered by the user would be here.", 'formbuilder');
									$field['Type'] = "varchar(255)";
								}

								if($field['Field'] == "field_value") {
									$field['Title'] = __("If necessary, enter a predefined value for the field.", 'formbuilder');
									$field['HelpText'] = __("If necessary, enter a predefined value for the fiel.  Most field types do not require a value.  Only Radio Buttons, Selection Dropdowns and Comments.", 'formbuilder') .
											"\\n\\n" . __("Radio Buttons and Selection Dropdowns:", 'formbuilder') .
											"\\n" . __("Each option should be put in the field value, one per line.  These options will be used as the values for users to choose from on the form.", 'formbuilder') .
											"\\n\\n" . __("Comments Fields:", 'formbuilder') .
											"\\n" . __("The information in the field value will be displayed as a comment on the form.", 'formbuilder');
									$field['Type'] = "text";
								}

								if($field['Field'] == "field_label") {
									$field['Title'] = __("The label you want to have in front of this field.", 'formbuilder');
									$field['HelpText'] = __("The label you want to have in front of this field.  When shown on the form, it will appear something like:", 'formbuilder') .
											"\\n\\n" . __("FIELD LABEL: [input box]", 'formbuilder') .
											"\\n\\n" . __("For submit images, this must be the path to the image to be used.", 'formbuilder');
									$field['Type'] = "varchar(255)";
								}

								if($field['Field'] == "required_data") {
									$field['Title'] = __("If you want this field to be required, select the type of data it should look for.", 'formbuilder');
									$field['HelpText'] = __("If you want this field to be required, select the type of data it should look for.", 'formbuilder');

									foreach($all_required_types as $field_type_name=>$field_type_help)
									{
										$field['Type'] .= "'" . $field_type_name . "',";
										$field['HelpText'] .= "\\n\\n" . $field_type_name . ": " . $field_type_help;
									}
									
									// Alter field_type field from text area to enum to allow for selection box.
									$field['Type'] = "enum('|'," . trim($field['Type'], ' ,') . ")";
									
								}

								if($field['Field'] == "error_message") {
									$field['Title'] = __("The error message to be displayed if the required field is not filled in.", 'formbuilder');
									$field['HelpText'] = __("The error message to be displayed if the required field is not filled in.", 'formbuilder');
									$field['Type'] = "varchar(255)";
								}


								// Display the form fields that should be displayed.
								if(
									$field['Field'] != "id"
									AND $field['Field'] != "form_id"
									AND $field['Field'] != "display_order"
									)
								{
									formbuilder_display_form_field($field, "formbuilderfields[" . $tableRowID . "]");
								}

							}
							echo "<br/>&nbsp;";
						}

					}
				?>
				</td>
			</tr>
			<tr valign="top">
				<td>
					<input type='submit' name='fieldAction[newField]' value='<?php _e('Add New Field', 'formbuilder'); ?>'>
					<input type="submit" name="Save" value="<?php _e('Save', 'formbuilder'); ?>">
				</td>
			</tr>
		</table>

	</fieldset>

</form>
