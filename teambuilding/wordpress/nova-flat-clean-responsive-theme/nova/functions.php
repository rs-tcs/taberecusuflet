<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
define('GUMM_TEMPLATEPATH', get_template_directory());
define('DS', DIRECTORY_SEPARATOR);

if (!function_exists('wp_get_theme'))
    $currTheme = get_theme_data(GUMM_TEMPLATEPATH . DS . 'style.css');
else
    $currTheme = wp_get_theme();

//Define constants (framework specific):
define('GUMM_FW_PREFIX', 'gumm_');
define('GUMM_BASE', GUMM_TEMPLATEPATH . DS . 'app' . DS);
define('GUMM_LIBS', GUMM_BASE . 'libs' . DS);
define('GUMM_CONFIGS', GUMM_BASE . 'config' . DS);
define('GUMM_VIEWS', GUMM_BASE . 'views' . DS);
define('GUMM_LAYOUTS', GUMM_VIEWS . 'theme-layouts' . DS);
define('GUMM_MODELS', GUMM_BASE . 'models' . DS);
define('GUMM_ELEMENTS', GUMM_VIEWS . 'elements' . DS);
define('GUMM_LAYOUT_ELEMENTS', GUMM_ELEMENTS . 'layout-components' . DS);
define('GUMM_LAYOUT_ELEMENTS_SINGLE', GUMM_ELEMENTS . 'layout-components-single' . DS);
define('GUMM_CONTROLLERS', GUMM_BASE . 'controllers' . DS);
define('GUMM_LIB_COMPONENTS', GUMM_LIBS . 'controller' . DS . 'components' . DS);
define('GUMM_ASSETS', GUMM_BASE . 'assets' . DS);
define('GUMM_VENDORS', GUMM_BASE . 'vendors' . DS);
define('GUMM_WIDGETS', GUMM_BASE . DS . 'widgets' . DS);
define('GUMM_THEME_PAGE', 'gumm-administration');
define('GUMM_EXTERNAL_PLUGINS', GUMM_TEMPLATEPATH . DS . 'plugins' . DS);

//Define constants (theme specific):
define('GUMM_THEME', $currTheme['Name']);
define('GUMM_THEME_PREFIX', str_replace(' ', '', strtolower($currTheme['Name'])));
define('GUMM_THEME_URL', get_template_directory_uri());
define('GUMM_THEME_ASSETS_URL', GUMM_THEME_URL . '/app/assets/');
define('GUMM_THEME_JS_URL', GUMM_THEME_URL . '/app/assets/js/');
define('GUMM_THEME_CSS_URL', GUMM_THEME_URL . '/app/assets/css/');
define('GUMM_THEME_IMG_URL', GUMM_THEME_URL . '/app/assets/img/');
define('GUMM_COOKIE', '__gumm_' . GUMM_THEME_PREFIX . '_settings');

if ( function_exists('add_theme_support') ) { // Added in 2.9
	add_theme_support('post-thumbnails');
	add_image_size('homepage-thumb', 200, 146, true); //(cropped)
}

// Load Translation Text Domain
load_theme_textdomain('gummfw', GUMM_TEMPLATEPATH.'/languages');

// Set Max Content Width
if (!isset($content_width)) $content_width = 900;

//Nav Menus
if(function_exists('register_nav_menu')):
	register_nav_menu( 'prime_nav_menu', __('Prime Navigation Menu', 'gummfw'));
endif;

// Do the magic
require_once(GUMM_LIBS . 'bootstrap.php');

App::uses('TgmPluginActivation', 'Vendor/TgmPluginActivation');

$GummTgmPluginActivation = new TgmPluginActivation();

add_filter('get_the_time', 'gummFilterDate', 3 , 99);

function gummFilterDate($date, $d, $post ) {
    global $gummWpHelper;
    
    $eventStartTime = $gummWpHelper->getPostMeta($post->ID, 'event_start_time');
    if ($eventStartTime) {
        $date = date_i18n('F j, Y', strtotime(preg_replace("'[a-z]'i", '', $eventStartTime)));
    }

    return $date;
}

?>
