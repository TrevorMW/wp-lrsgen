<?php

class Dashboard
{

  public function init_actions()
  {
    //add_action( '', array( $this, '' ) );
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


}

$dash = new Dashboard();
$dash->init_actions();