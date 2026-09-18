<?php
class BackgroundPatternModel extends GummModel {
    
    /**
     * @vr array
     */
    private $_schema = array(
        'id' => '',
        'type' => '',
        'name' => '',
        'url' => '',
        'schemes' => ''
    );
    
    /**
     * @var string
     */
    private $_texturesPath;
    
    /**
     * @return void
     */
    public function __construct() {
        parent::__construct();
        
        $this->_texturesPath = GUMM_TEMPLATEPATH . DS . 'images' . DS . 'textures';
    }
    
    /**
     * @param string $type
     * @param array $conditions
     */
    public function find($type='all', array $conditions=array()) {
        $patterns = array();
        
        App::import('Core', 'GummFolder');
        $Folder = new GummFolder($this->_texturesPath);
        $contents = $Folder->read(true, false, true);

        foreach ($contents[0] as $patternTypeFullPath) {
            $patternType = basename($patternTypeFullPath);
            
            $Folder->cd($patternTypeFullPath);
            $patternTypeContents = $Folder->read(true, false, true);

            $patternPaths = array_merge($patternTypeContents[0], $patternTypeContents[1]);

            foreach ($patternPaths as $patternNameFullPath) {
                $patterns[] = $this->getPatternFromPath($patternNameFullPath, $patternType);
            }
            
            extract($conditions, EXTR_OVERWRITE);
            $patterns = $this->_select($patterns, $conditions);

        }
        

        return $this->_result($type, $patterns);
    }
    
    /**
     * @param mixed $pattern string or pattern array
     * @param string $schemeName
     * @return mixed array if found false otherwise
     */
    public function getPatternScheme($pattern, $schemeName) {
        if (is_string($pattern)) {
            $pattern = $this->find('first', array('conditions' => array('name' => $pattern)));
        }
        if (!$pattern) {
            return false;
        }
        if (!isset($pattern['schemes'][$schemeName])) {
            return false;
        }
        
        return $pattern['schemes'][$schemeName];
    }
    
    /**
     * @param string $url
     * @param string $type
     * @return mixed string for specified type, else array containing all info
     */
    public function getPatternDataFromUrl($url, $type=null) {
        $data = array(
            'isGummPattern' => false,
            'transparency' => false,
            'weight' => false,
            'scheme' => false,
        );
        
        if (strpos($url, GUMM_THEME_URL . '/images/textures') !== false) {
            $data['isGummPattern'] = true;
        }
        
        if(preg_match("'^" . GUMM_THEME_URL. "images/textures/(.*)/(.*)/(.*)/.*'imsU", $url, $patternNameMatches)) {
            $data['scheme'] = $this->getPatternScheme($patternNameMatches[2], $patternNameMatches[3]);
        }
        
        $pi = pathinfo(str_replace('/', DS, $url));

        if (preg_match("'^.*\-([0-9]{3})$'U", $pi['filename'], $patternWeightMatches)) {
            $data['weight'] = (int) $patternWeightMatches[1];
        }
        
        if ($type && isset($data[$type])) {
            $data = $data[$type];
        }

        return $data;
    }
    
    /**
     * @param string
     * @return array
     */
    private function getPatternFromPath($path, $type=null) {
        if (!class_exists('GummFolder')) App::import('Core', 'GummFolder');
        
        $pi = pathinfo($path);
        $patternName = $pi['filename'];
        
        // if (is_file($path)) {
        $pattern = array_merge($this->_schema, array(
            'id' => Inflector::slug($patternName, '-'),
            'type' => $type,
            'name' => $patternName,
            'url' => GUMM_THEME_URL . '/images/textures/' . $type . '/' . $patternName,
            'schemes' => array(),
        ));
        
        if (is_dir($path)) {
            $Folder = new GummFolder($path);
            $contents = $Folder->read(true, false, true);
            
            $defaultPatternUrl = $pattern['url'];
            
            if ($contents[0]) {
                foreach ($contents[0] as $patternSchemeFullPath) {
                    $patternSchemeName = basename($patternSchemeFullPath);
                    
                    $Folder->cd($patternSchemeFullPath);
                    $schemeContents = $Folder->read(true, false, true);
                    
                    $pattern['schemes'][$patternSchemeName] = array(
                        'weights' => array()
                    );
                    foreach ($schemeContents[1] as $schemeWeightFullPath) {
                        $pi = pathinfo($schemeWeightFullPath);
                        if ($pi['extension'] != 'png') continue;
                        $weight = 100;
                        if (preg_match("'.*\-([0-9]+)'", $pi['basename'], $weight)) {
                            $weight = (int) $weight[1];
                        }
                        $pattern['schemes'][$patternSchemeName]['weights'][$weight] = array(
                            'value' => $weight,
                            'url' => $pattern['url'] . '/' . $patternSchemeName . '/' . $pi['basename'],
                        );
                        
                        if ($patternSchemeName == 'dark' && (int) $weight == 100) {
                            $defaultPatternUrl = $pattern['schemes'][$patternSchemeName]['weights'][$weight]['url'];
                        }

                    }
                }
                $pattern['url'] = $defaultPatternUrl;
            } elseif ($contents[1]) {
                $pattern['url'] .= basename($contents[1][0]);
            }
            
        }

        return $pattern;
    }

}
?>