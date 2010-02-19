<?php
/*
Filename: 		comments.php
Date: 			06-06-25
Copyright: 		2006, Glued Ideas
Author: 		Christopher Frazier (cfrazier@gluedideas.com)
Description: 	Multi-Author Template for WordPress (Subtle)
Requires:
*/

$aOptions = get_option('gi_subtle_theme');

load_theme_textdomain('gluedideas_subtle');

// Do not delete these lines
if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) { die ('Please do not load this page directly. Thanks!'); }

if (!empty($post->post_password)) { // if there's a password
	if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
		echo('<p class="nocomments">' . __("This post is password protected. Enter the password to view comments.", 'gluedideas_subtle') . '<p>');
		return;
	}
}

$iCommentCount = 0;

?>

<div id="comment_area">

<?php if ('open' == $post->comment_status) : ?>

	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="form_comments" class="prominent reduced"><div class="inner">
	
		<h2><?php _e("Write a Comment", 'gluedideas_subtle'); ?></h2>
		<p><?php _e("Take a moment to comment and tell us what you think.  Some basic HTML is allowed for formatting.", 'gluedideas_subtle'); ?></p>
	
<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
		<p><?php _e("You must be logged in to post a comment.", 'gluedideas_subtle'); ?>  <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>"><?php _e("Click here to login.", 'gluedideas_subtle'); ?></a></p>
<?php else : ?>
	
<?php if ( $user_ID ) : ?>
	
		<p><?php _e("Logged in as", 'gluedideas_subtle'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. | <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account"><?php _e("Click to log out", 'gluedideas_subtle'); ?></a>.</p>
	
<?php else : ?>
	
		<p><input type="text" id="input_comments_name" name="author" value="<?php echo $comment_author; ?>" class="input standard" /> <label for="input_comments_name"><?php _e("Name", 'gluedideas_subtle'); ?> <?php if ($req) echo "(required)"; ?></label></p>
		<p><input type="text" id="input_comments_email" name="email" value="<?php echo $comment_author_email; ?>" class="input standard" /> <label for="input_comments_email"><?php _e("E-mail", 'gluedideas_subtle'); ?> <?php if ($req) echo "(required)"; ?></label></p>
		<p><input type="text" id="input_comments_url" name="url" value="<?php echo $comment_author_url; ?>" class="input standard" /> <label for="input_comments_url"><?php _e("Website", 'gluedideas_subtle'); ?></label></p>
		
<?php endif; ?>
	
		<p><input type="checkbox" id="input_allow_float" name="allow_float" value="true" /> <label for="input_allow_float"><?php _e("Allow comment box to float next to comments.", 'gluedideas_subtle'); ?></label></p>

		<p><textarea name="comment" id="input_comment" rows="10" cols="40" class="input textarea" ><?php _e("Type your comment here.", 'gluedideas_subtle'); ?></textarea></p>
	  
		<p><input type="submit" id="input_comments_submit" name="submit" value="Submit Comment"/> <input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" /></p>
	
<?php do_action('comment_form', $post->ID); ?>
	
<?php endif; ?>

	</div></form>

<?php endif; ?>

	<div id="loop_comments">
	
		<a name="comments"></a>
		
		<h2><?php _e("Reader Comments", 'gluedideas_subtle'); ?></h2>
		
<?php if ($comments) : ?>
	
<?php foreach ($comments as $comment) : ?>
		<a name="comment-<?php comment_ID() ?>"></a>
		<div id="comment_<?php comment_ID() ?>" class="comment">
			<dl class="metadata reduced">
<?php if (function_exists('gravatar') && $aOptions['show_gravatar']) : ?>
<?php if ($aOptions['gravatar_default'] ==  '') { $gravatar_default = get_stylesheet_directory_uri() . '/styles/' . $aOptions['style'] . '/gravatar.gif'; } else { $gravatar_default = $aOptions['gravatar_default']; } ?>
				<dt class="comment_number gravatar">Gravatar:</dt> <dd class="comment_number"><a href="http://www.gravatar.com/" title="<?php _e('What is this?', get_bloginfo('name')); ?>"><img border="0" src="<?php gravatar($aOptions['gravatar_rating'], 40, $gravatar_default); ?>" class="gravatar" alt="" /></a></dd>
<?php else : ?>
				<dt class="comment_number"><?php _e("Comment Number:", 'gluedideas_subtle'); ?></dt> <dd class="comment_number"><?php $iCommentCount++; echo($iCommentCount); ?></dd>
<?php endif; ?>
				<dt class="writer"><?php _e("Written by:", 'gluedideas_subtle'); ?></dt> <dd class="writer"><?php comment_author_link() ?><br /></dd>
				<dt class="timedate"><?php _e("Posted on:", 'gluedideas_subtle'); ?></dt> <dd class="timedate"><?php comment_date() ?> at <?php comment_time() ?> <?php edit_comment_link('Edit','',''); ?></dd>
			</dl>
			<div class="content">
				<?php if ($comment->comment_approved == '0') { echo('<p>Your comment is awaiting moderation.</p>'); } ?>
				<?php comment_text() ?>
			</div>
		</div>
	
<?php endforeach; ?>
	
<?php else : ?>
	
	<?php if ('open' == $post->comment_status) : ?> 
				<p><?php _e("Be the first to leave a comment!", 'gluedideas_subtle'); ?></p>
				
	<?php else :  ?>
				<p><?php _e("Sorry, comments are closed.", 'gluedideas_subtle'); ?></p>
				
	<?php endif; ?>
		
<?php endif; ?>
	
	</div>
	
	<br class="clear" />

	<script language="JavaScript" type="text/javascript" src="<?php bloginfo('template_directory'); ?>/assets/js/form_comments.js"></script>

</div>
