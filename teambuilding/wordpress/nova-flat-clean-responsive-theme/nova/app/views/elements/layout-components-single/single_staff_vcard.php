<?php
class SingleStaffVcardLayoutElement extends GummLayoutElement {
    protected $id = '4AF0AD98-EA75-470D-A3C1-C73751A67C34';
    
    /**
     * @var string
     */
    public $group = 'single';
    
    protected $supports = array();
    
    protected $gridColumns = 12;
    
    public $noMargin = true;
    
    // public $editable = false;
    
    public function title() {
        return __('Staff Member vCard', 'gummfw');
    }
    
    protected function _fields() {
        return array();
    }
    
    protected function _render($options) {
        $options = array_merge(array(
            'moreLink' => false,
            'socialNetworksDisplay' => false,
        ), $options);
        global $post;
        
?>
        <div class="top-staff-wrap">
            <?php if ($post->Thumbnail): ?>
            <div class="single-staff-left-wrap">
                <div class="image-wrap">
                    <div class="image-details">
                            <?php
                            echo $this->Media->display($post->Thumbnail->guid, array('context' => 'span4', 'ar' => 1), array('alt' => get_the_title()));
                            if ($options['socialNetworksDisplay']) {
                                $divMaskAtts = array(
                                    'class' => array(
                                        'image-details-link',
                                        'image-wrap-mask',
                                    ),
                                );
                                echo '<div' . $this->Html->_constructTagAttributes($divMaskAtts) . '>';
                                
                                $socialNetworks = Set::filter($post->PostMeta['social_networks_url']);
                                if ($socialNetworks) {
                                    echo '<ul class="social-links">';
                                    foreach ($socialNetworks as $k => $v) {
                                        $networkName = str_replace('_url', '', $k);
                                	    echo '<li><a href="' . $v . '" class="icon-' . $networkName . '" target="_blank"></a></li>';
                                    }
                                    echo '</ul>';
                                }
                                
                                echo '</div>';
                            }
                            ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="single-staff-right-wrap">
                <div class="page-heading-wrap">
                    <div class="bluebox-container">
                        <h2>
                        <?php
                        the_title();
                        if ($categories = $this->Wp->getPostCategories($post)) {
                            echo '<span>' . implode(', ', $categories) . '</span>';
                        }
                        ?>
                        </h2>
                    </div>
                    <?php
                    if ($options['moreLink']) {
                        echo '<a href="' . get_permalink() . '" class="staff-more-link icon-plus"></a>';
                    }
                    ?>
                </div>
                <div class="single-staff-info"><?php the_content(); ?></div>
            </div>
        </div>
<?php
    }
}
?>