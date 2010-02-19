<?php
/*
Template Name: Calendar
*/

// Header
// ========================================================
	require_once TEMPLATEPATH . '/header.php';
	
	
// Loop for 'Static' Page Content
// ========================================================
	if (have_posts()){
		while ( have_posts() ){
			the_post();
			
			// Heading
				print '<h1 id="page-' . get_the_ID() .'">' . the_title( '','',false );
				edit_post_link('Edit', ' <span class="edit">[', ']</span>');
				print'</h1>';
			
			// Content Content
				print '<div class="entry">';
				the_content();
				print '</div>';
		}
	}
?>

<iframe
src="http://www.google.com/calendar/embed?src=v12a638n0tlbalu423hadvk6mc%40group.calendar.google.com&amp;height=614"
style=" border-width:0 " width="780" frameborder="0"
height="614"></iframe>

<?php
	$fg_include_sidebar = false;
	require_once TEMPLATEPATH . '/footer.php';
?>
