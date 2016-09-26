<?php /* Template Name: My PostType Page */ ?>
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
			<?php			  query_posts( array( 'post_type' => 'mytabs', 'order' => 'ASC', 'mytabs_categories' => 'logo' ) );			  //the loop start here			  if ( have_posts() ) : while ( have_posts() ) : the_post();			?>			  <h3><?php the_title(); ?></h3>			<code>  Post Type: 			<?php 			  $terms = get_the_terms( $post->ID, 'mytabs_categories' ); 					foreach($terms as $term) {				  echo $term->name;				}			?>			</code>							<p><?php the_content(); ?></p>						<?php endwhile; endif; wp_reset_query(); ?>															<?php				query_posts( array( 'post_type' => 'mytabs', 'order' => 'ASC', 'mytabs_categories' => 'logo' ) );				 /*the loop start here*/				  if ( have_posts() ) : while ( have_posts() ) : the_post();			?>				<?php 					$terms = get_the_terms( $post->ID, 'mytabs_categories' ); 					foreach($terms as $term) {				?>				  <a href="<?php echo get_term_link($term->slug, 'mytabs_categories') ?>"><b>Cata</b> <?php echo $term->name; ?></a>				<?php } ?>			<?php endwhile; endif; wp_reset_query(); ?>
			</div>
		</section>
		

</section>
<?php get_footer(); ?>