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
    $data = $_POST;
    $resp = new ajax_response( $data['action'], true );



    echo $resp->encode_response();
    die();
  }
}

$login = new Custom_Login();
$login->init_actions();