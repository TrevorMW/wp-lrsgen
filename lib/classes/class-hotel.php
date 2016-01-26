<?php

class Hotel extends WP_ACF_CPT
{
  public $hotel_id;
  public $hotel_name;
  public $hotel_slug;
  public $hotel_type;
  public $hotel_type_id;
  public $hotel_description;
  public $hotel_phone_number;
  public $hotel_email_address;
  public $hotel_concierge;
  public $hotel_manager;
  public $hotel_address;
  public $hotel_latitude;
  public $hotel_longitude;
  public $hotel_room_types;
  public $hotel_pets;
  public $hotel_pet_fee;
  public $hotel_smoking;
  public $hotel_smoking_fee;
  public $hotel_parking;
  public $hotel_parking_fee;
  public $hotel_categories;

  public $hotel_all_room_types = array( 'king' => 'King', 'queen' => 'Queen', 'double-queen' => 'Double Queen', 'double-double' => 'Double Double' );
  public $hotel_tools = array(
    array(
      'id'       => 'new-reservation',
      'endpoint' => '/new-reservation',
      'title'    => 'New Reservation for ',
      'icon'     => 'fa-file',
      'allowed'  => array( '','' )
    ),
    array(
      'id'       => 'edit-hotel',
      'endpoint' => '/edit-hotel',
      'title'    => 'Edit ',
      'icon'     => 'fa-edit',
      'allowed'  => array( '','' )
    ),
    array(
      'id'       => 'hotel-directions',
      'endpoint' => '/hotel-directions',
      'title'    => 'Get Directions for ',
      'icon'     => 'fa-map',
      'allowed'  => array( '','' )
    ),
    array(
      'id'       => 'rates',
      'endpoint' => '/rates',
      'title'    => 'Get rates for ',
      'icon'     => 'fa-list',
      'allowed'  => array( '', '' )
    )
  );

  public function __construct( $id = null  )
  {
    if( is_int( $id ) )
    {
      parent::__construct( $id );

      $hotel = get_post( $id );

      if( is_object( $hotel ) )
      {
        $terms = wp_get_post_terms( $hotel->ID, 'hotel-category' );

        $this->hotel_id          = $hotel->ID;
        $this->hotel_name        = $hotel->post_title;
        $this->hotel_slug        = $hotel->post_name;
        $this->hotel_description = $hotel->post_content;

        $this->hotel_type        = $terms[0]->name;
        $this->hotel_type_id     = $terms[0]->term_id;
      }
    }
  }

  public function init_actions()
  {
    add_action('wp_ajax_add_hotel', array( $this, 'add_hotel' ) );
    add_action('wp_ajax_nopriv_add_hotel', array( $this, 'add_hotel' ) );

    add_action('wp_ajax_edit_hotel', array( $this, 'edit_hotel' ) );
    add_action('wp_ajax_nopriv_edit_hotel', array( $this, 'edit_hotel' ) );

    add_action('wp_ajax_delete_hotel', array( $this, 'delete_hotel' ) );
    add_action('wp_ajax_nopriv_delete_hotel', array( $this, 'delete_hotel' ) );

    add_action('wp_ajax_update_hotel_map', array( $this, 'update_hotel_map' ));
    add_action('wp_ajax_nopriv_update_hotel_map', array( $this, 'update_hotel_map' ) );

    add_action('wp_ajax_filter_hotel_list', array( $this, 'filter_hotel_list' ));
    add_action('wp_ajax_nopriv_filter_hotel_list', array( $this, 'filter_hotel_list' ) );

    add_action( 'hotels_page_forms', array( $this, 'get_hotel_filters_form' ), 10 );
    add_action( 'hotels_page_tools', array( $this, 'get_hotel_actions' ), 10 );
  }


  /////////////////////////////////////////
  ///////////// MAIN METHODS  /////////////
  ///////////// ///////////////////////////

  public function update_hotel( array $data, $id = null, $path = null )
  {
    global $wpdb;

    $result = '';

    // IF ID, ASSUME EDITING IS TAKING PLACE
    if( $id != null )
    {
      $args = array(
        'ID'           => (int) $id,
        'post_title'   => $wpdb->escape( $data['hotel_name'] ),
        'post_name'    => sanitize_title_with_dashes( $wpdb->escape( $data['hotel_name'] ) ),
        'post_content' => $wpdb->escape( $data['hotel_description'] ),
        'post_type'    => 'hotel'
      );

      $tax_result = wp_set_object_terms( (int) $id, $data['hotel_type'], 'hotel-category', false );
      $result     = wp_update_post( $args, true );
      $this->update_hotel_meta( $data, $result );
    }
    else
    {
      $args = array(
        'ID'           => '',
        'post_title'   => $wpdb->escape( $data['hotel_name'] ),
        'post_name'    => sanitize_title_with_dashes( $wpdb->escape( $data['hotel_name'] ) ),
        'post_content' => $wpdb->escape( $data['hotel_description'] ),
        'post_status'  => 'publish',
        'post_type'    => 'hotel'
      );

      $result = wp_insert_post( $args );

      if( is_int( $result ) )
      {
        $tax_result = wp_set_object_terms( $result, $data['hotel']['hotel_type'], 'hotel-category', false );
        $this->add_hotel_meta( $data, $result );
      }
    }

    return $result;
  }

  public function update_hotel_meta( $data, $id )
  {
    if( is_int( $id ) )
    {
      $fields = get_field_objects( $id );

      if( $fields )
      {
        $meta_fields = array();

        foreach( $fields as $k => $field )
          $meta_fields[ $k ] = $field['key'];

        foreach( $data as $field_id => $val )
        {
          if( array_key_exists( $field_id, $meta_fields ) )
          {
            update_field( $meta_fields[$field_id], $val, $id );
          }
        }
      }
    }
  }

  public function add_hotel_meta( $data, $id )
  {
    global $wpdb;

    if( is_array( $data ) && is_int( $id ) )
    {
      update_field( 'field_561bc5fd21500', $wpdb->escape( $data['hotel_phone_number'] ),  $id ); //phone number
      update_field( 'field_561bc61721501', $wpdb->escape( $data['hotel_email_address'] ), $id ); //email
      update_field( 'field_561bc62721502', $wpdb->escape( $data['hotel_concierge'] ),     $id ); //concierge
      update_field( 'field_561bc63b21503', $wpdb->escape( $data['hotel_manager'] ) ,      $id ); //manager
      update_field( 'field_561bc64d21504', $wpdb->escape( $data['hotel_address'] ) ,      $id ); //address
      update_field( 'field_561bc65921505', $wpdb->escape( $data['hotel_latitude'] ) ,     $id ); //lat
      update_field( 'field_561bc66821506', $wpdb->escape( $data['hotel_longitude'] ) ,    $id ); //lng
      update_field( 'field_561bc6a73476a', $wpdb->escape( $data['hotel_room_types'] ) ,   $id ); //room types
      update_field( 'field_561bc6fb3476c', $wpdb->escape( $data['hotel_pets'] ) ,         $id ); //pets?
      update_field( 'field_561bc7693476f', $wpdb->escape( $data['hotel_pet_fee'] ) ,      $id ); //pet fee
      update_field( 'field_561bc7133476d', $wpdb->escape( $data['hotel_smoking'] ) ,      $id ); //smoking?
      update_field( 'field_561bc77e34770', $wpdb->escape( $data['hotel_smoking_fee'] ) ,  $id ); //smoking fee
      update_field( 'field_561bc73e3476e', $wpdb->escape( $data['hotel_parking'] ) ,      $id ); //parking?
      update_field( 'field_561bc79034771', $wpdb->escape( $data['hotel_parking_fee'] ) ,  $id ); //parking fee
    }
  }

  public function all_hotels_options()
  {
    $hotels = array();

    $loop = new WP_Query( array( 'post_type' => 'hotel', 'posts_per_page' => '-1' ) );

    if( $loop->have_posts() )
    {
      while( $loop->have_posts() ) : $loop->the_post();

        $hotels[ $loop->post->ID ] = $loop->post->post_title;

      endwhile;
    }

    return Form_Helper::options_for_select( $hotels, '', 'Select a Hotel' );
  }

  public function get_hotel_filters_form()
  {
    $hotel_types = Hotel::get_hotel_category_array();

    $data['data']['hotel_types'] = $hotel_types;

    ob_start();

      get_template_part_with_data( 'hotel/forms','template', 'hotel-filters', $data );

      $html .= ob_get_contents();

    ob_get_clean();

    echo $html;
  }

  public function get_hotel_actions()
  {
    $html .= '<a href="/add-hotel" class="btn btn-primary"><i class="fa fa-fw fa-hotel"></i> New Hotel</a>&nbsp;&nbsp;';
    $html .= '<a href="/new-reservation" class="btn btn-primary"><i class="fa fa-fw fa-file"></i> New Reservation</a>';

    echo $html;
  }


  /////////////////////////////////////////
  ///////////// STATIC METHODS  ///////////
  ///////////// ///////////////////////////

  public static function get_hotel_category_array()
  {
    $terms = array();

    $term_list = get_terms( 'hotel-category', array( 'hide_empty' => false ) );

    if( !empty( $term_list ) )
    {
      foreach( $term_list as $term )
      {
        $terms[$term->term_id] = $term->name;
      }
    }

    return $terms;
  }

  public static function validate_hotel_data( $data )
  {
    $field_data = array();
    $fields     = get_field_objects( $data['hotel_id'] );

    if( !empty( $fields ) )
    {
      foreach( $fields as $k => $field )
      {
        $field_data[$k] = $field['type'];
      }
    }

    if( !empty( $data) )
    {
      foreach( $data as $k => $d )
      {
        if( $field_data[$k] != null )
        {

        }
        else
        {

        }
      }
    }

    return true;
  }

  public static function get_all_hotels( $args = null )
  {
    $html = '';

    $defaultargs = array( 'post_type' => 'hotel',
                          'posts_per_page' => '-1',
                          'orderby' => 'post_title',
                          'order' => 'ASC',
                          'tax_query' => array() );

    $args != null  && is_array( $args ) ? $final_args = array_merge( $defaultargs, $args ) : $final_args = $defaultargs;

    $hotels = new WP_Query( $final_args );

    if( $hotels->have_posts() )
    {
      $html .= '<ul class="hotel-list" data-updateable-content="filter_hotels">';

      while( $hotels->have_posts() ) : $hotels->the_post();

        $data['hotel'] = new Hotel( $hotels->post->ID );

        ob_start();

        get_template_part_with_data( 'hotel', 'template', 'hotel', $data );

        $html .= ob_get_contents();

        ob_get_clean();

      endwhile;

      $html .= '</ul>';
    }

    wp_reset_postdata();

    return $html;

  }


  /////////////////////////////////////////
  ///////////// AJAX METHODS  /////////////
  ///////////// ///////////////////////////

  public function add_hotel()
  {
    $data  = $_POST;
    $resp  = new ajax_response();
    $hotel = new Hotel();

    if( !empty( $data ) )
      $result = $hotel->update_hotel( $data['hotel'], null, 'add' );

    if( is_int( $result ) )
    {
      $hotel = new Hotel( $result );

      $resp->set_status( true );
      $resp->set_message( $hotel->hotel_name.' added successfully.' );
    }
    else
    {
      $resp->set_status( false );
      $resp->set_message( 'Could not add '.$hotel->hotel_name.'. Please try again.' );
    }

    echo $resp->encode_response();
    die();
  }

  public function edit_hotel()
  {
    $result = '';
    $data   = $_POST;
    $resp   = new ajax_response();
    $hotel  = new Hotel( (int) $data['hotel']['hotel_id'] );

    if( is_object( $hotel ) && !empty( $data['hotel'] ) )
    {
      //$validation = Hotel::validate_hotel_data( $data['hotel'] );

      if( true )
      {
        $result = $hotel->update_hotel( $data['hotel'], $hotel->hotel_id );
      }
      else
      {
        $resp->set_status( false );
        $resp->set_message( $validation['error_msg'] );
      }

      if( is_int( $result ) )
      {
        $resp->set_status( true );
        $resp->set_message( $hotel->hotel_name.' edited successfully.' );
      }
      else
      {
        $resp->set_status( false );
        $resp->set_message( 'Could not edit '.$hotel->hotel_name.'. Please try again.' );
      }
    }
    else
    {
      $resp->set_status( false );
      $resp->set_message( 'Could not edit '.$hotel->hotel_name.'. Please try again.' );
    }

    echo $resp->encode_response();

    die();
  }

  public function delete_hotel()
  {
    $data   = $_POST;
    $result = '';
    $resp   = new ajax_response( $data['action'] );

    if( $data['hotel_id'] != '' )
    {
      $hotel  = new Hotel( (int) $data['hotel_id'] );
      $result = wp_delete_post( $hotel->hotel_id, false );
    }

    if( is_object( $result ) )
    {
      $resp->set_status( true );
      $resp->set_message( $hotel->hotel_name.' successfully moved to the trash. ' );
    }
    else
    {
      $resp->set_status( false );
      $resp->set_message( 'Could not delete '.$hotel->hotel_name.'. Please try again.' );
    }

    echo $resp->encode_response();

    die();
  }

  public function update_hotel_map()
  {
    $data     = $_POST;
    $resp     = new ajax_response( $data['action'] );
    $geocoder = new geocoder();
    $results  = null;

    if( $data['address'] != false )
      $address = str_replace( ' ','+', $data['address'] );
      $results = $geocoder::get_location( $address );

    if( $results != null )
      $resp->set_status( true );
      $resp->set_data( array( 'lat' => $results['lat'], 'lng' => $results['lng'] ) );

    echo $resp->encode_response();

    die();
  }

  public function filter_hotel_list()
  {
    $data  = $_POST;
    $hotel = new Hotel();
    $resp  = new ajax_response( $data['action'], true );

    if( $data['hotel_type'] != null )
    {
      $args = array( 'post_type'      => 'hotel',
                     'posts_per_page' => '-1',
                     'orderby'        => 'post_title',
                     'order'          => $data['hotel_sort'],
                     'tax_query'      => array( array( 'taxonomy' => 'hotel-category' , 'field' => 'term_id', 'terms' => (int) $data['hotel_type'] ) ) );
    }
    else
    {
      $args = array( 'post_type'      => 'hotel',
                     'posts_per_page' => '-1',
                     'orderby'        => 'post_title',
                     'order'          => $data['hotel_sort'], );
    }

    $loop = new WP_Query( $args );

    if( $loop->have_posts() )
    {
      $resp->set_status( true );
      $resp->set_data( array( 'hotels' => $hotel->get_all_hotels( $args ) ) );
    }
    else
    {
      $resp->set_message( 'No Hotels found. Try again hoss.' );
    }

    echo $resp->encode_response();
    die();
  }
}

$hotel = new Hotel();
$hotel->init_actions();