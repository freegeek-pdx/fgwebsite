<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
		<div class="post">
	
			<h1 class="posttitle" id="post-<?php the_ID(); ?>"> <?php the_title(); ?></h1>
			
			<div class="postentry">
				<?php the_content("<p>Read more...</p>"); ?>
			<?php wp_link_pages(); ?>
			</div>

			<p class="postmeta">
			<?php the_time('F j, Y') ?>  
			&#183; 
			<?php if (the_category(', '))  the_category(); ?>
			<?php if (get_the_tags()) the_tags(); ?>
			<?php edit_post_link(__('Edit'), ' &#183; ', ''); ?>
			</p>
			
		</div>
		
		<?php comments_template(); ?>
				
	<?php endwhile; else : ?>

		<h2><?php _e('Not Found'); ?></h2>

		<p><?php _e('Sorry, but the page you requested cannot be found.'); ?></p>
		
		<h3><?php _e('Search'); ?></h3>
		<?php the_tags('<p>Tags: ', ', ', '</p>'); ?>
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	<?php endif; ?>
<?php the_tags('<p>Tags: ', ', ', '</p>'); ?>

<?php require_once TEMPLATEPATH . '/footer.php'; ?>
