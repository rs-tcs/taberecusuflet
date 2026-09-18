<?php
class IosSliderLayoutElement extends GummLayoutElement {
    /**
     * @var string
     */
    protected $id = '46F54601-5B1E-446C-ABA9-E8DA51CCE409';
    
    /**
     * @var string
     */
    public $group = 'sliders';
    
    /**
     * @var int
     */
    protected $layoutPosition = 'all';
    
    /**
     * @var RevSlider
     */
    private $slides;
    
    /**
     * @var array
     */
    protected $supports = array('title', 'aspectRatio' => 3.43);
    
    public function title() {
        return __('Nova Slider', 'gummfw');
    }
    
    /**
     * @return array
     */
    protected function _fields() {
        return array(
            'layout' => array(
                'name' => __('Slider Layout', 'gummfw'),
                'type' => 'tabbed-input',
                'inputOptions' => array(
                    'rightdetails' => __('Right details', 'gummfw'),
                    'leftdetails' => __('Left details', 'gummfw'),
                    'bottomnav' => __('Bottom titles', 'gummfw'),
                    'righttabs' => __('Right titles', 'gummfw'),
                ),
                'tabs' => array(
                    'rightdetails' => array(
                        'showRightInfoBox' => array(
                            'name' => __('Display info box', 'gummfw'),
                            'type' => 'checkbox',
                            'value' => 'true',
                        ),
                        'showRightControlNav' => array(
                            'name' => __('Display control nav', 'gummfw'),
                            'type' => 'checkbox',
                            'value' => 'true',
                        ),
                    ),
                    'leftdetails' => array(
                        'detailsDisplay' => array(
                            'name' => __('Details display', 'gummfw'),
                            'type' => 'select',
                            'inputOptions' => array(
                                'both' => __('Heading and info box', 'gummfw'),
                                'topheading' => __('Heading only on top', 'gummfw'),
                                'bottomheading' => __('Heading only on bottom', 'gummfw'),
                                'info' => __('Info box only', 'gummfw'),
                            ),
                        ),
                        'showLeftDirectionNav' => array(
                            'name' => __('Display direction nav', 'gummfw'),
                            'type' => 'checkbox',
                            'value' => 'true',
                        ),
                    ),
                    'bottomnav' => array(
                        'tabText' => __('This slider layout has no additional settings', 'gummfw'),
                    ),
                    'righttabs' => array(
                        'showRightTabsControlNav' => array(
                            'name' => __('Display control nav', 'gummfw'),
                            'type' => 'checkbox',
                            'value' => 'false',
                        ),
                    ),
                ),
                'value' => 'rightdetails',
            ),
            'slides' => array(
                'name' => __('Slides', 'gummfw'),
                'type' => 'content-tabs',
                'inputSettings' => array(
                    'contentTypes' => array('text'),
                    'fields' => array('title', 'textarea' => 'plain'),
                    'buttonLabel' => __('Add New Slide', 'gummfw'),
                    'deleteButtonLabel' => __('Delete Current Slide', 'gummfw'),
                    'tabLabel' => __('Slide', 'gummfw'),
                    'additionalInputs' => array(
                        'media' => array(
                            'name' => '',
                            'type' => 'media',
                            'inputSettings' => array(
                                'buttons' => 'media'
                            ),
                        ),
                        'button' => array(
                            'name' => __('Button for this slide', 'gummfw'),
                            'type' => 'button-input',
                        ),
                    ),
                ),
            ),
            'autoplay' => array(
                'name' => __('Slider Autoplay', 'gummfw'),
                'type' => 'number',
                'value' => 0,
                'inputSettings' => array(
                    'slider' => array(
                        'min' => 0,
                        'max' => 50,
                        'step' => .5,
                        'numberType' => 's'
                    ),
                ),
            ),
        );
    }
    
    public function beforeRender($options) {
        if (!$this->slides = $this->getParam('slides')) return false;
    }
    
    protected function _render($options) {
        $mediaIds = array();
        foreach ($this->slides as $slideId => $slide) {
            if (isset($slide['media']) && $slide['media']) {
                $mediaIds[] = $slide['media'][0];
                $this->slides[$slideId]['media'] = $slide['media'][0];
            } else {
                $this->slides[$slideId]['media'] = false;
            }
        }
        $images = GummRegistry::get('Model', 'Post')->findAttachmentPosts($mediaIds);
        foreach ($this->slides as $slideId => $slide) {
            foreach ($images as $image) {
                if ($image->ID == $slide['media']) {
                    $this->slides[$slideId]['media'] = $image;
                    break;
                }
            }
        }
        foreach ($this->slides as $slideId => $slide) {
            if (!$slide['media']) {
                unset($this->slides[$slideId]);
            }
        }
        
        $rowSpan = 12 * $this->widthRatio();
        
        $divWrapperAtts = array(
            'class' => 'iosSliderContainer bluebox-slider-wrap',
            'style' => 'padding-bottom:' . (1/$this->getParam('aspectRatio'))*100 . '%;',
        );
        
        switch ($this->getParam('layout')) {
         case 'bottomnav':
            $divWrapperAtts['class'] .= ' nova-slider-bottom-nav';
            break;
         case 'righttabs':
            $divWrapperAtts['class'] .= ' nova-right-tabs-slider';
            break;
         case 'leftdetails':
            $divWrapperAtts['class'] .= ' nova-slider-four';
            break;
        }
        
        $divSliderAtts = array(
            'class' => 'iosSlider loading',
        );
        if ($sliderAutoPlay = $this->getParam('autoplay')) {
            if ($sliderAutoPlay > 0) {
                $divSliderAtts['data-auto-slide'] = 'true';
                $divSliderAtts['data-auto-slide-timer'] = $sliderAutoPlay * 1000;
            }
            
        }
?>
        <!-- BEGIN slider area -->
        <div<?php echo $this->Html->_constructTagAttributes($divWrapperAtts); ?>>
            <div<?php echo $this->Html->_constructTagAttributes($divSliderAtts); ?>>
                <div class="slider">
                <?php
                foreach ($this->slides as $slide) {
                    if ($slide['media']) {
                        echo $this->Media->display($slide['media']->guid, array(
                            'ar' => $this->getParam('aspectRatio'),
                            'context' => 'wrap'
                        ), array(
                            'alt' => $slide['media']->post_title,
                            'class' => 'swipe-item'
                        ));
                    }
                }
                ?>
                </div>
                <?php
                switch ($this->getParam('layout')) {
                 case 'rightdetails':
                    $this->_renderRightDetails();
                    break;
                 case 'leftdetails':
                    $this->_renderLeftDetails();
                    break;
                 case 'bottomnav':
                    $this->_renderBottomNav();
                    break;
                 case 'righttabs':
                    $this->_renderRightTabs();
                    break;
                }
                ?>
            </div>
            
        
            <div class="bluebox-slider-top-detail"></div>
            <div class="bluebox-slider-bottom-detail"></div>
        </div>
        <!-- END slider area -->
<?php
        
    }
    
    private function _renderRightDetails() {
?>
        <?php if ($this->getParam('showRightInfoBox') === 'true'): ?>
        <div style="position:absolute; width:100%; height:2%; top:0;">
            <div class="bluebox-slider-content bluebox-container" style="height:100%; position:relative;">
                <div class="slide-details" style="top:560%;bottom:-4340%;">
                    <div class="details-wrap iosSlider-details">
                        <?php
                        $counter = 0;
                        foreach ($this->slides as $slide) {
                            $divDetailAtts = array(
                                'class' => array('detail-item'),
                                'style' => 'width:100%;height:100%;'
                            );
                            if ($counter === 0) $divDetailAtts['class'][] = 'active';
        
                            echo '<div' . $this->Html->_constructTagAttributes($divDetailAtts) . '>';
                            echo '<div class="details-content">';

                            if ($slide['title']) {
                                echo '<h2>' . $slide['title'] . '</h2>';
                            }
                            if ($slide['text']) {
                                echo '<p>' . $slide['text'] . '</p>';
                            }
                            echo '</div>';
                
                            if ($slide['button']['title'] && $slide['button']['href']) {
                                $buttonAtts = array(
                                    'href' => $slide['button']['href'],
                                    'class' => 'bluebox-button large extra',
                                );
                                if ($slide['button']['newWindow'] === 'true') {
                                    $buttonAtts['target'] = '_blank';
                                }
                    
                                echo '<a' . $this->Html->_constructTagAttributes($buttonAtts) . '><span class="icon-chevron-right"></span>' . $slide['button']['title'] . '</a>';
                    
                            }
                
                            echo '</div>';
                            $counter++;
                        }
                        ?>
                        <div class="prev-next-links">
                            <a class="icon-chevron-left prev" style="cursor: pointer;"></a>
                            <a class="icon-chevron-right next" style="cursor: pointer;"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if ($this->getParam('showRightControlNav') === 'true'): ?>
            <?php $this->_renderControlNav(); ?>
        <?php endif; ?>
<?php
    }
    
    private function _renderLeftDetails() {
        $headingDivWrapAtts = array(
            'style' => 'position:absolute;width:100%;height0;left:0;',
            
        );
        if (in_array($this->getParam('detailsDisplay'), array('both', 'topheading'))) {
            $headingDivWrapAtts['style'] .= 'top:0;';
        } elseif ($this->getParam('detailsDisplay') === 'bottomheading') {
            $headingDivWrapAtts['style'] .= 'bottom:0;';
        }
?>
        <?php if ($this->getParam('detailsDisplay') !== 'info'): ?>
        <div<?php echo $this->Html->_constructTagAttributes($headingDivWrapAtts); ?>>
            <div class="bluebox-slider-content bluebox-container" style="position:relative;">
                <div class="heading-container iosSlider-details">
                    <?php
                    $counter = 0;
                    foreach ($this->slides as $slide) {
                        $divDetailAtts = array(
                            'class' => array('detail-item'),
                        );
                        if ($counter === 0) $divDetailAtts['class'][] = 'active';
                        echo '<div' . $this->Html->_constructTagAttributes($divDetailAtts) . '>';
                            echo '<h2>' . $slide['title'] . '</h2>';
                            echo '<a' . $this->Html->_constructTagAttributes(array(
                    	       'href' => $slide['button']['href'],
                    	       'class' => 'slide-link icon-plus',
                    	       'target' => $slide['button']['newWindow'] === 'true' ? '_blank' : null
                    	    )) . '></a>';
                        echo '</div>';
                        
                        $counter++;
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (in_array($this->getParam('detailsDisplay'), array('both', 'info'))): ?>
        <div style="position:absolute;width:100%;height0;bottom:0;">
            <div class="bluebox-slider-content bluebox-container" style="position:relative;">
                <div class="slide-details extra-info">
                    <div class="details-wrap iosSlider-details">
                        <?php
                        $counter = 0;
                        foreach ($this->slides as $slide) {
                            $divDetailAtts = array(
                                'class' => array('detail-item'),
                                'style' => 'width:100%;height:100%;'
                            );
                            if ($counter === 0) $divDetailAtts['class'][] = 'active';
        
                            echo '<div' . $this->Html->_constructTagAttributes($divDetailAtts) . '>';
                                // echo '<div class="details-content">';
                                    echo $slide['text'];
                                // echo '</div>';
                            echo '</div>';
                            $counter++;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if ($this->getParam('showLeftDirectionNav') === 'true'): ?>
        <div style="position:absolute;width:100%;height0;bottom:0;">
            <div class="bluebox-slider-content bluebox-container" style="position:relative;">
                <div class="prev-next-links">
                    <a class="icon-chevron-left prev" style="cursor: pointer;"></a>
                    <a class="icon-chevron-right next" style="cursor: pointer;"></a>
                </div>
            </div>
        </div>
        <?php endif; ?>
<?php
    }
    
    private function _renderBottomNav() {
?>
        <div style="position:absolute;width:100%;height0;bottom:0;">
            <div class="bluebox-slider-content bluebox-container" style="position:relative;">
                <?php
                $ulAtts = array(
                    'class' => 'slide-pagination iosSlider-pagination',
                );
                ?>
                <ul<?php echo $this->Html->_constructTagAttributes($ulAtts); ?>>
                    <?php
                    $slidesCount = count($this->slides);
                    $counter = 0;
                    foreach ($this->slides as $slide) {
                        $liAtts = array();
                        if ($counter === 0) {
                            $liAtts = array('class' => 'current');
                        }
                        $liAtts['style'] = 'width:' . 100/$slidesCount . '%;';
                        $innerLiHtml = '<a><span class="icon-arrow-right"></span>' . $slide['title'] . '</a>';
                        echo '<li' . $this->Html->_constructTagAttributes($liAtts) . '>' . $innerLiHtml . '</li>';
                
                        $counter++;
                    }
                    ?>
                </ul>
            </div>
        </div>
<?php
    }
    
    private function _renderRightTabs() {
?>
        <div style="position:absolute; width:100%; height:2%; top:0;">
            <div class="bluebox-slider-content bluebox-container" style="height:100%; position:relative;">
                <div class="slide-details" style="top:560%;bottom:-4340%;">
                    <div class="details-wrap iosSlider-details">
                        <ul class="iosSlider-pagination">
                            <?php
                            $slidesCount = count($this->slides);
                            $counter = 0;
                            foreach ($this->slides as $slide) {
                                $liAtts = array();
                                if ($counter === 0) {
                                    $liAtts = array('class' => 'current');
                                }
                                echo '<li' . $this->Html->_constructTagAttributes($liAtts) . '>';
                                echo '<a><span class="icon-arrow-right"></span>' . $slide['title'] . '</a>';
                                echo '</li>';

                                $counter++;
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
<?php
        if ($this->getParam('showRightTabsControlNav') === 'true') {
            $this->_renderControlNav();
        }
    }
    
    private function _renderControlNav() {
?>
        <div style="position:absolute;width:100%;height0;bottom:0;">
            <div class="bluebox-slider-content bluebox-container" style="position:relative;">
                <?php
                $ulAtts = array(
                    'class' => 'slide-pagination iosSlider-pagination',
                );
                ?>
                <ul<?php echo $this->Html->_constructTagAttributes($ulAtts); ?>>
                    <?php
                    $slidesCount = count($this->slides);
                    $counter = 0;
                    foreach ($this->slides as $slide) {
                        $liAtts = array();
                        if ($counter === 0) {
                            $liAtts = array('class' => 'current');
                        }
        
                        $innerLiHtml = '<a></a>';
                        if ($this->getParam('layout') === 'bottomnav') {
                            $liAtts['style'] = 'width:' . 100/$slidesCount . '%;';
                            $innerLiHtml = '<a><span class="icon-arrow-right"></span>' . $slide['title'] . '</a>';
                        }
                        echo '<li' . $this->Html->_constructTagAttributes($liAtts) . '>' . $innerLiHtml . '</li>';
        
                        $counter++;
                    }
                    ?>
                </ul>
            </div>
        </div>
<?php
    }
}
?>