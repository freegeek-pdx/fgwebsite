<?php
/*
Plugin Name: iBegin Share
Plugin URI: http://labs.ibegin.com/share/
Description: Adds a "Share" button to your posts.
Author: David Cramer
Version: 1.0
Author URI: http://labs.ibegin.com/
*/

define(IBEGIN_SHARE_STATE_DEFAULT, 0);
define(IBEGIN_SHARE_STATE_CONTENTPAGE, 1);

define(IBEGIN_SHARE_STYLE_BUTTON, 1);
define(IBEGIN_SHARE_STYLE_TEXT, 2);

$ibegin_share_state = IBEGIN_SHARE_STATE_DEFAULT;

$ibegin_share_options = array(
    'ibegin_share_add_to_post'      => __('Add iBegin Share to posts.', 'ibegin_share'),
    'ibegin_share_add_to_page'      => __('Add iBegin Share to pages.', 'ibegin_share'),
    'ibegin_share_enable_context'   => __('Allow the use of [ibeginshare] in posts and pages.', 'ibegin_share'),
    'ibegin_share_link_type'        => __('Share Link Style:', 'ibegin_share'),
);
$ibegin_share_options_default_values = array(
    'ibegin_share_add_to_post'      => '1',
    'ibegin_share_add_to_page'      => '0',
    'ibegin_share_enable_context'   => '1',
    'ibegin_share_link_type'        => IBEGIN_SHARE_STYLE_BUTTON,
);
$ibegin_share_options_choices = array(
    'ibegin_share_add_to_post'      => 1,
    'ibegin_share_add_to_page'      => 1,
    'ibegin_share_enable_context'   => 1,
    'ibegin_share_link_type'        => array(
        IBEGIN_SHARE_STYLE_BUTTON   =>  'Button',
        IBEGIN_SHARE_STYLE_TEXT     =>  'Text Link',
    ),
);
$ibegin_share_plugins = array(
    'bookmarks' =>  'Bookmarks',
    'email'     =>  'Email',
    'mypc'      =>  'My Computer',
    'printer'     =>  'Printer',
);


$ibegin_share_path =  get_settings('siteurl') . "/wp-content/plugins/ibegin_share";
/**
 * Adds/updates the options on plug-in activation.
 */
function iBeginShare_Install()
{
    global $ibegin_share_options_default_values, $ibegin_share_plugins;
    foreach ($ibegin_share_options_default_values as $option=>$value)
    {
        if (get_option($option) == '') update_option($option, $value);
    }
    foreach (array_keys($ibegin_share_plugins) as $plugin)
    {
        $option = 'ibegin_share_plugins_enable_' . $plugin;
        if (get_option($option) == '') update_option($option, '1');
    }
}
if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
    iBeginShare_Install();
}

// Includes CSS/JS.
add_action('wp_head', 'iBeginShare_Header');

if (get_option('ibegin_share_add_to_post') == '1' || get_option('ibegin_share_add_to_page') == '1')
{
    // Adds share button to content.
    add_action('the_content', 'iBeginShare_Widget');
}

// Admin options.
add_action('admin_menu', 'iBeginShare_Menu');

// Handles requests and option changes.
add_action('init', 'iBeginShare_Pages', 9999);

if (get_option('ibegin_share_enable_context') == '1')
{
    // Adds the [ibeginshare] context tag.
    add_filter('the_content', 'iBeginShare_ContextFilter');
}

/**
 * Includes iBegin Share's CSS and JavaScript files in the header.
 */
function iBeginShare_Header()
{
    global $ibegin_share_plugins, $ibegin_share_path;
    
    echo '<link rel="stylesheet" href="' . $ibegin_share_path . '/share/share/css/share-tool.css" media="screen" type="text/css"/>';
    echo '<script type="text/javascript" src="' . $ibegin_share_path . '/share/share/script/share.js"></script>';
    echo '<script type="text/javascript">iBeginShare.base_url = "' . $ibegin_share_path . '/share/"</script>';
    $disabled = array();
    foreach (array_keys($ibegin_share_plugins) as $plugin)
    {
        $option = 'ibegin_share_plugins_enable_'.$plugin;
        if (!get_option($option)) $disabled[] = 'iBeginShare.plugins.builtin.'.$plugin;
    }
    if (count($disabled))
    {
        echo '<script type="text/javascript">iBeginShare.plugins.unregister('.implode(',', $disabled).');</script>';
    }
}

/**
 * Handles the [ibeginshare] template filter.
 * @param {string} $content HTML content.
 * @param {string} $url URL to pass as link parameter.
 * @param {string} $title Title to pass as title parameter.
 * @param {string} $content_url Content url to pass as content parameter.
 */
function iBeginShare_ContextFilter($content, $title=null, $url=null, $content_url=null)
{
    $content = str_replace('[ibeginshare]', iBeginShare__renderLink(get_option('ibegin_share_link_type'), $url, $title, $content_url, true), $content);
    return $content;
}

/**
 * Adds an iBegin Share button to the content.
 * @param {string} $content HTML Content.
 * @return {string} Altered HTML Content.
 */
function iBeginShare_Widget($content)
{
    global $ibegin_share_state;

    // If we're not in the default state don't draw the button
    if ($ibegin_share_state != IBEGIN_SHARE_STATE_DEFAULT) return $content;
    
    // Only draw the button if it's enabled
    if ((is_page() && get_option('ibegin_share_add_to_page')) || (!is_page() && get_option('ibegin_share_add_to_post')))
    {
        return $content.'<p>'.iBeginShare__renderLink(get_option('ibegin_share_link_type'), null, null, null, true).'</p>';
    }
    else {
        return $content;
    }
}
/**
 * Creates the share text link. All arguments are optional.
 * @param {string} $url URL to pass as link parameter.
 * @param {string} $title Title to pass as title parameter.
 * @param {string} $content_url Content url to pass as content parameter.
 * @param {bool} $return_value If this is set it will return the output instead of printing.
 */
function iBeginShare_TextLink($url=null, $title=null, $content_url='', $return_value=false)
{
    return iBeginShare__renderLink(IBEGIN_SHARE_STYLE_TEXT, $url, $title, $content_url, $return_value);
}
/**
 * Creates the share button. All arguments are optional.
 * @param {string} $url URL to pass as link parameter.
 * @param {string} $title Title to pass as title parameter.
 * @param {string} $content_url Content url to pass as content parameter.
 * @param {bool} $return_value If this is set it will return the output instead of printing.
 */
function iBeginShare_Button($url=null, $title=null, $content_url='', $return_value=false)
{
    return iBeginShare__renderLink(IBEGIN_SHARE_STYLE_BUTTON, $url, $title, $content_url, $return_value);
}

/**
 * The private method which handles rendering all share links.
 * @param {string} $url URL to pass as link parameter.
 * @param {string} $title Title to pass as title parameter.
 * @param {string} $content_url Content url to pass as content parameter.
 * @param {bool} $return_value If this is set it will return the output instead of printing.
 */
function iBeginShare__renderLink($style, $url=null, $title=null, $content_url='', $return_value=false)
{
    global $post;
    
    if ($post)
    {
        if (!$title && !$url) $content_url = get_option('siteurl') . '/?ibegin_share_action=get_content&id=' . $post->ID;
        if (!$title) $title = get_the_title();
        if (!$url) $url = get_permalink($post->ID);
    }
    else
    {
        if (!$title) $title = get_option('blogname');
        if (!$url) $url = get_option('siteurl');
    }
    
    $id = rand(0,100000000000);
        
    $title = str_replace('\'', "\'", htmlspecialchars($title));
    $url = str_replace('\'', "\'", $url);
    if ($content_url) $content_url = str_replace('\'', "\'", $content_url);
    
    $output = array();
    $output[] = '<span id="share-tool-' . $id . '"><script type="text/javascript">iBeginShare.';
    switch ($style)
    {
        case IBEGIN_SHARE_STYLE_BUTTON:
            $output[] = 'attachButton';
            break;

        case IBEGIN_SHARE_STYLE_TEXT:
            $output[] = 'attachTextLink';
            break;
        
        default:
            return false;
    }
    $output[] = '(\'share-tool-'. $id . '\', {';
    $output[] = 'title: \'' . $title . '\', ';
    $output[] = 'link: \'' . $url . '\'';
    if ($content_url) $output[] = ', content: \'' . $content_url . '\'';
    $output[] = '});</script></span>';

    $output = implode('', $output);

    if ($return_value) return $output;
    else echo $output;
}
/**
 * Renders the options form in Plugins->iBegin Share
 */
function iBeginShare_OptionsForm()
{
    global $ibegin_share_options, $ibegin_share_options_choices, $ibegin_share_plugins;
    
    if (isset($_POST['save']))
    {
        foreach (array_keys($ibegin_share_options) as $option)
        {
            if (!empty($_POST[$option])) update_option($option, $_POST[$option]);
            else update_option($option, '0');
        }
        foreach (array_keys($ibegin_share_plugins) as $plugin)
        {
            $option = 'ibegin_share_plugins_enable_'.$plugin;
            if (!empty($_POST[$option])) update_option($option, $_POST[$option]);
            else update_option($option, '0');
        }
    }
    
    ob_start();
    ?>
    <?php if (!empty($_POST['save'])) { ?>
    <div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
    <?php } ?>
    <form action="" method="post" id="ibegin_share-conf" name="ibegin_share">
        <div class="wrap">
            <h2><?php echo __('Configuration', 'ibegin_share');?></h2>
            <?php foreach ($ibegin_share_options as $option=>$description) { ?>
                <?php $current_value = get_option($option); ?>
                <p>
                    <?php if (!is_array($ibegin_share_options_choices[$option])) { ?>
                        <label><input type="checkbox" value="<?php echo $ibegin_share_options_choices[$option];?>"<?php if ($current_value == $ibegin_share_options_choices[$option]) echo ' checked="checked"'; ?> name="<?php echo $option;?>" /> <?php echo htmlspecialchars($description);?></label>
                    <?php } else { ?>
                        <label for="id_<?php echo $option;?>"><?php echo htmlspecialchars($description);?></label>
                        <select name="<?php echo $option;?>">
                        <?php foreach ($ibegin_share_options_choices[$option] as $choice=>$label) { ?>
                            <option value="<?php echo $choice;?>"<?php if ($current_value == $choice) echo ' selected="selected"'; ?>><?php echo htmlspecialchars($label);?></option>
                        <?php } ?>
                        </select>
                    <?php } ?>
                </p>
            <?php } ?>
            <p>You may also directly embed the share link through either <code>&lt;? iBeginShare_Button(); ?&gt;</code> or <code>&lt;? iBeginShare_TextLink(); ?&gt;</code>.</p>
			<p>By default, we can automatically figure out the title and URL of the page. If you wish to override it, the two variables would be <code>(title, url)</code> in the function call. Eg <code>&lt;? iBeginShare_Button('My Page','http://www.mypage.com/'); ?&gt;</p>
            <h2><?php echo __('Plug-ins', 'ibegin_share');?></h2>
            <p>Below are a list of the available plug-ins. You may disable any of these.</p>
            <?php foreach ($ibegin_share_plugins as $plugin=>$description) { ?>
                <?php $option = 'ibegin_share_plugins_enable_'.$plugin; ?>
                <?php $current_value = get_option($option); ?>
                <p>
                    <label><input type="checkbox" value="1"<?php if ($current_value == '1') echo ' checked="checked"'; ?> name="<?php echo $option;?>" /> Enable the <?php echo htmlspecialchars($description);?> plug-in.</label>
                </p>
            <?php } ?>
            <p>You can create your own plug-in for iBegin Share. More details at the <a href="http://labs.ibegin.com/share/">iBegin Share website</a>.</p>
            <p class="submit">
                <input type="submit" name="save" value="<?php echo __('Save Changes', 'ibegin_share');?>" />
            </p>
        </div>
    </form>
    <?
    ob_end_flush();
}

function iBeginShare_Menu()
{
    add_submenu_page('plugins.php', __('iBegin Share', 'ibegin_share'), __('iBegin Share', 'ibegin_share'), 'manage_options', 'ibegin-options', 'iBeginShare_OptionsForm');
}
function iBeginShare_RenderContentPage($post)
{
    global $ibegin_share_state;
    $ibegin_share_state = IBEGIN_SHARE_STATE_CONTENTPAGE;
    header('Content-Type: text/html');
    ob_start();
    ?>
        <h1><?php echo htmlspecialchars($post->post_title);?></h1>
        <?php echo apply_filters('the_content', $post->post_content);?>
    <?
    ob_end_flush();
    $ibegin_share_state = IBEGIN_SHARE_STATE_DEFAULT;
    die();
}
function iBeginShare_Pages() {
    global $ibegin_share_options;
    
    if (empty($_REQUEST['ibegin_share_action'])) return;
    switch ($_REQUEST['ibegin_share_action'])
    {
        case 'get_content':
            $id = $_REQUEST['id'];
            if (empty($id)) header('Location: '.get_bloginfo('wpurl'));
            if (!$post =& get_post($id)) header('Location: '.get_bloginfo('wpurl'));
            iBeginShare_RenderContentPage($post);
            break;
    }
}

?>