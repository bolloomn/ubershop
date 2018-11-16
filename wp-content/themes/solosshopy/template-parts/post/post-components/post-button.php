<?php
/**
 * Template part for displaying post read more button.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Solosshopy
 */

$utility     = solosshopy_utility()->utility;
$btn_visible = solosshopy_get_mod( 'blog_read_more_btn', true, 'wp_kses_post' );
$btn_text    = solosshopy_get_mod( 'blog_read_more_text', true, 'wp_kses_post' );

$utility->attributes->get_button( array(
	'visible' => $btn_visible,
	'class'   => 'post__button btn btn-accent-1',
	'text'    => $btn_text,
	'html'    => '<div class="post__button-wrap"><a href="%1$s" %3$s>%4$s%5$s</a></div>',
	'echo'    => true,
) );
