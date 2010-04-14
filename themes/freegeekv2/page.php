<?php
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
				the_content('Read More...');
				print '</div>';
					
		}
		
	}
	
	
// Section for custom fields
// ========================================================
	//Meta tag variables
	$fg_cat_id = get_post_meta( $post->ID, 'fg_cat_id', true );
	$fg_title_cat_id = get_post_meta( $post->ID, 'fg_title_cat_id', true );
	$fg_numberposts = get_post_meta( $post->ID, 'fg_numberposts', true );
	$fg_get_tag = get_post_meta( $post->ID, 'fg_get_tag', true);
	$fg_list_titles = get_post_meta($post->ID, 'fg_list_titles', true);
	// for cats
	if( !empty( $fg_cat_id ) && !empty( $fg_numberposts )){
		$latest_post = get_posts( 'numberposts=' . $fg_numberposts . '&category=' . $fg_cat_id );
		//print it out
		foreach ( $latest_post as $post ) {
			setup_postdata($post);
			$more = 0; # VERY IMPORTANT!!!! We need to reset $more to zero so that the 'more tag' will function properly.
			print '<h3 id="post-' . get_the_ID() .'">' . the_title( '','',false ) . '</h3>';
			the_content( 'Read More...' );
		}}
	
		// for tags
	elseif(!empty( $fg_get_tag )){
		$latest_post = query_posts('tag=' . $fg_get_tag);
		//print it out
		foreach ( $latest_post as $post ) {
			setup_postdata($post);
			$more = 0; # VERY IMPORTANT!!!! We need to reset $more to zero so that the 'more tag' will function properly.
			print '<h3 id="post-' . get_the_ID() .'">' . the_title( '','',false ) . '</h3>';
			the_content( 'Read More...' );
		}}
// for just category titles
	elseif(!empty( $fg_list_titles) && !empty($fg_title_cat_id)){
		$latest_post = get_posts('category=' . $fg_title_cat_id . '&numberposts=0');
		//print it out
			foreach ( $latest_post as $post ) {
				setup_postdata($post);
				print '<a href="' . get_permalink() .'"><h3 id="post-' . get_the_ID() .'">' . the_title( '','',false ) . '</h3></a>';
			}}
// for shortening posts
			elseif(!empty( $fg_shorten_posts) && !empty($fg_cat_id) && !empty($fg_numberposts) ){
				$latest_post = get_posts('category=' . $fg_cat_id . '&numberposts=' . $fg_numberposts );
				//print it out
					foreach ( $latest_post as $post ) {
						setup_postdata($post);
						$more = 0; # VERY IMPORTANT!!!! We need to reset $more to zero so that the 'more tag' will function properly.
						print '<a href="' . get_permalink() .'"><h3 id="post-' . get_the_ID() .'">' . the_title( '','',false ) . '</h3></a>';
						the_excerpt();
					}}
			
// Footer
// ========================================================
	require_once TEMPLATEPATH . '/footer.php';
	
?>
<a href="<?php bloginfo( 'url' ); ?>/contact/web-feedback/"><span class="feedback">Feedback</span></a>
