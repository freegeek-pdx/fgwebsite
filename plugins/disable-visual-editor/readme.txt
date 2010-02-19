=== Plugin Name ===
Contributors: chmac
Donate link: http://www.callum-macdonald.com/code/donate/
Tags: visual editor, disable visual editor, tinymce, disable tinymce
Requires at least: 2.0
Tested up to: 2.2.3
Stable tag: 1.0

This plugin disables the visual editor for all users across the whole site. You could also remove the wp-includes/js/tinymce/ directory.

== Description ==

This plugin disables the visual editor for all users, every time a user updates their profile. This means even if a user checks the box "User visual editor", the setting will not be saved.

To completely remove the visual editor, delete or rename the wp-includes/js/tinymce/ directory. This will remove the "Use visual editor" checkbox on user's profile pages and ensure that the visual editor cannot be loaded. Please note that you will need to delete or rename this directory after every WordPress upgrade.

== Installation ==

1. Download the wp_mail_smtp.php file.
2. Upload the file to your `/wp-contents/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Delete or rename the `wp-includes/js/tinymce/` directory.

== Frequently Asked Questions ==

= I still see the "Use visual editor" checkbox =

This checkbox should disappear when you delete or rename the `wp-includes/js/tinymce/` directory. Remember you will need to delete or rename this directory after every WordPress version upgrade.

= Can you add feature x, y or z to the plugin? =

Short answer: maybe.

By all means please contact me to discuss features or options you'd like to see added to the plugin. I can't guarantee to add all of them, but I will consider all sensible requests. [Click here to contact Callum](http://www.callum-macdonald.com/contact/ "Callum").

== Support Questions ==

If you have support questions not covered in this readme, [contact Callum](http://www.callum-macdonald.com/contact/ "Callum")
