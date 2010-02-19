<?php
	
	// Try to add options
		#add_option( $name, $value = '', $deprecated = '', $autoload = 'yes' ) 

//FREEGEEKS SETTINGS 
/*
	fg_add_option('FG_HOME_PAGE_ID', 68);
	fg_add_option('FG_ABOUT_US_PAGE_ID', 10);
	fg_add_option('FG_MEDIA_PAGE_ID', 9);
	fg_add_option('FG_MEDIA_COVERAGE_PAGE_ID', 39 );
	fg_add_option('FG_PRESS_RELEASE_PAGE_ID', 37 );
	fg_add_option('FG_NEWS_CAT_ID', 6 );
	fg_add_option('FG_PRESS_RELEASE_CAT_ID', 3 );
	fg_add_option('FG_MEDIA_COVERAGE_CAT_ID', 5 );
*/



/* mike's comp
		fg_add_option( 'FG_HOME_PAGE_ID', 67 );
		fg_add_option( 'FG_ABOUT_US_PAGE_ID', 17 );
		fg_add_option( 'FG_MEDIA_PAGE_ID', 16 );
		fg_add_option( 'FG_MEDIA_COVERAGE_PAGE_ID', 44 );
		fg_add_option( 'FG_PRESS_RELEASE_PAGE_ID', 42 );
		fg_add_option( 'FG_NEWS_CAT_ID', 4 );
		fg_add_option( 'FG_PRESS_RELEASE_CAT_ID', 5 );
		fg_add_option( 'FG_MEDIA_COVERAGE_CAT_ID', 3 );
*/
		
		function fg_add_option( $name, $value ){
			$name = strtoupper( $name );
			
			add_option( $name, $value );
			
			$opt = get_option( $name );
			
			if( !empty( $opt ) )
				print '<p style="color:#080;"><strong>' . $name . '</strong> = ' . $opt . '</p>';
			else
				print '<p style="color:#f00;"><strong>' . $name . '</strong> does not exist in database.</p>';
		}
		
	// PAGE ID's WITH SPECIAL MEANING
		fg_define_option( 'FG_HOME_PAGE_ID' );
		fg_define_option( 'FG_ABOUT_US_PAGE_ID' );
		fg_define_option( 'FG_MEDIA_PAGE_ID' );
		fg_define_option( 'FG_MEDIA_COVERAGE_PAGE_ID' );
		fg_define_option( 'FG_PRESS_RELEASE_PAGE_ID' );
		fg_define_option( 'FG_NEWS_CAT_ID' );
		fg_define_option( 'FG_PRESS_RELEASE_CAT_ID' );
		fg_define_option( 'FG_MEDIA_COVERAGE_CAT_ID' );
	

	$fg_include_sidebar = true;
	
	$fg_single_column = get_post_meta( $post->ID, 'fg_single_column', true );
	
	// One column or 2?
	if ( $fg_single_column == true ){
		$fg_main_id = 'main_one_column';
		$fg_subnav_id = 'subnav_one_column';
		$fg_footer_id = 'footer_one_column';
	}else{
		$fg_main_id = 'main';
		$fg_subnav_id = 'subnav';
		$fg_footer_id = 'footer';
	}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> <?php } ?> <?php wp_title(); ?></title>

<link href="<?php bloginfo('stylesheet_url'); ?>" rel="stylesheet" media="screen" type="text/css" />

<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="icon" type="image/gif" href="<?php bloginfo('template_directory'); ?>/images/favicon.gif" />
<style type="text/css">
	#header li.page-item-<?php print FG_HOME_PAGE_ID; ?>{
		border-width:0;
		}
</style>
<?php wp_head(); ?>
</head>

<body>
	<div id="wrap">
	
		<div id="header">
			<a id="linkhome" href="<?php bloginfo('url') ?>"></a>
			<ul id="nav">
				<?php 
				// Top Level Navigation
				// ===================================================
					wp_list_pages('depth=1&title_li=0&sort_column=menu_order');
				?>
			</ul>
			<div id="header_bottom"></div>
		</div><!-- id=header -->
		
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
				<div id="<?php print $fg_subnav_id; ?>">
					<?php if ( !empty( $children ) ) { ?>
					<ul>
						<?php print strtolower( $children ); ?>
					</ul>
					<?php } ?>
				</div><!-- id=subnav -->
			
		
		<div id="<?php print $fg_main_id; ?>">
			<div id="content_gradient">
				<div id="content">
