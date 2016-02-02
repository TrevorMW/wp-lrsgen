<?php $html = '';

if( $hotel instanceOf Hotel )
{
  $html .= '<tr>
              <td>'.$hotel->hotel_name.'</td>
              <td contenteditable="true" data-target="hotel_phone_number">'.$hotel->hotel_phone_number.'</td>
              <td contenteditable="true" data-target="hotel_email_address">'.$hotel->hotel_email_address.'</td>
              <td contenteditable="true" data-target="hotel_concierge">'.$hotel->hotel_concierge.'</td>
              <td contenteditable="true" data-target="hotel_rates">'.$hotel->hotel_rate_data.'</td>
              <td style="display:none;">
                <form data-ajax-form data-action="update_rates">
                  <input type="hidden" name="hotel_phone_number" value="'.$hotel->hotel_phone_number.'">
                  <input type="hidden" name="hotel_email_address" value="'.$hotel->hotel_email_address.'">
                  <input type="hidden" name="hotel_rates" value="'.$hotel->hotel_rate_data.'">
                  <input type="hidden" name="hotel_concierge" value="'.$hotel->hotel_concierge.'">
                  <input type="hidden" name="hotel_id" value="'.$hotel->hotel_id.'" >
                </form>
              </td>
            </tr>';
}

echo $html;