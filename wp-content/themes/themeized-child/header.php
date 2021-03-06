<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
 ?>

  <!DOCTYPE html>
  <html <?php language_attributes(); ?> class="no-js">

  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--link rel="icon" type="image/png" href="<?php $post = get_post(22); setup_postdata($post); ?>	<?= wp_get_attachment_image_src( get_post_thumbnail_id(), 'full', false )[0] ?>"-->
    <!-- Bootstrap -->
    <link href="<?php echo get_stylesheet_directory_uri();?>/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/style.css">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/css/colorized.css">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/css/animate.css">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/css/slidenav.css">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/css/fonts.css">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/css/font-awesome.min.css">
    <!-- jQuery -->
    <!--[if (!IE)|(gt IE 8)]><!-->
    <script src="<?php echo get_stylesheet_directory_uri();?>/js/jquery-2.2.4.min.js"></script>
    <!--<![endif]-->
    <!--[if lte IE 8]>	  <script src="<?php echo get_stylesheet_directory_uri();?>/js/jquery1.9.1.min.js"></script>	<![endif]-->
    <!--browser selector-->
    <script src="<?php echo get_stylesheet_directory_uri();?>/js/css_browser_selector.js" type="text/javascript"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>      <script src="<?php echo get_stylesheet_directory_uri();?>/js/html5shiv.min.js"></script>      <script src="<?php echo get_stylesheet_directory_uri();?>/js/respond.min.js"></script>    <![endif]-->
    <?php  wp_head(); ?>
  </head>

  <body <?php $class="transition nav-plusminus slide-navbar slide-navbar--right" ; body_class( $class ); ?>>
    <header>
      <section class="hdr-area hdr-nav hdr--sticky cross-toggle" data-navitemlimit="0">
        <div class="container">
          <div class="rela">
            <div class="logo-area">

              <?php the_custom_logo(); ?>

            </div>
            <div class="language-area clrlist pull-right">
              <ul>
                <li><a href="#">ESPANOL</a></li>
                <li class="active"><a href="#">ENGLISH</a></li>
              </ul>
            </div>
            <nav class="navbar navbar-default" role="navigation" id="slide-nav">
              <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header"> <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">					<span class="sr-only">Toggle navigation</span>					<span class="icon-bar"></span>					<span class="icon-bar"></span>					<span class="icon-bar"></span>				  </button>                  </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div id="slidemenu">
                  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <?php						
					$defaults = array(
					'theme_location'  => '',								
					'menu'            => 'main_menu',								
					'container'       => '',								
					'container_class' => '',								
					'container_id'    => 'bs-example-navbar-collapse-1',								
					'menu_class'      => 'nav navbar-nav main-nav  navbar-right',								
					'menu_id'         => '',								
					'echo'            => true,								
					'fallback_cb'     => 'wp_page_menu',								
					'before'          => '',								
					'after'           => '',								
					'link_before'     => '',								
					'link_after'      => '',								
					'items_wrap'      => '<ul id="%1$s" class="nav navbar-nav">%3$s</ul>',								
					'depth'           => 0,								
					'walker'          => ''							
					);						
					wp_nav_menu( $defaults );					
					?>
                  </div>
                </div>
                <!-- /.container-fluid -->
            </nav>
            </div>
          </div>
      </section>
    </header>
    <main id="page-content">