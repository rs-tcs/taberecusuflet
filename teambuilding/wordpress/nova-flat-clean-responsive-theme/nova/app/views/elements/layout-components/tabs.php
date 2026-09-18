<?php
class TabsLayoutElement extends GummLayoutElement {
    /**
     * @var string
     */
    protected $id = '3520F38E-FAC4-49C7-8CFE-48CD1D5585C0';
    
    /**
     * @var string
     */
    public $group = 'custom';
    
    /**
     * @var array
     */
    protected $supports = array('title');
    
    /**
     * @var string
     */
    // protected $htmlClass = 'slide-element';
    
    private $tabs = array();
    
    /**
     * @return string
     */
    public function title() {
        return __('Tabs', 'gummfw');
    }
    
    /**
     * @return array
     */
    protected function _fields() {
        return array(
            'layout' => array(
                'name' => __('Layout', 'gummfw'),
                'type' => 'tabbed-input',
                'inputOptions' => array(
                    'horizontal' => __('Horizontal', 'gummfw'),
                    'vertical' => __('Vertical', 'gummfw'),
                ),
                'value' => 'horizontal',
                'tabs' => array(
                    array(
                        'tabText' => __('No additional settings available for this option', 'gummfw'),
                    ),
                    array(
                        'verticalLayoutStyle' => array(
                            'name' => __('Layout Style', 'gummfw'),
                            'type' => 'select',
                            'inputOptions' => array(
                                'small' => __('Small', 'gummfw'),
                                'medium' => __('Medium', 'gummfw'),
                                'large' => __('Large', 'gummfw'),
                            ),
                            'value' => 'medium'
                        ),
                    ),
                ),
            ),
            'tabs' => array(
                'name' => '',
                'type' => 'content-tabs',
                'inputSettings' => array(
                    'additionalInputs' => array(
                        'icon' => array(
                            'name' => __('Tab icon (vertical layout only)', 'gummfw'),
                            'type' => 'icon',
                            'default' => 'icon-plus'
                        ), 
                    ),
                ),
            ),
        );
    }
    
    public function beforeRender($options) {
        if (!$this->tabs = $this->getParam('tabs')) return false;
        // $this->shouldPaginate = true;
        // $this->posts = $this->queryPosts();
    }
    
    protected function _render($options) {
        $options = array_merge(array(
            'featured' => true,
        ), $options);
        
        switch ($this->getParam('layout')) {
         case 'horizontal':
            $this->_renderHorizontalLayout($options);
            break;
         case 'vertical':
            $this->_renderVerticalLayout($options);
            break;
        }
    }
    
    protected function _renderHorizontalLayout($options) {
?>
        <div class="bluebox-tabs">
            <ul class="nav nav-tabs">
            <?php $counter = 0; ?>
            <?php foreach ($this->tabs as $tabId => $tab): ?>
                <?php
                $tabElementId = 'tab-' . $tabId;
                $additionalClasses = 'tab';
                if ($counter === 0) $additionalClasses .= ' active';
                ?>
                <li class="<?php echo $additionalClasses; ?>">
                    <a href="#<?php echo $tabElementId; ?>" data-toggle="tab">
                        <span class="icon-chevron-down"></span><span class="icon-chevron-right"></span><?php echo $tab['title']; ?>
                    </a>
                </li>
                <?php $counter++; ?>
            <?php endforeach; ?>
            </ul>
            <div class="tab-content">
                <?php $counter = 0; ?>
                <?php foreach ($this->tabs as $tabId => $tab): ?>
                    <?php
                    $tabElementId = 'tab-' . $tabId;
                    $tabClass = array('tab-pane');
                    if ($counter == 0) $tabClass[] = 'active';
                    ?>
                    <div id="<?php echo $tabElementId; ?>" class="<?php echo implode(' ', $tabClass); ?>">
                        <?php echo $this->getTabContent($tab, $options); ?>
                    </div>
                    <?php $counter++; ?>
                <?php endforeach; ?>
            </div>
        </div>
<?php
    }
    
    protected function _renderVerticalLayout($options) {
?>
        <div class="bluebox-new-tabs-element <?php echo $this->getParam('verticalLayoutStyle'); ?>">
            <div class="nav-left">
                <ul>
                    <?php
                    $counter = 0;
                    foreach ($this->tabs as $tabId => $tab) {
                        $liAtts = array('class' => '');
                        $tabElementId = 'tab-' . $tabId;
                        if ($counter === 0) {
                            $liAtts['class'][] = 'active';
                        }
                        $tabIcon = 'icon-plus';
                        if (isset($tab['icon'])) {
                            $tabIcon = $tab['icon'];
                        }

                        echo '<li' . $this->Html->_constructTagAttributes($liAtts) . '>';
                            echo '<a href="#' . $tabElementId . '" data-toggle="tab">';
                                if ($this->getParam('verticalLayoutStyle') !== 'large') {
                                    echo $tab['title'];
                                }
                                echo '<span class="' . $tabIcon . '"></span>';
                            echo '</a>';
                        echo '</li>';
                    
                    
                        $counter++;
                    }
                    ?>
                </ul>
            </div>
            <div class="content-right tab-content">
                <?php $counter = 0; ?>
                <?php foreach ($this->tabs as $tabId => $tab): ?>
                    <?php
                    $tabElementId = 'tab-' . $tabId;
                    $tabClass = array('tab-pane', 'content');
                    if ($counter == 0) $tabClass[] = 'active';
                    ?>
                    <div id="<?php echo $tabElementId; ?>" class="<?php echo implode(' ', $tabClass); ?>">
                        <?php echo $this->getTabContent($tab, $options); ?>
                    </div>
                    <?php $counter++; ?>
                <?php endforeach; ?>
            </div>
        </div>
<?php
        
    }
    
    private function getTabContent($tab, $options) {
        $outputHtml = '';
        switch ($tab['source']) {
         case 'custom':
            $outputHtml = '<div class="span12">' . wpautop(do_shortcode($tab['text'])) . '</div>';
            break;
         case 'post':
            $outputHtml = $this->getTabPostHtml($tab, $options);
            break;
        }
        
        return $outputHtml;
    }
    
    private function getTabPostHtml($tab, $options) {
        $postType = $tab['post_type']['post_type'];
        $args = array(
            'post_type' => $postType,
            'posts_per_page' => $tab['post_type']['posts_number'],
        );
        
        if (isset($tab['post_type'][$postType . '-category'])) {
            $this->setParam($postType . '-category', $tab['post_type'][$postType . '-category']);
        }

        $this->queryPosts($args, $tab['post_type']);
        
        echo '<div class="blog-1-col">';
        while (have_posts()) {
            the_post();
            global $post;
            View::renderElement('layout-components-parts/post/single-vertical-item', array('elementId' => $this->id() . '_' . $post->post_type));
        }
        echo '</div>';
        
        wp_reset_query();
    }
}
?>