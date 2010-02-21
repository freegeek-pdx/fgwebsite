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
			<li class="first">Jobs</li>
			<li>Media Center</li>
			<li>FAQ</li>
			<li>Privacy/Terms</li>
			<li>Contact Us</li>
			<li>Site Map</li>
			</ul>
			</div><!-- id=footer_content -->
			<div id="footer_bottom">
			
			</div>
			
		</div><!-- id=footer -->
		<?php wp_footer(); ?>
		<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
	</div><!-- id=wrap -->
</body>
</html>
