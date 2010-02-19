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
			 <p class ="center">Helping the needy get nerdy since the beginning of the third millenium.  </p>
				<p class="center"> <a href="/contact/directions/">1731 SE 10th Avenue, Portland, OR</a> </p>
				<p class="center">Contact: <a href="/wordpress-feedback">email</a> | <a href="/contact/directions/"> phone</a></p>
			
				
			</div><!-- id=footer_content -->
			<div id="footer_bottom">
				<p class ="center">This site is optimized for <a href="http://www.mozilla.com/en-US/firefox/upgrade.html"<img src="http://fgdev.chasing-daylight.com/wp-content/themes/freegeekv2/images/Mozilla_Firefox.png" alt="Firefox" class="FF"/> Firefox</a>
				and is covered by a <a href="http://creativecommons.org/licenses/by-sa/3.0/"><img src="http://fgdev.chasing-daylight.com/wp-content/themes/freegeekv2/images/cc.png" alt="Creative Commons" class="FF"/> Creative Commons License</a></p>
			</div>
			

<?php transdukete(); ?>
			
<?php
if(function_exists("gltr_build_flags_bar")) { 
	gltr_build_flags_bar(); 

}
?>
		</div><!-- id=footer -->
		<?php wp_footer(); ?>
		<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
	</div><!-- id=wrap -->
</body>
</html>
