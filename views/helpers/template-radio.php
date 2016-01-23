<?php $html = '';

if( !empty( $radio ) )
{
  $html .= '<li class="full box striped floating">
              <div class="new-radio">
          			<label for="'.sanitize_title_with_dashes( $radio ).'" >
          			  <input type="radio" id="'.sanitize_title_with_dashes( $radio ).'" name="'.$field_id.'" value="'.$radio.'" />
          			  <div class="radio-parent"><div class="radio-child"></div></div>
          			  '.$radio.'
          			</label>
          		</div>
            </li>';
}

echo $html;

