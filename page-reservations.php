<?php
/**
 *
 * @package WordPress
 * @subpackage themename
 */

global $page_type;

get_header();

  do_action( $page_type.'content', $post->ID );

get_footer(); ?>
