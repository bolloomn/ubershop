<?php
	$post_css_classes = '';

	if( !has_post_thumbnail() ){
		$post_css_classes = 'no-media';
	}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($post_css_classes); ?>>
	<?php if( in_array(get_post_format(), array('video', 'audio', 'gallery', 'link')) || has_post_thumbnail() ) : ?>
		<div class="featured">
		<?php
			if( has_post_format('video') || has_post_format('audio')){
				pukka_media();
			}
			/*
			elseif( has_post_format('gallery') ){
				echo '<a href="' . get_permalink() . '">';
				the_post_thumbnail('thumb-single');
				echo '</a>';
			}
			*/
			elseif( has_post_format('link') ) {
				echo '<a href="'. get_post_meta($post->ID, '_pukka_link',true) .'" target="_blank">';
				the_post_thumbnail('thumb-single');
				echo '</a>';
			}
			elseif( has_post_thumbnail() ){
				echo '<a href="' . get_permalink() . '">';
				the_post_thumbnail('thumb-single');
				echo '</a>';
			}
		?>
		</div> <!-- .featured -->
	<?php endif; //<?php if( has_post_format(array('video', 'audio', 'gallery')) || has_post_thumbnail() ) : ?>

	<div class="content-wrap">
		<?php if( !has_post_format('quote') ) : ?>
		<header class="entry-header headings">
			<div class="entry-meta">
				<?php pukka_entry_meta(); ?>
			</div> <!-- .entry-meta -->
		</header> <!-- .entry-header -->

		<h1 class="entry-title">
			<?php if( has_post_format('link') ) : ?>
			<a href="<?php echo get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'link', true); ?>" target="_blank">
			<?php else : ?>
			<a href="<?php the_permalink(); ?>">
			<?php endif; ?>
			<?php the_title(); ?>
			</a>
		</h1>
		<?php endif; // if( !has_post_format('quote') ) ?>

		<div class="entry-content">
			

			<?php /* Add 'read more' link for 'link' format */ ?>
			<?php if( has_post_format('link') ) : ?>
			<?php the_content(); ?>
			<a href="<?php echo get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'link', true); ?>" target="_blank" class="more-link"><?php _e('Read more', 'pukka'); ?></a>
			<?php else : ?>
			<?php the_content( __('Read more', 'pukka') ); ?>
			<?php endif; ?>
		</div><!-- .entry-content -->
	</div> <!-- .content-wrap -->
</article>