</div>
				</div><!-- id=content -->


		<?php if ( $fg_include_sidebar ) : ?>
			<div id="sidebar_gradient">
				<div id="sidebar">
					<?php get_sidebar(); ?>
				</div><!-- id=sidebar -->
			</div><!-- id=sidebar_gradient -->

		<?php endif; ?>

			<div style="clear:both;"></div>
				
		</div><!-- id=main -->

		<div id="contentCap_btm"></div>

		<div id="<?php print $fg_footer_id; ?>">
		
			<div id="footer_content">
			 <ul id="footer-links">
			<li class="first"><a href="<?php bloginfo('url'); ?>/news/jobs/">Jobs</a></li>
			<!-- <li><a href="#">Media Center</a></li> -->
			<li><a href="<?php bloginfo('url'); ?>/about/FAQ">FAQ</a></li>
			<li><a href="<?php bloginfo('url'); ?>/terms">Privacy/Terms</a></li>
			<li><a href="<?php bloginfo('url'); ?>/contact">Contact Us</a></li>
			<li><a href="#">Site Map</a></li>
			</ul>
			</div><!-- id=footer_content -->
			<!--  <div id="footer_bottom">
			<ul>
			<li><a href="http://creativecommons.org/"><img src="
		 <?php bloginfo('url'); ?>/wp-content/themes/freegeekv2/images/cc.png" alt="Creative Commons"/></a></li>
			<li><a href="http://wordpress.org"><img src="<?php bloginfo('url'); ?>/wp-content/themes/freegeekv2/images/wp.gif" alt="Wordpress"/></a></li>
			</ul>
			</div> -->
			
		</div><!-- id=footer -->
		<?php wp_footer(); ?>
		<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
	</div><!-- id=wrap -->
</body>
</html>
