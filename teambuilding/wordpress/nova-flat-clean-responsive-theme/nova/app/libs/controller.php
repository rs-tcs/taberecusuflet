<?php
class Controller extends GummObject {
	const WPNONCE = 'F325C812-DC8A-40E4-9306-BC4337F4B6C4';
	
	/**
	 * @var string
	 */
	public $name;
	
	/**
	 * @var array
	 */
	public $uses = array();
	
	/**
	 * @var array
	 */
	public $components = array(
		'RequestHandler',
	);
	
	/**
	 * @var array
	 */
	public $helpers = array(
	    'Form',
	    'Wp',
	    'Html',
	    'Js',
	    'Layout',
	    'Media',
	    'Text',
	);
	
	/**
	 * @var string
	 */
	public $action;
	
	/**
	 * @var bool
	 */
	public $autoRender = true;
	
	public $adminMenu = false;
	
	public $viewVars = array();
	
	protected $data = array();
	
	public $layout = false;
	
	public $hasRendered = false;
	
	public function __construct() {
		parent::__construct();
		
	    $thisclassVars = get_class_vars(get_class($this));
	    $selfClassVars = get_class_vars('Controller');
	    
	    $this->name = $this->getName();
	    
	    if (isset($thisClassVars['helpers'])) {
	        $this->helpers = array_merge($selfClassVars['helpers'], $thisClassVars['helpers']);
	    }
	    
	    foreach ($this->components as $component) {
	        $this->$component = GummRegistry::get('Component', $component);
	    }
		
		foreach ($this->uses as $model) {
		    $this->$model = GummRegistry::get('Model', $model);
		}
	}
	
	protected function set($one, $two=null) {
		if (is_array($one)) {
			foreach ($one as $k => $arg) {
				$this->viewVars[$k] = $arg;
			}
		} else {
			$this->viewVars[$one] = $two;
		}
	}
	
	public function startupProcess() {
	    $this->beforeRender();
	}
	
	public function shutdownProcess($dieOnAjax=true) {
	    $this->render();
	    $this->afterRender();

	    if ($this->RequestHandler->isAjax() && $dieOnAjax !== false) {
	        do_action('print_ajax_scripts');
	        die;
	    }
	}
	
	public function beforeRender() {}
	
	public function afterRender() {}
	
	public function render($file=null) {
		$View = new View($this);
		$View->render($file);
	}
	
    /**
     * @return bool
     */
    public function validates() {
        return true;
        // return (!isset($_REQUEST['_wpnonce']) || isset($_REQUEST['_wpnonce']) && !wp_verify_nonce($_REQUEST['_wpnonce'], $this::WPNONCE)) ? false : true;
    }
    
    /**
     * @param array $conditions
     * @return array
     */
    public function paginate($conditions=array()) {
        $modelName = Inflector::singularize($this->name);
        $Model = $this->$modelName;
        
        // debug($Model);
    }
    
    // ======= //
    // GETTERS //
    // ======= //
    
    /**
     * @return string
     */
    public function getName() {
        $underscoredName = Inflector::underscore(get_class($this));
        return Inflector::camelize(str_replace('_controller', '', $underscoredName));
    }
}
?>