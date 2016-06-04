<?php 
function hussain_theme_styles() {	/*Header File*/    wp_enqueue_style( 'a22', get_stylesheet_directory_uri() . '/style.css' );	wp_enqueue_style( 'bootstrapmin', get_stylesheet_directory_uri() . '/css/bootstrap.min.css');	wp_enqueue_style( 'animate', get_stylesheet_directory_uri() . '/css/animate.css' );	wp_enqueue_style( 'fontawesome', get_stylesheet_directory_uri() . '/css/font-awesome.css' );	wp_enqueue_style( 'slidenav', get_stylesheet_directory_uri() . '/css/slidenav.css' );	
	/*Footer Files*/	wp_enqueue_script( 'boostrapjs', get_stylesheet_directory_uri() . '/js/bootstrap.min.js', array(), '1.0.0', true );	wp_enqueue_script( 'animateme', get_stylesheet_directory_uri() . '/js/kscript.js', array(), '1.0.0', true );	wp_enqueue_script( 'viewportchecker', get_stylesheet_directory_uri() . '/js/viewportchecker.js', array(), '1.0.0', true );}add_action( 'wp_enqueue_scripts', 'hussain_theme_styles' );

/***logos_posttype***/function args_favilogos_posttype(){   $args_products = array(   'labels'=> array( 'name'=>'favilogos',       'singular_name'=> 'favilogos',       'menu_name'=>'Icons/Logos',       'name_admin_bar'=> 'favilogos',       'all_items' =>'View all favilogos',       'add_new'=> 'Add New product' ),   'description' =>"This post type is for favilogos",   'public' => true,   'exclude_from_search'=>false,   'publicly_queryable'=> true,   'show_ui' => true,   'show_in_menu'=> true,   'show_in_admin_bar'=> true,   'menu_position'=>6,   'capability_type'=> 'page',   'menu_icon' => 'dashicons-heart',   'supports'=> array( 'title', 'editor', 'author', 'thumbnail', 'excerpt',    ),   'query_var'=>true,  );  register_post_type( "favilogos", $args_products ); } add_action("init","args_favilogos_posttype");/****./args_products_posttype***/


/******slider in dashboard***********/function my_custom_sliders_posttype(){
   $args = array(
   'labels'=> array( 'name'=>'sliders',
       'singular_name'=> 'slider',
       'menu_name'=>'Sliders',
       'name_admin_bar'=> 'sliders',
       'all_items' =>'View all sliders',
       'add_new'=> 'Add New sliders' ),
   'description' =>"This post type is for sliders",
   'public' => true,
   'exclude_from_search'=>false,
   'publicly_queryable'=> true,
   'show_ui' => true,
   'show_in_menu'=> true,
   'show_in_admin_bar'=> true,		'menu_icon' => 'dashicons-images-alt2',	
   'menu_position'=>6,
   'capability_type'=> 'page',
   'supports'=> array( 'title', 'editor', 'author', 'thumbnail', 'excerpt',
    ),
   'query_var'=>true,
  );
  register_post_type( "sliders", $args );
 }
 add_action("init","my_custom_sliders_posttype");
/******./slider in dashboard***********/








/******args_products_posttype***********/
function args_products_posttype(){
   $args_products = array(
   'labels'=> array( 'name'=>'products',
       'singular_name'=> 'products',
       'menu_name'=>'Products',
       'name_admin_bar'=> 'products',
       'all_items' =>'View all products',
       'add_new'=> 'Add New product' ),
   'description' =>"This post type is for products",
   'public' => true,
   'exclude_from_search'=>false,
   'publicly_queryable'=> true,
   'show_ui' => true,
   'show_in_menu'=> true,
   'show_in_admin_bar'=> true,      'menu_icon' => 'dashicons-products',

   'show_in_admin_bar'=> true,      'menu_icon' => 'dashicons-products',	//'menu_icon' => get_stylesheet_directory_uri().'/images/dash-products.png',

   'menu_position'=>6,
   'capability_type'=> 'page',
   'supports'=> array( 'title', 'editor', 'author', 'thumbnail', 'excerpt',
    ),
   'query_var'=>true,
  );
  register_post_type( "products", $args_products );
 }
 add_action("init","args_products_posttype");
/******./args_products_posttype**********/





/****ourwork_args category in posttype***/$ourwork_args = array(    'labels' => array(    'name' => 'ourwork Items',    'singular_name' => 'ourwork Item'),    'description' => 'Allows you to build custom ourwork items and link them to categories',	'menu_icon' => 'dashicons-schedule',    'public' => true,    'show_ui' => true,    'menu_position' => 20,    'supports' => array('title', 'editor', 'thumbnail'),    'has_archive' => true,    'rewrite' => array('slug' => 'ourwork-item'),    'can_export' => true);/* http://codex.wordpress.org/Function_Reference/register_post_type*/register_post_type('ourwork', $ourwork_args);$categories_labels = array(    'label' => 'Categories',    'hierarchical' => true,    'query_var' => true);/*  Register taxonomies for extra post type capabilities */register_taxonomy('ourwork_categories', 'ourwork', $categories_labels);/****./ourwork_args category in posttype***//*****portfolio_args category in posttype***/$portfolio_args = array(    'labels' => array(    'name' => 'Portfolio Items',    'singular_name' => 'Portfolio Item'),    'description' => 'Allows you to build custom portfolio items and link them to categories',	'menu_icon' => 'dashicons-awards',    'public' => true,    'show_ui' => true,    'menu_position' => 20,    'supports' => array('title', 'editor', 'thumbnail'),    'has_archive' => true,    'rewrite' => array('slug' => 'portfolio-item'),    'can_export' => true);/*  http://codex.wordpress.org/Function_Reference/register_post_type */ register_post_type('portfolio', $portfolio_args);$categories_labels = array(    'label' => 'Categories',    'hierarchical' => true,    'query_var' => true);/* Register taxonomies for extra post type capabilities */ register_taxonomy('portfolio_categories', 'portfolio', $categories_labels);/****./portfolio_args category in posttype ***/

/****ourwork_args category in posttype***/$ourwork_args = array(    'labels' => array(    'name' => 'ourwork Items',    'singular_name' => 'ourwork Item'),    'description' => 'Allows you to build custom ourwork items and link them to categories',	'menu_icon' => 'dashicons-schedule',    'public' => true,    'show_ui' => true,    'menu_position' => 20,    'supports' => array('title', 'editor', 'thumbnail'),    'has_archive' => true,    'rewrite' => array('slug' => 'ourwork-item'),    'can_export' => true);// http://codex.wordpress.org/Function_Reference/register_post_typeregister_post_type('ourwork', $ourwork_args);$categories_labels = array(    'label' => 'Categories',    'hierarchical' => true,    'query_var' => true);// Register taxonomies for extra post type capabilitiesregister_taxonomy('ourwork_categories', 'ourwork', $categories_labels);/****./ourwork_args category in posttype***//*****portfolio_args category in posttype***/$portfolio_args = array(    'labels' => array(    'name' => 'Portfolio Items',    'singular_name' => 'Portfolio Item'),    'description' => 'Allows you to build custom portfolio items and link them to categories',	'menu_icon' => 'dashicons-awards',    'public' => true,    'show_ui' => true,    'menu_position' => 20,    'supports' => array('title', 'editor', 'thumbnail'),    'has_archive' => true,    'rewrite' => array('slug' => 'portfolio-item'),    'can_export' => true);// http://codex.wordpress.org/Function_Reference/register_post_typeregister_post_type('portfolio', $portfolio_args);$categories_labels = array(    'label' => 'Categories',    'hierarchical' => true,    'query_var' => true);// Register taxonomies for extra post type capabilitiesregister_taxonomy('portfolio_categories', 'portfolio', $categories_labels);/****./portfolio_args category in posttype ***/









/******args_gallery_posttype***********/
function args_gallery_posttype(){
   $args_products = array(
   'labels'=> array( 'name'=>'gallery',
       'singular_name'=> 'gallery',
       'menu_name'=>'gallery',
       'name_admin_bar'=> 'gallery',
       'all_items' =>'View all gallery',
       'add_new'=> 'Add New product' ),
   'description' =>"This post type is for gallery",		'menu_icon' => 'dashicons-format-gallery',	
   'public' => true,
   'exclude_from_search'=>false,
   'publicly_queryable'=> true,
   'show_ui' => true,
   'show_in_menu'=> true,
   'show_in_admin_bar'=> true,
   'menu_position'=>6,
   'capability_type'=> 'page',
   'supports'=> array( 'title', 'editor', 'author', 'thumbnail', 'excerpt',
    ),
   'query_var'=>true,
  );
  register_post_type( "gallery", $args_products );
 }
 add_action("init","args_gallery_posttype");
/******./args_products_posttype**********/



	register_sidebar( array(		'name'          => __( 'footerlinks', 'twentyfifteen' ),		'id'            => 'footerlinks',		'description'   => __( 'appean on bottom.', 'twentyfifteen' ),		'before_widget' => '',		'after_widget'  => '',		'before_title'  => '',		'after_title'   => '',	) );
						register_sidebar( array(		'name'          => __( 'footerfriends', 'twentyfifteen' ),		'id'            => 'footerfriends',		'description'   => __( 'appean on bottom.', 'twentyfifteen' ),		'before_widget' => '',		'after_widget'  => '',		'before_title'  => '',		'after_title'   => '',	) );

					register_sidebar( array(		'name'          => __( 'Social Icons', 'twentyfifteen' ),		'id'            => 'social_icons',		'description'   => __( 'appean on bottom.', 'twentyfifteen' ),		'before_widget' => '',		'after_widget'  => '',		'before_title'  => '',		'after_title'   => '',	) );		include("metabox_fields.php");?>
