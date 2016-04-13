# WordPress Child Theme v0.1
For [Backend](#Backed) 


Additional file list:
* [kscript.js](#kscript.js)
* viewportchecker.js
* css_browser_selector.js
* swiper.jquery.min.js


## Installing
### 1. Create Database
### 2. Active child-theme
### 3. Create Menu 'main_menu'
### 4. Make defult HOME page
### 5. wp-admin/options-reading.php > "Front page displays" > A static page (select below) > [Save changes]

```html
Working...
```



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


##Contact form 7 Redirecting to Another URL After submissions
contact form 7 > 'Additional Settings'
just add this code.
```html
on_sent_ok: "location = 'http://mydomain.com/thank-you/';"
```


<hr/>
<img src="https://mir-s3-cdn-cf.behance.net/project_modules/disp/dd563b20465955.562fed481f5b4.gif" />
<br/>
[<img src="https://cdn1.iconfinder.com/data/icons/logotypes/32/twitter-128.png" width="auto" height="32" /> Coded by @GMKHussain](http://twitter.com/gmkhussain)
<hr/>
