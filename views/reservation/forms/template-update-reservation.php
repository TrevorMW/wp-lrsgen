<?php global $current_hotel, $current_reservation;

$btn = '';

$user   = wp_get_current_user();
$reserv = $current_reservation;

is_object( $current_hotel ) ? $hotel = $current_hotel : $hotel = new Hotel( (int) $current_reservation->reservation_hotel );

$reservation['mode'] == 'edit_reservation' ? $btn = 'Update Reservation' : $btn = 'Create Reservation'; ?>

<form data-ajax-form data-action="<?php echo $reservation['mode'];?>">

  <?php if( is_object( $hotel ) ) { ?>
    <input type="hidden" name="reservation[hotel_id]" value="<?php echo $hotel->hotel_id; ?>" />
  <?php } else { ?>
    <fieldset class="floating panel">
      <div class="panel-header"><legend>Reservation Hotel</legend></div>
      <div class="panel-content">
        <ul>
          <li class="full">
            <label>Reservation Hotel:</label>
            <select data-reservation-choose-hotel name="reservation[chosen_hotel]">
              <?php echo $hotel->all_hotels_options(); ?>
            </select>
          </li>
        </ul>
      </div>
    </fieldset>
  <?php } ?>

  <div class="col two-third">
    <fieldset class="floating panel">
      <div class="panel-header"><legend>Guest Details</legend></div>
      <div class="panel-content">
        <ul>

          <li class="<?php if( is_object( $hotel ) ) { ?>half<?php } else { ?> full<?php } ?>">
            <label>Guest First Name:</label>
            <input type="text"
                   name="reservation[guest_first_name]"
                   value="<?php echo $reserv->reservation_guest_first_name ;?>"
                   placeholder="Guest's First Name" required/>
          </li>

          <li class="half right">
            <label>Guest Last Name:</label>
            <input type="text"
                   name="reservation[guest_last_name]"
                   value="<?php echo $reserv->reservation_guest_last_name ;?>"
                   placeholder="Guest's Last Name" required/>
          </li>

          <li class="full clear">
            <label>Guest Address:</label>
            <input type="text"
                   name="reservation[guest_address]"
                   value="<?php echo $reserv->reservation_guest_address ;?>"
                   placeholder="Guest's Street Address" required/>
          </li>

          <li class="third">
            <label>Guest Country:</label>
            <input type="text"
                   name="reservation[guest_country]"
                   value="<?php echo $reserv->reservation_guest_country ;?>"
                   placeholder="Guest's Country" required/>
          </li>

          <li class="third">
            <label>Guest City:</label>
            <input type="text"
                   name="reservation[guest_city]"
                   value="<?php echo $reserv->reservation_guest_city ;?>"
                   placeholder="Guest's City" required/>
          </li>

          <li class="third right">
            <label>Guest Postal Code:</label>
            <input type="text"
                   name="reservation[guest_postal_code]"
                   value="<?php echo $reserv->reservation_guest_postal_code ;?>"
                   placeholder="Guest's Postal Code" required/>
          </li>

          <li class="half">
            <label>Guest Email:</label>
            <input type="email"
                   name="reservation[guest_email]"
                   value="<?php echo $reserv-> reservation_guest_email;?>"
                   placeholder="Guest's Email" />
          </li>

          <li class="half right">
            <label>Guest Phone Number:</label>
            <input type="text"
                   name="reservation[guest_phone_number]"
                   value="<?php echo $reserv->reservation_guest_phone ;?>"
                   placeholder="Guest's Phone Number" required/>
          </li>
        </ul>
      </div>
    </fieldset>

    <fieldset class="floating panel">
      <div class="panel-header"><legend>Guest Stay Details</legend></div>
      <div class="panel-content">
        <ul>
          <li class="third">
            <label># of Nights:</label>
            <input type="nuber"
                   name="reservation[nights]"
                   value="<?php echo $reserv->reservation_nights ;?>"  />
          </li>

          <li class="third ">
            <label># of Rooms:</label>
            <input type="nuber"
                   name="reservation[rooms]"
                   value="<?php echo $reserv->reservation_rooms ;?>"  />
          </li>

          <li class="third right">
            <label># of Guests:</label>
            <input type="nuber"
                   name="reservation[guests]"
                   value="<?php echo $reserv->reservation_guests ;?>"  />
          </li>
        </ul>
      </div>
    </fieldset>
  </div>

  <div class="col third right ">
    <fieldset class="floating panel">
      <div class="panel-header"><legend>Guest Credit Card Details</legend></div>
      <div class="panel-content">
        <ul>
          <li class="full">
            <label>Guest Credit Card:</label>
            <div data-cc-input>
              <input type="text"
                     name="reservation[guest_credit_card_number]"
                     data-validate-cc-card=""
                     value="<?php echo $reserv->reservation_guest_credit_card_number ;?>"
                     placeholder="XXXX XXXX XXXX XXXX" />
              <span data-card-type></span>
            </div>
          </li>

          <li class="third ">
            <label>CC Exp:</label>
            <select name="reservation[guest_credit_card_expiration_month]">
              <?php echo Form_Helper::cc_exp_months( $reserv->reservation_guest_credit_card_expiration_month );?>
            </select>
          </li>

          <li class="third ">
            <label>CC Exp:</label>
            <select name="reservation[guest_credit_card_expiration_year]">
              <?php echo Form_Helper::cc_exp_year( $reserv->reservation_guest_credit_card_expiration_year );?>
            </select>
          </li>

          <li class="third right">
            <label>Security Code:</label>
            <input type="text"
                   name="reservation[guest_credit_card_number_security_code]"
                   value="<?php echo $reserv->reservation_guest_credit_card_security_code ?>" />
          </li>
        </ul>
      </div>
    </fieldset>

  </div>

  <fieldset class="submit">
    <ul>
      <li class="submit"><button type="submit" class="btn btn-primary"><?php echo $btn; ?></button></li>
    </ul>
  </fieldset>

  <?php if( is_object( $current_hotel ) ) { ?>
    <input type="hidden"
           name="reservation[hotel_manager]"
           value="<?php echo $current_hotel->hotel_manager;?>" />

    <input type="hidden"
           name="reservation[hotel_concierge]"
           value="<?php echo $current_hotel->hotel_concierge;?>" />
  <?php } ?>

  <input type="hidden"
         name="reservation[made_by]"
         value="<?php echo $user->display_name; ?>" />
</form>