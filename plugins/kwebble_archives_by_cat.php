<?php 
/*
Plugin Name: Archives for a category 
Plugin URI: http://kwebble.com/blog/2007_08_15/archives_for_a_category
Description: Adds a cat parameter to wp_get_archives() to limit the posts used to generate the archive links to one or more categories.   
Author: Rob Schlüter
Author URI: http://kwebble.com/
Version: 1.3

Copyright
=========
Copyright 2007, 2008 Rob Schlüter. All rights reserved.

Licensing terms
===============
- You may use, change and redistribute this software provided the copyright notice above is included. 
- This software is provided without warranty, you use it at your own risk. 

Installation
============
1. Put this file in your WordPress plugin directory, <your wordpress dir>/wp-content/plugins.
2. Activate the plugin 'Archives for a category' on the Plugin Management page of the WordPress administration panel.
3. Optionally disable the canonical URLs from the menu Options | Kwebble.

Usage
=====
After installing and activating the plugin the wp_get_archives() function accepts a 'cat' 
parameter. Its value must be one or more category ID's, separated by comma's, of the 
categories to include in building the list of archives.

For example, to show the default monthly list of archives for category 1 put this in a template:

<?php wp_get_archives('cat=1'); ?>

The same list, but with posts from categories 1 and 3:

<?php wp_get_archives('cat=1,3'); ?>

To create a list of archives for category 1 as a dropdown box use:

<?php wp_get_archives('format=option&cat=1'); ?>

For a complete description of all parameters see the documentation of wp_get_archives().

Now you need to make sure the template used to show each archive displays posts from the selected 
category. I'm using category specific templates on this site, like category-id where id is the ID 
of the category to display.
You can use other templates in the template hierarchy, but make sure the template shows items of 
that category.

Limitations
===========
This plugin does not work for weekly archives. The list with archive links is correct, but the 
links themselves do not include the category. So when used, WordPress will not filter the resulting 
page on the category. The technical reason is that WordPress does not apply filters when the links 
for weekly archives are generated, so the plugin can't change them. Perhaps this is fixed in a next 
version of WordPress.

A WordPress feature added in version 2.3, called canonical URLs, redirects browsers on certain 
URLs. This also happens with the URLs for the archives with a cat parameter. This causes the 
archive pages to contain posts which do not belong to the selected period.

To solve this problem the plugin can disable canonical URLs. Go to the administration section of 
your blog, choose the Options tab and then the Kwebble subtab. On that page you will find a 
setting to disable canonical URLs.
This is using the same technique used in the 
<a href="http://txfx.net/files/wordpress/disable-canonical-redirects.phps">Disable Canonical URL Redirection plugin</a> 
Mark Jaquith made.

This plug-in was developed and tested to work correctly with WordPress versions 2.2.1 and 2.3.1. It 
probably also works with 2.3. and 2.3.2.

History
=======
Version 1.0 - Initial version, works with WordPress 2.2.1.
Version 1.2 - Added support for WordPress 2.3.1. 
Version 1.3 - Added support for multiple categories. 
*/

/**
 * Changes the WHERE clause for wp_get_archives to select only posts for the 
 * categories in the 'cat' parameter.
 * 
 * @param String $where a SQL WHERE clause.
 * @param Array	$args arguments passed to the wp_get_archives() function.
 * @return String modified SQL WHERE clause with additional selection by 
 * category if $args contains a parameter called cat.
 */
function kwebble_getarchives_where_for_category($where, $args){
	global $kwebble_getarchives_data;

	if (isset($args['cat'])){
		// Preserve the category for later use.
		$kwebble_getarchives_data['cat'] = $args['cat'];

		if (get_bloginfo('version') < 2.3){
			$where .= " AND post2cat.category_id in ($args[cat])";
		} else{
			$where .= " AND term_taxonomy.taxonomy = 'category' AND term_taxonomy.term_id in ($args[cat])";
		}
	}

	return $where;
}

/**
 * Changes the JOIN clause for wp_get_archives to join the tables with posts and those with 
 * category information.
 * 
 * @param String $join a SQL JOIN clause.
 * @param Array	$args arguments passed to the wp_get_archives() function.
 * @return String modified SQL JOIN clause joining the posts and category data if $args contains a
 * parameter called cat.
 */
function kwebble_getarchives_join_for_category($join, $args){
	global $wpdb;

	if (isset($args['cat'])){
		if (get_bloginfo('version') < 2.3){
			$join .= " JOIN $wpdb->post2cat post2cat ON post2cat.post_id=ID";
		} else{
			$join .= " JOIN $wpdb->term_relationships term_relationships ON term_relationships.object_id = $wpdb->posts.ID"
				. " JOIN $wpdb->term_taxonomy term_taxonomy ON term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id";
		}
	}

	return $join;
}

/**
 * Changes the archive link to include the categories from the 'cat' parameter.
 * 
 * @param String $url the generated URL for an archive.
 * @return String modified URL that includes the category. 
 */
function kwebble_archive_link_for_category($url){
	global $kwebble_getarchives_data;

	if (isset($kwebble_getarchives_data['cat'])){
		$url .= strpos($url, '?') === false ? '?' : '&';
		$url .= 'cat=' . $kwebble_getarchives_data['cat'];
	}

	return $url;
}

/*
 * Add the filters.
 */

// Prevent error if executed outside WordPress.
if (function_exists('add_filter')){
	// Constants for form field and options. 
	define('KWEBBLE_OPTION_DISABLE_CANONICAL_URLS', 'kwebble_disable_canonical_urls');
	define('KWEBBLE_GETARCHIVES_FORM_CANONICAL_URLS', 'kwebble_disable_canonical_urls');
	define('KWEBBLE_ENABLED', '');
	define('KWEBBLE_DISABLED', 'Y');

	add_filter('getarchives_where', 'kwebble_getarchives_where_for_category', 10, 2);
	add_filter('getarchives_join', 'kwebble_getarchives_join_for_category', 10, 2);

	add_filter('year_link', 'kwebble_archive_link_for_category');
	add_filter('month_link', 'kwebble_archive_link_for_category');
	add_filter('day_link', 'kwebble_archive_link_for_category');

	// Disable canonical URLs if the option is set.
	if (get_option(KWEBBLE_OPTION_DISABLE_CANONICAL_URLS) == KWEBBLE_DISABLED){
		remove_filter('template_redirect', 'redirect_canonical');
	}
}

/*
 * Option settings.
 */

// Hook for adding admin menu.
add_action('admin_menu', 'kwebble_getarchives_admin_pages');

// Action function for above hook.
function kwebble_getarchives_admin_pages() {
	// Add a new submenu under Options:
	add_options_page('Kwebble', 'Kwebble', 'manage_options', 'kwebble_options', 'kwebble_getarchives_admin_options_page');
}

// Show the admin form.
function kwebble_getarchives_admin_options_page() {
	$value = get_option(KWEBBLE_OPTION_DISABLE_CANONICAL_URLS);
	$kwebble_disable_canonical_urls = $value === false ? KWEBBLE_ENABLED : $value;
	$checked = $kwebble_disable_canonical_urls == KWEBBLE_DISABLED ? ' checked="checked"' : ''; 
	?>

	<div class="wrap">
		<h2>Kwebble get_archives with category</h2>
		<form method="post" action="options.php">
			<p class="submit">
				<input type="submit" name="Submit" value="<?php _e('Update Options &raquo;') ?>" />
			</p>

			<p>
				<?php wp_nonce_field('update-options') ?>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="<?php echo KWEBBLE_GETARCHIVES_FORM_CANONICAL_URLS; ?>" />

				The <a href="http://markjaquith.wordpress.com/2007/09/25/wordpress-23-canonical-urls/">canonical URLs</a> 
				feature introduced with WordPress version 2.3, may interfere with this plugin. If that happens the archive 
				pages contain posts outside the archive period. To solve this problem you can disable canonical URLs.<br/>
			</p>

			<p>
				<label><input type="checkbox" name="<?php echo KWEBBLE_GETARCHIVES_FORM_CANONICAL_URLS; ?>" value="<?php echo KWEBBLE_DISABLED; ?>" <?php echo $checked; ?> /> Disable canonical URLs.</label> 
			</p>

			<p class="submit">
				<input type="submit" name="Submit" value="<?php _e('Update Options &raquo;') ?>" />
			</p>			
		</form>
	</div>

	<?php    
}
?>