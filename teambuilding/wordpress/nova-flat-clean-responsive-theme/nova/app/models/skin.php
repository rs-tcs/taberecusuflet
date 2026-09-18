<?php
class SkinModel extends GummModel {
    const OPTION_ID = '_skins';
    const OPTION_ACTIVE_ID = '_activeThemeSkin';
    
    /**
     * @var array
     */
    public $inRelation = array('Option');
    
    /**
     * @var string
     */
    public $lastModifiedId = null;
    
    /**
     * @var array
     */
    private $_schema = array(
        'default' => array(
            'id' => 'default',
            'name' => 'Default',
            'css' => '',
            'inlineCss' => '',
        ),
    );
    
    /**
     * @var string
     */
    public $activeSkinId;
    
    /**
     * @param string $type
     * @param array $conditions
     * @return array
     */
    public function find($type='all', $conditions=array()) {
        $conditions = array_merge(array(
            'conditions' => array(),
            'sort' => 'descending',
            'fields' => '*'
        ), $conditions);
        extract($conditions, EXTR_OVERWRITE);
        
        $skins = $this->Option->find(self::OPTION_ID);
        
        if (!$skins || !is_array($skins)) $skins = $this->_schema;
        else $skins = array_merge($this->_schema, $skins);
        
        $skins = $this->_select($skins, $conditions, $fields);
        
        if ($type == 'all') {
            if (!$themeStyles = get_option(GUMM_THEME_PREFIX . '_styles')) {
                $themeStyles = array();
            }

            foreach ($skins as $skinId => &$skin) {
                if (isset($themeStyles[$skinId]) && isset($themeStyles[$skinId]['color_options'])) {
                    $skin['color_options'] = $themeStyles[$skinId]['color_options'];
                } else {
                    $skin['color_options'] = Configure::read('themeDefaultColors');
                }
            }
        }
        
        return $this->_result($type, $skins);
    }
    
    /**
     * @param string $id
     * @param bool $temp
     * @return bool
     */
    public function setActiveSkin($id, $temp=false) {
        if (!$temp) {
            $optionId = $this->gummOptionId(self::OPTION_ACTIVE_ID);
            return update_option($optionId, $id);
        } else {
            $this->activeSkinId = $id;
            return true;
        }
    }
    
    /**
     * @param string $fields;
     * @return mixed
     */
    public function getActiveSkin($fields='id') {
        $activeId = $this->activeSkinId;
        if (!$activeId) {
            $activeId = $this->Option->find(self::OPTION_ACTIVE_ID);
        }
        
        if ($activeId == Configure::read('Skin.customUserSkinId')) {
            $skin = array_merge($this->_schema['default'], array(
                'id' => $activeId,
                'name' => __('My Skin', 'gummfw'),
                'css' => '',
                'inlineCss' => '',
            ));
        }
        elseif (!$skin = $this->find('first', array('conditions' => array('id' => $activeId)))) {
            $skin = reset($this->_schema);
        }
        
        $return = array_merge($this->_schema['default'], $skin);
        if ($fields != 'all' && isset($return[$fields])) {
            $return = $return[$fields];
        }
        
        return $return;
    }
    
    /**
     * @return string|boolean false on failure
     */
    public function getActiveSkinCssUrl() {
        $url = false;
        if ($file = $this->getActiveSkin('css')) {
            if (is_file(GUMM_ASSETS . 'css' . DS . 'skins' . DS . $file)) {
                $url = GUMM_THEME_CSS_URL . 'skins/' . $file;
            }
        }
        return $url;
    }
    
    /**
     * @param array $data
     * @return bool
     */
    public function save($data = '') {
        $optionId = $this->gummOptionId(self::OPTION_ID);
        
        if (!isset($data['Skin']) || (isset($data['Skin']) && !isset($data['Skin'][$optionId]))) return false;
        
        foreach ($data['Skin'][$optionId] as &$skin) {
            $skin = array_merge(array(
                'id' => uniqid(),
                'name' => '',
                'css' => '',
            ), $skin);
        }
        
        $lastModifiedSkin = end($data['Skin'][$optionId]);
        $this->lastModifiedId = $lastModifiedSkin['id'];
        
        $skins = $this->find('all');
        $skins = Set::merge($skins, $data['Skin'][$optionId]);
        
        $this->id = $optionId;
        $this->mergeOnSave = false;
        
        return parent::save($skins);
    }
    
    /**
     * @param string $id
     * @return bool
     */
    public function remove($id) {
        if (!$id) return false;
        
        $optionId = $this->gummOptionId(self::OPTION_ID);
        
        $skins = $this->find('all');
        
        if (isset($skins[$id])) {
            unset($skins[$id]);
            
            $this->id = $optionId;
            $this->mergeOnSave = false;
            
            return parent::save($skins);
        }
        
        return false;
    }
}
?>