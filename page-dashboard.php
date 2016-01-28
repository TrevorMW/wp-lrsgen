<?php
/**
 *
 * @package WordPress
 * @subpackage themename
 */

global $page_type;

get_header();

$dash = new Dashboard(); ?>

  <div class="wrapper dashboard-data">
    <ul><?php echo $dash->get_dashboard_data(); ?></ul>
  </div>

<?php get_footer(); ?>
