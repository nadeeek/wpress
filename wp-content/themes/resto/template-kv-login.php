<?php
/**
  Template Name: Kv login page
 */
// here our custom login page code will be added soon

get_header(); ?>

  <div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
      <div class="wrapper" id="page-content">
        <?php

          get_template_part( 'template-parts/content', 'page' );

         if(!empty($errors)) {  //  to print errors,
          foreach($errors as $err )
            echo $err;
        }

        if(isset($_POST['login_Sbumit'])) {
          $creds                  = array();
          $creds['user_login']    = stripslashes( trim( $_POST['userName'] ) );
          $creds['user_password'] = stripslashes( trim( $_POST['passWord'] ) );
          $creds['remember']      = isset( $_POST['rememberMe'] ) ? sanitize_text_field( $_POST['rememberMe'] ) : '';
          $redirect_to            = esc_url_raw( $_POST['redirect_to'] );
          $secure_cookie          = null;

          if($redirect_to == '')
            $redirect_to= get_site_url(). '/dashboard/' ;

          if ( ! force_ssl_admin() ) {
            $user = is_email( $creds['user_login'] ) ? get_user_by( 'email', $creds['user_login'] ) : get_user_by( 'login', sanitize_user( $creds['user_login'] ) );

            if ( $user && get_user_option( 'use_ssl', $user->ID ) ) {
              $secure_cookie = true;
              force_ssl_admin( true );
            }
          }

          if ( force_ssl_admin() ) {
            $secure_cookie = true;
          }

          if ( is_null( $secure_cookie ) && force_ssl_login() ) {
            $secure_cookie = false;
          }

          $user = wp_signon( $creds, $secure_cookie );

          if ( $secure_cookie && strstr( $redirect_to, 'wp-admin' ) ) {
            $redirect_to = str_replace( 'http:', 'https:', $redirect_to );
          }

          if ( ! is_wp_error( $user ) ) {
            wp_safe_redirect( $redirect_to );
          } else {
            if ( $user->errors ) {
              $errors['invalid_user'] = __('<strong>ERROR</strong>: Invalid user or password.');
            } else {
              $errors['invalid_user_credentials'] = __( 'Please enter your username and password to login.', 'kvcodes' );
            }
          }
        }
        ?>
        <form name="loginform" action="http://wpress.com/login/" method="post">

          <p class="login-username">
            <label for="user_login">Username</label>
            <input type="text" name="userName" id="user_login" class="input" value="" >
          </p>
          <p class="login-password">
            <label for="user_pass">Password</label>
            <input type="password" name="passWord" id="user_pass" class="input" value="" >
          </p>

          <p class="login-remember"><label><input name="rememberMe" type="checkbox" id="rememberme" value="forever"> Remember Me</label></p>
          <p class="login-submit">
            <input type="hidden" name="login_Sbumit" >
            <input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="Log In">
            <input type="hidden" name="redirect_to" value="http://wpress.com/login/">
          </p>

        </form>

      </div>
    </main><!-- #main -->
  </div><!-- #primary -->

<?php
get_footer();