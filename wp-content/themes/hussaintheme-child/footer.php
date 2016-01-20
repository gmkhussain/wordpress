
	
	<footer>
	
		<section class="ftr">
			<div class="container">
				<div class="ftr-box col-sm-3 ftr-logo">
					<h4>Footer Logo</h4>
					<div class="ftr__logo"><img src="images/favicon.png" atl="" /></div>
				</div>
				<div class="ftr-box col-sm-6 ftr-links list-col-2 clrlist listview">
					<h4>Useful links</h4>
					<ul>
						<li><a href="#">Ink®</a></li>
						<li><a href="#">Training</a></li>
						<li><a href="#">Design Resources</a></li>
						<li><a href="#">Notable</a></li>
						<li><a href="#">Support</a></li>
						<li><a href="#">Blog</a></li>
					</ul>
				</div>
				<div class="ftr-box col-sm-3 social-area clrlist">
					<h4>Connect with Us</h4>
					<ul>
						<li><a href="#"><i class="fa fa-facebook"></i></a></li>
						<li><a href="#"><i class="fa fa-google"></i></a></li>
						<li><a href="#"><i class="fa fa-twitter"></i></a></li>
					</ul>					
				</div>				
			</div>
		</section>
	

	</footer>
	
	
	
<!-- beforeExit -->
<div class="modal fade " id="beforeExit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header ">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Modal Before Exit</h4>
      </div>
      <div class="modal-body modal-area text-center fnc-fom">
	  
			  <h3>WAIT <span>DONT'T GO....</span></h3>
			  <p>Become a Better, Faster Front-End Developer</p>

			  <div class="form-group">
				<input type="text" class="form-control" id="exampleInputEmail1" placeholder="Name...">
				<label>Name (Movable Label)</label>
			  </div>

			  <div class="form-group">
				<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email...">
			  </div>

			  <div class="pink-btn text-center">
				<button type="submit" class="form-control">GET ACCESS</button>
			  </div>
		</div>
	</div>
  </div>
</div>
	
	

</section>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="<?php echo get_stylesheet_directory_uri();?>/js/jquery1.11.1.min.js"></script>-->
    <!-- Include all compiled plugins (below), or include individual files as needed -->

    <script src="<?php echo get_stylesheet_directory_uri();?>/js/bootstrap.min.js"></script>
	
	<script src="<?php echo get_stylesheet_directory_uri();?>/js/viewportchecker.js"></script>
    <script src="<?php echo get_stylesheet_directory_uri();?>/js/kscript.js"></script>

	
	
	<script src="<?php echo get_stylesheet_directory_uri();?>/js/swiper.jquery.min.js"></script>
	<!-- Initialize Swiper -->
    <script>
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        slidesPerView: '7',
        centeredSlides: false,
        paginationClickable: true,
        spaceBetween: 15,
		autoplay: 2500,
		autoplayDisableOnInteraction: false
    });
    </script>

	
	<script>//When mouse out from website * add 'leavepopup' on <body>
		jQuery('body.leavepopup').mouseleave(function() {
			jQuery('#beforeExit').modal('show');
		});
	</script>
	
	
	   </body>
</html>