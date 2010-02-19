<div class="wrap">
	<h2>Dynamic Headers - About</h2>
<?php include("header.php"); ?>
	
	This plugin was written and is maintained by Dan Cannon of <a href="http://blog.nicasiodesign.com" target="_blank">Nicasio Design and Development</a>.<br />
	<br />
	Nicasio Design is a full service web design firm specializing in WordPress.<br />
	<br />
	Feel free to <a href="http://nicasiodesign.com/contact-us.php" target="_blank">Contact Us Today</a>  to discuss your next project.
	<br />
	<h2>Credits</h2>
	<strong>Dan Cannon</strong> - Lead Developer/Programmer<br />
	<strong>Chris Underwood</strong> - Lead Designer<br />
	<strong>Felix Figuereo</strong> - Project Management<br />
	
	<?php require_once (ABSPATH . WPINC . '/rss-functions.php'); ?>
	<?php $today = current_time('mysql', 1); ?>
	<div class="main">
	  <h2>Nicasio News</h2>

		<?php
		$rss = @fetch_rss('http://blog.nicasiodesign.com/feed/');
		if ( isset($rss->items) && 0 != count($rss->items) ) {
		?>
			<ol>
			<?php
			$rss->items = array_slice($rss->items, 0, 10);
			foreach ($rss->items as $item ) {
			?>
			<li>
			  <a href='<?php echo wp_filter_kses($item['link']); ?>'>
			  <?php echo wp_specialchars($item['title']); ?>
			  <small>-
				<?php echo human_time_diff( strtotime($item['pubdate'], time() ) ); ?>
				<?php _e('ago'); ?>
			  </small>
			  </a>
			</li>
		<?php
		}
		}
		?>
		</ol>
	<h2>Future Functionality</h2>
	- Ability to manage multiple dynamic media modules on each page.<br />
	- Ability to create your own dynamic media modules.<br />
	- More visual selection and management of your dynamic media library.<br />
	- Integration to allow use of WordPress Media Library.<br />
	- Ability to set header for different WordPress archive pages.<br />
	<br />
	<br />
</div>