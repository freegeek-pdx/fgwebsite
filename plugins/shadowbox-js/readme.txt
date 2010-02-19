=== Plugin Name ===
Contributors: sivel
Tags: images, formatting, links, post, posts
Requires at least: 2.0
Tested up to: 2.5
Stable tag: 0.4

A media viewer application written entirely in JavaScript. Using Shadowbox,
website authors can display pictures, movies, websites, inline content and
more in all major browsers without navigating away from the linking page.

== Description ==

A media viewer application written entirely in JavaScript. Using Shadowbox,
website authors can display pictures, movies, websites, inline content and
more in all major browsers without navigating away from the linking page.

This plugin uses Shadowbox.js written my Michael J. I. Jackson. 

Javascript libraries supported are: YUI, Prototype + Scriptaculous, jQuery,
Ext, Dojo and MooTools.  YUI, Ext, Dojo and MooTools are included in the
plugin and Prototype + Scriptaculous and jQuery are used from the Javascript
libraries included with Wordpress.

There is another Shadowbox plugin floating around but it implements
Shadowbox.js differently, comes with no instructions, doesn't give you an
option of the JS library you use, and is written in Italian.

This plugin can also be used as a drop in lightbox replacement, without
requiring you to edit posts already using lightbox.

== Installation ==

1. Upload the `shadowbox-js` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Edit shadowbox-js.php and modify $jsLib and $lightCSS based on the comments
following each variable.

NOTE: See "Other Notes" for Upgrade and Usage Instructions as well as other
pertinent topics.

== Screenshots ==

1. An Image
2. A Website
3. A YouTube Video
4. A FLV video with included flvplayer

== Frequently Asked Questions ==

== Upgrade ==

1. Deactivate the plugin through the 'Plugins' menu in WordPress
1. Delete the previous `shadowbox-js` folder from the `/wp-content/plugins/`
directory
1. Upload the new `shadowbox-js` folder to the `/wp-content/plugins/`
directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Edit shadowbox-js.php and modify $jsLib and $lightCSS based on the comments
following each variable.

== Usage ==

1. Create a link in your post in the following format:

`<a href="http://domain.tld/directory/to/image.jpg"
rel="shadowbox[album]">Image</a>`

The above link can be to pretty much anything including websites, video files,
YouTube, Google Video, inline content.

1. Be sure to include `rel="shadowbox"` as this activates the plugin.
1. If `rel="shadowbox[album]"` is included the portion listed here as
`[album]` will group multiple pictures into an album called album. 
1. If you are using this as a lightbox replacement you do not need to change
rel="lightbox" to rel="shadowbox".  Shadowbox.js now supports rel="lightbox"
natively.
1. If you want to make a gallery/album and only want one link to display you
can now use class="hidden" to hide the additional links.

= NOTE: = Do not use the visual editor for doing the above use the code
editor.  When modifying this post in the future do not use the visual editor;
please use the code editor always.

== Additional File Payloads ==

= jQuery: =
`0.9K	1	HTML/Text
139.0K	4	JavaScript Files
1.9K	1	Stylesheet File
141.9K	Total size
6	HTTP request`

= Prototype + Scriptaculous: =
`1.0K	1	HTML/Text
197.5K	5	JavaScript Files
1.9K	1	Stylesheet File
200.5K	Total size
7	HTTP requests`

= YUI: =
`0.9K	1	HTML/Text
115.7K	3	JavaScript Files
1.9K	1	Stylesheet File
118.6K	Total size
5	HTTP requests`

= Ext: =
`1.0K	1	HTML/Text
153.4K	4	JavaScript Files
1.9K	1	Stylesheet File
156.4K	Total size
6	HTTP requests`

= Dojo: =
`0.8K	1	HTML/Text
104.8K	3	JavaScript Files
1.9K	1	Stylesheet File
107.7K	Total size
5	HTTP requests`

= MooTools: = 
`0.9K	1	HTML/Text
58.0K	3	JavaScript Files
1.9K	1	Stylesheet File
60.9K	Total size
5	HTTP requests`

== Change Log ==

= 0.4 (2008-04-10): =
* Updated to use assetURL for location to shadowbox files
* Cleaned up code and added extended comments
* Added extras.css with support for hidden class
* Added support to not include javascript libraries

= 0.3 (2008-02-26): =
* Updated Shadowbox.js to version 1.0 Final
* Added support for Ext, Dojo and MooTools Javascript Libraries
* Removed lightbox2shadowbox function/filter as Shadowbox.js now natively supports rel="lightbox"
* Consolidated repetitive code
* Removed images that were not in use
* Selected MooTools as the default as it contains the smallest payload

= 0.2 (2008-02-22): =
* Initial Public Release

== To Do ==

1. Determine why YouTube videos are not being resized correctly in Shadowbox

