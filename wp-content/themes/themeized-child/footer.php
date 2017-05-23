
			
	<footer>
		<section class="ftr-area" id="footer">
		
			<div class="container">
			
                <div class="footer-box col-sm-4 col-sm-offset-1">
					<?php dynamic_sidebar( 'home__contactform' ); ?>	
					
					<hr/>
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
				
                <div class="footer-box col-sm-4 col-sm-offset-2 footer-right">
					<?php dynamic_sidebar( 'footer__contactinfo' ); ?>	
				</div>
				
				<div class="clearfix"></div>
                
				<div class="follow-area clrlist text-center">
						    
					<?php dynamic_sidebar( 'footer__followme' ); ?>
					
				<div class="footer-logo"><img src="<?php echo get_stylesheet_directory_uri();?>/images/logo.png" alt=""></div>	  							
                </div>  
                        					
			</div>
		</section>
		
		<section class="copyright-area">
		<div class="container">
		    <div class="copyright-cont text-center">
			    <?php dynamic_sidebar( 'bottom__copyright' ); ?>
				
			</div>   
		</div>
		</section>

		
	</footer>
	
			<a href="" class="scrollToTop"><i class="fa fa-arrow-up"></i></a>
	
</main>
    
	<!--Bootstrap-->
    <script src="<?php echo get_stylesheet_directory_uri();?>/js/bootstrap.min.js"></script>
	<!--./Bootstrap-->
	
	<!--Major Scripts-->
	<script src="<?php echo get_stylesheet_directory_uri();?>/js/viewportchecker.js"></script>
    <script src="<?php echo get_stylesheet_directory_uri();?>/js/kodeized.js"></script>
	<!--./Major Scripts-->

	<?php wp_footer(); ?>
	
		</body>
</html>