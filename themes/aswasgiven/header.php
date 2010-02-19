<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

	<title>
	<?php if (is_home () ) { bloginfo('name'); echo ' &raquo; ' ; bloginfo('description'); }
	elseif ( is_category() ) { single_cat_title(); echo ' &raquo; ' ; bloginfo('name'); }
	elseif (is_single() ) { single_post_title(); }
	elseif (is_page() ) { single_post_title();  echo ' &raquo; ' ; bloginfo('name');}
	else { wp_title(''); echo ' &raquo; '; bloginfo('name');} ?>
	</title>
	
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->

	<style type="text/css" media="screen">
		@import url( <?php bloginfo('stylesheet_url'); ?> );
	</style>
	
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php wp_get_archives('type=monthly&format=link'); ?>

	<?php wp_head(); ?>
<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/images/favicon.ico" />

	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/scripts/jquery-1.2.6.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/scripts/main.js"></script>
	
</head>

<body>
<div id="wrap">
  <div id="header">
  </div>
  <?php include (TEMPLATEPATH . '/nav.php'); ?>
 <div id="contentCap_top"></div> 
<div id="main">
  
      <div id="right-column">