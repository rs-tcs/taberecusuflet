<?php
class QuoteLayoutElement extends GummLayoutElement {
    /**
     * @var string
     */
    protected $id = '9B045E37-A945-40E5-977D-0737C1089334';
    
    /**
     * @var string
     */
    public $group = 'posts';
    
    /**
     * @var int
     */
    // protected $gridColumns = 2;
    
    /**
     * @var array
     */
    protected $supports = array('title', 'postsNumber' => 6);
    
    /**
     * @var string
     */
    protected $queryPostType = 'testimonial';
    
    /**
     * @var string
     */
    protected $htmlClass = 'slide-element';
    
    /**
     * @return string
     */
    public function title() {
        return __('Quotes', 'gummfw');
    }
    
    /**
     * @return array
     */
    protected function _fields() {
        return array(
            'layout' => array(
                'name' => __('Element Layout Style', 'gummfw'),
                'type' => 'select',
                'inputOptions' => array(
                    'default' => __('Default', 'gummfw'),
                    'fancy' => __('Fancy', 'gummfw'),
                ),
                'value' => 'default',
            ),
            'layoutStyle' => array(
                'name' => __('Element Color Style', 'gummfw'),
                'type' => 'select',
                'inputOptions' => array(
                    'light' => __('Light', 'gummfw'),
                    'colourful' => __('Colourful', 'gummfw'),
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
            'animationEffect' => array(
                'name' => __('Slide effect', 'gummfw'),
                'type' => 'select',
                'inputOptions' => array(
                    'fade' => __('Fade', 'gummfw'),
                    'slide' => __('Slide', 'gummfw'),
                ),
                'value' => 'Fade',
            ),
        );
    }
    
    public function beforeRender($options) {
        $this->posts = $this->queryPosts();
        
        if (count($this->posts) > 1 && $this->getParam('layout') === 'fancy') {
            $this->shouldPaginate = true;
        }
    }
    
    protected function _render($options) {
        $divAtts = array(
            'class' => array(
                'bluebox-quotes'
            ),
        );
        if ($this->getParam('layout') === 'fancy') {
            $divAtts['class'][] = 'quote-fancy';
        }
        if ($this->getParam('layoutStyle') === 'colourful') {
            $divAtts['class'][] = 'quote-colorful';
        }
        if ($this->getParam('fullWidth') === 'true') {
            $divAtts['class'][] = 'full-width';
        }
        
        $divSliderAtts = array(
            'id' => uniqid(),
            'class' => array('flex-slider loading'),
            'data-animation' => $this->getParam('animationEffect'),
            'data-control-nav' => '0',
            'data-direction-nav' => '0',
            'data-animation-loop' => '1',
            'data-slideshow' => '0',
            'data-smooth-height' => '1',
            'data-direction-nav-container' => '#' . $this->id() . '-nav-controls',
        );
?>
        <div<?php echo $this->Html->_constructTagAttributes($divAtts); ?>>
            <div class="quote-content">
                <div<?php echo $this->Html->_constructTagAttributes($divSliderAtts); ?>>
                    <div class="slides">
                        <?php while (have_posts()): the_post(); global $post; ?>
                            <div class="slide-item">
                            	<em><?php echo get_the_excerpt(); ?></em>
                        	    <?php
                        	    $authorText = '';
                        	    if (isset($post->PostMeta['author']) && $post->PostMeta['author']) {
                        	        if ($this->getParam('layout') === 'fancy') {
                        	            $authorText .= '<strong>' . $post->PostMeta['author'] . '</strong>';
                        	        } else {
                                        $authorText .= '~ ' . $post->PostMeta['author'];
                        	        }
                        	    }
                                if (isset($post->PostMeta['organisation']) && $post->PostMeta['organisation']) {
                        	        if ($this->getParam('layout') !== 'fancy') {
                                        $authorText .= ' /';
                                    }
                                    $authorText .= ' ' . $post->PostMeta['organisation'];
                                }
                        
                                if ($authorText) {
                                    echo '<p>' . $authorText . '</p>';
                                } elseif ($this->getParam('layout') === 'fancy') {
                                    echo '<p class="no-author"></p>';
                                }
                        	    ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <?php if (count($this->posts) > 1 && $this->getParam('layout') !== 'fancy'): ?>
                <ul id="<?php echo $this->id() . '-nav-controls'; ?>" class="quotes-arrows">
                    <li class="prev"><a href="#" class="icon-chevron-left prev bluebox-nav"></a></li>
                    <li class="next"><a href="#" class="icon-chevron-right next bluebox-nav"></a></li>
                </ul>
                <?php endif; ?>
            </div>
            <div class="element-background"></div>
        </div>
        

<?php
    }
}
?>