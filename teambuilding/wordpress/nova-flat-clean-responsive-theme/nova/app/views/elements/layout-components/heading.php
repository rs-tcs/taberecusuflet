<?php
class HeadingLayoutElement extends GummLayoutElement {
    protected $id = 'FFA63F0C-2D00-4B73-8347-FDB319D2FA4E';
    
    /**
     * @var string
     */
    public $group = 'custom';
    
    protected $gridColumns = 1;
    
    protected $supports = array();
    
    public function title() {
        return __('Heading', 'gummfw');
    }
    
    protected function _fields() {
        return array(
            'heading' => array(
                'name' => __('Heading', 'gummfw'),
                'type' => 'text',
            ),
        );
    }
    
    protected function _render($options) {
?>
    <div class="bluebox-heading-wrap">
        <h3 class="bluebox-heading"><?php echo $this->getParam('heading'); ?></h3>
    </div>
<?php
    }
}
?>