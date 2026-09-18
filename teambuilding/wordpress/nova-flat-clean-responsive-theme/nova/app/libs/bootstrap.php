<?php
require_once(GUMM_LIBS . 'app.php');
require_once(GUMM_LIBS . 'set.php');
require_once(GUMM_LIBS . 'gumm_sanitize.php');
require_once(GUMM_LIBS . 'string.php');
require_once(GUMM_LIBS . 'gumm_hash.php');
require_once(GUMM_LIBS . 'inflector.php');
require_once(GUMM_LIBS . 'configure.php');
require_once(GUMM_LIBS . 'app_functions.php');
require_once(GUMM_LIBS . 'gumm_registry.php');
require_once(GUMM_LIBS . 'gumm_object.php');
require_once(GUMM_LIBS . 'gumm_dispatcher.php');
require_once(GUMM_LIBS . 'gumm_router.php');
require_once(GUMM_LIBS . 'view.php');
require_once(GUMM_LIBS . 'views' . DS . 'gumm_helper.php');
require_once(GUMM_LIBS . 'views' . DS . 'gumm_layout_element_pagination_interface.php');
require_once(GUMM_LIBS . 'views' . DS . 'gumm_layout_element.php');
require_once(GUMM_LIBS . 'views' . DS . 'gumm_layout_posts_renderer_element.php');
require_once(GUMM_LIBS . 'gumm_model.php');
require_once(GUMM_LIBS . 'controller.php');
require_once(GUMM_LIBS . 'gumm_widget.php');
require_once(GUMM_BASE . 'app_controller.php');
require_once(GUMM_CONFIGS . 'routes.php');
require_once(GUMM_CONFIGS . 'core.php');
require_once(GUMM_CONFIGS . 'assets.config');
require_once(GUMM_CONFIGS . 'data.config');
require_once(GUMM_CONFIGS . 'options.config');
require_once(GUMM_CONFIGS . 'metaboxes.config');
require_once(GUMM_CONFIGS . 'resolutions.config');

// Register GUMM Helpers to be available in all views
App::import('Helper', 'Wp');
App::import('Helper', 'Js');
App::import('Helper', 'Media');
App::import('Helper', 'Text');
App::import('Helper', 'Html');

// App::uses('functions', 'Vendor/EnvatoWordpressToolkit');
$GummDispatcher = new GummDispatcher;

// Called in views to get custom logic for WP specific functions
$gummWpHelper = GummRegistry::get('Helper', 'Wp');
$gummJsHelper = GummRegistry::get('Helper', 'Js');
$gummJsHelper->registerScripts();
$gummMediaHelper = GummRegistry::get('Helper', 'Media');
$gummTextHelper = GummRegistry::get('Helper', 'Text');
$gummHtmlHelper = GummRegistry::get('Helper', 'Html');
$gummHtmlHelper->registerStyles();

$gummLayoutHelper = GummRegistry::get('Helper', 'Layout');

if (Configure::read('Data.externalPluginIntegraion')) {
    App::uses('GummExternalPluginIntegration', 'Plugin/GummExternalPluginIntegration');
    $gummExternalPluginIntegration = new GummExternalPluginIntegration();
    $gummExternalPluginIntegration->integrate();
}

if (GummRegistry::get('Helper', 'Wp')->getOption('enable_image_preload') == 'true' && !GummRegistry::get('Component', 'RequestHandler')->isAjax()) {
    define('GUMM_THEME_SUPPORTS_IMG_PRELOAD', true);
} else {
    define('GUMM_THEME_SUPPORTS_IMG_PRELOAD', false);
}

add_filter('the_posts', array(GummRegistry::get('Model', 'Post'), 'bindPostsModels'), 10, 2);
add_filter('add_meta_boxes', array(GummRegistry::get('Model', 'Post'), 'bindPostModels'));


// Custom admin head
require_once(GUMM_LAYOUTS . 'admin-head.gtp');
add_action('admin_head', 'gumm_admin_head');
// 
// App::uses('FontAwesome', 'Vendor/FontAwesome');
// $FontAwesome = new FontAwesome();
// d($FontAwesome->import());

// Always load these controllers, as they attach neccessary hooks and filters
$GummPostsController = GummRegistry::get('Controller', 'Posts');
$GummLayoutsController = GummRegistry::get('Controller', 'Layouts');

// Register custom taxonomies
$GummTaxonomiesController = GummRegistry::get('Controller', 'Taxonomies');

// Register menus and custom menu fields
$GummMenusController = GummRegistry::get('Controller', 'Menus');

if (Configure::read('admin.metaboxes')) {
	App::import('Controller', 'metaboxes');
	$MetaboxesController = new MetaboxesController;
	$MetaboxesController->registerMetaboxes();
}

GummRegistry::get('Controller', 'Sidebars')->registerSidebars();

// If widgets enabled - make the theme widget ready
if (Configure::read('widgets')) {
	GummRegistry::get('Controller', 'Widgets')->registerWidgets(Configure::read('widgets'));
}

App::import('Controller', 'shortcodes');
$GummShortcodesController = new ShortcodesController;

// Dispatch Theme's default options page request
if (!GummRegistry::get('Component', 'RequestHandler')->isAjax() && is_admin()) {
    $GummDispatcher->dispatch(array('admin' => true, 'controller' => 'options', 'action' => 'index'), 'init');
}

$GummDispatcher->dispatch(null, 'wp');

add_theme_support('automatic-feed-links');
add_theme_support('post-formats', array('gallery', 'video'));

if (wasThemeActivated()) {
    GummRegistry::get('Component', 'Importer')->import();
}

// GummRegistry::get('Component', 'Importer')->importSampleContent();

/* ==== Fix for tag queries === */
function gumm_post_type_tags_fix($request) {
    if ( isset($request['tag']) && !isset($request['post_type']) )
    $request['post_type'] = 'any';
    return $request;
}
add_filter('request', 'gumm_post_type_tags_fix');
/* ==== Fix for tag queries END === */

$GummTemplateBuilder = null;
function gummInitTemplateBuilder() {
    global $GummTemplateBuilder, $post;
    App::import('Component', 'GummTemplateBuilder');
    
    if (is_home() || is_category() || is_tag()) {
        $GummTemplateBuilder = new GummTemplateBuilderComponent(null, 'blog', 'Option');
    } elseif ( (is_single() || is_page()) && ($post) ) {
        $GummTemplateBuilder = new GummTemplateBuilderComponent($post);
    } else {
        $GummTemplateBuilder = new GummTemplateBuilderComponent(null, 'index', 'Option');
    }
}
add_action('wp_head', 'gummInitTemplateBuilder');

if (Configure::read('build') != 'release') {
    /* ==== Preview Render Handling === */
    function gummBeforeRender() {
        $LayoutModel = GummRegistry::get('Model', 'Layout');

        // if (isset($_GET['gummpreview']['layout'])) {
        //     $LayoutModel->setSchemaStringForLayout($_GET['gummpreview']['layout']);
        // }
        // if (isset($_GET['gummpreview']['layout_type'])) {
        //     $LayoutModel->setLayoutType($_GET['gummpreview']['layout_type'], $LayoutModel->getCurrentLayoutPage() . '-loop');
        // }
    }
    function previewPortfolioCatLink($link) {
        if (isset($_GET['gummpreview'])) {
            $url = parse_url($link);
            $gummPreviewQuery = http_build_query(array('gummpreview' => $_GET['gummpreview']));

            $link .= (isset($url['query']) && $url['query']) ? '&' : '?';
            $link .= $gummPreviewQuery;
        }
        return $link;
    }
    
    
    if (isset($_COOKIE['__gumm_user_preview_layout_schema']) && !is_admin()) {
        // GummRegistry::get('Model', 'Layout')->setSchemaStringForLayout($_COOKIE['__gumm_user_preview_layout_schema']);
    }
    if (isset($_COOKIE['__gumm_user_preview_skin']) && !is_admin()) {
        GummRegistry::get('Model', 'Skin')->setActiveSkin($_COOKIE['__gumm_user_preview_skin'], true);
    }

    if (isset($_GET['gummpreview'])) {
        if (isset($_GET['gummpreview']['skin'])) {
            GummRegistry::get('Component', 'Cookie')->write('preview.skin', $_GET['gummpreview']['skin'], false);
            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_redirect($_SERVER['HTTP_REFERER']);
                exit;
            }
        }
        add_action('wp_head', 'gummBeforeRender');
        add_filter('term_link', 'previewPortfolioCatLink');
    }
    /* ==== Preview Render Handling END === */
} elseif (GummRegistry::get('Component', 'Cookie')->read('preview', false)) {
    GummRegistry::get('Component', 'Cookie')->write('preview', false, false);
}
?>