
			
	<footer>
		<section class="ftr-area" id="footer">
			<div class="container">
			
                <div class="footer-box col-sm-4 col-sm-offset-1">
					<?php dynamic_sidebar( 'home__contactform' ); ?>	
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