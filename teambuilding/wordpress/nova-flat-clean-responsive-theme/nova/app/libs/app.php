<?php
class App {
    
    public static function uses($class, $namespace) {
        $path = GUMM_BASE . str_replace('/', DS, $namespace) . DS . Inflector::camelize($class) . '.php';
        require_once($path);
    }
    
    /**
     * @param string $type
     * @param string $name
     * @param bool $safe
     * @return bool
     */
	public static function import($type, $name, $safe=false) {
	    $path = null;
		switch (strtolower($type)) {
		 case 'model':
		    $path = GUMM_MODELS . strtolower(Inflector::underscore($name)) . '.php';
		    break;
		 case 'controller':
			$path = GUMM_CONTROLLERS . strtolower(Inflector::underscore($name)) . '_controller.php';
			break;
		 case 'helper':
			$path = GUMM_LIBS . 'views' . DS . 'helpers' . DS . strtolower(Inflector::underscore($name)) . '.php';
			break;
		 case 'widget':
			$path = GUMM_WIDGETS . Inflector::underscore(str_replace('Widget', '', $name)) . '.php';
			break;
		 case 'component':
			$path = GUMM_LIB_COMPONENTS . Inflector::underscore(str_replace('Component', '', $name)) . '.php';
			break;
		 case 'core':
		    $path = GUMM_LIBS . strtolower(Inflector::underscore($name) . '.php');
		    break;
		 case 'config':
		    $path = GUMM_CONFIGS . strtolower(Inflector::underscore($name) . '.config');
		    break;
		 case 'editor':
		    $path = GUMM_LIBS . 'views' . DS . 'editors' . DS . strtolower(Inflector::underscore($name)) . '.php';
		    break;
		 case 'layoutelement':
		    $path = GUMM_LAYOUT_ELEMENTS . strtolower(Inflector::underscore($name)). '.php';
		    if (!is_file($path) && is_file(GUMM_LAYOUT_ELEMENTS_SINGLE . strtolower(Inflector::underscore($name)). '.php')) {
		        $path = GUMM_LAYOUT_ELEMENTS_SINGLE . strtolower(Inflector::underscore($name)). '.php';
		    }
		    break;
		 case 'vendor':
		    $path = GUMM_VENDORS . strtolower(Inflector::underscore($name)) . '.php';
		    break;
		 case 'view/layoutelementbase':
		    $path = GUMM_LIBS . 'views' . DS . 'LayoutElementBase' . DS . strtolower(Inflector::underscore($name)) . '.php';
		    break;
		}
		if ($safe && !is_file($path)) {
		    return false;
		} else {
		    require_once($path);
		    return true;
		}
	}
	
	/**
	 * @param string $type
	 * @param string $path
	 * @return array
	 */
	public static function objects($type, $path=null) {
	    $type = strtolower($type);
	    App::import('Core', 'GummFolder');
	    switch ($type) {
	     case 'controller':
	        if (!$path) $path = GUMM_CONTROLLERS;
            $Folder = new GummFolder($path);
	    }
	    
	    $objects = array();
	    if (isset($Folder)) {
	        $list = $Folder->read();
	        foreach ($list[1] as $objectFile) {
	            $objectName = str_replace('_' . $type, '', $objectFile);
	            $objectName = str_replace('.php', '', $objectName);
	            $objectName = Inflector::camelize($objectName);
                
                $objectClass = ($type == 'model') ? $objectName : $objectName . ucwords($type);
                
                // App::import($type, $objectName);
                $objects[$objectName] = $objectClass;
	        }
	    }
	    
	    return $objects;
	}
}
?>