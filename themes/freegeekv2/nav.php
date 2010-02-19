<div id="nav">
 <ul>
				<?php
                                // Top Level Navigation
                                // ===================================================
                                        wp_list_pages('depth=1&title_li=0&sort_column=menu_order');
                                ?>
 </ul>
</div>


                <?php
                // Second Level Navigation
                // ===================================================  
                        
                        // No Nav on the Homepage
                        if( is_page( FG_HOME_PAGE_ID ) ) {
                                $children = false;
                        }
                        
                        // "News" category or posts in "News" category
                        elseif( is_category( FG_NEWS_CAT_ID ) || in_category( FG_NEWS_CAT_ID ) ) {
                                $children = wp_list_pages( 'depth=1&title_li=&child_of=' . FG_ABOUT_US_PAGE_ID . '&echo=0&sort_column=menu_order');
                        }
                        
                        // "Media Coverage" category or posts in "Media Coverage" category
                        elseif( is_category( FG_MEDIA_COVERAGE_CAT_ID ) || in_category( FG_MEDIA_COVERAGE_CAT_ID ) ) {
                                $children = wp_list_pages( 'depth=1&title_li=&child_of=' . FG_MEDIA_PAGE_ID . '&echo=0&sort_column=menu_order');
                        }
                        
                        // "Press Release" category or posts in "Press Release" category
                        elseif( is_category( FG_PRESS_RELEASE_CAT_ID ) || in_category( FG_PRESS_RELEASE_CAT_ID ) ) {
                                $children = wp_list_pages( 'depth=1&title_li=&child_of=' . FG_MEDIA_PAGE_ID . '&echo=0&sort_column=menu_order');
                        }
                        
                        // If is page that has ancestors
                        elseif( is_page() && !empty( $post->post_parent ) ) {
                                $children = wp_list_pages( 'depth=1&title_li=&child_of=' . fg_root_parent( $post ) . '&echo=0&sort_column=menu_order' );
                        }
                        
                        else // No Page Parents
                                $children = wp_list_pages( 'depth=1&title_li=&child_of=' . $post->ID . '&echo=0&sort_column=menu_order' );
                        
                        ?>
                                <div id="subnav">
                                        <?php if ( !empty( $children ) ) { ?>
                                        <ul>
                                                <?php print strtolower( $children ); ?>
                                        </ul>
                                        <?php } ?>
                                </div><!-- id=subnav -->


<!--
<span id="home_s">
<li>	<a href="#">Test 1 Home</a></li>
<li>	<a href="#">Test 2 Home</a></li>
<li>	<a href="#">Test 3 Home</a></li>
<li>	<a href="#">Test 4 Home</a></li>
</span>
<span id="about_s">
<li>	<a href="/about/#">About us</a></li>
<li>	<a href="/overview/#">Overview</a></li>
<li>	<a href="/structure/#">Structure</a></li>
<li>	<a href="/awards/#">Awards</a></li>
<li>	<a href="/affiliations/#">Affiliations</a></li>
<li>	<a href="/media/#">Media</a></li>
<li>	<a href="/jobs/#">Jobs</a></li>
</span>
<span id="donate_s">
<li>	<a href="/donate/#">What we take</a></li>
<li>	<a href="/security/#">Security</a></li>
<li>	<a href="/recycle/#">Recycle standards</a></li>
<li>	<a href="/reuse/#">Reuse</a></li>
<li>	<a href="/pickups/#">Arrange a pickup</a></li>
</span>
<span id="connect_s">
<li>	<a href="/philanthropy/#">Contribute</a></li>
<li>	<a href="/volunteer/#">Volunteer</a></li>
<li>	<a href="/e-newsletter/#">E-newsletter</a></li>
<li>	<a href="/events/#">Events</a></li>
<li>	<a href="/partners/#">Community Partners</a></li>
<li>	<a href="/sales/#">Thrift Store</a></li>
</span>
<span id="community_s">
<li>	<a href="/grants/#">Hardware Grants</a></li>
<li>	<a href="/grants/past-recipients/#">Past Recipients</a></li>
<li>	<a href="/gap/#">Geek Access Points</a></li>
<li>	<a href="/stories/#">Stories</a></li>
<li>	<a href="/grants/apply/#">Grant Application</a></li>
</span>
<span id="resources_s">
<li>	<a href="/ecycles/#">Oregon E-cycles</a></li>
<li>	<a href="/intergalactic/#">Free Geek Intergalactic</a></li>
<li>	<a href="/faq/#">FAQ</a></li>
<li>	<a href="/foss/#">FOSS</a></li>
<li>	<a href="/links/#">Links</a></li>
<li>	<a href="/calendar/#">Calendar</a></li>
</span>
<span id="contact_s">
	<a href="#">Test 1 contact</a>
	<a href="#">Test 2 contact</a>
	<a href="#">Test 3 contact</a>
	<a href="#">Test 4 contact</a>
</span>

</div>
-->
