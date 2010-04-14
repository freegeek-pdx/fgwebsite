<?php
	require_once TEMPLATEPATH . '/header.php';
	
	query_posts('page_id=68');
	if (have_posts()) :
		while (have_posts()) : the_post();
?>
			<div class="post" id="post-<?php the_ID(); ?>">
				<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a> <?php edit_post_link('Edit', '<span class="edit">', '</span>'); ?></h1>
				<div class="entry">
					<?php the_content('Read More...'); ?>
				</div>
			</div>

<?php
		endwhile;
	endif;
	// End "Page Loop

	// Begin Newset Post Loop.
	print '<h2>' . __( 'Latest News' ) . '</h2>';

	$latest_post = get_posts('numberposts=1');
	foreach($latest_post as $post) :
	setup_postdata($post);
	?>
	<h3><a href="<?php the_permalink(); ?>" id="post-<?php the_ID(); ?>"><?php the_title(); ?></a></h3>
<?php 
		the_content('Read More...');
	endforeach;
	print '<p><a href="/about">' . __( 'Click here for more news.' ) . '</a></p>';	
	require_once TEMPLATEPATH . '/footer.php';
?>
<a href="<?php bloginfo( 'url' ); ?>/contact/web-feedback/"><span class="feedback">Feedback</span></a>
	
