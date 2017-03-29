<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Resto
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<title>Resto</title>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<form id="login" action="login" method="post">
	<h1>Site Login</h1>
	<p class="status"></p>
	<label for="username">Username</label>
	<input id="username" type="text" name="username">
	<label for="password">Password</label>
	<input id="password" type="password" name="password">
	<a class="lost" href="<?php echo wp_lostpassword_url(); ?>">Lost your password?</a>
	<a class="lost" href="/index.php/register-form">Registration</a>
	<input class="submit_button" type="submit" value="Login" name="submit">
	<a class="close" href="">(close)</a>
	<?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
</form>
<!--logout-->
<?php if (is_user_logged_in()) { ?>
	<a class="login_button" href="<?php echo wp_logout_url( home_url() ); ?>">Logout</a>
<?php } else { ?>
	<a class="login_button" id="show_login" href="">Login</a>
<?php } ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'resto' ); ?></a>

	<header>
		<div class="wrapper">
			<h1 class="logu">Resto</h1>
			<nav role="navigation">
				<?php wp_nav_menu( array( 'theme_location' => 'menu-1', 'menu_id' => 'primary-menu' ) ); ?>
			</nav><!-- #site-navigation -->

		</div><!-- .wrapper-->


	</header><!-- #masthead -->

	<div id="content" class="site-content">
