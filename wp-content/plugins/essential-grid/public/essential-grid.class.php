<?php
/**
 * Essential Grid.
 *
 * @package   Essential_Grid
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/essential/
 * @copyright 2014 ThemePunch
 */

class Essential_Grid {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 */
	const VERSION = '1.5.4';
	const TABLE_GRID = 'eg_grids';
	const TABLE_ITEM_SKIN = 'eg_item_skins';
	const TABLE_ITEM_ELEMENTS = 'eg_item_elements';
	const TABLE_NAVIGATION_SKINS = 'eg_navigation_skins';
	const TABLE_GRID_VERSION = '1.0.1';
	
	private $grid_serial = 0;
    
	private $grid_api_name = null;
	private $grid_div_name = null;
	private $grid_id = null;
	private $grid_name = null;
	private $grid_handle = null;
	private $grid_params = array();
	private $grid_postparams = array();
	private $grid_layers = array();
	private $grid_inline_js = '';
	
	public $custom_settings = null;
	public $custom_layers = null;
	public $custom_images = null;
	public $custom_posts = null;
	public $custom_special = null;
	
	//other changings
	private $filter_by_ids = array();
	private $load_more_post_array = array();
    
	
	/**
	 * Unique identifier for the plugin.
	 * The variable name is used as the text domain when internationalizing strings of text.
	 */
	protected $plugin_slug = 'essential-grid';

	
	/**
	 * Instance of this class.
	 */
	protected static $instance = null;

	
	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 */
	public function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		
		$add_cpt = true;
		$add_cpt = apply_filters('essgrid_set_cpt', $add_cpt);
		if($add_cpt)
			add_action( 'init', array( $this, 'register_custom_post_type' ) );
		
		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		add_action('wp_ajax_Essential_Grid_Front_request_ajax', array($this, 'on_front_ajax_action'));
		add_action('wp_ajax_nopriv_Essential_Grid_Front_request_ajax', array($this, 'on_front_ajax_action')); //for not logged in users
		
	}

	
	/**
	 * Return the plugin slug.
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	
	/**
	 * Return an instance of this class.
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		
		return self::$instance;
	}

	
	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters('plugin_locale', get_locale(), $domain );
		
		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		
		load_plugin_textdomain( $domain, FALSE, dirname(dirname(plugin_basename( __FILE__ ))) . '/languages/' );
		//load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );

	}

	
	/**
	 * Register and enqueue public-facing style sheet.
	 */
	public function enqueue_styles() {
		wp_register_style($this->plugin_slug . '-plugin-settings', EG_PLUGIN_URL . 'public/assets/css/settings.css', array(), self::VERSION);
		wp_enqueue_style($this->plugin_slug . '-plugin-settings' );
		
		$font = new ThemePunch_Fonts();
		$font->register_fonts();
		
		wp_register_style('themepunchboxextcss', EG_PLUGIN_URL . 'public/assets/css/lightbox.css', array(), self::VERSION);
		
	}

	
	/**
	 * Register and enqueues public-facing JavaScript files.
	 */
	public function enqueue_scripts() {
		$js_to_footer = (get_option('tp_eg_js_to_footer', 'false') == 'true') ? true : false;
		$enable_log = (get_option('tp_eg_enable_log', 'false') == 'true') ? true : false;
		
		wp_register_script( 'themepunchboxext', EG_PLUGIN_URL . 'public/assets/js/lightbox.js', array('jquery'), self::VERSION, $js_to_footer);
		
		$waitfor = array( 'jquery', 'themepunchboxext' );
		
		if($enable_log) wp_enqueue_script( 'enable-logs', EG_PLUGIN_URL . 'public/assets/js/jquery.themepunch.enablelog.js', $waitfor, self::VERSION, $js_to_footer );
		
		wp_enqueue_script( 'tp-tools', EG_PLUGIN_URL . 'public/assets/js/jquery.themepunch.tools.min.js', $waitfor, self::VERSION, $js_to_footer );
		wp_enqueue_script( $this->plugin_slug . '-essential-grid-script', EG_PLUGIN_URL . 'public/assets/js/jquery.themepunch.essential.min.js', array( 'jquery', 'tp-tools' ), self::VERSION, $js_to_footer );
		
	}
	
	
	/**
	 * Register Shortcode
	 */
	public static function register_shortcode($args, $mid_content=null){
		//$dbg = new EssGridMemoryUsageInformation();
		//$dbg->setStart();
		//$dbg->setMemoryUsage('Before ShortCode');
		
		$caching = get_option('tp_eg_use_cache', 'false');
		
		$grid = new Essential_Grid;
		extract(shortcode_atts(array('alias' => '', 'settings' => '', 'layers' => '', 'images' => '', 'posts' => '', 'special' => ''), $args, 'ess_grid'));
        $eg_alias = ($alias != '') ? $alias : implode(' ', $args);
		
		if($settings !== '') $grid->custom_settings = json_decode(str_replace(array('({', '})', "'"), array('[', ']', '"'), $settings) ,true);
		if($layers !== '') $grid->custom_layers = json_decode(str_replace(array('({', '})', "'"), array('[', ']', '"'), $layers),true);
		if($images !== '') $grid->custom_images = explode(',', $images);
		if($posts !== '') $grid->custom_posts = explode(',', $posts);
		if($special !== '') $grid->custom_special = $special;
		
		if($settings !== '' || $layers !== '' || $images !== '' || $posts !== '' || $special !== ''){ //disable caching if one of this is set
			$caching = 'false';
		}
		
		$grid->check_for_shortcodes($mid_content); //check for example on gallery shortcode and do stuff
		
		if($eg_alias == '')
			$eg_alias = implode(' ', $args);
		
		$content = false;
		$grid_id = self::get_id_by_alias($eg_alias);
		
		if($grid_id == '0'){ //grid is created by custom settings. Check if layers and settings are set
			ob_start();
			$grid->output_essential_grid_by_settings();
			$content = ob_get_contents();
			ob_clean();
			ob_end_clean();
		}else{
		
			if($caching == 'true'){ //check if we use total caching
				//add wpml transient
				$lang_code = '';
				if(Essential_Grid_Wpml::is_wpml_exists()){
					$lang_code = Essential_Grid_Wpml::get_current_lang_code();
				}
				
				$content = get_transient( 'ess_grid_trans_full_grid_'.$grid_id.$lang_code );
			}
			
			if($content == false){
				ob_start();
				$grid->output_essential_grid_by_alias($eg_alias);
				$content = ob_get_contents();
				ob_clean();
				ob_end_clean();
				
				if($caching == 'true'){
					set_transient( 'ess_grid_trans_full_grid_'.$grid_id.$lang_code, $content, 60*60*24*7 );
				}
			}
			
		}
		
		$output_protection = get_option('tp_eg_output_protection', 'none');
		
		//$dbg->setMemoryUsage('After ShortCode');
		//$dbg->setEnd();
		//$dbg->printMemoryUsageInformation();
		
		//handle output types
		switch($output_protection){
			case 'compress':
				$content = str_replace("\n", '', $content);
				$content = str_replace("\r", '', $content);
				return($content);
			break;
			case 'echo':
				echo $content;		//bypass the filters
			break;
			default: //normal output
				return($content);
			break;
		}
		
	}
	
	
	/**
	 * Register Shortcode For Ajax Content
	 * @since: 1.5.0
	 */
	public static function register_shortcode_ajax_target($args, $mid_content=null){
		extract(shortcode_atts(array('alias' => ''), $args, 'ess_grid_ajax_target'));
        
		if($alias == '') return false; //no alias found
		
		$output_protection = get_option('tp_eg_output_protection', 'none');

		$content = '';
		
		$grid = new Essential_Grid;
		
		$grid_id = self::get_id_by_alias($alias);
		if($grid_id > 0){
			
			$grid->init_by_id($grid_id);
			//check if shortcode is allowed
			
			$is_sc_allowed = $grid->get_param_by_handle('ajax-container-position');
			if($is_sc_allowed != 'shortcode') return false;
			
			$content = $grid->output_ajax_container();
			
		}
		
		//handle output types
		switch($output_protection){
			case 'compress':
				$content = str_replace("\n", '', $content);
				$content = str_replace("\r", '', $content);
				return($content);
			break;
			case 'echo':
				echo $content;		//bypass the filters
			break;
			default: //normal output
				return($content);
			break;
		}
		
	}
	
	
	/**
	 * Register Shortcode For Filter
	 * @since: 1.5.0
	 */
	public static function register_shortcode_filter($args, $mid_content=null){
		extract(shortcode_atts(array('alias' => '', 'id' => ''), $args, 'ess_grid_nav'));
		
		if($alias == '') return false; //no alias found
		if($id == '') return false; //no alias found
		$base = new Essential_Grid_Base();
		$meta_c = new Essential_Grid_Meta();
		$navigation_c = new Essential_Grid_Navigation();
		
		$output_protection = get_option('tp_eg_output_protection', 'none');

		$content = '';
		
		ob_start();
		
		$grid = new Essential_Grid;
		
		$grid_id = self::get_id_by_alias($alias);
		
		if($grid_id > 0){
			
			$grid->init_by_id($grid_id);
			
			$layout = $grid->get_param_by_handle('navigation-layout', array());
			$navig_special_class = $grid->get_param_by_handle('navigation-special-class', array()); //has all classes in an ordered list
			$navig_special_skin = $grid->get_param_by_handle('navigation-special-skin', array()); //has all classes in an ordered list
			
			$special_class = '';
			$special_skin = '';
			
			if($id == 'sort') $id = 'sorting';
			
			//Check if selected element is in external list and also get the key to use it to get class
			if(isset($layout[$id]) && isset($layout[$id]['external'])){
				$special_class = @$navig_special_class[$layout[$id]['external']];
				$special_skin = @$navig_special_skin[$layout[$id]['external']];
			}else{ //its not in external set so break since its only allowed to use each element one time
				return false;
			}
			
			$navigation_c->set_special_class($special_class);
			$navigation_c->set_special_class($special_skin);
			$navigation_c->set_special_class('esg-fgc-'.$grid_id);
			
			$filter = false;
			switch($id){
				case 'sorting':
					$order_by_start = $grid->get_param_by_handle('sorting-order-by-start', 'none');
					$sort_by_text = $grid->get_param_by_handle('sort-by-text', __('Sort By ', EG_TEXTDOMAIN));
					$order_by = explode(',', $grid->get_param_by_handle('sorting-order-by', 'date'));
					if(!is_array($order_by)) $order_by = array($order_by);
					//set order of filter
					$navigation_c->set_orders_text($sort_by_text);
					$navigation_c->set_orders_start($order_by_start);
					$navigation_c->set_orders($order_by); 
					$navigation_c->output_sorting();
				break;
				case 'cart':
					$navigation_c->output_cart();
				break;
				case 'left':
					$navigation_c->output_navigation_left();
				break;
				case 'right':
					$navigation_c->output_navigation_right();
				break;
				case 'pagination':
					$navigation_c->output_pagination();
				break;
				case 'filter':
					$id = 1;
					$filter = true;
				break;
				default:
					//check for filter
					if(strpos($id, 'filter-') !== false){
						$id = intval(str_replace('filter-', '', $id));
						$filter = true;
					}else{
						return false;
					}
				break;
			}
			
			/*****
			 * Complex Filter Part
			 *****/
			if($filter === true){
				
				$start_sortby = $grid->get_param_by_handle('sorting-order-by-start', 'none');
				$start_sortby_type = $grid->get_param_by_handle('sorting-order-type', 'ASC');
				$post_category = $grid->get_postparam_by_handle('post_category');
				$post_types = $grid->get_postparam_by_handle('post_types');
				$page_ids = explode(',',  $grid->get_postparam_by_handle('selected_pages', '-1'));

				$max_entries = $grid->get_maximum_entries($grid);

				$additional_query = $grid->get_postparam_by_handle('additional-query', '');
				if($additional_query !== '')
					$additional_query = wp_parse_args($additional_query);

				$cat_tax = Essential_Grid_Base::getCatAndTaxData($post_category);

				$posts = Essential_Grid_Base::getPostsByCategory($grid_id, $cat_tax['cats'], $post_types, $cat_tax['tax'], $page_ids, $start_sortby, $start_sortby_type, $max_entries, $additional_query);
				
				$nav_filters = array();
				
				$taxes = array('post_tag');
				if(!empty($cat_tax['tax']))
					$taxes = explode(',', $cat_tax['tax']);
					
				if(!empty($cat_tax['cats'])){
					$cats = explode(',', $cat_tax['cats']);

					foreach($cats as $key => $cid){
						if(Essential_Grid_Wpml::is_wpml_exists() && isset($sitepress)){
							$new_id = icl_object_id($cid, 'category', true, $sitepress->get_default_language());
							$cat = get_category($new_id);
						}else{
							$cat = get_category($cid);
						}
						if(is_object($cat)){
							$nav_filters[$cid] = array('name' => $cat->cat_name, 'slug' => sanitize_key($cat->slug), 'parent' => $cat->category_parent);
						}
						
						foreach($taxes as $custom_tax){
							$term = get_term_by('id', $cid, $custom_tax);
							if(is_object($term)) $nav_filters[$cid] = array('name' => $term->name, 'slug' => sanitize_key($term->slug), 'parent' => $term->parent);
						}
					}
					
					if(!empty($filters_meta)){
						$nav_filters = $filters_meta + $nav_filters;
					}
					asort($nav_filters);
				}
				
				if($id == 1){
					$all_text = $grid->get_param_by_handle('filter-all-text');
					$listing_type = $grid->get_param_by_handle('filter-listing', 'list');
					$listing_text = $grid->get_param_by_handle('filter-dropdown-text');
					$selected = $grid->get_param_by_handle('filter-selected', array());
				}else{
					$all_text = $grid->get_param_by_handle('filter-all-text-'.$id);
					$listing_type = $grid->get_param_by_handle('filter-listing-'.$id, 'list');
					$listing_text = $grid->get_param_by_handle('filter-dropdown-text-'.$id);
					$selected = $grid->get_param_by_handle('filter-selected-'.$id, array());
				}
				$filter_allow = $grid->get_param_by_handle('filter-arrows', 'single');
				$filter_grouping = $grid->get_param_by_handle('filter-grouping', 'false');
				
				//check the selected and change metas to correct fields
				$filters_arr['filter-grouping'] = $filter_grouping;
				$filters_arr['filter-listing'] = $listing_type;
				$filters_arr['filter-selected'] = $selected;
				
				if(!empty($filters_arr['filter-selected'])){
					if(!empty($posts) && count($posts) > 0){
						foreach($filters_arr['filter-selected'] as $fk => $filter){
							if(strpos($filter, 'meta-') === 0){
								unset($filters_arr['filter-selected'][$fk]); //delete entry
								
								foreach($posts as $key => $post){
									$fil = str_replace('meta-', '', $filter);
									$post_filter_meta = $meta_c->get_meta_value_by_handle($post['ID'], 'eg-'.$fil);
									$arr = json_decode($post_filter_meta, true);
									$cur_filter = (is_array($arr)) ? $arr : array($post_filter_meta);
									//$cur_filter = explode(',', $post_filter_meta);
									$add_filter = array();
									if(!empty($cur_filter)){
										foreach($cur_filter as $k => $v){
											if(trim($v) !== ''){
												$add_filter[sanitize_key($v)] = array('name' => $v, 'slug' => sanitize_key($v), 'parent' => '0');
												if(!empty($filters_arr['filter-selected'])){
													$filter_found = false;
													foreach($filters_arr['filter-selected'] as $fcheck){
														if($fcheck == sanitize_key($v)){
															$filter_found = true;
															break;
														}
													}
													if(!$filter_found){
														$filters_arr['filter-selected'][] = sanitize_key($v); //add found meta
													}
												}else{
													$filters_arr['filter-selected'][] = sanitize_key($v); //add found meta
												}
											}
										}
										if(!empty($add_filter)) $navigation_c->set_filter($add_filter);
									}
								}
							}
						}
					}
				}
				
				if($all_text == '' || $listing_type == '' || $listing_text == '' || empty($filters_arr['filter-selected'])) return false;
				
				$navigation_c->set_filter_settings('filter', $filters_arr);
				$navigation_c->set_filter_text($all_text);
				$navigation_c->set_dropdown_text($listing_text);
				$navigation_c->set_filter_type($filter_allow);
				
				$found_filter = array();
				
				if(!empty($posts) && count($posts) > 0){
					foreach($posts as $key => $post){
					
						//check if post should be visible or if its invisible on current grid settings
						$is_visible = $grid->check_if_visible($post['ID'], $grid_id);
						if($is_visible == false) continue; // continue if invisible
						
						$filters = array();
						
						//$categories = get_the_category($post['ID']);
						$categories = $base->get_custom_taxonomies_by_post_id($post['ID']);
						//$tags = wp_get_post_terms($post['ID']);
						$tags = get_the_tags($post['ID']);
						
						if(!empty($categories)){
							foreach($categories as $key => $category){
								$filters[$category->term_id] = array('name' => $category->name, 'slug' => sanitize_key($category->slug), 'parent' => $category->parent);
							}
						}
						
						if(!empty($tags)){
							foreach($tags as $key => $taxonomie){
								$filters[$taxonomie->term_id] = array('name' => $taxonomie->name, 'slug' => sanitize_key($taxonomie->slug), 'parent' => '0');
							}
						}
						
						$filter_meta_selected = $grid->get_param_by_handle('filter-selected', array());
						if(!empty($filter_meta_selected)){
							foreach($filter_meta_selected as $filter){
								if(strpos($filter, 'meta-') === 0){
									$fil = str_replace('meta-', '', $filter);
									$post_filter_meta = $meta_c->get_meta_value_by_handle($post['ID'], 'eg-'.$fil);
									$arr = json_decode($post_filter_meta, true);
									$cur_filter = (is_array($arr)) ? $arr : array($post_filter_meta);
									//$cur_filter = explode(',', $post_filter_meta);
									if(!empty($cur_filter)){
										foreach($cur_filter as $k => $v){
											if(trim($v) !== '')
												$filters[sanitize_key($v)] = array('name' => $v, 'slug' => sanitize_key($v), 'parent' => '0');
										}
									}
								}
							}
						}
						
						$found_filter = $found_filter + $filters; //these are the found filters, only show filter that the posts have
						
					}
				}
				
				$remove_filter = array_diff_key($nav_filters, $found_filter); //check if we have filter that no post has (comes through multilanguage)
				if(!empty($remove_filter)){
					foreach($remove_filter as $key => $rem){ //we have, so remove them from the filter list before setting the filter list
						unset($found_filter[$key]);
					}
				}
				
				$navigation_c->set_filter($found_filter); //set filters $nav_filters $found_filter
			
				$navigation_c->output_filter_unwrapped();
				
			}
			
		}
		
		$content = ob_get_contents();
		ob_clean();
		ob_end_clean();
		
		//handle output types
		switch($output_protection){
			case 'compress':
				$content = str_replace("\n", '', $content);
				$content = str_replace("\r", '', $content);
				return($content);
			break;
			case 'echo':
				echo $content;		//bypass the filters
			break;
			default: //normal output
				return($content);
			break;
		}
	}
	
	
	/**
	 * We check the content for gallery shortcode. 
	 * If existing, create Grid based on the images
	 * @since: 1.2.0
	 * @moved: 1.5.4: moved to Essential_Grid_Base->get_all_gallery_images($mid_content);
	 **/
	public function check_for_shortcodes($mid_content){
		
		$base = new Essential_Grid_Base();
		
		$img = $base->get_all_gallery_images($mid_content);
		
		$this->custom_images = (empty($img)) ? null : $img;
		
	}
	
	
	public static function fix_shortcodes($content){
		$columns = array("ess_grid");
		$block = join("|",$columns);

		// opening tag
		$rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$content);

		// closing tag
		$rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)/","[/$2]",$rep);

		return $rep;
	}
	
	
	/**
	 * Register Custom Post Type & Taxonomy
	 */
	public function register_custom_post_type() {
		$postType = "essential_grid";
		$taxonomy = "essential_grid_category";
		
		$taxArgs = array();
		$taxArgs["hierarchical"] = true;
		$taxArgs["label"] = __("Custom Categories", EG_TEXTDOMAIN);
		$taxArgs["singular_label"] = __("Custom Categorie", EG_TEXTDOMAIN);
		$taxArgs["rewrite"] = true;
		$taxArgs["public"] = true;
		$taxArgs["show_admin_column"] = true;
		
		register_taxonomy($taxonomy,array($postType),$taxArgs); 
		
		$postArgs = array();
		$postArgs["label"] = __("Ess. Grid Posts", EG_TEXTDOMAIN);
		$postArgs["singular_label"] = __("Ess. Grid Post", EG_TEXTDOMAIN);
		$postArgs["public"] = true;
		$postArgs["capability_type"] = "post";
		$postArgs["hierarchical"] = false;
		$postArgs["show_ui"] = true;
		$postArgs["show_in_menu"] = true;
		$postArgs["supports"] = array('title', 'editor', 'thumbnail', 'author', 'comments', 'excerpt');			
		$postArgs["show_in_admin_bar"] = false;			
		$postArgs["taxonomies"] = array($taxonomy, 'post_tag');
		
		$postArgs["rewrite"] = array("slug"=>$postType,"with_front"=>true);
		
		register_post_type($postType,$postArgs);
		
	}
	
	
	/**
	 * Create/Update Database Tables
	 */
	public static function create_tables($networkwide = false){
		global $wpdb;
		
		if(function_exists('is_multisite') && is_multisite() && $networkwide){ //do for each existing site
		
			$old_blog = $wpdb->blogid;
			
            // Get all blog ids and create tables
			$blogids = $wpdb->get_col("SELECT blog_id FROM ".$wpdb->blogs);
			
            foreach($blogids as $blog_id){
				switch_to_blog($blog_id);
				self::_create_tables();
            }
			
            switch_to_blog($old_blog); //go back to correct blog
			
		}else{  //no multisite, do normal installation
		
			self::_create_tables();
			
		}
		
	}
	
	
	/**
	 * Create Tables, edited for multisite
	 * @since 1.5.0
	 */
	public static function _create_tables(){
		
		global $wpdb;
		
		//Create/Update Grids Database
		$grid_ver = get_option("tp_eg_grids_version", '0.99');
		
		if(version_compare($grid_ver, '1', '<')){
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			
			$table_name = $wpdb->prefix . self::TABLE_GRID;
			$sql = "CREATE TABLE $table_name (
				  id mediumint(9) NOT NULL AUTO_INCREMENT,
				  name VARCHAR(255) NOT NULL,
				  handle VARCHAR(255) NOT NULL,
				  postparams TEXT NOT NULL,
				  params TEXT NOT NULL,
				  layers TEXT NOT NULL,
				  UNIQUE KEY id (id),
				  UNIQUE (handle)
				  );";
				  
			dbDelta($sql);
			
			$table_name = $wpdb->prefix . self::TABLE_ITEM_SKIN;
			$sql = "CREATE TABLE $table_name (
				  id mediumint(9) NOT NULL AUTO_INCREMENT,
				  name VARCHAR(255) NOT NULL,
				  handle VARCHAR(255) NOT NULL,
				  params TEXT NOT NULL,
				  layers TEXT NOT NULL,
				  settings TEXT,
				  UNIQUE KEY id (id),
				  UNIQUE (name),
				  UNIQUE (handle)
				  );";
			
			dbDelta($sql);
			
			$table_name = $wpdb->prefix . self::TABLE_ITEM_ELEMENTS;
			$sql = "CREATE TABLE $table_name (
				  id mediumint(9) NOT NULL AUTO_INCREMENT,
				  name VARCHAR(255) NOT NULL,
				  handle VARCHAR(255) NOT NULL,
				  settings TEXT NOT NULL,
				  UNIQUE KEY id (id),
				  UNIQUE (handle)
				  );";
				  
			dbDelta($sql);
			
			$table_name = $wpdb->prefix . self::TABLE_NAVIGATION_SKINS;
			$sql = "CREATE TABLE $table_name (
				  id mediumint(9) NOT NULL AUTO_INCREMENT,
				  name VARCHAR(255) NOT NULL,
				  handle VARCHAR(255) NOT NULL,
				  css TEXT NOT NULL,
				  UNIQUE KEY id (id),
				  UNIQUE (handle)
				  );";
			
			dbDelta($sql);

			update_option('tp_eg_grids_version', '1');
		}
		
		//Change database on certain release? No Problem, use the following:
		if(version_compare($grid_ver, '1.02', '<')){
			
			//change layers size to MEDIUMTEXT from TEXT to allow more elements to be stored
			$table_name = $wpdb->prefix . self::TABLE_GRID;
			$sql = "CREATE TABLE $table_name (
				  layers MEDIUMTEXT NOT NULL
				  );";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		 
			update_option('tp_eg_grids_version', '1.02');
		}
	}
	
	
	/**
	 * Register Custom Sidebars, created in Grids
	 * @since 1.0.6
	 */
	public static function register_custom_sidebars(){
	
		// Register custom Sidebars
		$sidebars = get_option('esg-widget-areas', false);
		
		if(is_array($sidebars) && !empty($sidebars)){
			foreach($sidebars as $handle => $name){
				register_sidebar(
					array (
						'name'          => $name,
						'id'            => 'eg-'.$handle,
						'before_widget' => '',
						'after_widget'  => ''
					)
				);
			}
		}
	}
	
	
	/**
	 * Get all Grids in Database
	 */
	public static function get_essential_grids($order = false){
		global $wpdb;
		
		$additional = '';
		if($order !== false && !empty($order)){
			$ordertype = key($order);
			$orderby = reset($order);
			$additional .= ' ORDER BY '.$ordertype.' '.$orderby;
		}
		
		$table_name = $wpdb->prefix . self::TABLE_GRID;
		$grids = $wpdb->get_results("SELECT * FROM $table_name".$additional);
		
		return $grids;
	}
	
	
	/**
	 * Get Grid by ID from Database
	 */
	public static function get_essential_grid_by_id($id = 0){
		global $wpdb;
		
		$id = intval($id);
		if($id == 0) return false;
		
		$table_name = $wpdb->prefix . self::TABLE_GRID;
		
		$grid = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);
		
		if(!empty($grid)){
			$grid['postparams'] = @json_decode($grid['postparams'], true);
			$grid['params'] = @json_decode($grid['params'], true);
			$grid['layers'] = @json_decode($grid['layers'], true);
		}
		
		return $grid;
	}
	
	
	/**
	 * get array of id -> title
	 */		
	public static function get_grids_short($exceptID = null){
		$arrGrids = self::get_essential_grids();
		
		$arrShort = array();
		foreach($arrGrids as $grid){
			$id = $grid->id;
			$title = $grid->name;
			
			//filter by except
			if(!empty($exceptID) && $exceptID == $id)
				continue;
				
			$arrShort[$id] = $title;
		}
		
		return($arrShort);
	}
	
	
	/**
	 * get array of id -> handle
	 * @since 1.0.6
	 */		
	public static function get_grids_short_widgets($exceptID = null){
		$arrGrids = self::get_essential_grids();
		
		$arrShort = array();
		
		foreach($arrGrids as $grid){
			
			//filter by except
			if(!empty($exceptID) && $exceptID == $grid->id)
				continue;
				
			$arrShort[$grid->id] = $grid->handle;
		}
		
		return($arrShort);
	}
	
	
	/**
	 * get array of id -> title
	 */		
	public static function get_grids_short_vc($exceptID = null){
		$arrGrids = self::get_essential_grids();
		
		$arrShort = array();
		
		foreach($arrGrids as $grid){
			$alias = $grid->handle;
			$title = $grid->name;
			
			//filter by except
			if(!empty($exceptID) && $exceptID == $grid->id)
				continue;
				
			$arrShort[$title] = $alias;
		}
		
		return($arrShort);
	}
	
	
	/**
	 * Get Choosen Item Skin
	 * @since: 1.2.0
	 */		
	public static function get_choosen_item_skin(){
		
		$base = new Essential_Grid_Base();
		
		return $base->getVar($this->grid_params, 'entry-skin', 0, 'i');
		
	}
	
	
	/**
	 * Get Certain Parameter
	 * @since: 1.5.0
	 */		
	public function get_param_by_handle($handle, $default = ''){
		
		$base = new Essential_Grid_Base();
		
		return $base->getVar($this->grid_params, $handle, $default);
		
	}
	
	
	/**
	 * Get Certain Post Parameter
	 * @since: 1.5.0
	 */		
	public function get_postparam_by_handle($handle, $default = ''){
		
		$base = new Essential_Grid_Base();
		
		return $base->getVar($this->grid_postparams, $handle, $default);
		
	}
	
	
	/**
	 * Output Essential Grid in Page by alias
	 */		
	public function output_essential_grid_by_alias($eg_alias){
		global $wpdb;
		
		$table_name = $wpdb->prefix . self::TABLE_GRID;
		
		$grid = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE handle = %s", $eg_alias), ARRAY_A);
		
		if(!empty($grid)){
			$this->output_essential_grid($grid['id']);
		}else{ return false; }
		
	}
	
	
	/**
	 * Output Essential Grid in Page by Custom Settings and Layers
	 * @since: 1.2.0
	 */		
	public function output_essential_grid_by_settings(){
		if($this->custom_special !== null){
			if($this->custom_settings !== null) //custom settings got added. Overwrite Grid Settings and element settings
				$this->apply_custom_settings(true);
			
			$this->apply_all_media_types();
			
			$this->output_by_posts();
		}else{
			if($this->custom_settings == null || $this->custom_layers == null){ return false; }else{
				$this->output_essential_grid_custom();
			}
		}
		
	}
	
	
	/**
	 * Get Essential Grid ID by alias
	 * @since: 1.2.0
	 */		
	public static function get_id_by_alias($eg_alias){
		global $wpdb;
		
		$table_name = $wpdb->prefix . self::TABLE_GRID;
		
		$grid = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE handle = %s", $eg_alias), ARRAY_A);
		
		if(!empty($grid)){
			return $grid['id'];
		}else{ return '0'; }
		
	}
	
	
    /**
	 * Init essential data by id
	 */	
    public function init_by_id($grid_id){
        global $wpdb;
		
		$table_name = $wpdb->prefix . self::TABLE_GRID;
		
		$grid = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $grid_id), ARRAY_A);
		
		if(empty($grid)) return false;
		
		$this->grid_id = @$grid['id'];
		$this->grid_name = @$grid['name'];
		$this->grid_handle = @$grid['handle'];
		$this->grid_postparams = @json_decode($grid['postparams'], true);
		$this->grid_params = @json_decode($grid['params'], true);
		if(!empty($grid['layers'])){
			$orig_layers = $grid['layers'];
			$grid['layers'] = @json_decode(stripslashes($orig_layers), true);
			if(empty($grid['layers']) || !is_array($grid['layers'])) $grid['layers'] = @json_decode($orig_layers, true);

			if(!empty($grid['layers'])){
				foreach($grid['layers'] as $key => $layer){
					$orig_layers_cur = $grid['layers'][$key];
					$grid['layers'][$key] = @json_decode($orig_layers_cur, true);
					if(empty($grid['layers'][$key]) || !is_array($grid['layers'][$key])) $grid['layers'][$key] = @json_decode(stripslashes($orig_layers_cur), true);
				}
			}
		}
        $this->grid_layers = @$grid['layers'];
		
		return true;
    }
	
	
    /**
	 * Init essential data by given data
	 */	
    public function init_by_data($grid_data){
        
		$this->grid_id = @$grid_data['id'];
		$this->grid_name = @$grid_data['name'];
		$this->grid_handle = @$grid_data['handle'];
        $this->grid_postparams = @$grid_data['postparams'];
        $this->grid_params = @$grid_data['params'];
		
		if(!empty($grid_data['layers'])){
			foreach($grid_data['layers'] as $key => $layer){
				$grid_data['layers'][$key] = @json_decode(stripslashes($grid_data['layers'][$key]), true);
			}
		}
        $this->grid_layers = @$grid_data['layers'];
		
		return true;
    }
	
	
    /**
	 * Init essential data by id
	 */	
    public function set_loading_ids($ids){
        
		$this->filter_by_ids = $ids;
		
    }
	
	
    /**
	 * Init essential data by id
	 */	
    public function is_custom_grid(){
        
		if(isset($this->grid_postparams['source-type']) && $this->grid_postparams['source-type'] == 'custom')
			return true;
		else
			return false;
		
    }
    
    
	/**
	 * Output Essential Grid in Page
	 */		
	public function output_essential_grid($grid_id, $data = array(), $grid_preview = false){
		
		try{
		
			if($grid_preview){
				$data['id'] = $grid_id;
				$init = $this->init_by_data($data);
				if(!$init) return false; //be silent
			}else{
				$init = $this->init_by_id($grid_id);
				if(!$init) return false; //be silent
				Essential_Grid_Global_Css::output_global_css_styles_wrapped();
			}
			
			if($this->custom_posts !== null) //custom post IDs are added, so we change to post
				$this->grid_postparams['source-type'] = 'post';
			
			if($this->custom_images !== null) //custom images are added, so we change to gallery
				$this->grid_postparams['source-type'] = 'gallery';
				
			if($this->custom_settings !== null) //custom settings got added. Overwrite Grid Settings and element settings
				$this->apply_custom_settings();
			
			$this->set_api_names(); //set correct names for javascript and div id
			switch($this->grid_postparams['source-type']){
				case 'post':
					$this->output_by_posts($grid_preview);
				break;
				case 'custom':
					$this->output_by_custom($grid_preview);
				break;
				case 'gallery':
					$this->output_by_gallery($grid_preview);
				break;
				case 'streams':
				break;
			}
			
		}catch(Exception $e){
			$message = $e->getMessage();
			echo $message;
		}
	}
    
	
	/**
	 * set correct names for javascript and div id
	 * @since: 1.5.0
	 */
	public function set_api_names(){
		$ess_api = '';
		$ess_div = '';
		if($this->grid_id != null){
			$ess_api = $this->grid_id;
			$ess_div = $this->grid_id;
		}
		
		if($this->custom_special !== null){
			switch($this->custom_special){
				case 'related':
				case 'popular':
				case 'latest':
					$ess_api .= '_'.$this->custom_special;
					$ess_div .= '-'.$this->custom_special;
				break;
			}
		}
		if($this->custom_posts !== null){
			$ess_api .= '_custom_post';
			$ess_div .= '-custom_post';
		}
		if($this->custom_settings !== null){
			$ess_api .= '_custom';
			$ess_div .= '-custom';
		}
		if($this->custom_layers !== null){
			$ess_api .= '_layers';
			$ess_div .= '-layers';
		}
		if($this->custom_images !== null){
			$ess_api .= '_img';
			$ess_div .= '-img';
		}
		
		$this->grid_api_name = $ess_api;
		$this->grid_div_name = $ess_div;
	}
	
    
	/**
	 * Output Essential Grid in Page with Custom Layer and Settings
	 * @since: 1.2.0
	 */		
	public function output_essential_grid_custom($grid_preview = false){
		try{
			
			Essential_Grid_Global_Css::output_global_css_styles_wrapped();
			
			if($this->custom_settings !== null) //custom settings got added. Overwrite Grid Settings and element settings
				$this->apply_custom_settings(true);
			
			if($this->custom_layers !== null) //custom settings got added. Overwrite Grid Settings and element settings
				$this->apply_custom_layers(true);
			
			$this->apply_all_media_types();
			
			return $this->output_by_custom($grid_preview);
			
		}catch(Exception $e){
			$message = $e->getMessage();
			echo $message;
		}
	}
	
	
	/**
	 * Apply all media types for custom grids that have not much settings
	 * @since: 1.2.0
	 */
	public function apply_all_media_types(){
		/**
		 * Add settings that need to be set
		 * - use all media sources, sorting does not matter since we only set one thing in each entry
		 * - use all poster sources for videos, sorting does not matter since we only set one thing in each entry
		 * - use all lightbox sources, sorting does not matter since we only set one thing in each entry
		 */
		$media_orders = Essential_Grid_Base::get_media_source_order();
		foreach($media_orders as $handle => $vals){
			if($handle == 'featured-image' || $handle == 'alternate-image') continue;
			$this->grid_postparams['media-source-order'][] = $handle;
		}
		$this->grid_postparams['media-source-order'][] = 'featured-image'; //set this as the last entry
		$this->grid_postparams['media-source-order'][] = 'alternate-image'; //set this as the last entry
		
		$poster_orders = Essential_Grid_Base::get_poster_source_order();
		foreach($poster_orders as $handle => $vals){
			$this->grid_params['poster-source-order'][] = $handle;
		}
		
		$lb_orders = Essential_Grid_Base::get_lb_source_order();
		foreach($lb_orders as $handle => $vals){
			$this->grid_params['lb-source-order'][] = $handle;
		}
	}
	
	
	/**
	 * Apply Custom Settings to the Grid, so users can change everything in the settings they want to
	 * This allows to modify grid_params and grid_post_params
	 * @since: 1.2.0
	 */
	private function apply_custom_settings($has_handle = false){
		
		if(empty($this->custom_settings) || !is_array($this->custom_settings)) return false;
		
		$base = new Essential_Grid_Base();
		
		$translate_variables = array('grid-layout' => 'layout');
		
		foreach($this->custom_settings as $handle => $new_setting){
		
			if(isset($translate_variables[$handle])){
				$handle = $translate_variables[$handle];
			}
			
			if($has_handle){ //p- is in front of postparameters
			
				if(strpos($handle, 'p-') === 0)
					$this->grid_postparams[substr($handle, 2)] = $new_setting;
				else
					$this->grid_params[$handle] = $new_setting;
					
			}else{
			
				if(isset($this->grid_params[$handle])){
					$this->grid_params[$handle] = $new_setting;
				}elseif(isset($this->grid_postparams[$handle])){
					$this->grid_postparams[$handle] = $new_setting;
				}else{
					$this->grid_params[$handle] = $new_setting;
				}
					
			}
		}
		
		if(isset($this->grid_params['columns'])){ //change columns
			$columns = $base->set_basic_colums_custom($this->grid_params['columns']);
			$this->grid_params['columns'] = $columns;
		}
		
		if(isset($this->grid_params['rows-unlimited']) && $this->grid_params['rows-unlimited'] == 'off'){ //add pagination 
			$this->grid_params['navigation-layout']['pagination']['bottom-1'] = '0';
			$this->grid_params['bottom-1-margin-top'] = '10';
		}
		
		return true;
		
	}
	
	
	/**
	 * Apply Custom Layers to the Grid
	 * @since: 1.2.0
	 */
	private function apply_custom_layers(){
	
		$this->grid_layers = array();
		if(!empty($this->custom_layers) && is_array($this->custom_layers)){
			$add_poster_img = array();
			foreach($this->custom_layers as $handle => $val_arr){
				if(!empty($val_arr) && is_array($val_arr)){
					//$custom_poster = false;
					foreach($val_arr as $id => $value){
						//if($handle == 'custom-poster') $custom_poster = array($id, $value);
						if($handle == 'custom-poster'){
							$add_poster_img[$id] = $value;
							continue;
						}
						$this->grid_layers[$id][$handle] = $value;
					}
				}
			}
			
			if(!empty($add_poster_img)){
				foreach($add_poster_img as $id => $value){
					$this->grid_layers[$id]['custom-image'] = $value;
				}
			}
		}
		
	}
	
	
	/**
	 * Output by gallery
	 * Remove all custom elements, add image elements
	 * @since: 1.2.0
	 */
	public function output_by_gallery($grid_preview = false, $only_elements = false){

		$this->grid_layers = array();
		
		if(!empty($this->custom_images)){
			foreach($this->custom_images as $image_id){
				$alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
				$title = get_the_title($image_id);
				$excerpt = get_post_field('post_excerpt', $image_id);
				
				$this->grid_layers[] = array(
											'custom-image' => $image_id,
											'excerpt' => $excerpt,
											'title' => $title
											);
											
			}
		}
		
		return $this->output_by_custom($grid_preview = false, $only_elements = false);
		
	}
	
	
	/**
	 * Output by custom grid
	 */
	public function output_by_custom($grid_preview = false, $only_elements = false){
		$post_limit = 99999;
		
		$base = new Essential_Grid_Base();
		$navigation_c = new Essential_Grid_Navigation();
		$item_skin = new Essential_Grid_Item_Skin();
		$item_skin->grid_id = $this->grid_id;
		$item_skin->set_grid_type($base->getVar($this->grid_params, 'layout','even'));
		
		$item_skin->set_default_image_by_id($base->getVar($this->grid_postparams, 'default-image', 0, 'i'));
		
		$m = new Essential_Grid_Meta();
		
		$skins_html = '';
		$skins_css = '';
		$filters = array();
		
		$rows_unlimited = $base->getVar($this->grid_params, 'rows-unlimited', 'on');
		$load_more = $base->getVar($this->grid_params, 'load-more', 'none');
		$load_more_start = $base->getVar($this->grid_params, 'load-more-start', 3, 'i');
		
		if($rows_unlimited == 'on' && $load_more !== 'none' && $grid_preview == false){ //grid_preview means disable load more in preview
			$post_limit = $load_more_start;
		}
		
		$nav_filters = array();
		
		$nav_layout = $base->getVar($this->grid_params, 'navigation-layout', array());
		$nav_skin = $base->getVar($this->grid_params, 'navigation-skin', 'minimal-light');
		$hover_animation = $base->getVar($this->grid_params, 'hover-animation', 'fade');
		$filter_allow = $base->getVar($this->grid_params, 'filter-arrows', 'single');
		$filter_all_text = $base->getVar($this->grid_params, 'filter-all-text', __('Filter - All', EG_TEXTDOMAIN));
		$filter_dropdown_text = $base->getVar($this->grid_params, 'filter-dropdown-text', __('Filter Categories', EG_TEXTDOMAIN));
		
		$filter_grouping = $base->getVar($this->grid_params, 'filter-grouping', 'false');
		$listing_type = $base->getVar($this->grid_params, 'filter-listing', 'list');
		//$selected = $base->getVar($this->grid_params, 'filter-selected', array());
		$filters_arr['filter-grouping'] = $filter_grouping;
		$filters_arr['filter-listing'] = $listing_type;
		$filters_arr['filter-selected'] = array(); //always give empty array (metas ect. may still be checked if Grid was a post based grid before.
		
		$navigation_c->set_filter_settings('filter', $filters_arr);
		
		$nav_type = $base->getVar($this->grid_params, 'nagivation-type', 'internal');
		$do_nav = ($nav_type == 'internal') ? true : false;
		
		$order_by = explode(',', $base->getVar($this->grid_params, 'sorting-order-by', 'date'));
		if(!is_array($order_by)) $order_by = array($order_by);
		$order_by_start = $base->getVar($this->grid_params, 'sorting-order-by-start', 'none');
		
		$sort_by_text = $base->getVar($this->grid_params, 'sort-by-text', __('Sort By ', EG_TEXTDOMAIN));
		
		$module_spacings = $base->getVar($this->grid_params, 'module-spacings', '5');
		
		$top_1_align = $base->getVar($this->grid_params, 'top-1-align', 'center');
		$top_2_align = $base->getVar($this->grid_params, 'top-2-align', 'center');
		$bottom_1_align = $base->getVar($this->grid_params, 'bottom-1-align', 'center');
		$bottom_2_align = $base->getVar($this->grid_params, 'bottom-2-align', 'center');
		
		$top_1_margin = $base->getVar($this->grid_params, 'top-1-margin-bottom', 0, 'i');
		$top_2_margin = $base->getVar($this->grid_params, 'top-2-margin-bottom', 0, 'i');
		$bottom_1_margin = $base->getVar($this->grid_params, 'bottom-1-margin-top', 0, 'i');
		$bottom_2_margin = $base->getVar($this->grid_params, 'bottom-2-margin-top', 0, 'i');
		
		$left_margin = $base->getVar($this->grid_params, 'left-margin-left', 0, 'i');
		$right_margin = $base->getVar($this->grid_params, 'right-margin-right', 0, 'i');
		
		$nav_styles['top-1'] = array('margin-bottom' => $top_1_margin.'px', 'text-align' => $top_1_align);
		$nav_styles['top-2'] = array('margin-bottom' => $top_2_margin.'px', 'text-align' => $top_2_align);
		$nav_styles['left'] = array('margin-left' => $left_margin.'px');
		$nav_styles['right'] = array('margin-right' => $right_margin.'px');
		$nav_styles['bottom-1'] = array('margin-top' => $bottom_1_margin.'px', 'text-align' => $bottom_1_align);
		$nav_styles['bottom-2'] = array('margin-top' => $bottom_2_margin.'px', 'text-align' => $bottom_2_align);
		
		if($do_nav){ //only do if internal is selected
			$navigation_c->set_special_class('esg-fgc-'.$this->grid_id);
			$navigation_c->set_dropdown_text($filter_dropdown_text);
			$navigation_c->set_filter_text($filter_all_text);
			$navigation_c->set_specific_styles($nav_styles);
			$navigation_c->set_layout($nav_layout); //set the layout
			
			$navigation_c->set_orders($order_by); //set order of filter
			$navigation_c->set_orders_text($sort_by_text);
			$navigation_c->set_orders_start($order_by_start); //set order of filter
		}
        $item_skin->init_by_id($base->getVar($this->grid_params, 'entry-skin', 0, 'i'));
		
		$lazy_load = $base->getVar($this->grid_params, 'lazy-loading', 'off');
		if($lazy_load == 'on')
			$item_skin->set_lazy_load(true);
		
        $default_media_source_order = $base->getVar($this->grid_postparams, 'media-source-order', '');
		$item_skin->set_default_media_source_order($default_media_source_order);
		
        $default_lightbox_source_order = $base->getVar($this->grid_params, 'lb-source-order', '');
		$item_skin->set_default_lightbox_source_order($default_lightbox_source_order);
		
        $default_aj_source_order = $base->getVar($this->grid_params, 'aj-source-order', '');
		$item_skin->set_default_ajax_source_order($default_aj_source_order);
		
		$post_media_source_type = $base->getVar($this->grid_postparams, 'image-source-type', 'full');
		
		$default_video_poster_order = $base->getVar($this->grid_params, 'poster-source-order', '');
		$item_skin->set_default_video_poster_order($default_video_poster_order);
		
		$layout = $base->getVar($this->grid_params, 'layout','even');
        $layout_sizing = $base->getVar($this->grid_params, 'layout-sizing', 'boxed');
        
		$ajax_container_position = $base->getVar($this->grid_params, 'ajax-container-position', 'top');
		
		if($layout_sizing !== 'fullwidth' && $layout == 'masonry'){
			$item_skin->set_poster_cropping(true);
		}
		
		$skins_css = '';
		$skins_html = '';
		
		$found_filter = array();
		$i = 1;
		
		if(!empty($order_by_start) && !empty($this->grid_layers)){
			if(is_array($order_by_start)){
				foreach($order_by_start as $c_order){
					if($c_order == 'rand'){
						$this->grid_layers = $base->shuffle_assoc($this->grid_layers);
						break;
					}
				}
			}else{
				if($order_by_start == 'rand'){
					$this->grid_layers = $base->shuffle_assoc($this->grid_layers);
				}
			}
		}
		
		if(!empty($this->grid_layers) && count($this->grid_layers) > 0){
			foreach($this->grid_layers as $key => $entry){
				
				$post_media_source_data = $base->get_custom_media_source_data($entry, $post_media_source_type);
				$post_video_ratios = $m->get_custom_video_ratios($entry);
				$filters = array();
				
				if(is_array($order_by) && !empty($order_by)){
					//$sort = $this->prepare_sorting_array_by_post($post, $order_by);
					//$item_skin->set_sorting($sort);
				}
				if(!empty($entry['custom-filter'])){
					$cats = explode(',', $entry['custom-filter']);
					if(!is_array($cats)) $cats = (array)$cats;
					foreach($cats as $category){
						$filters[sanitize_key($category)] = array('name' => $category, 'slug' => sanitize_key($category));
					}
				}
				
				$found_filter = $found_filter + $filters; //these are the found filters, only show filter that the posts have
				
				if($i > $post_limit){
					$this->load_more_post_array[$key] = $filters; //set for load more, only on elements that will not be loaded from beginning
					continue; //Load only selected numbers of items at start (for load more)
				}
				$i++;
				
				$item_skin->set_filter($filters);
				$item_skin->set_media_sources($post_media_source_data);
				$item_skin->set_media_sources_type($post_media_source_type);
				$item_skin->set_video_ratios($post_video_ratios);
				$item_skin->set_layer_values($entry);
				
				ob_start();
				$item_skin->output_item_skin($grid_preview);
				$skins_html.= ob_get_contents();
				ob_clean();
				ob_end_clean();
				
				if($only_elements == false && $grid_preview == false){
					ob_start();
					$item_skin->output_element_css_by_meta();
					$skins_css.= ob_get_contents();
					ob_clean();
					ob_end_clean();
				}
				
			}
		}
		
		
		if($grid_preview !== false && $only_elements == false){ //add the add more box at the end
			ob_start();
			$item_skin->output_add_more();
			$skins_html.= ob_get_contents();
			ob_clean();
			ob_end_clean();
		}
		
		if($do_nav){ //only do if internal is selected
			$navigation_c->set_filter($found_filter); //set filters $nav_filters $found_filter
			$navigation_c->set_filter_type($filter_allow);
		}
		
		if($only_elements == false){
			ob_start();
			$item_skin->generate_element_css($grid_preview);
			$skins_css.= ob_get_contents();
			ob_clean();
			ob_end_clean();
		
		
			if($do_nav){ //only do if internal is selected
				$navigation_skin = $base->getVar($this->grid_params, 'navigation-skin', 'minimal-light');
				ob_start();
				$navigation_c->output_navigation_skin($navigation_skin);
				$nav_css = ob_get_contents();
				ob_clean();
				ob_end_clean();
				
				echo $nav_css;
			}
		
			echo $skins_css;
			
			if($item_skin->ajax_loading == true && $ajax_container_position == 'top'){
				echo $this->output_ajax_container();
			}
			
			$this->output_wrapper_pre($grid_preview);
		
			if($do_nav){ //only do if internal is selected
				$navigation_c->output_layout('top-1', $module_spacings);
				$navigation_c->output_layout('top-2', $module_spacings);
			}
		
			$this->output_grid_pre();
		}
		
		echo $skins_html;
		
		if($only_elements == false){
			$this->output_grid_post();
		
			if($do_nav){ //only do if internal is selected
				$navigation_c->output_layout('bottom-1', $module_spacings);
				$navigation_c->output_layout('bottom-2', $module_spacings);
				$navigation_c->output_layout('left');
				$navigation_c->output_layout('right');
			}
		
			$this->output_wrapper_post();
			
			if($item_skin->ajax_loading == true && $ajax_container_position == 'bottom'){
				echo $this->output_ajax_container();
			}
			
			$load_lightbox = $item_skin->do_lightbox_loading();
			
			if(!$grid_preview)
				$this->output_grid_javascript($load_lightbox);
		}
	}
	
	
	/**
	 * Output by posts
	 */
	public function output_by_posts($grid_preview = false){
		global $sitepress;
		
		$post_limit = 99999;
		
		$base = new Essential_Grid_Base();
		$navigation_c = new Essential_Grid_Navigation();
		$meta_c = new Essential_Grid_Meta();
		$item_skin = new Essential_Grid_Item_Skin();
		$item_skin->grid_id = $this->grid_id;
		$item_skin->set_grid_type($base->getVar($this->grid_params, 'layout','even'));
		
		$item_skin->set_default_image_by_id($base->getVar($this->grid_postparams, 'default-image', 0, 'i'));
		
		$m = new Essential_Grid_Meta();
		
		$skins_html = '';
		$skins_css = '';
		$filters = array();
		
		$rows_unlimited = $base->getVar($this->grid_params, 'rows-unlimited', 'on');
		$load_more = $base->getVar($this->grid_params, 'load-more', 'none');
		$load_more_start = $base->getVar($this->grid_params, 'load-more-start', 3, 'i');
		
		if($rows_unlimited == 'on' && $load_more !== 'none' && $grid_preview == false){ //grid_preview means disable load more in preview
			$post_limit = $load_more_start;
		}
		
		$start_sortby = $base->getVar($this->grid_params, 'sorting-order-by-start', 'none');
		
		$start_sortby_type = $base->getVar($this->grid_params, 'sorting-order-type', 'ASC');
		
		$post_category = $base->getVar($this->grid_postparams, 'post_category');
		$post_types = $base->getVar($this->grid_postparams, 'post_types');
		$page_ids = explode(',', $base->getVar($this->grid_postparams, 'selected_pages', '-1'));
		
		$max_entries = $this->get_maximum_entries($this);
		
		$additional_query = $base->getVar($this->grid_postparams, 'additional-query', '');
		if($additional_query !== '')
			$additional_query = wp_parse_args($additional_query);
		
		
		$cat_tax = array('cats' => '', 'tax' => '');
		
		if($this->custom_posts !== null){ //output by specific set posts
		
			$posts = Essential_Grid_Base::get_posts_by_ids($this->custom_posts, $start_sortby, $start_sortby_type);
			
			$cat_tax_obj = Essential_Grid_Base::get_categories_by_posts($posts);
			
			if(!empty($cat_tax_obj)){
				$cat_tax['cats'] = Essential_Grid_Base::translate_categories_to_string($cat_tax_obj);
			}
			//$cat_tax = Essential_Grid_Base::getCatAndTaxData($post_category); //get cats by posts
			
		}elseif($this->custom_special !== null){ //output by some special rule
			
			$max_entries = intval($base->getVar($this->grid_params, 'max-entries', '20'));
			if($max_entries == 0) $max_entries = 20;
			
			switch($this->custom_special){
				case 'related':
					$posts = Essential_Grid_Base::get_related_posts($max_entries);
				break;
				case 'popular':
					$posts = Essential_Grid_Base::get_popular_posts($max_entries);
				break;
				case 'latest':
				default:
					$posts = Essential_Grid_Base::get_latest_posts($max_entries);
				break;
			}
			
			$cat_tax_obj = Essential_Grid_Base::get_categories_by_posts($posts);
			
			if(!empty($cat_tax_obj)){
				$cat_tax['cats'] = Essential_Grid_Base::translate_categories_to_string($cat_tax_obj);
			}
			
			//$cat_tax = Essential_Grid_Base::getCatAndTaxData($post_category);  //get cats by posts
			
		}else{ //output with the grid settings from an existing grid
			
			$cat_tax = Essential_Grid_Base::getCatAndTaxData($post_category);
			
			$posts = Essential_Grid_Base::getPostsByCategory($this->grid_id, $cat_tax['cats'], $post_types, $cat_tax['tax'], $page_ids, $start_sortby, $start_sortby_type, $max_entries, $additional_query);
			
		}
		
		$nav_layout = $base->getVar($this->grid_params, 'navigation-layout', array());
		$nav_skin = $base->getVar($this->grid_params, 'navigation-skin', 'minimal-light');
		$hover_animation = $base->getVar($this->grid_params, 'hover-animation', 'fade');
		$filter_allow = $base->getVar($this->grid_params, 'filter-arrows', 'single');
		
		$nav_type = $base->getVar($this->grid_params, 'nagivation-type', 'internal');
		$do_nav = ($nav_type == 'internal') ? true : false;
		
		$order_by = explode(',', $base->getVar($this->grid_params, 'sorting-order-by', 'date'));
		if(!is_array($order_by)) $order_by = array($order_by);
		$order_by_start = $base->getVar($this->grid_params, 'sorting-order-by-start', 'none');
		if(strpos($order_by_start, 'eg-') === 0 || strpos($order_by_start, 'egl-') === 0){ //add meta at the end for meta sorting
			//if essential Meta, replace to meta name. Else -> replace - and _ with space, set each word uppercase
			$metas = $m->get_all_meta();
			$f = false;
			if(!empty($metas)){
				foreach($metas as $meta){
					if('eg-'.$meta['handle'] == $order_by_start || 'egl-'.$meta['handle'] == $order_by_start){
						$f = true;
						$order_by_start = $meta['name'];
						break;
					}
				}
			}
			
			if($f === false){
				$order_by_start = ucwords(str_replace(array('-', '_'), array(' ', ' '), $order_by_start));
			}
		}
		
		$sort_by_text = $base->getVar($this->grid_params, 'sort-by-text', __('Sort By ', EG_TEXTDOMAIN));
		
		$module_spacings = $base->getVar($this->grid_params, 'module-spacings', '5');
		
		$top_1_align = $base->getVar($this->grid_params, 'top-1-align', 'center');
		$top_2_align = $base->getVar($this->grid_params, 'top-2-align', 'center');
		$bottom_1_align = $base->getVar($this->grid_params, 'bottom-1-align', 'center');
		$bottom_2_align = $base->getVar($this->grid_params, 'bottom-2-align', 'center');
		
		$top_1_margin = $base->getVar($this->grid_params, 'top-1-margin-bottom', 0, 'i');
		$top_2_margin = $base->getVar($this->grid_params, 'top-2-margin-bottom', 0, 'i');
		$bottom_1_margin = $base->getVar($this->grid_params, 'bottom-1-margin-top', 0, 'i');
		$bottom_2_margin = $base->getVar($this->grid_params, 'bottom-2-margin-top', 0, 'i');
		
		$left_margin = $base->getVar($this->grid_params, 'left-margin-left', 0, 'i');
		$right_margin = $base->getVar($this->grid_params, 'right-margin-right', 0, 'i');
		
		$nav_styles['top-1'] = array('margin-bottom' => $top_1_margin.'px', 'text-align' => $top_1_align);
		$nav_styles['top-2'] = array('margin-bottom' => $top_2_margin.'px', 'text-align' => $top_2_align);
		$nav_styles['left'] = array('margin-left' => $left_margin.'px');
		$nav_styles['right'] = array('margin-right' => $right_margin.'px');
		$nav_styles['bottom-1'] = array('margin-top' => $bottom_1_margin.'px', 'text-align' => $bottom_1_align);
		$nav_styles['bottom-2'] = array('margin-top' => $bottom_2_margin.'px', 'text-align' => $bottom_2_align);
		
		$ajax_container_position = $base->getVar($this->grid_params, 'ajax-container-position', 'top');
		
		if($do_nav){ //only do if internal is selected
			$navigation_c->set_special_class('esg-fgc-'.$this->grid_id);
			
			$filters_meta = array();
				
			foreach($this->grid_params as $gkey => $gparam){
			
				if(strpos($gkey, 'filter-selected') === false) continue;
				
				$fil_id = intval(str_replace('filter-selected-', '', $gkey));
				$fil_id = ($fil_id == 0) ? '' : '-'.$fil_id;
				$filters_arr = array();
				
				$filters_arr['filter'.$fil_id]['filter-grouping'] = $base->getVar($this->grid_params, 'filter-grouping'.$fil_id, 'false');
				$filters_arr['filter'.$fil_id]['filter-listing'] = $base->getVar($this->grid_params, 'filter-listing'.$fil_id, 'list');
				$filters_arr['filter'.$fil_id]['filter-selected'] = $base->getVar($this->grid_params, 'filter-selected'.$fil_id, array());
				
				$filter_all_text = $base->getVar($this->grid_params, 'filter-all-text'.$fil_id, __('Filter - All', EG_TEXTDOMAIN));
				$filter_dropdown_text = $base->getVar($this->grid_params, 'filter-dropdown-text'.$fil_id, __('Filter Categories', EG_TEXTDOMAIN));
		
				if(!empty($filters_arr['filter'.$fil_id]['filter-selected'])){
					if(!empty($posts) && count($posts) > 0){
						foreach($filters_arr['filter'.$fil_id]['filter-selected'] as $fk => $filter){
							if(strpos($filter, 'meta-') === 0){
								unset($filters_arr['filter'.$fil_id]['filter-selected'][$fk]); //delete entry
								
								foreach($posts as $key => $post){
									$fil = str_replace('meta-', '', $filter);
									$post_filter_meta = $meta_c->get_meta_value_by_handle($post['ID'], 'eg-'.$fil);
									$arr = json_decode($post_filter_meta, true);
									$cur_filter = (is_array($arr)) ? $arr : array($post_filter_meta);
									//$cur_filter = explode(',', $post_filter_meta);
									$add_filter = array();
									if(!empty($cur_filter)){
										foreach($cur_filter as $k => $v){
											if(trim($v) !== ''){
												$add_filter[sanitize_key($v)] = array('name' => $v, 'slug' => sanitize_key($v), 'parent' => '0');
												if(!empty($filters_arr['filter'.$fil_id]['filter-selected'])){
													$filter_found = false;
													foreach($filters_arr['filter'.$fil_id]['filter-selected'] as $fcheck){
														if($fcheck == sanitize_key($v)){
															$filter_found = true;
															break;
														}
													}
													if(!$filter_found){
														$filters_arr['filter'.$fil_id]['filter-selected'][] = sanitize_key($v); //add found meta
													}
												}else{
													$filters_arr['filter'.$fil_id]['filter-selected'][] = sanitize_key($v); //add found meta
												}
											}
										}
										$filters_meta = $filters_meta + $add_filter;
										if(!empty($add_filter)) $navigation_c->set_filter($add_filter);
									}
								}
							}
						}
					}
				}

				$navigation_c->set_filter_settings('filter'.$fil_id, $filters_arr['filter'.$fil_id]);
				
				$navigation_c->set_filter_text($filter_all_text, $fil_id);
				$navigation_c->set_dropdown_text($filter_dropdown_text, $fil_id);
			}
			
			$navigation_c->set_filter_type($filter_allow);
			$navigation_c->set_specific_styles($nav_styles);
			
			$navigation_c->set_layout($nav_layout); //set the layout
			
			$navigation_c->set_orders($order_by); //set order of filter
			$navigation_c->set_orders_text($sort_by_text); //set order of filter
			$navigation_c->set_orders_start($order_by_start); //set order of filter
			
		}
		
		$nav_filters = array();
		
		$taxes = array('post_tag');
		if(!empty($cat_tax['tax']))
			$taxes = explode(',', $cat_tax['tax']);
			
		if(!empty($cat_tax['cats'])){
			$cats = explode(',', $cat_tax['cats']);

			foreach($cats as $key => $id){
				if(Essential_Grid_Wpml::is_wpml_exists() && isset($sitepress)){
					$new_id = icl_object_id($id, 'category', true, $sitepress->get_default_language());
					$cat = get_category($new_id);
				}else{
					$cat = get_category($id);
				}
				if(is_object($cat)){
					$nav_filters[$id] = array('name' => $cat->cat_name, 'slug' => sanitize_key($cat->slug), 'parent' => $cat->category_parent);
				}
				
				foreach($taxes as $custom_tax){
					$term = get_term_by('id', $id, $custom_tax);
					if(is_object($term)) $nav_filters[$id] = array('name' => $term->name, 'slug' => sanitize_key($term->slug), 'parent' => $term->parent);
				}
			}
			
			if(!empty($filters_meta)){
				$nav_filters = $filters_meta + $nav_filters;
			}
			asort($nav_filters);
		}
		
        $item_skin->init_by_id($base->getVar($this->grid_params, 'entry-skin', 0, 'i'));
		
		$lazy_load = $base->getVar($this->grid_params, 'lazy-loading', 'off');
		if($lazy_load == 'on')
			$item_skin->set_lazy_load(true);
		
        $default_media_source_order = $base->getVar($this->grid_postparams, 'media-source-order', '');
		$item_skin->set_default_media_source_order($default_media_source_order);
		
        $default_lightbox_source_order = $base->getVar($this->grid_params, 'lb-source-order', '');
		$item_skin->set_default_lightbox_source_order($default_lightbox_source_order);
		
		$default_aj_source_order = $base->getVar($this->grid_params, 'aj-source-order', '');
		$item_skin->set_default_ajax_source_order($default_aj_source_order);
		
		$lightbox_mode = $base->getVar($this->grid_params, 'lightbox-mode', 'single');
		$lightbox_include_media = $base->getVar($this->grid_params, 'lightbox-exclude-media', 'off');
		
		$post_media_source_type = $base->getVar($this->grid_postparams, 'image-source-type', 'full');
		
		$default_video_poster_order = $base->getVar($this->grid_params, 'poster-source-order', '');
		$item_skin->set_default_video_poster_order($default_video_poster_order);
		
		$layout = $base->getVar($this->grid_params, 'layout','even');
        $layout_sizing = $base->getVar($this->grid_params, 'layout-sizing', 'boxed');
		
		if($layout_sizing !== 'fullwidth' && $layout == 'masonry'){
			$item_skin->set_poster_cropping(true);
		}
		
		$skins_css = '';
		$skins_html = '';
		
		$found_filter = array();
		$i = 1;
		
		if($lightbox_mode == 'content' || $lightbox_mode == 'content-gallery' || $lightbox_mode == 'woocommerce-gallery'){
			$item_skin->set_lightbox_rel('ess-'.$this->grid_id);
		}
		
		if(!empty($posts) && count($posts) > 0){
			foreach($posts as $key => $post){
				if($grid_preview == false){
					//check if post should be visible or if its invisible on current grid settings
					$is_visible = $this->check_if_visible($post['ID'], $this->grid_id);
					if($is_visible == false) continue; // continue if invisible
				}
				
				$post_media_source_data = $base->get_post_media_source_data($post['ID'], $post_media_source_type);
				$post_video_ratios = $m->get_post_video_ratios($post['ID']);
				$filters = array();
				
				//$categories = get_the_category($post['ID']);
				$categories = $base->get_custom_taxonomies_by_post_id($post['ID']);
				//$tags = wp_get_post_terms($post['ID']);
				$tags = get_the_tags($post['ID']);
				
				if(!empty($categories)){
					foreach($categories as $key => $category){
						$filters[$category->term_id] = array('name' => $category->name, 'slug' => sanitize_key($category->slug), 'parent' => $category->parent);
					}
				}
				
				if(!empty($tags)){
					foreach($tags as $key => $taxonomie){
						$filters[$taxonomie->term_id] = array('name' => $taxonomie->name, 'slug' => sanitize_key($taxonomie->slug), 'parent' => '0');
					}
				}
				
				$filter_meta_selected = $base->getVar($this->grid_params, 'filter-selected', array());
				if(!empty($filter_meta_selected)){
					foreach($filter_meta_selected as $filter){
						if(strpos($filter, 'meta-') === 0){
							$fil = str_replace('meta-', '', $filter);
							$post_filter_meta = $meta_c->get_meta_value_by_handle($post['ID'], 'eg-'.$fil);
							$arr = json_decode($post_filter_meta, true);
							$cur_filter = (is_array($arr)) ? $arr : array($post_filter_meta);
							//$cur_filter = explode(',', $post_filter_meta);
							if(!empty($cur_filter)){
								foreach($cur_filter as $k => $v){
									if(trim($v) !== '')
										$filters[sanitize_key($v)] = array('name' => $v, 'slug' => sanitize_key($v), 'parent' => '0');
								}
							}
						}
					}
				}
				
				if(is_array($order_by) && !empty($order_by)){
					$sort = $this->prepare_sorting_array_by_post($post, $order_by);
					$item_skin->set_sorting($sort);
				}
				
				$found_filter = $found_filter + $filters; //these are the found filters, only show filter that the posts have
				
				if($i > $post_limit){
					$this->load_more_post_array[$post['ID']] = $filters; //set for load more, only on elements that will not be loaded from beginning
					continue; //Load only selected numbers of items at start (for load more)
				}
				$i++;
				
				if($lightbox_mode == 'content' || $lightbox_mode == 'content-gallery' || $lightbox_mode == 'woocommerce-gallery'){
					switch($lightbox_mode){
						case 'content':
							$lb_add_images = $base->get_all_content_images($post['ID']);
						break;
						case 'content-gallery':
							$lb_add_images = $base->get_all_gallery_images($post['post_content'], true);
						break;
						case 'woocommerce-gallery':
							$lb_add_images = array();
							if(Essential_Grid_Woocommerce::is_woo_exists()){
								$lb_add_images = Essential_Grid_Woocommerce::get_image_attachements($post['ID'], true);
							}
						break;
					}
					
					$item_skin->set_lightbox_addition(array('items' => $lb_add_images, 'base' => $lightbox_include_media));
				}
				
				$item_skin->set_filter($filters);
				$item_skin->set_media_sources($post_media_source_data);
				$item_skin->set_media_sources_type($post_media_source_type);
				$item_skin->set_video_ratios($post_video_ratios);
				$item_skin->set_post_values($post);
				
				ob_start();
				$item_skin->output_item_skin($grid_preview);
				$skins_html.= ob_get_contents();
				ob_clean();
				ob_end_clean();
				
				if($grid_preview == false){
					ob_start();
					$item_skin->output_element_css_by_meta();
					$skins_css.= ob_get_contents();
					ob_clean();
					ob_end_clean();
				}
			}
		}else{
			return false;
		}
		
		$remove_filter = array_diff_key($nav_filters, $found_filter); //check if we have filter that no post has (comes through multilanguage)
		if(!empty($remove_filter)){
			foreach($remove_filter as $key => $rem){ //we have, so remove them from the filter list before setting the filter list
				unset($nav_filters[$key]);
			}
		}
		
		if($do_nav){ //only do if internal is selected
			$navigation_c->set_filter($nav_filters); //set filters $nav_filters $found_filter
			$navigation_c->set_filter_type($filter_allow);
		}
		
		ob_start();
		$item_skin->generate_element_css();
		$skins_css.= ob_get_contents();
		ob_clean();
		ob_end_clean();
		
		if($do_nav){ //only do if internal is selected
			$found_skin = array();
			$navigation_skin = $base->getVar($this->grid_params, 'navigation-skin', 'minimal-light');
			$navigation_special_skin = $base->getVar($this->grid_params, 'navigation-special-skin', array());
			ob_start();
			$navigation_c->output_navigation_skin($navigation_skin);
			$found_skin[$navigation_skin] = true;
			
			if(!empty($navigation_special_skin)){
				foreach($navigation_special_skin as $spec_skin){
					if(!isset($found_skin[$spec_skin])){
						$navigation_c->output_navigation_skin($spec_skin);
						$found_skin[$spec_skin] = true;
					}
				}
			}
			$nav_css = ob_get_contents();
			ob_clean();
			ob_end_clean();
			
			echo $nav_css;
		}
		
		echo $skins_css;
		
		if($item_skin->ajax_loading == true && $ajax_container_position == 'top'){
			echo $this->output_ajax_container();
		}
		
		$this->output_wrapper_pre($grid_preview);
		if($do_nav){ //only do if internal is selected
			$navigation_c->output_layout('top-1', $module_spacings);
			$navigation_c->output_layout('top-2', $module_spacings);
		}
		
		$this->output_grid_pre();
		
		echo $skins_html;
		
		$this->output_grid_post();
		if($do_nav){ //only do if internal is selected
			$navigation_c->output_layout('bottom-1', $module_spacings);
			$navigation_c->output_layout('bottom-2', $module_spacings);
			$navigation_c->output_layout('left');
			$navigation_c->output_layout('right');
		}
		
		$this->output_wrapper_post();
		
		if($item_skin->ajax_loading == true && $ajax_container_position == 'bottom'){
			echo $this->output_ajax_container();
		}
		
		$load_lightbox = $item_skin->do_lightbox_loading();
		
		if(!$grid_preview)
			$this->output_grid_javascript($load_lightbox);
	}
    
	
	/**
	 * Output by specific posts for load more
	 */
	public function output_by_specific_posts(){

		$base = new Essential_Grid_Base();
		$item_skin = new Essential_Grid_Item_Skin();
		$item_skin->grid_id = $this->grid_id;
		$item_skin->set_grid_type($base->getVar($this->grid_params, 'layout','even'));
		$meta_c = new Essential_Grid_Meta();
		
		$item_skin->set_default_image_by_id($base->getVar($this->grid_postparams, 'default-image', 0, 'i'));
		
		$m = new Essential_Grid_Meta();
		
		$start_sortby = $base->getVar($this->grid_params, 'sorting-order-by-start', 'none');
		
		$start_sortby_type = $base->getVar($this->grid_params, 'sorting-order-type', 'ASC');
		
		if(!empty($this->filter_by_ids)){
			$posts = Essential_Grid_Base::get_posts_by_ids($this->filter_by_ids, $start_sortby, $start_sortby_type);
		}else{
			return false;
		}
		
        $item_skin->init_by_id($base->getVar($this->grid_params, 'entry-skin', 0, 'i'));
		$order_by = explode(',', $base->getVar($this->grid_params, 'sorting-order-by', 'date'));
		if(!is_array($order_by)) $order_by = array($order_by);
		
		
		$lazy_load = $base->getVar($this->grid_params, 'lazy-loading', 'off');
		if($lazy_load == 'on')
			$item_skin->set_lazy_load(true);
		
        $default_media_source_order = $base->getVar($this->grid_postparams, 'media-source-order', '');
		$item_skin->set_default_media_source_order($default_media_source_order);
		
		$default_lightbox_source_order = $base->getVar($this->grid_params, 'lb-source-order', '');
		$item_skin->set_default_lightbox_source_order($default_lightbox_source_order);
		
		$lightbox_mode = $base->getVar($this->grid_params, 'lightbox-mode', 'single');
		$lightbox_include_media = $base->getVar($this->grid_params, 'lightbox-exclude-media', 'off');
		
        $default_aj_source_order = $base->getVar($this->grid_params, 'aj-source-order', '');
		$item_skin->set_default_ajax_source_order($default_aj_source_order);
		
		$post_media_source_type = $base->getVar($this->grid_postparams, 'image-source-type', 'full');
		
		$default_video_poster_order = $base->getVar($this->grid_params, 'poster-source-order', '');
		$item_skin->set_default_video_poster_order($default_video_poster_order);
		
		$layout = $base->getVar($this->grid_params, 'layout','even');
        $layout_sizing = $base->getVar($this->grid_params, 'layout-sizing', 'boxed');
		
		if($layout_sizing !== 'fullwidth' && $layout == 'masonry'){
			$item_skin->set_poster_cropping(true);
		}
		
		$skins_html = '';
		
		if($lightbox_mode == 'content' || $lightbox_mode == 'content-gallery' || $lightbox_mode == 'woocommerce-gallery'){
			$item_skin->set_lightbox_rel('ess-'.$this->grid_id);
		}
		
		if(!empty($posts) && count($posts) > 0){
			foreach($posts as $key => $post){
				//check if post should be visible or if its invisible on current grid settings
				$is_visible = $this->check_if_visible($post['ID'], $this->grid_id);
				
				if($is_visible == false) continue; // continue if invisible
				
				$post_media_source_data = $base->get_post_media_source_data($post['ID'], $post_media_source_type);
				$post_video_ratios = $m->get_post_video_ratios($post['ID']);
				
				$filters = array();
				
				//$categories = get_the_category($post['ID']);
				$categories = $base->get_custom_taxonomies_by_post_id($post['ID']);
				//$tags = wp_get_post_terms($post['ID']);
				$tags = get_the_tags($post['ID']);
				
				if(!empty($categories)){
					foreach($categories as $key => $category){
						$filters[$category->term_id] = array('name' => $category->name, 'slug' => sanitize_key($category->slug));
					}
				}
				
				if(!empty($tags)){
					foreach($tags as $key => $taxonomie){
						$filters[$taxonomie->term_id] = array('name' => $taxonomie->name, 'slug' => sanitize_key($taxonomie->slug));
					}
				}
				
				$filter_meta_selected = $base->getVar($this->grid_params, 'filter-selected', array());
				if(!empty($filter_meta_selected)){
					foreach($filter_meta_selected as $filter){
						if(strpos($filter, 'meta-') === 0){
							$fil = str_replace('meta-', '', $filter);
							$post_filter_meta = $meta_c->get_meta_value_by_handle($post['ID'], 'eg-'.$fil);
							$arr = json_decode($post_filter_meta, true);
							$cur_filter = (is_array($arr)) ? $arr : array($post_filter_meta);
							//$cur_filter = explode(',', $post_filter_meta);
							if(!empty($cur_filter)){
								foreach($cur_filter as $k => $v){
									if(trim($v) !== '')
										$filters[sanitize_key($v)] = array('name' => $v, 'slug' => sanitize_key($v), 'parent' => '0');
								}
							}
						}
					}
				}
				
				if(is_array($order_by) && !empty($order_by)){
					$sort = $this->prepare_sorting_array_by_post($post, $order_by);
					$item_skin->set_sorting($sort);
				}
				
				if($lightbox_mode == 'content' || $lightbox_mode == 'content-gallery' || $lightbox_mode == 'woocommerce-gallery'){
					switch($lightbox_mode){
						case 'content':
							$lb_add_images = $base->get_all_content_images($post['ID']);
						break;
						case 'content-gallery':
							$lb_add_images = $base->get_all_gallery_images($post['post_content'], true);
						break;
						case 'woocommerce-gallery':
							$lb_add_images = array();
							if(Essential_Grid_Woocommerce::is_woo_exists()){
								$lb_add_images = Essential_Grid_Woocommerce::get_image_attachements($post['ID'], true);
							}
						break;
					}
					
					$item_skin->set_lightbox_addition(array('items' => $lb_add_images, 'base' => $lightbox_include_media));
				}
				
				$item_skin->set_filter($filters);
				$item_skin->set_media_sources($post_media_source_data);
				$item_skin->set_media_sources_type($post_media_source_type);
				$item_skin->set_video_ratios($post_video_ratios);
				$item_skin->set_post_values($post);
				$item_skin->set_load_more();
				
				
				ob_start();
				$item_skin->output_item_skin();
				$skins_html.= ob_get_contents();
				ob_clean();
				ob_end_clean();
				
			}
		}else{
			return false;
		}
		
		return $skins_html;
		
	}
    
	
	/**
	 * Output by specific ids for load more custom grid
	 */
	public function output_by_specific_ids(){
		
		$base = new Essential_Grid_Base();
		$item_skin = new Essential_Grid_Item_Skin();
		$item_skin->grid_id = $this->grid_id;
		$item_skin->set_grid_type($base->getVar($this->grid_params, 'layout','even'));
		
		$item_skin->set_default_image_by_id($base->getVar($this->grid_postparams, 'default-image', 0, 'i'));
		
		$m = new Essential_Grid_Meta();
		
		$filters = array();
		
		$order_by = explode(',', $base->getVar($this->grid_params, 'sorting-order-by', 'date'));
		if(!is_array($order_by)) $order_by = array($order_by);
		
        $item_skin->init_by_id($base->getVar($this->grid_params, 'entry-skin', 0, 'i'));
		
		$lazy_load = $base->getVar($this->grid_params, 'lazy-loading', 'off');
		if($lazy_load == 'on')
			$item_skin->set_lazy_load(true);
		
        $default_media_source_order = $base->getVar($this->grid_postparams, 'media-source-order', '');
		$item_skin->set_default_media_source_order($default_media_source_order);
		
        $default_lightbox_source_order = $base->getVar($this->grid_params, 'lb-source-order', '');
		$item_skin->set_default_lightbox_source_order($default_lightbox_source_order);
		
        $default_aj_source_order = $base->getVar($this->grid_params, 'aj-source-order', '');
		$item_skin->set_default_ajax_source_order($default_aj_source_order);
		
		$post_media_source_type = $base->getVar($this->grid_postparams, 'image-source-type', 'full');
		
		$default_video_poster_order = $base->getVar($this->grid_params, 'poster-source-order', '');
		$item_skin->set_default_video_poster_order($default_video_poster_order);
		
		$layout = $base->getVar($this->grid_params, 'layout','even');
        $layout_sizing = $base->getVar($this->grid_params, 'layout-sizing', 'boxed');
		
		if($layout_sizing !== 'fullwidth' && $layout == 'masonry'){
			$item_skin->set_poster_cropping(true);
		}
		
		$skins_html = '';
		
		$found_filter = array();
		
		if(!empty($this->grid_layers) && count($this->grid_layers) > 0){
			foreach($this->grid_layers as $key => $entry){
				
				if(!in_array($key, $this->filter_by_ids)) continue;
			
				$post_media_source_data = $base->get_custom_media_source_data($entry, $post_media_source_type);
				$post_video_ratios = $m->get_custom_video_ratios($entry);
				$filters = array();
				
				if(is_array($order_by) && !empty($order_by)){
					//$sort = $this->prepare_sorting_array_by_post($post, $order_by);
					//$item_skin->set_sorting($sort);
				}
				if(!empty($entry['custom-filter'])){
					$cats = explode(',', $entry['custom-filter']);
					if(!is_array($cats)) $cats = (array)$cats;
					foreach($cats as $category){
						$filters[sanitize_key($category)] = array('name' => $category, 'slug' => sanitize_key($category));
					}
				}
				
				$found_filter = $found_filter + $filters; //these are the found filters, only show filter that the posts have
				
				$item_skin->set_filter($filters);
				$item_skin->set_media_sources($post_media_source_data);
				$item_skin->set_media_sources_type($post_media_source_type);
				$item_skin->set_video_ratios($post_video_ratios);
				$item_skin->set_layer_values($entry);
				$item_skin->set_load_more();
				
				ob_start();
				$item_skin->output_item_skin();
				$skins_html.= ob_get_contents();
				ob_clean();
				ob_end_clean();
				
			}
		}else{
			return false;
		}
		
		return $skins_html;
		
	}
	
	
	public function prepare_sorting_array_by_post($post, $order_by){
		$base = new Essential_Grid_Base();
		$link_meta = new Essential_Grid_Meta_Linking();
		$meta = new Essential_Grid_Meta();
		
		$m = $meta->get_all_meta(false);
		$lm = $link_meta->get_all_link_meta(false);
		
		$sorts = array();
		foreach($order_by as $order){
			switch($order){
				case 'date':
					$sorts['date'] = strtotime($base->getVar($post, 'post_date'));
				break;
				case 'title':
					$sorts['title'] = substr($base->getVar($post, 'post_title', ''), 0, 10);
				break;
				case 'excerpt':
					$sorts['excerpt'] = substr(strip_tags($base->getVar($post, 'post_excerpt', '')), 0, 10);
				break;
				case 'id':
					$sorts['id'] = $base->getVar($post, 'ID');
				break;
				case 'slug':
					$sorts['slug'] = $base->getVar($post, 'post_name');
				break;
				case 'author':
					$authorID = $base->getVar($post, 'post_author');
					$sorts['author'] = get_the_author_meta('display_name', $authorID);
				break;
				case 'last-modified':
					$sorts['last-modified'] = strtotime($base->getVar($post, 'post_modified'));
				break;
				case 'number-of-comments':
					$sorts['number-of-comments'] = $base->getVar($post, 'comment_count');
				break;
				case 'random':
					$sorts['random'] = rand(0,9999);
				break;
				default: //check if meta. If yes, add meta values
					if(strpos($order, 'eg-') === 0){
						if(!empty($m)){
							foreach($m as $me){
								if('eg-'.$me['handle'] == $order){
									$sorts[$order] = $meta->get_meta_value_by_handle($post['ID'],$order);
									break;
								}
							}
						}
					}elseif(strpos($order, 'egl-') === 0){
						if(!empty($lm)){
							foreach($lm as $me){
								if('egl-'.$me['handle'] == $order){
									$sorts[$order] = $link_meta->get_link_meta_value_by_handle($post['ID'],$order);
									break;
								}
							}
						}
					}
				break;
			}
		}
		
		//add woocommerce sortings
		if(Essential_Grid_Woocommerce::is_woo_exists()){
			$product = get_product($post['ID']);
			if(!empty($product)){
				foreach($order_by as $order){
					switch($order){
						case 'meta_num_total_sales':
							$sorts['total-sales'] = get_post_meta($post['ID'],$order,true);
						break;
						case 'meta_num__regular_price':
							$sorts['regular-price'] = $product->get_price();
						break;
						//case 'meta_num__sale_price':
						//	$sorts['sale-price'] = $product->get_sale_price();
						//break;
						case 'meta__featured':
							$sorts['featured'] = ($product->is_featured()) ? '1' : '0';
						break;
						case 'meta__sku':
							$sorts['sku'] = $product->get_sku();
						break;
						case 'meta_num_stock':
							$sorts['in-stock'] = $product->get_stock_quantity();
						break;
					}
				}
			}
		}
		
		return $sorts;
	}
	
	
    public function output_wrapper_pre($grid_preview = false){
        
        $base = new Essential_Grid_Base();
        
        $this->grid_serial++;
		
		if($this->grid_div_name === null) $this->grid_div_name = $this->grid_id;
		
		$grid_id = ($grid_preview !== false) ? 'esg-preview-grid' : 'esg-grid-'.$this->grid_div_name.'-'.$this->grid_serial;
		$article_id = ($grid_preview !== false) ? ' id="esg-preview-skinlevel"' : '';
		
        $hide_markup_before_load = $base->getVar($this->grid_params, 'hide-markup-before-load', 'off');
        $background_color = $base->getVar($this->grid_params, 'main-background-color', 'transparent');
        $navigation_skin = $base->getVar($this->grid_params, 'navigation-skin', 'minimal-light');
        $paddings = $base->getVar($this->grid_params, 'grid-padding', 0);
        $css_id = $base->getVar($this->grid_params, 'css-id', '');
		
		$pad_style = '';
		
		if(is_array($paddings) && !empty($paddings)){
			$pad_style = 'padding: ';
			foreach($paddings as $size){
				$pad_style .= $size.'px ';
			}
			$pad_style .= ';';
			
			$pad_style .= ' box-sizing:border-box;';
			$pad_style .= ' -moz-box-sizing:border-box;';
			$pad_style .= ' -webkit-box-sizing:border-box;';
		}
		
		$div_style = ' style="';
		$div_style.= 'background-color: '.$background_color.';';
		$div_style.= $pad_style;
		if($hide_markup_before_load == 'on')
			$div_style.= ' display:none';
			
		$div_style.= '"';
        
		$css_id = ($css_id !== '') ? ' id="'.$css_id.'"' : '';
		
        echo '<!-- THE ESSENTIAL GRID '. self::VERSION .' -->'."\n\n";
        
		echo '<!-- GRID WRAPPER FOR CONTAINER SIZING - HERE YOU CAN SET THE CONTAINER SIZE AND CONTAINER SKIN -->'."\n";
		echo '<article class="myportfolio-container '.$navigation_skin.'"'.$article_id.$css_id.'>'."\n\n"; //fullwidthcontainer-with-padding 
        
        echo '    <!-- THE GRID ITSELF WITH FILTERS, PAGINATION, SORTING ETC... -->'."\n";
		echo '    <div id="'.$grid_id.'" class="esg-grid"'.$div_style.'>'."\n";
        
    }
    
    
    public function output_wrapper_post(){
        
        echo '    </div><!-- END OF THE GRID -->'."\n\n";

		echo '</article>'."\n";
		echo '<!-- END OF THE GRID WRAPPER -->'."\n\n";

		echo '<div class="clear"></div>'."\n";
        
    }
    
    
    public function output_grid_pre(){
        
        echo '<!-- ############################ -->'."\n";
        echo '<!-- THE GRID ITSELF WITH ENTRIES -->'."\n";
        echo '<!-- ############################ -->'."\n";
        echo '<ul>'."\n";
        
    }
    
    
    public function output_grid_post(){
        
        echo '</ul>'."\n";
        echo '<!-- ############################ -->'."\n";
        echo '<!--      END OF THE GRID         -->'."\n";
        echo '<!-- ############################ -->'."\n";
        
    }
    
    
    public function output_grid_javascript($load_lightbox = false, $is_demo = false){
        $base = new Essential_Grid_Base();
		
		$hide_markup_before_load = $base->getVar($this->grid_params, 'hide-markup-before-load', 'off');
		
        $layout = $base->getVar($this->grid_params, 'layout','even');
        $force_full_width = $base->getVar($this->grid_params, 'force_full_width', 'off');
		
        $content_push = $base->getVar($this->grid_params, 'content-push', 'off');
        
        $rows_unlimited = $base->getVar($this->grid_params, 'rows-unlimited', 'on');
        $load_more_type = $base->getVar($this->grid_params, 'load-more', 'on');
		$rows = $base->getVar($this->grid_params, 'rows', 4, 'i');
		
        $columns = $base->getVar($this->grid_params, 'columns', '');
        $columns = $base->set_basic_colums($columns);
        
        $columns_advanced = $base->getVar($this->grid_params, 'columns-advanced', 'off');
        if($columns_advanced == 'on')
            $columns_width = $base->getVar($this->grid_params, 'columns-width', '');
        else
            $columns_width = array(); //get defaults
        
        $columns_width = $base->set_basic_colums_width($columns_width);
        
        $space = $base->getVar($this->grid_params, 'spacings', 0, 'i');
        $page_animation = $base->getVar($this->grid_params, 'grid-animation', 'scale');
        $anim_speed = $base->getVar($this->grid_params, 'grid-animation-speed', 800, 'i');
        $delay_basic = $base->getVar($this->grid_params, 'grid-animation-delay', 1, 'i');
        $delay_hover = $base->getVar($this->grid_params, 'hover-animation-delay', 1, 'i');
        $filter_type = $base->getVar($this->grid_params, 'filter-arrows', 'single');
        $filter_logic = $base->getVar($this->grid_params, 'filter-logic', 'or');
		
        $lightbox_mode = $base->getVar($this->grid_params, 'lightbox-mode', 'single');
		$lightbox_mode = ($lightbox_mode == 'content' || $lightbox_mode == 'content-gallery' || $lightbox_mode == 'woocommerce-gallery') ? 'contentgroup' : $lightbox_mode;
		
        $layout_sizing = $base->getVar($this->grid_params, 'layout-sizing', 'boxed');
        $layout_offset_container = $base->getVar($this->grid_params, 'fullscreen-offset-container', '');
        
        $aspect_ratio_x = $base->getVar($this->grid_params, 'x-ratio', 4, 'i');
        $aspect_ratio_y = $base->getVar($this->grid_params, 'y-ratio', 3, 'i');
        
        $lazy_load = $base->getVar($this->grid_params, 'lazy-loading', 'off');
        $lazy_load_color = $base->getVar($this->grid_params, 'lazy-load-color', '#FFFFFF');
		
		$spinner = $base->getVar($this->grid_params, 'use-spinner', '0');
		$spinner_color = $base->getVar($this->grid_params, 'spinner-color', '#FFFFFF');
		
		
		//LIGHTBOX VARIABLES
		$usetwitter = $base->getVar($this->grid_params, 'lightbox-twitter','off');
		$usefacebook = $base->getVar($this->grid_params, 'lightbox-facebook','off');		
		$lightbox_title_type = $base->getVar($this->grid_params, 'lightbox-type', "null");
		$lightbox_position = $base->getVar($this->grid_params, 'lightbox-position', 'bottom');		
		
		$lightbox_effect_open_close = $base->getVar($this->grid_params, 'lightbox-effect-open-close', 'fade');		
		$lightbox_effect_next_prev = $base->getVar($this->grid_params, 'lightbox-effect-next-prev', 'fade');		
		$lightbox_effect_open_close_speed = $base->getVar($this->grid_params, 'lightbox-effect-open-close-speed', 'normal');		
		$lightbox_effect_next_prev_speed = $base->getVar($this->grid_params, 'lightbox-effect-next-prev-speed', 'normal');		
		
		$lightbox_arrows = $base->getVar($this->grid_params, 'lightbox-arrows', 'on'); 
		$lightbox_thumbs = $base->getVar($this->grid_params, 'lightbox-thumbs', 'off');
		$lightbox_thumbs_w = $base->getVar($this->grid_params, 'lbox-thumb-w', '50');		
		$lightbox_thumbs_h = $base->getVar($this->grid_params, 'lbox-thumb-h', '50');
		
		$linebreak = '\'<br />\'';	
		$twitteraddon = '\'<a href="https://twitter.com/share" class="twitter-share-button" data-count="none" data-url="\'+this.href+\'">'.__('Tweet', EG_TEXTDOMAIN).'</a>\'';
		$facebookaddon = '\'<iframe src="//www.facebook.com/plugins/like.php?href=\'+this.href+\'&amp;layout=button_count&amp;show_faces=true&amp;width=500&amp;action=like&amp;font&amp;colorscheme=light&amp;height=23" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:110px; height:23px;" allowTransparency="true"></iframe>\'';

		$lbox_padding = $base->getVar($this->grid_params, 'lbox-padding', array('0','0','0','0'));
		$lbox_inpadding = $base->getVar($this->grid_params, 'lbox-inpadding', array('0','0','0','0'));
		
		$rtl = $base->getVar($this->grid_params, 'rtl', 'off');
		
		$wait_for_fonts = get_option('tp_eg_wait_for_fonts', 'true');
		
		$pagination_scroll = $base->getVar($this->grid_params, 'pagination-scroll', 'off');
		$pagination_scroll_offset = $base->getVar($this->grid_params, 'pagination-scroll-offset', '0', 'i');
		
		$ajax_callback = $base->getVar($this->grid_params, 'ajax-callback', '');
		$ajax_css_url = $base->getVar($this->grid_params, 'ajax-css-url', '');
		$ajax_js_url = $base->getVar($this->grid_params, 'ajax-js-url', '');
		$ajax_scroll_onload = $base->getVar($this->grid_params, 'ajax-scroll-onload', 'on');
		$ajax_callback_argument = $base->getVar($this->grid_params, 'ajax-callback-arg', 'on');
		$ajax_content_id = $base->getVar($this->grid_params, 'ajax-container-id', '');
		$ajax_scrollto_offset = $base->getVar($this->grid_params, 'ajax-scrollto-offset', '0');
		$ajax_close_button = $base->getVar($this->grid_params, 'ajax-close-button', 'off');
		$ajax_button_nav = $base->getVar($this->grid_params, 'ajax-nav-button', 'off');
		$ajax_content_sliding = $base->getVar($this->grid_params, 'ajax-content-sliding', 'on');
		$ajax_button_type = $base->getVar($this->grid_params, 'ajax-button-type', 'button');
		if($ajax_button_type == 'type2'){
			$ajax_button_text = $base->getVar($this->grid_params, 'ajax-button-text', __('Close', EG_TEXTDOMAIN));
		}
		$ajax_button_skin = $base->getVar($this->grid_params, 'ajax-button-skin', 'light');
		$ajax_button_inner = $base->getVar($this->grid_params, 'ajax-button-inner', 'false');
		$ajax_button_h_pos = $base->getVar($this->grid_params, 'ajax-button-h-pos', 'r');
		$ajax_button_v_pos = $base->getVar($this->grid_params, 'ajax-button-v-pos', 't');
		
		$cobbles_pattern = $base->getVar($this->grid_params, 'cobbles-pattern', array());
		$use_cobbles_pattern = $base->getVar($this->grid_params, 'use-cobbles-pattern', 'off');
		
		$js_to_footer = (get_option('tp_eg_js_to_footer', 'false') == 'true') ? true : false;
		
		//add inline style into the footer
		if($js_to_footer && $is_demo == false){
			ob_start();
		}
		
        echo '<script type="text/javascript">'."\n";
        
        if($hide_markup_before_load == 'off') {
	        echo 'function eggbfc(winw,resultoption) {'."\n";
			echo '	var lasttop = winw,'."\n";
			echo '	lastbottom = 0,'."\n";
			echo '	smallest =9999,'."\n";
			echo '	largest = 0,'."\n";
			echo '	samount = 0,'."\n";
			echo '	lamoung = 0,'."\n";
			echo '	lastamount = 0,'."\n";
			echo '	resultid = 0,'."\n";
			echo '	resultidb = 0,'."\n";
			echo '	responsiveEntries = ['."\n";
	        echo '						{ width:'.$columns_width['0'].',amount:'.$columns['0'].'},'."\n";
	        echo '						{ width:'.$columns_width['1'].',amount:'.$columns['1'].'},'."\n";		
			echo '						{ width:'.$columns_width['2'].',amount:'.$columns['2'].'},'."\n";
			echo '						{ width:'.$columns_width['3'].',amount:'.$columns['3'].'},'."\n";
			echo '						{ width:'.$columns_width['4'].',amount:'.$columns['4'].'},'."\n";
			echo '						{ width:'.$columns_width['5'].',amount:'.$columns['5'].'},'."\n";
			echo '						{ width:'.$columns_width['6'].',amount:'.$columns['6'].'}'."\n";
	        echo '						];'."\n";
			echo '	if (responsiveEntries!=undefined && responsiveEntries.length>0)'."\n";
			echo '		jQuery.each(responsiveEntries, function(index,obj) {'."\n";
			echo '			var curw = obj.width != undefined ? obj.width : 0,'."\n";
			echo '				cura = obj.amount != undefined ? obj.amount : 0;'."\n";
			echo '			if (smallest>curw) {'."\n";
			echo '				smallest = curw;'."\n";
			echo '				samount = cura;'."\n";
			echo '				resultidb = index;'."\n";
			echo '			}'."\n";
			echo '			if (largest<curw) {'."\n";
			echo '				largest = curw;'."\n";
			echo '				lamount = cura;'."\n";
			echo '			}'."\n";
			echo '			if (curw>lastbottom && curw<=lasttop) {'."\n";
			echo '				lastbottom = curw;'."\n";
			echo '				lastamount = cura;'."\n";
			echo '				resultid = index;'."\n";
			echo '			}'."\n";
			echo '		})'."\n";
			echo '		if (smallest>winw) {'."\n";
			echo '			lastamount = samount;'."\n";
			echo '			resultid = resultidb;'."\n";
			echo '		}'."\n";
			echo '		var obj = new Object;'."\n";
			echo '		obj.index = resultid;'."\n";
			echo '		obj.column = lastamount;'."\n";
			echo '		if (resultoption=="id")'."\n";
			echo '			return obj;'."\n";
			echo '		else'."\n";
			echo '			return lastamount;'."\n";
			echo '	}'."\n";
	        echo 'if ("'.$layout.'"=="even") {'."\n";
			echo '	var coh=0,'."\n";
			echo '		container = jQuery("#esg-grid-'.$this->grid_div_name.'-'.$this->grid_serial.'");'."\n";	
			if($layout_sizing == 'fullscreen'){
				echo 'coh = jQuery(window).height();'."\n";							

				if($layout_offset_container !== ''){
					echo 'try{'."\n";				
					echo '	var offcontainers = "'.$layout_offset_container.'".split(",");'."\n";
					echo '	jQuery.each(offcontainers,function(index,searchedcont) {'."\n";
					echo '		coh = coh - jQuery(searchedcont).outerHeight(true);'."\n";
					echo '	})'."\n";
					echo '} catch(e) {}'."\n";		
				}						
			} else {
				echo '	var	cwidth = container.width(),'."\n";
				echo '		ar = "'.$aspect_ratio_x.':'.$aspect_ratio_y.'",'."\n";
				echo '		gbfc = eggbfc(jQuery(window).width(),"id"),'."\n";
				if($rows_unlimited == 'on'){
					echo '	row = 2;'."\n";
				} else {
					echo '	row = '.$rows.';'."\n";
				}																		
				echo 'ar = ar.split(":");'."\n";
				echo 'aratio=parseInt(ar[0],0) / parseInt(ar[1],0);'."\n";
				echo 'coh = cwidth / aratio;'."\n";
				echo 'coh = coh/gbfc.column*row;'."\n";
			}
			echo '	var ul = container.find("ul").first();'."\n";
			echo '	ul.css({display:"block",height:coh+"px"});'."\n";
			echo '}'."\n";
		}
		
        echo 'var essapi_'.$this->grid_api_name.';'."\n";
        echo 'jQuery(document).ready(function() {'."\n";
        echo '	essapi_'.$this->grid_api_name.' = jQuery("#esg-grid-'.$this->grid_div_name.'-'.$this->grid_serial.'").tpessential({'."\n";
        echo '        layout:"'.$layout.'",'."\n";
        
		if($rtl == 'on') echo '        rtl:"on",'."\n";
		
        echo '        forceFullWidth:"'.$force_full_width.'",'."\n";
        echo '        lazyLoad:"'.$lazy_load.'",'."\n";
		if($lazy_load == 'on')
			echo '        lazyLoadColor:"'.$lazy_load_color.'",'."\n";
		
        if($rows_unlimited == 'on'){
			$load_more		  = $base->getVar($this->grid_params, 'load-more', 'button');
			$load_more_amount = $base->getVar($this->grid_params, 'load-more-amount', 3, 'i');
			$load_more_show_number = $base->getVar($this->grid_params, 'load-more-show-number', 'on');
			
			if($load_more !== 'none'){
				$load_more_text = $base->getVar($this->grid_params, 'load-more-text', __('Load More', EG_TEXTDOMAIN));
				echo '        gridID:"'.$this->grid_id.'",'."\n";
				echo '        loadMoreType:"'.$load_more.'",'."\n";
				echo '        loadMoreAmount:'.$load_more_amount.','."\n";
				echo '        loadMoreTxt:"'.$load_more_text.'",'."\n";
				echo '        loadMoreNr:"'.$load_more_show_number.'",'."\n";
				echo '        loadMoreEndTxt:"'.__('No More Items for the Selected Filter', EG_TEXTDOMAIN).'",'."\n";   
				echo '        loadMoreItems:';
				$this->output_load_more_list();
				echo ','."\n";
			}
			echo '        row:9999,'."\n";
        }else{
			echo '        row:'.$rows.','."\n";
		}
		$token = wp_create_nonce('Essential_Grid_Front');
		echo '        loadMoreAjaxToken:"'.$token.'",'."\n";
		echo '        loadMoreAjaxUrl:"'.admin_url('admin-ajax.php').'",'."\n";
		echo '        loadMoreAjaxAction:"Essential_Grid_Front_request_ajax",'."\n";
		
		echo '        ajaxContentTarget:"'.$ajax_content_id.'",'."\n";
		echo '        ajaxScrollToOffset:"'.$ajax_scrollto_offset.'",'."\n";
		echo '        ajaxCloseButton:"'.$ajax_close_button.'",'."\n";
		echo '        ajaxContentSliding:"'.$ajax_content_sliding.'",'."\n";
		if($ajax_callback !== '') echo '        ajaxCallback:"'.stripslashes($ajax_callback).'",'."\n";
		if($ajax_css_url !== '') echo '        ajaxCssUrl:"'.$ajax_css_url.'",'."\n";
		if($ajax_js_url !== '') echo '        ajaxJsUrl:"'.$ajax_js_url.'",'."\n";
		if($ajax_scroll_onload !== 'off') echo  '        ajaxScrollToOnLoad:"on",'."\n";
		if($ajax_callback_argument == 'on') echo  '        ajaxCallbackArgument:"on",'."\n";
		
		echo '        ajaxNavButton:"'.$ajax_button_nav.'",'."\n";
		echo '        ajaxCloseType:"'.$ajax_button_type.'",'."\n";
		if($ajax_button_type == 'type2'){
			echo '        ajaxCloseTxt:"'.$ajax_button_text.'",'."\n";
		}
		echo '        ajaxCloseInner:"'.$ajax_button_inner.'",'."\n";
		echo '        ajaxCloseStyle:"'.$ajax_button_skin.'",'."\n";
		
		$ajax_button_h_pos = $base->getVar($this->grid_params, 'ajax-button-h-pos', 'r');
		$ajax_button_v_pos = $base->getVar($this->grid_params, 'ajax-button-v-pos', 't');
		if($ajax_button_h_pos == 'c'){
			echo '        ajaxClosePosition:"'.$ajax_button_v_pos.'",'."\n";
		}else{
			echo '        ajaxClosePosition:"'.$ajax_button_v_pos.$ajax_button_h_pos.'",'."\n";
		}
		
        echo '        space:'.$space.','."\n";
        echo '        pageAnimation:"'.$page_animation.'",'."\n";
		
		echo '        paginationScrollToTop:"'.$pagination_scroll.'",'."\n";
        if($pagination_scroll == 'on'){
			echo '        paginationScrollToOffset:'.$pagination_scroll_offset.','."\n";
		}
		
        echo '        spinner:"spinner'.$spinner.'",'."\n";
		
		if($spinner != '0' && $spinner != '5')
			echo '        spinnerColor:"'.$spinner_color.'",'."\n";
		
        if($layout_sizing == 'fullwidth'){
			echo '        forceFullWidth:"on",'."\n";
		}elseif($layout_sizing == 'fullscreen'){
			echo '        forceFullScreen:"on",'."\n";
			if($layout_offset_container !== ''){
				echo '        fullScreenOffsetContainer:"'.$layout_offset_container.'",'."\n";
			}
		}
		
		if($layout == 'even')
			echo '        evenGridMasonrySkinPusher:"'.$content_push.'",'."\n";
		
        echo '        lightBoxMode:"'.$lightbox_mode.'",'."\n";
		
		if(!empty($cobbles_pattern) && $layout == 'cobbles' && $use_cobbles_pattern == 'on'){
			echo '        cobblesPattern:"'.implode(',', $cobbles_pattern).'",'."\n";
		}
        echo '        animSpeed:'.$anim_speed.','."\n";
        echo '        delayBasic:'.$delay_basic.','."\n";
        echo '        mainhoverdelay:'.$delay_hover.','."\n";
		
        echo '        filterType:"'.$filter_type.'",'."\n";
		
		if($filter_type == 'multi'){
			echo '        filterLogic:"'.$filter_logic.'",'."\n";
		}
		
        echo '        filterGroupClass:"esg-fgc-'.$this->grid_id.'",'."\n";
		
		if($wait_for_fonts === 'true'){
			$tf_fonts = new ThemePunch_Fonts();
			$fonts = $tf_fonts->get_all_fonts();
			if(!empty($fonts)){
				$first = true;
				$font_string = '[';
				foreach($fonts as $font){
					if($first === false) $font_string.= ',';
					$font_string.= "'".esc_attr($font['url'])."'";
					$first = false;
				}
				$font_string.= ']';
				echo '        googleFonts:'.$font_string.','."\n";
			}
		}
		
        if($layout != 'masonry'){
            echo '        aspectratio:"'.$aspect_ratio_x.':'.$aspect_ratio_y.'",'."\n";
        }
        echo '        responsiveEntries: ['."\n";
        echo '						{ width:'.$columns_width['0'].',amount:'.$columns['0'].'},'."\n";
        echo '						{ width:'.$columns_width['1'].',amount:'.$columns['1'].'},'."\n";		
		echo '						{ width:'.$columns_width['2'].',amount:'.$columns['2'].'},'."\n";
		echo '						{ width:'.$columns_width['3'].',amount:'.$columns['3'].'},'."\n";
		echo '						{ width:'.$columns_width['4'].',amount:'.$columns['4'].'},'."\n";
		echo '						{ width:'.$columns_width['5'].',amount:'.$columns['5'].'},'."\n";
		echo '						{ width:'.$columns_width['6'].',amount:'.$columns['6'].'}'."\n";
        echo '						]';
		
		if($columns_advanced == 'on')
			$this->output_ratio_list();
		
		echo "\n";
		
        echo '	});'."\n\n";
		
		//check if lightbox is active
		if($load_lightbox) {
			echo '	try{'."\n";
			echo '	jQuery("#esg-grid-'.$this->grid_div_name.'-'.$this->grid_serial.' .esgbox").esgbox({'."\n";
			echo '		padding : ['.$lbox_padding[0].','.$lbox_padding[1].','.$lbox_padding[2].','.$lbox_padding[3].'],'."\n";
			echo '      afterLoad:function() { '."\n";
			echo ' 		if (this.element.hasClass("esgboxhtml5")) {'."\n";
			echo '		   var mp = this.element.data("mp4"),'."\n";
			echo '		      ogv = this.element.data("ogv"),'."\n";
			echo '		      webm = this.element.data("webm");'."\n";	
			echo '         this.content =\'<div style="width:100%;height:100%;"><video autoplay="true" loop="" class="rowbgimage" poster="" width="100%" height="auto"><source src="\'+mp+\'" type="video/mp4"><source src="\'+webm+\'" type="video/webm"><source src="\'+ogv+\'" type="video/ogg"></video></div>\';	'."\n";			
			echo '		   var riint = setInterval(function() {jQuery(window).trigger("resize");},100); setTimeout(function() {clearInterval(riint);},2500);'."\n";
			echo '		   };'."\n";				
			echo '		 },'."\n";
		/*	echo '		ajax: { type:"post",url:'.admin_url('admin-ajax.php').',dataType:"json",data:{
										 action: "Essential_Grid_Front_request_ajax",
									     client_action: "load_more_content",
									     token: '.$token.',
									     postid:postid}, success:function(data) { jQuery.esgbox(data.data)} },'."\n";*/
			echo '		beforeShow : function () { '."\n";
			echo '			this.title = jQuery(this.element).attr(\'lgtitle\');'."\n";
			echo '			if (this.title) {'."\n";
			if ($lightbox_title_type=="null") 
				echo '				this.title="";'."\n";
			if ($usetwitter=="on" || $usefacebook=="on")
				echo '				this.title += '.$linebreak.';'."\n";
			if ($usetwitter=="on")
				echo '				this.title += '.$twitteraddon.';'."\n";
			if ($usefacebook=="on")
				echo '				this.title += '.$facebookaddon.';'."\n";
			
			echo '   		this.title =  \'<div style="padding:'.$lbox_inpadding[0].'px '.$lbox_inpadding[1].'px '.$lbox_inpadding[2].'px '.$lbox_inpadding[3].'px">\'+this.title+\'</div>\';'."\n";										
			echo '			}'."\n";															

			echo '		},'."\n";
			
			echo '		afterShow : function() {'."\n";			
			
			if ($usetwitter=="on")
				echo '			twttr.widgets.load();'."\n";
			echo '		},'."\n";
			echo '		openEffect : \''.$lightbox_effect_open_close.'\','."\n";		
			echo '		closeEffect : \''.$lightbox_effect_open_close.'\','."\n";		
			echo '		nextEffect : \''.$lightbox_effect_next_prev.'\','."\n";		
			echo '		prevEffect : \''.$lightbox_effect_next_prev.'\','."\n";											
			echo '		openSpeed : \''.$lightbox_effect_open_close_speed.'\','."\n";		
			echo '		closeSpeed : \''.$lightbox_effect_open_close_speed.'\','."\n";		
			echo '		nextSpeed : \''.$lightbox_effect_next_prev_speed.'\','."\n";		
			echo '		prevSpeed : \''.$lightbox_effect_next_prev_speed.'\','."\n";	
			if ($lightbox_arrows=="off")
				echo '		arrows : false,'."\n";													
			echo '		helpers : {'."\n";
			echo '			media : {},'."\n";
			if ($lightbox_thumbs == "on") {
				echo '			thumbs: {'."\n";
				echo '				width : '.$lightbox_thumbs_w.','."\n";
				echo '				height : '.$lightbox_thumbs_h."\n";			
				echo '			},'."\n";			
			}
			echo '		    title : {'."\n";
			if ($lightbox_title_type!="null") 
				echo '				type:"'.$lightbox_title_type.'",'."\n";
			else
				echo '				type:""'."\n";
			if ($lightbox_title_type!="null") 
				echo '				position:"'.$lightbox_position.'",'."\n";			
			echo '			}'."\n";
			
			echo '		}'."\n";
			echo '});'."\n"."\n";
			echo ' } catch (e) {}'."\n"."\n";
		}		
		
		//output custom javascript if any is set
		$custom_javascript = stripslashes($base->getVar($this->grid_params, 'custom-javascript', ''));
		if($custom_javascript !== ''){
			echo $custom_javascript;
		}
		echo '});'."\n";
		echo '</script>'."\n";
		
		if($js_to_footer && $is_demo == false){
			$js_content = ob_get_contents();
			ob_clean();
			ob_end_clean();
			
			$this->grid_inline_js = $js_content;
			
			add_action('wp_footer', array($this, 'add_inline_js'));
		}
        
    }
	
	
	/**
	 * Output the Load More list of posts
	 */
	public function output_load_more_list(){
		
		if(!empty($this->load_more_post_array)){
			$wrap_first = true;
			echo '[';
			
			foreach($this->load_more_post_array as $id => $filter){
				echo (!$wrap_first) ? ','."\n" : "\n";
				
				echo '				['.$id.', [-1, ';
				
				if(!empty($filter)){
					$slug_first = true;
					foreach($filter as $slug_id => $slug){
						echo (!$slug_first) ? ', ' : '';
						
						if(intval($slug_id == 0)) $slug_id = "'".$slug_id."'";
						echo $slug_id;
						
						$slug_first = false;
					}
				}
				
				echo ']]';
				
				$wrap_first = false;
			}
			
			echo ']';
		}else{
			echo '[]';
		}
	}
	
	
	/**
	 * Output the custom row sizes if its set
	 */
	public function output_ratio_list(){
		$base = new Essential_Grid_Base;
		
		$columns = $base->getVar($this->grid_params, 'columns', ''); //this is the first line
        $columns = $base->set_basic_colums($columns);
		
		$columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-0', '');
		$columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-1', '');
		$columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-2', '');
		$columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-3', '');
		$columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-4', '');
		$columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-5', '');
		$columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-6', '');
		$columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-7', '');
		$columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-8', '');
		//$columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-9', '');
		
		$found_rows = 0;
		foreach($columns_advanced as $adv_key => $adv){
			if(empty($adv)) continue;
			$found_rows++;
		}
		
		if($found_rows > 0){
			echo ','."\n";
			echo '		rowItemMultiplier: ['."\n";
			
			echo '						[';
			echo $columns[0].',';
			echo $columns[1].',';
			echo $columns[2].',';
			echo $columns[3].',';
			echo $columns[4].',';
			echo $columns[5].',';
			echo $columns[6];
			echo ']';
			
			foreach($columns_advanced as $adv_key => $adv){
				if(empty($adv)) continue;
				
				echo ','."\n";
				echo '						[';
				
				$entry_first = true;
				foreach($adv as $val){
					echo (!$entry_first) ? ',' : '';
					echo $val;
					$entry_first = false;
				}
				
				echo ']';
			}
			
			echo "\n".'						]';
		}
	}
	
	
	/**
	 * check if post is visible in grid
	 */
	public function check_if_visible($post_id, $grid_id){
		$pr_visibility = json_decode(get_post_meta($post_id, 'eg_visibility', true), true);
		
		$is_visible = true;
		
		if(!empty($pr_visibility) && is_array($pr_visibility)){ //check if element is visible in grid
			foreach($pr_visibility as $pr_grid => $pr_setting){
				if($pr_grid == $grid_id){
					if($pr_setting == false)
						$is_visible = false;
					else
						$is_visible = true;
					break;
				}
			}
		}
		
		return $is_visible;
	}
	
	
	/**
	 * Output Filter from current Grid (used for Widgets)
	 * @since 1.0.6
	 */
	public function output_grid_filter(){
		
		switch($this->grid_postparams['source-type']){
			case 'post':
				$this->output_filter_by_posts();
			break;
			case 'custom':
				$this->output_filter_by_custom();
			break;
			case 'streams':
			break;
		}
		
	}
	
	
	/**
	 * Output Sorting from current Grid (used for Widgets)
	 * @since 1.0.6
	 */
	public function output_grid_sorting(){
		
		switch($this->grid_postparams['source-type']){
			case 'post':
				$this->output_sorting_by_posts();
			break;
			case 'custom':
				$this->output_sorting_by_custom();
			break;
			case 'streams':
			break;
		}
		
	}
	
	
	/**
	 * Output Sorting from post based
	 * @since 1.0.6
	 */
	public function output_sorting_by_posts(){
		$this->output_sorting_by_all_types();
	}
	
	
	/**
	 * Output Sorting from custom grid
	 * @since 1.0.6
	 */
	public function output_sorting_by_custom(){
		$this->output_sorting_by_all_types();
	}
	
	
	/**
	 * Output Sorting from custom grid
	 * @since 1.0.6
	 */
	public function output_sorting_by_all_types(){
		$base = new Essential_Grid_Base();
		$nav = new Essential_Grid_Navigation();
		$m = new Essential_Grid_Meta();
		
		$order_by = explode(',', $base->getVar($this->grid_params, 'sorting-order-by', 'date'));
		if(!is_array($order_by)) $order_by = array($order_by);

		$order_by_start = $base->getVar($this->grid_params, 'sorting-order-by-start', 'none');
		if(strpos($order_by_start, 'eg-') === 0 || strpos($order_by_start, 'egl-') === 0){ //add meta at the end for meta sorting
			//if essential Meta, replace to meta name. Else -> replace - and _ with space, set each word uppercase
			$metas = $m->get_all_meta();
			$f = false;
			if(!empty($metas)){
				foreach($metas as $meta){
					if('eg-'.$meta['handle'] == $order_by_start || 'egl-'.$meta['handle'] == $order_by_start){
						$f = true;
						$order_by_start = $meta['name'];
						break;
					}
				}
			}
			
			if($f === false){
				$order_by_start = ucwords(str_replace(array('-', '_'), array(' ', ' '), $order_by_start));
			}
		}
		
		$nav->set_orders($order_by); //set order of filter
		$nav->set_orders_start($order_by_start); //set order of filter
		
		$nav->output_sorting();
	}
	
	
	/**
	 * Output Filter from post based
	 * @since 1.0.6
	 */
	public function output_filter_by_posts(){
		$base = new Essential_Grid_Base();
		$nav = new Essential_Grid_Navigation();
		
		$filter_allow = $base->getVar($this->grid_params, 'filter-arrows', 'single');
		$filter_all_text = $base->getVar($this->grid_params, 'filter-all-text', __('Filter - All', EG_TEXTDOMAIN));
		$filter_dropdown_text = $base->getVar($this->grid_params, 'filter-dropdown-text', __('Filter Categories', EG_TEXTDOMAIN));
		
		$nav->set_filter_text($filter_all_text);
		$nav->set_dropdown_text($filter_dropdown_text);

		$start_sortby = $base->getVar($this->grid_params, 'sorting-order-by-start', 'none');
		$start_sortby_type = $base->getVar($this->grid_params, 'sorting-order-type', 'ASC');
		
		$post_category = $base->getVar($this->grid_postparams, 'post_category');
		$post_types = $base->getVar($this->grid_postparams, 'post_types');
		$page_ids = explode(',', $base->getVar($this->grid_postparams, 'selected_pages', '-1'));
		
		$additional_query = $base->getVar($this->grid_postparams, 'additional-query', '');
		if($additional_query !== '')
			$additional_query = wp_parse_args($additional_query);

		$cat_tax = Essential_Grid_Base::getCatAndTaxData($post_category);

		$posts = Essential_Grid_Base::getPostsByCategory($this->grid_id, $cat_tax['cats'], $post_types, $cat_tax['tax'], $page_ids, $start_sortby, $start_sortby_type, -1, $additional_query);

		$nav_filters = array();

		$taxes = array('post_tag');
		if(!empty($cat_tax['tax']))
			$taxes = explode(',', $cat_tax['tax']);
			
		if(!empty($cat_tax['cats'])){
			$cats = explode(',', $cat_tax['cats']);
			
			foreach($cats as $key => $id){
				$cat = get_category($id);
				if(is_object($cat))	$nav_filters[$id] = array('name' => $cat->cat_name, 'slug' => sanitize_key($cat->slug));
				
				foreach($taxes as $custom_tax){
					$term = get_term_by('id', $id, $custom_tax);
					if(is_object($term)) $nav_filters[$id] = array('name' => $term->name, 'slug' => sanitize_key($term->slug));
				}
			}
			
			asort($nav_filters);
		}


		$found_filter = array();
		if(!empty($posts) && count($posts) > 0){
			foreach($posts as $key => $post){
				//check if post should be visible or if its invisible on current grid settings
				$is_visible = $this->check_if_visible($post['ID'], $this->grid_id);
				if($is_visible == false) continue; // continue if invisible
				
				$filters = array();
				
				//$categories = get_the_category($post['ID']);
				$categories = $base->get_custom_taxonomies_by_post_id($post['ID']);
				//$tags = wp_get_post_terms($post['ID']);
				$tags = get_the_tags($post['ID']);
				
				if(!empty($categories)){
					foreach($categories as $key => $category){
						$filters[$category->term_id] = array('name' => $category->name, 'slug' => sanitize_key($category->slug));
					}
				}
				
				if(!empty($tags)){
					foreach($tags as $key => $taxonomie){
						$filters[$taxonomie->term_id] = array('name' => $taxonomie->name, 'slug' => sanitize_key($taxonomie->slug));
					}
				}
				
				$found_filter = $found_filter + $filters; //these are the found filters, only show filter that the posts have
			}
		}

		$remove_filter = array_diff_key($nav_filters, $found_filter); //check if we have filter that no post has (comes through multilanguage)
		if(!empty($remove_filter)){
			foreach($remove_filter as $key => $rem){ //we have, so remove them from the filter list before setting the filter list
				unset($nav_filters[$key]);
			}
		}

		$nav->set_filter($nav_filters); //set filters $nav_filters $found_filter
		$nav->set_filter_type($filter_allow);
		
		
		$nav->output_filter();
		
	}
	
	
	/**
	 * Output Filter from custom grid
	 * @since 1.0.6
	 */
	public function output_filter_by_custom(){
		$base = new Essential_Grid_Base();
		$nav = new Essential_Grid_Navigation();
		
		$filter_allow = $base->getVar($this->grid_params, 'filter-arrows', 'single');
		$filter_all_text = $base->getVar($this->grid_params, 'filter-all-text', __('Filter - All', EG_TEXTDOMAIN));
		$filter_dropdown_text = $base->getVar($this->grid_params, 'filter-dropdown-text', __('Filter Categories', EG_TEXTDOMAIN));
		
		$nav->set_dropdown_text($filter_dropdown_text);
		
		$nav->set_filter_text($filter_all_text);

		$found_filter = array();

		if(!empty($this->grid_layers) && count($this->grid_layers) > 0){
			foreach($this->grid_layers as $key => $entry){
				$filters = array();
				
				if(!empty($entry['custom-filter'])){
					$cats = explode(',', $entry['custom-filter']);
					if(!is_array($cats)) $cats = (array)$cats;
					foreach($cats as $category){
						$filters[sanitize_key($category)] = array('name' => $category, 'slug' => sanitize_key($category));
						
						$found_filter = $found_filter + $filters; //these are the found filters, only show filter that the posts have
						
					}
				}
			}
		}
		
		$nav->set_filter($found_filter); //set filters $nav_filters $found_filter
		$nav->set_filter_type($filter_allow);
		
		$nav->output_filter();
		
	}
	
	/**
	 * Output Ajax Container
	 * @since 1.5.0
	 */
	public function output_ajax_container(){
	
		$base = new Essential_Grid_Base();
		
		$container_id = $base->getVar($this->grid_params, 'ajax-container-id', '');
		$container_css = $base->getVar($this->grid_params, 'ajax-container-css', ''); 
		
		$container_pre = $base->getVar($this->grid_params, 'ajax-container-pre', ''); 
		$container_post = $base->getVar($this->grid_params, 'ajax-container-post', ''); 
		
		$cont = '';
		$cont .= '<div class="eg-ajax-target-container-wrapper" id="'.$container_id.'">'."\n";
		$cont .= '	<!-- CONTAINER FOR PREFIX -->'."\n";
		$cont .= '	<div class="eg-ajax-target-prefix-wrapper">'."\n";
		$cont .= $container_pre;
		$cont .= '	</div>'."\n";
		$cont .= '	<!-- CONTAINER FOR CONTENT TO LOAD -->'."\n";
		$cont .= '	<div class="eg-ajax-target"></div>'."\n";
		$cont .= '	<!-- CONTAINER FOR SUFFIX -->'."\n";
		$cont .= '	<div class="eg-ajax-target-sufffix-wrapper">'."\n";
		$cont .= $container_post;
		$cont .= '	</div>'."\n";
		$cont .= '</div>'."\n";
		
		if($container_css !== '' && $container_id !== ''){
			$cont .= '<!-- CONTAINER CSS -->'."\n";
			$cont .= '<style type="text/css">'."\n";
			$cont .= '#'.$container_id.' {'."\n";
			$cont .= $container_css;
			$cont .= '}'."\n";
			$cont .= '</style>';
		}
		
		$cont = do_shortcode($cont);
		return $cont;
	}
	
	
	/**
	 * Output Inline JS
	 * @since 1.1.0
	 */
	public function add_inline_js(){
	
		echo $this->grid_inline_js;
		
	}
	
	
	/**
	 * Check the maximum entries that should be loaded
	 * @since: 1.5.3
	 */
	public function get_maximum_entries($grid){
		$base = new Essential_Grid_Base();
		
		$max_entries = intval($grid->get_postparam_by_handle('max_entries', '-1'));
		
		if($max_entries !== -1) return $max_entries;
		
		$layout = $grid->get_param_by_handle('navigation-layout', array());
		
		if(isset($layout['pagination']) || isset($layout['left']) || isset($layout['right'])) return $max_entries;
		
		$rows_unlimited = $grid->get_param_by_handle('rows-unlimited', 'on');
		$load_more = $grid->get_param_by_handle('load-more', 'none');
        $rows = intval($grid->get_param_by_handle('rows', '3'));
		
		$columns_advanced = $grid->get_param_by_handle('columns-advanced', 'off');
       
        $columns = $grid->get_param_by_handle('columns', ''); //this is the first line
        $columns = $base->set_basic_colums($columns);
		
		$max_column = 0;
		foreach($columns as $column){
			if($max_column < $column) $max_column = $column;
		}
		
		if($columns_advanced === 'on'){
			$columns_advanced = array();
			$columns_advanced[] = $columns;
			$columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-0', '');
			$columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-1', '');
			$columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-2', '');
			$columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-3', '');
			$columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-4', '');
			$columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-5', '');
			$columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-6', '');
			$columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-7', '');
			$columns_advanced[] = $base->getVar($this->grid_params, 'columns-advanced-rows-8', '');
			
			$match = array(0,0,0,0,0,0,0);
			for($i=0;$i<=$rows;$i++){
				foreach($columns_advanced as $col_adv){
					if(!empty($col_adv)){
						foreach($col_adv as $key => $val){
							$match[$key] += $val;
						}
						$i++;
					}
					if($i>=$rows) break;
				}
			}
			
			foreach($match as $highest){
				if($max_column < $highest) $max_column = $highest;
			}
			
		}
		
		if($rows_unlimited === 'off'){
			if($columns_advanced === 'off'){
				$max_entries = $max_column * $rows;
			}else{
				$max_entries = $max_column;
			}
		}elseif($rows_unlimited === 'on' && $load_more === 'none'){
			$max_entries = $max_column;
		}
		
		return $max_entries;
	}
	
	
	/**
	 * Adds functionality for authors to modify things at activation of plugin
	 * @since 1.1.0
	 */
	public static function activation_hooks($networkwide = false){
		//set all starting options
		$options = array();
		$options = apply_filters('essgrid_mod_activation_option', $options);
		if(function_exists('is_multisite') && is_multisite() && $networkwide){ //do for each existing site
			global $wpdb;
			
			$old_blog = $wpdb->blogid;
			
            // Get all blog ids and create tables
			$blogids = $wpdb->get_col("SELECT blog_id FROM ".$wpdb->blogs);
			
            foreach($blogids as $blog_id){
			
				switch_to_blog($blog_id);
				
				foreach($options as $opt => $val){
					update_option('tp_eg_'.$opt, $val);
				}
				
            }
			
            switch_to_blog($old_blog); //go back to correct blog
			
		}else{
		
			foreach($options as $opt => $val){
				update_option('tp_eg_'.$opt, $val);
			}
			
		}
		
	}
	
	/**
	 * Adds default Grids at installation process 
	 * @since 1.5.0
	 */
	public static function propagate_default_grids(){
		
		$default_grids = array();
		
		$default_grids = apply_filters('essgrid_add_default_grids', $default_grids);
		
		if(!empty($default_grids)){
			$im = new Essential_Grid_Import();
			$im->import_grids($default_grids);
		}
		
	}
	
	
	/**
	 * Does the uninstall process, also multisite checks
	 * @since 1.5.0
	 */
	public static function uninstall_plugin($networkwide = false){
		// If uninstall not called from WordPress, then exit
		if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
			exit;
		}
		
		global $wpdb;
		
		if(function_exists('is_multisite') && is_multisite() && $networkwide){ //do for each existing site
			global $wpdb;
			
			$old_blog = $wpdb->blogid;
			
            // Get all blog ids and create tables
			$blogids = $wpdb->get_col("SELECT blog_id FROM ".$wpdb->blogs);
			
            foreach($blogids as $blog_id){
			
				switch_to_blog($blog_id);
				self::_uninstall_plugin();
				
            }
			
            switch_to_blog($old_blog); //go back to correct blog
			
		}else{
			self::_uninstall_plugin();
		}
		
	}
	
	
	/**
	 * Does the uninstall process
	 * @since 1.5.0
	 * @moved from uninstall.php
	 */
	public static function _uninstall_plugin(){
		// If uninstall not called from WordPress, then exit
		if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
			exit;
		}
		
		global $wpdb;

		//Delete Database Tables
		$wpdb->query( "DROP TABLE ". $wpdb->prefix . Essential_Grid::TABLE_GRID);
		$wpdb->query( "DROP TABLE ". $wpdb->prefix . Essential_Grid::TABLE_ITEM_SKIN);
		$wpdb->query( "DROP TABLE ". $wpdb->prefix . Essential_Grid::TABLE_ITEM_ELEMENTS);
		$wpdb->query( "DROP TABLE ". $wpdb->prefix . Essential_Grid::TABLE_NAVIGATION_SKINS);

		//Delete Options
		delete_option('tp_eg_role');
		delete_option('tp_eg_grids_version');
		delete_option('tp_eg_custom_css');

		delete_option('tp_eg_output_protection');
		delete_option('tp_eg_tooltips');
		delete_option('tp_eg_wait_for_fonts');
		delete_option('tp_eg_js_to_footer');
		delete_option('tp_eg_use_cache');
		delete_option('tp_eg_query_type');
		delete_option('tp_eg_enable_log');

		delete_option('tp_eg_update-check');
		delete_option('tp_eg_update-check-short');
		delete_option('tp_eg_latest-version');
		delete_option('tp_eg_code');
		delete_option('tp_eg_username');
		delete_option('tp_eg_api-key');
		delete_option('tp_eg_valid');
		delete_option('tp_eg_valid-notice');

		delete_option('esg-widget-areas');
		delete_option('esg-custom-meta');
		delete_option('esg-custom-link-meta');
		
		delete_option('tp_eg_custom_css_imported');
		
	}
	
	
	/**
	 * Handle Ajax Requests
	 */
	public static function on_front_ajax_action(){
		$base = new Essential_Grid_Base();
		
		$token = $base->getPostVar("token", false);
		
		//verify the token
		$isVerified = wp_verify_nonce($token, 'Essential_Grid_Front');
		
		$error = false;
		if($isVerified){
			$data = $base->getPostVar('data', false);
			//client_action: load_more_items
			switch($base->getPostVar('client_action', false)){
				case 'load_more_items':
					$gridid = $base->getPostVar('gridid', 0, 'i');
					if(!empty($data) && $gridid > 0){
						$grid = new Essential_Grid();
						
						$result = $grid->init_by_id($gridid);
						if(!$result){
							$error = __('Grid not found', EG_TEXTDOMAIN);
						}else{
							$grid->set_loading_ids($data); //set to only load choosen items
							$html = false;
							//check if we are custom grid
							if($grid->is_custom_grid()){
								$html = $grid->output_by_specific_ids();
							}else{
								$html = $grid->output_by_specific_posts();
							}
							
							if($html !== false){
								self::ajaxResponseData($html);
							}else{
								$error = __('Items Not Found', EG_TEXTDOMAIN);
							}
						}
					}else{
						$error = __('No Data Received', EG_TEXTDOMAIN);
					}
				break;
				case 'load_more_content':
					$postid = $base->getPostVar('postid', 0, 'i');
					if($postid > 0){
						$raw_content = get_post_field('post_content', $postid);
						if(!is_wp_error($raw_content)){
							$content = apply_filters('the_content', $raw_content); //filter apply for qTranslate and other
							self::ajaxResponseData($content);
						}
					}
					$error = __('Post Not Found', EG_TEXTDOMAIN);
				break;
			}
			
		}else{
			$error = true;
		}
		
		if($error !== false){
			$showError = __('Loading Error', EG_TEXTDOMAIN);
			if($error !== true)
				$showError = __('Loading Error: ', EG_TEXTDOMAIN).$error;
			
			self::ajaxResponseError($showError, false);
		}
		exit();
	}
	
	
	
	/**
	 * echo json ajax response
	 */
	public static function ajaxResponse($success,$message,$arrData = null){
		
		$response = array();
		$response["success"] = $success;				
		$response["message"] = $message;

		if(!empty($arrData)){
			
			if(gettype($arrData) == "string" || gettype($arrData) == "boolean")
				$arrData = array("data"=>$arrData);				
			
			$response = array_merge($response,$arrData);
		}
			
		$json = json_encode($response);
		
		echo $json;
		exit();
	}

	
	/**
	 * echo json ajax response, without message, only data
	 */
	public static function ajaxResponseData($arrData){
		if(gettype($arrData) == "string")
			$arrData = array("data"=>$arrData);
		
		self::ajaxResponse(true,"",$arrData);
	}
	
	
	/**
	 * echo json ajax response
	 */
	public static function ajaxResponseError($message,$arrData = null){
		
		self::ajaxResponse(false,$message,$arrData,true);
	}
	
	
	/**
	 * echo ajax success response
	 */
	public static function ajaxResponseSuccess($message,$arrData = null){
		
		self::ajaxResponse(true,$message,$arrData,true);
		
	}
	
	
	/**
	 * echo ajax success response
	 */
	public static function ajaxResponseSuccessRedirect($message,$url){
		$arrData = array("is_redirect"=>true,"redirect_url"=>$url);
		
		self::ajaxResponse(true,$message,$arrData,true);
	}
	
}