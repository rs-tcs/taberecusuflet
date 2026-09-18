<?php
class SinglePostMetaLayoutElement extends GummLayoutElement {
    protected $id = '736F8B32-11D1-486E-B4EA-55388596E0F2';
    
    /**
     * @var string
     */
    public $group = 'single';
    
    protected $supports = array();
    
    protected $gridColumns = 12;
    
    public function title() {
        return __('Post Meta', 'gummfw');
    }
    
    protected function _fields() {
        return array(
            'metaFields' => array(
                'type' => 'checkboxes',
                'name' => __('Meta fields', 'gummfw'),
                'inputOptions' => array(
                    'author' => __('Author', 'gummfw'),
                    'date' => __('Date', 'gummfw'),
                    'categories' => __('Categories', 'gummfw'),
                    'comments' => __('Comments', 'gummfw'),
                ),
                'value' => array(
                    'author' => 'true',
                    'date' => 'true',
                    'categories' => 'true',
                    'comments' => 'true',
                ),
            ),
        );
    }
    
    protected function _render($options) {
        $metaFields = array_keys(Set::filter(Set::booleanize($this->getParam('metaFields'))));
        
        echo '<div class="line-meta-details">';
        
        echo $this->Html->postDetails($metaFields, array(
            'prefixes' => array(
                'author' => __('By', 'gummfw'),
                'date' => __('/', 'gummfw'),
                'comments' => __('/', 'gummfw'),
                'category' => __('/', 'gummfw'),
            ),
            'formats' => array(
                'date' => 'd F Y',
            ),
        ));
        echo '</div>';
    }
}
?>