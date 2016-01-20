<?php 

function hussain_theme_styles() {
	//Header File
    wp_enqueue_style( 'a22', get_stylesheet_directory_uri() . '/style.css' );
	wp_enqueue_style( 'bootstrapmin', get_stylesheet_directory_uri() . '/css/bootstrap.min.css');
	wp_enqueue_style( 'animate', get_stylesheet_directory_uri() . '/css/animate.css' );
	wp_enqueue_style( 'fontawesome', get_stylesheet_directory_uri() . '/css/font-awesome.css' );
	wp_enqueue_style( 'slidenav', get_stylesheet_directory_uri() . '/css/slidenav.css' );

	
	//Footer Files	wp_enqueue_script( 'boostrapjs', get_stylesheet_directory_uri() . '/js/bootstrap.min.js', array(), '1.0.0', true );
	wp_enqueue_script( 'animateme', get_stylesheet_directory_uri() . '/js/kscript.js', array(), '1.0.0', true );
	wp_enqueue_script( 'viewportchecker', get_stylesheet_directory_uri() . '/js/viewportchecker.js', array(), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'hussain_theme_styles' );






/******slider in dashboard***********/
function my_custom_sliders_posttype(){
   $args = array(
   'labels'=> array( 'name'=>'sliders',
       'singular_name'=> 'slider',
       'menu_name'=>'sliders',
       'name_admin_bar'=> 'sliders',
       'all_items' =>'View all sliders',
       'add_new'=> 'Add New sliders' ),
   'description' =>"This post type is for sliders",
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
  register_post_type( "sliders", $args );
 }
 add_action("init","my_custom_sliders_posttype");
/******./slider in dashboard***********/








/******args_products_posttype***********/
function args_products_posttype(){
   $args_products = array(
   'labels'=> array( 'name'=>'products',
       'singular_name'=> 'products',
       'menu_name'=>'products',
       'name_admin_bar'=> 'products',
       'all_items' =>'View all products',
       'add_new'=> 'Add New product' ),
   'description' =>"This post type is for products",
   'public' => true,
   'exclude_from_search'=>false,
   'publicly_queryable'=> true,
   'show_ui' => true,
   'show_in_menu'=> true,
   'show_in_admin_bar'=> true,
   'menu_position'=>6,
      'menu_icon' => get_stylesheet_directory_uri().'/images/dash-products.png',
   'capability_type'=> 'page',
   'supports'=> array( 'title', 'editor', 'author', 'thumbnail', 'excerpt',
    ),
   'query_var'=>true,
  );
  register_post_type( "products", $args_products );
 }
 add_action("init","args_products_posttype");
/******./args_products_posttype**********/









/******logos_posttype***********/
function args_favilogos_posttype(){
   $args_products = array(
   'labels'=> array( 'name'=>'favilogos',
       'singular_name'=> 'favilogos',
       'menu_name'=>'favilogos',
       'name_admin_bar'=> 'favilogos',
       'all_items' =>'View all favilogos',
       'add_new'=> 'Add New product' ),
   'description' =>"This post type is for favilogos",
   'public' => true,
   'exclude_from_search'=>false,
   'publicly_queryable'=> true,
   'show_ui' => true,
   'show_in_menu'=> true,
   'show_in_admin_bar'=> true,
   'menu_position'=>6,
   'capability_type'=> 'page',
   'menu_icon' => get_stylesheet_directory_uri().'/images/dash-logos.png',
   'supports'=> array( 'title', 'editor', 'author', 'thumbnail', 'excerpt',
    ),
   'query_var'=>true,
  );
  register_post_type( "favilogos", $args_products );
 }
 add_action("init","args_favilogos_posttype");
/******./args_products_posttype**********/








/******args_gallery_posttype***********/
function args_gallery_posttype(){
   $args_products = array(
   'labels'=> array( 'name'=>'gallery',
       'singular_name'=> 'gallery',
       'menu_name'=>'gallery',
       'name_admin_bar'=> 'gallery',
       'all_items' =>'View all gallery',
       'add_new'=> 'Add New product' ),
   'description' =>"This post type is for gallery",
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





register_sidebar( array(
		'name'          => __( 'footerlinks', 'twentyfifteen' ),
		'id'            => 'footerlinks',
		'description'   => __( 'appean on bottom.', 'twentyfifteen' ),
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => '',
	) );

register_sidebar( array(
		'name'          => __( 'footerfriends', 'twentyfifteen' ),
		'id'            => 'footerfriends',
		'description'   => __( 'appean on bottom.', 'twentyfifteen' ),
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => '',
	) );

register_sidebar( array(
		'name'          => __( 'footersocial', 'twentyfifteen' ),
		'id'            => 'footersocial',
		'description'   => __( 'appean on bottom.', 'twentyfifteen' ),
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => '',
	) );


/*


register_sidebar( array(
		'name'          => __( 'topheader', 'twentyfifteen' ),
		'id'            => 'telephone',
		'description'   => __( 'appean on top.', 'twentyfifteen' ),
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => '',
	) );
	
	
	
	//FOoter
register_sidebar( array(
'name' => 'Footer Sidebar 1',
'id' => 'footer-sidebar-1',
'description' => 'Appears in the footer area',
'before_widget' => '<div class="ftr-box col-sm-3 clrlist listview">',
'after_widget' => '</div>',
'before_title' => '<h4>',
'after_title' => '</h4>',
) );

register_sidebar( array(
'name' => 'Footer Sidebar 2',
'id' => 'footer-sidebar-2',
'description' => 'Appears in the footer area',
'before_widget' => '<div class="ftr-box col-sm-3 clrlist listview">',
'after_widget' => '</div>',
'before_title' => '<h4>',
'after_title' => '</h4>',
) );

register_sidebar( array(
'name' => 'Footer Sidebar 3',
'id' => 'footer-sidebar-3',
'description' => 'Appears in the footer area',
'before_widget' => '<div class="ftr-box col-sm-3 clrlist listview">',
'after_widget' => '</div>',
'before_title' => '<h4>',
'after_title' => '</h4>',
) );

register_sidebar( array(
'name' => 'Footer Sidebar 4',
'id' => 'footer-sidebar-4',
'description' => 'Appears in the footer area',
'before_widget' => '<div class="ftr-box col-sm-3 clrlist listview">',
'after_widget' => '</div>',
'before_title' => '<h4>',
'after_title' => '</h4>',
) );



register_sidebar( array(
'name' => 'bottom 1',
'id' => 'bottom-1',
'description' => 'Appears in the footer area',
'before_widget' => '<div class="cr pull-lft">',
'after_widget' => '</div>',
'before_title' => '',
'after_title' => '',
) );


register_sidebar( array(
'name' => 'bottom 2',
'id' => 'bottom-2',
'description' => 'Appears in the footer area',
'before_widget' => '<div class="cr pull-rgt">',
'after_widget' => '</div>',
'before_title' => '',
'after_title' => '',
) );
*/