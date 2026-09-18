<?php
class GummDispatcher extends GummObject {
    
    /**
     * Holds info, if init hook passed
     * 
     * @return void
     */
    private $init = false;
    
    private $controller;
    
    private $callback;
    
    private $params;
    
    /**
     * @return void
     */
    public function __construct() {
        parent::__construct();
        
        $this->RequestHandler = GummRegistry::get('Component', 'RequestHandler');
    }
    
    /**
     * Returns a singleton instance of the GummDispatcher class.
     *
     * @return GummDispatcher instance
     * @access public
     */
	public static function &getInstance() {
		static $instance = array();
		if (!$instance) {
		    $_inst = new GummDispatcher();
			$instance[0] =& $_inst;
		}
		return $instance[0];
	}
    
    /**
     * @return void
     */
    public function dispatch($requestAction=array(), $init='init') {
        if (is_string($requestAction) || is_null($requestAction)) $requestAction = array();
        if ($this->RequestHandler->isAjax() && $init !== 'init') $init = 'init';
        $callback = self::getCallbackToDispatch($requestAction);
        
        if ($callback === false) return;
        
        $controller =& $callback[0];
        $action = $callback[1];
        
        if ($this->RequestHandler->isAdminAjax()) {

            if (empty($_COOKIE[AUTH_COOKIE]) && !empty($_REQUEST['auth_cookie'])) {
                $_COOKIE[AUTH_COOKIE] = $_REQUEST['auth_cookie'];
                unset($GLOBALS['current_user']);
            }
            
            add_action('wp_ajax_' . $this->RequestHandler->getAction(), array(&$controller, 'startupProcess'), 10);
            add_action('wp_ajax_' . $this->RequestHandler->getAction(), $callback, 11);
            add_action('wp_ajax_' . $this->RequestHandler->getAction(), array(&$controller, 'shutdownProcess'), 12);
        } else {
            $this->controller = $controller;
            $this->callback = $callback;
            $this->params = $this->RequestHandler->getRequestParams();
            
            if ($this->init === false) {
                add_action($init, array(&$this, 'dispatchCurrentAction'), 11);
            } else {
                $this->dispatchCurrentAction();
            }

        }
    }
    
    /**
     * @return array|false
     */
    public static function getCallbackToDispatch(array $requestAction=array()) {
        $_this = GummDispatcher::getInstance();
        
        extract($requestAction, EXTR_SKIP);
        
        if (!isset($controller)) {
            $controller = $_this->RequestHandler->getController();
            // Render / Add to Menu the Theme Options Page
            if (!$controller) return false;
            
            if (!$controller && is_admin() && !$_this->RequestHandler->isAdminAjax()) {
                $controller = 'options';
                $action = 'admin_index';
            } elseif (!$controller){
                return false;
            }
        }
        
        if (!isset($action)) $action = $_this->RequestHandler->getAction();
        if (!$action) {
            $action = 'index';
            if (is_admin()) $action = 'admin_' . $action;
        }
        
        
        // $ControllerObj = GummRegistry::get('Controller', $controller);
        // debug($ControllerObj);
        App::import('Controller', $controller);
        $controllerClass = Inflector::camelize($controller) . 'Controller';
        if (!class_exists($controllerClass)) {
            trigger_error(sprintf(__('Class %s not defined.', 'gummfw'), $controllerClass), E_USER_ERROR);
        }
        
        $ControllerObj = new $controllerClass;
        if (!is_a($ControllerObj, 'Controller')) {
            trigger_error(sprintf(__('Class %s must be a Controller.', 'gummfw'), $controllerClass), E_USER_ERROR);
        }
        
        $realAction = str_replace(GUMM_FW_PREFIX, '', $action);
        if (strpos($realAction, 'admin_') === false && (is_admin() || isset($_REQUEST['gummadmin']))) {
            $realAction = 'admin_' . $realAction;
        }
        
        $ControllerObj->action = $realAction;
        
        $callback = array(&$ControllerObj, $realAction);
        if (!is_callable($callback)) {
            trigger_error(__('Request is not a valid callback.', 'gummfw'), E_USER_ERROR);
        }
        
        return $callback;
    }
    
    public function dispatchCurrentAction() {
        if (!$this->controller || !$this->callback) return false;
        
        // d($this->callback);
        
        $this->controller->startupProcess();
        call_user_func_array($this->callback, $this->params);
        $this->controller->shutdownProcess();
        
        $this->controller = $this->callback = $this->params = null;
    }
}
?>