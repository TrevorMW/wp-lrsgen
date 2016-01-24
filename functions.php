<?php
/**
 * @package WordPress
 * @subpackage themename
 */


/** Require Classes **/

require_once('lib/classes/class-wp-acf-cpt.php');
require_once('lib/classes/class-geocoder.php');
require_once('lib/classes/class-validation.php');
require_once('lib/classes/class-ajax_response.php');
require_once('lib/classes/class-form-helper.php');
require_once('lib/classes/class-hotel.php');
require_once('lib/classes/class-reservation.php');
require_once('lib/classes/class-action_name.php');
require_once('lib/classes/class-custom-login.php');

function add_global_query_args()
{
  global $wp;

  if( is_object( $wp ) )
  {
    $wp->add_query_var('hotel_id');
    $wp->add_query_var('reservation_id');
    $wp->add_query_var('report_id');
  }
}
add_action( 'init', 'add_global_query_args' );

// LOAD GLOBAL DATA FOR VARIOUS USAGE
function instantiate_globals( $post )
{
  global $wp;

  if( is_object( $post ) )
  {
    $name = new Action_Name( $post );

    $GLOBALS['page_type'] = $name->type;
  }

  $hotel_id       = (int) get_query_var( 'hotel_id', null );
  $reservation_id = (int) get_query_var( 'reservation_id', null );
  $report_id      = (int) get_query_var( 'report_id', null );

  if( $hotel_id != null )
    $GLOBALS['current_hotel'] = new Hotel( $hotel_id );

  if( $reservation_id != null )
    $GLOBALS['current_reservation'] = new Reservation( $reservation_id );

  if( $report_id != null )
    $GLOBALS['current_report'] = new Report( $report_id );
}
add_action( 'add_globals', 'instantiate_globals', 10, 1 );

function check_if_user_exists()
{
  if( !is_user_logged_in() && !is_front_page() )
  {
    wp_redirect( '/login', 301 );
    die();
  }
}
add_action( 'template_redirect', 'check_if_user_exists' );


function go_home()
{
  wp_redirect( home_url() );
  exit();
}
add_action('wp_logout','go_home');

////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////// START CUSTOM FUNCTIONS ////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////





function get_rates_tools()
{
  echo '<a href="#" data-save-rates class="btn btn-tool"><i class="fa fa-fw fa-save"></i>&nbsp;Save All Rates</a>';
}

function get_rates_content()
{
  $html = $rates = '';

  $hotels = array();

  $args = array( 'post_type' => 'hotel', 'posts_per_page' => '-1', 'orderby' => 'name', 'order' => 'ASC' );
  $loop = new WP_Query( $args );

  if( $loop->have_posts() )
  {
    while( $loop->have_posts() ) : $loop->the_post();

      $hotels[] = new Hotel( $loop->post->ID );

    endwhile;
  }

  if( count( $hotels ) >= 1 )
  {
    foreach( $hotels as $hotel )
    {
      $data['hotel'] = $hotel;

      ob_start();

      get_template_part_with_data( 'template','hotel-table-rate', $data );

      $rates .= ob_get_contents();

      ob_get_clean();
    }
  }

  if( $rates != null )
  {
    $html .= '<table class="hotel-rate-table" data-editable-table>';
    $html .= '<thead>';
    $html .= '<th class="hotel-row-action">Full?</th>';
    $html .= '<th class="hotel-name">Hotel Name:</th>';
    $html .= '<th class="hotel-phone">Phone:</th>';
    //$html .= '<th class="hotel-email">Email</th>';
    $html .= '<th class="hotel-concierge">Concierge</th>';
    $html .= '<th class="hotel-manager">Manager</th>';
    $html .= '<th class="hotel-rates">Rates</th>';

    $html .= '</thead>';
    $html .= '<tbody>';
    $html .= $rates;
    $html .= '</tbody>';
    $html .= '</table>';

  }

  wp_reset_postdata();

  echo $html;
}



////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////// END CUSTOM FUNCTIONS //////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////







////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////// START FORM HELPER FUNCTIONS ///////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////

function get_hotel_room_types( $hotel )
{
  $html = '';

  if( is_object( $hotel ) )
  {
    $options = $hotel->hotel_all_room_types;

    if( !empty( $options ) )
    {
      foreach( $options as $k => $option )
      {
        $data['field_id']  = 'hotel[hotel_room_types][]';
        $data['checkbox']  = $option;
        $data['selected']  = !empty( $hotel->hotel_room_types ) ? checked( in_array( $k, $hotel->hotel_room_types ), true, false ) : '' ;

        ob_start();

          get_template_part_with_data( 'helpers/', 'template','checkbox', $data );

          $html .= ob_get_contents();

        ob_get_clean();
      }
    }
  }

  return $html;
}

////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////// END FORM HELPER FUNCTIONS /////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////








////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////// START BUILDER FUNCTIONS ///////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////



////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////// END BUILDER FUNCTIONS /////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////





////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////// START GLOBAL ACTION FUNCTIONS /////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////

function add_page_header()
{
  global $post;
  global $page_type;

  $html = ' ';

  if( is_object( $post ) )
  {
    $html .= '<header class="window-header"><h2>'.$post->post_title.'</h2></header>';
  }

  echo $html;
}
add_action( 'content_header', 'add_page_header', 5 );

function add_main_menu()
{
  $menu = '';

  $args = array(
  	'theme_location'  => 'primary',
  	'menu'            => 'primary',
  	'container'       => false,
  	'echo'            => false,
  	'items_wrap'      => '%3$s',
  );

  $menu = wp_nav_menu( $args );

  echo $menu;
}
add_action( 'main_navigation', 'add_main_menu', 10 );

function remove_admin_bar()
{
  if ( !current_user_can('administrator') && !is_admin() )
  {
    show_admin_bar(false);
  }
}
add_action('after_setup_theme', 'remove_admin_bar');

function get_hotel_data()
{
  $html = '';

  $html .= '<div class="wrapper hotel-list">';
  $html .= Hotel::get_all_hotels();
  $html .= '</div>';

  echo $html;
}
add_action( 'hotels_content', 'get_hotel_data' );

////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////// END GLOBAL ACTION FUNCTIONS ///////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////






////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////// START INDIVIDUAL ACTION FUNCTIONS /////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////

function load_login_form()
{
  $html = '';

  $data = array();

  wp_enqueue_script('login', get_template_directory_uri().'/js/login.js', array('jquery'), null, true);

  $html .= '<div class="table-cell login-wrap">';

    $html .= '<div class="login-box" data-switch-view="register-box">';

      ob_start();
      get_template_part_with_data( 'template', 'login-form', $data );
      $html .= ob_get_contents();
      ob_get_clean();

    $html .= '</div>';

    $html .= '<div class="register-box" data-switch-view="login-box">';

      ob_start();
      get_template_part_with_data( 'template', 'login-form', $data );
      $html .= ob_get_contents();
      ob_get_clean();

    $html .= '</div>';

  $html .= '</div>';

  echo $html;
}
add_action( 'login_page', 'load_login_form', 5 );

////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////// END INDIVIDUAL ACTION FUNCTIONS ///////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////






////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////// START UTILITY FUNCTIONS ///////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////

require_once('utility_functions.php');

////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////// END UTILITY FUNCTIONS /////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////


