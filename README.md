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


<hr/>
<img src="https://mir-s3-cdn-cf.behance.net/project_modules/disp/dd563b20465955.562fed481f5b4.gif" />
<br/>
[<img src="https://cdn1.iconfinder.com/data/icons/logotypes/32/twitter-128.png" width="auto" height="32" /> Coded by @GMKHussain](http://twitter.com/gmkhussain)
<hr/>