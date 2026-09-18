<?php
class SidebarModel extends GummModel {
    
    const DEFAULT_CONFIG_KEY = 'defaults.sidebars';
    
    /**
     * @var array
     */
    public $inRelation = array('Option', 'PostMeta');
    
    /**
     * @var array
     */
    private $_layoutSidebarsSchema = array(
        'left' => '',
        'right' => '',
    );
    
    /**
     * @var string
     */
    private $_customSidebarsOptionId = '_sidebars';
    
    /**
     * @param string $type
     * @param array $conditions
     * @return array
     */
    public function find($type='all', $conditions=array()) {
        $conditions = array_merge(array(
            'conditions' => array(),
            'sort' => 'descending'
        ), $conditions);
        extract($conditions, EXTR_OVERWRITE);
        
        global $wp_registered_sidebars;
        $wpRegisteredSidebars = ($wp_registered_sidebars) ? $wp_registered_sidebars : array();
        
        $sidebars = (array) $wpRegisteredSidebars;
        
        $customSidebars = $this->Option->find($this->_customSidebarsOptionId);

        if (!$sidebars && $customSidebars) $sidebars = $customSidebars;
        
        foreach ($sidebars as $sidebarId => &$sidebar) {
            if ($customSidebars && array_key_exists($sidebarId, $customSidebars)) {
                $sidebar['custom'] = true;
            } else {
                $sidebar['custom'] = false;
            }
        }
        if ($sort == 'reverse') {
            $sidebars = array_reverse($sidebars, true);
        }
        
        if ($conditions) {
            $sidebars = $this->_select($sidebars, $conditions);
        }
        
        switch ($type) {
         case 'all':
            return $sidebars;
            break;
         case 'first':
            return ($sidebars) ? reset($sidebars) : array();
            break;
        }

    }
    
    /**
     * @param int $optionId
     * @param string $model
     * @return array
     */
    public function findForOption($optionId) {
        $sidebars = $this->Option->find($optionId);
        
        return $this->_mergeDefaultSidebarData($sidebars);
    }
    
    /**
     * @param int $postId
     * @param string $metaKey
     * @return array
     */
    public function findForPost($postId, $metaKey) {
        $sidebars = $this->PostMeta->find($postId, $metaKey);
        
        // d($sidebars);
        
        return $this->_mergeDefaultSidebarData($sidebars);
    }
    
    /**
     * @param array $sidebars
     * @return array
     */
    private function _mergeDefaultSidebarData($sidebars) {
        $_schema = $this->getSchema();
        // $sidebars = $this->find('all');

        // $sidebarsForOption = $this->Option->find($optionId);
        
        if ($sidebars && is_array($sidebars)) {
            $sidebars = array_merge($_schema, $sidebars);
        } else {
            $sidebars = $_schema;
        }
        
        foreach ($sidebars as $orientation => &$sidebarId) {
            if ($sidebarId) {
                $sidebar = $this->findById($sidebarId);
                if ($sidebar) $sidebarId = $sidebar;
                else $sidebarId = false;
            }
        }
        
        return $sidebars;
    }
    
    /**
     * @param string $layoutKey
     * @param string $orientation
     * @return mixed array on success else boolean false
     */
    public function findForLayoutByOrientation($layoutKey, $orientation) {
        $optionId = $this->constructOptionId('sidebars', $layoutKey);
        
        if (preg_match("'^post-[a-z]+-([0-9]+)$'iU", $layoutKey, $layoutPostId)) {
            $sidebars = $this->findForPost($layoutPostId[1], $optionId);
        } else {
            $sidebars = $this->findForOption($optionId);
        }
        
        return (isset($sidebars[$orientation]) && $sidebars[$orientation]) ? $sidebars[$orientation] : false;
    }
    
    /**
     * @return array
     */
    public function getSchema() {
        return $this->_layoutSidebarsSchema;
    }
    
    /**
     * @param int $sidebarId
     * @return bool
     */
    public function delete($sidebarId) {
        $customSidebars = $modifiedSidebars = $this->find('all', array('conditions' => array('custom' => true)));

        foreach ($modifiedSidebars as $id => $data) {
            if ($id == $sidebarId) unset($modifiedSidebars[$id]);
        }
        
        $success = false;

        if ($customSidebars != $modifiedSidebars) {
            $optionId = $this->Option->getFullOptionId($this->_customSidebarsOptionId);
            update_option($optionId, $modifiedSidebars);
            $success = true;
        }
        
        return $success;
    }
    
    /**
     * @param string $orientation
     * @param array $sidebar
     * @return void
     */
    public function storeDefaultForOrientation($orientation, $sidebar) {
        if ($orientation && $sidebar) {
            Configure::write(self::DEFAULT_CONFIG_KEY . '.' . $orientation, $sidebar);
        }
    }
    
    /**
     * @return mixed array on success, else boolean false
     */
    public function getDefaultForOrientation($orientation) {
        return Configure::read(self::DEFAULT_CONFIG_KEY . '.' . $orientation);
        
    }

}
?>