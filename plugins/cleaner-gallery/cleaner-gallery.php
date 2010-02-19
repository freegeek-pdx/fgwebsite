<?php

/*

Plugin Name: Cleaner Gallery
Plugin URI: http://justintadlock.com/archives/2008/04/13/cleaner-wordpress-gallery-plugin
Description: This plugin replaces the default gallery feature in WordPress 2.5+ with a valid XHTML solution and offers multiple Lightbox-type image scripts support.
Version: 0.3.3
Author: Justin Tadlock
Author URI: http://justintadlock.com
License: GPL

*************************===Notes===******************************
* This update is for WordPress 2.5.1, which fixes some major issues.
* This file takes the original gallery and outputs a cleaner valid XHTML gallery.
* Caption links to attachment page if set in the image uploader.
* If Lightbox-type scripts are off, the image links to the attachment page.

*** Tested with:
* Lightbox 2 - http://www.huddletogether.com/projects/lightbox2/
* Slimbox - http://www.digitalia.be/software/slimbox
* Thickbox - http://jquery.com/demo/thickbox/
* Lytebox - http://dolem.com/lytebox/
* Greybox - http://orangoo.com/labs/GreyBox/
* Lightview - http://www.nickstakenburg.com/projects/lightview/
* jQuery Lightbox Plugin (balupton edition) - http://www.balupton.com/sandbox/jquery_lightbox/
* jQuery Lightbox plugin - http://leandrovieira.com/projects/jquery/lightbox/
* Shutter Reloaded - http://www.laptoptips.ca/projects/wp-shutter-reloaded/

*************************===Code===******************************
*/

// Auto load Thickbox (included with WP 2.5)?
	// add_action('wp_head', 'thickbox_css');
	// wp_enqueue_script('thickbox');

/************************************************
Create our new gallery shortcode based off the original
************************************************/
function jt_gallery_shortcode($attr) {
global $post;
/************************************************
Begin user-defined variables
************************************************/
// Lightbox or Slimbox?
// jQuery Lightbox plugin(s)?
	$a_rel = "lightbox[cleaner-gallery-$post->ID]";
	$a_class = "lightbox";

// Shutter?
	// $a_rel = "lightbox[cleaner-gallery-$post->ID]";
	// $a_class = "shutterset_cleaner-gallery-$post->ID";

// Lytebox?
	// $a_rel = "lytebox[cleaner-gallery-$post->ID]";
	// $a_class = "lytebox";

// Greybox?
	// $a_rel = "gb_imageset[cleaner-gallery-$post->ID]";
	// $a_class = "greybox";

// Thickbox?
	// $a_rel = "clean-gallery-$post->ID";
	// $a_class = "thickbox";

// Lightview?
	// $a_rel = "gallery[cleaner-gallery-$post->ID]";
	// $a_class = "lightview";

// Show caption link?
	$cap_link = true;

// Always show captions (use title if caption isn't defined)?
	$cap_always = true;
/************************************************
End user-defined variables
************************************************/

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}
	extract(shortcode_atts(array(
		'orderby' => 'menu_order ASC, ID ASC',
		'id' => $post->ID,
		'itemtag' => 'dl',
		'icontag' => 'dt',
		'captiontag' => 'dd',
		'columns' => 3,
		'size' => 'thumbnail',
	), $attr));

	$id = intval($id);
	$attachments = get_children("post_parent=$id&post_type=attachment&post_mime_type=image&orderby={$orderby}");

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $id => $attachment )
			$output .= wp_get_attachment_link($id, $size, true) . "\n";
		return $output;
	}

	$listtag = tag_escape($listtag);
	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;

// Remove the style output in the middle of the freakin' page
// This needs to be added to the header (width applied through CSS but limits it a bit)

// Open gallery
	$output = apply_filters('gallery_style', "<div class='gallery gallery-$post->ID'>");

// Loop through each gallery item
	foreach ( $attachments as $id => $attachment ) {
	// Larger image URL (Lightbox/Thickbox)
		$a_img = wp_get_attachment_url($id);
	// Attachment page ID
		$att_page = get_attachment_link($id);
	// Returns array
		$img = wp_get_attachment_image_src($id, $size);
		$img = $img[0];
	// If no caption is defined, set the title and alt attributes to title
		$title = $attachment->post_excerpt;
		if($title == '') $title = $attachment->post_title;

	// Output each gallery item
		$output .= "\n<{$itemtag} class='gallery-item col-$columns'>\n";
		$output .= "<{$icontag} class='gallery-icon'>\n";

	// If using Lightbox, set the link to the img URL
	// Else, set the link to the attachment URL
		if($a_rel == true) $link = $a_img;
		elseif($a_class == true) $link = $a_img;
		else $link = $att_page;
		$output .= "\t<a href=\"$link\" title=\"$title\" class=\"$a_class\" rel=\"$a_rel\">";
	// Output image
		$output .= "<img src=\"$img\" alt=\"$title\" />";
	// Close link
		$output .= "</a>";
		$output .= "\n</{$icontag}>";
	// Check if user wants to always show the caption
		if($cap_always == true) $caption = $attachment->post_title;
		else $caption = $attachment->post_excerpt;
	// Show caption
		if($captiontag && $caption) {
			$output .= "\n<$captiontag class='gallery-caption'>\n\t";
	// Caption link? (defaults to true)
			if($cap_link == true)
				$output .= '<a href="'.$att_page.'" title="'.$caption.'">'.$caption.'</a>';
			else
				$output .= $caption;
		// Close caption tag
			$output .= "</$captiontag>";
		}
	// Close individual gallery item
		$output .= "\n</{$itemtag}>";
		if($columns > 0 && ++$i % $columns == 0)
			$output .= '<div style="clear:both;" class="clear"><!-- --></div>';
	}
// Close gallery
	$output .= "\n</div>\n";
	return $output;
}

/************************************************
Function for outputting the CSS
************************************************/
function jt_gallery_css () {
	global $site_url;
	$css = get_bloginfo('wpurl') . '/wp-content/plugins/cleaner-gallery/cleaner-gallery.css';
	$css = '<link rel="stylesheet" href="'.$css.'" type="text/css" media="screen" />';
	$css = "<!-- User is using the Cleaner WP Gallery plugin version 0.3.3 -->\n$css\n";
	echo $css;
}

/************************************************
Function for outputting the Thickbox CSS
************************************************/
function thickbox_css() {
	$css = get_bloginfo('wpurl')."/wp-includes/js/thickbox/thickbox.css";
	$css = '<link rel="stylesheet" href="'.$css.'" type="text/css" media="screen" />';
	$css = "<!-- User is using Thickbox -->\n$css\n";
	echo $css;
}

/************************************************
Important stuff that runs this thing
************************************************/

// Remove original gallery shortcode
	remove_shortcode(gallery);

// Add a new shortcode
	add_shortcode('gallery', 'jt_gallery_shortcode');

// Get the CSS required and it to blog head
	add_action('wp_head', 'jt_gallery_css');
?>
