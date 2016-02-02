<?php

class Rates
{
  public $rate_hotel;

  public function __construct( $id = null )
  {
    if( is_int( $id ) )
      $post = get_post( $id );

    if( $post instanceOf WP_Post )
    {
      $this->rate_hotel = $post;
    }
  }

  public function init_actions()
  {
    add_action( 'wp_ajax_load_rates_async', array( $this, 'load_rates_async' ) );
    add_action( 'wp_ajax_nopriv_load_rates_async', array( $this, 'load_rates_async' ) );

    add_action( 'wp_ajax_update_rates', array( $this, 'update_rates' ) );
    add_action( 'wp_ajax_nopriv_update_rates', array( $this, 'update_rates' ) );

    add_action( 'rates_content', array( $this, 'build_rates_page' ), 10 );
  }


  /////////////////////////////////////////
  ///////////// MAIN METHODS  /////////////
  ///////////// ///////////////////////////

  public function build_rates_page()
  {
    $html = '';

    $html .= '<div class="wrapper hotel-rates">
                <table>
                  <thead>
                    <th style="width:205px;">Hotel Name</th>
                    <th style="width:155px;">Phone</th>
                    <th style="width:300px;">Email</th>
                    <th style="width:155px;">Manager</th>
                    <th>Rates for '.date('m-d-Y').'</th>
                  </thead>
                  <tbody data-loadable-content="load_rates_async" data-load-when="now" data-updateable-content="rates_data">
                  </tbody>
                </table>
              </div>';

    echo $html;
  }

  public function get_all_rates()
  {
    $html = '';

    $loop = new WP_Query( array( 'post_type' => 'hotel', 'posts_per_page' => '-1', 'orderby' => 'post_title', 'order' => 'ASC' ) );

    if( $loop->have_posts() )
    {
      while( $loop->have_posts() ) : $loop->the_post();

        $data['hotel'] = new Hotel( $loop->post->ID );

        ob_start();

          get_template_part_with_data( 'rates', 'template', 'hotel-rate', $data );

          $html .= ob_get_contents();

        ob_get_clean();

      endwhile;
    }

    return $html;
  }

  /////////////////////////////////////////
  ///////////// AJAX METHODS  /////////////
  ///////////// ///////////////////////////

  public function load_rates_async()
  {
    $data  = $_POST;
    $resp  = new ajax_response( $data['action'], true );

    $resp->set_status( true );
    $resp->set_data( array( 'loadable_content' => $this->get_all_rates() ) );

    echo $resp->encode_response();
    die();
  }

  public function update_rates()
  {
    $data = $_POST;
    $resp = new ajax_response( $data['action'], true );

    if( $data['hotel_id'] != null )
    {
      $hotel = new Hotel( (int) $data['hotel_id'] );

      if( $hotel instanceOf Hotel )
      {
        $result = $hotel->save_rate_data( $data );
        $result ? $resp->set_status( true ) : '' ;
      }
    }

    echo $resp->encode_response();
    die();
  }
}

$rates = new Rates();
$rates->init_actions();