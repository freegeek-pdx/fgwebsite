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
if(!class_exists('formbuilder_xml_db_results')) {
class formbuilder_xml_db_results 
{
	
	// Class variables
	var $result_limit = 10;

	
	// Constructor
	function formbuilder_xml_db_results()
	{
		define("STD_DATE", "n/j/Y");
	}
	
	// Methods
	function show_adminpage()
	{
 		global $formbuilder_admin_nav_options;
		
		if(!isset($_GET['fbxmlaction'])) $_GET['fbxmlaction'] = '';
		
		switch($_GET['fbxmlaction'])
		{
			case "showexport":
				$this->show_export();
			break;
			
			case "showemail":
				$this->show_email($_GET['fbxmlid']);
			break;
			
			default:
				$this->list_results();
			break;
		}
	}
	
	function show_dashboard()
	{
	?>
	<div class="info-box-formbuilder postbox">
		<h3 class="info-box-title hndle"><?php _e('Database XML Backup', 'formbuilder'); ?></h3>
		<div class="inside">
		<p><?php _e('FormBuilder is now able to store XML copies of all submitted form data in the MySQL database.  If you wish to enable this feature, select the checkbox below and click Save.', 'formbuilder'); ?></p>
		<?php
			if(isset($_POST['formbuilder_db_xml_submit']) AND $_POST['formbuilder_db_xml_submit'])
			{
				if(isset($_POST['formBuilder_xml_backup']) AND $_POST['formBuilder_xml_backup'] == '1')
					update_option('formbuilder_db_xml', '1');
				else
					update_option('formbuilder_db_xml', '0');
			}		
			$xml_backup = get_option('formbuilder_db_xml');
		?>
		<form action="" method="POST">
			<?php _e('Save form data in XML format in the database', 'formbuilder'); ?>: <input type="checkbox" name="formBuilder_xml_backup" value="1" <?php if($xml_backup == '1') echo "checked='checked'"; ?>/> <?php _e('Yes!', 'formbuilder'); ?>
			<input type="submit" name="formbuilder_db_xml_submit" value="<?php _e('Save', 'formbuilder'); ?>" />
		</form>
		<p><a href="<?php echo FB_ADMIN_PLUGIN_PATH; ?>&fbaction=formResults"><?php _e('Click here to manage the forms currently stored in your database.', 'formbuilder'); ?></a></p>
		<p><a href="<?php echo FB_ADMIN_PLUGIN_PATH; ?>&fbaction=formResults&fbxmlaction=showexport"><?php _e('Click here to export form results to CSV.'); ?></a></p>
		</div>
	</div>
	<?php
	}
	
	function show_export()
	{
		global $wpdb;
		
		?>
		<?php formbuilder_admin_nav('formResults'); ?>
		<fieldset class="options metabox-holder">
			<div class="info-box-formbuilder postbox">
				<h3 class="info-box-title hndle"><?php _e('Export Data:', 'formbuilder'); ?> </h3>
				<div class="fbxml-form-export inside">
				
					<form action='<?php echo FORMBUILDER_PLUGIN_URL;?>formbuilder_export_results.php' method='POST'>
						<?php _e('Please select the date range you wish to export data from:', 'formbuilder'); ?><br/><br/>
						<?php _e('From:', 'formbuilder'); ?> <?php $this->input_date('date_from', date(STD_DATE, time()-(3600*24*30))); ?><br/>
						<?php _e('To:', 'formbuilder'); ?> <?php $this->input_date('date_to', date(STD_DATE, time())); ?><br/><br/>
		
						<?php _e('Select the form(s) you would like to export data from:', 'formbuilder'); ?><br/>
						<select name='form_id'>
							<option value=''><?php _e('All Forms (non-standard CSV format)', 'formbuilder'); ?></option>
							<?php 
								$sql = 'SELECT * FROM ' . FORMBUILDER_TABLE_FORMS . ' ORDER BY name ASC;';
								$forms = $wpdb->get_results($sql, ARRAY_A);
								foreach($forms as $form)
								{
									$sql = "SELECT id FROM " . FORMBUILDER_TABLE_RESULTS . " WHERE form_id = '" . $form['id'] . "';";
									$result = $wpdb->get_col($sql, ARRAY_A);
									$total_rows = count($result);
							
									echo "<option value='" . $form['id'] . "'>" . $form['name'] . "(" . $total_rows . ")</option>";
								}
							?>
						</select><br/><br/>
						<input type='submit' name='Submit' value='<?php _e('Export', 'formbuilder'); ?>' />
					</form>
				
				</div>
			</div>
		</fieldset>
		<?php
		
	}
	
	function show_email($email_id)
	{
		global $wpdb;
		if(!eregi('^[0-9]+$', $email_id)) $error = "Invalid email ID";
		
		if(!isset($error))
		{
			$sql = "SELECT * FROM " . FORMBUILDER_TABLE_RESULTS . " WHERE id='$email_id' ORDER BY timestamp DESC LIMIT 0," . $this->result_limit . ";";
			$result = $wpdb->get_row($sql, ARRAY_A);
			if($result == false) $error = "No email found with that ID.";
			$form_data = $this->xmltoarray($result['xmldata']);
		}
		?>
		<?php formbuilder_admin_nav('formResults'); ?>
		<h3><?php _e('Form Details', 'formbuilder'); ?>: </h3>
		<div class="fbxml-form-details">
		<?php
			if(isset($error))	echo $error;
			else
			{
				foreach($form_data['form'] as $key=>$value) 
					echo strtoupper($key) . ": " . nl2br($value . "\n\n");
			}
		?>
		</div>
		
		<?php
	}
	
	function list_results()
	{
		global $wpdb;
		
		
		if(isset($_POST['formResultSelected']) AND isset($_POST['formResultSelectedAction']))
		{
			switch($_POST['formResultSelectedAction'])
			{
				case 'Delete':
					if(is_array($_POST['formResultSelected']))
					{
						$selected = $_POST['formResultSelected'];
						
						foreach($selected as $formResultID)
						{
							if(is_numeric($formResultID) AND preg_match('/^[0-9]+$/isu', $formResultID))
							{
								$sql = "DELETE FROM " . FORMBUILDER_TABLE_RESULTS . " WHERE id = '" . $formResultID . "' LIMIT 1;";
								$result = $wpdb->query($sql);
							}
							else
								echo "Invalid form result ID detected: $formResultID<br/>\n";
						}
					}
				break;
				
				case 'Export':
					if(is_array($_POST['formResultSelected']))
					{
						$selected = $_POST['formResultSelected'];
						
						foreach($selected as $formResultID)
						{
							if(is_numeric($formResultID) AND preg_match('/^[0-9]+$/isu', $formResultID))
							{
								$export_ids[] = $formResultID;
							}
							else
								echo "Invalid form result ID detected: $formResultID<br/>\n";
						}
						
						$export_ids_string = implode(",", $export_ids);
						$url = FORMBUILDER_PLUGIN_URL . "formbuilder_export_results.php?formResults=$export_ids_string";
						echo "<meta HTTP-EQUIV='REFRESH' content='0; url=" . $url . "'>";
					}
				break;
				
				default:
				break;
			}
		}
		
		
		// Check to see if we should display multiple pages.
		if(isset($_GET['paged']) AND eregi("^[0-9]+$", $_GET['paged'])) $result_page = $_GET['paged'];
		else $result_page = 1;
		
				?>
		<?php formbuilder_admin_nav('formResults'); ?>
		<fieldset class="options metabox-holder">
			<div class="info-box-formbuilder postbox">
				<h3 class="info-box-title hndle"><?php _e('Recent Form Results:', 'formbuilder'); ?> </h3>
						
						<?php
		
					$sql = "SELECT id FROM " . FORMBUILDER_TABLE_RESULTS . ";";
					$result = $wpdb->get_col($sql, ARRAY_A);
					$total_rows = count($result);
		
				// Iterate through the results and display them line by line.
				echo "<form action='' method='POST'><table class='widefat'>";
				echo "<tr class='fbexporttable'>" .
						"<td>&nbsp;</td>" .
						"<td><strong>" . __("Date:", 'formbuilder') . "</strong></td>" .
						"<td>" .
						"<span class='fbexport'>" .
						"<a href='" . FB_ADMIN_PLUGIN_PATH . "&fbaction=formResults&fbxmlaction=showexport'>" . __("Export", 'formbuilder') . "</a>" .
						"</span>" .
						"<strong>" . __("Message:", 'formbuilder') . "</strong>" .
						"</td>" .
					"</tr>";
				
				for($i=0; $i<$this->result_limit; $i++)
				{
					$sql_offset = $this->result_limit * ($result_page-1);
					$sql = "SELECT * FROM " . FORMBUILDER_TABLE_RESULTS . " ORDER BY timestamp DESC LIMIT $sql_offset," . $this->result_limit . ";";
					$result = $wpdb->get_row($sql, ARRAY_A, $i);
					if($result == false) break;
					$form_data = $this->xmltoarray($result['xmldata']);
					
					$message = "";
					foreach($form_data['form'] as $key=>$value) $message .= strtoupper($key) . ": " . $value . "\n";
					if(strlen($message) > 80) $message = substr($message, 0, 80) . "...";
		
					echo "<tr>" .
							"<td><input type='checkbox' name='formResultSelected[]' value='" . $result['id'] . "'/></td>" .
							"<td><a href='" . FB_ADMIN_PLUGIN_PATH . "&fbaction=formResults" .
							"&fbxmlaction=showemail&fbxmlid=" . $result['id'] . "'>" . 
							date("F j, Y, g:i a", $result['timestamp']) . "</a></td>" .
							"<td>" . __("Message:", 'formbuilder') . $message . "</td>" .
						"</tr>";
				}
				
				$curpos = $sql_offset+$this->result_limit;
				
				echo "<tr>";
				if($sql_offset > 0)
					echo "<td align='left'><a href='" . FB_ADMIN_PLUGIN_PATH . "&fbaction=formResults&paged=" . ($result_page - 1) . "'>&lt;-- " . __("previous", 'formbuilder') . "</a></td>";
				else
					echo "<td align='left'>&nbsp;</td>";
				
				echo "<td align='left'>&nbsp;</td>";
				
				if($curpos < $total_rows)
					echo "<td align='right'><a href='" . FB_ADMIN_PLUGIN_PATH . "&fbaction=formResults&paged=" . ($result_page + 1) . "'>" . __("next", 'formbuilder') . " --&gt;</a></td>";
				else
					echo "<td align='right'>&nbsp;</td>";
				echo "</tr>";
				
				echo "<tr><td colspan=3 align='left'>" .
						"With Selected: <select name='formResultSelectedAction'>" .
							"<option value=''></option>" .
							"<option value='Export'>Export</option>" .
							"<option value='Delete'>Delete</option>" .
						"</select>" .
						" <input type='submit' value='Go' /></td></tr>";
				
				echo "</table></form>";
				?>
			</div>
		</fieldset>		
		<?php
	}
	
	function export_csv()
	{
		global $wpdb;
		
		$specific_form = false;
		$where = "WHERE 1";
		
		// Configure the Where clause depending on posted data.
		if(isset($_POST['date_from']) AND isset($_POST['date_to']))
		{
			$timestamp_from = $this->output_date($_POST['date_from'], false);
			$timestamp_to = $this->output_date($_POST['date_to'], true);
			
			$where .= " AND timestamp > '$timestamp_from' AND timestamp < '$timestamp_to'";
		}

		if(isset($_POST['form_id']) AND $_POST['form_id'] != "" AND eregi('^[0-9]+$', $_POST['form_id']))
		{
			$form_id = addslashes(trim($_POST['form_id']));
			$specific_form = true;
			$where .= " AND form_id = '" . $form_id . "'";
		}
		
		if(isset($_GET['formResults']))
		{
			$formIDs = explode(",", $_GET['formResults']);
			$where .= " AND (";
			$first = true;
			foreach($formIDs as $form_id)
			{
				if(eregi('^[0-9]+$', $form_id))
				{
					$form_id = addslashes(trim($form_id));
					if(!$first) $where .= " OR";
					$where .= " id = '" . $form_id . "'";
				}
				$first = false;
			}
			
			$where .= " ) ";
		}
		
		
		// Set headers
		
		header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=forms.csv");
		header("Content-Type: application/csv");
		header("Content-Transfer-Encoding: text");
		
		// Create the first line of the CSV export with field labels if necessary.
		echo '"' . __('Result ID', 'formbuilder') . '","' . __('Form ID', 'formbuilder') . '","' . __('Timestamp', 'formbuilder') . '"';
		echo ',"' . __('FormSubject', 'formbuilder') . '","' . __('FormRecipient', 'formbuilder') . '"';

		if($specific_form == true AND $form_id > 0)
		{
			$field_list = array();
			$field_list[] = 'FormSubject';
			$field_list[] = 'FormRecipient';
			$sql = "SELECT * FROM " . FORMBUILDER_TABLE_FIELDS . " WHERE form_id = '" . $form_id . "' ORDER BY display_order ASC;";
			$fields = $wpdb->get_results($sql, ARRAY_A);
			if(isset($fields) AND $fields !== false) foreach($fields as $field)
			{
				if(trim($field['field_name']) != "")
				{
					$field_list[] = $field['field_name'];
					echo ',"' . $field['field_name'] . '"';
				}
			}
			else
				echo ',"' . __('Field Values', 'formbuilder') . '"';

			$field_list[] = 'IP';
			$field_list[] = 'Page';
			$field_list[] = 'Referrer';

			echo ',"' . __('IP', 'formbuilder') . '"';
			echo ',"' . __('Page', 'formbuilder') . '"';
			echo ',"' . __('Referrer', 'formbuilder') . '"';
			
		}
		else
		{
			echo ',"' . __('Field Values', 'formbuilder') . '"';
		}
		
		echo "\r\n";
		
		
		$i = 0;
		 
		do
		{
			$sql_offset = $this->result_limit;
			$sql = "SELECT * FROM " . FORMBUILDER_TABLE_RESULTS . " $where ORDER BY timestamp DESC;";
			$result = $wpdb->get_row($sql, ARRAY_A, $i);
			
			if($result === false OR $result == "") break;
			
			$form_data = $this->xmltoarray($result['xmldata']);
			
			echo $result['id'];
			echo ',' . $result['form_id'];
			echo ',"' . date("F j, Y, g:i a", $result['timestamp']) . '"';
			

			if($specific_form == true AND $field_list)
			{
				foreach($field_list as $key)
				{
					if(isset($form_data['form'][$key]))
						$value = $form_data['form'][$key];
					else
						$value = "";
					
					echo ',"' . str_replace('"', '""', decode_html_entities($value, ENT_NOQUOTES, get_option('blog_charset'))) . '"';
				}
			} else {
				foreach($form_data['form'] as $key=>$value)
				{
					if($specific_form == true)
						$key_insert = '';
					else
						$key_insert = $key . ': ';
					echo ',"' . $key_insert . str_replace('"', '""', decode_html_entities($value, ENT_NOQUOTES, get_option('blog_charset'))) . '"';
				}
			}
			echo "\r\n";
			$i++;
		} while($result != false);
	}
	
	function xmltoarray($xml)
	{
		$xml = trim($xml);
		
		$match = "#<([a-z0-9_]+)([ \"']*[a-z0-9_ \"']*)>(.*)(</\\1>)#si";
		$offset = 0;
		
		if(!preg_match($match, $xml, $regs, false, $offset)) {
			return($xml);
		}
		
		while(preg_match($match, $xml, $regs, false, $offset))
		{
			list($data, $element, $attribs, $content, $closing) = $regs;
			$offset = strpos($xml, $data) + strlen($data);
			
			$tmp = $this->xmltoarray($content);
			$result[$element] = $tmp;
			
		}
		
		return($result);
	}

	function output_date($datestring, $endofday=false)
	{
		if($endofday == true)
			$timestamp = mktime(23, 59, 59, $datestring['month'], $datestring['day'], $datestring['year']);
		else
			$timestamp = mktime(0, 0, 0, $datestring['month'], $datestring['day'], $datestring['year']);
		
		return($timestamp);
	}
	
	function input_date($field_name, $date=false, $future=false)
	{
		if($date === false) $date = date(STD_DATE);

		$use_date = explode("/", $date);
		?>
				<select name="<?php echo $field_name; ?>[month]" class="unformatted">
					<option></option>
					<?php for($i=1; $i<=12; $i++)
						{
							if($i == $use_date[0]) $selected = " selected";
							else $selected = "";
							$long_month = date("F", mktime(1,1,1,$i,1,2007));
							?>
								<option value="<?php echo $i; ?>"<?php echo $selected; ?>><?php echo $long_month; ?></option>
							<?php
						}
					?>
				</select>
				<select name="<?php echo $field_name; ?>[day]" class="unformatted">
					<option></option>
					<?php for($i=1; $i<=31; $i++)
						{
							if($i == $use_date[1]) $selected = " selected";
							else $selected = "";
							?>
								<option value="<?php echo $i; ?>"<?php echo $selected; ?>><?php echo $i; ?></option>
							<?php
						}
					?>
				</select>,
				<select name="<?php echo $field_name; ?>[year]" class="unformatted">
					<option></option>
					<?php
						$year = date("Y");
						if($future === false)
						{
							for($i=$year; $i>=$year-100; $i--)
							{
								if($i == $use_date[2]) $selected = " selected";
								else $selected = "";
								?>
									<option value="<?php echo $i; ?>"<?php echo $selected; ?>><?php echo $i; ?></option>
								<?php
							}
						}
						else
						{
							for($i=$year; $i<=$year+100; $i++)
							{
								if($i == $use_date[2]) $selected = " selected";
								else $selected = "";
								?>
									<option value="<?php echo $i; ?>"<?php echo $selected; ?>><?php echo $i; ?></option>
								<?php
							}
						}
					?>
				</select>
		<?php
	}


}
}
?>