<?php 

function my_theme_styles() {
	/*Header File
    wp_enqueue_style( 'style', get_stylesheet_directory_uri() . '/style.css' );
	wp_enqueue_style( 'bootstrapmin', get_stylesheet_directory_uri() . '/css/bootstrap.min.css');
	wp_enqueue_style( 'animate', get_stylesheet_directory_uri() . '/css/animate.css' );
	wp_enqueue_style( 'fontawesome', get_stylesheet_directory_uri() . '/css/font-awesome.min.css' );
	wp_enqueue_style( 'slidenav', get_stylesheet_directory_uri() . '/css/slidenav.css' );
	wp_enqueue_style( 'colorized', get_stylesheet_directory_uri() . '/css/colorized.css' );*/
	

	/*Footer Files
	wp_enqueue_script( 'boostrapjs', get_stylesheet_directory_uri() . '/js/bootstrap.min.js', array(), '1.0.0', true );
	wp_enqueue_script( 'animateme', get_stylesheet_directory_uri() . '/js/kodeized.js', array(), '1.0.0', true );
	wp_enqueue_script( 'viewportchecker', get_stylesheet_directory_uri() . '/js/viewportchecker.js', array(), '1.0.0', true );*/
}
add_action( 'wp_enqueue_scripts', 'my_theme_styles' );

function theme_prefix_setup() {
    add_theme_support( 'custom-logo' );
}
add_action( 'after_setup_theme', 'theme_prefix_setup' );








/****my_slider_args category in posttype***/
$my_slider_args = array(
    'labels' => array(
    'name' => 'my_slider',
    'singular_name' => 'my_slider'),
    'description' => 'Allows you to build custom my_slider items and link them to categories',
	'menu_icon' => 'dashicons-images-alt2',
    'public' => true,
    'show_ui' => true,
    'menu_position' => 20,
    'supports' => array('title', 'editor', 'thumbnail'),
    'has_archive' => true,
    'rewrite' => array('slug' => 'my_slider'),
    'can_export' => true
);

/* http://codex.wordpress.org/Function_Reference/register_post_type */
register_post_type('my_slider', $my_slider_args);

$categories_labels = array(
    'label' => 'Categories',
    'hierarchical' => true,
    'query_var' => true
);

/*  Register taxonomies for extra post type capabilities */
register_taxonomy('my_slider_categories', 'my_slider', $categories_labels);
/****./my_slider_args in posttype***/





















function the_breadcrumb() {
		echo '<ul id="crumbs">';
	if (!is_home()) {
		echo '<li><a href="';
		echo get_option('home');
		echo '">';
		echo 'Home';
		echo "</a></li>";
		if (is_category() || is_single()) {
			echo '<li>';
			the_category(' </li><li> ');
			if (is_single()) {
				echo "</li><li>";
				the_title();
				echo '</li>';
			}
		} elseif (is_page()) {
			echo '<li>';
			echo the_title();
			echo '</li>';
		}
	}
	elseif (is_tag()) {single_tag_title();}
	elseif (is_day()) {echo"<li>Archive for "; the_time('F jS, Y'); echo'</li>';}
	elseif (is_month()) {echo"<li>Archive for "; the_time('F, Y'); echo'</li>';}
	elseif (is_year()) {echo"<li>Archive for "; the_time('Y'); echo'</li>';}
	elseif (is_author()) {echo"<li>Author Archive"; echo'</li>';}
	elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {echo "<li>Blog Archives"; echo'</li>';}
	elseif (is_search()) {echo"<li>Search Results"; echo'</li>';}
	echo '</ul>';
}







	register_sidebar( array(
		'name'          => __( 'footerlinks', 'twentysixteen' ),
		'id'            => 'footerlinks',
		'description'   => __( 'appean on bottom.', 'twentysixteen' ),
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => '',
	) );


	register_sidebar( array(
		'name'          => __( 'bottom__copyright', 'twentysixteen' ),
		'id'            => 'bottom__copyright',
		'description'   => __( 'appean on bottom.', 'twentysixteen' ),
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => '',
	) );

		
	register_sidebar( array(
		'name'          => __( 'footer__contactinfo', 'twentysixteen' ),
		'id'            => 'footer__contactinfo',
		'description'   => __( 'appean on bottom.', 'twentysixteen' ),
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => '',
	) );



	
add_filter('gettext', 'change_howdy', 10, 3); 
function change_howdy($translations, $untranslated_text, $domain)
{
    if (!is_admin() || 'default' != $domain) {
        return $translations;
    }
    if (false !== strpos($translations, 'Howdy')) {
        $translations = str_replace('Howdy', 'Hello', $translations);
    }
    return $translations;
}


?>