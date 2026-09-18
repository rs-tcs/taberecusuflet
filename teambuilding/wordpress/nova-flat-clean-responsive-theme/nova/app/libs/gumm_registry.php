<?php
class GummRegistry {
    
    /**
     * @var array
     */
    private $__registry = array();
    
    /**
     * Returns a singleton instance of the GummRegistry class.
     *
     * @return GummRegistry instance
     * @access public
     */
    public static function &getInstance() {
        static $instance = array();
        if (!$instance) {
		    $_inst = new GummRegistry();
			$instance[0] =& $_inst;
        }
        return $instance[0];
    }
    
    /**
     * @return array
     */
    public static function getRegistry() {
        $instance =& GummRegistry::getInstance();
        
        return $instance->__registry;
    }
    
    /**
     * @param array $registry
     * @return void
     */
    public static function updateRegistry($key, $val) {
        $instance =& GummRegistry::getInstance();
        
        $instance->__registry[$key] = $val;
    }
    
    /**
     * @return object
     */
    public static function get($type, $name) {
        $name = Inflector::camelize($name);
        
        $registry = GummRegistry::getRegistry();
        
        $regKey = $type . '_' . $name;
        if (isset($registry[$regKey])) return $registry[$regKey];
        
        App::import($type, $name);
        
        $objName = false;
        switch (strtolower($type)) {
         case 'model':
            $objName = $name . 'Model';
            break;
         case 'controller':
            $objName = $name . 'Controller';
            break;
         case 'helper':
            $objName = $name . 'Helper';
            break;
         case 'widget':
            $objName = $name;
            break;
         case 'component':
            $objName = $name . 'Component';
            break;
         case 'editor':
            $objName = $name . 'Editor';
            break;
        }
        
        $obj = new $objName;

        GummRegistry::updateRegistry($regKey, $obj);
        
        return $obj;
    }
}
?>