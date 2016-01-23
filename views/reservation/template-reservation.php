<?php $html = '';

if( is_object( $reservation ) )
{
  $html .= '<li class="reservation-list-item">
              <div class="reservation-tools">
                <ul>
                  <li><a href="/edit-reservation?reservation_id='.$reservation->reservation_id.'"><i class="fa fa-edit"></i></a></li>
                  <li>
                    <form data-ajax-form data-action="delete_reservation">
                      <input type="hidden" name="reservation_id" value="'.$reservation->reservation_id.'" />
                      '.wp_nonce_field( 'delete_reservation', 'delete_reservation', false ).'
                      <button type="submit"><i class="fa fa-trash"></i></button>
                    </form>
                  </li>
                </ul>
              </div>
              <div class=""></div>
              <div class=""></div>
              <div class=""></div>
              <div class=""></div>
            </li>';
}

echo $html;