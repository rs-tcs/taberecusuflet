<?php
class SingleShareboxLayoutElement extends GummLayoutElement {
    protected $id = '9BC625C5-8435-47F4-BD5A-458FB8A0E7C1';
    
    /**
     * @var string
     */
    public $group = 'single';
    
    protected $supports = array();

    
    protected $gridColumns = 12;
    
    public function title() {
        return __('Sharebox', 'gummfw');
    }
    
    protected function _fields() {
        return array(
            'socialNetworks' => array(
                'name' => __('Enabled Networks', 'gummfw'),
                'type' => 'checkboxes',
                'value' => array(
                    'facebook' => 'true',
                    'twitter' => 'true', 
                    'linkedin' => 'false', 
                    'printerest' => 'false', 
                    'googleplus' => 'true',
                ),
                'inputOptions' => array(
                    'facebook' => 'Facebook', 
                    'twitter' => 'Twitter', 
                    'linkedin' => 'LinkedIn', 
                    'printerest' => 'Printerest', 
                    'googleplus' => 'Google+', 
                ),
            ),
        );
    }
    
    protected function _render($options) {
        $networks = Set::filter(Set::booleanize($this->getParam('socialNetworks')));
        
        echo '<div class="bluebox-share-options">';
            echo '<span>' . __('Share The Story', 'gummfw') . '</span>';
            echo '<div class="bluebox-details-social">';
                View::renderElement('social-links', array(
                    'networks' => $networks,
                    'accountMode' => 'share',
                    'additionalClass' => 'bluebox-shadows',
                ));
            echo '</div>';
        echo '</div>';
    }
}
?>