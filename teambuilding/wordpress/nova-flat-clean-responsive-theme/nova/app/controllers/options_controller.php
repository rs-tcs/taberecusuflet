<?php
class OptionsController extends AppController {
    
    const WPNONCE = '763853E0-A48B-45AD-AC75-1AFEC6EAE471';
    
    /**
     * @var array
     */
    public $uses = array('Option', 'Media', 'LayoutBlock', 'Skin');
    
    /**
     * @var array
     */
	public $components = array(
		'RequestHandler',
		'Fonts',
		'Cookie',
	);

    private $options;
    
    private $_tabSchema = array(
        'id' => '',
        'title' => 'Tab Title',
        'toolbar' => '',
        'parent_id' => '',
    );
    
    private $_optionSchema = array(
        'name' => '',
        'description' => '',
        'important' => '',
        'id' => '',
        'default' => '',
        'tab_id' => '',
        'group' => '',
        'type' => '',
        'data' => '',
        'inputOptions' => '',
        'inputAttribtues' => '',
        'inputSettings' => '',
        'dependant' => '',
        'dependsOn' => '',
        'requestAction' => '',
        'adminActions' => '',
        'model' => 'Option',
    );

    public function __construct() {
        parent::__construct();
    }
    
    public function parseArrayToString($array) {
        if (!is_array($array)) return $array;
        
        $string = 'array(' . PHP_EOL;
        foreach ($array as $key => $val) {
            $string .= "\t'{$key}'" . ' => ';
            if (is_array($val)) {
                $string .= $this->parseArrayToString($val);
            } elseif ($val === true){
                $string .= "true";
            } elseif ($val === false) {
                $string .= "false";
            } else {
                $string .= "'{$val}'";
            }
            $string .= ', ' . PHP_EOL;
            
        }
        $string .= ')' . PHP_EOL;
        
        return $string;
        // debug($string);
    }
    
    private function writeConfigOptions($options=array(), $type='all') {
        // $options = ()$this->getConfigOptions();
        // $options['options'] = array();
        // update_option(GUMM_THEME_PREFIX . '__gumm_options', $options);
        
        // d($options);
        
        $theConfigString = "<?php" . PHP_EOL;
        
        if (isset($options['tabs'])):
        
        $theConfigString .= "Configure::write('admin.options.tabs', array(" . PHP_EOL;
        foreach ($options['tabs'] as $tab) {
            $theConfigString .= "\t";
            $theConfigString .= "'{$tab['id']}' => array(" . PHP_EOL;
            
            foreach ($tab as $key => $value) {
                $theConfigString .= "\t\t";
                if (in_array($key, array('title'))) {
                    $theConfigString .= "'$key' => __('$value', 'gummfw')," . PHP_EOL;
                } else {
                    $theConfigString .= "'$key' => '$value'," . PHP_EOL;
                }
            }

            $theConfigString .= "\t";
            $theConfigString .= ")," . PHP_EOL;
        }
        $theConfigString .= '));' . PHP_EOL;
        
        $theConfigString .= PHP_EOL;
        
        endif;
        
        if (isset($options['options'])):
        
        $theConfigString .= "Configure::write('admin.options.options', array(" . PHP_EOL;
        foreach ($options['options'] as $option) {
            $theConfigString .= "\t";
            $theConfigString .= "array(" . PHP_EOL;
            
            foreach ($option as $key => $value) {
                if ($value === 'Array') $value = '';
                $theConfigString .= "\t\t";
                if (in_array($key, array('description', 'name'))) {
                    $value = addslashes(stripslashes($value));
                    $theConfigString .= "'$key' => __('$value', 'gummfw')," . PHP_EOL;
                } elseif (in_array($key, array('id', 'dependant', 'dependsOn'))) {
                    // TMP
                    $value = str_replace(GUMM_THEME_PREFIX . '_', '', $value);
                    
                    // if (strpos($value, 'style') === 0) {
                    //     $valueParts = explode('.', $value);
                    //     $firstKey = array_shift($valueParts);
                    //     $secondKey = array_shift($valueParts);
                    //     
                    //     
                    // }
                    if ($value)
                        $theConfigString .= "'$key' => GUMM_THEME_PREFIX . '_$value'," . PHP_EOL;
                    else
                        $theConfigString .= "'$key' => ''," . PHP_EOL;
                } else {
                    // if ($key == 'adminActions') $value = "array('save' => '#')";
                    
                    if (is_array($value)) {
                        // debug($value);
                        $value = $this->parseArrayToString($value);
                        // debug($value);
                        // d($value);
                    } else {
                        $value = stripslashes($value);
                    }
                    
                    if (strpos($value, 'array') !== false || strpos($value, '__(') !== false) {
                        $theConfigString .= "'$key' => $value," . PHP_EOL;
                    } else {
                        $theConfigString .= "'$key' => '$value'," . PHP_EOL;
                    }
                    // $theConfigString .= "'$key' => '$value'," . PHP_EOL;
                }
            }
            
            $theConfigString .= "\t";
            $theConfigString .= ")," . PHP_EOL;
        }
        
        $theConfigString .= "));" . PHP_EOL;
        

        
        endif;
        
        $theConfigString .= '?>';
        
        $theFile = GUMM_CONFIGS . 'options.config';
        // @fpc($theFile, $theConfigString);
    }
    
    public function admin_setup() {
        if ($this->data && isset($this->data['GummOptionSetup'])) {
            foreach ($this->data['GummOptionSetup']['options'] as &$option) {

                $requestAction =& $option['requestAction'];
                if (isset($requestAction['controller']) && $requestAction['controller'] && $requestAction['action']) {
                    if (isset($requestAction['namedKey'])) {
                        for ($i=0; $i<count($requestAction['namedKey']); $i++) {
                            if (!$requestAction['namedKey'][$i] || !$requestAction['namedVal'][$i]) continue;

                            $requestAction['namedParams'][$requestAction['namedKey'][$i]] = $requestAction['namedVal'][$i];
                        }
                        unset($requestAction['namedKey']);
                        unset($requestAction['namedVal']);
                    }
                } else {
                    $requestAction = '';
                }

            }
            
            $this->writeConfigOptions($this->data['GummOptionSetup']);
            update_option(GUMM_THEME_PREFIX . '__gumm_options', $this->data['GummOptionSetup']);
        }
        $options = $this->getConfigOptions();
        
        // d($options);
        
        // d($options);
        // $deprOptions = Configure::read('admin.optionsDepr');
        // $options['options'] = $deprOptions['options'];
        
        foreach ($options['options'] as &$option) {
            // debug($option['adminActions']);
            if (isset($option['adminActions'])) {
                if ($option['adminActions'] == 'Array' || !$option['adminActions'] && $option['adminActions'] != 'false') $option['adminActions'] = "array('save' => '#')";
            }

            if ($option['requestAction'] == 'Array') $option['requestAction'] = '';
        }
        
        // $this->writeConfigOptions($options);
        // update_option(GUMM_THEME_PREFIX . '__gumm_options', $options);

        $this->set(compact('options'));
        $this->render('admin_setup');
    }
    
    private function getConfigOptions() {
        $options = Configure::read('admin.options');
        // $options = get_option(GUMM_THEME_PREFIX . '__gumm_options');
        // if (!$options) $options = Configure::read('admin.options');
        
        if (!isset($options['options'])) $options['options'] = array();
        
        return $options;
    }
    
    private function getTab($tabId) {
        $options = $this->getConfigOptions();
        $tab = $this->_tabSchema;
        $tab['id'] = uniqid();
        
        foreach ($options['tabs'] as $currTab) {
            if ($currTab['id'] == $tabId) {
                $tab = $currTab;
                break;
            }
        }
        return $tab;
    }
    
    public function admin_setup_edit_tab($tabId=null) {
        if (!$tabId) $tabId = $this->RequestHandler->getNamed('tabId');
        if (!$tabId) $tabId = uniqid();
        
        $tab = $this->getTab($tabId);
        $_tabSchema = $this->_tabSchema;
        $this->set(compact('tab', '_tabSchema'));
    }
    
    public function admin_setup_edit_tab_options($tabId=null) {
        if (!$tabId) $tabId = $this->RequestHandler->getNamed('tabId');
        if (!$tabId) return;
        
        $options = $this->getConfigOptions();
        // d($options);
        $optionsForTab = array();
        foreach ($options['options'] as $option) {
            if (!$option) continue;
            if ($option['tab_id'] == $tabId) $optionsForTab[] = $option;
        }
        
        $_optionSchema = $this->_optionSchema;
        $_optionSchema['tab_id'] = $tabId;
        
        $this->set(compact('optionsForTab', '_optionSchema', 'tabId'));
    }
    
    public function admin_setup_edit_tab_option($tabId=null, $optionId=null) {
        if (!$tabId) $tabId = $this->RequestHandler->getNamed('tabId');
        if (!$tabId) return;
        if (!$optionId) $optionId = $this->RequestHandler->getNamed('optionId');
        
        $option = array();
        if ($optionId) {
            $options = $this->getConfigOptions();
            foreach ($options['options'] as $configOption) {
                // d($configOption);
                if ($configOption['id'] == $optionId) {
                    $option = array_merge($this->_optionSchema, $configOption);
                    break;
                }
            }
        }
        
        if (!$option) {
            $option = $this->_optionSchema;
            $option['id'] = uniqid();
            $option['tab_id'] = $tabId;
        } 
        
        // $_optionsSchema = $this->_optionsSchema;
        // $_optionsSchema['tab_id'] = $tabId;
        $this->set(compact('option'));

    }
    
    /**
     * Renders main Theme Options page
     * 
     * @return void
     */
    public function admin_index() {
        
        if (!$this->RequestHandler->isAjax()) {
            $this->adminMenu = array('subPage' => 'Theme Options');
        }
        
        $adminOptions = $this->getThemeOptions();
        
        $tabs = array();
        $childTabs = array();
        foreach ($adminOptions['tabs'] as $tabId => $tabItem) {
            if ($tabItem['parent_id']) {
                $childTabs[$tabId] = $tabItem;
            } else {
                $tabs[$tabId] = $tabItem;
            }
        }
        
        foreach ($childTabs as $tabId => $tabItem) {
            $parentId = $tabItem['parent_id'];
            if (isset($tabs[$parentId])) {
                if (!isset($tabs[$parentId]['tabs'])) $tabs[$parentId]['tabs'] = array();
                $tabs[$parentId]['tabs'][$tabId] = $tabItem;
            }
        }
        
        $adminOptions['tabs'] = $tabs;
        
        $currentSkin = $this->Skin->getActiveSkin('name');
        
        $this->set(compact('adminOptions', 'currentSkin'));
    }
    
    
    /**
     * Saves options data
     * 
     * @return json | redirects
     */
    public function admin_save() {
        $this->autoRender = false;
        
        if (!$this->validates()) die(__('Security check failed.', 'gummfw'));
        
        if ($this->data) {
            $this->Option->saveAll();
        }
        
        if ($this->RequestHandler->isAjax()) {
            echo json_encode(array('msg' => __('Options saved.', 'gummfw')));
            exit;
        }
    }
    
    /**
     * @return string
     */
    public function admin_font_manager() {
        $optionId = $this->RequestHandler->getNamed('optionId');
        $fontSource = $this->RequestHandler->getNamed('fontSource');
        
        switch ($fontSource) {
         case 'google':
            $fontsList = $this->Fonts->getGoogleFontsList();
            break;
         default:
            $fontsList = $this->Fonts->getBrowserFontsList();
            break;
        }
        
        $this->set(compact('optionId', 'fontsList', 'fontSource'));
    }
    
    /**
     * @return array
     */
    private function getThemeOptions() {
        $options = Configure::read('admin.options');
        if (!is_array($options)) $options = array();
        if (!isset($options['options'])) $options['options'] = array();
        if (!isset($options['tabs'])) $options['tabs'] = array();
        
        return $options;
    }

}
?>