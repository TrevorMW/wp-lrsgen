<?php $html = '';

if( is_object( $reservation ) )
{
  $html .= '<li class="table fixed floating">
              <div class="table-cell reservation-tools">
                <ul>
                  <li>
                    <form data-ajax-form data-action="delete_reservation">
                      <input type="hidden" name="reservation_id" value="'.$reservation->reservation_id.'" />
                      <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-trash"></i></button>
                    </form>
                  </li>
                  <li><a href="/edit-reservation?reservation_id='.$reservation->reservation_id.'" ><i class="fa fa-fw fa-edit"></i></a></li>
                </ul>
              </div>
              <div class="table-cell reservation-id">
                <a href="/view-reservation?reservation_id='.$reservation->reservation_id.'">
                  '.$reservation->get_guest_full_name().' - '.$reservation->get_reservation_address().'
                </a>
              </div>
            </li>';
}

echo $html;