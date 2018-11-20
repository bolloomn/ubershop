<?php
/**
 * Template for displaying link post format in grid layout
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
			<a href="<?php echo get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'link', true); ?>" target="_blank">
			 <?php the_post_thumbnail('thumb-brick'); ?>
			</a>
		</div>
<?php endif; //if( has_post_thumbnai() ) ?>

	<div class="brick-meta-wrap">
	<?php pukka_entry_meta(array('hide_tags'=>true)); ?>
	</div> <!-- .brick-meta-wrap -->

	<div class="brick-content">
		<h3><a href="<?php echo get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'link', true); ?>" target="_blank"><?php the_title(); ?></a></h3>
		<?php the_excerpt(); ?>
	</div> <!-- .brick-content -->
</div>  <!-- .brick -->