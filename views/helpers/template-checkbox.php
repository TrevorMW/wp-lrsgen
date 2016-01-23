<?php $html = '';

if( !empty( $checkbox ) )
{
  $html .= '<li class="auto box striped floating">
              <div class="new-checkbox">
          			<label for="'.sanitize_title_with_dashes( $checkbox ).'" >
          			  <input type="checkbox" '.$selected.' id="'.sanitize_title_with_dashes( $checkbox ).'" name="'.$field_id.'" value="'.sanitize_title_with_dashes( $checkbox ).'" />
          			  <div class="checkbox-parent"><div class="checkbox-child"></div></div>
          			  '.ucfirst( $checkbox ).'
          			</label>
          		</div>
            </li>';
}

echo $html;

