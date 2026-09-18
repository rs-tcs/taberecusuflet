<?php
class TextBoxLayoutElement extends GummLayoutElement {
    protected $id = '9DBA9EC1-4189-478E-B7F6-DD724C45DBD2';
    
    /**
     * @var string
     */
    public $group = 'custom';
        
    protected $gridColumns = 12;
    
    private $_defaultLinkAtts = array();
    
    private $layoutStyle;
    
    public function title() {
        return __('Text Box', 'gummfw');
    }
    
    protected function _fields() {
        $iconFields = array(
            'iconLetter' => array(
                'name' => '',
                'type' => 'icon',
            ),
            'iconLayout' => array(
                'name' => __('Icon position', 'gummfw'),
                'type' => 'radio',
                'inputOptions' => array(
                    'withHeading' => __('With heading', 'gummfw'),
                    'withText' => __('With text', 'gummfw'),
                ),
                'value' => 'withHeading',
            ),
        );
        $mediaUploadFields = array(
            'media' => array(
                'name' => '',
                'type' => 'media',
                'inputSettings' => array(
                    'buttons' => 'media'
                ),
            ),
            'aspectRatio' => array(
                'name' => __('Media Aspect Ratio', 'gummfw'),
                'type' => 'number',
                'value' => 1.77,
                'inputSettings' => array(
                    'slider' => array(
                        'min' => 0.01,
                        'max' => 5,
                        'step' => 0.01,
                        'numberType' => ''
                    ),
                ),
            ),
        );
        $gaugeFields = array(
            'gaugeSymbol' => array(
                'type' => 'tabbed-input',
                'name' => '',
                'inputOptions' => array(
                    'icon' => __('Use Icon', 'gummfw'),
                    'symbol' => __('Use Percent', 'gummfw'),
                ),
                'value' => 'icon',
                'tabs' => array(
                    array(
                        'gaugeIcon' => array(
                            'type' => 'icon',
                            'name' => __('Gauge Icon', 'gummfw'),
                        ),
                    ),
                    array(
                        'tabText' => __('No additional settings for this option', 'gummfw'),
                    ),
                ),
            ),
            'gaugeIconSize' => array(
                'type' => 'number',
                'name' => __('Icon Size', 'gummfw'),
                'inputSettings' => array(
                    'slider' => array(
                        'min' => 2,
                        'max' => 72,
                        'step' => 1,
                        'numberType' => 'px',
                    ),
                ),
                'value' => 24
            ),
            'gaugeLayout' => array(
                'name' => __('Element Layout', 'gummfw'),
                'type' => 'radio',
                'inputOptions' => array(
                    'topCenteredText' => __('Gauge on top, center aligned text', 'gummfw'),
                    'topLeftText' => __('Gauge on top, left aligned text', 'gummfw'),
                    'withHeading' => __('Gauge with heading', 'gummfw'),
                    'withText' => __('Gauge with text', 'gummfw'),
                ),
                'value' => 'topCenteredText',
            ),
            'gaugeSize' => array(
                'type' => 'number',
                'name' => __('Gauge Size', 'gummfw'),
                'inputSettings' => array(
                    'slider' => array(
                        'min' => 12,
                        'max' => 400,
                        'step' => 2,
                        'numberType' => 'px',
                    ),
                ),
                'value' => 120
            ),
            'gaugeStroke' => array(
                'type' => 'number',
                'name' => __('Gauge Stroke Width', 'gummfw'),
                'inputSettings' => array(
                    'slider' => array(
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                        'numberType' => 'px',
                    ),
                ),
                'value' => 4,
            ),
            'gaugePercent' => array(
                'type' => 'number',
                'name' => __('Gauge Percent', 'gummfw'),
                'inputSettings' => array(
                    'slider' => array(
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                        'numberType' => '%',
                    ),
                ),
                'value' => 25
            ),
            'gaugeAnimationSpeed' => array(
                'type' => 'number',
                'name' => __('Animation Speed', 'gummfw'),
                'inputSettings' => array(
                    'slider' => array(
                        'min' => 0,
                        'max' => 5000,
                        'step' => 10,
                        'numberType' => 'ms',
                    ),
                ),
                'value' => 800
            ),
        );
        
        $fields = array(
            'content' => array(
                'name' => __('Block Content', 'gummfw'),
                'type' => 'text-editor',
                'value' => '',
                'inputAttributes' => array('cols' => 2),
            ),
            'mediaSource' => array(
                'name' => __('Element Layout', 'gummfw'),
                'type' => 'tabbed-input',
                'inputOptions' => array(
                    'plain' => __('Just Text', 'gummfw'),
                    'icon' => __('Icon', 'gummfw'),
                    'media' => __('Image', 'gummfw'),
                    'gauge' => __('Gauge', 'gummfw'),
                ),
                'value' => 'icon',
                'tabs' => array(
                    array(
                        'tabText' => __('No additional settings for this option', 'gummfw'),
                    ),
                    $iconFields,
                    $mediaUploadFields,
                    $gaugeFields,
                ),
            ),
            'linkButton' => array(
                'name' => __('Block Button Title', 'gummfw'),
                'type' => 'text',
                'value' => '',
            ),
            'link' => array(
                'name' => __('Block Link Url', 'gummfw'),
                'type' => 'text',
                'value' => '#',
            ),
            'linkTargetBlank' => array(
                'name' => __('Open link in new window', 'gummfw'),
                'type' => 'radio',
                'inputOptions' => array(
                    'true' => __('Enable', 'gummfw'),
                    'false' => __('Disable', 'gummfw'),
                ),
                'value' => 'false',
            ),
        );
        
        return $fields;
    }
    
    public function beforeRender($options) {
        $this->layoutStyle = 'three';
        switch ($this->getParam('mediaSource')) {
         case 'icon':
            $this->layoutStyle = 'one';
            if ($this->getParam('iconLayout') === 'withText') {
                $this->layoutStyle = 'two';
            }
            break;
         case 'media':
            $this->layoutStyle = 'four';
            break;
         case 'gauge':
            $this->htmlClass .= 'text-box-gauge';
            $this->layoutStyle = 'five';
            if ($this->getParam('gaugeLayout') === 'topLeftText') {
                $this->layoutStyle = 'six';
            } elseif ($this->getParam('gaugeLayout') === 'withHeading') {
                $this->layoutStyle = 'seven';
            } elseif ($this->getParam('gaugeLayout') === 'withText') {
                $this->layoutStyle = 'eight';
            }
            break;
        }
        
        $styleClass = $this->layoutStyle;
        if ($styleClass === 'seven') {
            $styleClass = 'one';
        } elseif ($styleClass === 'eight') {
            $styleClass = 'two';
        }
        $this->htmlClass .= ' bluebox-textboxes text-box-style-' . $styleClass;
    }
    
    /**
     * Overrides default title rendering
     */
    protected function renderTitle() {
        return '';
    }
    
    protected function _render($options) {
        // $layoutStyle = $this->getParam('layout');
        
        switch ($this->layoutStyle) {
         case 'one':
            $this->_renderLayoutOne($options);
            break;
         case 'two':
            $this->_renderLayoutTwo($options);
            break;
         case 'three':
            $this->_renderLayoutThree($options);
            break;
         case 'four':
            $this->_renderLayoutFour($options);
            break;
         case 'five':
            $this->_renderLayoutGaugeTop($options);
            break;
         case 'six':
            $this->_renderLayoutGaugeTop($options);
            break;
         case 'seven':
            $this->_renderLayoutGaugeWithHeading($options);
            break;
         case 'eight':
            $this->_renderLayoutGaugeWithText($options);
            break;
        }
    }
    
    // ========== //
    // LAYOUT ONE //
    // ========== //
    
    protected function _renderLayoutOne($options) {
        $iconLinkAtts = array_merge($this->_defaultLinkAtts(), array(
            'class' => 'bluebox-icon-container'
        ));
?>
        <a<?php echo $this->Html->_constructTagAttributes($iconLinkAtts); ?>>
            <span class="<?php echo $this->getParam('iconLetter'); ?>"></span>
        </a>
        <h4 class="head-link">
            <a<?php echo $this->Html->_constructTagAttributes($this->_defaultLinkAtts()); ?>><?php echo $this->getParam('headingText'); ?></a>
        </h4>
        <div class="bluebox-clear"></div>
        
        <?php
        echo wpautop(do_shortcode($this->getParam('content')));
        echo $this->_renderReadMoreLink();
        ?>
<?php
    }
    
    // ========== //
    // LAYOUT TWO //
    // ========== //
    
    protected function _renderLayoutTwo($options) {
        $iconLinkAtts = array_merge($this->_defaultLinkAtts(), array(
            'class' => 'bluebox-icon-container'
        ));
?>
        <h4 class="head-link">
            <a<?php echo $this->Html->_constructTagAttributes($this->_defaultLinkAtts()); ?>><?php echo $this->getParam('headingText'); ?><span class="icon-chevron-right"></span></a>
        </h4>
        <div class="bluebox-clear"></div>
        <a<?php echo $this->Html->_constructTagAttributes($iconLinkAtts); ?>>
            <span class="<?php echo $this->getParam('iconLetter'); ?>"></span>
        </a>
        
        <?php
        echo wpautop(do_shortcode($this->getParam('content')));
        echo $this->_renderReadMoreLink();
        ?>
<?php        
    }
    
    // ============ //
    // LAYOUT THREE //
    // ============ //
    
    protected function _renderLayoutThree($options) {
?>
        <div class="text-box-container">
            <h4 class="head-link">
                <a<?php echo $this->Html->_constructTagAttributes($this->_defaultLinkAtts()); ?>><?php echo $this->getParam('headingText'); ?></a>
            </h4>
            <?php
            echo wpautop(do_shortcode($this->getParam('content')));
            echo $this->_renderReadMoreLink('bluebox-button extra');
            ?>
        </div>
<?php
    }
    
    // =========== //
    // LAYOUT FOUR //
    // =========== //
    
    protected function _renderLayoutFour($options) {
        $media = null;
        if ($mediaItems = $this->getMediaItems()) {
            $media = reset($mediaItems);
        }
        
        $iconLinkAtts = array_merge($this->_defaultLinkAtts(), array(
            'class' => 'image-details'
        ));
        
?>
        <?php if ($media): ?>
        <a<?php echo $this->Html->_constructTagAttributes($iconLinkAtts); ?>>
            <?php
            echo $this->Media->display($media->guid, array(
                // 'ar' => $this->getParam('aspectRatio'),
                // 'context' => 'span' . $this->getRowSpan(),
            ), array(
                'alt' => $media->post_title,
            ));
            ?>
    	</a>
    	<?php endif; ?>
        <h4 class="head-link">
            <a <?php echo $this->Html->_constructTagAttributes($this->_defaultLinkAtts()); ?>><?php echo $this->getParam('headingText'); ?></a>
        </h4>
        <div class="bluebox-clear"></div>
        <?php
        echo wpautop(do_shortcode($this->getParam('content')));
        echo $this->_renderReadMoreLink();
        
        ?>
<?php
    }
    
    // ========================== //
    // GAUGE LAYOUT CENTERED TEXT //
    // ========================== //
    
    private function _renderLayoutGaugeTop($options) {
        $iconLinkAtts = array_merge($this->_defaultLinkAtts(), array(
            'class' => 'bb-gauge-icon-container'
        ));
?>
        <a<?php echo $this->Html->_constructTagAttributes($iconLinkAtts); ?>>
        <?php
            $icon = $this->getParam('gaugeIcon');
            echo GummRegistry::get('Helper', 'Layout')->gaugeChart(array(
                'size' => $this->getParam('gaugeSize'),
                'percent' => $this->getParam('gaugePercent'),
                // 'color' => '#ed7721',
                'backgroundColor' => '#fafafa',
                'strokeWidth' => $this->getParam('gaugeStroke'),
                'icon' => $icon,
                'iconStyle' => $this->getGaugeIconStyle(),
                'fontSize' => $this->getParam('gaugeIconSize'),
                'animationSpeed' => (int) $this->getParam('gaugeAnimationSpeed'),
            ));
        ?>
        </a>
        <h4 class="head-link">
            <a<?php echo $this->Html->_constructTagAttributes($this->_defaultLinkAtts()); ?>><?php echo $this->getParam('headingText'); ?></a>
        </h4>
        <div class="bluebox-clear"></div>

        <?php
        echo wpautop(do_shortcode($this->getParam('content')));
        echo $this->_renderReadMoreLink();
        ?>
<?php
    }
    
    // ========================= //
    // GAUGE LAYOUT WITH HEADING //
    // ========================= //
    
    private function _renderLayoutGaugeWithHeading($options) {
        $iconLinkAtts = array_merge($this->_defaultLinkAtts(), array(
            'class' => 'bb-gauge-icon-container'
        ));
?>
        <a<?php echo $this->Html->_constructTagAttributes($iconLinkAtts); ?>>
        <?php
            $iconStyle = $this->getParam('gaugeSymbol');
            $icon = $this->getParam('gaugeIcon');
            echo GummRegistry::get('Helper', 'Layout')->gaugeChart(array(
                'size' => $this->getParam('gaugeSize'),
                'percent' => $this->getParam('gaugePercent'),
                // 'color' => '#ed7721',
                'backgroundColor' => '#fafafa',
                'strokeWidth' => $this->getParam('gaugeStroke'),
                'icon' => $icon,
                'iconStyle' => $this->getGaugeIconStyle(),
                'fontSize' => $this->getParam('gaugeIconSize'),
                'animationSpeed' => (int) $this->getParam('gaugeAnimationSpeed'),
            ));
        ?>
        </a>
        <h4 class="head-link">
            <a<?php echo $this->Html->_constructTagAttributes($this->_defaultLinkAtts()); ?>><?php echo $this->getParam('headingText'); ?></a>
        </h4>
        <div class="bluebox-clear"></div>

        <?php
        echo wpautop(do_shortcode($this->getParam('content')));
        echo $this->_renderReadMoreLink();
        ?>
<?php
    }
    
    // ======================= //
    // GAUGE LAYOUT WITH TEXT //
    // ====================== //
    
    private function _renderLayoutGaugeWithText($options) {
        $iconLinkAtts = array_merge($this->_defaultLinkAtts(), array(
            'class' => 'bb-gauge-icon-container'
        ));
?>
        <h4 class="head-link">
            <a<?php echo $this->Html->_constructTagAttributes($this->_defaultLinkAtts()); ?>><?php echo $this->getParam('headingText'); ?><span class="icon-chevron-right"></span></a>
        </h4>
        <div class="bluebox-clear"></div>
        <a<?php echo $this->Html->_constructTagAttributes($iconLinkAtts); ?>>
        <?php
            $icon = $this->getParam('gaugeIcon');
            echo GummRegistry::get('Helper', 'Layout')->gaugeChart(array(
                'size' => $this->getParam('gaugeSize'),
                'percent' => $this->getParam('gaugePercent'),
                // 'color' => '#ed7721',
                'backgroundColor' => '#fafafa',
                'strokeWidth' => $this->getParam('gaugeStroke'),
                'icon' => $icon,
                'iconStyle' => $this->getGaugeIconStyle(),
                'fontSize' => $this->getParam('gaugeIconSize'),
            ));
        ?>
        </a>
        <?php
        echo wpautop(do_shortcode($this->getParam('content')));
        echo $this->_renderReadMoreLink();
        ?>
<?php
    }
    
    private function _renderReadMoreLink($linkClass = 'bluebox-more-link') {
        if ($moreLinkText = $this->getParam('linkButton')) {
            $moreLinkAtts = array_merge($this->_defaultLinkAtts(), array(
                'class' => $linkClass,
            ));

            echo '<a' . $this->Html->_constructTagAttributes($moreLinkAtts) . '>' . $moreLinkText . '<span class="icon-chevron-right"></span></a>';
        }
    }
    
    private function _defaultLinkAtts() {
        if (!$this->_defaultLinkAtts) {
            $this->_defaultLinkAtts = array(
                'href' => $this->getParam('link'),
                'target' => $this->getParam('linkTargetBlank') == 'true' ? '_blank' : null,
            );
        }
        
        return $this->_defaultLinkAtts;
    }
    
    private function getGaugeIconStyle() {
        $iconStyle = $this->getParam('gaugeSymbol');
        if ($iconStyle === 'icon' && !$this->getParam('gaugeIcon')) {
            $iconStyle = 'plain';
        }
        return $iconStyle;
    }
}
?>