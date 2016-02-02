<?php
/**
 * @package WordPress
 * @subpackage themename
 */

// LOAD HELPER CLASSES
require_once('lib/classes/class-wp-acf-cpt.php');
require_once('lib/classes/class-validation.php');
require_once('lib/classes/class-ajax_response.php');
require_once('lib/classes/class-geocoder.php');
require_once('lib/classes/class-form-helper.php');
require_once('lib/classes/class-wrappers.php');
require_once('lib/classes/class-action_name.php');


// LOAD CORE CLASSES
require_once('lib/classes/class-hotel.php');
require_once('lib/classes/class-reservation.php');
require_once('lib/classes/class-dashboard.php');
require_once('lib/classes/class-dashboard-date.php');
require_once('lib/classes/class-rates.php');
require_once('lib/classes/class-custom-login.php');



/**
 * add_global_query_args function.
 *
 * @access public
 * @return void
 */
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



/**
 * instantiate_globals function.
 *
 * @access public
 * @param mixed $post
 * @return void
 */
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



/**
 * check_if_user_exists function.
 *
 * @access public
 * @return void
 */
function check_if_user_exists()
{
  if( !is_user_logged_in() && !is_front_page() )
  {
    wp_redirect( '/login', 301 );
    die();
  }
}
add_action( 'template_redirect', 'check_if_user_exists' );



/**
 * go_home function.
 *
 * @access public
 * @return void
 */
function go_home()
{
  wp_redirect( home_url() );
  exit();
}
add_action('wp_logout','go_home');



/**
 * create_register_codes_table function.
 *
 * @access public
 * @return void
 */
function create_register_codes_table()
{
  global $wpdb, $charset_collate, $db_version;

  $table_name      = $wpdb->prefix. "register_codes";
  $charset_collate = $wpdb->get_charset_collate();

  if( $wpdb->get_var( "SHOW TABLES LIKE '" . $table_name . "'" ) !=  $table_name )
  {
    $create_sql = "CREATE TABLE " . $table_name . " (
                    register_code_id INT(11) NOT NULL auto_increment,
                    register_code VARCHAR(12) NOT NULL,
                    register_code_used BOOLEAN NOT NULL,
                    register_code_used_by VARCHAR(300) NOT NULL,
                    PRIMARY KEY (register_code_id))$charset_collate;";
  }

  require_once( ABSPATH . "wp-admin/includes/upgrade.php" );
  dbDelta( $create_sql );

  if( !isset( $wpdb->register_codes ) )
  {
    $wpdb->register_codes = $table_name;
    $wpdb->tables[]       = str_replace( $wpdb->prefix, '', $table_name );
  }
}
add_action( 'init', 'create_register_codes_table' );



/**
 * example_add_dashboard_widgets function.
 *
 * @access public
 * @return void
 */
function example_add_dashboard_widgets()
{

	wp_add_dashboard_widget( 'register_codes', 'Generate User Register Codes',  'build_register_codes_widget' );
}
add_action( 'wp_dashboard_setup', 'example_add_dashboard_widgets' );



/**
 * build_register_codes_widget function.
 *
 * @access public
 * @return void
 */
function build_register_codes_widget()
{
  global $wpdb;

  $html = $code_data = '';

  $codes = $wpdb->get_results( 'SELECT * FROM '.$wpdb->register_codes );

  if( is_array( $codes ) )
  {
    foreach( $codes as $code )
    {
      if( $code->register_code_used == 0 )
      {
        $code_data .= '<tr>
                      <td>'.$code->register_code.'</td>
                      <td>
                        <form data-ajax-form data-action="">
                          <input type="hidden" name="code_id" value="'.(int) $code->register_code_id.'" />
                          <button type="submit" class="button button-small">Retire Code</button>
                        </form>
                      </td>
                     </tr>';
      }
    }
  }

  $html .= '<div data-overlay-parent><div data-overlay><i class="fa fa-fw fa-spin fa-spinner"></i></div></div>
            <form data-ajax-form data-action="generate_register_codes">
              <input type="number" name="number_of_codes" value="" />
              <button type="submit" class="button btn-primary">Generate Codes</button>
            </form>
            <br />
            <br />
            <div data-updateable-content="generate_register_codes">
              <table>
                <thead>
                  <th>Code</th>
                  <th>Actions</th>
                </thead>
                <tbody>'.$code_data.'</tbody>
              </table>
            </div>';

  echo $html;
}



/**
 * generate_register_codes function.
 *
 * @access public
 * @return void
 */
function generate_register_codes()
{
  global $wpdb;

  $strings    = array();
  $data       = $_POST;
  $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';

  if( (int) $data['number_of_codes'] > 0 )
  {
    for( $j = 0; $j < (int) $data['number_of_codes']; $j++ )
    {
      $code = '';

      for( $i = 0; $i < 12; $i++ )
      {
        $code .= $characters[ rand( 0, strlen( $characters ) - 1 ) ];
      }

      $strings[] = $code;

      $code = '';
    }

    if( is_array( $strings ) )
    {
      foreach( $strings as $string )
      {
        $wpdb->insert( 'lrsgen_register_codes', array( 'register_code' => $string, 'register_code_used' => 0 ), array( '%s', '%d' ) );
      }
    }
  }

}
add_action( 'wp_ajax_generate_register_codes', 'generate_register_codes' );
add_action( 'wp_ajax_nopriv_generate_register_codes', 'generate_register_codes' );



/**
 * add_page_header function.
 *
 * @access public
 * @return void
 */
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



/**
 * add_main_menu function.
 *
 * @access public
 * @return void
 */
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



/**
 * remove_admin_bar function.
 *
 * @access public
 * @return void
 */
function remove_admin_bar()
{
  if ( !current_user_can('administrator') && !is_admin() )
  {
    show_admin_bar(false);
  }
}
add_action('after_setup_theme', 'remove_admin_bar');


////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////// START CUSTOM FUNCTIONS ////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////





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
//////////////////////////////////// START UTILITY FUNCTIONS ///////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////

require_once('utility_functions.php');

////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////// END UTILITY FUNCTIONS /////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////


