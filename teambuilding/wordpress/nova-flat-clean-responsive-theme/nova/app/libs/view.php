<?php
class View extends GummObject {
	
	/**
	 * @var array
	 */
	private $__passedArgs = array();
	
	/**
	 * @var array
	 */
	private $__assignedVars = array();
	
	/**
	 * @var string
	 */
	private $__assignContext;
	
	/**
	 * @var array
	 */
	private $__parents = array();
	
	/**
	 * @var bool
	 */
	private $__assignOverwrite = true;
	
	/**
	 * @var Controller
	 */
	protected $controller;
	
	/**
	 * @var string
	 */
	private $action;
	
	/**
	 * @var bool
	 */
	private $autoRender;
	
	/**
	 * @var string
	 */
	private $viewFile;
	
	/**
	 * @var string
	 */
	private $templateExtension = 'gtp';
	
	/**
	 * @var bool
	 */
	private $hasRendered;
	
/**
 * Constructor
 *
 * @param Controller $controller A controller object to pull View::__passedArgs from.
 * @return View
 */
	public function __construct(&$controller=null) {
	    if ($controller) {
    		$this->__passedArgs = $controller->viewVars;
    		$this->controller =& $controller;
    		$this->action = $this->controller->action;
    		$this->autoRender = $this->controller->autoRender;
    		$this->hasRendered =& $this->controller->hasRendered;

    		$this->importHelpers();
	    }
	    
	    parent::__construct();
	}
	
    /**
     * Returns a singleton instance of the View class.
     *
     * @return View instance
     * @access public
     */
    public static function &getInstance() {
        // static $instance = array();
        // if (!$instance) {
        //     $instance[0] =& new View();
        // }
        // return $instance[0];
        $instance = new View();
        return $instance;
    }
	
	public function render($viewFile=null, $withAdminMenu=false) {
	    if ($this->hasRendered === true || $this->autoRender === false) return;
	    
		if ($this->controller->adminMenu !== false)
			add_action('admin_menu', array(&$this, '_wpRender'));
		else
			$this->_render($viewFile);

		$this->hasRendered = true;
	}
	
	public function _wpRender() {
		if (is_array($this->controller->adminMenu) && $this->controller->adminMenu['subPage']) {
			add_theme_page(GUMM_THEME . " Options", $this->controller->adminMenu['subPage'], 'edit_theme_options', GUMM_THEME_PAGE, array(&$this, '_render'));
		}
	}
	
	public function _render($viewFile=null) {
        // $form = new FormHelper;
        // $gummWpHelper = new WpHelper;
        // $gummMediaHelper = GummRegistry::get('Helper', 'Media');
        // $gummHtmlHelper = new HtmlHelper;
        // $gummJsHelper = new JsHelper;
        // $gummLayoutHelper = GummRegistry::get('Helper', 'Layout');
		
		if ($this->controller->helpers) {
            // d($this->controller->helpers);
		    foreach ($this->controller->helpers as $helperName) {
                // $friendlyName = Inflector::variable(Inflector::underscore($helperName.'Helper'));
                // $$friendlyName =& $this->controller->$helperName;
                // if ($helperName == 'Form') $form =& $this->controller->$helperName;
		        $this->$helperName = GummRegistry::get('Helper', $helperName);
                // $this->$helperName =& $this->controller->$helperName;
		    }
		}
		
		extract($this->__passedArgs, EXTR_SKIP);
		
		$viewFile = $this->_getViewFileName($viewFile);
		
		ob_start();
		include($viewFile);
		$contentHtml = ob_get_clean();
		
		$this->assign('content', $contentHtml);
		
		if ($this->__parents) {
		    ob_start();
		    $this->__assignOverwrite = false;
		    foreach ($this->__parents as $parentViewFile) {
		        include($parentViewFile);
		    }
		    $content_for_layout = ob_get_clean();
		} else {
		    $content_for_layout = $contentHtml;
		}
		
		if ($this->controller->layout) {
			require_once(GUMM_LAYOUTS . $this->controller->layout . '.' . $this->controller->templateExtension);
		} else {
			echo $content_for_layout;
		}
	}
	
	public static function renderElement($name, $params=array(), $output=true) {
	    $_this = self::getInstance();
	    
	    return $_this->element($name, $params, $output);
	}
	
	public function element($name, $params=array(), $output=true) {
	    if (!$this->controller) {
	        $this->controller = GummRegistry::get('Controller', 'Posts');
		    foreach ($this->controller->helpers as $helperName) {
		        $this->$helperName = GummRegistry::get('Helper', $helperName);
		    }
	    }
	    $this->__passedArgs = $params;
	    $name = explode('/', $name);
	    $name = implode(DS, $name);
		$form = GummRegistry::get('Helper', 'Form');
		$gummWpHelper = GummRegistry::get('Helper', 'Wp');
		$gummMediaHelper = GummRegistry::get('Helper', 'Media');
		$gummHtmlHelper = GummRegistry::get('Helper', 'Html');
		$gummTextHelper = GummRegistry::get('Helper', 'Text');
		extract($params, EXTR_SKIP);
		
		ob_start();
		include(GUMM_ELEMENTS . $name . '.gtp');
		$contentHtml = ob_get_clean();
		$this->assign('content', $contentHtml);
		
		if ($this->__parents) {
		    ob_start();
		    $this->__assignOverwrite = false;
		    foreach ($this->__parents as $parentViewFile) {
		        include($parentViewFile);
		    }
		    $outputHtml = ob_get_clean();
            // d($outputHtml);
		} else {
		    $outputHtml = $contentHtml;
		}
        // $outputHtml = ob_get_clean();
		
		if (!$output) {
			return $outputHtml;
		} else {
			echo $outputHtml;
		}
	}
	
	private function _getViewFileName($viewFile=null) {
	    $viewFile = ($viewFile) ? $viewFile : $this->action;
		$viewFile = GUMM_VIEWS . str_replace('_controller', '', Inflector::underscore(get_class($this->controller))) . DS . Inflector::underscore($viewFile) . '.' . $this->templateExtension;
		
        return $viewFile;
	}
	
	private function importHelpers() {
		App::import('Helper', 'Form');
		App::import('Helper', 'Wp');
	}
	
	protected function extend($name) {
        // $this->__extends = true;
	    $this->__parents[$name] = GUMM_VIEWS . $name . '.' . $this->templateExtension;
        // extract($this->__passedArgs);
        // require_once();
	}
	
	protected function assign($context, $content) {
	    $assign = true;
	    if (!$this->__assignOverwrite && isset($this->__assignedVars[$context])) {
	        $assign = false;
	    }
	    if ($assign) {
            $this->__assignedVars[$context] = $content;
	    }
	}
	
	protected function start($context) {
	    $assign = true;
	    if (!$this->__assignOverwrite && isset($this->__assignedVars[$context])) {
	        $assign = false;
	    }
        if ($assign) {
    	    $this->__assignContext = $context;
        }
	    ob_start();
	}
	
	protected function end() {
	    $assign = true;
	    if (!$this->__assignOverwrite && isset($this->__assignedVars[$this->__assignContext])) {
	        $assign = false;
	    }
        if ($assign) {
        	$this->__assignedVars[$this->__assignContext] = ob_get_clean();
    	    $this->__assignContext = null;
	    } else {
	        ob_end_clean();
	    }
	}
	
	protected function fetch($context) {
	    $result = false;
	    if (isset($this->__assignedVars[$context])) {
	        $result = $this->__assignedVars[$context];
	    }
	    
	    return $result;
	}

}
?>