<?php

class Dashboard
{

  public function init_actions()
  {
    add_action( 'wp_ajax_get_date_data', array( $this, 'get_date_data' ) );
    add_action( 'wp_ajax_nopriv_get_date_data', array( $this, 'get_date_data' ) );

    add_action( 'wp_ajax_filter_dashboard_dates', array( $this, 'filter_dashboard_dates' ) );
    add_action( 'wp_ajax_nopriv_filter_dashboard_dates', array( $this, 'filter_dashboard_dates' ) );
  }


  /////////////////////////////////////////
  ///////////// MAIN METHODS  /////////////
  ///////////// ///////////////////////////

  public function get_dashboard_data( $days = null )
  {
    $html = '';

    $new_dates = array();

    is_int( $days ) ? $end_date = $days : $end_date = 7 ;

    $daterange = new DatePeriod( new DateTime( '-'.$end_date.' days' ), new DateInterval('P1D'), new DateTime() );

    if( $daterange instanceOf DatePeriod )
    {
      foreach( $daterange as $k => $date )
      {
        $new_dates[] = $date;
      }

      foreach( array_reverse( $new_dates ) as $k => $date )
      {
        $data['date']       = $date;
        $data['date_title'] = $date->format("M dS, Y");
        $data['inc']        = $k;

        ob_start();

          get_template_part_with_data( 'dashboard', 'template', 'dashboard-date', $data );

          $html .= ob_get_contents();

        ob_get_clean();
      }

    }

    return $html;
  }


  /////////////////////////////////////////
  ///////////// AJAX METHODS  /////////////
  ///////////// ///////////////////////////

  public function get_date_data()
  {
    $data = $_POST;
    $resp = new ajax_response( $data['action'], true );

    if( $data['variable_data'] != null )
    {
      $resp->set_status( true );
      $resp->set_data( array( 'loadable_content' => 'etrghvaoucyq3boigwsodrifuv suiboqiu5oqiuebroqviebrivueybrgirbf' ) );

      if( is_time( $data['variable_data'] ) )
      {

      }
      else
      {

      }
    }
    else
    {
      $resp->set_message('Could not load data. Try Again.');
    }

    echo $resp->encode_response();
    die();
  }

  public function filter_dashboard_dates()
  {
    $data = $_POST;
    $dash = new Dashboard();
    $resp = new ajax_response( $data['action'], true );

    if( $data['dashboard_date_count'] != '' )
    {
      $resp->set_status( true );
      $resp->set_data( array( 'dashboard_dates' => $dash->get_dashboard_data( (int) $data['dashboard_date_count'] ) ) );
    }

    echo $resp->encode_response();
    die();
  }

}

$dash = new Dashboard();
$dash->init_actions();