<?php
/**
 *
 * @package WordPress
 * @subpackage themename
 */

global $page_type, $current_hotel;

get_header(); ?>

<div class="wrapper new-reservation">

  <?php $data['reservation']['mode'] = 'new_reservation';

  get_template_part_with_data( 'reservation/forms', 'template', 'update-reservation', $data );?>

</div>

<?php get_footer(); ?>
