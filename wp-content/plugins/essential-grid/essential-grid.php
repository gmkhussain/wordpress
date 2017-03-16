<?php
/**
 * @package   Essential_Grid
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://codecanyon.net/item/essential-grid-wordpress-plugin/7563340
 * @copyright 2014 ThemePunch
 *
 * @wordpress-plugin
 * Plugin Name:       Essential Grid
 * Plugin URI:        http://www.themepunch.com/essential/
 * Description:       Essential Grid - Premium grid plugin
 * Version:           1.5.4
 * Author:            ThemePunch
 * Author URI:        http://themepunch.com
 * Text Domain:       essential-grid
 * Domain Path:       /languages
 */
 
 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if(class_exists('Essential_Grid')) {
	die('ERROR: It looks like you have more than one instance of Essential Grid installed. Please remove additional instances for this plugin to work again.');
}

define( 'EG_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'EG_PLUGIN_URL', str_replace('index.php','',plugins_url( 'index.php', __FILE__ )));

define( 'EG_TEXTDOMAIN', 'essential-grid');

$wc_is_localized = false; //used to determinate if already done for cart button on this skin

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/
$base_path = plugin_dir_path( __FILE__ );

require_once($base_path . '/includes/base.class.php');

require_once($base_path . '/public/essential-grid.class.php');

require_once($base_path . '/includes/global-css.class.php');

require_once($base_path . '/includes/navigation.class.php');

require_once($base_path . '/includes/grids-widget.class.php');

require_once($base_path . '/includes/item-skin.class.php');

require_once($base_path . '/includes/item-element.class.php');

require_once($base_path . '/includes/wpml.class.php');

require_once($base_path . '/includes/woocommerce.class.php');

require_once($base_path . '/includes/meta.class.php');

require_once($base_path . '/includes/fonts.class.php');

require_once($base_path . '/includes/aq_resizer.class.php');

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array('Essential_Grid', 'create_tables' ));
register_activation_hook( __FILE__, array('Essential_Grid_Item_Skin', 'propagate_default_item_skins' ));
register_activation_hook( __FILE__, array('Essential_Grid_Navigation', 'propagate_default_navigation_skins' ));
register_activation_hook( __FILE__, array('Essential_Grid_Global_Css', 'propagate_default_global_css' ));
register_activation_hook( __FILE__, array('ThemePunch_Fonts', 'propagate_default_fonts' ));
register_activation_hook( __FILE__, array('Essential_Grid', 'activation_hooks' ));
register_activation_hook( __FILE__, array('Essential_Grid', 'propagate_default_grids' ));

//register_deactivation_hook( __FILE__, array('Essential_Grid', 'deactivate' ));

add_action('plugins_loaded', array('Essential_Grid', 'get_instance'));

add_filter('the_content', array('Essential_Grid', 'fix_shortcodes'));

add_shortcode('ess_grid', array('Essential_Grid', 'register_shortcode'));
add_shortcode('ess_grid_ajax_target', array('Essential_Grid', 'register_shortcode_ajax_target'));
add_shortcode('ess_grid_nav', array('Essential_Grid', 'register_shortcode_filter'));

add_action('widgets_init', array('Essential_Grid', 'register_custom_sidebars'));
add_action('widgets_init', create_function('', 'return register_widget("Essential_Grids_Widget");'));

/* //ToDo Widget part
add_action('widgets_init', create_function('', 'return register_widget("Essential_Grids_Widget_Filter");'));
add_action('widgets_init', create_function('', 'return register_widget("Essential_Grids_Widget_Pagination");'));
add_action('widgets_init', create_function('', 'return register_widget("Essential_Grids_Widget_Pagination_Left");'));
add_action('widgets_init', create_function('', 'return register_widget("Essential_Grids_Widget_Pagination_Right");'));
add_action('widgets_init', create_function('', 'return register_widget("Essential_Grids_Widget_Sorting");'));
add_action('widgets_init', create_function('', 'return register_widget("Essential_Grids_Widget_Cart");'));
*/

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/
if(is_admin()){ // && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) 
	
	/*****************
	 * Developer Part for deactivation of the Activation Area
	 * @since: 1.1.0
	 *****************/
	if(isset($_GET['EssentialAsTheme'])){
		if($_GET['EssentialAsTheme'] == 'true'){
			update_option('EssentialAsTheme', 'true');
		}else{
			update_option('EssentialAsTheme', 'false');
		}
	}
	
	$EssentialAsTheme = false;
	
	function set_ess_grid_as_theme(){
		global $EssentialAsTheme;
		
		if(defined('ESS_GRID_AS_THEME')){
			if(ESS_GRID_AS_THEME == true)
				$EssentialAsTheme = true;
		}else{
			if(get_option('EssentialAsTheme', 'true') == 'true')
				$EssentialAsTheme = true;
		}
	}
	/*****************
	 * END: Developer Part for deactivation of the Activation Area
	 *****************/
	 
	add_action('plugins_loaded', array( 'Essential_Grid', 'create_tables' ));
	
	require_once($base_path . '/admin/essential-grid-admin.class.php');
	
	require_once($base_path . '/admin/includes/update.class.php');
	
	require_once($base_path . '/admin/includes/dialogs.class.php');
	
	require_once($base_path . '/admin/includes/import.class.php');
	
	require_once($base_path . '/admin/includes/export.class.php');
	
	require_once($base_path . '/admin/includes/import-post.class.php');
	
	require_once($base_path . '/admin/includes/plugin-update.class.php');
	
	add_action('plugins_loaded', array( 'Essential_Grid_Admin', 'do_update_checks' )); //add update checks
	
	add_action('plugins_loaded', array( 'Essential_Grid_Admin', 'get_instance' ));
	
	add_action('plugins_loaded', array( 'Essential_Grid_Admin', 'visual_composer_include' )); //VC functionality
	//add_action('init', array('Essential_Grid_Admin', 'visual_composer_include')); //VC functionality
	
}

/*add_action('shutdown', 'ess_debug' );

function ess_debug(){
	global $wpdb;
	echo "<pre>";
	print_r($wpdb->queries);
	echo "</pre>";
}
*/
//debug memory usage
//require_once($base_path . '/admin/includes/debug.class.php');

?>