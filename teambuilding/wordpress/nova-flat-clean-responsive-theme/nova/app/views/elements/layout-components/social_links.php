<?php
class SocialLinksLayoutElement extends GummLayoutElement {
    protected $id = 'D7FE1FDE-0491-45CE-BDA3-73D95103013A';
    
    /**
     * @var string
     */
    public $group = 'social';
    
    protected $htmlClass = 'single-post-social-bar';
    // protected $gridColumns = 1;
    
    protected $supports = array();
    
    public function title() {
        return __('Social Links', 'gummfw');
    }
    
    protected function _fields() {
        return array(
            'networks' => array(
                'name' => __('Enabled Networks', 'gummfw'),
                'type' => 'select',
                'inputOptions' => Configure::read('Data.socialNetworksEnabled'),
                'inputAttributes' => array(
                    'multiple' => true
                ),
                'value' => $this->Wp->getOption('social.networks_enabled'),
            ),
        );
    }
    
    protected function _render($options) {
        View::renderElement('social-links', array('networks' => $this->getParam('networks')));
    }
}
?>