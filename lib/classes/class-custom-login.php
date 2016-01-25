<?php

class Custom_Login
{

  public function init_actions()
  {
    add_action( 'wp_ajax_user_login', array( $this, 'user_login' ) );
    add_action( 'wp_ajax_nopriv_user_login', array( $this, 'user_login' ) );

    add_action( 'wp_ajax_user_register', array( $this, 'user_register' ) );
    add_action( 'wp_ajax_nopriv_user_register', array( $this, 'user_register' ) );
  }

  public function get_login_form()
  {
    $html = '';

    $data['login']['mode']   = 'login';
    $data['login']['btn']    = 'Login';
    $data['login']['action'] = 'user_login';

    ob_start();

      get_template_part_with_data( 'login/forms', 'template', 'login-form', $data );

      $html .= ob_get_contents();

    ob_get_clean();

    return $html;
  }

  public function get_register_form()
  {
    $html = '';

    $data['login']['mode']   = 'register';
    $data['login']['btn']    = 'Register';
    $data['login']['action'] = 'user_register';

    ob_start();

      get_template_part_with_data( 'login/forms', 'template', 'login-form', $data );

      $html .= ob_get_contents();

    ob_get_clean();

    return $html;
  }




  public function user_login()
  {
    global $wpdb;

    $data = $_POST;
    $resp = new ajax_response( $data['action'], true );

    $login_data['user_login']    = $wpdb->escape( $data['user_name'] );
    $login_data['user_password'] = $wpdb->escape( $data['pass'] );

    $user_verify = wp_signon( $login_data, false );

    if( is_wp_error( $user_verify ) )
    {
      $resp->set_message( 'Incorrect Login credentials. Please try again.' );
    }
    else
    {
      $resp->set_status( true );
      $resp->set_message( 'Login successful. Redirecting your now..' );
      $resp->set_data( array( 'redirect_url' => '/dashboard' ) );
    }

    echo $resp->encode_response();
    die();
  }

  public function user_register()
  {
    global $wpdb;

    $data       = $_POST;
    $login_data = array();
    $resp       = new ajax_response( $data['action'], true );

    $code_data = $wpdb->get_results( 'SELECT * FROM '.$wpdb->register_codes.' WHERE 1=1 AND register_code == '. $wpdb->escape( $data['sec_code'] ) );

    if( $code_data->register_code_used == 0 )
    {
      $username = $wpdb->escape( $data['user_name'] );
      $exists   = username_exists( $username );

      if( !$exists )
      {
      	$user_id = wp_create_user( $username, wp_generate_password( $length = 12, $include_standard_special_chars = false ), $username );

      	if( !is_wp_error( $user_id ) )
      	{
        	$wpdb->update( $wpdb->register_codes,
        	               array( 'register_code_used' => 1, 'register_code_used_by' => $user_id->data->username ),
        	               array( 'register_code_id' => (int) $code_data->register_code_id )
        	             );

        	$resp->set_status( true );
        	$resp->set_message( $user_id->data->username.' is successfully registered. Please switch to the login tab to login.' );
      	}
      	else
      	{
        	foreach( $user_id->errors as $k => $error )
        	{
          	$resp->set_message( array( $error[0] ) );
        	}
      	}
      }
      else
      {
      	$resp->set_message( 'User already exists. Please use a different email address.' );
      }
    }
    else
    {
      $resp->set_message( 'Security token not recognized. Could not register you without a valid security token.' );
    }

    echo $resp->encode_response();
    die();
  }
}

$login = new Custom_Login();
$login->init_actions();