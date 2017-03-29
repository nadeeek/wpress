<?php
/*
Plugin Name: Register Form
Description: Register Form
Author: Nadeesha
Version: 0.1
*/

// registration form fields
function nw_registration_form_fields() {

    ob_start(); ?>
    <h3 class="nw_header"><?php _e('Register New Account'); ?></h3>

    <?php
    // show any error messages after form submission
    nw_show_error_messages(); ?>

    <form id="nw_registration_form" class="nw_form" action="" method="POST">
        <fieldset>
            <p>
                <label for="nw_user_Login"><?php _e('Username'); ?></label>
                <input name="nw_user_login" id="nw_user_login" class="required" type="text"/>
            </p>
            <p>
                <label for="nw_user_email"><?php _e('Email'); ?></label>
                <input name="nw_user_email" id="nw_user_email" class="required" type="email"/>
            </p>
            <p>
                <label for="nw_user_first"><?php _e('First Name'); ?></label>
                <input name="nw_user_first" id="nw_user_first" type="text"/>
            </p>
            <p>
                <label for="nw_user_last"><?php _e('Last Name'); ?></label>
                <input name="nw_user_last" id="nw_user_last" type="text"/>
            </p>
            <p>
                <label for="password"><?php _e('Password'); ?></label>
                <input name="nw_user_pass" id="password" class="required" type="password"/>
            </p>
            <p>
                <label for="password_again"><?php _e('Password Again'); ?></label>
                <input name="nw_user_pass_confirm" id="password_again" class="required" type="password"/>
            </p>
            <p>
                <input type="hidden" name="nw_register_nonce" value="<?php echo wp_create_nonce('nw-register-nonce'); ?>"/>
                <input type="submit" value="<?php _e('Register Your Account'); ?>"/>
            </p>
        </fieldset>
    </form>
    <?php
    return ob_get_clean();
}

add_shortcode('register_form', 'nw_registration_form_fields');

// register a new user
function nw_add_new_member() {
    if (isset( $_POST["nw_user_login"] ) && wp_verify_nonce($_POST['nw_register_nonce'], 'nw-register-nonce')) {
        $user_login		= $_POST["nw_user_login"];
        $user_email		= $_POST["nw_user_email"];
        $user_first 	= $_POST["nw_user_first"];
        $user_last	 	= $_POST["nw_user_last"];
        $user_pass		= $_POST["nw_user_pass"];
        $pass_confirm 	= $_POST["nw_user_pass_confirm"];

        // this is required for username checks
        require_once(ABSPATH . WPINC . '/registration.php');

        if(username_exists($user_login)) {
            // Username already registered
            nw_errors()->add('username_unavailable', __('Username already taken'));
        }
        if(!validate_username($user_login)) {
            // invalid username
            nw_errors()->add('username_invalid', __('Invalid username'));
        }
        if($user_login == '') {
            // empty username
            nw_errors()->add('username_empty', __('Please enter a username'));
        }
        if(!is_email($user_email)) {
            //invalid email
            nw_errors()->add('email_invalid', __('Invalid email'));
        }
        if(email_exists($user_email)) {
            //Email address already registered
            nw_errors()->add('email_used', __('Email already registered'));
        }
        if($user_pass == '') {
            // passwords do not match
            nw_errors()->add('password_empty', __('Please enter a password'));
        }
        if($user_pass != $pass_confirm) {
            // passwords do not match
            nw_errors()->add('password_mismatch', __('Passwords do not match'));
        }

        $errors = nw_errors()->get_error_messages();

        // only create the user in if there are no errors
        if(empty($errors)) {

            $new_user_id = wp_insert_user(array(
                    'user_login'		=> $user_login,
                    'user_pass'	 		=> $user_pass,
                    'user_email'		=> $user_email,
                    'first_name'		=> $user_first,
                    'last_name'			=> $user_last,
                    'user_registered'	=> date('Y-m-d H:i:s'),
                    'role'				=> 'subscriber'
                )
            );
            if($new_user_id) {
                // send an email to the admin alerting them of the registration
                wp_new_user_notification($new_user_id);

                // log the new user in
                wp_set_auth_cookie($user_login, $user_pass, true);
                wp_set_current_user($new_user_id, $user_login);
                do_action('wp_login', $user_login);

                // send the newly created user to the home page after logging them in
                wp_redirect(home_url()); exit;
            }

        }

    }
}
add_action('init', 'nw_add_new_member');

// used for tracking error messages
function nw_errors(){
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

// displays error messages from form submissions
function nw_show_error_messages() {
    if($codes = nw_errors()->get_error_codes()) {
        echo '<div class="nw_errors">';
        // Loop error codes and display errors
        foreach($codes as $code){
            $message = nw_errors()->get_error_message($code);
            echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
        }
        echo '</div>';
    }
}