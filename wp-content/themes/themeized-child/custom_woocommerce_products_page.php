<?php /* Template Name: Product Page */ ?>

<?php get_header(); ?>


<section class="inner-page">
					
					<span class="cartitems">
						<a class="cart-contents" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><i class="glyphicon glyphicon-shopping-cart"></i> <?php echo sprintf (_n( '%d item', '%d items', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?> - <?php echo WC()->cart->get_cart_total(); ?></a>
					</span>
					

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


		<section id="recentProductArea">
			<div class="container">
				<div class="products-area">
				<?php
					$args = array( 'post_type' => 'product', 'stock' => 1, 'posts_per_page' => 4, 'orderby' =>'date','order' => 'DESC' );
					$loop = new WP_Query( $args );
					while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
							<div class="product-box col-sm-3">    
								<div class="prod-inr">
								<a id="id-<?php the_id(); ?>" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
								   <div class="prod-img"> <?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="'.woocommerce_placeholder_img_src().'" alt="Placeholder" width="65px" height="115px" />'; ?></div>

									<div class="prod-cont">
									<h3><?php the_title(); ?></h3>
									   <span class="price"><?php echo $product->get_price_html(); ?></span>
								</a>
								<?php woocommerce_template_loop_add_to_cart( $loop->post, $product ); ?>
								<div class="clearfix"></div>
								</div>
								</div>
							</div>
				<?php endwhile; ?>
				<?php wp_reset_query(); ?>
				</div>
			</div>
		</section>
</section>

<?php get_footer(); ?>