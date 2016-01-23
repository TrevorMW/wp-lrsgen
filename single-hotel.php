<?php
/**
 * @package WordPress
 * @subpackage themename
 */

get_header();

the_post();

$hotel = new Hotel( $post->ID ); //var_dump( $hotel );?>

  <div class="wrapper hotel-map" data-map-canvas data-lat="<?php echo $hotel->hotel_latitude; ?>" data-lng="<?php echo $hotel->hotel_longitude; ?>" data-zoom="18" style="border:0; border-bottom:1px solid #ccc;height:350px;"></div>

  <div class="wrapper white single-hotel-directions">
    <div class="container table">
      <div class="table-cell half">
        <?php echo $hotel->hotel_address; ?>
      </div>
      <div class="table-cell half">
        <a href="/hotel-directions?hotel_id=<?php echo $hotel->hotel_id ?>" class="btn btn-primary">Directions &nbsp;<i class="fa fa-arrow-right"></i></a>
      </div>
    </div>
  </div>

<?php get_footer(); ?>
