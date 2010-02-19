<?php
/*
Plugin Name: Page Tree
Plugin URI: http://www.mansjonasson.se/wppagetree
Description: Display Wordpress pages in a collapsible tree structure for better overview
Version: 1.4
Author: Måns Jonasson
Author URI: http://www.mansjonasson.se
*/

/*
Dual licensed under the MIT and GPL licenses:
http://www.opensource.org/licenses/mit-license.php
http://www.gnu.org/licenses/gpl.html

Developed for .SE (Stiftelsen för Internetinfrastruktur) - http://www.iis.se
*/

add_action( 'init', 'pagetree_init' );
add_action('admin_menu', 'pagetree_menu');


// Initialize this plugin. Called by 'init' hook.
function pagetree_init()
{
	load_plugin_textdomain( 'pagetree', 'wp-content/plugins/page-tree' );
}

// Add menu option to "Pages" menu
function pagetree_menu() {
	add_submenu_page('edit-pages.php', __("Wordpress Page Tree", "page-tree"), __("Page tree", "page-tree"), 5, __FILE__, 'pagetree_options');
}



function pagetree_options() {
	echo '<div class="wrap">';
	
	?>
	
	
	<script src="<?php bloginfo('wpurl')?>/wp-content/plugins/page-tree/lib/jquery.cookie.js" type="text/javascript"></script>
	<script src="<?php bloginfo('wpurl')?>/wp-content/plugins/page-tree/lib/jquery.treeview.js" type="text/javascript"></script>
	<link rel="stylesheet" href="<?php echo bloginfo('wpurl')?>/wp-content/plugins/page-tree/lib/jquery.treeview.css" />
	<link rel="stylesheet" href="<?php echo bloginfo('wpurl')?>/wp-content/plugins/page-tree/page-tree.css" />
	
	<script type="text/javascript">
	<!--

	jQuery(document).ready(function(){

		// Init this page tree, with #treecontrol and cookie memory
		jQuery("#navigation").treeview({
			control: "#treecontrol",
			persist: "cookie",
			animated: "fast",
			cookieId: "treeview-navigation"
		});

	});
	//-->
	</script>
	
	
	<h2><?php echo __("Page tree", "page-tree")?></h2>
	
	
	<?php

	// Get ugly, CSS-class-messy WP-list of all pages
	$pages = wp_list_all_pages("echo=0&title_li=&link_before=&link_after=");
	
	if (strlen($pages)) {
		
	?>
	
	<div id="treecontrol">
		<a class="button" title="<?php echo __("Collapse the entire tree below", "page-tree")?>" href="#"><?php echo __("Collapse All", "page-tree")?></a>
		<a class="button" title="<?php echo __("Expand the entire tree below", "page-tree")?>" href="#"><?php echo __("Expand All", "page-tree")?></a>
		<a class="button" title="<?php echo __("Toggle the tree below, opening closed branches, closing open branches", "page-tree")?>" href="#"><?php echo __("Toggle All", "page-tree")?></a>
	</div>

	
	<ul id="navigation">
	
	<?php

	echo pagetree_make_tree($pages);

	?>
	</ul>
	
	
	
	<?php

	echo '</div>';
	}

}

function pagetree_make_tree($pages) {
			// Split into messy array
		$pageAr = explode("\n", $pages);

		foreach($pageAr AS $txt) {

			$out = "";

			$re1='.*?';	# Non-greedy match on filler
			$re2='(\\d+)';	# Integer Number 1

			// regexp match out all page IDs
			if ($c=preg_match_all ("/".$re1.$re2."/is", $txt, $matches))
			{ // This is a line with a page
				$int1=$matches[1][0];

				$pageID = $int1;

				// Get post status (publish|pending|draft|private|future)
				$thisPage = get_page($pageID);
				$pageStatus = $thisPage -> post_status;
				
				if ($pageStatus != "publish") {
					$pageStatus = "strikethrough";	
				}
				
				// Get page title
				$pageTitle = trim(strip_tags($txt));
				
				// Make sure we don't display empty page titles
				if ($pageTitle == "") $pageTitle = __("(no title)", "page-tree");

				$linesAr[$pageID] = $pageTitle;
				if (stristr($txt, "<li class")) { // This is a line with beginning LI
					$out .= "<li>";
				}

				// Create our own link to edit page for this ID
				$out .= "<a class=\"$pageStatus\" href=\"" . get_bloginfo('wpurl') . "/wp-admin/page.php?action=edit&post=$pageID\">" . $pageTitle . "</a>";

				if (stristr($txt, "</li>")) { // This is a line with an ending LI
					$out .= "</li>";
				}

				$outAr[] = $out;


			}
			else { // This is a line with something else than a page (<ul>, </ul>, etc) - just add it to the pile
				$outAr[] = $txt;
			}

			// Keep all lines in $origAr just in case we want to check things again in the future
			$origAr[] = $txt;

		}

		// Print the new, pretty UL-LI by joining the array
		return join("\n", $outAr);	
}

/**
 * Retrieve a list of pages.
 *
 * The defaults that can be overridden are the following: 'child_of',
 * 'sort_order', 'sort_column', 'post_title', 'hierarchical', 'exclude',
 * 'include', 'meta_key', 'meta_value', and 'authors'.
 *
 * @since 1.5.0
 * @uses $wpdb
 *
 * @param mixed $args Optional. Array or string of options that overrides defaults.
 * @return array List of pages matching defaults or $args
 */
function &get_all_pages($args = '') {
	global $wpdb;

	$defaults = array(
		'child_of' => 0, 'sort_order' => 'ASC',
		'sort_column' => 'post_title', 'hierarchical' => 1,
		'exclude' => '', 'include' => '',
		'meta_key' => '', 'meta_value' => '',
		'authors' => '', 'parent' => -1, 'exclude_tree' => ''
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	$key = md5( serialize( compact(array_keys($defaults)) ) );
	if ( $cache = wp_cache_get( 'get_pages', 'posts' ) ) {
		if ( isset( $cache[ $key ] ) ) {
			$pages = apply_filters('get_pages', $cache[ $key ], $r );
			return $pages;
		}
	}

	$inclusions = '';
	if ( !empty($include) ) {
		$child_of = 0; //ignore child_of, parent, exclude, meta_key, and meta_value params if using include
		$parent = -1;
		$exclude = '';
		$meta_key = '';
		$meta_value = '';
		$hierarchical = false;
		$incpages = preg_split('/[\s,]+/',$include);
		if ( count($incpages) ) {
			foreach ( $incpages as $incpage ) {
				if (empty($inclusions))
					$inclusions = $wpdb->prepare(' AND ( ID = %d ', $incpage);
				else
					$inclusions .= $wpdb->prepare(' OR ID = %d ', $incpage);
			}
		}
	}
	if (!empty($inclusions))
		$inclusions .= ')';

	$exclusions = '';
	if ( !empty($exclude) ) {
		$expages = preg_split('/[\s,]+/',$exclude);
		if ( count($expages) ) {
			foreach ( $expages as $expage ) {
				if (empty($exclusions))
					$exclusions = $wpdb->prepare(' AND ( ID <> %d ', $expage);
				else
					$exclusions .= $wpdb->prepare(' AND ID <> %d ', $expage);
			}
		}
	}
	if (!empty($exclusions))
		$exclusions .= ')';

	$author_query = '';
	if (!empty($authors)) {
		$post_authors = preg_split('/[\s,]+/',$authors);

		if ( count($post_authors) ) {
			foreach ( $post_authors as $post_author ) {
				//Do we have an author id or an author login?
				if ( 0 == intval($post_author) ) {
					$post_author = get_userdatabylogin($post_author);
					if ( empty($post_author) )
						continue;
					if ( empty($post_author->ID) )
						continue;
					$post_author = $post_author->ID;
				}

				if ( '' == $author_query )
					$author_query = $wpdb->prepare(' post_author = %d ', $post_author);
				else
					$author_query .= $wpdb->prepare(' OR post_author = %d ', $post_author);
			}
			if ( '' != $author_query )
				$author_query = " AND ($author_query)";
		}
	}

	$join = '';
	$where = "$exclusions $inclusions ";
	if ( ! empty( $meta_key ) || ! empty( $meta_value ) ) {
		$join = " LEFT JOIN $wpdb->postmeta ON ( $wpdb->posts.ID = $wpdb->postmeta.post_id )";

		// meta_key and meta_value might be slashed
		$meta_key = stripslashes($meta_key);
		$meta_value = stripslashes($meta_value);
		if ( ! empty( $meta_key ) )
			$where .= $wpdb->prepare(" AND $wpdb->postmeta.meta_key = %s", $meta_key);
		if ( ! empty( $meta_value ) )
			$where .= $wpdb->prepare(" AND $wpdb->postmeta.meta_value = %s", $meta_value);

	}

	if ( $parent >= 0 )
		$where .= $wpdb->prepare(' AND post_parent = %d ', $parent);

	//$query = "SELECT * FROM $wpdb->posts $join WHERE (post_type = 'page' AND post_status = 'publish') $where ";
	$query = "SELECT * FROM $wpdb->posts $join WHERE (post_type = 'page') $where ";
	$query .= $author_query;
	$query .= " ORDER BY " . $sort_column . " " . $sort_order ;

	$pages = $wpdb->get_results($query);

	#if ( empty($pages) ) {
	#	$pages = apply_filters('get_pages', array(), $r);
	#	return $pages;
	#}

	// Update cache.
	#update_page_cache($pages);

	if ( $child_of || $hierarchical )
		$pages = & get_page_children($child_of, $pages);

	if ( !empty($exclude_tree) ) {
		$exclude = array();

		$exclude = (int) $exclude_tree;
		$children = get_page_children($exclude, $pages);
		$excludes = array();
		foreach ( $children as $child )
			$excludes[] = $child->ID;
		$excludes[] = $exclude;
		$total = count($pages);
		for ( $i = 0; $i < $total; $i++ ) {
			if ( in_array($pages[$i]->ID, $excludes) )
				unset($pages[$i]);
		}
	}

	#$cache[ $key ] = $pages;
	#wp_cache_set( 'get_all_pages', $cache, 'posts' );

	#$pages = apply_filters('get_pages', $pages, $r);

	return $pages;
}

/**
 * Retrieve or display list of pages in list (li) format.
 *
 * @since 1.5.0
 *
 * @param array|string $args Optional. Override default arguments.
 * @return string HTML content, if not displaying.
 */
function wp_list_all_pages($args = '') {
	$defaults = array(
		'depth' => 0, 'show_date' => '',
		'date_format' => get_option('date_format'),
		'child_of' => 0, 'exclude' => '',
		'title_li' => __('Pages'), 'echo' => 1,
		'authors' => '', 'sort_column' => 'menu_order, post_title',
		'link_before' => '', 'link_after' => ''
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	$output = '';
	$current_page = 0;

	// sanitize, mostly to keep spaces out
	$r['exclude'] = preg_replace('[^0-9,]', '', $r['exclude']);

	// Allow plugins to filter an array of excluded pages
	$r['exclude'] = implode(',', apply_filters('wp_list_pages_excludes', explode(',', $r['exclude'])));

	// Query pages.
	$r['hierarchical'] = 0;
	$pages = get_all_pages($r);

	if ( !empty($pages) ) {
		if ( $r['title_li'] )
			$output .= '<li class="pagenav">' . $r['title_li'] . '<ul>';

		global $wp_query;
		if ( is_page() || $wp_query->is_posts_page )
			$current_page = $wp_query->get_queried_object_id();
		$output .= walk_page_tree($pages, $r['depth'], $current_page, $r);

		if ( $r['title_li'] )
			$output .= '</ul></li>';
	}

	#$output = apply_filters('wp_list_pages', $output);

	if ( $r['echo'] )
		echo $output;
	else
		return $output;
}

?>
