<?php
/*
Plugin Name: No Flash Uploader
Version: 1.1.1
Plugin URI: http://dd32.id.au/
Description: Disables the WordPress Flash Uploader
Author: Dion Hulse
Author URI: http://dd32.id.au/
*/

add_filter('flash_uploader', '__return_false', 5);