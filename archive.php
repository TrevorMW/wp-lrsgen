<?php
/**
 * @package WordPress
 * @subpackage themename
 */

get_header(); the_post();?>

<div class="primary" role="structure">
  <section class="page archive">
    <header class="page-header">
      <h1 class="page-title" role="heading">
        <?php if ( is_day() ) : printf( __( 'Daily Archives: <span>%s</span>', 'themename' ), get_the_date() ); ?>
        <?php elseif ( is_month() ) : printf( __( 'Monthly Archives: <span>%s</span>', 'themename' ), get_the_date( 'F Y' ) ); ?>
        <?php elseif ( is_year() ) : printf( __( 'Yearly Archives: <span>%s</span>', 'themename' ), get_the_date( 'Y' ) ); ?>
        <?php else : _e( 'Blog Archives', 'themename' );  endif; ?>
      </h1>
    </header>
    <div class="page-content">
      <?php rewind_posts(); get_template_part( 'loop', 'archive' ); ?>
    </div>
  </section>
</div>

<?php get_sidebar(); get_footer(); ?>
