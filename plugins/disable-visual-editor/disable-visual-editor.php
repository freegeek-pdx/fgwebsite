<?php

/*
Plugin Name: Disable Visual Editor
Plugin URI: http://www.callum-macdonald.com/code/disable-visual-editor/
Description: This plugin disables the visual editor for all users, every time a user updates their profile. If you also remove the wp-includes/js/tinymce/ directory this will remove the option in user's profiles and completely disable the visual editor (this directory also needs to be removed after each version upgrade).
Author: Callum Macdonald
Version: 1.0
Author URI: http://www.callum-macdonald.com/
*/

// This plugin is released under the GPL version 3 or later available at
// http://www.gnu.org/licenses/gpl.txt

/**
 * Disable the visual editor for all users
 * 
 * We can ignore the passed $userID because we're disabling for all users.
 */
function disable_visual_editor($userID) {
	
	global $wpdb;
	
	$wpdb->query("UPDATE `" . $wpdb->prefix . "usermeta` SET `meta_value` = 'false' WHERE `meta_key` = 'rich_editing'");
	
}

/**
 * Add an action to catch profile updates and new users.
 */

add_action('profile_update','disable_visual_editor');
add_action('user_register','disable_visual_editor');

?>