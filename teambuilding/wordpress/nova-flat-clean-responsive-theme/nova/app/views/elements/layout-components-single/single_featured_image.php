<?php
class SingleFeaturedImageLayoutElement extends GummLayoutElement {
    protected $id = '23BE9E8F-B722-4F9B-B557-DE2F1BF8DE63';
    
    /**
     * @var string
     */
    public $group = 'single';
    
    /**
     * @var array
     */
    protected $supports = array();
    
    protected $gridColumns = 12;
    
    public function title() {
        return __('Featured Image', 'gummfw');
    }
    
    protected function _fields() {
        return array(
            'mediaCrop' => array(
                'type' => 'tabbed-input',
                'name' => __('Crop Settings', 'gummfw'),
                'inputOptions' => array(
                    'full' => __('Use Original', 'gummfw'),
                    'crop' => __('Crop', 'gummfw'),
                ),
                'value' => 'crop',
                'tabs' => array(
                    array(
                        'tabText' => __('No additional settings for this option', 'gummfw'),
                    ),
                    array(
                        'aspectRatio' => array(
                            'name' => __('Media Aspect Ratio', 'gummfw'),
                            'type' => 'number',
                            'value' => 1.61,
                            'inputSettings' => array(
                                'slider' => array(
                                    'min' => 0.01,
                                    'max' => 5,
                                    'step' => 0.01,
                                    'numberType' => ''
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'sliderType' => array(
                'name' => __('Slider Type', 'gummfw'),
                'type' => 'slider-inputs',
                'value' => 'ios',
            ),
        );
    }
    
    public function beforeRender($options) {
        global $post;
        
        $shouldRender = true;
        if (!$post->Thumbnail || !$post->PostMeta['featured_image_single_display']) {
            $shouldRender = false;
        }
        
        return $shouldRender;
    }
    
    protected function _render($options) {
        global $post;
        
        $mediaDimensions = null;
        if ($this->getParam('mediaCrop') === 'crop') {
            $rowSpan = 12 * $this->widthRatio();
            $mediaDimensions = array(
                'ar' => $this->getParam('aspectRatio'),
                'context' => 'span' . $rowSpan
            );            
        }
        
        if ($post->Thumbnail): 
            if (count($post->Media) > 1):
                $sliderType = $this->getParam('sliderType');
                $settings = array_merge($this->getDefaultSettingsForSlider($sliderType), array(
                    'elementId' => $this->id(),
                    'media' => $post->Media,
                    'dimensions' => $mediaDimensions,
                    'linkToParent' => false,
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
            elseif ($post->PostMeta['featured_image_single_display']):
?>
            <div class="image-wrap">
                <span class="image-details" style="display:inline;">
                    <?php echo $this->Media->display($post->Thumbnail->guid, null, array('alt' => get_the_title())); ?>
                </span>
            </div>
<?php
            endif;
        endif;
    }
    
    protected function getDefaultSettingsForSlider($sliderType) {
        $settings = (array) $this->getParam('sliderType-settings.' . $sliderType);
        $settings = Set::booleanize($settings);

        return $settings;
    }
}
?>