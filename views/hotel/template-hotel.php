<?php $html = '';

if( is_object( $hotel ) )
{
  $img     = get_the_post_thumbnail( $hotel->hotel_id, 'small');

  $tools = $hotel->hotel_tools;

  $html .= '<li class="hotel floating" data-hotel-id="'.$hotel->hotel_name.'">';
    $html .= '<div class="wrapper hotel-inner">';


      $html .= '<div class="wrapper hotel-img"><a href="'.get_permalink( $hotel->hotel_id ).'">'.$img.'</a></div>';


      $html .= '<div class="wrapper hotel-content">';

        $html .= '<header class="hotel-header">';
        $html .= '<h1><a href="'.get_permalink( $hotel->hotel_id  ).'">'.$hotel->hotel_name.'</a></h1>';
        $html .= '</header>';

        $html .= '<ul class="hotel-details">';

          $html .= '<li class="hotel-detail hotel-address">
                    '.$hotel->hotel_address.'
                    </li>';

          $html .= '<li class="hotel-detail hotel-email">
                      <a href="mailto:'.$hotel->hotel_email_address.'">'.$hotel->hotel_email_address.'</a>
                    </li>';

          $html .= '<li class="hotel-detail hotel-phone">
                      <a href="tel:'.$hotel->hotel_phone_number.'">'.$hotel->hotel_phone_number.'</a>
                    </li>';

        $html .= '</ul>';

      $html .= '</div>';


      $html .= '<div class="wrapper table mini-toolbar hotel-toolbar">';

        if( count( $tools ) > 1 )
        {
          foreach( $tools as $tool )
          {
            $html .= '<div class="table-cell"><a href="'.$tool['endpoint'].'?hotel_id='.$hotel->hotel_id.'" title="'.$tool['title'].$hotel->hotel_name.'"><i class="fa fa-fw '.$tool['icon'].'"></i></a></div>';
          }
        }

        $html .= '<div class="table-cell">
                    <form data-ajax-form data-action="delete_hotel" data-confirm="Do you really want to delete the '.$hotel->hotel_name.'? This cannot be undone.">
                      <input type="hidden" name="hotel_id" value="'.$hotel->hotel_id.'" />
                      <button type="submit" title="Delete '.$hotel->hotel_name.'"><i class="fa fa-fw fa-spinner fa-spin" data-progress></i><i class="fa fa-fw fa-trash"></i></button>
                    </form>
                  </div>';

      $html .= '</div>';


    $html .= '</div>';
  $html .= '</li>';
}

echo $html;