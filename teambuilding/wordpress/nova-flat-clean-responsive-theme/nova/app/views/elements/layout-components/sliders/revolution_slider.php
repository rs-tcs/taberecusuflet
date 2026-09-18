<?php
class RevolutionSliderLayoutElement extends GummLayoutElement {
    /**
     * @var string
     */
    protected $id = 'D843537A-7CC6-48BD-B319-9BFD3DA8DA47';
    
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
     * @var RevSlider
     */
    private $slider;
    
    /**
     * @var array
     */
    protected $supports = array('title');
    
    public function __construct($data=array()) {
        parent::__construct($data);
        
        if (!class_exists('RevSlider')) {
            $this->setErrors(array(
                __('Revolution Slider plugin is not installed. The plugin comes with this theme\'s downloaded files. Please install it to use the element', 'gummfw')
            ));
        }
    }
    
    public function title() {
        return __('Revolution Slider', 'gummfw');
    }
    
    /**
     * @return array
     */
    protected function _fields() {
        $RevSlider = new RevSlider();
        $sliders = $RevSlider->getArrSliders();
        $inputOptions = array();
        foreach ($sliders as $slider) {
            $inputOptions[$slider->getAlias()] = $slider->getTitle();
        }
    	
        return array(
            'activeRevSlider' => array(
                'name' => __('Choose slider', 'gummfw'),
                'type' => 'select',
                'inputOptions' => $inputOptions
            ),
        );
    }
    
    /**
     * @return string
     */
    protected function getElementStyle() {
        return 'display:none;';
    }
    
    protected function getSliderHeight() {
        return $this->getRevSlider()->getParam('height');
    }
    
    public function getRevSlider() {
        if (!$this->slider && $alias = $this->getParam('activeRevSlider')) {
			$this->slider = new RevSlider();
            $this->slider->initByMixed($alias);
        }
		
		return $this->slider;	
    }
    
    /**
     * @return string
     */
    protected function _render($options) {
        if ($activeAlias = $this->getParam('activeRevSlider')) {
            putRevSlider($activeAlias);
            echo '<i class="icon-spinner icon-spin rev-slider-spinner"></i>';
            ?>
            <script type="text/javascript" class="revslider-support-script">
            (function( $ ){
                var spinnerIcon = $('#<?php echo $this->htmlElementId; ?>').find('.rev-slider-spinner');
                spinnerIcon.css({
                    top: '50%',
                    left: '50%',
                    marginTop: -(spinnerIcon.height()/2),
                    marginLeft: -(spinnerIcon.width()/2)
                });
                var ele = $('#<?php echo $this->htmlElementId; ?>');
                var revSliderWrapper = ele.find('.rev_slider_wrapper');
                var revSlider = revSliderWrapper.children('.rev_slider');
                var height = parseInt(revSlider.css('maxHeight'));
                
                revSlider.css({opacity: 0});
                revSliderWrapper.css({height: height});
                ele.css({display: 'block'});
            
                revSlider.waitForImages(function(){
                    spinnerIcon.hide('fade', 600, function(){
                        $(this).remove();
                    });
                });
                revSlider.on('revolution.slide.onloaded', function(){
                    revSliderWrapper.find('.tp-rightarrow.default').addClass('icon-chevron-right');
                    revSliderWrapper.find('.tp-leftarrow.default').addClass('icon-chevron-left');
                    revSliderWrapper.css({height: ''});
                    revSlider.css({opacity: ''});
                    ele.addClass('revslider-gumm-initialised').find('script.revslider-support-script').remove();
                });

            }) (jQuery);
            </script>
            <?php
            
        }
    }
}
?>