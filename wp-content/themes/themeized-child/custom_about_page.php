<?php /* Template Name: My Basic Page */ ?>

<?php get_header(); ?>


<section class="inner-page">
	<?php
		// Start the loop.
		while ( have_posts() ) : the_post(); ?>

		<section class="inr-bnr text-center">
			<div class="inr-bnr-img"> <?php echo the_post_thumbnail('full'); ?></div>
			<h1><?php echo the_title(); ?> </h1>

		</section>

	<?php endwhile ?>

		<section class="main-content">
			<div class="container">
					<?php echo 	the_content(); ?>
			</div>
		</section>
		

</section>

<?php get_footer(); ?>