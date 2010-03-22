<?php
	$dhnd_image_dir = ABSPATH.'wp-content/header-images/';
	$dhnd_image_url_base = get_bloginfo('wpurl').'/wp-content/header-images/';

	$path = ABSPATH."wp-content/header-images/";
	
	if(is__writable($dhnd_image_dir) != true){
		echo '<div class="error"><p>';
			echo 'It looks like <strong>wp-content/header-images/</strong> is not writable or does not exist.<br /><br /> You will need to create this directory make this directory writable in order for the plugin to work.<br /><br />Maybe <a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank">This Article</a> from WordPress will help.';
		echo '</p></div><br />';
	}
?>
<div class="updated fade below-h2">
	<p>
	If you use this plugin and like it please consider donating to our development fund.<br /><br />
	Our developers create and maintain these plugins on their own time and any donations would be greatly appreciated. <strong>Even $1.00</strong> helps<br />
	<br />
	<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6LJ9BJN6EUFEY">Donate Now with Paypal</a>
	</p>
</div>