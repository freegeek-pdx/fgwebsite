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
	<div>
	<ul>
	<li><a href='/etc/news'>News</a></li>
	<li><a href='/etc/this-week-at-the-geek'>This Week at the Geek</a></li>
	<li><a href='/etc/opportunities'>Opportunities</a></li>
	</ul>
	</div>
	</li>"; } ?><?php if (is_page(606)) { 
	//for news page
	//========================================================
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
	<head>
		<title></title>
	</head>
	<body>
		<div>
			<ul>
				<li>
					<a href='/category/events'>Events</a>
				</li>
				<li>
					<a href='/category/media-coverage'>Media Coverage</a>
				</li>
				<li>
					<a href='/category/press-releases'>Press Releases</a>
				</li>
			</ul>		
		</div><?php }?>
			
			
		<?php echo "<ul>";
		//Handles the rest
		//======================================================
	/*		//if the page has a parent
			if ($post->post_parent) {
				 $children = wp_list_pages("depth=1&title_li=&child_of=".$post->post_parent."&echo=0");
				}
		//if its the about us page
			elseif (is_page('10')) {
				$children=wp_list_pages( 'echo=1&child_of=10&title_li=');
			}
			//if its the thriftstore page
			elseif (is_page('295')) {
				$children=wp_list_pages( 'echo=1&child_of=295&title_li=');
			}
			elseif($post->ancestors)
				{
					// now you can get the the top ID of this page
					// wp is putting the ids DESC, thats why the top level ID is the last one
					$ancestors = end($post->ancestors);
					$children = wp_list_pages("title_li=&child_of=".$ancestors."&echo=0");
					// you will always get the whole subpages list
				}
			
			//Everything else
			else {
			 $children = wp_list_pages("depth=5&title_li=&child_of=".$post->ID."&echo=0");
				if ($children) {
					echo $children;
				}}*/
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
