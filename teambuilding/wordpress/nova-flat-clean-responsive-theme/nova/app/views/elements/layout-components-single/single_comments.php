<?php
class SingleCommentsLayoutElement extends GummLayoutElement {
    protected $id = '4E357AA0-4B66-4E92-83D1-C3D8D4B693E0';
    
    /**
     * @var string
     */
    public $group = 'single';
    
    protected $supports = array();
    
    public $editable = false;
    
    protected $gridColumns = 12;
    
    public function title() {
        return __('Comments', 'gummfw');
    }
    
    protected function _fields() {
        return array();
    }
    
    protected function _render($options) {
        comments_template('', true); 
    }
}
?>