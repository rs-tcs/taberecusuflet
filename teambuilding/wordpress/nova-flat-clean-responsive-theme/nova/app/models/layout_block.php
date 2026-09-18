<?php
class LayoutBlockModel extends GummModel {
    
    /**
     * @var array
     */
    private $_schema = array(
        'background_image' => '',
        'background_repeat' => 'no-repeat',
        'background_position_left' => 'left',
        'background_position_top' => 'top',
    );
    
    /**
     * @var string
     */
    private $_layerIdSchema = 'layer-%d';

    /**
     * @var int
     */
    private $_layerBlockDepth = 7;
    
    /**
     * @var string
     */
    private $_texturesPath;
    
    /**
     * @param array $data
     * @return array
     */
    public function beforeSave($data) {
        $dataToSave = array();
        
        
        $layoutBlockGroups = array();
        foreach ($data as $layoutType => $layoutBlock) {
            foreach ($layoutBlock as $blockLayerId => $blockData) {
                $groupName = $blockLayerId;
                if (preg_match("'(.*)\-([0-7]{1})$'", $blockLayerId, $match)) {
                    $groupName = $match[1] . '-group';
                }
                $layoutBlockGroups[$layoutType][$groupName][$blockLayerId] = $blockData;
            }
        }
        $data = array();
        foreach ($layoutBlockGroups as $layoutType => $layoutGroup) {
            foreach ($layoutGroup as $groupName => $groupData) {
                if (count($groupData) > 1) {
                    $counter = $this->_layerBlockDepth;
                    foreach ($groupData as $blockLayerId => $blockData) {
                        $newBlockLayerId = str_replace('-group', '', $groupName) . '-' . $counter;
                        $data[$layoutType][$newBlockLayerId] = $blockData;
                        $counter--;
                    }
                } else {
                    $data[$layoutType][$groupName] = reset($groupData);
                }
            }
        }
        
        foreach ($data as $layoutType => $layoutBlock) {
            foreach ($layoutBlock as $blockLayerId => $blockData) {
                
                if (is_array($blockData) && isset($blockData['backgrounds']) && is_array($blockData['backgrounds'])) {
                    
                    if (isset($blockData['backgrounds']['background-position-left']) && isset($blockData['backgrounds']['background-position-top'])) {
                        $blockData['backgrounds']['background-position'] = $blockData['backgrounds']['background-position-left'] . ' ' . $blockData['backgrounds']['background-position-top'];
                        unset($blockData['backgrounds']['background-position-left']);
                        unset($blockData['backgrounds']['background-position-top']);
                    }
                    $blockData['backgrounds'] = array_merge(array(
                        'background-image' => '',
                        'background-color' => '',
                        'background-position' => '',
                        'background-repeat' => '',
                    ), $blockData['backgrounds']);
                    if (!$blockData['backgrounds']['background-image']) {
                        unset($blockData['backgrounds']['background-position']);
                        unset($blockData['backgrounds']['background-repeat']);
                    }
                    $dataToSave[$layoutType][$blockLayerId] = $blockData;

                } else {
                    $dataToSave[$layoutType][$blockLayerId] = $blockData;
                }
            }
        }
        
        // d('a');
        
        return $dataToSave;
    }
    
    /**
     * @param int $optionId
     * @return string
     */
    public function getNewBlockLayerId($optionId) {
        $gummWpHelper = GummRegistry::get('Helper', 'Wp');
        
        $newBlockLayerId = sprintf($this->_layerIdSchema, $this->_layerBlockDepth);
        
        $blockData = $gummWpHelper->getOption($optionId);
        if ($blockData && is_array($blockData)) {
            for ($i=$this->_layerBlockDepth; $i>=1; $i--) {
                $currLayerId = sprintf($this->_layerIdSchema, $i);
                if (!isset($blockData[$currLayerId])) {
                    $newBlockLayerId = $currLayerId;
                    break;
                }
            }
        }

        return $newBlockLayerId;

    }

    
}
?>