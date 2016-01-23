<?php
/**
 *
 * @package WordPress
 * @subpackage themename
 */

global $page_type, $current_reservation;

get_header(); ?>

<div class="wrapper new-reservation">

  <?php if( is_int( $current_reservation->reservation_id ) ) {

    $data['reservation']['mode'] = 'new_reservation';

    get_template_part_with_data( 'reservation/forms', 'template', 'update-reservation', $data );?>

  <?php } else { ?>

    <div class="center"><p>Could not load Reservation. Please try again.</p></div>

  <?php } ?>

</div>

<?php get_footer(); ?>
