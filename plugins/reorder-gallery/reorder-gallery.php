<?php
/**
 * Plugin Name: Reorder Gallery
 * Plugin URI: http://xavisys.com/wordpress-reorder-gallery-plugin/
 * Description: Lets you reorder gallery items with the ease of drag and drop. Requires PHP5
 * Version: 1.0.1
 * Author: Aaron D. Campbell
 * Author URI: http://xavisys.com/
 */

/**
 * Changelog:
 * 05/15/2008: 1.0.1
 * 	- A problem in the interface jQuery plugin kept the inputs from being clicked to focus.  Fixed by making file name "handle" to move by.
 *
 * 05/15/2008: 1.0.0
 * 	- Added the drag and drop functionality
 *
 * 05/02/2008: 0.1.0
 * 	- Added to Wordpress.org
 *
 * 04/29/2008: 0.0.1
 * 	- Original Version
 */

/*  Copyright 2006  Aaron D. Campbell  (email : wp_plugins@xavisys.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
/**
 * wpReorderGallery is the class that handles ALL of the plugin functionality.
 * It helps us avoid name collisions
 * http://codex.wordpress.org/Writing_a_Plugin#Avoiding_Function_Name_Collisions
 */

class wpReorderGallery
{
    /**
     * Enqueues JavaScript
     *
     */
    public function __construct()
    {
    	//To work with JS, the items need position:static; which they don't have
        $jsDir = get_option('siteurl') . '/wp-content/plugins/reorder-gallery/js/';

        wp_register_script('reorderGallery', "{$jsDir}reorder-gallery.js", array( 'interface' ), '20080531');
    }

    public function addJS() {
    	wp_enqueue_script('reorderGallery');
    }

    public function attachment_fields_to_edit($form_fields, $post) {
    	$form_fields['menu_order'] = array(
			'label'      => __('Order'),
			'value'      => $post->menu_order
		);
    	return $form_fields;
    }
    public function attachment_fields_to_save($post, $attachment) {
    	$post['menu_order'] = $attachment['menu_order'];
    	return $post;
    }
}
$wpReorderGallery = new wpReorderGallery();
add_action('media_upload_gallery', array($wpReorderGallery, 'addJS'));
add_filter('attachment_fields_to_edit', array($wpReorderGallery, 'attachment_fields_to_edit'), null, 2);
add_filter('attachment_fields_to_save', array($wpReorderGallery, 'attachment_fields_to_save'), null, 2);