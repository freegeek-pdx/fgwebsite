
		<ul>
			<?php
				// Widgetized sidebar content
				if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) {
					// This is where default sidebar content goes - which will be used only if no widgets are defined
				}
				
				// "News" category or posts in "News" category
				if ( is_page( FG_ABOUT_US_PAGE_ID ) || is_category( FG_NEWS_CAT_ID ) || in_category( FG_NEWS_CAT_ID ) ) {
					print fg_sidebar_cat_posts( FG_NEWS_CAT_ID );
				}
				
				// "Media Coverage" category or posts in "Media Coverage" category
				elseif ( is_page( FG_MEDIA_COVERAGE_PAGE_ID ) || is_category( FG_MEDIA_COVERAGE_CAT_ID ) || in_category( FG_MEDIA_COVERAGE_CAT_ID ) ) {
					print fg_sidebar_cat_posts( FG_MEDIA_COVERAGE_CAT_ID );
				}
				
				// "Press Release" category or posts in "Press Release" category
				elseif ( is_page( FG_PRESS_RELEASE_PAGE_ID ) || is_category( FG_PRESS_RELEASE_CAT_ID ) || in_category( FG_PRESS_RELEASE_CAT_ID ) ) {
					print fg_sidebar_cat_posts( FG_PRESS_RELEASE_CAT_ID );
				}
				
				// Print list of categories for Single post view
				if ( is_single() ){
					print wp_list_categories( 'title_li=<h2>' . __('Categories') . '</h2>&echo=0;' );
				}

?>
	<?php include_once TEMPLATEPATH . '/searchform.php' ?>
		</ul>
