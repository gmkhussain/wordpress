	<footer>
		<section class="ftr">
			<div class="container">
				<div class="ftr-box col-sm-3 ftr-logo">
					<h4>Footer Logo</h4>
					
					<?php $post = get_post(31); setup_postdata($post); ?>
					
					<div class="ftr__logo"><?php the_post_thumbnail(); ?></div>
					
				</div>
				<div class="ftr-box col-sm-6 ftr-links list-col-2 clrlist listview">
					<h4>Useful links</h4>
					
					<?php
						$defaults = array(
							'theme_location'  => '',
							'menu'            => 'main_menu',
							'container'       => '',
							'container_class' => '',
							'container_id'    => 'bs-navbar-ftr',
							'menu_class'      => 'navbar-ftr',
							'menu_id'         => '',
							'echo'            => true,
							'fallback_cb'     => 'wp_page_menu',
							'before'          => '',
							'after'           => '',
							'link_before'     => '',
							'link_after'      => '',
							'items_wrap'      => '<ul id="%1$s" class="navbar-ftr">%3$s</ul>',
							'depth'           => 0,
							'walker'          => ''
						);
						wp_nav_menu( $defaults );
						?>
						
				</div>
				<div class="ftr-box col-sm-3 social-area clrlist">
					<h4>Connect with Us</h4>
					<?php dynamic_sidebar( 'social_icons' ); ?>				
				</div>				
			</div>
		</section>
	</footer>
	
	

</section>

    <script src="<?php echo get_stylesheet_directory_uri();?>/js/bootstrap.min.js"></script>
	
	<script src="<?php echo get_stylesheet_directory_uri();?>/js/viewportchecker.js"></script>
    <script src="<?php echo get_stylesheet_directory_uri();?>/js/-kscript.js"></script>

	
	<?php wp_footer(); ?>
			
	   </body>
</html>