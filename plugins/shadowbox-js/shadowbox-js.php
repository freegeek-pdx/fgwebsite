<?php
/*
Plugin Name: Shadowbox JS
Plugin URI: http://sivel.net/category/wordpress/plugins/
Description: Used as a media-viewer script for web pages that allows content to be viewed without navigating away from the original linking page.  Similar to Lightbox or Thickbox.
Version: 0.4
Author:  Matt Martz <mdmartz@sivel.net>
Author URI: http://sivel.net

        Copyright (c) 2008 Matt Martz (http://sivel.net)
        Shadowbox JS is released under the GNU Lesser General Public License (LGPL)
        http://www.gnu.org/licenses/lgpl.txt

        This is a WordPress 2 plugin (http://wordpress.org).

        Shadowbox (c) 2008 Michael J. I. Jackson (http://mjijackson.com/shadowbox)
	Shadowbox is licensed under the GNU Lesser General Public License (LGPL)
        http://www.gnu.org/licenses/lgpl.txt
*/

/**
 * Specify the Javascript library you wish to use.
 * 
 * Possible values: 'yui', 'prototype', 'jquery', 'ext', 'dojo' or 'mootools'
 * The default is 'mootools'
 *
 * @var string
 **/
$jsLib = 'mootools';

/**
 * Specifies whether or not to load the javascript library specified above.
 * This is helpful if your theme or another plugin you are using already 
 * includes a javascript library in the headers.  You should specify the same 
 * library above that the theme or plugin uses so Shadowbox knows how to work.  
 * Usually jquery or prototype are used.
 * 
 * Set to '0' to let this script insert the javascript into the header or '1' to 
 * allow your theme or other plugin to handle that.  if you have tried letting 
 * your theme handle this and this plugin is not working try setting $noLib back 
 * to '0'
 *
 * @var boolean
 **/
$noLib = '0';

/**
 * Specify which style you wish to use.
 *
 * Set to '0' to use shadowbox default style and '1' to use lightbox like style.
 *
 * @var boolean
 **/
$lightCSS = '0';

/* No Need To Modify Anything Below This Line */

/** 
 * Time to setup our variables for the URL to your blog and to the plugin as 
 * well as some variables to shorten the amount of characters I have to type
 * later on
 **/
$sbSiteUrl = get_option('siteurl');
$sbPluginUrl = $sbSiteUrl.'/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/';

$sbPre = '<script type="text/javascript"';
$sbPost = '</script>';

$sbCSS = '<link rel="stylesheet" type="text/css" href="' . $sbPluginUrl . 'css/shadowbox';
$initOps = 'assetURL: \'' . $sbPluginUrl . '\'';
if ($lightCSS) :
	$sbCSS .= '-light';
	$initOps .= ',' . "\n";
	$initOps .= '			loadingImage: \'images/loading-light.gif\',' . "\n";
	$initOps .= '			overlayBgImage: \'images/overlay-85.png\'';
endif;
$sbCSS .= '.css" />' . "\n";
$sbCSS .= '<link rel="stylesheet" type="text/css" href="' . $sbPluginUrl . 'css/extras.css" />';

$sbInit = $sbPre . '>' . "\n";
$sbInit .= '	window.onload = function(){' . "\n"; 
$sbInit .= '		var conf = {' . "\n";
$sbInit .= '			' . $initOps . "\n";
$sbInit .= '		};' . "\n";
$sbInit .= '		Shadowbox.init(conf);' . "\n";
$sbInit .= '	};' . "\n";
$sbInit .= $sbPost;

$sbJS = $sbPre . ' src="' . $sbPluginUrl . 'js/adapter/shadowbox-' . $jsLib  . '.js">' . $sbPost . "\n";
$sbJS .= $sbPre . ' src="' . $sbPluginUrl . 'js/shadowbox.js">' . $sbPost . "\n";
$sbJS .= $sbInit;

/**
 * This function is called by the add_action WordPress function
 *
 * This information is inserted into the HTML headers as the page loads
 *
 * @return string
 **/
function shadowbox_headers() {
	global $jsLib;
	$sbBegin = "\n" . '<!-- Begin Shadowbox JS -->' . "\n";
	$sbEnd = '<!-- End Shadowbox JS -->' . "\n\n";
	$sbHeader = new sbGenHead;
	print($sbBegin . $sbHeader->$jsLib() . $sbEnd);
}

/**
 * This is our class to make building the code to insert into the headers
 * for the different javascript libraries easier
 *
 * @return string
 **/
class sbGenHead {
        function yui() {
                global $sbCSS, $noLib, $sbJS, $sbPre, $sbPost, $sbPluginUrl;
                $output = $sbCSS . "\n";
		if (!$noLib)
	                $output .= $sbPre . ' src="' . $sbPluginUrl . 'js/yui-utilities.js">' . $sbPost . "\n";
                $output .= $sbJS . "\n";
                return $output;
        }
        function prototype() {
                global $sbCSS, $noLib, $sbJS, $sbPre, $sbPost, $sbSiteUrl, $sbPluginUrl;
                $output = $sbCSS . "\n";
		if (!$noLib) :
	                $output .= $sbPre . ' src="' . $sbSiteUrl . '/wp-includes/js/scriptaculous/prototype.js">' . $sbPost . "\n";
        	        $output .= $sbPre . ' src="' . $sbSiteUrl . '/wp-includes/js/scriptaculous/scriptaculous.js?load=effects">' . $sbPost . "\n";
		endif;
                $output .= $sbJS . "\n";
                return $output;
        }
        function jquery() {
                global $sbCSS, $noLib, $sbJS, $sbPre, $sbPost, $sbSiteUrl, $sbPluginUrl;
                $output = $sbCSS . "\n";
		if (!$noLib) :
	                $output .= $sbPre . ' src="' . $sbSiteUrl . '/wp-includes/js/jquery/jquery.js">' . $sbPost . "\n";
        	        $output .= $sbPre . ' src="' . $sbSiteUrl . '/wp-includes/js/jquery/interface.js">' . $sbPost . "\n";
		endif;
                $output .= $sbJS . "\n";
                return $output;
        }
        function ext() {
                global $sbCSS, $noLib, $sbJS, $sbPre, $sbPost, $sbPluginUrl;
                $output = $sbCSS . "\n";
		if (!$noLib) :
	                $output .= $sbPre . ' src="' . $sbPluginUrl . 'js/ext-base.js">' . $sbPost . "\n";
			$output .= $sbPre . ' src="' . $sbPluginUrl . 'js/ext-core.js">' . $sbPost . "\n";
		endif;
                $output .= $sbJS . "\n";
                return $output;
        }
        function dojo() {
                global $sbCSS, $noLib, $sbJS, $sbPre, $sbPost, $sbPluginUrl;
                $output = $sbCSS . "\n";
		if (!$noLib)
	                $output .= $sbPre . ' src="' . $sbPluginUrl . 'js/dojo.js">' . $sbPost . "\n";
                $output .= $sbJS . "\n";
                return $output;
        }
        function mootools() {
                global $sbCSS, $noLib, $sbJS, $sbPre, $sbPost, $sbPluginUrl;
                $output = $sbCSS . "\n";
		if (!$noLib)
	                $output .= $sbPre . ' src="' . $sbPluginUrl . 'js/mootools.js">' . $sbPost . "\n";
                $output .= $sbJS . "\n";
                return $output;
        }
}

/**
 * This function will call the shadowbox_headers function above during page loads.
 **/
add_action('wp_head', 'shadowbox_headers');
?>
