<?php
class ShortcodeModel extends GummModel {
    
    /**
     * @return void
     */
    public function __construct() {
        parent::__construct();
        
        App::import('Config', 'Shortcodes');
    }
    
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
        
        $shortcodes = Configure::read('shortcodes');
        $shortcodes = $this->_select($shortcodes, $conditions);
        
        foreach ($shortcodes as &$shortcode) {
            $shortcode['icon'] = $this->getIconForShortcode($shortcode);
        }
        
        $result = array();
        switch ($type) {
         case 'all':
            $result = $shortcodes;
            break;
         case 'first':
            $result = (array) reset($shortcodes);
            break;
        }

        return $result;
    }
    
    /**
     * @param string $shortcodeId
     * @return array
     */
    public function getChildrenForShortcode($shortcodeId) {
        $shortcode = $this->find('first', array('conditions' => array('id' => $shortcodeId)));
        $children = array();
        if (isset($shortcode['types']) && $shortcode['types']) {
            foreach ($shortcode['types'] as $type => $name) {
                $childShortcode = array(
                    'id' => $shortcode['id'] . '-' . $type,
                    'parent_id' => $shortcode['id'],
                    'name' => $name,
                    'type' => $type,
                    'types' => false,
                    'editor' => isset($shortcode['editor']) ? $shortcode['editor'] : null,
                    'attributes' => isset($shortcode['attributes']) ? $shortcode['attributes'] : array(),
                    'attributesOptions' => isset($shortcode['attributesOptions']) ? $shortcode['attributesOptions'] : array(),
                );
                $childShortcode['icon'] = $this->getIconForShortcode($childShortcode);
                $children[] = $childShortcode;
            }
        }
        
        return $children;
    }
    
    /**
     * @return array
     */
    public function findListTypes() {
        $results = array();
        App::import('Core', 'GummFile');
        $CssFile = new GummFile(get_stylesheet_directory() . DS . 'style.css');

        $cssContents = $CssFile->read();

        if (preg_match("'/\* -{3,} list styles -{3,} \*/(.*)/\* -{3,} end list styles -{3,} \*/'imsU", $cssContents, $listDeclarationMatches)) {
            if (preg_match_all("'ul\.sc-list\.([a-zA-Z-_]+)\s.*\{.*background-image\s?\:\s?url\((.*)\).*\}'imsU", $listDeclarationMatches[1], $listTypesMatches)) {

                for ($i=0; $i<count($listTypesMatches[0]); $i++) {
                    $results[] = array(
                        'name' => Inflector::humanize($listTypesMatches[1][$i]),
                        'class' => $listTypesMatches[1][$i],
                        'icon' => array(
                            'url' => GUMM_THEME_URL . '/' . $listTypesMatches[2][$i],
                            'repeat' => 'no-repeat',
                            'position' => 'center',
                        ),
                        'types' => false,
                    );
                }
            }
        }
        
        return $results;
    }
    
    /**
     * @param array $shortcode
     */
    protected function getIconForShortcode($shortcode) {
        $url = GUMM_THEME_IMG_URL . 'shortcodes';
        $url .= '/icon-' . $shortcode['id'] . '.png';
        // d($shortcode);
        // d($imgUrl);
        return array(
            'url' => $url,
            'repeat' => 'no-repeat',
            'position' => 'center',
        );
    }
    
}
?>