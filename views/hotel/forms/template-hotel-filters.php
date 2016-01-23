<?php $html = '';

if( is_array( $data ) && !empty( $data ) )
{
  $sort = array( 'ASC' => 'Ascending', 'DESC' => 'Descending' );

  $html .= '<form data-ajax-form data-action="filter_hotel_list" data-target="filter_hotels">';
  $html .= '<ul class="inline">';
  $html .= '<li><select name="hotel_sort" data-fireable-input>'.Form_Helper::options_for_select( $sort, null, null ).'</select></li>';
  $html .= '<li><select name="hotel_type" data-fireable-input>'.Form_Helper::options_for_select( $data['hotel_types'], null, 'Hotel Type' ).'</select></li>';
  $html .= '</ul>';
  $html .= '</form>';
}

echo $html;