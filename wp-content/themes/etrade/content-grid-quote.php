<?php
/**
 * Template for displaying standard post in grid layout
 */

$css_class = '';

if( get_post_format() ){
	$css_class .= ' brick-' . get_post_format();
}

if( !has_post_thumbnail() && !pukka_has_post_media() ){
	$css_class .= ' no-media';
}
?>
<div class="brick basic <?php echo $css_class; ?>">
<?php if( has_post_thumbnail() ) : ?>
		<div class="brick-media">
			<a href="<?php the_permalink(); ?>">
			 <?php the_post_thumbnail('thumb-brick'); ?>
			</a>
		</div>
<?php endif; //if( has_post_thumbnai() ) ?>

	<div class="brick-content">
		<?php the_excerpt(); ?>
	</div> <!-- .brick-content -->
</div>  <!-- .brick -->