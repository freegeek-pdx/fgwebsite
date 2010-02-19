<?php


function fg_truncate( $text ) {
        $chars = 300;
        $text = $text." ";
        $text = substr($text,0,$chars);
        $text = substr($text,0,strrpos($text,' '));
        $text = $text."...";
        return $text;
    }

// jQuery Accordian Stuff - FAQ page + others
// ========================================================
	add_action( 'init', 'fg_load_scripts' );
	
	function fg_load_scripts() {
		wp_enqueue_script( 'jquery' );
		#wp_enqueue_script( 'fg_accordian', get_bloginfo( 'template_directory' ) . '/accordian.js' );	
	}
	
	add_action( 'wp_head', 'fg_dropdown_js' );
	function fg_dropdown_js() {
		
		global $post;
		
		$fg_js_dropdown = '';
	
		$fg_js_dropdown = get_post_meta( $post->ID, 'fg_js_dropdown', true );
		
		$fg_js_dropdown_element = get_post_meta( $post->ID, 'fg_js_dropdown_element', true );
		
		if( !empty( $fg_js_dropdown ) ) {
			
			$ele = ( !empty( $fg_js_dropdown_element ) ) ? $fg_js_dropdown_element : 'h2';
			$script = '$(document).ready(function(){
				$("div.hide_show_area '.$ele.' + div").hide();
				$("div.hide_show_area '.$ele.'").click(function () { 
					$(this).next("div").slideToggle("fast"); 
				});
			});';
	
			print "\n\t" . '<script type="text/javascript">' . $script . '</script>';
		}
	}

// Register Sidebars
// ========================================================
	if ( function_exists('register_sidebar') )
	    register_sidebar(array(
	        'before_widget' => '<li id="%1$s" class="widget %2$s">',
	        'after_widget' => '</li>',
	        'before_title' => '<h2 class="widgettitle">',
	        'after_title' => '</h2>',
	    ));

	
// Filter to eliminate title attribute from wp_list_pages()
// ==========================================================
	add_filter( 'wp_list_pages', 'fg_filter_title_tag' );
	function fg_filter_title_tag( $content ){
		return preg_replace( '/title="[\w\s-]*"/', '', $content);
	}

	
// Define Constants from records stored in options table	
// ==========================================================
	function fg_define_option( $name ){
		$name = strtoupper( $name );
		$opt = get_option( $name );
		if ( !empty( $opt ) && !defined( $name ) )
			define( $name, $opt );
	}
	
	
// Filter to add "Current Page" functionality for post and category views
// ==========================================================
	add_filter( 'wp_list_pages', 'fg_current_page' );
	function fg_current_page( $content ){
		
		// "News" category or posts in "News" category
		if( is_category( FG_NEWS_CAT_ID ) || in_category( FG_NEWS_CAT_ID ) ) {
			$content = fg_force_current_page( $content, FG_ABOUT_US_PAGE_ID );
		}
		
		// "Media Coverage" category or posts in "Media Coverage" category
		elseif( is_category( FG_MEDIA_COVERAGE_CAT_ID ) || in_category( FG_MEDIA_COVERAGE_CAT_ID ) ) {
			$content = fg_force_current_page( $content, FG_MEDIA_PAGE_ID );
			$content = fg_force_current_page( $content, FG_MEDIA_COVERAGE_PAGE_ID );
		}
		
		// "Press Release" category or posts in "Press Release" category
		elseif( is_category( FG_PRESS_RELEASE_CAT_ID ) || in_category( FG_PRESS_RELEASE_CAT_ID ) ) {
			$content = fg_force_current_page( $content, FG_MEDIA_PAGE_ID );
			$content = fg_force_current_page( $content, FG_PRESS_RELEASE_PAGE_ID );
		}
		
		return $content;		
	}
	
	// Helper function for "fg_current_page()"
	function fg_force_current_page( $nav_list, $page_id ){
		$find_me = 'class="page_item page-item-' . $page_id . '"';
		$replace = substr( $find_me, 0, -1 ) . ' current_page_parent"';
		if( substr_count( $nav_list, $find_me ) == 1 )
			$nav_list = str_replace( $find_me, $replace, $nav_list );
		return $nav_list;
	}
		
		
// Shows Posts on the sidebar
// ==========================================================
	function fg_sidebar_cat_posts( $cat ){
		$output = '';
		$r = new WP_Query('showposts=5&what_to_show=posts&nopaging=0&post_status=publish&cat=' . $cat );
		if ($r->have_posts()) {	
			$output .= "\n\t" . '<li><h2>' . __( get_cat_name( $cat ) ) . '</h2></li>';
			$output .= "\n\t" . '<ul>';
			while ($r->have_posts()) {
				$r->the_post();
				$output .= "\n\t" . '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
			}
			$output .= "\n\t" . '</ul>';
		}
		return $output;
	}
	
	
// Add special filtering to text widget
// ==========================================================
	add_filter( 'widget_text', 'wptexturize', 10);
	add_filter( 'widget_text', 'convert_chars', 10 );
	add_filter( 'widget_text', 'wpautop', 10 );


// Removes version info from head
// Don't make it easy for malicious users!!!
// ===============================================
	remove_action('wp_head', 'wp_generator');


// Debug
// ========================================================================
	function print_a( $array ){
		print '<pre>';
		print_r($array);
		print '</pre>';	
	}

	
// Find the "Root Parent" of a Page
// param: '$post' = Post Object
// returns: Post ID of Root Parent
// ========================================================================
	function fg_root_parent( $post ){
		if ( $post->post_parent == 0 )
			return $post->ID;
		else{
			$parent = get_post( $post->post_parent );
			return ( fg_root_parent( $parent ) );
		}	
	}

?>
