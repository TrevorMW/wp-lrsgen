<?php

class Form_Helper
{

  public static function options_for_select( $options = array(), $selected = null, $default_name = null )
  {
    $html = '';

    $default_name != null ? $html .= '<option value="">'.$default_name.'</option>' : '' ;

    if( !empty( $options ) )
    {
      foreach( $options as $k => $option )
      {
        $html .= '<option value="'.$k.'" '.selected( $selected, $k, $echo ).'>'.$option.'</option>';
      }
    }

    return $html;
  }


  public static function get_range_options( $min, $max, $step, $prefix, $suffix )
  {
    $options = array();

    $range = range( $min, $max, $step );

    foreach( $range as $k => $val )
    {
      $options[ $val ] = $prefix.number_format( $val ).$suffix;
    }

    return $options;
  }


  public static function cc_exp_months()
  {
    $options = array();

    $months = range( 1, 12 );

    if( !empty( $months ) )
    {
      foreach( $months as $k => $val )
      {
        $options[ $val ] = $val;
      }
    }

    return self::options_for_select( $options, '', 'Exp. Month' );
  }

  public static function cc_exp_year()
  {
    $options = array();

    $years = range( date('Y'), ( date('Y') + 10 ) );

    if( !empty( $years ) )
    {
      foreach( $years as $k => $val )
      {
        $options[ $val ] = $val;
      }
    }

    return self::options_for_select( $options, '', 'Exp. Year'  );
  }
}