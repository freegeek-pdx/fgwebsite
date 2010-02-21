
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
	fg_add_option('FG_EVENTS_CAT_ID', 8 );
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
		fg_define_option( 'FG_EVENTS_CAT_ID' );
	

	$fg_include_sidebar = true;
	
	$fg_single_column = get_post_meta( $post->ID, 'fg_single_column', true );
	
	// One column or 2?
	if ( $fg_single_column == true ){
		$fg_main_id = 'main_one_column';
		$fg_subnav_id = 'subnav_one_column';
		$fg_footer_id = 'footer_one_column';
		$fg_include_sidebar = false;
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

<link href="<?php bloginfo('stylesheet_url'); ?>" rel="stylesheet" media="screen, print" type="text/css" />


<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<link rel="icon" type="image/gif" href="<?php bloginfo('template_directory'); ?>/images/favicon.gif" />

<style type="text/css">
	#header li.page-item-<?php print FG_HOME_PAGE_ID; ?>{
		border-width:0;
		}
</style>

<?php wp_head(); ?>

        <script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/scripts/jquery-1.2.6.min.js"></script>
</head>

<body>

<div id="wrap">

  <div id="header" onclick="location.href=' <?php bloginfo('url'); ?>';" style="cursor: pointer;"><?php show_media_header(); ?>
<div id="headerText"><a href="/etc/directions" class="addr">1731 SE 10th Avenue, Portland, OR</a>        <a href="/etc/directions" class="hour">Tuesday - Saturday 11am - 7pm</a></div>
	</div>

 <div id="contentCap_top">
<div id="nav">
	<?php
	if (function_exists('dtab_list_tabs')) {
		dtab_list_tabs();
	}
	?>
	</div>
</div> 

   <div id="main">

      <?php
			if ( $fg_single_column == "true" ) {
			echo "<div id=\"content_gradient_onecolumn\">";
			} else {
			echo "<div id=\"content_gradient\">";
			} ?>
         <div id="content">


