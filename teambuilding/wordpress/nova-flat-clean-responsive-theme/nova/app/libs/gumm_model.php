<?php
abstract class GummModel extends GummObject {
    
    /**
     * @var string
     */
    protected $primaryKey = 'id';
    
    /**
     * @var string
     */
    protected $identityField = 'name';
    
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var bool
     */
    public $mergeOnSave = true;
    
    /**
     * @var array
     */
    protected $inRelation = array();
    
    /**
     * @var int
     */
    public $id;
    
    /**
     * Constructor for the GummModel class
     * Initializes the $data parameter, cleans the security fields from the $_POST variable
     * 
     * @return void
     */
    public function __construct() {
        parent::__construct();
        
        if (!$this->name) $this->name = str_replace('Model', '', get_class($this));
        if (isset($this->data['_mergeonsave']) && (int) $this->data['_mergeonsave'] === 0) $this->mergeOnSave = false;
        
        GummRegistry::updateRegistry('Model_' . $this->name, $this);
        
        foreach ($this->inRelation as $modelInRelation) {
            if (!isset($this->$modelInRelation)) $this->$modelInRelation = GummRegistry::get('Model', $modelInRelation);
        }
    }
    
    /**
     * @param array $data
     * @return bool
     */
    public function saveAll(array $data=array()) {
        if (!$data) $data = $this->data;
        if (!$data) return false;
        
        foreach ($data as $model => $modelData) {
            if (!in_array($model, $this->inRelation) && $model !== $this->name) continue;
            
            if ($model === $this->name) {
                $ModelRef =& $this;
            } else {
                $ModelRef =& $this->$model;
            }
            
            $modelData = $ModelRef->beforeSaveAll($modelData);
            
            foreach ($modelData as $entryId => $entryData) {
                if (is_array($entryData) && isset($entryData['id'])) $ModelRef->id = $entryData['id'];
                else $ModelRef->id = $entryId;
                
                $ModelRef->save($entryData);
            }
        }
    }
    
    /**
     * @param mixed $data
     * @return bool
     */
    public function save($data='') {
        $data = $this->beforeSave($data);
        
        if (Configure::read('themeSupport.skins') === true) {
            if ($this->id == GUMM_THEME_PREFIX . '_styles') {
                $skin = GummRegistry::get('Model', 'Skin')->getActiveSkin();
                $data = array($skin => $data);
                
                if (!$this->mergeOnSave) {
                    $existingStylesData = get_option($this->id);
                    if ($existingStylesData && is_array($existingStylesData)) {
                        $data = array_merge($existingStylesData, $data);
                    }
                }
            }
        }

        if ($this->mergeOnSave) {
            $existingData = get_option($this->id);
            
            $originData = $data;
            $dimensionCount = Set::countDim($existingData, true);
            if ($dimensionCount === 1 && isset($data[0])) {
                
            } elseif ($existingData && is_array($existingData) && is_array($data)) {
                $data = Set::merge($existingData, $data);
            }
        }
        
        $success = update_option($this->id, $data);

        $this->afterSave($data);
        
        return $success;
    }
    
    /**
     * Magic function to handle various calls to the model
     * 
     * @param string $method
     * @param array $arguments
     * @return void
     */
    public function __call($method, $arguments) {
        if (method_exists($this, $method)) {
            $this->$method();
        } elseif (strpos($method, 'findBy') === 0) {
            $conditionKey = strtolower(str_replace('findBy', '', $method));
            $conditionVal = reset($arguments);
            $conditions = array($conditionKey => $conditionVal);
            
            return $this->find('first', array('conditions' => $conditions));
            
            // return call_user_func_array(array(&$this, 'find'), array('first', array('conditions' => $conditions)));
        }
    }
    
    /**
     * @param mixed $data
     * @return array
     */
    public function beforeSaveAll($data) {
        return $data;
    }
    
    /**
     * @param mixed $data
     * @return array
     */
    public function beforeSave($data) {
        return $data;
    }
    
    /**
     * @param mixed $data
     * @return void
     */
    public function afterSave($data) {
        
    }
    
    /**
     * @param array $data
     * @param array $conditions
     * @param mixed $fields
     * @return array
     */
    protected function _select($data, $conditions, $fields='') {
        foreach ($conditions as $key => $val) {
            if (!$val) continue;

            $keyParts = explode(' ', $key);
            if (count($keyParts) == 1) {
                foreach ($data as $dataKey => $entry) {
                    if (!is_array($entry)) continue;
                    if (!isset($entry[$key]) || isset($entry[$key]) && $entry[$key] != $val) unset($data[$dataKey]);
                }
            } elseif (count($keyParts) == 2) {
                $keyVal = $keyParts[0];
                $keyCond = $keyParts[1];
                switch ($keyCond) {
                 case '!=':
                    foreach ($data as $dataKey => $entry) {
                        if (is_array($entry) && isset($entry[$keyVal])) {
                            // debug($keyVal);
                            // debug($val);
                            // d($entry);
                            if (in_array($entry[$keyVal], (array) $val)) {
                                // d('y');
                                unset($data[$dataKey]);
                                
                            } 
                        }
                    }
                    break;
                }
            }
        }
        
        if ($fields) {
            
        }

        return $data;
    }
    
    /**
     * @param string type
     * @param array $data
     * @return array
     */
    protected function _result($type, $data) {

        switch ($type) {
         case 'first':
            $result = ($data) ? reset($data) : array();
            break;
         case 'list':
            if ($data && is_array($data)) {
                foreach ($data as $entry) {
                    $result[$entry[$this->primaryKey]] = $entry[$this->identityField];
                }
            }
            break;
         default:
            $result = $data;
            break;
        }
        
        return $result;
    }
}
?>