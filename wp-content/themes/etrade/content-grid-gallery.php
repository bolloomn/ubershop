<?php
/**
 * Template for displaying gallery post in grid layout
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

	<div class="brick-media">

	<?php if( has_post_thumbnail() ) : ?>
			<?php // If there is featured image - use it! ?>
			<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail('thumb-brick'); ?>
			</a>
	<?php else : ?>
		
		<?php /* else use slider */ ?>
			<ul class="slides">
				<?php
					$args = array(
						'post_type' => 'attachment',
						'posts_per_page' => -1,
						'exclude' => get_post_thumbnail_id(), // dont display featured image
						'post_status' => null,
						'post_parent' => $post->ID,
						'orderby' => 'menu_order',
						'order' => 'ASC',
					);
					
					$attachments = get_posts( $args );
					if ( $attachments ) {
							$slide_items = '';
							foreach ( $attachments as $attachment ) {
								 $slide_items .= '<li>';
								 $slide_items .= '<a href="'. wp_get_attachment_url($attachment->ID) .'">';
								 $slide_items .= wp_get_attachment_image($attachment->ID, 'thumb-brick');
								 $slide_items .= '</a>';
								 $slide_items .= '</li>' ."\n";
								}
							// attach lightbox
							$slide_items = apply_filters('pukka_attach_lightbox', $slide_items, $post->ID);
							echo $slide_items;
					}
				 ?>
			</ul> <!-- .slides -->


	<?php endif; ?>

	</div> <!-- .brick-media -->

	<div class="brick-meta-wrap">
	<?php pukka_entry_meta(array('hide_tags'=>true)); ?>
	</div> <!-- .brick-meta-wrap -->

	<div class="brick-content">
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php the_excerpt(); ?>
	</div> <!-- .brick-content -->
</div>  <!-- .brick -->