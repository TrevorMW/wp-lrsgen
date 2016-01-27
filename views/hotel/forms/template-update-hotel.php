<?php global $current_hotel;

if( is_object( $current_hotel ) )
{
  $form_mode = 'edit';
  $action    = 'edit_hotel';
  $btn       = 'Update '.ucfirst( $current_hotel->hotel_name );

  $hotel = $current_hotel;
}
else
{
  $form_mode = 'add';
  $action    = 'add_hotel';
  $btn       = 'Create New Hotel';

  $hotel = new Hotel();
}

$types = $hotel::get_hotel_category_array(); ?>

<form data-ajax-form data-action="<?php echo $action ?>">
  <div data-form-msg></div>

  <div class="col two-third">

    <fieldset class="panel floating">
      <div class="panel-header"><legend>Basic Hotel Details</legend></div>
      <div class="panel-content">
        <ul>
          <li class="half">
            <label>Hotel Name:</label>
            <input type="text" name="hotel[hotel_name]" value="<?php echo $hotel->hotel_name; ?>" placeholder="Hotel Name" />
          </li>

          <li class="half right">
            <label>Hotel Type:</label>
            <select name="hotel[hotel_type]">
              <?php echo Form_Helper::options_for_select( $types, $hotel->hotel_type );?>
            </select>
          </li>

          <li class="full">
            <label>Hotel Email:</label>
            <input type="text" name="hotel[hotel_email_address]" value="<?php echo $hotel->hotel_email_address; ?>" placeholder="Hotel Email" />
          </li>

          <li class="third">
            <label>Hotel Phone Number:</label>
            <input type="text" name="hotel[hotel_phone_number]" value="<?php echo $hotel->hotel_phone_number; ?>" placeholder="Hotel Phone #" />
          </li>

          <li class="third">
            <label>Hotel Manager:</label>
            <input type="text" name="hotel[hotel_manager]" value="<?php echo $hotel->hotel_manager; ?>" placeholder="Hotel Manager" />
          </li>

          <li class="third right">
            <label>Hotel Concierge:</label>
            <input type="text" name="hotel[hotel_concierge]" value="<?php echo $hotel->hotel_concierge; ?>" placeholder="Hotel Concierge" />
          </li>

          <li class="full">
            <label>Hotel Description:</label>
            <textarea type="text" name="hotel[hotel_description]" placeholder="Hotel Description"><?php echo $hotel->hotel_description ?></textarea>
          </li>
        </ul>
      </div>
    </fieldset>

    <fieldset class="panel floating">
      <div class="panel-header table">
        <div class="table-cell"><legend>Hotel Location Information</legend></div>
        <div class="table-cell panel-button"><a href="#" data-refesh-map><i class="fa fa-refresh"></i></a></div>
      </div>
      <div class="panel-content">
        <div class="col half">
          <div data-map-canvas data-lat="<?php echo $hotel->hotel_latitude; ?>" data-lng="<?php echo $hotel->hotel_longitude; ?>" data-drag="true" data-zoom="" class="floating"  style="height:350px;"></div>
        </div>

        <div class="col half right">
          <ul>
            <li class="full">
              <label>Hotel Address:</label>
              <input type="text" name="hotel[hotel_address]" data-ajax-field data-action="update_hotel_map" value="<?php echo $hotel->hotel_address; ?>" placeholder="Hotel Address" />
            </li>

            <li class="full">
              <label>Hotel Latitude:</label>
              <input type="text" data-update-lat="<?php echo $hotel->hotel_latitude; ?>" name="hotel[hotel_latitude]" value="<?php echo $hotel->hotel_latitude; ?>" />
            </li>

            <li class="full">
              <label>Hotel Longitude:</label>
              <input type="text" data-update-lng="<?php echo $hotel->hotel_longitude; ?>" name="hotel[hotel_longitude]" value="<?php echo $hotel->hotel_longitude; ?>" />
            </li>

          </ul>
        </div>
      </div>
    </fieldset>
  </div>

  <div class="col third right">
    <fieldset class="panel floating ">
      <div class="panel-header"><legend>Hotel Room Information</legend></div>
      <div class="panel-content">
        <ul>
          <?php echo get_hotel_room_types( $hotel ); ?>
        </ul>
      </div>
    </fieldset>

    <fieldset class="panel floating">
      <div class="panel-header"><legend>Hotel Policies</legend></div>
      <div class="panel-content">
        <ul>
          <li class="auto box striped floating" data-boolean-fee-parent>
            <div class="new-checkbox">
        			<label for="hotel-allows-pets">
        			  <input type="checkbox" id="hotel-allows-pets" data-boolean-fee name="hotel[hotel_pets]" <?php echo checked( $hotel->hotel_pets );?> />
        			  <div class="checkbox-parent"><div class="checkbox-child"></div></div>
        			  Hotel Allows Pets?
        			</label>
        		</div>
        		<div data-boolean-fee-input class="<?php $hotel->hotel_pets ? print 'active' : ''; ?>">
          		<input type="number" name="hotel[hotel_pet_fee]" value="<?php echo number_format(  $hotel->hotel_pet_fee, 2 ) ?>" />
        		</div>
          </li>

          <li class="auto box striped floating" data-boolean-fee-parent>
            <div class="new-checkbox">
        			<label for="hotel-allows-smoking">
        			  <input type="checkbox" id="hotel-allows-smoking" data-boolean-fee name="hotel[hotel_smoking]" <?php echo checked( $hotel->hotel_smoking );?> />
        			  <div class="checkbox-parent"><div class="checkbox-child"></div></div>
        			  Hotel Allows Smoking?
        			</label>
        		</div>
        		<div data-boolean-fee-input class="<?php $hotel->hotel_smoking ? print 'active' : ''; ?>">
          		<input type="number" name="hotel[hotel_smoking_fee]" value="<?php echo number_format( $hotel->hotel_smoking_fee, 2 ); ?>" />
        		</div>
          </li>

          <li class="auto box striped floating" data-boolean-fee-parent>
            <div class="new-checkbox">
        			<label for="hotel-has-parking">
        			  <input type="checkbox" id="hotel-has-parking" data-boolean-fee name="hotel[hotel_parking]" <?php echo checked( $hotel->hotel_parking );?> />
        			  <div class="checkbox-parent"><div class="checkbox-child"></div></div>
        			  Hotel Has Parking?
        			</label>
        		</div>
        		<div data-boolean-fee-input class="<?php $hotel->hotel_parking  ? print 'active' : ''; ?>">
          		<input type="number" name="hotel[hotel_parking_fee]" value="<?php echo number_format(  $hotel->hotel_parking_fee, 2 ) ?>" />
        		</div>
          </li>

        </ul>
      </div>
    </fieldset>
  </div>

  <fieldset class="submit">
    <ul>
      <li class="submit"><button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-spin fa-spinner" data-progress></i> &nbsp;<?php echo $btn; ?></button></li>
    </ul>
  </fieldset>

  <input type="hidden" name="hotel[hotel_id]" value="<?php echo $hotel->hotel_id ?>" />
</form>

