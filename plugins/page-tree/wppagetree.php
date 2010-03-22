<?php
/*
Plugin Name: Page Tree
Plugin URI: http://www.mansjonasson.se/wppagetree
Description: Display Wordpress pages in a collapsible tree structure for better overview
Version: 2.8
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

add_action('admin_head', 'pagetree_head', 1);

add_action("plugins_loaded", "pagetree_init");

add_action('wp_footer', 'pagetree_head');

// Initialize this plugin. Called by 'init' hook.
function pagetree_init() {

	$plugin_dir = basename(dirname(__FILE__)); 
	
	pagetree_load_locale();
	
	wp_register_sidebar_widget( 'page-tree', __("Page Tree"), 'pagetree_widget', array('description' => __('Display a hierarchically structured tree of your pages.')) );
	wp_register_widget_control( 'page-tree', __("Page Tree"), 'pagetree_widget_control');	
	
	if (is_admin()) {
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-tabs');
		
		wp_enqueue_script('jquery-cookie', plugins_url('lib/jquery.cookie.js', __FILE__));
		wp_enqueue_script('jquery-treeview', plugins_url('lib/jquery.treeview.js', __FILE__));
	
		wp_enqueue_style( 'page-tree-treeview', plugins_url('lib/jquery.treeview.css', __FILE__), false, false, 'all' );
		wp_enqueue_style( 'page-tree-css', plugins_url('page-tree.css', __FILE__), false, false, 'all' );
	}
}

function pagetree_load_locale() {
    $locale = get_locale();
    if( empty( $locale ) )
    	$locale = 'en_US';

    $mofile = dirname( __FILE__ )."/locale/$locale.mo";
    load_textdomain( "page-tree", $mofile );
}


// Load JS/CSS in the HTML head of page with tree
function pagetree_head() {
	global $add_my_script;
	
	if (!$add_my_script) return;
	
	wp_enqueue_script('jquery', false, false, false, true);
    wp_enqueue_script('jquery-ui-core', false, false, false, true);
    wp_enqueue_script('jquery-ui-tabs', false, false, false, true);
    
    wp_register_script('jquery-cookie', plugins_url('lib/jquery.cookie.js', __FILE__), array('jquery'), "1.0", true);
    wp_register_script('jquery-treeview', plugins_url('lib/jquery.treeview.js', __FILE__), array('jquery'), "1.0", true);
    
    wp_print_scripts('jquery-cookie');
    wp_print_scripts('jquery-treeview');

}

function pagetree_print_styles() {
	/** For now, remove the enqueue_style to instead print the CSS directly into the tree. Ugly, but compatible **/
	#wp_enqueue_style( 'page-tree-treeview', plugins_url('lib/jquery.treeview.css', __FILE__), false, false, 'all' );
    #wp_enqueue_style( 'page-tree-css', plugins_url('page-tree.css', __FILE__), false, false, 'all' );
    echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . plugins_url('lib/jquery.treeview.css', __FILE__) . "\" />\n";
}

// [pagetree expand=false show_control=false only_subpages=false child_of=false]
function pagetree_func($atts) {
	global $add_my_script;	
	$add_my_script = true;

	extract(shortcode_atts(array(
		'expand' => false,
		'show_control' => false,
		'only_subpages' => false,
		'child_of' => false,
	), $atts));
	

	pagetree_public($expand, $show_control, $only_subpages, $child_of);
	
}

add_shortcode('pagetree', 'pagetree_func');


// Add menu option to "Pages" menu
function pagetree_menu() {
	add_submenu_page('edit-pages.php', __("Wordpress Page Tree", "page-tree"), __("Page tree", "page-tree"), 7, __FILE__, 'pagetree_options');
}



function pagetree_widget($args) {
	extract($args);

	global $pagetree_called_from_widget;
	
	$pagetree_called_from_widget = true;
	
	$options = get_option("pagetree_widget");

	echo $before_widget;
	echo $before_title;
	echo $options['title'];
	echo $after_title;
	pagetree_public();
	echo $after_widget;

}


function pagetree_widget_control() {
	$options = get_option("pagetree_widget");
	if (!is_array( $options )) {
		$options = array(
		'title' => __('Page tree'),
		'show_control' => 1, 
		'expand' => 0, 
		'only_subpages' => 0,
		'child_of' => false
		);
	}

	if ($_POST['pagetree-Submit']) {
		$options['title'] = htmlspecialchars($_POST['pagetree-WidgetTitle']);
		$options['show_control'] = (int)htmlspecialchars($_POST['pagetree-ShowControl']);
		$options['expand'] = (int)htmlspecialchars($_POST['pagetree-Expand']);
		$options['only_subpages'] = (int)htmlspecialchars($_POST['pagetree-OnlySubpages']);
		$options['child_of'] = (int)htmlspecialchars($_POST['pagetree-ChildOf']);
		
		update_option("pagetree_widget", $options);
	}

?>
<p>
	<label for="pagetree-WidgetTitle"><?php echo __("Title"); ?>: </label><br />
	<input class="widefat" type="text" id="pagetree-WidgetTitle" name="pagetree-WidgetTitle" value="<?php echo $options['title'];?>" />
	<br /><br />
	<label for="pagetree-ShowControl"><?php echo __("Show controls"); ?> </label><br />
	<small><?php _e("Display the links to expand all, collapse all or toggle all?"); ?></small><br />
	<input type="radio" <?php print($options["show_control"] == 0 ? "CHECKED " : "");?>id="pagetree-ShowControl" name="pagetree-ShowControl" value="0"> <?php echo __("No"); ?> 
	<input type="radio" <?php print($options["show_control"] == 1 ? "CHECKED " : "");?>id="pagetree-ShowControl" name="pagetree-ShowControl" value="1"> <?php echo __("Yes"); ?>
	<br /><br />
	<label for="pagetree-Expand"><?php echo __("Expand all"); ?></label><br />
	<small><?php _e("When loading the tree, should all branches be expanded by default?"); ?></small><br />
	<input type="radio" <?php print($options["expand"] == 0 ? "CHECKED " : "");?>id="pagetree-Expand" name="pagetree-Expand" value="0"> <?php echo __("No"); ?> 
	<input type="radio" <?php print($options["expand"] == 1 ? "CHECKED " : "");?>id="pagetree-Expand" name="pagetree-Expand" value="1"> <?php echo __("Yes"); ?>
	<br /><br />
	<label for="pagetree-OnlySubpages"><?php echo __("Only subpages"); ?></label><br />
	<small><?php _e("Should the tree only show pages below the current page in the tree structure?"); ?></small><br />
	<input type="radio" <?php print($options["only_subpages"] == 0 ? "CHECKED " : "");?>id="pagetree-OnlySubpages" name="pagetree-OnlySubpages" value="0"> <?php echo __("No"); ?> 
	<input type="radio" <?php print($options["only_subpages"] == 1 ? "CHECKED " : "");?>id="pagetree-OnlySubpages" name="pagetree-OnlySubpages" value="1"> <?php echo __("Yes"); ?>
	<br /><br />
	<label for="pagetree-ChildOf"><?php echo __("Page tree root"); ?></label><br />
	<small><?php _e("Starting point for the tree."); ?></small><br />
	<?php wp_dropdown_pages(array('selected' => $options["child_of"], 'name' => 'pagetree-ChildOf', 'show_option_none' => "[" . __('Show the complete structure') . "]", 'sort_column'=> 'menu_order, post_title')); ?>
	
	
	<input type="hidden" id="pagetree-Submit" name="pagetree-Submit" value="1" />
	
</p> 	
 	
<?php
}

function pagetree_public($expand = false, $show_control = false, $only_subpages = false, $child_of = false) {
	
	global $pagetree_called_from_widget, $post, $add_my_script;
	
	$add_my_script = true;
	
	if ($pagetree_called_from_widget == true) {
		$options = get_option("pagetree_widget");
		
	}
	else {
		$options = array("expand" => $expand, "show_control" => $show_control, "only_subpages" => $only_subpages, "child_of" => $child_of);
	}
	
	
	
	pagetree_print_styles();

	?>

	
	<script type="text/javascript">
	<!--
	var alreadyrunflag=0 //flag to indicate whether target function has already been run
	
	if (document.addEventListener)
		document.addEventListener("DOMContentLoaded", function(){alreadyrunflag=1; treestuff()}, false)
	else if (document.all && !window.opera) {
		document.write('<script type="text/javascript" id="contentloadtag" defer="defer" src="javascript:void(0)"><\/script>')
		var contentloadtag=document.getElementById("contentloadtag")
		contentloadtag.onreadystatechange=function(){
		if (this.readyState=="complete"){
			alreadyrunflag=1
			treestuff()
			}
		}
	}
	
	window.onload=function(){
	  setTimeout("if (!alreadyrunflag) treestuff()", 0)
	}


	function treestuff() {
		// Init this page tree, with #treecontrol and cookie memory
		jQuery("#navigation").treeview({
			control: "#treecontrol",
			persist: "location",
			animated: "fast"<?php
			if ($options["expand"] == 0) {
				echo ", collapsed: \"true\"";
			}
			?>
		
		});

	}
	//-->
	</script>
	
	<?php

	$args = array(
		"echo" => 0,
		"title_li" => "", 
		"link_before" => "", 
		"link_after" => "",
		"sort_column" => "menu_order"
	);
	
	if ($options["only_subpages"] == true) {
		$args["child_of"] = $post -> ID;	
	}
	elseif ($options["child_of"] > 0) {
		$args["child_of"] = $options["child_of"];	
	}

	$pages = wp_list_pages($args);

	if (strlen($pages)) {

	?>
	<div id="page-tree">
	<?php
	if ($options["show_control"] == 1) {
	?>
	<div id="treecontrol">
		<a class="button" title="<?php echo __("Collapse the entire tree below", "page-tree")?>" href="#"><?php echo __("Collapse All", "page-tree")?></a>
		<a class="button" title="<?php echo __("Expand the entire tree below", "page-tree")?>" href="#"><?php echo __("Expand All", "page-tree")?></a>
		<a class="button" title="<?php echo __("Toggle the tree below, opening closed branches, closing open branches", "page-tree")?>" href="#"><?php echo __("Toggle All", "page-tree")?></a>
	</div>	
	<?php 
	} 
	?>
	
	<ul id="navigation">
	
	<?php

	echo pagetree_make_tree($pages, true);

	?>
	</ul>
	</div>
	
	<?php

	}

}



function pagetree_options() {

	echo '<div class="wrap">';

	?>
	
	
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

function pagetree_make_tree($pages, $public = false) {
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
			$pageURL = get_permalink($pageID);

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

			if ($public) {
				// Create our own link to edit page for this ID
				$out .= "<a class=\"$pageStatus\" href=\"$pageURL\">" . $pageTitle . "</a>";
			}
			else {
				$out .= "<a class=\"$pageStatus\" href=\"" . get_bloginfo('wpurl') . "/wp-admin/page.php?action=edit&post=$pageID\">" . $pageTitle . "</a> <a style=\"font-size: 10px;\" class=\"$pageStatus\" href=\"$pageURL\">#</a>";
			}

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
	'authors' => '', 'parent' => -1, 'exclude_tree' => '',
	'include_trash' => false
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
	if ($include_trash) {
		$query = "SELECT * FROM $wpdb->posts $join WHERE (post_type = 'page') $where ";
	}
	else {
		$query = "SELECT * FROM $wpdb->posts $join WHERE (post_type = 'page' AND post_status != 'trash') $where ";
	}
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
