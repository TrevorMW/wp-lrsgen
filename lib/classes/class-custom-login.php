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

    $html .= '<form data-ajax-form data-action="user_login">
                <ul>
                  <li class="full">
                    <label>Username:</label>
                    <input type="text" name="user_name" value="" />
                  </li>
                  <li class="full">
                    <label>Password:</label>
                    <input type="password" name="pass" value="" />
                  </li>
                  <li class="submit">
                    <button type="submit" class="btn btn-primary">Login</button>
                  </li>
                </ul>
              </form>';

    return $html;
  }

  public function get_register_form()
  {
    $html = '';

    $html .= '<form data-ajax-form data-action="user_register">
                <ul>
                  <li class="full">
                    <label>Username:</label>
                    <input type="text" name="user_name" value="" />
                  </li>
                  <li class="full">
                    <label>Password:</label>
                    <input type="password" name="pass" value="" />
                  </li>
                  <li class="full">
                    <label>Password:</label>
                    <input type="password_again" name="pass" value="" />
                  </li>
                  <li class="submit">
                    <button type="submit" class="btn btn-primary">Login</button>
                  </li>
                </ul>
              </form>';

    return $html;
  }
}

$login = new Custom_Login();
$login->init_actions();