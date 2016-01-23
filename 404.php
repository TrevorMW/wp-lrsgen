<?php
/**
 * @package WordPress
 * @subpackage themename
 */

get_header(); ?>

<div class="primary">
  <section class="page error404 not-found">
    <header class="page-header">
      <h1 class="page-title">
        <?php _e( 'This is somewhat embarrassing, isn&rsquo;t it?', 'themename' ); ?>
      </h1>
    </header>
    <div class="page-content">
      <p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching, or one of the links below, can help.', 'themename' ); ?>
      </p>
    </div>
  </section>
</div>
<div class="secondary">
  <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
