<?php
// WP Hive Plugin
// Version 0.4.2

global $wpdb, $table_prefix, $hostname;

// Get the correct host name
$hostname = $_SERVER['X_FORWARDED_HOST'] == false ? $_SERVER['HTTP_HOST'] : $_SERVER['X_FORWARDED_HOST'];

// Strip any leading or training dots
$hostname = trim($hostname, ".");

// Strip the www prefix
if (substr($hostname, 0, 4) == "www.") {
	$hostname = substr($hostname,4);
}

// Check if WP Hive is installed, run installation if not.
$installed = $wpdb->get_var("SELECT val FROM wphive_config WHERE item = 'installed'");
if ( empty($installed) )	{

	// Create the config table
	$wpdb->query ("CREATE TABLE `wphive_config` (
		`item` varchar (255) NOT NULL,
		`val` varchar (255),
		PRIMARY KEY ( `item` ))");

	// Create the host table
	$wpdb->query ("CREATE TABLE `wphive_hosts` (
		`host` varchar (255) NOT NULL,
		`prefix` varchar (6) NOT NULL )");

	// Create common directory
	if (! file_exists(WP_CONTENT_DIR . '/wp-hive/') ) {
		mkdir(WP_CONTENT_DIR . '/wp-hive');
	}
	// Set intitial prefix to existing $table_prefix (assume the prefix is an existing WP installation)
	if ($table_prefix != false) {
		$prefix = $table_prefix;
		$wpdb->query($wpdb->prepare("INSERT INTO wphive_hosts (host, prefix) values ( %s, %s )", $hostname, $prefix));

		// Create storage directory for the domain
		if ( ! file_exists(WP_CONTENT_DIR . '/wp-hive/' . $hostname . '/') ) {
			mkdir(WP_CONTENT_DIR . '/wp-hive/' . $hostname);
		}
		// Clean up special files in the root
		// Note: Users should ensure the files are accessed by Plugins in their new location
		$specialfiles = array("robots.txt", "favicon.ico", "sitemap.xml", "sitemap.xml.gz");
		foreach ($specialfiles as $file) {
			if (file_exists(ABSPATH . '/' . $file) ) {
				rename(ABSPATH . '/' . $file, WP_CONTENT_DIR . '/wp-hive/' . $hostname . '/' . $file);
			}
		}

		// Insert some config values
		$wpdb->query ($wpdb->prepare("INSERT INTO wphive_config (item, val) values ( %s, %s )", "allow_new_hosts", 1));
		$wpdb->query ($wpdb->prepare("INSERT INTO wphive_config (item, val) values ( %s, %s )", "installed", 1));

		// TODO: Check if WP Hive plugin is installed / active
	}
}

// Get the corresponding prefix from the db
if (empty($prefix)) {
	$prefix = $wpdb->get_var($wpdb->prepare("SELECT prefix FROM wphive_hosts WHERE host = %s", $hostname));
}

// No prefix found? Try to add one
if ( empty($prefix) ) {
	// Check if we are allowed to add a new site
	if ($wpdb->get_var("SELECT val FROM wphive_config WHERE item = 'allow_new_hosts'") == 1)	{
		$prefix = substr($hostname, 0, 3) . "_";
		// Ensure prefix is unique
		while ($wpdb->get_var($wpdb->prepare("SELECT count(*) FROM wphive_hosts WHERE prefix = %s", $prefix)) > 0) {
			$i = $i == false ? 0 : $i + 1;
			$prefix = substr($prefix, 0, 2) . $i . "_";
		}
		$wpdb->query($wpdb->prepare("INSERT INTO wphive_hosts (host, prefix) values ( %s, %s )", $hostname, $prefix));
		// Create storage directory for the domain
		if ( ! file_exists(WP_CONTENT_DIR . '/wp-hive/' . $hostname . '/') ) {
			mkdir(WP_CONTENT_DIR . '/wp-hive/' . $hostname);
		}
	}
	else	{
		echo "Host not found.";
		exit(0);
	}
}

$table_prefix = $prefix;
?>