<?php
/*
Filename: 		sidebar.php
Date: 			06-06-25
Copyright: 		2008, Frazier Media
Author: 		Christopher Frazier (cfrazier@fraziermedia.com)
Description: 	Multi-Author Template for WordPress (Subtle)
Requires:
*/

?>

<h2>Contact info</h2>

<p>1731 SE 10th Avenue, Portland, OR
Tuesday-Saturday
11:00am to 7:00pm
Sunday-Monday
CLOSED<br />
<a href="mailto:freegeek@freegeek.org">email freegeek</a>  
</p>
<?php

if (is_front_page() ) { 
    
    echo '<ul>
          <li><a href="/donate/">Donate</a></li>
          <li><a href="/volunteer/">Volunteer</a></li>
          <li><a href="/not sure/">Earn a Computer</a></li>
          </ul>';    
    } else { // do nothing
    
    }


?>
<!-- ?php sidebarEventsCalendar();? -->

<!--

<h3>Archives</h3>
<ul class="icon category">
	<?php wp_get_archives('type=monthly'); ?>
</ul>

<h3>Meta</h3>
<ul class="icon jump">
	<?php wp_register(); ?>
	<li><?php wp_loginout(); ?></li>
	<li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
	<li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
	<?php wp_meta(); ?>
</ul>
-->
