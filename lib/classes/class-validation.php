<?php

class validator
{
  public $filter_schemes = array( 'text'     => '',
                                  'email'    => '',
                                  'textarea' => '',
                                  'number'   => '',
                                  'phone'    => '' );

  public static function filter_data( $data, $filter = null, $type = null )
  {
    $filter_const = '';

    $type != null ? $filter_const = type : $filter_const = $filter ;

    return filter_var( trim( $data ), $filter_const );
  }
}
