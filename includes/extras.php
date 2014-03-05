<?php
/**
 * Extra Functions
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// if( !class_exists( 'DiagonalizerFramework' ) ) {
// 	class DiagonalizerFramework {
// 		public static $_dir;
//         public static $_url;

//         public static function cleanFilePath( $path ) {
//             $path = str_replace('','', str_replace( array( "\\", "\\\\" ), '/', $path ) );
//             if ($path[ strlen($path)-1 ] === '/') {
//                 $path = rtrim($path, '/');
//             }
//             return $path;
//         }

//         static function init() {
//         	self::$_dir     = trailingslashit( self::cleanFilePath( dirname( __FILE__ ) ) );
//             $wp_content_dir = trailingslashit( self::cleanFilePath( WP_CONTENT_DIR ) );
//             $wp_content_dir = trailingslashit( str_replace( '//', '/', $wp_content_dir ) );
//             $relative_url   = str_replace( $wp_content_dir, '', self::$_dir );
//             $wp_content_url = self::cleanFilePath( ( is_ssl() ? str_replace( 'http://', 'https://', WP_CONTENT_URL ) : WP_CONTENT_URL ) );
//             self::$_url     = trailingslashit( $wp_content_url ) . $relative_url;  
//         }
// 	}

// 	/**
//      * action 'redux/init'
//      * @param null
//      */

//     do_action( 'includes/init', DiagonalizerFramework::init() );
// }

/*------------------------------------------------------------------
	Windows-proof constants: replace backward by forward slashes. 
	Thanks to: @peterbouwmeester
-------------------------------------------------------------------*/
function myCleanPath( $path ) {
    $path = str_replace('','', str_replace( array( "\\", "\\\\" ), '/', $path ) );
    if ($path[ strlen($path)-1 ] === '/') {
        $path = rtrim($path, '/');
    }
    return $path;
}

/*------------------------------------------------------------------
	FOLDERS PATH
-------------------------------------------------------------------*/
define( 'INCLUDES_DIR', 		trailingslashit( myCleanPath ( dirname( __FILE__ ) ) ) );
define( 'FRAMEWORK_DIR', 		trailingslashit( INCLUDES_DIR . 'framework' ) );
define( 'FUNCTIONS_DIR', 		trailingslashit( INCLUDES_DIR . 'functions' ) );
define( 'REDUX_DIR', 			trailingslashit( FRAMEWORK_DIR . 'redux' ) );
define( 'SMK_SBG_DIR', 			trailingslashit( FRAMEWORK_DIR . 'smk-sidebar-generator' ) );
// define( 'SCPT_PLUGIN_DIR',		trailingslashit( FRAMEWORK_DIR . 'super-cpt' ) );

/*------------------------------------------------------------------
	URL Windows-proof constants
-------------------------------------------------------------------*/
$wp_content_dir = trailingslashit( myCleanPath( WP_CONTENT_DIR ) );
$wp_content_dir = trailingslashit( str_replace( '//', '/', $wp_content_dir ) );
$relative_url   = str_replace( $wp_content_dir, '', INCLUDES_DIR );
$wp_content_url = myCleanPath( ( is_ssl() ? str_replace( 'http://', 'https://', WP_CONTENT_URL ) : WP_CONTENT_URL ) );

/*------------------------------------------------------------------
	FOLDERS URI
-------------------------------------------------------------------*/
define( 'INCLUDES_URI',			trailingslashit( $wp_content_url ) . $relative_url );
define( 'FRAMEWORK_URI', 		trailingslashit( INCLUDES_URI . 'framework' ) );
define( 'STATIC_URI', 			trailingslashit( INCLUDES_URI . 'static' ) );
define( 'STATIC_JS',			trailingslashit( STATIC_URI . 'js' ) );
define( 'STATIC_CSS',			trailingslashit( STATIC_URI . 'css' ) );
define( 'SMK_SBG_URI', 			trailingslashit( FRAMEWORK_URI . 'smk-sidebar-generator' ) );
// define( 'SCPT_PLUGIN_URL',		trailingslashit( FRAMEWORK_URI . 'super-cpt' ) );

require_once REDUX_DIR . 'ReduxCore/framework.php';
require_once FUNCTIONS_DIR . 'option.php';
require_once FUNCTIONS_DIR . 'option-functions.php';

require_once FRAMEWORK_DIR . 'Mobile_Detect.php';
// require_once FRAMEWORK_DIR . 'CPT.php'; // https://github.com/mboynes/super-cpt
// require_once FRAMEWORK_DIR . 'acpt/config.php'; // https://github.com/kevindees/advanced_custom_post_types
// require_once FRAMEWORK_DIR . 'super-cpt/super-cpt.php';
require_once FRAMEWORK_DIR . 'integration.php';
require_once FRAMEWORK_DIR . 'smk-sidebar-generator/smk-sidebar-generator.php';
require_once FUNCTIONS_DIR . 'post_type.php';
require_once FUNCTIONS_DIR . 'shortcodes.php';
require_once FUNCTIONS_DIR . 'link_pages.php';
require_once FUNCTIONS_DIR . 'resize.php';


function extras_theme_setup(){
	add_theme_support( 'woocommerce' );
    // load_theme_textdomain('extras', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'extras_theme_setup');


/*------------------------------------------------------------------
	DEREGISTER, DEQUEUE AND LOAD WITH CONDITIONS
-------------------------------------------------------------------*/
// add_action( 'wp_print_styles', 'my_deregister_styles', 100 );
// function my_deregister_styles() {
// 	// wp_deregister_style( 'rs-settings' );
// 	// wp_deregister_style( 'rs-captions' );
// }

// add_action( 'wp_print_scripts', 'my_deregister_javascript', 100 );
// function my_deregister_javascript() {
// 	// wp_dequeue_script('revslider-jquery.themepunch.revolution.min');
// }

// // Load Scripts Only on Specific Pages
// add_action( 'wp_print_styles', 'load_specific', 100 );
// function load_specific() {
// 	if ( !is_front_page() ) {
// 		wp_dequeue_style( 'rs-settings' );
// 		wp_dequeue_style( 'rs-captions' );
// 		wp_dequeue_script( 'revslider-jquery.themepunch.revolution.min' );
// 	}
// }

// enqueue scripts
function us_fonts() {
	$protocol = is_ssl() ? 'https' : 'http';
	wp_enqueue_style( 'us-opensans', "$protocol://fonts.googleapis.com/css?family=Gentium+Book+Basic:400,400italic,700,700italic" );
	wp_enqueue_style( 'us-abril', "$protocol://fonts.googleapis.com/css?family=Abril+Fatface" );
	wp_enqueue_style( 'merriweather', "$protocol://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" );
	wp_enqueue_style( 'roboto', "$protocol://fonts.googleapis.com/css?family=Roboto+Slab:400,300,100" );
}
add_action( 'wp_enqueue_scripts', 'us_fonts' );

function extras_scripts() {
	wp_enqueue_style( 'easy-pie-chart', STATIC_CSS . 'jquery.easy-pie-chart.css', false, '1.6.2', 'screen');
  	wp_enqueue_style('extras_styles', STATIC_CSS . 'extras.css', false, '6c39f42987ae297a5a21e2bb35bf3402');
  	wp_enqueue_style('astra_styles', STATIC_CSS . 'astra.css', false, '6c39f42987ae297a5a21e2bb35bf3402');
  	wp_enqueue_style('icomoon', STATIC_CSS . 'font-icomoon.css', false, '6c39f42987ae297a5a21e2bb35bf3402');
  	// wp_enqueue_style('awesome', STATIC_CSS . 'font-awesome.css', false, '6c39f42987ae297a5a21e2bb35bf3402');
  	// wp_enqueue_style( 'elusive', STATIC_CSS . 'elusive-webfont.css', false, '6c39f42987ae297a5a21e2bb35bf3402' );
  	wp_register_style( 'tabsaccordions', STATIC_CSS . 'tabs/tabs+accordion.css');
	wp_register_style('minimal', STATIC_CSS . 'tabs/minimal.css');
	wp_register_style( 'tabsaccordions-a', STATIC_CSS . 'accordion-a/accordion-a.css');
	wp_register_style('minimal-a', STATIC_CSS . 'accordion-a/minimal.css');

	wp_enqueue_style( 'tabsaccordions' );
	wp_enqueue_style( 'minimal' );
	wp_enqueue_style( 'tabsaccordions-a' );
	wp_enqueue_style( 'minimal-a' );

  	wp_register_script('extras_scripts', STATIC_JS . 'extras.min.js', array('jquery'), '808c636db1edd932b7b9aa713b000324', true);
  	wp_register_script('easy-pie-charts', STATIC_JS . 'jquery.easy-pie-chart.js', false,'1.6.2', true );	
	wp_register_script('index', STATIC_JS . 'extra_js/index.js', 'jquery', NULL, TRUE);
	wp_register_script('ba-resize', STATIC_JS . 'extra_js/jquery.ba-resize.js', 'jquery', NULL, TRUE);
	wp_register_script('tabs_accordions', STATIC_JS . 'extra_js/jquery.tabs+accordion.js', 'jquery', NULL, TRUE);
	wp_register_script('jquery-ui', STATIC_JS . 'extra_js/jquery-ui-1.8.20.custom.min.js', 'jquery', NULL, TRUE);
	wp_register_script('accordion-a', STATIC_JS . 'extra_js/jquery.accordion-a-1.1.min.js', 'jquery', NULL, TRUE);

 	wp_enqueue_script('extras_scripts');
 	wp_enqueue_script('easy-pie-charts');	
 	wp_enqueue_script('index');
	wp_enqueue_script('ba-resize');
	wp_enqueue_script('tabs_accordions');
	wp_enqueue_script('jquery-ui');
	wp_enqueue_script('accordion-a');
}
add_action('wp_enqueue_scripts', 'extras_scripts', 100);

