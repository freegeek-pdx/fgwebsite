<?php
/*
Plugin Name: Show Posts By Selective Category
Plugin URI: http://www.chillnite.com/wordpress-plugin-show-posts-by-selective-category
Description: [WP 2.3.x] Displays a list of the post titles in a selected category. Use [pbc=CategoryId count=num_of_posts] format.
Author: -DA-
Version: 1.0
Author URI: http://www.chillnite.com
*/

function pbc($catid_in=0,$count=0) {

	global $wpdb, $post;
	
	$the_output = '<ul>';
		
	$pbcresults = $wpdb->get_results("SELECT * FROM $wpdb->posts,$wpdb->term_relationships WHERE $wpdb->posts.post_type = 'post' AND $wpdb->posts.post_status = 'publish' AND $wpdb->posts.post_type='post' and $wpdb->posts.id =$wpdb->term_relationships.object_id and $wpdb->term_relationships.term_taxonomy_id=$catid_in order by $wpdb->posts.post_date desc,$wpdb->posts.post_title asc LIMIT $count");
		
		foreach ( $pbcresults as $pbcresult ) 
		{
	    $the_output .= '<li><a href="' . get_permalink($pbcresult->ID) . '">' . apply_filters('the_title', $pbcresult->post_title) . '</a></li>';
		}
    
    $the_output .= '</ul>';
	
	return $the_output;
}

function pbc_gen($content) 
{
	$content = preg_replace("/\[pbc=(\d+) count=(\d+)\]/ise", "pbc('\\1','\\2')", $content);
	return $content;
}

add_filter('the_content', 'pbc_gen');

?>