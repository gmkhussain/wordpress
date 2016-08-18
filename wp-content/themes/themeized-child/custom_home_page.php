<?php /* Template Name: My Home Page */ ?>

<?php get_header(); ?>


<section id="page-content">

 <section class="slider fadeft">

	<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
			  <?php 
			 $slide_number = 0; 
			 $args_number=array(
				  'post_type'=> 'sliders',
				  'post_status' => 'publish',
				  'posts_per_page' => 3,
				 );
			$my_slide_number = new WP_Query($args_number);

			if( $my_slide_number->have_posts() ) {
			?>
			  <ol class="carousel-indicators">
				<?php while ($my_slide_number->have_posts()) : $my_slide_number->the_post(); ?>
				<li data-target="#carousel-example-generic" data-slide-to="<?php echo $slide_number++; ?>"></li>
				<?php endwhile; ?>
			  </ol>
			 <?php } ?>
  
			  <!-- Wrapper for slides -->
			  <div class="carousel-inner">

		<?php
		 $class_active="active";
		 $args=array( 'post_type'=> 'sliders', 'post_status' => 'publish', 'posts_per_page' => 3, );
			$my_query = new WP_Query($args);
			if( $my_query->have_posts() ) {
			  while ($my_query->have_posts()) : $my_query->the_post(); ?>
					<div class="item <?php echo $class_active ;?>">
						 <?php the_post_thumbnail('full'); ?>
					<div class="container">
					  <div class="caro-caps">
							<h3><?php the_title(); ?></h3>
							<p><?php the_content();?></p>
						  </div>
					 </div>
				   </div>
			<?php  $class_active=""; endwhile; }
			wp_reset_query();  // Restore global post data stomped by the_post().
		?>
				
		</div>

	  <!-- Controls -->
	  <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left"></span>
	  </a>
	  <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right"></span>
	  </a>
	</div>

</section>
	
	
	<section class="tabs-area tab-animate">
	
		<div class="bg-red">
			<div class="container">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs nav-cntr">
				  
				  <?php 
					$tab_number = 1; 
					$args = array('post_type' => 'mytabs', 'order' => 'ASC');
					 
					$loop = new WP_Query( $args );

					while ( $loop->have_posts() ) : $loop->the_post();
					?>
					
					<li><a href="#tab<?php echo $tab_number++ ?>" data-toggle="tab"><?php the_title(); ?></a></li>
	  
				  <?php endwhile;?>
				  
				</ul>
			</div>
		</div>
	
	
		<div class="bg-white">
		<div class="container">
			<div class="tab-content overload">
			  
			<?php 
				$tab_number = 1; 
				$args = array('post_type' => 'mytabs', 'order' => 'ASC');
				 
				$loop = new WP_Query( $args );

				while ( $loop->have_posts() ) : $loop->the_post();
			?>
				
					<div class="tab-pane active" id="tab<?php echo $tab_number++ ?>">
						<?php the_content(); ?>
					</div>
			<?php endwhile;?>
			  
			</div>	
		</div>	
	</div>	
	
		</div>
	</section>
	
<?php get_footer(); ?>