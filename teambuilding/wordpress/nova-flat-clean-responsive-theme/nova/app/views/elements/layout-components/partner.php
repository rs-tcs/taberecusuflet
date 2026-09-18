<?php
class PartnerLayoutElement extends GummLayoutElement {
    /**
     * @var string
     */
    protected $id = 'E1A1A5F9-3743-431D-B4FC-E6DC620CE7B2';
    
    /**
     * @var string
     */
    public $group = 'posts';
    
    /**
     * @var int
     */
    protected $gridColumns = 1;
    
    /**
     * @var array
     */
     protected $supports = array(
         'categories',
         'title', 'postsNumber' => 5,
         'postColumns' => array(
             'min' => 4,
             'max' => 5,
             'value' => 5,
         ),
     );
    
    /**
     * @var string
     */
    protected $queryPostType = 'partner';

    protected $postType = 'partner';
    
    /**
     * @var int
     */
    private $visibleNum = 5;
    
    /**
     * @var string
     */
    // protected $htmlClass = 'slide-element';
    
    /**
     * @return string
     */
    public function title() {
        return __('Partners', 'gummfw');
    }
    
    /**
     * @return array
     */
    protected function _fields() {
	return array(
            'linkTargetBlank' => array(
                'name' => __('Open partner link in new window', 'gummfw'),
                'type' => 'radio',
                'inputOptions' => array(
                    'true' => __('Enable', 'gummfw'),
                    'false' => __('Disable', 'gummfw'),
                ),
                'value' => 'true'
            ),
        );
    }
    
    public function beforeRender($options) {
        $this->visibleNum = $this->getParam('postColumns');
        $this->posts = $this->queryPosts();
        if (count($this->posts) > $this->visibleNum) {
            $this->shouldPaginate = true;
            $this->htmlClass .= ' gumm-layout-element-slider';
            $this->htmlElementData = array(
                'data-directional-nav' => '.heading-pagination',
                'data-num-visible' => $this->visibleNum,
            );
        }
    }
    
    protected function _render($options) {
        $isSlider = count($this->posts) > $this->visibleNum;
        $divClass = array('bluebox-partners');
        $ulClass = array('partners-slide');
        if ($isSlider) {
            // $divClass[] = 'gumm-layout-element-slider';
            $ulClass[] = 'slides-container';
        }

        $attributes = array(
            'class' => implode(' ', $divClass),
        );
        if ($isSlider) {
            // $attributes['data-directional-nav'] = '.heading-pagination';
            // $attributes['data-num-visible'] = $this->visibleNum;
        }
?>
        <div<?php echo $this->Html->_constructTagAttributes($attributes);?>>
            <ul class="<?php echo implode(' ', $ulClass); ?>">
                <?php
                $counter = 1;
                while (have_posts()): the_post(); global $post;
                if ($post->Thumbnail) {
                    $liAtts = array(
                        'class' => '',
                        'style' => 'width:' . (100/$this->visibleNum) . '%;',
                    );
                    $linkAtts = array(
                        'href' => $post->PostMeta['url'],
                        'class' => 'tooltip-link',
                        'data-original-title' => get_the_title(),
                        'target' => $this->getParam('linkTargetBlank') === 'true' ? '_blank' : null,
                    );
                    if ($counter > $this->visibleNum) $liAtts['class'] .= 'hidden';
                    echo '<li' . $this->Html->_constructTagAttributes($liAtts) . '><a' . $this->Html->_constructTagAttributes($linkAtts) . '>' . $this->Media->display($post->Thumbnail->guid, null, array('alt' => get_the_title())) . '</a></li>';
                }
                $counter++;
                endwhile;
                ?>
            </ul>
        </div>
<?php
    }
}
?>