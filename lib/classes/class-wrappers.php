<?php

class Wrappers
{

  public static function page_forms_wrapper( $content = null )
  {
    $html = '';

    if( $content != null )
    {
      $html .= '<div class="wrapper page-forms"> '.$content.'</div>';
    }

    return $html;
  }

}