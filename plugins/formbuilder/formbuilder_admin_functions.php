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

	// Allow showing an alert to the user when necessary.
	function formbuilder_admin_alert($msg = '', $msg2 = '')
	{
		if($msg2 AND $msg) echo "<div class='updated'><p><strong>$msg</strong><br/>$msg2</p></div>";
		elseif($msg) echo "<div class='updated'><p><strong>$msg</strong></p></div>";
	}

	function formbuilder_admin_css()
	{
		?>
		<link rel='stylesheet' href='<?php echo FORMBUILDER_PLUGIN_URL; ?>formbuilder_styles_admin.css' type='text/css' media='all' />
		<?php
	}

	function formbuilder_options_page($action=""){
		global $wpdb, $fbdbg;
		
		$version = get_option('formbuilder_version');

		// Determine and set path to current formbuilder page.
		$path = $_SERVER['REQUEST_URI'];
		
		$path_length = strpos($path, FORMBUILDER_FILENAME) + strlen(FORMBUILDER_FILENAME);
		
		$path = substr($path, 0, strpos($path, '?')) . '?page=' . FORMBUILDER_FILENAME;

		define("FB_ADMIN_PLUGIN_PATH", $path);

		if($version != FORMBUILDER_VERSION_NUM)
		{	// FormBuilder is NOT set up correctly with the proper version number.  Rerun the activation script.
			formbuilder_activation();
		}

		$version = get_option('formbuilder_version');


		?>


		<div id="icon-tools" class="icon32"><br></div>
		<div class="wrap">
			<h2><?php _e('FormBuilder Management', 'formbuilder'); ?> (v <?php echo $version; ?>)</h2>
			<strong><?php _e('Navigation', 'formbuilder'); ?>:</strong> <a href="<?php echo FB_ADMIN_PLUGIN_PATH; ?>"><?php _e('FormBuilder', 'formbuilder'); ?></a>

		<?php
		if(!isset($_GET['fbaction'])) $_GET['fbaction'] = false;
		switch($_GET['fbaction']) {

			case "newForm":
				formbuilder_options_newForm();
			break;

			case "editForm":
				formbuilder_options_editForm($_GET['fbid']);
			break;

			case "copyForm":
				formbuilder_options_copyForm($_GET['fbid']);
			break;

			case "removeForm":
				formbuilder_options_removeForm($_GET['fbid']);
				formbuilder_options_default();
			break;

			case "newResponse":
				formbuilder_options_newResponse();
			break;

			case "editResponse":
				formbuilder_options_editResponse($_GET['fbid']);
			break;

			case "copyResponse":
				formbuilder_options_copyResponse($_GET['fbid']);
			break;

			case "removeResponse":
				formbuilder_options_removeResponse($_GET['fbid']);
				formbuilder_options_default();
			break;

			case "formResults":
				if(!isset($results_page)) $results_page = new formbuilder_xml_db_results();
				$results_page->show_adminpage();
			break;

			case "uninstall":
				if(!isset($_GET['confirm']))
					formbuilder_cleaninstall(false);
				else
					formbuilder_cleaninstall($_GET['confirm']);
			break;

			default:
				formbuilder_options_default();
			break;

		}
		?>

		</div>

		<?php

	}



?>