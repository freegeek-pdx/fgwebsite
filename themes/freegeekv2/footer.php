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
				<p class="center">Helping the needy get nerdy since the beginning of the third millennium</p>
				
				
			</div><!-- id=footer_content -->
			<div id="footer_bottom">
				<p class="center">Stuff on this site is covered by a <a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons License</a></p>
			</div>
			
			<p class="center">
				<img src="<?php bloginfo('template_directory')  ?>/images/footer-logo.gif" alt="Free Geek" />
				<img src="<?php bloginfo('template_directory')  ?>/images/wp.gif" alt="Free Geek" />	
			</p>

		</div><!-- id=footer -->
		<?php wp_footer(); ?>
		<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
	</div><!-- id=wrap -->
</body>
</html>
