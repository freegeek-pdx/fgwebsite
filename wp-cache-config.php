<?php
/*
WP-Cache Config Sample File

See wp-cache.php for author details.
*/

$wp_cache_refresh_single_only = '0'; //Added by WP-Cache Manager
$wp_cache_make_known_anon = 0; //Added by WP-Cache Manager
$wp_cache_mod_rewrite = 1; //Added by WP-Cache Manager
$wp_cache_front_page_checks = 1; //Added by WP-Cache Manager
$wp_cache_mfunc_enabled = 0; //Added by WP-Cache Manager
$wp_supercache_304 = 0; //Added by WP-Cache Manager
$wp_cache_no_cache_for_get = 0; //Added by WP-Cache Manager
$wp_cache_disable_utf8 = 0; //Added by WP-Cache Manager
$wp_super_cache_late_init = 0; //Added by WP-Cache Manager
$cache_schedule_interval = 'daily'; //Added by WP-Cache Manager
$cache_gc_email_me = 0; //Added by WP-Cache Manager
$cache_time_interval = '3600'; //Added by WP-Cache Manager
$cache_scheduled_time = '20:00'; //Added by WP-Cache Manager
$cache_schedule_type = 'time'; //Added by WP-Cache Manager
$cache_page_secret = 'd585981e90f8317d49c87cf931cb46e4'; //Added by WP-Cache Manager
$wp_cache_slash_check = 1; //Added by WP-Cache Manager
$cache_badbehaviour_file = ''; //Added by WP-Cache Manager
$cache_badbehaviour = 0; //Added by WP-Cache Manager
$wp_cache_object_cache = 0; //Added by WP-Cache Manager
$wp_supercache_cache_list = 0; //Added by WP-Cache Manager
$wp_cache_hide_donation = 1; //Added by WP-Cache Manager
$wp_cache_not_logged_in = 1; //Added by WP-Cache Manager
$wp_cache_clear_on_post_edit = 0; //Added by WP-Cache Manager
$wp_cache_hello_world = 0; //Added by WP-Cache Manager
$wp_cache_mobile_enabled = 0; //Added by WP-Cache Manager
$wp_cache_cron_check = 1; //Added by WP-Cache Manager
define( 'WPCACHEHOME', WP_CONTENT_DIR . "/plugins/wp-super-cache/" ); //Added by WP-Cache Manager

$cache_compression = 0; //Added by WP-Cache Manager
$cache_enabled = true; //Added by WP-Cache Manager
$super_cache_enabled = true; //Added by WP-Cache Manager
$cache_max_time = 3600; //Added by WP-Cache Manager
//$use_flock = true; // Set it true or false if you know what to use
$cache_path = WP_CONTENT_DIR . '/cache/';
$file_prefix = 'wp-cache-';

// We want to be able to identify each blog in a WordPress MU install
$blogcacheid = '';
if( defined( 'VHOST' ) ) {
	$blogcacheid = 'blog'; // main blog
	if( constant( 'VHOST' ) == 'yes' ) {
		$blogcacheid = $_SERVER['HTTP_HOST'];
	} else {
		$request_uri = preg_replace('/[ <>\'\"\r\n\t\(\)]/', '', str_replace( '..', '', $_SERVER['REQUEST_URI'] ) );
		if( strpos( $request_uri, '/', 1 ) ) {
			if( $base == '/' ) {
				$blogcacheid = substr( $request_uri, 1, strpos( $request_uri, '/', 1 ) - 1 );
			} else {
				$blogcacheid = str_replace( $base, '', $request_uri );
				$blogcacheid = substr( $blogcacheid, 0, strpos( $blogcacheid, '/', 1 ) );
			}
			if ( '/' == substr($blogcacheid, -1))
				$blogcacheid = substr($blogcacheid, 0, -1);
		}
		$blogcacheid = str_replace( '/', '', $blogcacheid );
	}
}
global $ryan52_cache_dir;
$blogcacheid = $ryan52_cache_dir;

// Array of files that have 'wp-' but should still be cached 
$cache_acceptable_files = array( 'wp-comments-popup.php', 'wp-links-opml.php', 'wp-locations.php' );

$cache_rejected_uri = array('wp-.*\\.php', 'index\\.php');
$cache_rejected_user_agent = array ( 0 => 'bot', 1 => 'ia_archive', 2 => 'slurp', 3 => 'crawl', 4 => 'spider');

$cache_rebuild_files = 1; //Added by WP-Cache Manager

// DEBUG mode. Change this to your email address to be sent debug emails.
// Remove comment (//) to enable and add back to disable.
//$wp_cache_debug = "you@example.com";

// Disable the file locking system.
// If you are experiencing problems with clearing or creating cache files
// uncommenting this may help.
$wp_cache_mutex_disabled = 1; //Added by WP-Cache Manager

// Just modify it if you have conflicts with semaphores
$sem_id = 1715195038; //Added by WP-Cache Manager

if ( '/' != substr($cache_path, -1)) {
	$cache_path .= '/';
}

$wp_cache_mobile = 0;
$wp_cache_mobile_whitelist = 'Stand Alone/QNws';
$wp_cache_mobile_browsers = '2.0 MMP, 240x320, AvantGo, BlackBerry, Blazer, Cellphone, Danger, DoCoMo, Elaine/3.0, EudoraWeb, hiptop, IEMobile, iPhone, iPod, KYOCERA/WX310K, LG/U990, MIDP-2.0, MMEF20, MOT-V, NetFront, Newt, Nintendo Wii, Nitro, Nokia, Opera Mini, Palm, Playstation Portable, portalmmm, Proxinet, ProxiNet, SHARP-TQ-GX10, Small, SonyEricsson, Symbian OS, SymbianOS, TS21i-10, UP.Browser, UP.Link, Windows CE, WinWAP';

// gzip the first page generated for clients that support it.
$wp_cache_gzip_first = 0;
// change to relocate the supercache plugins directory
define( 'WPCACHEHOME', WP_CONTENT_DIR . "/plugins/wp-super-cache/" ); //Added by WP-Cache Manager
// set to 1 to do garbage collection during normal process shutdown instead of wp-cron
$wp_cache_shutdown_gc = 0; 
?>
