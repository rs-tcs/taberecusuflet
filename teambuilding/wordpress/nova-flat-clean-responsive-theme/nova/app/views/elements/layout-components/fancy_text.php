<?php
class FancyTextLayoutElement extends GummLayoutElement {
    /**
     * @var string
     */
    protected $id = '14E1CE18-77F7-4917-A6CA-AC2BB2E913E1';
    
    /**
     * @var string
     */
    public $group = 'custom';
    
    /**
     * @var int
     */
    protected $gridColumns = 12;
    
    /**
     * @var array
     */
    protected $supports = array();
    
    /**
     * @var string
     */
    protected $layoutPosition = 'all';
    
    /**
     * @return string
     */
    public function title() {
        return __('Fancy Text', 'gummfw');
    }
    
    /**
     * @return array
     */
    protected function _fields() {
        $customLayoutHtml = '';

        $customLayoutHtml .= '<div class="block-background-images-editor">';
        $customLayoutHtml .= $this->Html->link(__('Add New Image', 'gummfw'),
            array(
                'admin' => true,
                'ajax' => true,
                'controller' => 'layouts',
                'action' => 'admin_edit_layer_background_image',
                'optionId' => $this->getFieldInputId('customBackgroundImages'),
                'modelName' => 'GummPostMeta',
            ), array(
                'style' => 'margin:20px 0 40px 0;',
                'class' => 'add-new-layer-background btn btn-large btn-primary btn-success',
            )
        );
        $customLayoutHtml .= $this->Layout->blockBackgroundImagesEditor($this->getFieldInputId('customBackgroundImages'), 'GummPostMeta');
        $customLayoutHtml .= '</div>';
        $customLayoutHtml .= '<div class="row-fluid">';
        $customLayoutHtml .= $this->Layout->blockBackgroundColorsEditor($this->getFieldInputId('customBackgroundColor'), 'GummPostMeta');
        $customLayoutHtml .= '</div>';
        $customLayoutHtml .= $this->constructFieldInput('customTextStyle', array(
            'name' => __('Text Color Style', 'gummfw'),
            'type' => 'select',
            'inputOptions' => array(
                'dark' => __('Dark', 'gummfw'),
                'light' => __('Light', 'gummfw'),
            ),
            'value' => 'light',
        ));
        
        return array(
            'boxHeading' => array(
                'name' => __('Heading Text', 'gummfw'),
                'type' => 'text',
            ),
            'boxSubheading' => array(
                'name' => __('Subheading Text', 'gummfw'),
                'type' => 'text',
            ),
            'boxLink' => array(
                'name' => __('Button Text', 'gummfw'),
                'type' => 'text',
            ),
            'boxLinkHref' => array(
                'name' => __('Button Link', 'gummfw'),
                'type' => 'text',
            ),
            'buttonTarget' => array(
                'name' => __('Open link in new window', 'gummfw'),
                'type' => 'radio',
                'inputOptions' => array(
                    'new' => __('Enable', 'gummfw'),
                    'same' => __('Disable', 'gummfw'),
                ),
                'value' => 'same',
            ),
            'layoutStyle' => array(
                'name' => __('Element Style', 'gummfw'),
                'type' => 'tabbed-input',
                'inputOptions' => array(
                    'light' => __('Light', 'gummfw'),
                    'colourful' => __('Colourful', 'gummfw'),
                    'custom' => __('Custom', 'gummfw'),
                ),
                'tabs' => array(
                    array(
                        'tabText' => __('No additional settings for this option', 'gummfw')
                    ),
                    array(
                        'tabText' => __('No additional settings for this option', 'gummfw')
                    ),
                    $customLayoutHtml,
                ),
                'value' => 'light',
            ),
            'fullWidth' => array(
                'name' => __('Element Full Width', 'gummfw'),
                'type' => 'radio',
                'inputOptions' => array(
                    'true' => __('Enable', 'gummfw'),
                    'false' => __('Disable', 'gummfw'),
                ),
                'value' => 'false',
            ),
            'alignment' => array(
                'name' => __('Text Alignment', 'gummfw'),
                'type' => 'radio',
                'inputOptions' => array(
                    'left' => __('Left', 'gummfw'),
                    'center' => __('Center', 'gummfw'),
                ),
                'value' => 'left',
            ),
        );
    }
    
    protected function _render($options) {
        $subHeading     = $this->getParam('boxSubheading');
        $boxLink        = $this->getParam('boxLink');
        $boxLinkHref    = ($this->getParam('boxLinkHref')) ? $this->getParam('boxLinkHref') : '#';
        $boxLinkTarget  = $this->getParam('buttonTarget')  === 'new' ? '_blank' : null;
        
        $divAtts = array(
            'class' => array('bluebox-fancy-text'),
            'style' => null,
        );
        // $divClass = 'bluebox-fancy-text';

        if ($this->getParam('fullWidth') === 'true' && $this->widthRatio() == 1) {
            $divAtts['class'][] = 'full-width';
            // $divClass .= ' full-width';
        }
        if ($this->getParam('alignment') === 'center') {
            $divAtts['class'][] = 'bluebox-center';
            // $divClass .= ' bluebox-center';
        }
        if ($this->getParam('layoutStyle') === 'colourful') {
            // $divClass .= ' fancy-colorful';
            $divAtts['class'][] = 'fancy-colorful';
        } elseif ($this->getParam('layoutStyle') === 'custom') {
            if ($this->getParam('customTextStyle') === 'light' || $this->getParam('customTextStyle') === null) {
                $divAtts['class'][] = 'fancy-colorful';
            }
            $styleProps = '';
            if ($bgColor = $this->getParam('customBackgroundColor')) {
                $styleProps .= 'background-color:#' . $bgColor . ';';
            }
            if ($bgImages = $this->getParam('customBackgroundImages')) {
                $imgProps = array(
                    'background-image' => array(),
                    'background-repeat' => array(),
                    'background-position' => array(),
                );
                foreach ($bgImages as $bgImageProps) {
                    if ($bgImageProps['background-image']) {
                        $imgProps['background-image'][] = "url('" . $bgImageProps['background-image'] . "')";
                        $imgProps['background-repeat'][] = $bgImageProps['background-repeat'];
                        $imgProps['background-position'][] = $bgImageProps['background-position-left'] . ' ' . $bgImageProps['background-position-top'];
                    }
                }
                $imgProps = Set::filter($imgProps);
                foreach ($imgProps as $prop => $declaration) {
                    $styleProps .= $prop . ':' . implode(', ', $declaration) . ';';
                }
            }
            
            if ($styleProps) {
                $divAtts['style'] = $styleProps;
            }
        }
?>
        <div<?php echo $this->Html->_constructTagAttributes($divAtts); ?>>
            <div class="fancy-content">
                <h4 class="head-link"><?php echo $this->getParam('boxHeading'); ?></h4>
                <p><?php echo do_shortcode($subHeading); ?></p>
            </div>
            <?php
            if ($boxLink):
                $containerAtts = array(
                    'class' => 'button-container',
                    'style' => 'margin-top:8px;'
                );
                if ($this->Wp->getOption('enable_effects') === 'true') {
                    $containerAtts['class'] .= ' not-initialized gumm-scrollr-item';
                }
                $buttonAtts = array(
                    'href'   => $boxLinkHref,
                    'target' => $boxLinkTarget,
                    'class'  => 'bluebox-button extra',
                );
            ?>
            <div<?php echo $this->Html->_constructTagAttributes($containerAtts); ?>>
                <a<?php echo $this->Html->_constructTagAttributes($buttonAtts); ?>>
                    <?php echo $boxLink; ?><span class="icon-chevron-right"></span>
                </a>
            </div>
            <?php endif; ?>
            <?php
            $bgDivAtts = array(
                'class' => 'element-background',
                'style' => $divAtts['style'],
            );
            ?>
            <div<?php echo $this->Html->_constructTagAttributes($bgDivAtts); ?>></div>
        </div>
<?php
    }
}
?>