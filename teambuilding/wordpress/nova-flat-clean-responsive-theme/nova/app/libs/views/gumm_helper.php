<?php
class GummHelper extends GummObject {
    
    public $helpers = array();
    
    public $name;
    
    public function __construct() {
        parent::__construct();
        
        if (!$this->name) $this->name = str_replace('Helper', '', get_class($this));
        if (isset($this->data['_mergeonsave']) && (int) $this->data['_mergeonsave'] === 0) $this->mergeOnSave = false;
        
        foreach ($this->helpers as $helper) {
            $this->$helper = GummRegistry::get('Helper', $helper);
        }
        
        GummRegistry::updateRegistry('Helper_' . $this->name, $this);
    }
    
    /**
     * @param array $attributes
     * @return string
     */
    public function _constructTagAttributes(array $attributes=array()) {
        $attributes = Set::filter($attributes);
        
        $outputHtml = '';
        
        if ($attributes):
            foreach ($attributes as $attName => $attValue) {
                if (is_array($attValue)) $attValue = implode(' ', $attValue);
                $attName = trim($attName);
                $attValue = trim($attValue);
                switch ($attName) {
                 case 'value':
                    $attValue = htmlentities($attValue, ENT_QUOTES, 'utf-8');
                    break;
                 case 'disabled':
                    if ($attValue) $attValue = 'disabled';
                    break;
                }
                
                if ((!$attValue || !is_string($attName)) && $attValue !== 0 && $attValue !== '0') continue;
            
                $outputHtml .= ' ' . $attName . '="' . esc_attr($attValue) . '"';
            }
        endif;
        
        return $outputHtml;
    }
    
    public function isUserAgentOldIE() {
        return preg_match('/(?i)msie [1-8]/',env('HTTP_USER_AGENT'));
    }
}
?>