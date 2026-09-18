<?php
class GummObject {
    
    /**
     * @var array
     */
    private $loadables = array(
        // 'helpers',
        // 'components'
    );
    
    /**
     * @var array
     */
    public $wpFilters = array();
    
    /**
     * @var array
     */
    public $wpActions = array();
    
    /**
     * @var array
     */
    public $ajaxScriptActions = array();
    
    /**
     * Used to store the requestAction method named params
     * 
     * @var array
     */
    public $requestActionParams = array();
    
    /**
     * Security fields to remove from the $data var
     * 
     * @var array
     */
    private $__securityPostFields = array('_wpnonce', 'gummcontroller', 'action', 'gummadmin');
    
	/**
	 * @var array
	 */
	private $scriptBlockExecutionData = array();
	
	/**
	 * @var array
	 */
	private $scriptData = array();
    
    /**
     * Gummbase constructor
     * 
     * @return void
     */
    public function __construct() {
        
        if ($_POST) {
            $this->data = $_POST;
            foreach ($this->data as $name => $value) {
                if (in_array($name, $this->__securityPostFields)) unset($this->data[$name]);
            }
        }
        
        foreach ($this->loadables as $loadable) {
            if (isset($this->$loadable)) {
                foreach ($this->$loadable as $loadableComp) {
                    $this->$loadableComp = GummRegistry::get(ucwords(Inflector::singularize($loadable)), $loadableComp);
                }
            }
        }

        foreach ($this->wpFilters as $filter => $func) {
            if (is_array($func)) {
                if (isset($func['priority']) && isset($func['args']) && isset($func['func'])) {
                    if (is_array($func['func'])) {
                        foreach ($func['func'] as $f) {
                            add_filter($filter, array(&$this, $f), $func['priority'], $func['args']);
                        }
                    } else {
                        add_filter($filter, array(&$this, $func['func']), $func['priority'], $func['args']);
                    }
                } else {
                    foreach ($func as $f) {
                        add_filter($filter, array(&$this, $f));
                    }
                }
            } else {
                add_filter($filter, array(&$this, $func));
            }
        }
        
        $_actions = array();
        foreach ($this->wpActions as $action => $func) {
            if (is_array($func)) {
                foreach ($func as $f) {
                    $_actions[$action][] = $f;
                }
            } else {
                $_actions[$action][] = $func;
            }
        }
        
        if (!GummRegistry::get('Component', 'RequestHandler')->isAjax()) {
            foreach ($_actions as $hook => $methods) {
                foreach ($methods as $method) {
                    add_action($hook, array(&$this, $method));
                }
            }
        } elseif ($_actions) {
            foreach ($_actions as $hook => $methods) {
                if (in_array($hook, array(
                    'admin_print_footer_scripts', 'after_wp_tiny_mce',
                ))) {
                    if (!isset($this->ajaxScriptActions[$hook])) $this->ajaxScriptActions[$hook] = array();
                    $this->ajaxScriptActions[$hook] = array_merge($this->ajaxScriptActions[$hook], $methods);
                }
            }
            
            add_action('print_ajax_scripts', array(&$this, '_actionPrintAjaxScripts'));
        }

        $scriptBlockHook = 'print_footer_scripts';
        if (is_admin()) {
            if (GummRegistry::get('Component', 'RequestHandler')->isAjax()) {
                $scriptBlockHook = 'print_ajax_scripts';
            } else {
                $scriptBlockHook = 'admin_print_footer_scripts';
            }
        }
        add_action($scriptBlockHook, array(&$this, 'printFooterScriptData'));

    }
    
/**
 * Returns a singleton instance of the Configure class.
 *
 * @return Object instance
 * @access public
 */
	public static function &getInstance() {
		static $instance = array();
		if (!$instance) {
		    $_inst = new GummObject();
			$instance[0] =& $_inst;
		}
		return $instance[0];
	}
    
    public function _actionPrintAjaxScripts() {
        foreach ($this->ajaxScriptActions as $hook => $methods) {
            foreach ($methods as $method) {
                call_user_func_array(array(&$this, $method), array('ajax' => true));
            }
        }
        $this->printFooterScriptData();
    }
    
    /**
     * @param array $callback
     * @param mixed $params
     * @return void
     */
    public function requestAction($requested, $render=true) {
        
        if (!isset($requested['controller'])) {
            if (is_a($this, 'View')) {
                if (isset($this->controller)) {
                    $requested['controller'] = str_replace('_controller', '', Inflector::underscore(get_class($this->controller)));
                }
            }
        }
        
        if (!isset($requested['controller']) || !isset($requested['action'])) return false;
        
        $callback = GummDispatcher::getCallbackToDispatch($requested);
        
        if (!$callback) return false;
        
        $args = GummRouter::getUrlParams($requested);
        $params = $args['params'];
        
        $controller =& $callback[0];
        
        $controller->startupProcess();
        
        $return = call_user_func_array($callback, $params);
        
        if ($render === false) {
            ob_start();
            $controller->shutdownProcess(false);
            $return = ob_get_clean();
        } else {
            $controller->shutdownProcess(false);
        }
        

        return $return;
    }
    
    public static function _requestAction($requested, $render=true) {
        return self::getInstance()->requestAction($requested, $render);
    }
    
    /**
     * Returns parsed option_id from the Configure Map of option types
     * 
     * @param string $mapKey
     * @param string $optionId
     */
    public function constructOptionId($type, $id) {
        $typeStructure = Configure::read('optionIdStructureMap.' . $type);
        
        if (!$typeStructure) return $id;
        
        $typeStructure = GUMM_THEME_PREFIX . '_' . $typeStructure;
        
        $regexMatch = str_replace('%s', '.*', $typeStructure);        
        if (preg_match("'$regexMatch'imsU", $id)) {
            return $id;
        }
        
        $optionId = sprintf($typeStructure, $id);
        
        return $optionId;
    }
    
    /**
     * @param string $id
     * @return string
     */
    public function friendlyOptionId($id) {
        if (strpos($id, GUMM_THEME_PREFIX . '_') === 0) {
            $id = substr($id, strlen(GUMM_THEME_PREFIX . '_'));
        } elseif (strpos($id, 'gummbase_') === 0) {
            $id = substr($id, strlen('gummbase_'));
        }
        
        return $id;
    }
    
    /**
     * @param string $id
     * @return string
     */
    public function gummOptionId($id, $supressSkins=false) {
        if (defined('GUMM_THEME_PREFIX')) {
            if (strpos($id, GUMM_THEME_PREFIX) !== 0 && strpos($id, '_wp') !== 0 && strpos($id, 'gummbase') !== 0 && strpos($id, '_gumm') !== 0)  {
                $id = GUMM_THEME_PREFIX . '_' . $id;
            }
            
            if (Configure::read('themeSupport.skins') === true) {
                $rootOptionId = $id;
                $optionIdParts = explode('.', $id);
                $rootOptionId = array_shift($optionIdParts);
                
                if ($rootOptionId == GUMM_THEME_PREFIX . '_styles' && $supressSkins === false) {
                    if (
                        isset($_COOKIE['__gumm_user_preview_skin']) &&
                        $_COOKIE['__gumm_user_preview_skin'] == Configure::read('Skin.customUserSkinId') &&
                        !is_admin() &&
                        Configure::read('build') != 'release'
                    ) {
                        $skin = 'default';
                    } else {
                        $skin = GummRegistry::get('Model', 'Skin')->getActiveSkin();
                    }

                    array_unshift($optionIdParts, $skin);
                    array_unshift($optionIdParts, $rootOptionId);
                    
                    $id = implode('.', $optionIdParts);

                }
            }

        }
        
        return $id;
    }
    
    /**
     * @param array $conditions
     * @return array
     */
    protected function _parseQueryConditions($conditions, $mergeWith=array()) {
        $conditions = Set::filter((array) $conditions);
        
        if (isset($conditions['id'])) {
            if (is_array($conditions['id'])) {
                $conditions['post__in'] = $conditions['id'];
            } else {
                $conditions['p'] = $conditions['id'];
            }
            unset($conditions['id']);
        }
        if (isset($conditions['limit'])) {
            $conditions['posts_per_page'] = $conditions['limit'];
            unset($conditions['limit']);
        } if (isset($conditions['postType'])) {
            $conditions['post_type'] = $conditions['postType'];
            unset($conditions['postType']);
        }
        
        return array_merge($conditions, $mergeWith);
    }
    
    /**
     * @param string $message
     * @return void
     */
    public function e404($message='404') {
        die($message);
    }
    
	public function scriptBlockStart($jQuery=true) {
	    $this->scriptBlockExecutionData = array('jQuery' => $jQuery);
	    ob_start();
	}
	
	public function scriptBlockEnd() {
	    extract($this->scriptBlockExecutionData);
	    $this->scriptBlockExecutionData = array();
	    $this->scriptData[(int) $jQuery][] = ob_get_clean();
	}
    
	public function printFooterScriptData() {
	    if (!$this->scriptData) return true;
	    $isAjax = GummRegistry::get('Component', 'RequestHandler')->isAjax();
?>
        <script type="text/javascript">
<?php
        if (isset($this->scriptData[1])):
?>
        (function( $ ){
<?php   if (!$isAjax): ?>
        $(window).load(function(){
<?php   endif; ?>
<?php
	    foreach ($this->scriptData[1] as $scriptBlock) {
            echo $scriptBlock;
	    }
?>
<?php   if (!$isAjax): ?>
        });
<?php   endif; ?>
        })( jQuery );
<?php
        endif;
?>
        </script>
<?php
        return true;
	}
}
?>