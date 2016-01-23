<?php
/**
 *
 * @package WordPress
 * @subpackage themename
 *
 */

global $page_type, $current_reservation;

get_header();

$hotel = new Hotel( (int) $current_reservation->reservation_hotel );?>

  <div class="wrapper reservation-sheet">
    <article class="container floating">

      <header class="align-center">
        <img src="http://" alt="" />
      </header>
      <section>

      </section>
      <footer class="table align-center">
        <div class="table-cell">
          <i class="fa fa-fw fa-home"></i> <?php echo $hotel->hotel_name; ?>
        </div>
        <div class="table-cell">
          <i class="fa fa-fw fa-phone"></i> <?php echo $hotel->hotel_phone_number; ?>
        </div>
        <div class="table-cell">
          <i class="fa fa-fw fa-phone"></i> <?php echo $hotel->hotel_phone_number; ?>
        </div>
        <div class="table-cell">

        </div>
      </footer>
    </article>
  </div>

<?php get_footer(); ?>
