<?php
/**
 * Resto functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Resto
 */

if ( ! function_exists( 'resto_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function resto_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Resto, use a find and replace
	 * to change 'resto' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'resto', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'menu-1' => esc_html__( 'Primary', 'resto' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'resto_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif;
add_action( 'after_setup_theme', 'resto_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function resto_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'resto_content_width', 640 );
}
add_action( 'after_setup_theme', 'resto_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function resto_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'resto' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'resto' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer', 'resto' ),
		'id'            => 'footer',
		'description'   => esc_html__( 'Add widgets here.', 'resto' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name' => 'Theme Sidebar',
		'id' => 'mat-sidebar',
		'description' => 'The main sidebar shown on the right in our awesome theme',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));
}
add_action( 'widgets_init', 'resto_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function resto_scripts() {
	wp_enqueue_style( 'resto-style', get_stylesheet_uri() );

	wp_enqueue_script( 'resto-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'resto-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'resto_scripts' );

function ajax_login_init(){

	wp_register_script('ajax-login-script', get_template_directory_uri() . '/js/ajax-login-script.js', array('jquery') );
	wp_enqueue_script('ajax-login-script');

	wp_localize_script( 'ajax-login-script', 'ajax_login_object', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'redirecturl' => home_url(),
		'loadingmessage' => __('Sending user info, please wait...')
	));

	// Enable the user with no privileges to run ajax_login() in AJAX
	add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
}

// Execute the action only if the user isn't logged in
if (!is_user_logged_in()) {
	add_action('init', 'ajax_login_init');
}

$user = wp_get_current_user();
//print_r($user);
if ( in_array( 'subscriber', (array) $user->roles ) ) {
	//The user has the "author" role
	add_action('admin_menu', 'admin_subscriber_setup_menu');

}

function admin_subscriber_setup_menu(){
	add_menu_page( 'Subscriber Menu', 'Subscriber', 'read', 'Subscriber', 'subscriber_init' );
}

function subscriber_init(){

}

function ajax_login(){

	// First check the nonce, if it fails the function will break
	check_ajax_referer( 'ajax-login-nonce', 'security' );

	// Nonce is checked, get the POST data and sign user on
	$info = array();
	$info['user_login'] = $_POST['username'];
	$info['user_password'] = $_POST['password'];
	$info['remember'] = true;

	$user_signon = wp_signon( $info, false );
	if ( is_wp_error($user_signon) ){
		echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
	} else {
		echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...')));
	}

	die();
}



/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
