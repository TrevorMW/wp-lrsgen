<?php
/**
 * @package WordPress
 * @subpackage themename
 */
$blog_id = get_option('page_for_posts'); get_header(); ?>

<div class="primary" role="structure">
  <section class="page posts-page">
  	<header class="page-header">
    	<h1 class="page-title" role="heading"><?php get_the_title( $blog_id );?></h1>
    </header>
    <div class="page-content">
      <?php $post = get_post( $blog_id ); $post->post_content; ?><hr />
      <?php get_template_part( 'loop', 'index' ); ?>
    </div>
  </section>
</div>

<?php get_sidebar();  get_footer(); ?>
