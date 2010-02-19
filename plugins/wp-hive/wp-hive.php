<?php
/*
 Plugin Name: WP Hive
 Plugin URI: http://wp-hive.com/overview/
 Description: Hive manager for multiple blogs with a single WP installation.
 Version: 0.4.2
 Author: ikailo
 Author URI: http://wp-hive.com
 */

/* --------
 * Robots.txt Manager
 * Don't forget to save the robots file as /robottxt/[db_prefix]robot.txt.
 * Also, you MUST ensure that no robot.txt file exists in the root directory.
 */
function hive_robots_check() {
	global $wpdb, $hostname;

	$robotsfile = WP_CONTENT_DIR . '/wp-hive/'. $hostname . '/robots.txt';
	if ( file_exists ( $robotsfile ) ) {
		readfile( $robotsfile );
		exit(0);
	}
}
add_action('do_robotstxt', 'hive_robots_check');


/* --------
 * Sitemap.xml Manager
 * Don't forget to set the sitemap generator to save the file as /sitemaps/[db_prefix]sitemap.xml.
 * Also, you MUST ensure that no sitemap.xml file exists in the root directory.
 */
function hive_sitemap_check() {
	global $wpdb, $wp_query, $hostname;

	if ( $wp_query->get('sitemap') == '1') {
		$sitemapfile = WP_CONTENT_DIR . '/wp-hive/'. $hostname . '/sitemap.xml';
		if ( file_exists ( $sitemapfile ) ) {
			header('Content-type: application/xml; charset="utf-8"');
			readfile( $sitemapfile );
			exit(0);
		}
		else {
			status_header('404');
			include ( TEMPLATEPATH . '/404.php' );
			exit(0);
		}
	}
	elseif ( $wp_query->get('sitemapgz') == '1' ) {
		$sitemapgzfile = WP_CONTENT_DIR . '/wp-hive/'. $hostname . '/sitemap.xml.gz';
		if ( file_exists ( $sitemapgzfile ) ) {
			header('Content-type: application/x-gzip');
			readfile( $sitemapgzfile );
			exit(0);
		}
		else {
			status_header('404');
			include ( TEMPLATEPATH . '/404.php' );
			exit(0);
		}
	}
}
add_action('template_redirect', 'hive_sitemap_check');

// add sitemap as an allowed query var
function hive_sitemap_query_var($vars){
	array_push($vars, 'sitemap', 'sitemapgz');
	return $vars;
}
add_filter('query_vars','hive_sitemap_query_var');

// add sitemap rewrite rules
function hive_sitemap_intercept($rewrite_rules) {

	$sitemap_rules = array (
		'sitemap.xml$' => 'index.php?sitemap=1',
		'sitemap.xml.gz$' => 'index.php?sitemapgz=1'
		);

		return ( $rewrite_rules + $sitemap_rules );
}
add_filter( 'root_rewrite_rules', 'hive_sitemap_intercept' );


/* --------
 * Favicon.ico Manager
 * Don't forget to save your favicon.ico file as /favicons/[db_prefix]favicons.ico.
 * Also, you MUST ensure that no favicon.ico file exists in the root directory.
 */
function hive_favicon_check() {
	global $wpdb, $wp_query, $hostname;

	if ( $wp_query->get('favicon') == '1') {
		$faviconfile = WP_CONTENT_DIR . '/wp-hive/'. $hostname . '/favicon.ico';
		if ( file_exists ( $faviconfile ) ) {
			$finfo = finfo_open(FILEINFO_MIME);
			$fmime = finfo_file($finfo, $faviconfile);			
			finfo_close($finfo);				
			header('Content-type: ' . $fmime); // Send the actual MIME type of the favicon
			readfile( $faviconfile );
			exit(0);
		}
		else {
			status_header('404');
			echo "File Does Not Exist";
			exit(0);
		}
	}
}
add_action('template_redirect', 'hive_favicon_check');

// add favicon as an allowed query var
function hive_favicon_query_var($vars){
	array_push($vars, 'favicon');
	return $vars;
}
add_filter('query_vars','hive_favicon_query_var');

// add favicon rewrite rules
function hive_favicon_intercept($rewrite_rules) {

	$favicon_rules = array (
		'favicon.ico$' => 'index.php?favicon=1'
		);

		return ( $rewrite_rules + $favicon_rules );
}
add_filter( 'root_rewrite_rules', 'hive_favicon_intercept' );
?>