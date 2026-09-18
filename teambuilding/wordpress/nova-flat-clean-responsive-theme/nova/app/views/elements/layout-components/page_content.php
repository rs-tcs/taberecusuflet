<?php
class PageContentLayoutElement extends GummLayoutElement {
    /**
     * @var string
     */
    protected $id = '9CFAAE0C-0B98-4613-9488-8A2A3916E9E4';
    
    /**
     * @var string
     */
    public $group = 'custom';
    
    /**
     * @var bool
     */
    protected $editable = false;
    
    /**
     * @var array
     */
    protected $supports = array();
    
    public function title() {
        return __('Page Content', 'gummfw');
    }
    
    protected function _fields() {
        return array();
    }
    
    protected function _render($options) {
        the_content();
    }
}
?>