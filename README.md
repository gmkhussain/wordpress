# WordPress Child Theme v0.1
For [Backend](#Backed) 


Additional file list:
* [kscript.js](#kscript.js)
* viewportchecker.js
* css_browser_selector.js
* swiper.jquery.min.js


## Installing
### 1. Download WordPress from wordpress.org
### 2. Create <Project Folder> into htdocs on your XAMPP / Locahost
### 3. Exract WordPress.zip file
### 4. Create Database
### 5. Run SQL code / import SQL file in PHPMyAdmin
### 6. Change URLs in wp_options
#### a) site url
#### b) home
### 7. Rename wp_content with "wp_content_old"
### 8. Paste WP Package / Get Git Clone * Replace existing files.
### 9. Config Database in wp_config.php
### 10. Login into wp-admin with U: admin , P: admin123


```html
Working...
```


##Change site URL and WordPress URL in Localhost/Live Site
###NOTE: 
Steps  | Actions
------------- | -------------
1. | Goto phpMyAdmin
2. | Click on your Database
3. | Click on SQL
4. | Run SQL query as per your needs


```html
UPDATE wp_options
SET option_value = 'http://new-domain-name.com'
WHERE option_name = 'home';

UPDATE wp_options
SET option_value = 'http://new-domain-name.com'
WHERE option_name = 'siteurl';

UPDATE wp_posts
SET post_content = REPLACE(post_content,'http://old-domain-name.com','http://new-domain-name.com');

UPDATE wp_posts
SET guid = REPLACE(guid,'http://old-domain-name.com','http://new-domain-name.com');


//Change WP Options URLs
UPDATE wp_options
SET option_value = REPLACE(option_value,'http://localhost/projects/_wpbasic','http://localhost/projects/wordpress/NEW_Project_Name/');

```




##Admin info
User: admin
Password: admin123


##ContactForm7 HTML with Bootstrap classes
```html
<div class="form-group col-sm-4 pad-l0">[text* first-name class:form-control placeholder "Name" ]</div>
<div class="form-group col-sm-4 pad-r0">[email* your-email class:form-control placeholder "Email"]</div>
<div class="form-group col-sm-12 pad0">[textarea your-message class:form-control ]</div>
<div class="form-group col-sm-12 pad0">[submit class:form-control "send"]</div>
```




##Remove the width and height attributes from WP image *Past in 'functions.php'
```html
add_filter( 'post_thumbnail_html', 'remove_width_attribute', 10 );
add_filter( 'image_send_to_editor', 'remove_width_attribute', 10 );
 
function remove_width_attribute( $html ) {
    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
}
```


##Posts_per_page with no limit
```html
$args = array(
'post_type'      => 'post',
'posts_per_page' => -1
);
```

##Remove All shortcode tags from content.
```html
<?php strip_shortcodes( get_the_content() ); ?>
```


##Truncated string with specified charters
```html
<?php echo mb_strimwidth(get_the_content(), 0, 150, '...'); ?>
```


##Contact form 7 Redirecting to Another URL After submissions
contact form 7 > 'Additional Settings'
just add this code.
```html
on_sent_ok: "location = 'http://mydomain.com/thank-you/';"
```

<p id="pwd4cf7"></p>
###Password Fields for ContactForm7
```html
//HTML code
<input type="text" name="your-password" class="password"  value="daddd">

//ContactForm7 Shortcode
[text password]
 or 
[text your-password]
 or 
[text my-password]


//Add this jQuery code in footer file. * Tested is working.
jQuery("[name*='password']").attr("type", "password");
```

##Admin Styling
```html
//add this code in wp-admin/admin-header.php
<?php wp_enqueue_style( 'wordpress-style', get_stylesheet_directory_uri() . '/wordpress-style.css' ); ?>
```

##WooCommerce thumbnails images display in main image on mouse click
```html
	jQuery( ".thumbnails a" ).click(function() {
		imgUrl = jQuery(this).attr('data-href');
		imgBig = jQuery(".woocommerce-main-image img").attr('src', imgUrl);
	});
```


##Fixing Images and Broken Links by updating Paths in WordPress by SQL query
```html
UPDATE wp_posts SET post_content = REPLACE(post_content, 'http://localhost/projects/wordpress/myproject_wp/', 'http://mydomain.com/projects/myproject_wp/');
```

##Pagination for Custom Post Type
```html
			the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => __( '< Prev', 'textdomain' ),
				'next_text' => __( 'Next >', 'textdomain' ),
			));
```			
		
		
##Custom Post Type Category Link
```html
<?php
	query_posts( array( 'post_type' => 'myblogs', 'order' => 'ASC', 'myblogs_categories' => 'articles' ) );
	 /*the loop start here*/
	  if ( have_posts() ) : while ( have_posts() ) : the_post();
?>
			
	<?php 
		$terms = get_the_terms( $post->ID, 'myblogs_categories' ); 
		foreach($terms as $term) {
	?>
						
	  <a href="<?php echo get_term_link($term->slug, 'myblogs_categories') ?>">Show me more <?php echo $term->name; ?></a>
				  
	<?php } ?>

<?php endwhile; endif; wp_reset_query(); ?>
```



##PHP Session for counting visits
```html
<?php
session_start();

if (!isset($_SESSION['views'])) { 
    $_SESSION['views'] = 0;
}

$_SESSION['views'] = $_SESSION['views']+1;

if ($_SESSION['views'] == 1) {
    /***DO SOMETHING***/
}
?>
```

<hr/>
<img src="https://mir-s3-cdn-cf.behance.net/project_modules/disp/dd563b20465955.562fed481f5b4.gif" />
<br/>
[<img src="https://cdn1.iconfinder.com/data/icons/logotypes/32/twitter-128.png" width="auto" height="32" /> Coded by @GMKHussain](http://twitter.com/gmkhussain)
<hr/>