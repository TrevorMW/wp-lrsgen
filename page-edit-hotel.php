<?php
/**
 *
 * @package WordPress
 * @subpackage themename
 */

global $page_type, $current_hotel;

get_header(); ?>

<div class="wrapper edit-hotel-form">

  <?php if( is_int( $current_hotel->hotel_id ) ) {

    $data['hotel'] = $current_hotel; ?>

    <?php get_template_part_with_data( 'hotel/forms', 'template', 'update-hotel', $data );?>

  <?php } else { ?>

    <div class="center"><p>Could not load Hotel data. Please try again.</p></div>

  <?php } ?>

</div>

<?php get_footer(); ?>

