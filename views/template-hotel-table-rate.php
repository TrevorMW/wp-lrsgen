<?php $html = '';

if( is_object( $hotel ) )
{
  $phone     = get_field( 'hotel_phone_number',   $hotel->ID );
  $email     = get_field( 'hotel_email_address',  $hotel->ID );
  $concierge = get_field( 'hotel_concierge',      $hotel->ID );
  $manager   = get_field( 'hotel_manager',        $hotel->ID );

  $html .= '<tr>';

  $html .= '<td class="hotel-row-action" data-hotel-row-action  tabindex="1"><input type="checkbox" name="hotel_booked" /></td>';
  $html .= '<td>'.$hotel->post_title.'</td>';
  $html .= '<td>'.$phone.'</td>';
  $html .= '<td contentEditable="true" data-edit-id="hotel_concierge" tabindex="2">'.$concierge.'</td>';
  $html .= '<td contentEditable="true" data-edit-id="hotel_manager"  tabindex="3">'.$manager.'</td>';
  $html .= '<td contentEditable="true" data-edit-id="hotel_rates"  tabindex="4">'.$rates.'</td>';

  $html .= '<form data-editable-row data-action="save_hotel_row">
            <input type="hidden" name="hotel_concierge" value="'.$concierge.'"/>
            <input type="hidden" name="hotel_manager" value="'.$manager.'"/>
            <input type="hidden" name="hotel_rates" value="'.$rates.'"/>
            <input type="hidden" name="hotel_id" value="'.$hotel->ID.'" />
            </form>
            </tr>';

}

echo $html;