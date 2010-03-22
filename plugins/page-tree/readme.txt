=== WordPress Page Tree ===
Contributors: mungobbq
Tags: admin, page, pages, tree, hierarchy, overview, navigation, widget, widgets, sidebar
Requires at least: 2.5
Tested up to: 2.9.2
Stable tag: trunk

Creates a expand/collapse tree for showing all your pages on your site or in your administration "pages" panel.

== Description ==

Do you have a WordPress site with lots of pages in a hierarchical structure? Are you trying to use WordPress like a "real" CMS? Then this plugin is for you!

Page Tree gives you a much-needed overview of your pages in the admininstration panel using a common expand/collapse menu, which lets you navigate your page structure like a folder tree in Windows Explorer.

Now you can quickly and easily find the page you want to edit using the expandable tree view, and also get an admin view site map of your complete site.

The page tree is displayed in the WordPress administration panel, under your regular "Pages" menu. See the screen shot for a better understanding of how it works. 

If you want, you can use the sidebar widget to show the page tree on your site. Or you can use the pagetree shortcode to display the tree anywhere you want in your site. Finally, you can also use the pagetree_public() function to display the tree in using your templates.

== Changelog ==

= 2.8 =
* New localization functions. If you want to help translate page tree to your language, contact me!
* Added Belorussian translation, thanks to [FatCow](http://www.fatcow.com/ "Fat Cow")

= 2.7 =
* Admin page tree now has links to the published versions of the pages, on the site. 
* New Javascript method for launching widget/public tree, which works with themes that do not include jQuery.

= 2.6.1 =
* Emergency release due to typo in CSS link

= 2.6 =
* Reverted CSS back to inclusion in the body depending on use, to prevent JS and CSS being included on every page of the site, even if the tree was not used.
* Moved JS inclusions to the footer for compatibility
* Added shortcode syntax for displaying the tree

= 2.5 =
* On suggestion from "bitjungle", moved script and css inclusion to head for HTML validation goodness.
* Reverted to requiring user level 7 (at least editor) to see the Page Tree inside WP Admin.

= 2.4 =
* Added support 2.9 trash: Pages in the trash are no longer displayed at all in the tree.

= 2.3 =
* New public tree and widget options for designating page tree root and limiting range of pages shown in the tree.

= 2.2 =
* Now letting users with ALL capabilities see and use the page tree.

= 2.1 =
* Added PHP function for template use: `pagetree_public($expand = false, $show_controls = false)`. Defaults are "false" for both, which gives you a "naked" collapsed tree. 

= 2.0 =
* Added sidebar widget support for displaying the page tree on your site.

= 1.4 =
* Removed short tags for better compatibility

= 1.3 = 
* Bugfix for fresh install of 2.7.1

= 1.2 =
* Minor bugfix to prevent empty tree if there are no nested pages.

= 1.1 =
* Added unpublished pages to the page tree. Pages that are unpublished/drafts/future will now be shown in the tree, but with a strike-through to let you know that they are not published.

= 1.0 = 
* First stable version of plugin.

== Installation ==

Quick and easy installation:

1. Upload the folder `wppagetree` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. (Optional) Use the sidebar widget or the template function to display the tree on your site. 
1. Done!

== Frequently Asked Questions ==

= OK, so the page tree is useful in my admin area, but HOW do I display the tree on my site, for easier page navigation? =

There are three ways. Simplest is to use the sidebar widget, if your template is widgetized. Simply drag the "Page tree" widget to where you want it - set its name and whether you want to display the tree collapsed or expanded by default, and if you want to show or hide the expand/collapse controls.

There is also a shortcode for including the tree in your pages/posts if you want to. The syntax is:

`[pagetree]`

... for a plain simple tree without controls. 

The complete options are

`[pagetree expand=0 show_controls=0 only_subpages=0 child_of=0]`

... using the same attribute syntax as the function below. 

Or you can use the pagetree_public function which lets you use the tree anywhere on your site: 

`<?php pagetree_public($expand = false, $show_controls = false, $only_subpages = false, $child_of = false); ?>`

Defaults are "false" for all options, which gives you a "naked", complete collapsed page tree. 

So, just use `pagetree_public()` for a simple, naked tree. 

= What does this plugin actually do? =

This plugin simply provides an easier way for administrators/authors to navigate a large collection of pages in your WordPress installation. It adds a new submenu to your "pages" navigation menu which takes you to a list of all your published pages in a collapse/expand tree. 

= I can't see my unpublished/hidden pages! =

Well, yes and no. The sidebar widget and template function only displays published pages, since it is meant for use on your public site. The admin tree displays ALL pages. 

== Screenshots ==

1. The page tree in action
2. New option in your pages administration panel
3. Widget control for displaying the page tree on your site

== Wishlist / Coming attractons ==

Do you have suggestions? Feel free to contact me at mans@mansjonasson.se


