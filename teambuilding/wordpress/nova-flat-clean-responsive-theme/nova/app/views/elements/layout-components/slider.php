<?php
class SliderLayoutElement extends GummLayoutElement {
    /**
     * @var string
     */
    protected $id = 'E1D2E9F6-2D68-4848-9140-A068259FC710';
    
    /**
     * @var string
     */
    public $group = 'sliders';
    
    /**
     * @var int
     */
    // protected $gridColumns = 2;
    
    /**
     * @var array
     */
    protected $supports = array('title', 'media', 'aspectRatio' => 1.34560906516);
    
    /**
     * @var string
     */
    protected $htmlClass = 'roki-gallery slide-element';
    
    /**
     * @var bool
     */
    protected $fullWidthEditor = true;
    
    /**
     * @var array
     */
    private $media = array();
    
    /**
     * @return string
     */
    public function title() {
        return __('Slider', 'gummfw');
    }
    
    /**
     * @return array
     */
    protected function _fields() {
        return array(
            'sliderType' => array(
                'name' => __('Slider Type', 'gummfw'),
                'type' => 'slider-inputs',
                'value' => 'ios',
                // 'inputOptions' => array(
                //     'windy' => __('windy', 'gummfw'),
                //     'flex' => __('flex', 'gummfw'),
                //     'rev' => __('revolution', 'gummfw'),
                // ),
            ),
        );
    }
    
    public function beforeRender($options) {
        if (!$this->media = $this->getMediaItems()) {
            return false;
        }
    }
    
    protected function _render($options) {
        $rowSpan = 12 * $this->widthRatio();
        $mediaDimensions = array(
            'ar' => $this->getParam('aspectRatio'),
            'context' => 'span' . $rowSpan
        );
        $linkToParent = $this->getParam('mediaSource') === 'post';
        
        $sliderType = $this->getParam('sliderType');
        $settings = array_merge($this->getDefaultSettingsForSlider($sliderType), array(
            'elementId' => $this->id(),
            'media' => $this->media,
            'dimensions' => $mediaDimensions,
            'linkToParent' => $linkToParent,
        ));
        switch ($sliderType) {
         case 'flex':
            View::renderElement('layout-components-parts/sliders/flex-slider', $settings);
            break;
         case 'windy':
            View::renderElement('layout-components-parts/sliders/windy-slider', $settings);
            break;
         case 'ios':
            View::renderElement('layout-components-parts/sliders/ios-slider', $settings);
            break;
        }
    }
    
    
    protected function getDefaultSettingsForSlider($sliderType) {
        $settings = (array) $this->getParam('sliderType-settings.' . $sliderType);
        $settings = Set::booleanize($settings);

        return $settings;
    }
}
?>