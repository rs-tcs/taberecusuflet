<?php
class SingleAuthorLayoutElement extends GummLayoutElement {
    protected $id = '5DFC4205-FDF2-4FBD-A15A-3Fsd36B9777F3D';
    
    /**
     * @var string
     */
    public $group = 'single';
    
    protected $supports = array();
    
    protected $gridColumns = 12;
    
    public $editable = false;
    
    public function title() {
        return __('About The Author', 'gummfw');
    }
    
    protected function _fields() {
        return array();
    }
    
    public function beforeRender($options) {
        $this->supports[] = 'title';
        $this->setParam('headingText', __('About the author', 'gummfw'));

        $this->htmlClass .= ' bluebox-about-author-wrap';
    }
    
    protected function _render($options) {
?>
        <div class="bluebox-about-author">
            <div class="author-image">
                <div class="image-wrap">
                    <?php
                    $linkAtts = array(
                        'href' => get_the_author_meta('user_url'),
                        'class' => 'image-details',
                    );
                    ?>
                    <a<?php echo $this->Html->_constructTagAttributes($linkAtts); ?>>
                        <?php echo get_avatar(get_the_author_meta('email'), '101'); ?>
                    </a>
                </div>
            </div>
            <span class="bluebox-about-author-description">
                <?php echo get_the_author_meta("description"); ?>
            </span>
        </div>
<?php
    }
}
?>