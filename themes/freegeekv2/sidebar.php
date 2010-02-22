<!-- BEGIN SIDEBAR -->
<?php 
	//check for home page 
	if (is_front_page() || is_page(443) || is_page(445) || is_page(456)) {
	echo "<li>
			<div id='question'>
	<div class='Qone'><a href='/etc/need-a-computer/'>
	<img src='http://fgdev.chasing-daylight.com/wp-content/themes/freegeekv2/images/person-comp.jpg' alt='Get a computer'/>
	</a> <p class='question-text'><a href='/etc/need-a-computer/'>I need a computer</a></p>
	</div>
	<div class='Qtwo'>
	<a href='/donate/what-we-take/'><img src='http://fgdev.chasing-daylight.com/wp-content/themes/freegeekv2/images/donate.jpg' alt='Donate your used hardware.'/>
	</a><p class='question-text'><a href='/donate/what-we-take/'>I have technology to donate</a></p>
	</div>
	<div class='Qthree'>
	<a href='/etc/get-involved'><img src='http://fgdev.chasing-daylight.com/wp-content/themes/freegeekv2/images/volunteer.jpg' alt='Volunteer with us.'/></a>
	<p class='question-text'><a href='/etc/get-involved'>I want to get involved</a></p>
	</div>
	<div class='Qfour'>
	<a href='/etc/want-to-learn'><img src='http://fgdev.chasing-daylight.com/wp-content/themes/freegeekv2/images/learn.jpg' alt='Come participate in one of our learning opportunities.'/></a>
	<p class='question-text'><a href='/etc/want-to-learn'>I want to learn</a></p>
	</div>
	</div>
	
	</li>"; } ?>
		
	<?php
	
 ?>
			
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
