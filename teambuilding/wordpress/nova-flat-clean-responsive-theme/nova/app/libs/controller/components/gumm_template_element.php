<?php

if (!class_exists('GummFile')) {
    require GUMM_LIBS . 'gumm_file.php';
}

class GummTemplateElementComponent extends GummFile {
    
    /**
     * @var string
     */
    private $id;
    
    /**
     * @var string
     */
    public $title;
    
    /**
     * @var array
     */
    public $settings;
    
    /**
     * @var array
     */
    public $params = array();
    
    /**
     * @var array
     */
    public $fields = array();
    
    /**
     * @var string
     */
    private $editor = 'default';
    
    /**
     * @var LayoutEditor
     */
    private $fieldsEditor;
    
    /**
     * @var string
     */
    private $model = '';
    
    /**
     * @param string $path
     * @param array $elementParams
     * @return void
     */
    public function __construct($path, $params=array()) {
        parent::__construct($path);
        $data = $this->read();
        
        if (preg_match("'params = {.*}'ims", $data, $elementParams)) {
            $elementParams = preg_replace("'\s?\*\s?'imsU", ' ', $elementParams[0]);
            $elementParams = str_replace('params = ', '', $elementParams);
            $elementParams = json_decode($elementParams, true);
            
            if (isset($elementParams['fields'])) {
                $this->fields = $this->_filterParams((array) $elementParams['fields']);
            }
        }
        
        $params = Set::merge($elementParams, (array) $params);
        
        $this->_init((array) $params);
    }
    
    /**
     * @param string $id
     * @return string
     */
    public function id($id=null) {
        if ($id) $this->id = $id;
        
        return $this->id;
    }
    
    /**
     * @param string $model
     * @return string
     */
    public function model($model=null) {
        if ($model) $this->model = $model;
        
        return $this->model;
    }
    
    /**
     * @param array $params
     * @return void
     */
    private function _init(array $params=array()) {
        $this->params = $params;
        $params = $this->_filterParams(Set::merge(array(
            'id' => uniqid(),
            'name' => __('Unnamed Template Element', 'gummfw'),
            'settings' => array(
                'widthRatio' => 1,
            ),
            'editor' => $this->editor,
        ), $params));
        
        extract($params, EXTR_SKIP);
        $this->settings = $settings;
        $this->title = $name;
        $this->id = $id;
        $this->editor = $editor;
    }
    
    /**
     * @param array $params
     * @return array
     */
    private function _filterParams(array $params=array()) {
        $output = array();
        foreach ($params as $k => $param) {
            if (is_array($param)) $param = $this->_filterParams($param);
            if (is_string($param) && strpos($param, '__(') !== false) {
                if (preg_match("|__\('(.*)'\)|", $param, $stringToTranslate)) {
                    $param = $stringToTranslate[1];
                }
            }
            $output[$k] = $param;
        }
        
        return $output;
    }
    
    /**
     * @return bool
     */
    public function isEditable() {
        $editable = true;
        if (isset($this->params['editable']) && $this->params['editable'] === false) {
            $editable = false;
        }
        
        return $editable;
    }
    
    /**
     * @return LayoutEditor
     */
    public function getFieldsEditor() {
        if (!is_a($this->fieldsEditor, 'LayoutElement')) {
            $editorName = $this->editor;
            if ($editorName == 'default') $editorName = 'LayoutElement';

            $editorClass = $editorName . 'Editor';
            
            App::import('Editor', $editorName);
            $this->fieldsEditor = new $editorClass($this);
        }
        
        return $this->fieldsEditor;
    }
    
    /**
     * @param bool $safe
     * @return string
     */
    public function widthRatio($safe=false) {
        $ratio = $this->settings['widthRatio'];
        if ($safe) {
            $ratio = Inflector::slug($ratio);
        }
        
        return $ratio;
    }
    
    /**
     * @return void
     */
    public function display() {
        View::renderElement(basename(GUMM_LAYOUT_ELEMENTS) . DS . $this->name(), $this->settings);
    }
}
?>