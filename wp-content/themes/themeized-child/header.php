<?php
/**
?>
<!DOCTYPE html>
<?php  wp_head(); ?>
</head>
<body <?php body_class( $class ); ?>>
		<?php global $post; $slug = get_post( $post )->post_name; echo $slug; ?>
		<?php  $post2 = get_post(74); setup_postdata($post2); ?>
		<?php wp_get_attachment_image_src( get_post_thumbnail_id( $post2 ), 'single-post-thumbnail' );  ?>