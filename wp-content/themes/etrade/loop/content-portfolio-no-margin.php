<?php
/**
 * Template for displaying portfolio post type in grid layout (no margin)
*/

$css_classes = '';

if( !has_post_thumbnail() ){
	$css_classes .= ' no-media';
}

// portfolio category
$term = pukka_get_object_term($post->ID, 'portfolio_category');

if( $term != false && !empty($term->slug)){
	$css_classes .= ' '. $term->slug;
}
?>
		<div class="portfolio-item no-margin basic <?php echo $css_classes; ?>">
			<a href="<?php the_permalink(); ?>"> <!-- open wrap link -->
			<?php
			if( has_post_thumbnail() 
				|| get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'secondary_image_id', true) != '' 
				|| get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'secondary_image_url', true) != '' 
			)
			: ?>
			<div class="portfolio-media">
				<?php
				if( get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'secondary_image_id', true) != '' ){
					// secondary image is uploaded
					$image = wp_get_attachment_image_src(get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'secondary_image_id', true), 'thumb-cat-col');
				}
				elseif( get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'secondary_image_url', true) != '' ){
					// secondary image URL is specified
					$image_url = get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'secondary_image_url', true);
					$image = array($image_url);

					$image_info = getimagesize($image_url);
					
					if( $image_info != false ){
						$image[] = $image_info[0]; // width
						$image[] = $image_info[1]; // height
					}
				}
				elseif( has_post_thumbnail() ){
					$image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumb-cat-col');
				}
			?>
			<?php
				echo '<img src="'. $image[0] .'" width="'. $image[1] .'" height="'. $image[2] .'" alt="'. get_the_title() .'" />' ."\n";
			?>
		</div>
		<?php endif; //if( has_post_thumbnai() ) ?>

	<div class="portfolio-content">
		<div class="portfolio-text text-content">
			<h3><?php the_title(); ?></h3>
			<?php the_excerpt(); ?>
		</div>
	</div> <!-- .portfolio-content -->

	</a> <!-- close wrap link -->
</div>  <!-- .portfolio-item -->