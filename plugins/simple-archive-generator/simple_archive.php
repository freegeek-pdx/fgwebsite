<?php
/*
	Plugin Name:	Simple Archive Generator
	Plugin URI:		http://www.sterling-adventures.co.uk/blog/2007/10/01/simple-archive-plugin/
	Description:	A plugin which simply lists all posts by category.
	Author:			Peter Sterling
	Version:		2.2
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
		'comment_count' => 'on',
		'exclude_zero_cats' => 'off',
		'show_intro' => 'on'
	);
}


// Set up the required text domain for the chosen language.
function set_simple_archive_textdomain()
{
	$test = WPLANG;
	if(!empty($test)) {
		load_plugin_textdomain('simple-archive', 'wp-content/plugins/simple_archive');
	}
}


// Build string for statictics...
function get_statistics($p, $c, $m, $a, $s)
{
	if($s == 'on') {
		$ext .= __('There are ', 'simple-archive') . $p . __(' posts in ', 'simple-archive') . $c . __(' categories with ', 'simple-archive') . $m . __(' comments', 'simple-archive');
		if($a == 'on') $ext .= __(' (plus ', 'simple-archive') . get_option('akismet_spam_count') . __(' spam comments)', 'simple-archive');
		return $ext;
	}
}


// Generate (return) the archive content...
function create_simple_archive()
{
	global $simple_archive_options;

	$cats = get_categories('orderby=' . ($simple_archive_options['sort_alphabetically'] == 'on' ? 'name' : 'ID') . '&hierarchical=' . ($simple_archive_options['group_hierarchies'] == 'on' ? '1' : '0'));
	$result = '';
	$indent = false;
	$done_post_ids = ' ';
	$post_count = 0;
	$comment_count = 0;

	if($simple_archive_options['show_intro'] == 'on') {
		$intro = '<p>' . __('Categories are sorted ', 'simple-archive') . ($simple_archive_options['sort_alphabetically'] == 'on' ? __('alphabetically.', 'simple-archive') : __('in the order created.', 'simple-archive')) . '</p>';
		if($simple_archive_options['group_hierarchies'] == 'on') $intro .= '<p>' . __('Hierarchical categories are grouped and indented under their parent category.', 'simple-archive') . '</p>';
		$intro .= '<p>' . __('Reports are listed ', 'simple-archive') . ($simple_archive_options['list_post_once'] == 'on' ? __('once only, under the category they are first shown.', 'simple-archive') : __('under all applied categories.', 'simple-archive')) . '</p>';
		if($simple_archive_options['comment_count'] == 'on') $intro .= '<p>' . __('A count (in brackets) is given of comments received against individual reports.', 'simple-archive') . '</p>';
		if($simple_archive_options['category_count'] == 'on') $intro .= '<p>' . __('The number of reports under each category is given (in brackets) after each category name.  Reports may be filed under more than one category and are included in the total for all categories under which they are filed, but are not included in a parent category\'s total.', 'simple-archive') . '</p>';
	}

	foreach($cats as $cat) {
		// Skip excluded categories.
		$excluded_categories = explode(",", $simple_archive_options['exclude_categories']);
		if(in_array($cat->cat_ID, $excluded_categories)) continue;

		if($cat->category_parent != '0' && $indent == false && $simple_archive_options['group_hierarchies'] == 'on') {
			$indent = true;
			$result .= '<div style="margin-left: 40px;">';
		}

		if($cat->category_parent == '0' && $indent == true) {
			$indent = false;
			$result .= '</div>';
		}

		$posts = get_posts('category=' . $cat->term_id . '&orderby=post_date&order=DESC&numberposts=9999');

		// Only show category heading if there are posts to show.
		$posts_to_show = 0;

		if($simple_archive_options['exclude_zero_cats'] == 'on') {
			foreach($posts as $post) {
				setup_postdata($post);
				if(!strpos($done_post_ids, '+' . $post->ID . '+') || $simple_archive_options['list_post_once'] != 'on') {
					$posts_to_show++;
				}
			}
		}

		if($posts_to_show > 0 || $simple_archive_options['exclude_zero_cats'] != 'on') {
			$result .= '<h3 class="simple_aheading"><a href="' . get_category_link($cat->term_id) . '" title="' . htmlentities($cat->cat_name) . '">' . ($indent ? '&raquo; ' : '') . $cat->cat_name . '</a>' . ($simple_archive_options['category_count'] == 'on' ? ' (' . $cat->category_count . ')' : '') . '</h3>';
			$result .= trim($cat->description) != ''? $cat->description . '<br />' : '';

			$result .= '<ul>';

			foreach($posts as $post) {
				setup_postdata($post);
				if(!strpos($done_post_ids, '+' . $post->ID . '+') || $simple_archive_options['list_post_once'] != 'on') {
					$result .= '<li><a class="simple_alink" href="' .  get_permalink($post->ID) . '" title="' . htmlentities($post->post_title) . '">' . $post->post_title . '</a>';
					if($post->comment_count > 0 && $simple_archive_options['comment_count'] == 'on') $result .= ' (' . $post->comment_count . ')';
					if(!strpos($done_post_ids, '+' . $post->ID . '+')) {
						$comment_count += $post->comment_count;
						$post_count++;
					}
					$result .= '</li>';
					$done_post_ids .= '+' . $post->ID . '+';
				}
			}

			$result .= '</ul>';
		}
	}

	// May have been indenting a nested category...
	if($indent) $result .= '</div>';

	$result .= '<p style="border-top: 1pt solid #ddd; text-align:center; margin-top: 30px;">Simple Archive ' . __('by', 'simple-archive') . ' <a href="http://www.sterling-adventures.co.uk">Sterling Adventures</a></p>';

	$intro .= get_statistics($post_count, sizeof($cats), $comment_count, $simple_archive_options['show_spam_count'], $simple_archive_options['show_counts']) . '.';

	return $intro . $result;
}


// Display simple archive if trigger is found...
function generate_simple_archive($content)
{
	if (strpos($content, "<!-- simple_archive -->") !== FALSE) {
		$content = preg_replace('/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content);
		$content = str_replace('<!-- simple_archive -->', create_simple_archive(), $content);
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
			'show_spam_count' => $_POST['show_spam_count'],
			'category_count' => $_POST['category_count'],
			'show_counts' => $_POST['show_counts']
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
				<tr><td>Simple Archive language:<br /><small>Set language in <code>wp-config.php</code>, e.g. <code>&lt;?php define ('WPLANG', 'de'); ?&gt;</code></small></td><td><input type="text" value="<?php echo WPLANG; ?>" readonly/></td></tr>
				<tr><td><label for="list_post_once">Only list posts once (1st time encountered) in the archive:</label> </td><td><input id="list_post_once" type="checkbox" name="list_post_once" <?php echo $simple_archive_options['list_post_once'] == 'on' ? 'checked="checked"' : ''; ?> /></td></tr>
				<tr><td><label for="group_hierarchies">Group hierarchical categories:</label> </td><td><input id="group_hierarchies" type="checkbox" name="group_hierarchies" <?php echo $simple_archive_options['group_hierarchies'] == 'on' ? 'checked="checked"' : ''; ?> /></td></tr>
				<tr><td><label for="sort_alphabetically">Sort categories alphabetically:</label> </td><td><input id="sort_alphabetically" type="checkbox" name="sort_alphabetically" <?php echo $simple_archive_options['sort_alphabetically'] == 'on' ? 'checked="checked"' : ''; ?> /></td></tr>
				<tr><td><label for="comment_count">Include comment count:</label> </td><td><input id="comment_count" type="checkbox" name="comment_count" <?php echo $simple_archive_options['comment_count'] == 'on' ? 'checked="checked"' : ''; ?> /></td></tr>
				<tr><td><label for="category_count">Include category count:</label> </td><td><input id="category_count" type="checkbox" name="category_count" <?php echo $simple_archive_options['category_count'] == 'on' ? 'checked="checked"' : ''; ?> /></td></tr>
				<tr><td><label for="show_intro">Show introduction:</label><br /><small>Explains the format of the Simple Archive output - controlled by options above.</small></td><td><input id="show_intro" type="checkbox" name="show_intro" <?php echo $simple_archive_options['show_intro'] == 'on' ? 'checked="checked"' : ''; ?> /></td></tr>
				<tr><td><label for="exclude_zero_cats">Exclude categories with no posts:</label> </td><td><input id="exclude_zero_cats" type="checkbox" name="exclude_zero_cats" <?php echo $simple_archive_options['exclude_zero_cats'] == 'on' ? 'checked="checked"' : ''; ?>" /></td></tr>
				<tr><td><label for="exclude_categories">Exclude categories (IDs, comma separated):</label> </td><td><input id="exclude_categories" type="text" name="exclude_categories" value="<?php echo $simple_archive_options['exclude_categories']; ?>" /></td></tr>
				<tr><td><label for="show_counts">Show statistics:</label> </td><td><input id="show_counts" type="checkbox" name="show_counts" <?php echo $simple_archive_options['show_counts'] == 'on' ? 'checked="checked"' : ''; ?> />
				and then <label for="show_spam_count">show spam count:</label> <input id="show_spam_count" type="checkbox" name="show_spam_count" <?php echo $simple_archive_options['show_spam_count'] == 'on' ? 'checked="checked"' : ''; ?> /></td></tr>
				<tr><td>Statistics output:</td><td><em><strong><?php echo get_statistics('X', 'Y', 'Z', $simple_archive_options['show_spam_count'], $simple_archive_options['show_counts']); ?></strong></em></td></tr>
			</table>
			<p class="submit"><input type="submit" name="submit" value="Update Simple Archive Options" /></p>
		</form>
		<h3>Simple Archive Usage</h3>
		Use this text to include a simple archive on one of your pages (or posts).<br />
		<code>&lt;!-- simple_archive --&gt;</code>
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


add_filter('the_content', 'generate_simple_archive');
add_action('admin_menu', 'simple_archive_options');
add_action('init', 'set_simple_archive_textdomain');
?>