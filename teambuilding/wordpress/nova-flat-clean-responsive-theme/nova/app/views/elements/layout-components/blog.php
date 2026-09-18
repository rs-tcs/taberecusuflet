<?php
class BlogLayoutElement extends GummLayoutElement {
    /**
     * @var string
     */
    protected $id = '80227A46-2FBC-44DF-A526-1BCBEB1260D7';
    
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
    protected $supports = array(
        'title',
        'excerpt',
        'postsNumber',
        'postType' => array(
            'value' => 'post',
            'flickr' => false
        ),
        // 'layout' => 'slider',
        // 'postColumns' => array(
        //     'min' => 1
        // ),
        'paginationLinks'
    );
    
    public function __construct($data=array()) {
        parent::__construct($data);
        
        $this->layoutsAvailable['blog-medium-image'] = __('Medium Image Layout', 'gummfw');
    }
    
    /**
     * @return string
     */
    public function title() {
        return __('Blog Layout', 'gummfw');
    }
    
    /**
     * @return array
     */
    protected function _fields() {
        $horizontalLayoutFields = array_merge(array(
            'horizontalLayoutStyle' => array(
                'name' => __('Layout Style', 'gummfw'),
                'type' => 'select',
                'value' => 'slider',
                'inputOptions' => array(
                    'grid'   => __('Layout as grid', 'gummfw'),
                    'slider' => __('Layout as row slider', 'gummfw'),
                ),
            ),
            'horizontalElementStyle' => array(
                'name' => __('Elements Style', 'gummfw'),
                'type' => 'select',
                'value' => 'plain',
                'inputOptions' => array(
                    'plain' => __('Plain', 'gummfw'),
                    'fancy' => __('Fancy', 'gummfw'),
                ),
            ),
        ), $this->_postColumnsFields(array(
            'min' => 1,
            'max' => 4,
            'value' => 4
        )));
        
        $verticalLayoutFields = array(
            'verticalLayoutStyle' => array(
                'name' => __('Layout Style', 'gummfw'),
                'type' => 'select',
                'value' => 'default',
                'inputOptions' => array(
                    'default'   => __('Full width image', 'gummfw'),
                    'leftImage' => __('Thumbnail on the left', 'gummfw'),
                    'half'      => __('Half image on the left', 'gummfw'),
                ),
            ),
        );
        
        $layoutFields = array(
            'name' => __('Element Layout', 'gummfw'),
            'type' => 'tabbed-input',
            'inputOptions' => array(
                'horizontal' => __('Horizontal', 'gummfw'),
                'vertical' => __('Vertical', 'gummfw'),
            ),
            'value' => 'horizontal',
            'tabs' => array(
                $horizontalLayoutFields,
                $verticalLayoutFields,
            ),
        );
        
        return array(
            'layout' => $layoutFields,
            'lightBoxLinkDisplay' => array(
                'name' => __('Display LightBox Link', 'gummfw'),
                'type' => 'radio',
                'value' => 'true',
                'inputOptions' => array(
                    'true' => __('Enable', 'gummfw'),
                    'false' => __('Disable', 'gummfw'),
                ),
            ),
            // 'metaFieldsDisplay' => array(
            //     'name' => __('Display date, comment, or author info', 'gummfw'),
            //     'type' => 'checkbox',
            //     'value' => 'true',
            // ),
        );
    }
    
    public function beforeRender($options) {
        $this->posts = $this->queryPosts();
        if (
            $this->getColumns() > 1 &&
            count($this->posts) > $this->getParam('postColumns') &&
            $this->getParam('layout') === 'horizontal' &&
            $this->getParam('horizontalLayoutStyle') === 'slider'
        ) {
            $this->shouldPaginate = true;
            $this->htmlClass .= ' gumm-layout-element-slider';
            $this->htmlElementData = array(
                'data-directional-nav' => '.heading-pagination',
                'data-num-visible' => (int) $this->getParam('postColumns'),
            );
        }
    }
    
    protected function _render($options) {
        $rowClass = array('row-fluid');
        // if ($this->shouldPaginate) $rowClass[] = 'slides-container';
        
        if ($this->getColumns() == 1) $layout = 'vertical';
        else ($layout = $this->getParam('layout'));
        
?>
        <div class="<?php echo implode(' ', $rowClass); ?>">
<?php
        switch ($layout) {
         case 'vertical':
            $this->renderVerticalLayout($options);
            break;
         case 'horizontal':
            $this->renderHorizontalLayout($options);
            break;
        }
?>
        </div>
<?php
    }
    
    public function renderHorizontalLayout($options) {
        $columns = (int) $this->getParam('postColumns');
        $rowSpan = 12 / $columns;
        
        $foundPosts = count($this->posts);
        
        $rowAtts = array(
            'class' => array('row-fluid'),
        );
        if ($this->getParam('horizontalElementStyle') === 'plain') {
            $rowAtts['class'][] =  'blog-4-cols';
        } else {
            $rowAtts['class'][] =  'blog-fancy-cols';
        }
        if ($this->shouldPaginate) {
            $rowAtts['class'][] = 'slides-container';
        }
?>
        <div<?php echo $this->Html->_constructTagAttributes($rowAtts); ?>>
<?php
        $counter = 1;

        while (have_posts()): the_post();
            global $post;
            $itemAtts = array(
                'class' => array('span' . $rowSpan, implode(' ', get_post_class())),
            );
            
            if ($this->shouldPaginate && $counter > $columns) {
                $itemAtts['class'][] = 'hidden';
            }
            
?>
            <div<?php echo $this->Html->_constructTagAttributes($itemAtts); ?>>
                <?php
                $this->_renderStickyRibbon();
                switch ($this->getParam('horizontalElementStyle')) {
                 case 'plain':
                    $this->_renderSinglePlainStyle($options);
                    break;
                 case 'fancy':
                    $this->_renderSingleFancyStyle($options);
                    break;
                }
                ?>
            </div>
<?php

            if ($counter % $columns === 0 && $counter < $foundPosts && $this->getParam('horizontalLayoutStyle') === 'grid') {
                echo '</div><div' . $this->Html->_constructTagAttributes($rowAtts) . '>';
            }

            $counter++;
        endwhile;
?>
        </div>
<?php
    }
    
    public function _renderSinglePlainStyle($options) {
        global $post;
        $columns = (int) $this->getParam('postColumns');
        $rowSpan = 12 / $columns;
?>
        <?php if ($post->Thumbnail): ?>
            <div class="image-wrap">
                <div class="image-details">
                    <?php
                    echo $this->Media->display($post->Thumbnail->guid, array(
                        'ar' => 1.62068965517,
                        'context' => 'span' . $rowSpan,
                        'alt' => get_the_title(),
                    ));
                    ?>
                    <a<?php echo $this->_imageLinkAtts(); ?>>
                    <?php
                    if ($this->getParam('lightBoxLinkDisplay') === 'true') {
                        echo '<i class="icon-search"></i>';
                    }
                    ?>
                    </a>
                </div>
            </div>
        <?php endif; ?>
        <a href="<?php the_permalink(); ?>" class="head-link">
            <h4><?php the_title(); ?></h4>
        </a>
        <span class="bluebox-date">
            <?php
            echo trim($this->Html->postDetails(array(
                'date', 'comments',
            ), array(
                'beforeDetail' => '',
                'afterDetail' => ' /',
                'prefixes' => array(
                    'author' => '',
                    'date' => '',
                    'category' => '',
                    'comments' => '',
                ),
            )), ' /');
            ?>
        </span>
        <?php
        if ($this->getParam('excerptLength') > 0) {
            echo $this->Text->paragraphize($this->Text->truncate(get_the_excerpt(), $this->getParam('excerptLength')));
        }
        ?>
        <a href="<?php the_permalink(); ?>" class="bluebox-more-link">
            <?php _e('Read More', 'gummfw'); ?><span class="icon-chevron-right"></span>
        </a>
<?php
    }
    
    public function _renderSingleFancyStyle($options) {
        global $post;
        $columns = (int) $this->getParam('postColumns');
        $rowSpan = 12 / $columns;
?>
        <div class="bluebox-new-blog-element">
            <?php if ($post->Thumbnail): ?>
            <div class="image-wrap">
                <div class="image-details">
                    <?php
                    echo $this->Media->display($post->Thumbnail->guid, array(
                        'ar' => 1,
                        'context' => 'span' . $rowSpan,
                        'alt' => get_the_title(),
                    ));
                    ?>
                    <a<?php echo $this->_imageLinkAtts(); ?>>
                    <?php
                    if ($this->getParam('lightBoxLinkDisplay') === 'true') {
                        echo '<i class="icon-plus"></i>';
                    }
                    ?>
                    </a>
                </div>
            </div>
            <?php endif; ?>
            <div class="blog-new-heading-wrap">
                <div class="inner-wrap">
                    <a href="<?php the_permalink(); ?>" class="head-link"><h4><?php the_title(); ?></h4></a>
                    <div class="new-blog-date">
                        <?php
                        echo $this->Html->postDetails(array(
                            'date',
                        ), array(
                            'beforeDetail' => '',
                            'afterDetail' => '',
                            'prefixes' => array(
                                'date' => '',
                            ),
                            'formats' => array(
                                'date' => 'j M'
                            )
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <span class="blog-new-post-format-icon <?php echo $this->Wp->getPostFormatIcon(); ?>"></span>
        </div>
<?php
    }
    
    public function renderVerticalLayout($options) {
        $verticalLayoutStyle = $this->getParam('verticalLayoutStyle');
        if ($this->getColumns() == 1) $verticalLayoutStyle = 'singleVertical';
        
        switch ($verticalLayoutStyle) {
         case 'default':
            $this->renderVerticalDefaultLayout();
            break;
         case 'leftImage':
            $this->renderVerticalLeftImageLayout();
            break;
         case 'half':
            $this->renderVerticalHalfImageLayout();
            break;
         case 'singleVertical':
            $this->renderVerticalSingleLayout();
            break;
        }
    }
    
    public function renderVerticalDefaultLayout() {
?>
        <!-- BEGIN blog loop posts -->

        <div class="blog-loop-standard">

        <?php while (have_posts()): the_post(); ?>
            <?php global $post; ?>
    
            <div class="blog-line <?php echo implode(' ', get_post_class()); ?>">
                <?php $this->_renderStickyRibbon(); ?>
                <?php if ($post->Thumbnail): ?>
                    <div class="image-wrap">
                        <div class="image-details">
                            <?php
                            echo $this->Media->display($post->Thumbnail->guid, array(
                                'ar' => 2.57088122605,
                                'context' => 'span' . $this->getRowSpan(),
                            ));
                            ?>
                            <a<?php echo $this->_imageLinkAtts(); ?>>
                            <?php
                            if ($this->getParam('lightBoxLinkDisplay') === 'true') {
                                echo '<i class="icon-search"></i>';
                            }
                            ?>
                            </a>
                        </div>
                        <div class="line-details">
                            <div class="line-post-format <?php echo $this->Wp->getPostFormatIcon(); ?>"></div>
                            <div class="line-date">
                            <?php
                            echo $this->Html->postDetails('date', array(
                                'beforeDetail' => '',
                                'afterDetail' => '',
                                'prefixes' => '',
                                'formats' => array(
                                    'date' => '\<\s\t\r\o\n\g\>j\<\/\s\t\r\o\n\g\>\<\s\p\a\n\>M\<\/\s\p\a\n\>',
                                ),
                            ));
                            ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <h3 class="line-heading"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
                <div class="line-meta-details">
                    <?php
                    echo trim($this->Html->postDetails(array(
                        'author', 'date', 'categories', 'comments',
                    ), array(
                        'beforeDetail' => '',
                        'afterDetail' => ' /',
                        'prefixes' => array(
                            'author' => __('By', 'gummfw'),
                            'date' => '',
                            'category' => '',
                            'comments' => '',
                        ),
                    )), ' /');
                    ?>
                </div>
                <?php
                if ($this->getParam('excerptLength') === 'full') {
                    the_excerpt();
                } elseif ($this->getParam('excerptLength') > 0) {
                    echo $this->Text->paragraphize($this->Text->truncate(get_the_excerpt(), $this->getParam('excerptLength')));
                }
                ?>
                <a href="<?php the_permalink(); ?>" class="bluebox-more-link">
                    <?php _e('Read More', 'gummfw'); ?><span class="icon-chevron-right"></span>
                </a>
        
            </div>

        <?php endwhile; ?>

        </div>
<?php
    }
    
    public function renderVerticalLeftImageLayout() {
?>
        <div class="blog-3-cols">
            <?php while (have_posts()): the_post(); ?>
                <?php
                global $post;
                $contentSpan = 8;
                ?>
                <div class="row-fluid <?php echo implode(' ', get_post_class()); ?>">
                    <?php $this->_renderStickyRibbon(); ?>
                    <?php if ($post->Thumbnail): ?>
                        <div class="span4">
                            <div class="image-wrap">
                                <div class="image-details">
                                    <?php
                                    echo $this->Media->display($post->Thumbnail->guid, array(
                                        'ar' => 1.61832061069,
                                        'context' => 'span4',
                                    ));
                                    ?>
                                    <a<?php echo $this->_imageLinkAtts(); ?>>
                                    <?php
                                    if ($this->getParam('lightBoxLinkDisplay') === 'true') {
                                        echo '<i class="icon-search"></i>';
                                    }
                                    ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php $contentSpan = 12; ?>
                    <?php endif; ?>
                    <div class="span<?php echo $contentSpan; ?>">
                        <a href="<?php the_permalink(); ?>" class="head-link">
                            <h4><?php the_title(); ?></h4>
                        </a>
                        <span class="bluebox-date">
                            <?php
                            echo trim($this->Html->postDetails(array(
                                'date', 'comments',
                            ), array(
                                'beforeDetail' => '',
                                'afterDetail' => ' /',
                                'prefixes' => array(
                                    'author' => '',
                                    'date' => '',
                                    'category' => '',
                                    'comments' => '',
                                ),
                            )), ' /');
                            ?>
                        </span>
                        <?php
                        if ($this->getParam('excerptLength') > 0) {
                            echo $this->Text->paragraphize($this->Text->truncate(get_the_excerpt(), $this->getParam('excerptLength')));
                        }
                        ?>
                        <a href="<?php the_permalink(); ?>" class="bluebox-more-link">
                            <?php _e('Read More', 'gummfw'); ?><span class="icon-chevron-right"></span>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
<?php
    }
    
    public function renderVerticalHalfImageLayout() {
?>
        <div class="blog-loop-standard half-image">
            <?php while (have_posts()): the_post(); ?>
                <?php global $post; ?>
                <div class="blog-line <?php echo implode(' ', get_post_class()); ?>">
                    <?php $this->_renderStickyRibbon(); ?>
                	<div class="blog-half">
                        <?php if ($post->Thumbnail): ?>
                            <div class="image-wrap">
                                <div class="image-details">
                                    <?php
                                    echo $this->Media->display($post->Thumbnail->guid, array(
                                        'ar' => 1.62051282051,
                                    ));
                                    ?>
                                    <a<?php echo $this->_imageLinkAtts(); ?>>
                                    <?php
                                    if ($this->getParam('lightBoxLinkDisplay') === 'true') {
                                        echo '<i class="icon-search"></i>';
                                    }
                                    ?>
                                    </a>
                                </div>
                                <div class="line-details">
                                    <div class="line-post-format <?php echo $this->Wp->getPostFormatIcon(); ?>"></div>
                                    <div class="line-date">
                                    <?php
                                    echo $this->Html->postDetails('date', array(
                                        'beforeDetail' => '',
                                        'afterDetail' => '',
                                        'prefixes' => '',
                                        'formats' => array(
                                            'date' => '\<\s\t\r\o\n\g\>j\<\/\s\t\r\o\n\g\>\<\s\p\a\n\>M\<\/\s\p\a\n\>',
                                        ),
                                    ));
                                    ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="blog-half">
                        
                    	<div class="half-content">
                            <h3 class="line-heading"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
                            <?php

                            if ($this->getParam('excerptLength') > 0) {
                                echo $this->Text->paragraphize($this->Wp->getTheExcerpt((int) $this->getParam('excerptLength')));
                            //     echo $this->Text->paragraphize($this->Text->truncate(get_the_excerpt(), $this->getParam('excerptLength')));
                            }
                            ?>
                            <a href="<?php the_permalink(); ?>" class="bluebox-more-link">
                                <?php _e('Read More', 'gummfw'); ?><span class="icon-chevron-right"></span>
                            </a>
                        </div>
                    </div>
					<div class="bluebox-clear"></div>
                    <div class="line-meta-details">
                        <?php
                        echo trim($this->Html->postDetails(array(
                            'author', 'date', 'categories', 'comments',
                        ), array(
                            'beforeDetail' => '',
                            'afterDetail' => ' /',
                            'prefixes' => array(
                                'author' => __('By', 'gummfw'),
                                'date' => '',
                                'category' => '',
                                'comments' => '',
                            ),
                        )), ' /');
                        ?>
                    </div>

            	</div>
            <?php endwhile; ?>
        </div>
<?php
    }
    
    public function renderVerticalSingleLayout() {
        echo '<div class="blog-1-col">';
        while (have_posts()) {
            the_post();
            global $post;
            View::renderElement('layout-components-parts/post/single-vertical-item', array(
                'lightBoxLinkDisplay' => $this->getParam('lightBoxLinkDisplay') === 'true',
                'elementId' => $this->id(),
            ));
        }
        echo '</div>';
    }
    
    private function _imageLinkAtts() {
        global $post;
        
        $atts = array(
            'href' => get_permalink(),
            'class' => array('image-details-link'),
        );
        
        if ($this->getParam('lightBoxLinkDisplay') === 'true') {
            $atts['class'][] = 'image-wrap-mask';
            if ($post->Thumbnail) {
                $atts['href'] = $post->Thumbnail->permalink;
                $atts['rel'] = 'prettyPhoto[' . $this->htmlElementId . ']';
            }
        }
        
        return $this->Html->_constructTagAttributes($atts);
    }
    
    private function _renderStickyRibbon() {
        global $post;
        
        if (is_sticky() && is_home() && is_paged() === false):
?>
        <div class="ribbon-container">
            <div class="ribbon-pro">
                <div class="content"><i class="icon-star"></i></div>
                <div class="back-sh"></div>
                <div class="back-sh-2"></div>
            </div>
        </div>
        
<?php
        endif;
    }
}
?>