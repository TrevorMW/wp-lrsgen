<?php
/**
 *
 * @package WordPress
 * @subpackage themename
 */

get_header('login');

$login = new Custom_Login();?>

  <div class="table-cell">

    <div class="login-page-content floating">

      <div class="login-logo">
        <h1>LRS<small>gen</small></h1>
      </div>
      <div class="login-forms">
        <nav data-tab-triggers>
          <ul>
            <li><a href="#" class="active" data-tab-trigger="login">Login</a></li>
            <li><a href="#" data-tab-trigger="register">Register</a></li>
          </ul>
        </nav>
        <div data-tabs>

          <div class="active" data-tab="login">
            <?php echo $login->get_login_form(); ?>
          </div>
          <div data-tab="register">
            <?php echo $login->get_register_form(); ?>
          </div>
        </div>
      </div>
    </div>

  </div>

<?php get_footer('login'); ?>
