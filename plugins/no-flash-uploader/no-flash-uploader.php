<?php
/*
Plugin Name: No Flash Uploader
Version: 1.1
Plugin URI: http://dd32.id.au/
Description: Disables the Flash Uploader of 2.5
Author: Dion Hulse
Author URI: http://dd32.id.au/
*/

add_filter('flash_uploader', 'noflashuploader', 5);
function noflashuploader(){
	return false;
}

?>