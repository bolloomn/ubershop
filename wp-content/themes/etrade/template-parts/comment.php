<?php
/**
 * The template for displaying comments.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy
 *
 * @package Solosshopy
 */
?>
<div class="comment-author vcard">
	<?php echo solosshopy_comment_author_avatar(); ?>
</div>
<div class="comment-content-wrap">
	<footer class="comment-meta">
		<div class="comment-metadata">
			<?php echo solosshopy_get_comment_author_link(); ?>
			<?php echo solosshopy_get_comment_date(); ?>
		</div>
	</footer>
	<div class="comment-content">
		<?php echo solosshopy_get_comment_text(); ?>
	</div>
	<div class="reply">
		<?php echo solosshopy_get_comment_reply_link( array(
			'reply_text' => esc_html__( 'Reply', 'solosshopy' ),
		) ); ?>
	</div>
</div>
