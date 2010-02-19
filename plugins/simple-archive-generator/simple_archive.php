<?php
/*
	Plugin Name:	Simple Archive Generator
	Plugin URI:		http://www.sterling-adventures.co.uk/blog/2007/10/01/simple-archive-plugin/
	Description:	A plugin which simply lists all posts by category.
	Author:			Peter Sterling
	Version:		5.2
	Changes:		0.1 - Initial version.
					0.2 - Only lists the first occurrence of a post.
					0.3 - Added administration options page.
					0.4 - Statistics added.
					0.5 - Option to also show spam comment count.
					0.6 - Fixed category nesting error.
					1.0 - A whole bunch of fixes and typos thanks to Mark DeNio.
					2.0 - German translation plus a few other features thanks to Ingo Terpelle (http://www.xing.com/profile/Ingo_Terpelle).
					2.1 - Enhancements (plus a couple of fixes) to Ingo's version.
					2.2 - Support for internationalization added.
					3.0 - Option for showing post dates in archive - prompted by Brendan Berkley (http://www.zamagazine.org).
					3.1 - Bug in date function fixed.
					3.2 - Full stop error in statistics information.
					4.0 - Hide and Show option.
					5.0 - Update to use WP's Walker class.
					5.1 - Extra options to control indentation style.
					5.2 - Needed redesign of output to avoid a BIG issue with PHP memory overflow!
	Author URI:		http://www.sterling-adventures.co.uk/
*/

// Default options...
$simple_archive_options = get_option('plugin_simple_archive');
if(!is_array($simple_archive_options)) {
	// Options do not exist or have not yet been loaded so we define standard options...
	$simple_archive_options = array(
		'list_post_once' => 'on',
		'group_hierarchies' => 'on',
		'sort_alphabetically' => 'on',
		'show_counts' => 'on',
		'show_spam_count' => 'on',
		'category_count' => 'on',
		'show_date' => 'on',
		'date_format' => get_option('date_format'),	// Get WordPress date format option as default.
		'comment_count' => 'on',
		'exclude_zero_cats' => 'off',
		'hide' => 'off',
		'show_intro' => 'on',
		'indent_px' => '30',
		'indent_ch' => 'on',
		'indent_rp' => 'off'
	);
}


// Icon files.
define('PLUS', get_settings('siteurl') . '/wp-content/plugins/simple-archive-generator/icon_plus.gif');
define('MINUS', get_settings('siteurl') . '/wp-content/plugins/simple-archive-generator/icon_minus.gif');

$has_archive = false;

// Set up the required text domain for the chosen language.
function set_simple_archive_textdomain()
{
	$test = WPLANG;
	if(!empty($test)) {
		load_plugin_textdomain('simple-archive', 'wp-content/plugins/simple-archive-generator');
	}
}


// Build string for statictics...
function get_statistics($p, $c, $m, $a, $s)
{
	if($s == 'on') {
		$ext .= __('There are ', 'simple-archive') . $p . __(' posts in ', 'simple-archive') . $c . __(' categories with ', 'simple-archive') . $m . __(' comments', 'simple-archive');
		if($a == 'on') $ext .= __(' (plus ', 'simple-archive') . get_option('akismet_spam_count') . __(' spam comments)', 'simple-archive');
		return $ext . '.';
	}
}


// Extend WP's Walker class to properly navigate the category hierarchy...
class Simple_Archive_Category_Walker extends Walker {
	// Variables needed by the WP Walker class.
	var $tree_type = 'category';
	var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

	// Variables used by the Simple Archive plug-in.
	var $done_post_ids = ' ';
	var $comment_count = 0;
	var $post_count = 0;

	// Output a new element (Category)...
	function start_el(&$output, $category, $depth, $r)
	{
		global $simple_archive_options;

		$args = wp_parse_args($r);

		// Only show category heading if there are posts to show; keep count of posts...
		$posts_to_show = 0;

		// Get posts for this category.
		$posts = get_posts('category=' . $category->term_id . '&orderby=post_date&order=DESC&numberposts=9999');

		// Build a list of the post IDs that will be shown.
		foreach($posts as $post) {
			setup_postdata($post);
			if(!strpos($this->done_post_ids, '+' . $post->ID . '+') || $simple_archive_options['list_post_once'] != 'on') {
				$posts_to_show++;
			}
		}

		// Start the output.  Closed with the "end_el" method.
		echo "<div class='simple_acat'" . ($simple_archive_options['group_hierarchies'] == 'on' ? " style='margin-left: " . ($depth > 0 ? $simple_archive_options['indent_px'] : '0') . "px;'>" : '>');

		// If any to show, output...
		if($posts_to_show > 0 || $simple_archive_options['exclude_zero_cats'] != 'on') {
			// Category heading.
			$cat_name = apply_filters('list_cats', $category->name, $category);
			echo '<h3 style="display: inline;" class="simple_aheading">';
			if($depth > 0 && $simple_archive_options['group_hierarchies'] == 'on' && $simple_archive_options['indent_ch'] == 'on') echo str_repeat('&raquo;', $simple_archive_options['indent_rp'] == 'on' ? $depth : 1) . ' ';
			echo '<a href="' . get_category_link($category->term_id) . '" title="' . sprintf(__( 'View all posts filed under %s' ), $cat_name) . '">' . $cat_name . '</a>';
			if($args['show_count']) echo '&nbsp;&nbsp;(' . $category->count . ')';
			echo "</h3>\n";

			// Collapsable?
			echo ($simple_archive_options['hide'] == 'on' ? ' <a id="cat-control-' . $category->term_id . '" href="javascript:sa_show_hide(' . $category->term_id . ');"><img alt="" class="no-rate" src="' . PLUS . '" title="' . __('Expand', 'simple-archive') . '" /></a>' : '') . '<br />';

			// Category description.
			echo trim($category->description) != '' ? $category->description . '<br />' : '';

			// List of posts for this category...
			echo '<ul id="cat-list-' . $category->term_id . '"' . ($simple_archive_options['hide'] != 'on' ? '>' : ' style="display: none;">');

			foreach($posts as $post) {
				setup_postdata($post);

				// Only show if not already shown, or we are showing duplicates for each category filed under.
				if(!strpos($this->done_post_ids, '+' . $post->ID . '+') || $simple_archive_options['list_post_once'] != 'on') {
					echo '<li><a class="simple_alink" href="' .  get_permalink($post->ID) . '" title="' . htmlentities($post->post_title) . '">' . $post->post_title . '</a>';
					if($simple_archive_options['show_date'] == 'on') echo ', ' . mysql2date($simple_archive_options['date_format'], $post->post_date);
					if($post->comment_count > 0 && $simple_archive_options['comment_count'] == 'on') echo ' (' . $post->comment_count . ')';
					if(!strpos($this->done_post_ids, '+' . $post->ID . '+')) {
						$this->comment_count += $post->comment_count;
						$this->post_count++;
					}
					echo '</li>' . "\n";
					$this->done_post_ids .= '+' . $post->ID . '+';
				}
			}

			echo '</ul>';
		}
	}

	// End element output.
	function end_el(&$output, $page, $depth, $args)
	{
		echo "</div>\n";
	}
}


// Generate (return) the archive content...
function create_simple_archive()
{
	global $simple_archive_options,  $has_archive;

	if(!$has_archive) return;

	// Build arguments list...
	$args = 'orderby=' . ($simple_archive_options['sort_alphabetically'] == 'on' ? 'name' : 'ID') .
			'&hierarchical=' . ($simple_archive_options['group_hierarchies'] == 'on' ? '1' : '0') .
			(!empty($simple_archive_options['exclude_categories']) ? '&exclude=' . $simple_archive_options['exclude_categories'] : '') .
			'&show_count=' . ($simple_archive_options['category_count'] == 'on' ? '1' : '0') .
			'&hide_empty=' . ($simple_archive_options['exclude_zero_cats'] == 'on' ? '1' : '0');

	// Get categories as required by arguments.
	$cats = get_categories($args);

	// Optional (user choice) introductory explanation of output...
	if($simple_archive_options['show_intro'] == 'on') {
		echo '<p>', __('Categories are sorted ', 'simple-archive'), ($simple_archive_options['sort_alphabetically'] == 'on' ? __('alphabetically.', 'simple-archive') : __('in the order created.', 'simple-archive')), '</p>';
		if($simple_archive_options['group_hierarchies'] == 'on') echo '<p>', __('Hierarchical categories are grouped and indented under their parent category.', 'simple-archive'), '</p>';
		echo '<p>', __('Reports are listed ', 'simple-archive') . ($simple_archive_options['list_post_once'] == 'on' ? __('once only, under the category they are first shown.', 'simple-archive') : __('under all applied categories.', 'simple-archive')), '</p>';
		if($simple_archive_options['comment_count'] == 'on') echo '<p>', __('A count (in brackets) is given of comments received against individual reports.', 'simple-archive'), '</p>';
		if($simple_archive_options['category_count'] == 'on') echo '<p>', __('The number of reports under each category is given (in brackets) after each category name.  Reports may be filed under more than one category and are included in the total for all categories under which they are filed, but are not included in a parent category\'s total.', 'simple-archive'), '</p>';
	}

	// Instantiate walker object and "walk" categories...
	$walker = new Simple_Archive_Category_Walker;
	$result = $walker->walk($cats, ($simple_archive_options['group_hierarchies'] == 'on' ? 0 : -1), $args);

	// Summary information...
	echo '<p>', get_statistics($walker->post_count, sizeof($cats), $walker->comment_count, $simple_archive_options['show_spam_count'], $simple_archive_options['show_counts']), '</p>';

	// Credit...
	echo '<p style="border-top: 1pt solid #ddd; text-align:center; margin-top: 30px;">Simple Archive ' . __('by', 'simple-archive') . ' <a href="http://www.sterling-adventures.co.uk">Sterling Adventures</a></p>';
}


// Display simple archive if trigger is found...
function generate_simple_archive($content)
{
	global $has_archive;
	if(strpos($content, "<!-- simple_archive -->") !== false) {
		$has_archive = true;
		$content = preg_replace('/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content);
		$content = str_replace('<!-- simple_archive -->', '', $content);
	}
	return $content;
}


// Generate the options page...
function generate_simple_archive_options_page()
{
	global $simple_archive_options;

	add_option('plugin_simple_archive', $simple_archive_options, 'Simple Archive Plugin Options');

	// Check form submission and update options if no error occurred.
	if(isset($_POST['submit'])) {
		$simple_archive_options_update = array (
			'exclude_categories' => $_POST['exclude_categories'],
			'show_intro' => $_POST['show_intro'],
			'exclude_zero_cats' => $_POST['exclude_zero_cats'],
			'list_post_once' => $_POST['list_post_once'],
			'group_hierarchies' => $_POST['group_hierarchies'],
			'sort_alphabetically' => $_POST['sort_alphabetically'],
			'comment_count' => $_POST['comment_count'],
			'show_date' => $_POST['show_date'],
			'date_format' => $_POST['date_format'],
			'show_spam_count' => $_POST['show_spam_count'],
			'category_count' => $_POST['category_count'],
			'hide' => $_POST['hide'],
			'show_counts' => $_POST['show_counts'],
			'indent_px' => (((string) $_POST['indent_px']) === ((string)(int) $_POST['indent_px']) ? $_POST['indent_px'] : 0),
			'indent_ch' => $_POST['indent_ch'],
			'indent_rp' => $_POST['indent_rp']
		);
		update_option('plugin_simple_archive', $simple_archive_options_update);
	}

	// Get options.
	$simple_archive_options = get_option('plugin_simple_archive');
?>
	<div class="wrap">
		<h2>Simple Archive Options</h2>
		Control the behaviour of the simple archive generator.<br />
		Please visit the author's site, <a href='http://www.sterling-adventures.co.uk/' title='Sterling Adventures'>Sterling Adventures</a>, and say "Hi"...
		<form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?page=' . basename(__FILE__); ?>&updated=true">
			<h3>Simple Archive Settings</h3>
			<table class="form-table">
				<tr><td>Language:<br /><small>Set language in <code>wp-config.php</code>, e.g. <code>&lt;?php define ('WPLANG', 'de'); ?&gt;</code></small></td><td><input type="text" value="<?php echo WPLANG; ?>" readonly/> (read only)</td></tr>
				<tr><td><label for="hide">Hide and Show feature:</label></td><td><input type="checkbox" name="hide" <?php echo $simple_archive_options['hide'] == 'on' ? 'checked="checked"' : ''; ?> /> <small>Click to dynamically hide or show the list of posts for each category</small></td></tr>
				<tr><td><label for="list_post_once">Only list posts once (1st time encountered) in the archive:</label></td><td><input type="checkbox" name="list_post_once" <?php echo $simple_archive_options['list_post_once'] == 'on' ? 'checked="checked"' : ''; ?> /></td></tr>
				<tr><td><label for="group_hierarchies">Group hierarchical categories:</label></td><td><input type="checkbox" name="group_hierarchies" <?php echo $simple_archive_options['group_hierarchies'] == 'on' ? 'checked="checked"' : ''; ?> /></td></tr>
				<tr><td><label for="sort_alphabetically">Sort categories alphabetically:</label></td><td><input type="checkbox" name="sort_alphabetically" <?php echo $simple_archive_options['sort_alphabetically'] == 'on' ? 'checked="checked"' : ''; ?> /></td></tr>
				<tr><td><label for="comment_count">Include comment count:</label></td><td><input type="checkbox" name="comment_count" <?php echo $simple_archive_options['comment_count'] == 'on' ? 'checked="checked"' : ''; ?> /></td></tr>
				<tr><td><label for="category_count">Include category post count:</label></td><td><input type="checkbox" name="category_count" <?php echo $simple_archive_options['category_count'] == 'on' ? 'checked="checked"' : ''; ?> /></td></tr>
				<tr><td><label for="show_date">Show post dates:</label></td><td><input type="checkbox" name="show_date" <?php echo $simple_archive_options['show_date'] == 'on' ? 'checked="checked"' : ''; ?> /></td></tr>
				<tr><td><label for="date_format">Date format:</label><br /><small>Used if '<i>Show post dates</i>' is set.  Default is <b><?php echo get_option('date_format'); ?></b> (from <i>Settings &raquo; General &raquo; Date Format</i>)</small></td><td><input type="text" name="date_format" value="<?php echo $simple_archive_options['date_format']; ?>" /><br /><small>Output: <b><?php echo mysql2date($simple_archive_options['date_format'], current_time('mysql')); ?></b></small></td></tr>
				<tr><td><label for="show_intro">Show introduction:</label><br /><small>Explains the format of the Simple Archive output - controlled by options above</small></td><td><input type="checkbox" name="show_intro" <?php echo $simple_archive_options['show_intro'] == 'on' ? 'checked="checked"' : ''; ?> /></td></tr>
				<tr><td><label for="exclude_zero_cats">Exclude categories with no posts:</label></td><td><input type="checkbox" name="exclude_zero_cats" <?php echo $simple_archive_options['exclude_zero_cats'] == 'on' ? 'checked="checked"' : ''; ?>" /></td></tr>
				<tr><td><label for="exclude_categories">Exclude categories:</label><br /><small>Comma separated IDs</small></td><td><input type="text" name="exclude_categories" value="<?php echo $simple_archive_options['exclude_categories']; ?>" /></td></tr>
				<tr>
					<td><label for="show_counts">Show statistics:</label></td>
					<td>
						<input type="checkbox" name="show_counts" <?php echo $simple_archive_options['show_counts'] == 'on' ? 'checked="checked"' : ''; ?> /> and then <label for="show_spam_count">show spam count: </label><input type="checkbox" name="show_spam_count" <?php echo $simple_archive_options['show_spam_count'] == 'on' ? 'checked="checked"' : ''; ?> /><br />
						<small>Output: <b><?php echo get_statistics('X', 'Y', 'Z', $simple_archive_options['show_spam_count'], $simple_archive_options['show_counts']); ?></b></small>
					</td>
				</tr>
				<tr><td><label for="indent_px">Indent:</label><br /><small>Pixels to indent nested categories by.</small></td><td><input type="text" size="3" name="indent_px" value="<?php echo $simple_archive_options['indent_px']; ?>" /> px</td></tr>
				<tr>
					<td><label for="indent_ch">Include indent character (&raquo;):</label></td>
					<td>
						<input type="checkbox" name="indent_ch" <?php echo $simple_archive_options['indent_ch'] == 'on' ? 'checked="checked"' : ''; ?>" /> and <label for="indent_rp">repeat for depth of nesting: </label><input type="checkbox" name="indent_rp" <?php echo $simple_archive_options['indent_rp'] == 'on' ? 'checked="checked"' : ''; ?> /><br />
						<small>Include character to highlight nested categories.</small>
					</td>
				</tr>
			</table>
			<p class="submit"><input type="submit" name="submit" value="Update Simple Archive Options" /></p>
		</form>
		<h3>Simple Archive Usage</h3>
		<p>
			Use this text to include a simple archive on one of your pages (or posts).<br />
			<code>&lt;!-- simple_archive --&gt;</code>
		</p>
		<p>
			There are three (optional) formatting classes to help style the output:<ol>
			<li><code>simple_acat</code> - styles each block; category heading and list of posts.  Suggest something like <code>.simple_acat { padding: 3px; }</code> in your <code>style.css</code> template file.</li>
			<li><code>simple_aheading</code> - styles each category heading.  Also uses <code>&lt;h3&gt;</code> from your template, so you may be happy to ignore this style.</li>
			<li><code>simple_alink</code> - style for each of the listed post links.  Again, will use the default style for links from your template, so you may be happy to ignore this style too.</li>
			</ol>
		</p>
	</div>
<?php
}


// Add options menu to administration interface...
function simple_archive_options()
{
	if(function_exists('add_options_page')) {
		add_options_page('Simple Archive Options', 'Simple Archive', 8, basename(__FILE__), 'generate_simple_archive_options_page');
	}
}


// Return show/hide javascript.
function add_simple_archive_script()
{
	global $simple_archive_options;

	if(!is_page() || $simple_archive_options['hide'] != 'on') return;

	printf('<script language="Javascript" type="text/javascript">
		function sa_show_hide(num)
		{
			var l = document.getElementById("cat-list-" + num);
			var c = document.getElementById("cat-control-" + num);

			if(l.style.display == "none") {
				l.style.display = "block";
				c.innerHTML = "<img alt=\'\' class=\'no-rate\' src=\'%3$s\' title=\'%1$s\' />";
			}
			else {
				l.style.display = "none";
				c.innerHTML = "<img alt=\'\' class=\'no-rate\' src=\'%4$s\' title=\'%2$s\' />";
			}
		}
	</script>', __('Collapse', 'simple-archive'), __('Expand', 'simple-archive'), MINUS, PLUS);
}


function sa_initialise()
{
	global $has_archive;
	$has_archive = false;
}

add_filter('the_content', 'generate_simple_archive');
add_action('admin_menu', 'simple_archive_options');
add_action('loop_start', 'sa_initialise');
add_action('loop_end', 'create_simple_archive');
add_action('init', 'set_simple_archive_textdomain');
add_action('wp_head', 'add_simple_archive_script');
?>