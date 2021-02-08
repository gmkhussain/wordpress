# WordPress Child Theme v1.5



## How to install WordPress
### 1. Download WordPress from wordpress.org(wordpress.org)
### 2. Create <Project Folder> into htdocs on your XAMPP / Locahost
### 3. Exract WordPress.zip file
### 4. Create Database
	<img src="https://www.studentstutorial.com/img/phpmyadmin2.PNG" alt="" />
	
### Visit url ```http://localhost/projects/wordpress/wordpress-project-folder```
	It will redirect to http://localhost/projects/wordpress/p-admin/setup-config.php?step=0
	
### Click on ```Let's Go`` button
### Enter information needed
	<img src="https://wplang.org/wp-content/uploads/2014/06/Installation-Process.jpg" alt="" />











## How to Create Child Theme

### Click the create new folder, enter your child theme’s name and click Create.
For example folder name: ```twentynineteen-child```



### Create style.css with following code.

```css
/*
Theme Name: Twenty Nineteen Child
Theme URL: http://domain.com
Description: Twenty Nineteen Child Theme
Author: Amoos John Doe
Author URL: http://domain.com
Template: twentynineteen
Version: 1.0.0
Text Domain: twentynineteen-child
*/
```


### Create a blank functions.php file in the same folder
NOTE: but do not copy/paste the code from the parent theme’s file.


### From the WordPress admin area, navigate to Appearance -> Themes to see your newly created child theme and click Activate



### Open functions.php and add CSS JS

File: functions.php
```php
<?php
/**
 * Proper way to enqueue scripts and styles
 */
function wpdocs_theme_name_scripts() {
	
	wp_enqueue_style( 'main', get_stylesheet_directory_uri().'/src/css/main.css' );
    wp_enqueue_script( 'script-name', get_stylesheet_directory_uri() . '/src/js/main.js', array(), '1.0.0', true );
	/* true = it will add into footer */
	/* false = it will add into header */
}
add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );
?>
```


```wp_enqueue_script( 'script-name', get_stylesheet_directory_uri() . '/src/js/main.js', array(), '1.0.0', true );```

NOTE: Last parameter value if set as ```true``` it will add into footer, OR its set as ```false``` = it will add into header.






	
	
	






### Woocommerce add to cart button missing
```
This problem will solved it by simply filling out the needed information for the product (ID, SKU, Price, Weight, Dimensions)
```


```html
Working...
```


### Add Post Categories to the Body Class in WordPress
```html
add_filter('body_class','add_category_to_single');
function add_category_to_single($classes, $class) {
  if (is_single() ) {
    global $post;
    foreach((get_the_category($post->ID)) as $category) {
      // add category slug to the $classes array
      $classes[] = $category->category_nicename;
    }
  }
  // return the $classes array
  return $classes;
}
```

## Change site URL and WordPress URL in Localhost/Live Site
### NOTE: 
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




## Admin info
User: admin
Password: admin123


## ContactForm7 HTML with Bootstrap classes
```html
<div class="form-group col-sm-4 pad-l0">[text* first-name class:form-control placeholder "Name" ]</div>
<div class="form-group col-sm-4 pad-r0">[email* your-email class:form-control placeholder "Email"]</div>
<div class="form-group col-sm-12 pad0">[textarea your-message class:form-control ]</div>
<div class="form-group col-sm-12 pad0">[submit class:form-control "send"]</div>
```




## Remove the width and height attributes from WP image *Past in 'functions.php'
```html
add_filter( 'post_thumbnail_html', 'remove_width_attribute', 10 );
add_filter( 'image_send_to_editor', 'remove_width_attribute', 10 );
 
function remove_width_attribute( $html ) {
    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
}
```


## Posts_per_page with no limit
```html
$args = array(
'post_type'      => 'post',
'posts_per_page' => -1
);
```

## Remove All shortcode tags from content.
```html
<?php strip_shortcodes( get_the_content() ); ?>
```


##Truncated string with specified charters
```html
<?php echo mb_strimwidth(get_the_content(), 0, 150, '...'); ?>
```


## Contact form 7 Redirecting to Another URL After submissions
contact form 7 > 'Additional Settings'
just add this code.
```html
on_sent_ok: "location = 'http://mydomain.com/thank-you/';"
```

<p id="pwd4cf7"></p>
### Password Fields for ContactForm7
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

## Admin Styling
```html
//add this code in wp-admin/admin-header.php
<?php wp_enqueue_style( 'wordpress-style', get_stylesheet_directory_uri() . '/wordpress-style.css' ); ?>
```

## WooCommerce thumbnails images display in main image on mouse click
```html
	jQuery( ".thumbnails a" ).click(function() {
		imgUrl = jQuery(this).attr('data-href');
		imgBig = jQuery(".woocommerce-main-image img").attr('src', imgUrl);
	});
```


## Fixing Images and Broken Links by updating Paths in WordPress by SQL query
```html
UPDATE wp_posts SET post_content = REPLACE(post_content, 'http://localhost/projects/wordpress/myproject_wp/', 'http://mydomain.com/projects/myproject_wp/');
```

## Pagination for Custom Post Type
```html
			the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => __( '< Prev', 'textdomain' ),
				'next_text' => __( 'Next >', 'textdomain' ),
			));
```			
		


		
## Custom Post Type Category Link
<b>Issue:</b> diaply twice if assgined on 2 posts.
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





## Display categories of Custom Post Type?
<b>Note:</b> All category will not display until assign to any post.
```html
<?php
$taxonomy = 'my_products_categories';
$terms = get_terms($taxonomy); // Get all terms of a taxonomy

if ( $terms && !is_wp_error( $terms ) ) :
?>
    <ul>
        <?php foreach ( $terms as $term ) { ?>
            <li><a href="<?php echo get_term_link($term->slug, $taxonomy); ?>"><?php echo $term->name; ?></a></li>
        <?php } ?>
    </ul>
<?php endif;?>
```










## PHP Session for counting visits
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


## Checking User login status
```html
<?php
$current_user = wp_get_current_user();
if ( 0 == $current_user->ID ) {
    // Not logged in.
} else {
    // Logged in.
}
?>
```

## Post thumbnail url
```html
<?php echo the_post_thumbnail_url(full);?>

Example: <section class="page-bnr-area bg-cvr" style="background-image:url(<?php echo the_post_thumbnail_url(full);?>)";>
```


## Woocommerce shortcode for recent products 

```html
[recent_products per_page="12" columns="5"]
```


## How to edit Woocommerce fileds 
```html
class-wc-checkout.php
```


## Woocommerce Cart Item dropdown
			```html		 
					<li class="dropdown cart-menu"><a href="<?php echo wc_get_cart_url(); ?>"><i class="icon topicon2"><img src="<?php echo get_stylesheet_directory_uri();?>/images/cart-icon.png"></i></a> 
					
										
						<?php global $woocommerce; ?>
						
						<a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo $woocommerce->cart->get_cart_url(); ?>">Cart Items <span class="total">(<?php echo sprintf(_n('%d item', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?>)</span></a>

					  
					  <?php if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) : ?>
					  <ul class="dropdown-menu">
					  
										<?php $woocommerce->cart->cart_contents = array_reverse($woocommerce->cart->cart_contents); ?>
										<?php foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) :
											$_product = $values['data'];
											if ( $_product->exists() && $values['quantity'] > 0 ) :
												$product_quantity = esc_attr( $values['quantity'] );
												$product_price = (( get_option('woocommerce_display_cart_prices_excluding_tax') == 'yes' ) ? $_product->get_price_excluding_tax() : $_product->get_price()) * $product_quantity;
											?>
											<li>
												<span class="shop-bag-thumb">
													<a href="<?php echo esc_url( get_permalink( apply_filters('woocommerce_in_cart_product_id', $values['product_id'] ) ) ); ?>" class="clearfix">
														<?php echo $_product->get_image('zoom-thumb'); ?>
													</a>
												</span>

												<span class="details">
													<span class="title"><?php echo $_product->get_title(); ?></span>
													
													<span class="qty">Qty: <?php echo $product_quantity; ?></span>
													
													<?php echo apply_filters('woocommerce_cart_item_price_html', woocommerce_price( $product_price ), $values, $cart_item_key ); ?>

												<?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" title="%s">&times;</a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __('Remove from cart', 'woocommerce') ), $cart_item_key ); 
													?>
												</span>
												
											</li>
										<?php endif; endforeach; ?>
									
									<div class="subtotal">
										<span class="text">Subtotal</span><span class="total"><?php echo $woocommerce->cart->get_cart_total(); ?></span>
									</div>
									
									<a href="<?php echo home_url( '/' ); ?>shopping-bag/" class="go-to-checkout">Checkout</a>
								
								<?php endif; ?>			
	
					  </ul>
			
					</li>
```			
					
### How to Display Advanced Custom Fields Content
```html
<?php the_field('text_field'); ?>
```

## How to get Categories from Woocommerce
```html
			<?php

						  $taxonomy     = 'product_cat';
						  $orderby      = 'name';  
						  $show_count   = 0;      // 1 for yes, 0 for no
						  $pad_counts   = 0;      // 1 for yes, 0 for no
						  $hierarchical = 1;      // 1 for yes, 0 for no  
						  $title        = '';  
						  $empty        = 0;

						  $args = array(
								 'taxonomy'     => $taxonomy,
								 'orderby'      => $orderby,
								 'show_count'   => $show_count,
								 'pad_counts'   => $pad_counts,
								 'hierarchical' => $hierarchical,
								 'title_li'     => $title,
								 'hide_empty'   => $empty
						  );
						 $all_categories = get_categories( $args );
						 echo "<ul class='nav navbar-nav navbar-list'>";
						 foreach ($all_categories as $cat) {
							if($cat->category_parent == 0) {
								$category_id = $cat->term_id;       
								echo '<li><a href="'. get_term_link($cat->slug, 'product_cat') .'">'. $cat->name .'</a></li>';

								$args2 = array(
										'taxonomy'     => $taxonomy,
										'child_of'     => 0,
										'parent'       => $category_id,
										'orderby'      => $orderby,
										'show_count'   => $show_count,
										'pad_counts'   => $pad_counts,
										'hierarchical' => $hierarchical,
										'title_li'     => $title,
										'hide_empty'   => $empty
								);
								$sub_cats = get_categories( $args2 );
								
									echo "<ul>";
										if($sub_cats) {
											foreach($sub_cats as $sub_category) {
												echo '<li><a href="'. get_term_link($sub_category->slug, 'product_cat') .'">'. $sub_category->name .'</a></li>';
											}   
										}
									echo "</ul>";
							}       
						}
						echo "</ul>";
			?>
```








## Overriding WooCommerce files with a Child Theme

After you activate your child theme, visit your site's front page. Everything should look exactly the same. All you have done so far is basically made a pointer from the child to the parent theme.

**Step 1:** Create woocommerce directory.

Now in your child theme directory create a new folder named "woocommerce". This folder will be responsible for storing customized WooCommerce template files.

You should now have: /wp-content/themes/twentytwelve-child/woocommerce/

**Step 2:** Add files which you want to customize

You can find a list of all WooCommerce template files here: /wp-content/plugins/woocommerce/templates/

In this example, I want to customize this file: /wp-content/plugins/woocommerce/templates/single-product/add-to-cart/external.php

So I need to create that directory path and add that file in my child folder so that I have this:

/wp-content/themes/twentytwelve-child/woocommerce/single-product/add-to-cart/external.php

**Step 3:** Modify the external.php file

Now we are ready to make our file edits. Open /wp-content/themes/twentytwelve-child/woocommerce/single-product/add-to-cart/external.php in your file editor and change this line:

```html
	<a href="<?php echo esc_url( $product_url ); ?>" rel="nofollow" class="single_add_to_cart_button button alt"><?php echo $button_text; ?></a>
```
	
To this:

```html
	<a target="_blank" href="<?php echo esc_url( $product_url ); ?>" rel="nofollow" class="single_add_to_cart_button button alt"><?php echo $button_text; ?></a>
```
	
Save the file.

Now your 'buy' buttons will open in a new window.

<hr>

### How to override WooCommerce template files ? ( Short Note )

To override the shop page, 
copy: wp-content/plugins/woocommerce/templates/archive-product.php 
to wp-content/themes/your_theme_name/woocommerce/archive-product.php

and then make the necessary changes to the template in your themes folder.


### How to get WooCommerce product author's name
<b>Note:</b> 
* Install 'Change Product Author for WooCommerce'
* https://wordpress.org/plugins/woo-change-product-author/

```javascript
add_action( 'woocommerce_single_product_summary', 'woocommerce_product_author', 6);
function woocommerce_product_author() {
    the_author();
}
```







## WordPress asking localhost FTP credentials when install plugins on Ubuntu

Open ```wp-config.php``` and add following code.

```javascript
define('FS_METHOD', 'direct');
```




## How to get and display ACF ( Advanced Custom Fields ) field_key

```javascript
//Admin URL
wp-admin/edit.php?post_type=acf&page=acf-export
```


```javascript
//Display Values
<?php 

/*
*  Get a field object and display it with it's value
*/

$field_name = "text_field";
$field = get_field_object($field_name);

echo $field['label'] . ': ' . $field['value'];

/*
*  Get a field object and display it with it's value (using the field key and the value fron another post)
*/

$field_key = "field_5039a99716d1d";
$post_id = 123;
$field = get_field_object($field_key, $post_id);

echo $field['label'] . ': ' . $field['value'];

/*
*  Get a field object and create a select form element
*/

$field_key = "field_5039a99716d1d";
$field = get_field_object($field_key);

if( $field )
{
	echo '<select name="' . $field['key'] . '">';
		foreach( $field['choices'] as $k => $v )
		{
			echo '<option value="' . $k . '">' . $v . '</option>';
		}
	echo '</select>';
}

?>
```




## After 10 posts didn't display any post I am using WP_Query on custom post type

```javascript
// wp-admin/options-reading.php
```

<b>Note:</b> Try on wordpress <kbd>admin > settings > reading ></kbd> and upload the max of posts from 10 to any number.

OR

use <kbd>posts_per_page => -1</kbd> in WP Query parameter.







## Defer Parsing of JavaScript in WordPress

This code add to the bottom of your theme’s functions.php

```php
function defer_parsing_of_js ( $url ) {
    if ( FALSE === strpos( $url, '.js' ) ) return $url;
    if ( strpos( $url, 'jquery.js' ) ) return $url;
    return '$url" defer';
    }
    add_filter( 'clean_url', 'defer_parsing_of_js', 11, 1 );
```




## load script or style for specific page in wordpress

```js
<?php

function pid() {
  global $current_screen;
  $type = $current_screen->post_type;
    ?>
    <script type="text/javascript">
        var page_id = '<?php global $post; echo $post->ID ?>';
            console.log(page_id);
    </script>
    <?php
    
    if ( is_page(21880) ) { // find-certified-providers
        wp_enqueue_script('new-page-js', get_template_directory_uri() . '/js/new-page.js', array( 'jquery' ),'1.1' , true );
    }
} 
add_action('wp_head','pid');

```











## Custom Hook directly after specific ID or tag in WordPress

Creating custom hook in page.php (or anywhere you may need a hook) locate:

```html
<body <?php body_class(); ?>>
  <main id="main_content">
    <?php body_main_begin(); ?>
```


Hook add into functions.php

```js
function body_main_begin() {
  do_action('body_main_begin');
}
```

Now you can use by add any actions you need in functions.php:

```js
function my_function() {
  /* php code goes here */
  get_template_part('inc/breadcrumbs' );
}
add_action('body_main_begin', 'my_function');
```














<h1 align="center" id="Errors">
	<img src="https://cdn.iconscout.com/icon/free/png-256/warning-272-830593.png"  height="80" width="auto" />
	<br/>
	Issues / Errors / Mistakes
</h1>


## Missing Admin Bar Issue in WordPress

Make sure <?php wp_footer(); ?> line added your theme in theme’s ```footer.php``` file just before the </body> tag.

NOTE: If still not works... turn the debugging on by opening ```wp-config.php``` file and changing ```define('WP_DEBUG', false);``` to ```define('WP_DEBUG', true);```. WordPress will now show you warnings and notices that were previously hidden.



Another solution to resolve that problem just add the following code into your ```function.php``` or into your own plugin:
```javascript
function admin_bar(){

  if(is_user_logged_in()){
    add_filter( 'show_admin_bar', '__return_true' , 1000 );
  }
}
add_action('init', 'admin_bar' );
```








## How to fetch posts from post type using WP REST API

Update your wordpress version incase not updated. This code for v2 of the REST API plugin

Add the following code to create a rest endpoint in the functions.php file of your theme:

```php
add_action( 'init', 'add_myCustomPostType_endpoint');
function add_myCustomPostType_endpoint(){
    global $wp_post_types;
    $wp_post_types['my_custome_post_type']->show_in_rest = true;
    $wp_post_types['my_custome_post_type']->rest_base = 'my_custome_post_type';
    $wp_post_types['my_custome_post_type']->rest_controller_class = 'WP_REST_Posts_Controller';
}
```


Now visit URL:
http://localhost/projects/your-project-name/wp-json/wp/v2/my_custome_post_type








