<?php
class OptionModel extends GummModel {
    /**
     * @var array
     */
    public $inRelation = array('LayoutBlock', 'Media', 'Sidebar');
    
    
    /**
     * @param string $optionId
     * @param array $options
     * @return mixed False if option not set
     */
	public function find($optionId, $options=array()) {
	    $options = array_merge(array(
	       'booleanize' => false,
	    ), $options);
	    $optionId = $this->gummOptionId($optionId);
        
        if (strpos($optionId, '.') !== false) {
            $optionPath = explode('.', $optionId);
            
            $rootOptionId = array_shift($optionPath);
            $rootValue = get_option($rootOptionId);
            
            if ($rootValue) {
                $optionXPath = implode('/', $optionPath);
                $extractedValue = Set::extract('/Option/' . $optionXPath, array('Option' => $rootValue));

                $optionLastId = end($optionPath);
                
                $parsedValue = array();
                if ($extractedValue) {
                    foreach ($extractedValue as $val) {
                        if (is_array($val)) {
                            if (isset($val[$optionLastId])) $val = $val[$optionLastId];
                            $parsedValue = array_merge($parsedValue, $val);
                            
                        } else {
                            // We are working with ids, so extract will match only one id, and if not array - we need the string returned
                            $parsedValue = $val;
                            break;
                        }
                    }
                    
                    if ($options['booleanize']) {
                        $parsedValue = Set::booleanize($parsedValue);
                    }
            		if (is_array($parsedValue)) {
            		    $parsedValue = Set::applyNative(Set::filter($parsedValue), 'stripslashes');
            		} elseif ($parsedValue !== false) {
            		    $parsedValue = stripslashes($parsedValue);
            		}
            		
                    return $parsedValue;
                }

            }
            
        }
        
        // if (strpos($optionId, '[') !== false) {
        //  if (preg_match_all("'(.*)(\[(.*)\])+'msU", $optionId, $out)) {
        //      $baseOptionId = $out[1][0];
        //      $baseValue = get_option($baseOptionId);
        //      if (is_array($baseValue)) {
        //          $innerVal = Set::extract('/Option/' . implode('/', $out[3]), array('Option' => $baseValue));
        //          if ($innerVal) return reset($innerVal);
        //      }
        //  }
        // }
		
		$value = get_option($optionId);

		if ($value === false) {
			$configOption = $this->getConfigOption($optionId);
			if (isset($configOption['default'])) {
				$value = $configOption['default'];
				if (isset($optionPath) && is_array($optionPath)) {
				    $optionLastId = end($optionPath);
    				if (isset($optionLastId) && $value && is_array($value) && isset($value[$optionLastId])) {
    				    $value = $value[$optionLastId];
    				}				    
				}
			} elseif (isset($configOption['options'])) {
				$value = array();
				foreach ($configOption['options'] as $configSubOption) {
					if (preg_match_all("'(.*)(\[(.*)\])+'msU", $configSubOption['id'], $out)) {
						$valueKey = end($out[3]);
						$value[$valueKey] = $this->find($configSubOption['id']);
					}
				}
			}
		}
		
		if (is_array($value)) {
		    $value = Set::applyNative(Set::filter($value), 'stripslashes');
		} elseif ($value !== false) {
		    $value = stripslashes($value);
		}
		
		if ($optionId === GUMM_THEME_PREFIX . '_email' && !$value) {
		    $value = get_option('admin_email');
		}
		
        if ($options['booleanize']) {
            $value = Set::booleanize($value);
        }
		
		return $value;
	}
	
	public function getConfigOption($optionId, $options=array()) {
	    if (strpos($optionId, GUMM_THEME_PREFIX . '_styles') === 0) {
	        $optionParts = explode('.', $optionId);
	        unset($optionParts[1]);
            $optionId = implode('.', $optionParts);
	    }
		$configOption = '';
		$options = ($options) ? $options : Configure::read('admin.options');
		
		if ($options) {
			foreach ($options['options'] as $option) {
                // debug($optionBlock);
                // foreach ($optionBlock as $option) {
                    if (!isset($option['id'])) {
                        continue;
                    } elseif ($optionId !== $option['id'] && strpos($option['id'], $optionId) === 0 && strpos($option['id'], '.') !== false) {
                        if (!is_array($configOption)) $configOption = array('default' => array());
                        
                        $childIds = explode('.', $option['id']);
                        array_shift($childIds);
                        $childIds = implode('.', $childIds);
                        $configOption['default'] = Set::insert($configOption['default'], $childIds, $option['default']);
                        
                    } elseif ($optionId == $option['id']) {
						$configOption = $option;
						break;
					} elseif (isset($option['options'])) {
						$newOptions = array('options' => array($option['options']));
						$configOption = $this->getConfigOption($optionId, $newOptions);

						if ($configOption) break;
					} elseif (isset($option['tabs'])) {
					    $newOptions = array('options' => array());
					    foreach ($option['tabs'] as $optionsForTab) {
                            foreach ($optionsForTab as $optionForTab) {
                                if (is_array($optionForTab)) {
                                    $newOptions['options'][] = $optionForTab;
                                }
                            }
					    }
					    $configOption = $this->getConfigOption($optionId, $newOptions);
					    
						if ($configOption) break;					    
					}
			}
		}
		
		return $configOption;
	}
	
	/**
	 * @param string $optionId
	 * @return string
	 */
	public function getFullOptionId($optionId) {
		if (defined('GUMM_THEME_PREFIX')) {
			if (strpos($optionId, GUMM_THEME_PREFIX) !== 0)  {
				$optionId = GUMM_THEME_PREFIX . '_' . $optionId;
			}
		}
		
		return $optionId;
	}

}
?>