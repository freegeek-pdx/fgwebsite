<?php
// NOTES: 
// This template controls display of all "single post views".
// For singe page views see "page.php" in this directory.


// Header
// ========================================================
	require_once TEMPLATEPATH . '/header.php';
	
	
// Loop for 'Static' Page Content
// ========================================================
	if (have_posts()){
		while ( have_posts() ){
			the_post();
			
			// Heading
			//	print '<h1 id="page-' . get_the_ID() .'">' . the_title( '','',false ); 
				edit_post_link('Edit', ' <span class="edit">[', ']</span>');
			//	print'</h1>';
			
			// Content Content
				print '<div class="entry">';
				the_content();
				print '</div>';
		}
	}
	
	
// News Posts
// 
// ========================================================
	
	$fg_cat_id = get_post_meta( $post->ID, 'fg_cat_id', true );
	$fg_numberposts = get_post_meta( $post->ID, 'fg_numberposts', true );
	
	if( !empty( $fg_cat_id ) && !empty( $fg_numberposts ) ){
		#print '<h2>' . __( 'Latest News' ) . '</h2>';
		$latest_post = get_posts( 'numberposts=' . $fg_numberposts . '&category=' . $fg_cat_id );
		foreach ( $latest_post as $post ) {
			setup_postdata($post);
			$more = 0; # VERY IMPORTANT!!!! We need to reset $more to zero so that the 'more tag' will function propperly.
			print '<h3 id="post-' . get_the_ID() .'">' . the_title( '','',false ) . '</h3>';
			the_content( 'Read More...' );
		}
		
	}
	
	
// Footer
// ========================================================
	require_once TEMPLATEPATH . '/footer.php';
	
?>
