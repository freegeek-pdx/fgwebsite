<?php require_once TEMPLATEPATH . '/header.php';
	
	if ( have_posts() ) {
		// Hack. Set $post so that the_date() works.
		// NOTE: This hack comes from WordPress "Default" theme.
		$post = $posts[0]; 
		
		// If this is a category archive
		if ( is_category() )
			print '<h1>Archive for the &#8216;' . single_cat_title( '', false ) . '&#8217; Category</h1>';
		
		// If this is a tag archive
		elseif( is_tag() )
			print '<h1>Posts Tagged &#8216;' . single_tag_title( '', false ) . '&#8217;</h1>';
 	   
		// If this is a daily archive
		elseif ( is_day() )
			print '<h1>Archive for ' . get_the_time('F jS, Y') . '</h1>';
			
		// If this is a monthly archive
		elseif ( is_month() )
			print '<h1>Archive for ' . get_the_time('F, Y') . '</h1>';
			
		// If this is a yearly archive
		elseif ( is_year() )
			print '<h1>Archive for ' . get_the_time('Y') . '</h1>';
	  
		// If this is an author archive
		elseif ( is_author() )
			print '<h1>Author Archive</h1>';
			
		// If this is a paged archive
		elseif ( isset( $_GET['paged'] ) && !empty( $_GET['paged'] ) )
			print '<h1 class="pagetitle">Blog Archives</h1>';
		
		// Loop for posts
		while ( have_posts() ){
			the_post();
			
			// Heading
				print '<h2 id="post-' . get_the_ID() .'">' . the_title( '','',false );
					edit_post_link('Edit', ' <span class="edit">[', ']</span>');
				print'</h2>';
			
			// Content Content
				print '<div class="entry">';
					the_content();
				print '</div>';
		}
	}
?>

	<div class="navigation">
		<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
		<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
	</div>
	
<?php
	
	
	require_once TEMPLATEPATH . '/footer.php';
	
?>
