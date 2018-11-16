<?php
/**
 * Template part for posts pagination.
 *
 * @package Solosshopy
 */

the_posts_pagination(
	array(
		'prev_text' => sprintf( '%s %s', '<i class="nc-icon-mini arrows-1_tail-triangle-left"></i>', esc_html__( 'PREV', 'solosshopy' ) ),
		'next_text' => sprintf( '%s %s', esc_html__( 'NEXT', 'solosshopy' ), '<i class="nc-icon-mini arrows-1_tail-triangle-right"></i>' ),
	)
);
