=== Reorder Gallery ===
Contributors: aaroncampbell
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=paypal%40xavisys%2ecom&item_name=Reorder%20Gallery%20Plugin&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: gallery, photos, order, reorder
Requires at least: 2.5
Tested up to: 2.5.2
Stable tag: 1.0.1

Allows you to reorder your gallery images with the ease of drag and drop. Resquires PHP5.

== Description ==

Gives you the ability to simply drag and drop to reorganize your images how you
want them to appear in your gallery.  If drag and drop isn't your thing, it also
adds an "Order" field to the images in your gallery, much like pages have, which
allows you to reorder them to display in whatever order you want.  Requires PHP5.

If you are having problems sorting since version 1.0.1 of this plugin, read this:

In order to fix another bug, I had to use the filename as a "handle" to drag by.
When you move your cursor over the name of the file, it should change to a
"move" cursor, at which time you can drag/drop the image as before.

== Installation ==

1. Verify that you have PHP5, which is required for this plugin.
1. Upload the whole `reorder-gallery` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= I'm having problems sorting since version 1.0.1 of this plugin! =

In order to fix another bug, I had to use the filename as a "handle" to drag by.  When you move your cursor over the name of the file, it should change to a "move" cursor, at which time you can drag/drop the image as before.

= Once I have them in the order I want them, what do I have to do? =

Simply click the "Save all changes" button to save their order.

= How do I change the order? =

Each image will have an "Order" field.  The lower the number in that field, the earlier in the gallery it is.  If you use the drag and drop sorting, this field is updated for you.

= What if some images have the same value? =

If images have the same number, they will be ordered with the most recently added last.
