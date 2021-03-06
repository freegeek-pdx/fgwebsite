<?php
/*
Created by the TruthMedia Internet Group
(website: truthmedia.com       email : webmaster@truthmedia.com)

Plugin Programming and Design by James Warkentin
http://www.warkensoft.com/about-me/

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; version 3 of the License.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
 	function formbuilder_options_default()
 	{
 		global $wpdb, $formbuilder_admin_nav_options;
		$relative_path = FORMBUILDER_PLUGIN_URL;

		if(!formbuilder_user_can('create'))
		{
			formbuilder_admin_alert('You do not have permission to access this area.');
			return;
		}
		
		include(FORMBUILDER_PLUGIN_PATH . "html/options_default.inc.php");
 	}

 	function formbuilder_options_newForm()
 	{
		global $wpdb;
		if(!formbuilder_user_can('create'))
		{
			formbuilder_admin_alert('You do not have permission to access this area.');
			return;
		}
		
		$sql =  "INSERT INTO " . FORMBUILDER_TABLE_FORMS . "(" .
					"`name`," .
					"`subject`," .
					"`recipient`," .
					"`thankyoutext`" .
				") VALUES (" .
					"'" . __('New Form', 'formbuilder') . "', " .
					"'" . __("Generic Website Feedback Form", 'formbuilder') . "', " .
					"'" . get_option('admin_email') . "', " .
					"''" .
				");";
		
		if($wpdb->query($sql) !== false)
		{
			$insert_id = $wpdb->insert_id;
			
			$sql = 	"INSERT INTO `" . FORMBUILDER_TABLE_FIELDS . "` (`form_id`, `display_order`, `field_type`, `field_name`, `field_value`, `field_label`, `required_data`, `error_message`) VALUES " .
					"($insert_id, 1, 'single line text box', 'Name', '', 'Name', 'any text', 'You must enter your name.'), " .
					"($insert_id, 2, 'single line text box', 'Email', '', 'Email', 'email address', 'You must enter your email address.'), " .
					"($insert_id, 3, 'large text area', 'Comments', '', 'Comments', '', '');";
			if($wpdb->query($sql) !== false)
				formbuilder_options_default();
			else
				formbuilder_admin_alert(__("Unable to create new form fields.  Attempted to run the following SQL: ", 'formbuilder'), $sql);
		}
		else
		{
			formbuilder_admin_alert(__("Unable to create new form.  Attempted to run the following SQL: ", 'formbuilder'), $sql);
		}
 	}

 	function formbuilder_options_editForm($form_id)
 	{
 		global $wpdb, $formbuilder_admin_nav_options;
 		
		if(!formbuilder_user_can('create'))
		{
			formbuilder_admin_alert('You do not have permission to access this area.');
			return;
		}
		
 		if(isset($_POST['formbuilder']) AND is_array($_POST['formbuilder']))
		{
			$_POST['formbuilder'] = formbuilder_array_stripslashes($_POST['formbuilder']);
			$_POST['formbuilderfields'] = formbuilder_array_stripslashes($_POST['formbuilderfields']);

			// Verify the data that was posted.
				// No verification currently done on the main form fields.

			// Check to ensure that we can save the form data.  List an error message if not.
			if(false === $wpdb->update(FORMBUILDER_TABLE_FORMS, $_POST['formbuilder'], array('id'=>$form_id))) $errors[] = "ERROR.  Your form failed to save.";

			// Check to see if we have any form fields to save, while making sure there are no existing error messages.
			if(isset($_POST['formbuilderfields']) AND is_array($_POST['formbuilderfields']) AND !isset($errors))
			{
				// Iterate through the form fields, do verification and save them to the database.
				foreach($_POST['formbuilderfields'] as $key => $value)
				{
					// Verify that the field has appropriate data
					$value['field_name'] = clean_field_name($value['field_name']);
					
					// Verify that the field has a field name at all
					if(!$value['field_name'])
					{
						if($value['field_type'] != 'comments area'
						AND $value['field_type'] != 'page break'
						) 
						{
							$errors[] = sprintf(__("ERROR.  You have a field on your form with an empty field name.  The field is a '%s'  All fields MUST have a unique field name.", 'formbuilder'), $value['field_type']);
						}
					}
					
					// Check to ensure that the field name hasn't already been used.
					if($value['field_name'])
					{
						if(!isset($tmp_field_names[$value['field_name']]))
						{
							$tmp_field_names[$value['field_name']] = true;
						}
						else
						{
							$errors[] = __("ERROR.  You have a duplicate field '" . $value['field_name'] . "' on your form.  All field names must be unique.", 'formbuilder');
						}
					}
					
					$result = $wpdb->update(FORMBUILDER_TABLE_FIELDS, $value, array('id'=>$key));
					if(false === $result) $errors[] = __("ERROR.  Problems were detected while saving your form fields.", 'formbuilder');
				}
			}
			if(isset($_POST['fieldAction']) AND is_array($_POST['fieldAction']))
			{
				$fieldAction = $_POST['fieldAction'];
				$fieldKey = key($fieldAction);
				$fieldValue = current($fieldAction);

				if($fieldValue == __('Add New Field', 'formbuilder'))
				{
					if($fieldKey == "newField")
					{	// Create a new field at the end of the form.
						$sql = "SELECT * FROM " . FORMBUILDER_TABLE_FIELDS . " WHERE form_id = $form_id ORDER BY display_order DESC;";
						$relatedRows = $wpdb->get_results($sql, ARRAY_A);
#						$relatedRows = $tableFields->search_rows("$form_id", "form_id", "display_order DESC");
						$actionRow = $relatedRows[0];
						$display_order = $actionRow['display_order'] + 1;

						$wpdb->insert(FORMBUILDER_TABLE_FIELDS, array("form_id"=>"$form_id", "display_order"=>$display_order), array('%d', '%d'));
						$rowID = $wpdb->insert_id;
#						$tableFields->save_row($rowID, array("form_id"=>"$form_id", "display_order"=>$display_order));
					}
					if(!isset($errors)) echo "<meta http-equiv='refresh' content='0;url=#field_$rowID' />";
				}
				if($fieldValue == __("Add Another", 'formbuilder'))
				{
					$sql = "SELECT * FROM " . FORMBUILDER_TABLE_FIELDS . " WHERE id = $fieldKey ORDER BY display_order DESC;";
					$results = $wpdb->get_results($sql, ARRAY_A);
					$actionRow = $results[0];
					#$actionRow = $tableFields->load_row($fieldKey);


					$sql = "SELECT * FROM " . FORMBUILDER_TABLE_FIELDS . " WHERE form_id = $form_id ORDER BY display_order DESC;";
					$relatedRows = $wpdb->get_results($sql, ARRAY_A);
					#$relatedRows = $tableFields->search_rows("$form_id", "form_id");

					foreach($relatedRows as $row)
					{
						#$row = $tableFields->load_row($tableRowID);
						$tableRowID = $row['id'];
						if($row['display_order'] >= $actionRow['display_order'])
						{
							$row['display_order'] = $row['display_order'] + 1;
							$wpdb->update(FORMBUILDER_TABLE_FIELDS, $row, array('id'=>$tableRowID));
							#$tableFields->save_row($tableRowID, $row);
						}
					}

					$wpdb->insert(FORMBUILDER_TABLE_FIELDS, array("form_id"=>"$form_id", "display_order"=>$actionRow['display_order']), array('%d', '%d'));
					$rowID = $wpdb->insert_id;

					#$rowID = $tableFields->create_row();
					#$tableFields->save_row($rowID, array("form_id"=>"$form_id", "display_order"=>$actionRow['display_order']));
					if(!isset($errors)) echo "<meta http-equiv='refresh' content='0;url=#field_$rowID' />";
				}
				if($fieldValue == __("Delete", 'formbuilder'))
				{
#					$actionRow = $tableFields->load_row($fieldKey);
#					$relatedRows = $tableFields->search_rows("$form_id", "form_id", "display_order ASC");
#					$tableFields->remove_row($fieldKey);

					$sql = "SELECT * FROM " . FORMBUILDER_TABLE_FIELDS . " WHERE id = $fieldKey ORDER BY display_order DESC;";
					$results = $wpdb->get_results($sql, ARRAY_A);
					$actionRow = $results[0];

					$sql = "SELECT * FROM " . FORMBUILDER_TABLE_FIELDS . " WHERE form_id = $form_id ORDER BY display_order ASC;";
					$relatedRows = $wpdb->get_results($sql, ARRAY_A);

					$sql = "DELETE FROM " . FORMBUILDER_TABLE_FIELDS . " WHERE id = '$fieldKey';";
					$wpdb->query($sql);
					
					foreach($relatedRows as $row)
					{
#						$row = $tableFields->load_row($tableRowID);
						$tableRowID = $row['id'];
						
						if($row['display_order'] > $actionRow['display_order'])
						{
							$row['display_order'] = $row['display_order'] - 1;
#							$tableFields->save_row($tableRowID, $row);
							$wpdb->update(FORMBUILDER_TABLE_FIELDS, $row, array('id'=>$tableRowID));
						}
					}
					if(!isset($errors)) echo "<meta http-equiv='refresh' content='0;url=#field_" . $relatedRows[0]['id'] . "' />";
				}
				if($fieldValue == __("Move Up", 'formbuilder'))
				{
#					$actionRow = $tableFields->load_row($fieldKey);
#					$relatedRows = $tableFields->search_rows("$form_id", "form_id", "display_order ASC");

					$sql = "SELECT * FROM " . FORMBUILDER_TABLE_FIELDS . " WHERE id = $fieldKey ORDER BY display_order DESC;";
					$results = $wpdb->get_results($sql, ARRAY_A);
					$actionRow = $results[0];

					$sql = "SELECT * FROM " . FORMBUILDER_TABLE_FIELDS . " WHERE form_id = $form_id ORDER BY display_order ASC;";
					$relatedRows = $wpdb->get_results($sql, ARRAY_A);

#					$firstRow = $tableFields->load_row(reset($relatedRows));
					$sql = "SELECT * FROM " . FORMBUILDER_TABLE_FIELDS . " WHERE id = '" . $relatedRows[0]['id'] . "' ORDER BY display_order DESC;";
					$results = $wpdb->get_results($sql, ARRAY_A);
					$firstRow = $results[0];

					$firstPos = $firstRow['display_order'];

					$current_pos = $actionRow['display_order'];

					if($current_pos > $firstPos)
					{
						$current_pos -= 1;
						$actionRow['display_order'] = $current_pos;

						foreach($relatedRows as $row)
						{
#							$row = $tableFields->load_row($tableRowID);
							$tableRowID = $row['id'];

							if($row['display_order'] == $current_pos)
							{
								$row['display_order'] = $row['display_order'] + 1;
#								$tableFields->save_row($tableRowID, $row);
								$wpdb->update(FORMBUILDER_TABLE_FIELDS, $row, array('id'=>$tableRowID));
							}
						}
#						$tableFields->save_row($fieldKey, $actionRow);
						$wpdb->update(FORMBUILDER_TABLE_FIELDS, $actionRow, array('id'=>$fieldKey));
					}
					if(!isset($errors)) echo "<meta http-equiv='refresh' content='0;url=#field_$fieldKey' />";
				}
				if($fieldValue == __("Move Down", 'formbuilder'))
				{
#					$actionRow = $tableFields->load_row($fieldKey);
					$sql = "SELECT * FROM " . FORMBUILDER_TABLE_FIELDS . " WHERE id = $fieldKey ORDER BY display_order DESC;";
					$results = $wpdb->get_results($sql, ARRAY_A);
					$actionRow = $results[0];

#					$relatedRows = $tableFields->search_rows("$form_id", "form_id", "display_order DESC");
					$sql = "SELECT * FROM " . FORMBUILDER_TABLE_FIELDS . " WHERE form_id = $form_id ORDER BY display_order DESC;";
					$relatedRows = $wpdb->get_results($sql, ARRAY_A);


#					$firstRow = $tableFields->load_row(reset($relatedRows));
					$sql = "SELECT * FROM " . FORMBUILDER_TABLE_FIELDS . " WHERE id = '" . $relatedRows[0]['id'] . "' ORDER BY display_order DESC;";
					$results = $wpdb->get_results($sql, ARRAY_A);
					$firstRow = $results[0];

					$lastPos = $firstRow['display_order'];

					$current_pos = $actionRow['display_order'];


					if($current_pos < $lastPos)
					{
						$current_pos += 1;
						$actionRow['display_order'] = $current_pos;

						foreach($relatedRows as $row)
						{
#							$row = $tableFields->load_row($tableRowID);
							$tableRowID = $row['id'];

							if($row['display_order'] == $current_pos)
							{
								$row['display_order'] = $row['display_order'] - 1;
#								$tableFields->save_row($tableRowID, $row);
								$wpdb->update(FORMBUILDER_TABLE_FIELDS, $row, array('id'=>$tableRowID));
							}
						}
#						$tableFields->save_row($fieldKey, $actionRow);
						$wpdb->update(FORMBUILDER_TABLE_FIELDS, $actionRow, array('id'=>$fieldKey));
					}
					if(!isset($errors)) echo "<meta http-equiv='refresh' content='0;url=#field_$fieldKey' />";
				}
			}
			if(isset($_POST['Save']) AND !isset($errors))
			{
				$message = sprintf(__("Your form has been saved.  %sYou may click here to return to the main FormBuilder options page.%s", 'formbuilder'), "<a href='" . FB_ADMIN_PLUGIN_PATH . "'>", "</a>");
			}

		}
		
		if(isset($message)) echo "<div class='updated'><p><strong>$message</strong></p></div>"; 
		if(isset($errors)) 
		{
			foreach($errors as $error) 
			{
				echo "<div class='updated' style='background-color: #FFBBBB; border: 1px solid red; color: red;'><p><strong>$error</strong></p></div>";
			}
		} 

		$sql = "SELECT * FROM " . FORMBUILDER_TABLE_FORMS . " WHERE id = '$form_id';";
		$results = $wpdb->get_results($sql, ARRAY_A);
		$form_fields = $results[0];
		
		foreach($form_fields as $key=>$value)
		{
			$field = array();
			
			$field['Field'] = $key;
			
			if(!isset($_POST['formbuilder'][$key]))
				$field['Value'] = $value;
			else
				$field['Value'] = $_POST['formbuilder'][$key];
				
				
			// Add a brief explanation to specific fields of how to enter the data.
			if($field['Field'] == "name") {
				$field['Title'] = __('What do you want to call this contact form?', 'formbuilder');
				$field['HelpText'] = __('What do you want to call this contact form?', 'formbuilder');
				$field['Type'] = "varchar(255)";
			}

			if($field['Field'] == "subject") {
				$field['Title'] = __('The subject line for the email you receive from the form.', 'formbuilder');
				$field['HelpText'] = __('The subject line for the email you receive from the form.', 'formbuilder');
				$field['Type'] = "varchar(255)";
			}

			if($field['Field'] == "recipient") {
				$field['Title'] = __('What email address should the data from this contact form be mailed to?', 'formbuilder');
				$field['HelpText'] = __('What email address should the data from this contact form be mailed to?', 'formbuilder');
				$field['Type'] = "varchar(255)";
			}

			if($field['Field'] == "method") {
				$field['Title'] = __('How should this form post data?  If you are unsure, leave it on POST', 'formbuilder');
				$field['HelpText'] = __('How should this form post data?  If you are unsure, leave it on POST', 'formbuilder');
				$field['Type'] = "enum(POST,GET)";
			}

			if($field['Field'] == "action") {
				$field['Title'] = __('You may specify an alternate form processing system if necessary.  If you are unsure, leave it alone.', 'formbuilder');
				$field['HelpText'] = __('You may specify an alternate form processing system if necessary.  If you are unsure, leave it alone.', 'formbuilder');
				$field['Type'] = "enum('|" . __('Form to Email - Convert the form results to an email.', 'formbuilder') . "'";


				if(file_exists(FORMBUILDER_PLUGIN_PATH . "/modules"))
				{
					$d = dir(FORMBUILDER_PLUGIN_PATH . "/modules");
					while (false !== ($entry = $d->read())) {
					   if($entry != "." AND $entry != "..") {
					   	$module_filename = FORMBUILDER_PLUGIN_PATH . "/modules/$entry";
					   	if(!is_file($module_filename)) continue;
					   	$module_data = implode("", file($module_filename));

					   	if(eregi("\n\w*name\: ([^\r\n]+)", $module_data, $regs)) {
					   		$module_name = $regs[1];
					   	} else {
					   		$module_name = $entry;
					   	}
					   	$field['Type'] .= ",'$entry|$module_name'";
					   	
					   	if(eregi("\n\w*instructions\: ([^\r\n]+)", $module_data, $regs)) {
					   		$module_instructions = "\\n\\n" . addslashes($regs[1]);
					   	} else {
					   		$module_instructions = "";
					   	}
					   	$field['HelpText'] .= $module_instructions;
					   	
					   }
					}
					$d->close();
				}
				$field['Type'] .= ")";
			}

			if($field['Field'] == "thankyoutext") {
				$field['Title'] = __('What message would you like to show your visitors?', 'formbuilder');
				$field['HelpText'] = __('What message would you like to show your visitors when the successfully complete the form?', 'formbuilder');
				$field['Type'] = "text";
			}

			if($field['Field'] == "autoresponse") {
				$field['Title'] = __('You may specify an autoresponse to send back if necessary.', 'formbuilder');
				$field['HelpText'] = __('You may specify an autoresponse to send back if necessary.  You should have alread created them on the main FormBuilder Management page.', 'formbuilder');
				$field['Type'] = "enum('|'";

				$sql = "SELECT * FROM " . FORMBUILDER_TABLE_RESPONSES . ";";
				$response_ids = $wpdb->get_results($sql, ARRAY_A);
#				$response_ids = $tableResponses->list_rows();
				if($response_ids) foreach($response_ids as $response_data)
				{
#					$response_data = $tableResponses->load_row($response_id);
					$field['Type'] .= ",'" . $response_data['id'] . "|" . $response_data['name'] . "'";
				}
				$field['Type'] .= ")";
			}

			$fields[$key] = $field;
	
		}


		$all_field_types = array(
			'single line text box'=>__("Standard single line text box.", 'formbuilder'),
			'small text area'=>__("Small multi-line text box.", 'formbuilder'),
			'large text area'=>__("Large multi-line text box.", 'formbuilder'),
			'password box'=>__("Used for password entry.  Characters are hidden.", 'formbuilder'),
			'datestamp'=>__("Date selection field.", 'formbuilder'),
			'unique id'=>__("Put a unique ID on your forms.", 'formbuilder'),
			'checkbox'=>__("Single check box.", 'formbuilder'),
			'radio buttons'=>__("Radio selection buttons.  Enter one per line in the field value.", 'formbuilder'),
			'selection dropdown'=>__("Dropdown box.  Enter one value per line in the field value.", 'formbuilder'),
			'hidden field'=>__("A hidden field on the form.  The data will appear in the email.", 'formbuilder'),
			'comments area'=>__("Special field just for text on the form.  Put the text in the field value.", 'formbuilder'),
			'followup page'=>__("Special field just for indicating a followup url, once the form has been submitted.  Put the url you want people to be redirected to in the field value.", 'formbuilder'),
			'recipient selection'=>__("A special selection dropdown allowing the visitor to specify an alternate form recipient.  Enter values in the form of email@domain.com|Destination Name.", 'formbuilder'),
			'spam blocker'=>__("Special field on the form.  Read more on the FormBuilder admin page.  Only needs a field name.", 'formbuilder'),
			'page break'=>__("Allows you to break your form into multiple pages.  Needs field name and field label.", 'formbuilder'),
			'reset button'=>__("Allows you to put a customized reset button anywhere on the form.  Needs field name and field label.", 'formbuilder'),
			'submit button'=>__("Allows you to put a customized submit button anywhere on the form.  Needs field name and field label.", 'formbuilder'),
			'submit image'=>__("Allows you to put a customized submit image anywhere on the form.  Needs field name and field label.  Field label must be the PATH TO THE IMAGE to be used for the submit button.", 'formbuilder'),
		);
		if(function_exists('imagecreate')) 
			$all_field_types['captcha field'] = __("Special field on the form for displaying CAPTCHAs.  Field name is used for identifying the field.  Field label is used to give the visitor further instruction on what to fill out.", 'formbuilder');

		$all_required_types = array(
			'any text'=>__("Requires some sort of text in this field.", 'formbuilder'),
			'name'=>__("Requires a name.", 'formbuilder'),
			'email address'=>__("Requires an email address.", 'formbuilder'),
			'confirm email'=>__("Requires an email address", 'formbuilder'),
			'phone number'=>__("Requires a phone number.", 'formbuilder'),
			'any number'=>__("Requires some sort of number.", 'formbuilder'),
			'valid url'=>__("Requires a valid URL.", 'formbuilder'),
			'single word'=>__("Requires a single word.", 'formbuilder'),
			'datestamp (dd/mm/yyyy)'=>__("Requires a date stamp in the form of (dd/mm/yyyy)", 'formbuilder'),
		);
			
		ksort($all_field_types);
		ksort($all_required_types);

		include(FORMBUILDER_PLUGIN_PATH . "html/options_edit_form.inc.php");
 	}

 	function formbuilder_options_copyForm($form_id)
 	{
		global $wpdb, $formbuilder_admin_nav_options;
		
		if(!formbuilder_user_can('create'))
		{
			formbuilder_admin_alert('You do not have permission to access this area.');
			return;
		}
		
		// Duplicate the main form table row
		$sql = "SELECT * FROM " . FORMBUILDER_TABLE_FORMS . " WHERE id = '$form_id' LIMIT 0,1;";
		$form_data = $wpdb->get_results($sql, ARRAY_A);
		$form_data = $form_data[0];
		
		unset($form_data['id']);
		$form_data['name'] .= __(" (COPY)", 'formbuilder');
		
		$result = $wpdb->insert(FORMBUILDER_TABLE_FORMS, $form_data);
		$new_form_id = $wpdb->insert_id;
		
		// Duplicate all fields on the form, assigning them to the newly created form table row
		$sql = "SELECT * FROM " . FORMBUILDER_TABLE_FIELDS . " WHERE form_id = '$form_id';";
		$related = $wpdb->get_results($sql, ARRAY_A);
		foreach($related as $field)
		{
			unset($field['id']);
			$field['form_id'] = $new_form_id;
			$result = $wpdb->insert(FORMBUILDER_TABLE_FIELDS, $field);
		}

		formbuilder_options_default();
 	}

	function formbuilder_options_removeForm($form_id)
	{
		global $wpdb, $formbuilder_admin_nav_options;
		
		if(!formbuilder_user_can('create'))
		{
			formbuilder_admin_alert('You do not have permission to access this area.');
			return;
		}
		
		$sql = "DELETE FROM " . FORMBUILDER_TABLE_FORMS . " WHERE id = '$form_id';";
		$wpdb->query($sql);
		
		$sql = "SELECT * FROM " . FORMBUILDER_TABLE_FIELDS . " WHERE form_id = '$form_id';";
		$related = $wpdb->get_results($sql, ARRAY_A);
		if($related) foreach($related as $field)
		{
			$sql = "DELETE FROM " . FORMBUILDER_TABLE_FIELDS . " WHERE id = '" . $field['id'] . "';";
			$wpdb->query($sql);
		}
	}
	
	
	function formbuilder_options_settings()
 	{
 		global $wpdb, $formbuilder_admin_nav_options;
		$relative_path = FORMBUILDER_PLUGIN_URL;
		
		if(!formbuilder_user_can('manage'))
		{
			formbuilder_admin_alert('You do not have permission to access this area.');
			return;
		}
		
		include(FORMBUILDER_PLUGIN_PATH . "html/options_settings.inc.php");
 	}

	
	

	function formbuilder_options_strings()
 	{
 		global $wpdb, $formbuilder_admin_nav_options;
 		
		if(!formbuilder_user_can('manage'))
		{
			formbuilder_admin_alert('You do not have permission to access this area.');
			return;
		}
		
 		$formBuilderTextStrings = formbuilder_load_strings();
		
		if(isset($_POST['formbuilder_reset_all_text_strings']) AND $_POST['formbuilder_reset_all_text_strings'] == 'yes')
		{
			delete_option('formbuilder_text_strings');
			$formBuilderTextStrings = formbuilder_load_strings();
		}
		elseif($_POST) foreach($formBuilderTextStrings as $key=>$value)
		{
			if($_POST[$key])
			{
				$formBuilderTextStrings[$key] = htmlentities(stripslashes($_POST[$key]), ENT_QUOTES, get_option('blog_charset'));
			}
			update_option('formbuilder_text_strings', $formBuilderTextStrings);
		}
 		
		$relative_path = FORMBUILDER_PLUGIN_URL;
		include(FORMBUILDER_PLUGIN_PATH . "html/options_strings.inc.php");
 	}

	
	

	// Function to display individual form fields on an HTML page.  $field_info should contain an array describing the field, including any data associated with it.
	function formbuilder_display_form_field($field_info, $prefix = "formbuilder", $template_before = "<div style='padding: 1px 0 2px 20px;'>", $template_mid = ": ", $template_after = "</div>\n")
	{
		$field_name = strtoupper(str_replace("_", " ", $field_info['Field']));
		$field_data = htmlentities($field_info['Value'], ENT_QUOTES, get_option('blog_charset'));

		if(isset($field_info['HelpText'])) $helpText = ' <a href="javascript:;" onClick="alert(\'' . $field_info['HelpText'] . '\');">?</a> ';
		if(isset($helpText)) $template_after = $helpText . $template_after;
		
		if(!isset($field_info['Title'])) $field_info['Title'] = ""; 

		if(eregi("[a-z]+\(([0-9]+)\)", $field_info['Type'], $regs))
		{
			if($regs[1] > 50)  $size = 50;
			else $size = $regs[1];
			$field_details = "<input " .
						"name='" . $prefix . "[" . $field_info['Field'] . "]' " .
						"id='" . $field_info['Field'] . "' " .
						"type='text' " .
						"size='$size' " .
						"maxlength='$regs[1]' " .
						"value='$field_data' " .
						"alt='" . $field_info['Title'] . "' " .
						"title='" . $field_info['Title'] . "' " .
					"/>";
		}

		elseif(eregi("enum\((.+)\)", $field_info['Type'], $regs))
		{
			$enum_values = explode(",", $regs[1]);

			$field_details = "<select " .
						"name='" . $prefix . "[" . $field_info['Field'] . "]' " .
						"id='" . $field_info['Field'] . "' " .
						"alt='" . $field_info['Title'] . "' " .
						"title='" . $field_info['Title'] . "' " .
					">\n";
			foreach($enum_values as $value)
			{
				$value = str_replace("'", "", $value);

				// Check whether or not keys were passed along with the values.
				if(strpos($value, "|") !== false)
				{
					list($key, $value) = explode("|", $value);

				}
				else
					$key = $value;

				if($key == $field_data) $select = "selected";
				else $select = "";

				$field_details .= "<option value='$key' $select>$value</option>\n";
			}
			$field_details .= "</select>";
		}
		elseif(eregi("blob", $field_info['Type']) OR eregi("text", $field_info['Type']) OR eregi("longtext", $field_info['Type']))
		{
			$blob_cols = 52;

			$blob_rows =substr_count(wordwrap($field_data, $blob_cols), "\n");

			if($blob_rows > 30) $blob_rows = 30;
			if($blob_rows <= 2) $blob_rows = 2;

			$field_details = "<textarea " .
						"name='" . $prefix . "[" . $field_info['Field'] . "]' " .
						"id='" . $field_info['Field'] . "' " .
						"cols='$blob_cols' " .
						"rows='$blob_rows' " .
						"alt='" . $field_info['Title'] . "' " .
						"title='" . $field_info['Title'] . "' " .
					">\n$field_data</textarea>";
		}
		else
		{
			$field_details = "Field type not found!";
			print_r($field_info); echo $template_after;

		}

		// Output the actual field data
		echo $template_before . "<span class='formbuilderLabel'>" . $field_name . $template_mid . "</span>" . "<span class='formbuilderField'>$field_details</span>" . $template_after;
	}

	function formbuilder_array_stripslashes($slash_array = array())
	{
		if($slash_array)
		{
			foreach($slash_array as $key=>$value)
			{
				if(is_array($value))
				{
					$slash_array[$key] = formbuilder_array_stripslashes($value);
				}
				else
				{
					$slash_array[$key] = stripslashes($value);
				}
			}
		}
		return($slash_array);
	}
