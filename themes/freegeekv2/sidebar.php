<!-- BEGIN SIDEBAR -->

<?php 
		if (is_front_page() || is_page(443) || is_page(445) || is_page(456)) : ?>
		
	<li>
			<div id='question'>
	<div class='Qone'><a href='<?php bloginfo( 'url' ); ?>/etc/need-a-computer/'>
	<img src='<?php bloginfo( 'template_url' ); ?>/images/person-comp.jpg' alt='Get a computer'/>
	</a> <p class='question-text'><a href='<?php bloginfo( 'url' ); ?>/etc/need-a-computer/'>I need a computer</a></p>
	</div>
	<div class='Qtwo'>
	<a href='<?php bloginfo( 'url' ); ?>/donate/what-we-take/'><img src='<?php bloginfo( 'template_url' ); ?>/images/donate.jpg' alt='Donate your used hardware.'/>
	</a><p class='question-text'><a href='<?php bloginfo( 'url' ); ?>/donate/what-we-take/'>I have a donation</a></p>
	</div>
	<div class='Qthree'>
	<a href='<?php bloginfo( 'url' ); ?>/etc/get-involved'><img src='<?php bloginfo( 'template_url' ); ?>/images/volunteer.jpg' alt='Volunteer with us.'/></a>
	<p class='question-text'><a href='<?php bloginfo( 'url' ); ?>/etc/get-involved'>I want to get involved</a></p>
	</div>
	<div class='Qfour'>
	<a href='<?php bloginfo( 'url' ); ?>/etc/want-to-learn'><img src='<?php bloginfo( 'template_url' ); ?>/images/learn.jpg' alt='Come participate in one of our learning opportunities.'/></a>
	<p class='question-text'><a href='<?php bloginfo( 'url' ); ?>/etc/want-to-learn'>I want to learn</a></p>
	</div>
	</div>
	</li>

	<?php endif; ?>

		<?php echo "<ul>";
		
				$post_ancestors = get_post_ancestors($post);
				if (count($post_ancestors)) {
				    $top_page = array_pop($post_ancestors);
				    $children = wp_list_pages('title_li=&child_of='.$top_page.'&echo=0');
				    $sect_title = get_the_title($top_page);
				} 
				
					//if its the about us page
						elseif (is_page('10')) {
							$children=wp_list_pages( 'echo=1&child_of=10&title_li=');
						}
						//if its the thriftstore page
						elseif (is_page('295')) {
							$children=wp_list_pages( 'echo=1&child_of=295&title_li=');
						}
						//if its the news page
						elseif (is_page('606')) {	
						    $children=wp_list_pages( 'echo=1&child_of=606&title_li=');  
						}
				elseif (is_page()) {
				    $children = wp_list_pages('title_li=&child_of='.$post->ID.'&echo=0&depth=2');
				    $sect_title = the_title('','', false);}
			
				if ($children) {
				   	echo $children;
				}
			?><?php echo "<li class='search'>"; 
					
		include_once TEMPLATEPATH . '/searchform.php' ;

		echo "</li> </ul>"; ?>
	</body>
</html>
