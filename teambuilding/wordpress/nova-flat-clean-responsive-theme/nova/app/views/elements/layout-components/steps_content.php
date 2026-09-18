<?php
class StepsContentLayoutElement extends GummLayoutElement {
    /**
     * @var string
     */
    protected $id = '005D4703-6ED9-46AC-AE0C-91F1411D9C1D';
    
    /**
     * @var string
     */
    public $group = 'custom';
    
    /**
     * @var string
     */
    protected $name = 'steps_content';
    
    /**
     * @var int
     */
    protected $gridColumns = 1;
    
    /**
     * @var array
     */
    protected $supports = array();
    
    /**
     * @var string
     */
    protected $htmlClass = 'poker-rules sliding-content-element';
    
    /**
     * @var bool
     */
    protected $fullWidthEditor = true;
    
    /**
     * @return string
     */
    public function title() {
        return __('Steps Content', 'gummfw');
    }
    
    /**
     * @return array
     */
    protected function _fields() {
        return array(
            'tabs' => array(
                'name' => '',
                'type' => 'content-tabs',
                'inputSettings' => array(
                    'contentTypes' => 'text',
                    'additionalInputs' => array(
                        'icon' => array(
                            'name' => __('Bullet Icon', 'gummfw'),
                            'type' => 'icon',
                        ),
                    ),
                ),
            ),
        );
    }
    
    /**
     * @return void
     */
    protected function _render($options) {
        if (!$tabs = $this->getParam('tabs')) return;
?>
        <div class="left-side-steps sliding-content-steps">
            <?php $counter = 1;?>
            <?php foreach ($tabs as $tab): ?>
                <?php
                $icon = $counter;
                if ($tab['icon']) $icon = '<i class="' . $tab['icon'] . '"></i>';
                ?>
                <div class="rule-step <?php if($counter == 1) echo 'current'; ?>">
                	<a href="#">
                        <div class="date-details">
                        <span class="day"><?php echo $icon; ?></span>
                        </div>
                    </a>
                </div>
            <?php $counter++; ?>
            <?php endforeach; ?>
        </div>
        <div class="rule-step-content">
            <div class="blog-post-pointer-detail st"></div>
            <div class="sliding-content-holder">
            <?php $counter = 1; ?>
            <?php foreach ($tabs as $tab): ?>
                <?php
                $entryClass = array('sliding-content-entry');
                if ($counter > 1) $entryClass[] = 'hidden';
                ?>
                <div class="<?php echo implode(' ', $entryClass); ?>">
                    <?php echo wpautop(do_shortcode($tab['text'])); ?>
                </div>
            <?php $counter++; ?>
            <?php endforeach; ?>
            </div>
        </div>
        <div class="rule-step-wrap"></div>
<?php      
    }

}
?>