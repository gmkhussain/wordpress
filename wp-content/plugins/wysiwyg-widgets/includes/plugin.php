<?php
/**
 * Load the plugin textdomain
 */
function wywi_load_textdomain() {
  	load_plugin_textdomain( 'wysiwyg-widgets', false, 'wysiwyg-widgets/languages/' );
}

add_action('plugins_loaded', 'wywi_load_textdomain');

/**
 * Register the Widget class
 */
function wywi_register_widget() {
	require_once WYWI_PLUGIN_DIR . 'includes/class-widget.php'; 
	register_widget('WYSIWYG_Widgets_Widget');  
}

add_action( 'widgets_init', 'wywi_register_widget');

/**
 * Register the post type used for the widget blocks
 */
function wywi_register_post_type()	{
	$labels = array(
	    'name' => __('Widget Blocks', 'wysiwyg-widgets'),
	    'singular_name' => __('Widget Block', 'wysiwyg-widgets'),
	    'add_new' => __('New Widget Block', 'wysiwyg-widgets'),
	    'add_new_item' => __('Add New Widget Block', 'wysiwyg-widgets'),
	    'edit_item' => __('Edit Widget Block', 'wysiwyg-widgets'),
	    'new_item' => __('New Widget Block', 'wysiwyg-widgets'),
	    'all_items' => __('All Widget Blocks', 'wysiwyg-widgets'),
	    'view_item' => __('View Widget Block', 'wysiwyg-widgets'),
	    'search_items' => __('Search Widget Blocks', 'wysiwyg-widgets'),
	    'not_found' =>  __('No widget blocks found', 'wysiwyg-widgets'),
	    'not_found_in_trash' => __('No widget blocks found in Trash', 'wysiwyg-widgets'), 
	    'menu_name' => __('Widget Blocks', 'wysiwyg-widgets')
	  );
	$args = array(
		'public' => false,
		'show_ui' => true,
		'labels' => $labels,
		'supports' => array('title', 'editor')
	);

   	register_post_type( 'wysiwyg-widget', $args );
}

add_action('init', 'wywi_register_post_type');

// add necessary content filters
add_filter( 'ww_content', 'wptexturize') ;
add_filter( 'ww_content', 'convert_smilies' );
add_filter( 'ww_content', 'convert_chars' );
add_filter( 'ww_content', 'wpautop' );
add_filter( 'ww_content', 'shortcode_unautop' );
add_filter( 'ww_content', 'do_shortcode', 11);
add_filter( 'ww_content', array( $GLOBALS['wp_embed'], 'run_shortcode' ), 8 );
add_filter( 'ww_content', array( $GLOBALS['wp_embed'], 'autoembed' ), 8 );
