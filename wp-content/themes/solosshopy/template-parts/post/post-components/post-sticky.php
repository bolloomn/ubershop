<?php
/**
 * Template part for displaying sticky.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Solosshopy
 */

$utility          = solosshopy_utility()->utility;
$sticky           = solosshopy_sticky_label( false );

echo wp_kses_post( $sticky );
