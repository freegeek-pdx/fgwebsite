</div>
				</div><!-- id=content -->

	$fg_include_sidebar = true;
	
	$fg_single_column = get_post_meta( $post->ID, 'fg_single_column', true );
	
	// One column or 2?
	if ( $fg_single_column == true ){
		$fg_main_id = 'main_one_column';
		$fg_subnav_id = 'subnav_one_column';
		$fg_footer_id = 'footer_one_column';
		$fg_include_sidebar = false;
	}else{
		$fg_main_id = 'main';
		$fg_subnav_id = 'subnav';
		$fg_footer_id = 'footer';
	}

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
			<li>|</li>
			<!-- <li><a href="#">Media Center</a></li> -->
			<li><a href="<?php bloginfo('url'); ?>/about/FAQ">FAQ</a></li>
			<li>|</li>
			<!-- <li><a href="<?php bloginfo('url'); ?>/terms">Privacy/Terms</a></li>
			<li>|</li> -->
			<li><a href="<?php bloginfo('url'); ?>/contact">Contact Us</a></li>
			<li>|</li>
				<li><a href="<?php bloginfo('url'); ?>/about/directions">Directions</a></li>
				<li>|</li>
			<li><a href="<?php bloginfo('url'); ?>/sitemap">Site Map</a></li>
			</ul>
<!--
			<span id="cc">
				<a href="http://creativecommons.org/"><img src="
			 <?php bloginfo('url'); ?>/wp-content/themes/freegeekv2/images/cc.png" alt="Creative Commons"/></a>
			</span>
-->
			</div><!-- id=footer_content -->
			 <div id="footer_bottom">	
			</div> 
			
		</div><!-- id=footer -->
		<?php wp_footer(); ?>
		<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
	</div><!-- id=wrap -->
</body>
</html>
