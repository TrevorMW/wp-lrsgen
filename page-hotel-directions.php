<?php
/**
 *
 * @package WordPress
 * @subpackage themename
 */

global $page_type, $current_hotel;

get_header();

$map_data = array( 'lat'  => $current_hotel->hotel_latitude,
                   'lng'  => $current_hotel->hotel_longitude,
                   'zoom' => 12 );

if( is_object( $current_hotel ) ) { ?>

  <div class="wrapper hotel-directions">
    <div class="container table" style="height:inherit;">
      <div class="table-cell half directions">
        <form data-ajax-form data-action="get_hotel_directions_data">
          <fieldset class="panel">
            <div class="panel-header"><legend>Direction Addresses</legend></div>
            <div class="panel-content">
              <ul>
                <li class="full">
                  <label>Starting Address:</label>
                  <input type="text" name="hotel_directions[directions_start]" value="<?php echo $current_hotel->hotel_address ?>" readonly />
                </li>

                <li class="full">
                  <label>End Address:</label>
                  <input type="text" name="hotel_directions[directions_end]" value="" />
                </li>

                <li class="submit">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-spin fa-spinner" data-progress></i> &nbsp;Get Directions</button>
                </li>
              </ul>
            </div>
          </fieldset>
        </form>

        <div class="panel">
          <div class="table panel-header">
            <div class="table-cell"><h3>Directions</h3></div>
            <div class="table-cell panel-button"><a href="#" data-print="#printable-content" disabled><i class="fa fa-print"></i></a></div>
          </div>
          <div class="panel-content" id="printable-content">

          </div>
        </div>
      </div>
      <div class="table-cell half location-map">
        <div data-map-canvas data-options='<?php echo json_encode( $map_data );?>' style="height:75vh;"></div>
      </div>
    </div>
  </div>

<?php } else { ?>




<?php } get_footer(); ?>
