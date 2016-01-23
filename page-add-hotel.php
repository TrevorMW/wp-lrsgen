<?php
/**
 *
 * @package WordPress
 * @subpackage themename
 */

global $page_type, $current_hotel;

get_header(); ?>

<div class="wrapper add-hotel-form">
  <?php get_template_part_with_data( 'hotel/forms', 'template', 'update-hotel', $data );?>
</div>

<?php get_footer(); ?>

