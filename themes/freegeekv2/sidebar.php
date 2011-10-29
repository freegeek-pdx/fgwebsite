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

<div id="sidebar_links">	
		<?php 
		if (!is_tree('10')) {
	
				$post_ancestors = get_post_ancestors($post);
				if (count($post_ancestors)) {
				    $top_page = array_pop($post_ancestors);
				    $children = wp_list_pages('title_li=&child_of='.$top_page.'&echo=0&depth=1');
				    $sect_title = get_the_title($top_page);

				} 
					
					//if its the thriftstore page
						elseif (is_page('295')) {
							$children=wp_list_pages( 'echo=1&child_of=295&title_li=&depth=1');
						}
						//if its the news page and some children.
						elseif (is_page('606') || is_page('1129') || is_page('1121') || is_page('1123') || is_page('1137') || is_page('284')) {	
						    $children=wp_list_pages( 'echo=1&child_of=606&title_li=&depth=1&exclude=1131') + wp_list_pages('echo=1&include=24&title_li=&depth=1');  
						}
						// About
						elseif (is_page('2')) {	
						    $children=wp_list_pages( 'echo=1&child_of=2&title_li=&depth=1');  
						}
				elseif (is_page() && !is_page(10)) {

				    $children = wp_list_pages('title_li=&child_of='.$post->ID.'&echo=0&depth=1');
				    $sect_title = the_title('','', false);}
			
				if ($children) {
				   	echo $children;
				}

			}
			if (is_tree('10')) {
				echo "
				<span class='about-list-sidebar'>Who we are</span>
				<ul class='about-list'>
				<li><a href='/board'>Board of Directors</a></li> 
				<li><a href='/staff'>Staff</a></li> 

				<li><a href='/volunteers'>Volunteers</a></li>
				</ul>
				<span class='about-list-sidebar'>What we do</span>
				<ul class='about-list'>
				<li><a href='/mission'>Mission</a></li> 
				<li><a href='/grants'>Hardware Grants</a></li> 
				<li><a href='/about/recycle'>Recycle</a></li> 
				<li><a href='/reuse'>Reuse</a></li> 
				<li><a href='/education'>Education</a></li> 
				<li><a href='/techsupport'>Tech Support</a></li>
				<li><a href='/media'>Media Info</a></li> 
				</ul>
				<span class='about-list-sidebar'>How we do it</span>
				<ul class='about-list'>
				<li><a href='/foss'>Free & Open Source</a></li> 
				<li><a href='/tools'>The Tools We Use</a></li> 
				<li><a href='/consensus'>Consensus</a></li>
				</ul>
				";
			}
			?>
</div>				
			<?php echo "<li class='search'>"; 
					
		include_once TEMPLATEPATH . '/searchform.php' ;

		echo "</li> </ul>"; ?>
	</body>
</html>
