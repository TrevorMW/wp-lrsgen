<?php

class Dashboard
{

  public function init_actions()
  {
    add_action( 'wp_ajax_get_date_data', array( $this, 'get_date_data' ) );
    add_action( 'wp_ajax_nopriv_get_date_data', array( $this, 'get_date_data' ) );
  }


  /////////////////////////////////////////
  ///////////// MAIN METHODS  /////////////
  ///////////// ///////////////////////////

  public function get_dashboard_data()
  {
    $html = '';

    $daterange = new DatePeriod( new DateTime( '-7 days' ), new DateInterval('P1D'), new DateTime() );

    if( $daterange instanceOf DatePeriod )
    {
      foreach( $daterange as $k => $date )
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

    $resp->set_status( true );
    $resp->set_data( array( 'loadable_content' => 'etrghvaoucyq3boigwsodrifuv suiboqiu5oqiuebroqviebrivueybrgirbf') );

    echo $resp->encode_response();
    die();
  }

}

$dash = new Dashboard();
$dash->init_actions();