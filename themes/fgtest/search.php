<?php
// NOTES: 
// This template controls display of all "single post views".
// For singe page views see "page.php" in this directory.

	//remove_action('iBeginShare_Pages');
	remove_action('the_content', 'iBeginShare_Widget');
// Header
// ========================================================
	require_once TEMPLATEPATH . '/header.php';
	
	print '<h1>Search Results</h1>';
	
// Loop for 'Static' Page Content
// ========================================================
	if (have_posts()){
		while ( have_posts() ){
			the_post();
			
			// Heading
				print '<h2 id="page-' . get_the_ID() .'">';
				print '<a href="' . apply_filters('the_permalink', get_permalink() ) . '">';
				the_title( '','',true );
				print '</a>';
				edit_post_link('Edit', ' <span class="edit">[', ']</span>');
				print'</h2>';
			
			// Content
				print '<div class="entry">';
				the_excerpt();
				print '</div>';
		}
	}else{
		print '<p>Sorry, but we could not find what you are looking for.</p>';
	}

	print '<p>You might also want to check our <a href="/about/faq/">FAQ</a> or <a href="/about/sitemap/">sitemap</a>, or <a href="/about/general-info-request/">contact us</a>.</p>';
// Footer
// ========================================================
	require_once TEMPLATEPATH . '/footer.php';
	
?>
