<?php
class LayerSliderLayoutElement extends GummLayoutElement {
    /**
     * @var string
     */
    protected $id = 'B13EDD82-FDAD-4BAC-A913-C42A836E7B99';
    
    /**
     * @var string
     */
    public $group = 'sliders';
    
    /**
     * @var bool
     */
    protected $fullWidthEditor = false;
    
    /**
     * @var int
     */
    protected $layoutPosition = 'all';
    
    /**
     * @var array
     */
    protected $supports = array(
        'title',
        'plugin' => array(
            'name' => 'LayerSlider',
            'path' => 'LayerSlider/layerslider.php',
        ),
    );
    
    public function title() {
        return __('Layer Slider', 'gummfw');
    }
    
    /**
     * @return array
     */
    protected function _fields() {
        $inputAttributes = array();
        $sliders = $this->getSliders('list');
        if (!$sliders) {
            $sliders = array(__('No sliders have been created.', 'gummfw'));
            $inputAttributes['disabled'] = true;
        }
        
        return array(
            'activeLayerSlider' => array(
                'name' => __('Choose slider', 'gummfw'),
                'type' => 'select',
                'inputOptions' => $sliders,
                'inputAttributes' => $inputAttributes,
            ),
        );
    }
    
    /**
     * @return string
     */
    // protected function getElementStyle() {
    //     return 'display:none;';
    // }
    
    // protected function getSliderHeight() {
    //     return $this->getRevSlider()->getParam('height');
    // }
    
    /**
     * @return string
     */
    protected function _render($options) {
        if ($activeId = $this->getParam('activeLayerSlider')) {
            echo do_shortcode('[layerslider id="' . $activeId . '"]');
        }
    }
    
    public function getSliders($type='all') {
        // Get WPDB Object
        global $wpdb;

        // Table name
        $table_name = $wpdb->prefix . "layerslider";

        // Get sliders
        $sliders = $wpdb->get_results( "SELECT * FROM $table_name
                                            WHERE flag_hidden = '0' AND flag_deleted = '0'
                                            ORDER BY id ASC LIMIT 200" );
                                            
        $result = array();
        
        if ($type === 'list' && $sliders) {
            foreach ($sliders as $slider) {
                $result[$slider->id] = $slider->name;
            }
        } elseif ($sliders) {
            $result = $sliders;
        }
                                            
        return $result;
    }
}
?>