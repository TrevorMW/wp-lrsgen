<?php $html = '';

if( is_array( $login ) )
{
  $html .= '<form data-ajax-form data-action="'.$login['action'].'" novalidate autocomplete="off"><div data-form-msg></div>';

    $html .= '<ul>';

      if( $login['mode'] == 'register' )
      {
        $html .= '<li class="two-third">';
          $html .= '<label>Email Address:</label>';
          $html .= '<input type="text" name="user_name" value="" />';
        $html .= '</li>';

        $html .= '<li class="third right">';
          $html .= '<label>Register Code:</label>';
          $html .= '<input type="text" name="sec_code" value="" />';
        $html .= '</li>';
      }
      else
      {
        $html .= '<li class="full">';
          $html .= '<label>Username:</label>';
          $html .= '<input type="text" name="user_name" value="" />';
        $html .= '</li>';
      }

      if( $login['mode'] == 'register' )
      {
        /*

        $html .= '<li class="half">';
          $html .= '<label>Password:</label>';
          $html .= '<input type="password" name="pass" value="" />';
        $html .= '</li>';

        $html .= '<li class="half right">';
          $html .= '<label>Retype Password:</label>';
          $html .= '<input type="password" name="pass_again" value="" />';
        $html .= '</li>';

        */
      }
      else
      {
        $html .= '<li class="full">';
          $html .= '<label>Password:</label>';
          $html .= '<input type="password" name="pass" value="" />';
        $html .= '</li>';
      }

      $html .= '<li class="submit">';
        $html .= '<button type="submit" class="btn btn-primary">'.$login['btn'].'</button>';
      $html .= '</li>';

    $html .= '</ul>';
  $html .= '</form>';
}


echo $html;