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
											__("Select the type of field that you wish to have shown in this location.  Most of them require a field name and label.  Field value is optional.", 'formbuilder') . "\\n" .
											"\\n\\nsingle line text box: " . __("Standard single line text box.", 'formbuilder') .
											"\\n\\nsmall text area: " . __("Small multi-line text box.", 'formbuilder') .
											"\\n\\nlarge text area: " . __("Large multi-line text box.", 'formbuilder') .
											"\\n\\npassword box: " . __("Used for password entry.  Characters are hidden.", 'formbuilder') .
											"\\n\\ndatestamp: " . __("Date selection field.", 'formbuilder') .
											"\\n\\nunique id: " . __("Put a unique ID on your forms.", 'formbuilder') .
											"\\n\\ncheckbox: " . __("Single check box.", 'formbuilder') .
											"\\n\\nradio buttons: " . __("Radio selection buttons.  Enter one per line in the field value.", 'formbuilder') .
											"\\n\\nselection dropdown: " . __("Dropdown box.  Enter one value per line in the field value.", 'formbuilder') .
											"\\n\\nhidden field: " . __("A hidden field on the form.  The data will appear in the email.", 'formbuilder') .
											"\\n\\ncomments area: " . __("Special field just for text on the form.  Put the text in the field value.", 'formbuilder') .
											"\\n\\nfollowup page: " . __("Special field just for indicating a followup url, once the form has been submitted.  Put the url you want people to be redirected to in the field value.", 'formbuilder') .
											"\\n\\nrecipient selection: " . __("A special selection dropdown allowing the visitor to specify an alternate form recipient.  Enter values in the form of email@domain.com|Destination Name.", 'formbuilder') .
											"\\n\\ncaptcha field: " . __("Special field on the form for displaying CAPTCHAs.  Field name is used for identifying the field.  Field label is used to give the visitor further instruction on what to fill out.", 'formbuilder') .
											"\\n\\nspam blocker: " . __("Special field on the form.  Read more on the FormBuilder admin page.  Only needs a field name.", 'formbuilder') .
											"\\n\\npage break: " . __("Allows you to break your form into multiple pages.  Needs field name and field label.", 'formbuilder') .
											"\\n\\nreset button: " . __("Allows you to put a customized reset button anywhere on the form.  Needs field name and field label.", 'formbuilder') .
											"\\n\\nsubmit button: " . __("Allows you to put a customized submit button anywhere on the form.  Needs field name and field label.", 'formbuilder') .
											"\\n\\nsubmit image: " . __("Allows you to put a customized submit image anywhere on the form.  Needs field name and field label.  Field label must be the PATH TO THE IMAGE to be used for the submit button.", 'formbuilder') .
											"";

									// Alter field_type field from text area to enum to allow for selection box.
									$field['Type'] = "enum('single line text box'";
									$field['Type'] .= ",'small text area'";
									$field['Type'] .= ",'large text area'";
									$field['Type'] .= ",'password box'";
									$field['Type'] .= ",'datestamp'";
									$field['Type'] .= ",'unique id'";
									$field['Type'] .= ",'checkbox'";
									$field['Type'] .= ",'radio buttons'";
									$field['Type'] .= ",'selection dropdown'";
									$field['Type'] .= ",'hidden field'";
									$field['Type'] .= ",'comments area'";
									$field['Type'] .= ",'followup page'";
									$field['Type'] .= ",'recipient selection'";
									
									if(function_exists('imagecreate')) 
										$field['Type'] .= ",'captcha field'";
									
									$field['Type'] .= ",'spam blocker'";
									$field['Type'] .= ",'page break'";
									$field['Type'] .= ",'reset button'";
									$field['Type'] .= ",'submit button'";
									$field['Type'] .= ",'submit image'";
									$field['Type'] .= ")";

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

									// Alter required data field from text area to enum to allow for selection box.
									$field['Type'] = "enum('|'";

									$field['Type'] .= ",'any text'";
									$field['Type'] .= ",'name'";
									$field['Type'] .= ",'email address'";
									$field['Type'] .= ",'confirm email'";
									$field['Type'] .= ",'phone number'";
									$field['Type'] .= ",'any number'";
									$field['Type'] .= ",'valid url'";
									$field['Type'] .= ",'single word'";
									$field['Type'] .= ",'datestamp (dd/mm/yyyy)'";

									$field['Type'] .= ")";
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
