<?php

class Reservation extends WP_ACF_CPT
{
  public $reservation_id;
  public $reservation_date;
  public $reservation_hotel;
  public $reservation_concierge;
  public $reservation_manager;
  public $reservation_made_by;

  public $reservation_guest_first_name;
  public $reservation_guest_last_name;
  public $reservation_guest_address;
  public $reservation_guest_city;
  public $reservation_guest_country;
  public $reservation_guest_postal_code;
  public $reservation_guest_email;
  public $reservation_guest_phone;

  public $reservation_guest_credit_card_number;
  public $reservation_guest_credit_card_expiration_month;
  public $reservation_guest_credit_card_expiration_year;
  public $reservation_guest_credit_card_security_code;

  public $reservation_guests;
  public $reservation_rooms;
  public $reservation_nights;

  public $reservation_hotel_rate_per_night;
  public $reservation_sub_total;
  public $reservation_fee_total;
  public $reservation_grand_total;

  public $reservation_tools = array(
    array(
      'id'       => 'edit-reservation',
      'endpoint' => '/edit-reservation',
      'title'    => 'Edit ',
      'icon'     => 'fa-edit'
    ),
    array(
      'id'       => 'rates',
      'endpoint' => '/rates',
      'title'    => 'Get rates for ',
      'icon'     => 'fa-list'
    ),
    array(
      'id'       => 'print',
      'endpoint' => '#',
      'title'    => 'Print Reservation',
      'icon'     => 'fa-print'
    )
  );

  public $reservation_generated_tools = array(
    array(
      'id'       => 'print-reservation',
      'endpoint' => '#',
      'title'    => 'Edit ',
      'icon'     => 'fa-print'
    ),
    array(
      'id'       => 'email',
      'endpoint' => '#',
      'title'    => 'Get rates for ',
      'icon'     => 'fa-envelope'
    ),
    array(
      'id'       => 'edit-reservation',
      'endpoint' => '/edit-reservation',
      'title'    => 'Print Reservation',
      'icon'     => 'fa-edit'
    )
  );


  public function __construct( $id = null, $hotel_id = null )
  {
    if( is_int( $id ) )
    {
      parent::__construct( $id );
      $resrv = get_post( $id );
      $hotel = new Hotel( $hotel_id );

      if( is_object( $resrv ) )
        $this->reservation_id = $resrv->ID;
    }
  }


  public function init_actions()
  {
    add_action('wp_ajax_new_reservation', array( $this, 'new_reservation' ) );
    add_action('wp_ajax_nopriv_new_reservation', array( $this, 'new_reservation' ) );

    add_action('wp_ajax_edit_reservation', array( $this, 'edit_reservation' ) );
    add_action('wp_ajax_nopriv_edit_reservation', array( $this, 'edit_reservation' ) );

    add_action('wp_ajax_delete_reservation', array( $this, 'delete_reservation' ) );
    add_action('wp_ajax_nopriv_delete_reservation', array( $this, 'delete_reservation' ) );

    add_action( 'reservations_content', array( $this, 'get_all_reservations'), 10 );
  }


  /////////////////////////////////////////
  ///////////// MAIN METHODS  /////////////
  ///////////// ///////////////////////////

  public function get_all_reservations()
  {
    $html = '';

    $posts = get_posts_array( array( 'post_type' => 'reservation', 'posts_per_page' => '-1', 'orderby' => 'post_date', 'order' => 'DESC' ) );

    if( is_array( $posts) && !empty( $posts ) )
    {
      $html .= '<div class="wrapper reservation-list"><ul>';

      foreach( $posts as $k => $post )
      {
        $data['reservation'] = new Reservation( $k );

        ob_start();

          get_template_part_with_data( 'reservation', 'template', 'reservation', $data );

          $html .= ob_get_contents();

        ob_get_clean();
      }

      $html .= '</ul></div>';
    }

    echo $html;
  }

  public function get_reservation_address()
  {
    return $this->reservation_guest_address.', '.$this->reservation_guest_city.', '.$this->reservation_guest_country.' '.$this->reservation_guest_postal_code;
  }

  public function get_guest_full_name()
  {
    return $this->reservation_guest_first_name.' '.$this->reservation_guest_last_name;
  }

  /////////////////////////////////////////
  ///////////// AJAX METHODS  /////////////
  ///////////// ///////////////////////////

  public function new_reservation()
  {
    $data = $_POST;
    $resp = new ajax_response( $data['action'] );


    echo $resp->encode_response();
    die();
  }

  public function edit_reservation()
  {
    $data = $_POST;
    $resp = new ajax_response( $data['action'] );


    echo $resp->encode_response();
    die();
  }

  public function delete_reservation()
  {
    $data = $_POST;
    $resp = new ajax_response( $data['action'] );

    check_ajax_referer( 'delete_reservation' );


    echo $resp->encode_response();
    die();
  }
}

$reservation = new Reservation();
$reservation->init_actions();