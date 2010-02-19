<?php
// NOTES: 
// This template controls display of all "single post views".
// For singe page views see "page.php" in this directory.


// Header
// ========================================================
	require_once TEMPLATEPATH . '/header.php';
	
	
// Loop for Post Content
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
	
// Navigation for Posts
// ========================================================
?>	
	<div class="navigation">
		<div class="alignleft"><?php previous_post_link('&laquo; %link', 'Previous', true ); ?></div>
		<div class="alignright"><?php next_post_link('%link &raquo;', 'Next', true ); ?></div>
	</div>
	
	
<?php
// Footer
// ========================================================
	require_once TEMPLATEPATH . '/footer.php';
	
?>
