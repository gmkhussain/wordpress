<?php /* Template Name: My Home Page */ ?>

<?php get_header(); ?>


<section class="slider-area">
  <div class="container">

    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
      <?php 
			 $slide_number = 0; 			
			 $args_number=array(			
			 'post_type'=> 'my_slider',				
			 'post_status' => 'publish',				
			 'posts_per_page' => -1,'my_slider_categories' => 'home'			 );
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
			$args=array( 'post_type'=> 'my_slider', 'post_status' => 'publish', 'posts_per_page' => -1,'my_slider_categories' => 'home' );
			$my_query = new WP_Query($args);
				if( $my_query->have_posts() ) {
					while ($my_query->have_posts()) : $my_query->the_post(); ?>
						<div class="item <?php echo $class_active ;?>">
							<?php the_post_thumbnail('full'); ?> 
							<h2><?php the_title(); ?></h2>
						</div>
		<?php  
			$class_active=""; endwhile; 
			}							
			wp_reset_query();  // Restore global post data stomped by the_post().		
		?>
      </div>

      <!-- Controls -->
      <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left fa fa-angle-left"></span>
      </a>
      <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right fa fa-angle-right"></span>
      </a>
    </div>

  </div>
</section>


		
	<?php
	$args = array( 'numberposts' => '5' );
	$recent_posts = wp_get_recent_posts( $args );
	foreach( $recent_posts as $recent ){
		echo '<li><a href="' . get_permalink($recent["ID"]) . '">' .   $recent["post_title"].'</a> </li> ';
		 echo  $recent["post_content"];
	}
	wp_reset_query();
?>


<?php get_footer(); ?>