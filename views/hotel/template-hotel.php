<?php $html = '';

if( is_object( $hotel ) )
{
  $img     = get_the_post_thumbnail( $hotel->hotel_id, 'small');
  $address = get_field( 'hotel_address', $hotel->hotel_id );

  $tools = $hotel->hotel_tools;

  $html .= '<li class="hotel floating" data-hotel-id="'.$hotel->hotel_name.'">';
    $html .= '<div class="wrapper hotel-inner">';


      $html .= '<div class="wrapper hotel-img"><a href="'.get_permalink( $hotel->hotel_id ).'">'.$img.'</a></div>';


      $html .= '<div class="wrapper hotel-content">';

        $html .= '<header class="hotel-header">';
        $html .= '<h1><a href="'.get_permalink( $hotel->hotel_id  ).'">'.$hotel->hotel_name.'</a></h1>';
        $html .= '</header>';

        $html .= '<div class="wrapper hotel-details">
                  '.$address.'
                  </div>';

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