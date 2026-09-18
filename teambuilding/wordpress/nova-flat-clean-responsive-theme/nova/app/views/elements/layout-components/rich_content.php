<?php
class RichContentLayoutElement extends GummLayoutElement {
    protected $id = 'CEC0B17B-6818-4D1B-93F0-57D25D712AC8';
    
    /**
     * @var string
     */
    public $group = 'custom';
    
    protected $gridColumns = 12;
    
    public function title() {
        return __('Custom Content', 'gummfw');
    }
    
    protected function _fields() {
        return array(
            'content' => array(
                'name' => 'Block Content',
                'type' => 'text-editor',
                'value' => '',
                'inputAttributes' => array('cols' => 2),
            ),
        );
    }
    
    protected function _render($options) {
        $content = $this->getParam('content');
        $content = apply_filters('the_content', $content);
        
        echo $content;
    }
}
?>