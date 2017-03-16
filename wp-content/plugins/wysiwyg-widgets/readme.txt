=== Plugin Name ===
Contributors: DvanKooten
Donate link: https://dannyvankooten.com/donate/
Tags: widget,visual editor,image widget,visual,tinymce,fckeditor,widgets,rich text,wysiwyg,html
Requires at least: 3.7
Tested up to: 4.2
Stable tag: 2.3.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Edit widget content using the default WordPress visual editor and media uploading functionality. Create widgets like you would create posts or pages.

== Description ==

= WYSIWYG Widgets or rich text widgets =

This plugin adds so called "Widget Blocks" to your website which you can easily display in your widget areas. 

You can create or edit the widget blocks just like you would edit any post or page, with all the default WordPress editing functions enabled. This way, you can use the visual editor that comes with WordPress to format your widgets. You can even use media uploading to insert images and so forth.

**Features:**

- Create beautiful widgets without having to write HTML code
- Easily insert media into your widget content
- Add headings, lists, blockquotes and other HTML elements to your widgets using the WordPress visual editor
- Use WP Links dialog to easily link to any of your pages or posts from a widget
- Use shortcodes inside your widgets
- Translation ready

**Translators**

- Dutch (nl_NL) - [Danny van Kooten](https://dannyvankooten.com/)
- Spanish (es_ES) - [Maria Ramos - WebHostingHub](http://webhostinghub.com/)
- Italian (it_IT) - [Tiziano D'Angelo - Studio D'Angelo](http://www.dangelos.it/)
- Looking for more.. :)

If you have created your own language pack, or have an update of an existing one, you can send [gettext PO and MO files](http://codex.wordpress.org/Translating_WordPress) to me so that I can bundle it into WYSIWYG Widgets. You can [download the latest PO file here](http://plugins.svn.wordpress.org/wysiwyg-widgets/trunk/languages/wysiwyg-widgets.po).

**More information**

- [WYSIWYG Widgets](https://dannyvankooten.com/wordpress-plugins/wysiwyg-widgets/)
- Check out more [WordPress plugins](https://dannyvankooten.com/wordpress-plugins/) by the same author
- You should follow [@DannyvanKooten](https://twitter.com/dannyvankooten) on Twitter.
- [Thank Danny for this plugin by donating $10, $20 or $50.](https://dannyvankooten.com/donate/)

== Installation ==

1. Upload the contents of wysiwyg-widgets.zip to your plugins directory.
1. Activate the plugin
1. Create a Widget Block by going to *Widget Blocks > Add New*
1. Go to *Appearance > Widgets*, drag the WYSIWYG Widget to one of your widget areas and select which Widget Block to display.
1. *(Optional)* Go to the front-end of your website and enjoy your beautiful widget.

== Frequently Asked Questions ==

= What does this plugin do? =
This plugin creates a custom post type called "Widget Blocks" so you can create widget content just like you would create posts or pages. You can show these "Widget Blocks" by dragging a "WYSIWYG Widget" widget to one of your widget areas and selecting which widget block to display inside it.

= Where do I create a Widget Block? =
The plugin adds a menu to the *Pages* menu item. Just go to *Pages > Widget Blocks* and start creating beautiful widgets.

= What does WYSIWYG mean? =
What You See Is What You Get

= Can I switch between 'Visual' and 'HTML' mode with this plugin? =
Yes, all the default options that you are used to from the post editor are available for the widget editor.

= Will this plugin help me create widgets with images and links =
Yes.

= Is this plugin free? =
Yes, totally. Donations are appreciated though!

== Screenshots ==

1. Overview of created Widget Blocks
2. Edit the content of a WYSIWYG Widget just like you are used to edit posts.
3. Drag the WYSIWYG Widget to one of your widget areas and select the Widget Block to show.

== Changelog ==

= 2.3.5 - March 18, 2015 =

**Fixes**

- Video URL's on their own line will now autoembed

**Improvements**

- Code styling now adheres to WordPress coding standard

= 2.3.4 - December 20, 2013 =
* Fixed: Paragraphs inside or after shortcodes
* Improved: Changed widget name for more consistency

= 2.3.3 - November 18, 2013 =
* Added: Italian translations, thanks to [Tiziano D'Angelo](http://www.dangelos.it/)
* Improved: Code loading
* Improved: added empty index.php files to prevent directory listings
* Improved: all default WordPress' post filters are now applied to the widget content as well. 

= 2.3.2 - November 8, 2013 =
* Improved: When `show_title` is false, (empty) title tags will not be displayed.

= 2.3.1 - November 6, 2013 =
* Added: Spanish translations, thanks to [Maria Ramos from WebHostingHub](http://www.webhostinghub.com/)
* Improved: Minor security and license improvements

= 2.3 - November 5, 2013 =
* Improved: Title now changes with the Widget Block, no widget re-save necessary
* Improved: Minor code improvements
* Improved: Removed all third-party meta boxes from Edit Widget Block screen.
* Improved: Plugin is now translation ready
* Added: Dutch translation

= 2.2.6 - October 30, 2013 =
* Fixed: Show title checkbox now defaults to a checked state.

= 2.2.5 - October 26, 2013 =
* Added checkbox option to widget to hide the title.

= 2.2.4 - October 21, 2013 =
* Moved menu item back to its own menu item
* Widget title now defaults to the title of the selected Widget Block
* Some textual improvements

= 2.2.3 - October 16, 2013 =
* Moved menu item to pages to prevent capability problems
* Removed WP SEO meta box from edit widget block screen

= 2.2.2 =
* Improved: UI improvements, cleaned up admin area.
* Improved: Minor code improvement

= 2.2.1 =
* Improved: small code improvements
* Improved: changed menu position 

= 2.2 =
* Fixed: shortcodes were not processed in v2.1.

= 2.1 =
* Fixed: Social sharing buttons showing up after widget content.

= 2.0.1 =
* Added: meta box in WYSIWYG Widget editor screen.
* Added: debug messages for logged in administrators on frontend when no WYSIWYG Widget OR an invalid WYSIWYG Widget is selected.
* Added: title is now optional for even more control. If empty, it won't be shown. You are now no longer required to use the heading tag which is set in the widget options since you can use a (any) heading in your post.

= 2.0 =
* Total rewrite WITHOUT backwards compatibility. Please back-up your existing WYSIWYG Widgets' content before updating, you'll need to recreate them. Don't drag them to "deactivated widgets", just copy & paste the HTML content somewhere.

= 1.2 =
* Updated the plugin for WP 3.3. Broke backwards compatibility (on purpose), so when running WP 3.2.x and below: stick with [version 1.1.1](https://downloads.wordpress.org/plugin/wysiwyg-widgets.zip).

= 1.1.2 =
* Temporary fix for WP 3.3+

= 1.1.1 =
* Fixed problem with link dialog reloading page upon submit

= 1.1 =
* Changed the way WYSIWYG Widget works, no more overlay, just a WYSIWYG editor in your widget form.
* Fixed full-screen mode
* Fixed link dialog for WP versions below 3.2
* Fixed strange browser compatibility bug
* Fixed inconstistent working
* Added the ability to use shortcodes in WYSIWYG Widget's text

= 1.0.7 =
* Fixed small bug that broke the WP link dialog for WP versions older then 3.2
* Fixed issue with lists and weird non-breaking spaces
* Added compatibility with Dean's FCKEditor for Wordpress plugin
* Improved JS

**NOTE**: In this version some things were changed regarding the auto-paragraphing. This is now being handled by TinyMCE instead of WordPress, so when updating please run trough your widgets to correct this. :) 

= 1.0.6 =
* Added backwards compatibility for WP installs below version 3.2 Sorry for the quick push!

= 1.0.5 =
* Fixed issue for WP3.2 installs, wp_tiny_mce_preload_dialogs is no valid callback. Function got renamed.

= 1.0.4 =
* Cleaned up code
* Improved loading of TinyMCE
* Fixed issue with RTL installs

= 1.0.3 =
* Bugfix: Hided the #wp-link block, was appearing in footer on widgets.php page.
* Improvement: Removed buttons added by external plugins, most likely causing issues. (eg Jetpack)
* Improvement: Increase textarea size after opening WYSIWYG overlay.
* Improvement: Use 'escape' key to close WYSIWYG editor overlay without saving changes.

= 1.0.2 =
* Bugfix: Fixed undefined index in dvk-plugin-admin.php
* Bugfix: Removed `esc_textarea` which caused TinyMCE to break
* Improvement: Minor CSS and JS improvements, 'Send to widget' button is now always visible
* Improvement: Added a widget description
* Improvement: Now using the correct way to set widget form width and height

= 1.0.1 =
* Bugfix: Fixed the default title, it's now an empty string. ('')

= 1.0 = 
* Initial release

== Upgrade Notice ==

= 2.3 =
Plugin is now translation-ready. Included Dutch translations, looking for more translators!

= 2.0  =
No backwards compatibility, please back-up your existing widgets before upgrading!