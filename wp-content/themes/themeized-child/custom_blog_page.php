<?php
/*
Template Name: My Blog Posts
*/


 get_header(); ?>

	<section class="blog-area pt50 pb50">
		<div class="container">
	
	
	<div class="post-area col-sm-8">
	
        <?php query_posts('post_type=post&post_status=publish&posts_per_page=10&paged='. get_query_var('paged')); ?>

		<?php if( have_posts() ): ?>

			<?php while( have_posts() ): the_post(); ?>

			<div id="post-<?php get_the_ID(); ?>" <?php post_class(); ?>>
				<div class="blog__img col-sm-4">
					<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
				</div>
				
				<div class="blog__cont col-sm-8 clrhm">
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					
					<br/>

					<span class="meta"><?php //author_profile_avatar_link(48); ?> <strong><?php the_time('F jS, Y'); ?></strong> / <strong><?php the_author_link(); ?></strong> / <span class="comments"><?php comments_popup_link(__('0 comments','example'),__('1 comment','example'),__('% comments','example')); ?></span></span>


					<?php the_excerpt(__('Readmore »','example')); ?>

			
				</div><!-- /#post-<?php get_the_ID(); ?> -->
			</div>
			<?php endwhile; ?>

			<div class="navigation">
				<span class="newer"><?php previous_posts_link(__('« Newer','example')) ?></span> <span class="older"><?php next_posts_link(__('Older »','example')) ?></span>
			</div><!-- /.navigation -->

		<?php else: ?>

			<div id="post-404" class="noposts">

				<p><?php _e('None found.','example'); ?></p>

			</div><!-- /#post-404 -->

		<?php endif; wp_reset_query(); ?>
	
		</div>
	
	
		<div class="post-sidebar-area col-sm-4">
			
			
			<div class="post-search-area">
				<?php get_search_form(); ?>
			</div>

			
			<div class="recent-area  clrlist listview">
				<h3>Recent Posts</h3>
				<ul>
				<?php
					$args = array( 'numberposts' => '5' );
					$recent_posts = wp_get_recent_posts( $args );
					foreach( $recent_posts as $recent ){
						echo '<li><a href="' . get_permalink($recent["ID"]) . '">' . $recent["post_title"].'</a> </li> ';
					}
					wp_reset_query();
				?>
				</ul>
			</div>
			
			
			<div class="archives-area clrlist listview">
				<?php the_post(); ?>
				
				<div class="archives-by-month">
					<h3>Archives by Month:</h3>
					<ul>
						<?php wp_get_archives('type=monthly'); ?>
					</ul>
				</div>
				
				<div class="archives-by-subject">				
					<h3>Archives by Subject:</h3>
					<ul>
						<?php wp_list_categories(); ?>
					</ul>
				</div>

			</div>
			
		</div>
		
		
	</section><!-- /#content -->

	<style>
a.more-link {
		background-color: #39c;
		color: #fff;
		display: inline-block;
		padding: 5px 10px;
		margin-top: 10px;
	}
		
form.search-form span.screen-reader-text {
    display: none;
}

form.search-form {
    float: left;
    width: 100%;
}

form.search-form input[type=search] {
    width: 100%;
    height: 40px;
    border: 1px solid #ccc;
    padding: 5px 10px;
    font-weight: 400;
}

form.search-form label {
    float: left;
    width: 100%;
}

form.search-form input.search-submit.screen-reader-text {
    float: right;
    position: absolute;
    right: 0;
    height: 40px;
    border: 1px solid #ccc;
    font-weight: 400;
    text-transform: uppercase;
    padding: 10px 20px;
}

.post.type-post {
    float: left;
    width: 100%;
    margin-bottom: 20px;
    border-top: 1px solid #ccc;
    padding-top: 30px;
}

.post-sidebar-area ul li {
    float: left;
    width: 100%;
    margin: 10px 0;
}

.post-sidebar-area h3 {
    margin-top: 20px;
    float: left;
    width: 100%;
    margin-bottom: 0;
}


div#comments {
    background-color: #f7f7f7;
    float: left;
    padding: 15px;
    border: 1px solid #eee;
    margin: 40px 0;
}


p.comment-form-comment label {
    display: block;
}

nav.navigation.post-navigation.-nav {
    clear: both;
    float: left;
    width: 100%;
    margin: 0 0 30px;
}


.format-video .post-thumbnail {
    display: none;
}

</style>
	
	
<?php get_footer(); ?>
