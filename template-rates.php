<?php
/**
 * Template Name: Template - Rates
 * Description: Generic Sub Page Template
 *
 * @package WordPress
 * @subpackage themename
 */

if( function_exists( 'get_'.$post->post_name.'_tools' ) )
{
  add_action( 'page-tools', 'get_'.$post->post_name.'_tools', 10 );
}

get_header();

do_action( "$post->post_name_content", $post->ID );

get_footer(); ?>
