=== Exclude Pages ===
Contributors: simonwheatley
Donate link: http://www.simonwheatley.co.uk/wordpress-plugins/
Tags: get_pages, navigation, menu, exclude pages, hide pages
Requires at least: 2.2.3
Tested up to: 2.5
Stable tag: 1.4

This plugin adds a checkbox, “include this page in menus”, uncheck this to exclude pages from the 
page navigation that users see on your site.

== Description ==

This plugin adds a checkbox, “include this page in menus”, uncheck this to exclude pages from the 
page navigation that users see on your site.

Any issues: [contact me](http://www.simonwheatley.co.uk/contact-me/).

== Change Log ==

= v1.4 2008/01/02 =

* ENHANCEMENT: Now compatible with WP 2.5
* FIX: Pages are also excluded from the "Front page displays:" > "Posts page:" admin menu. (Reported by Ed Foley) This plugin now checks if it's within the admin area, and does nothing if it is.

= v1.3 2008/01/02 =

* FIXED: Descendant (e.g. child) pages were only being checked to a depth of 1 generation.
* FIXED: The link to visit the hidden ancestor page from an affected descendant page was hard-coded to my development blog URL. ([Reported by webdragon777](http://wordpress.org/support/topic/147689?replies=1#post-662909 "Wordpress forum topic"))
* FIXED: Stripped out some stray error logging code.

= v1.2 2007/11/21 =

* ENHANCEMENT: Child pages of an excluded page are now also hidden. There is also a warning message in the edit screen for any child page with a hidden ancestor, informing the person editing that the page is effectively hidden; a link is provided to edit the ancestor affecting the child page.

= v1.1 2007/11/10 =

* FIXED: Pages not created manually using "Write Page" were always excluded from the navigation, meaning the admin has to edit the page to manually include them. Pages created by other plugins are not always included in the navigation, if you want to exclude them (a less common scenario) you have to edit them and uncheck the box. ([Reported by Nudnik](http://wordpress.org/support/topic/140017 "Wordpress forum topic"))

== Description ==

This plugin adds a checkbox, “include this page in menus”, which is checked by default. If you uncheck 
it, the page will not appear in any listings of pages (which includes, and is *usually* limited to, your 
page navigation menus).

Pages which are children of excluded pages also do not show up in menu listings. (An alert in the editing screen, 
underneath the "include" checkbox allows you to track down which ancestor page is affecting child pages 
in this way.)

== Requests & Bug Reports ==

I'm simply noting requests & bug reports here, I've not necessarily looked into any of these.

*None!*

== Installation ==

1. Upload `exclude_pages.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Create or edit a page, and enjoy the frisson of excitement as you exclude it from the navigation

== Screenshots ==

There appears to be a [bug](http://wordpress.org/support/topic/167316?replies=1) in this plugin site, which is causing screenshots to
not be updated. This may mean there's not a WordPress 2.5 screenshot below.

1. WP 2.5 - Showing the control on the editing screen to exclude a page from the navigation
2. WP 2.5 - Showing the control and warning for a page which is the child of an excluded page
3. Pre WP 2.5 - Showing the control on the editing screen to exclude a page from the navigation
4. Pre WP 2.5 - Showing the control and warning for a page which is the child of an excluded page
