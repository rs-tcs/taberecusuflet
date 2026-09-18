<?php
class SinglePortfolioDetailsLayoutElement extends GummLayoutElement {
    protected $id = '232A32F0-E15A-4EBE-9889-EA97F98F40F6';
    
    /**
     * @var string
     */
    public $group = 'single';
    
    protected $supports = array();
    
    public $editable = false;
    
    protected $gridColumns = 3;
    
    public function title() {
        return __('Portfolio Details', 'gummfw');
    }
    
    protected function _fields() {
        return array();
    }
    
    protected function _render($options) {
        global $post;
        
        $rowSpan = $this->getRowSpan();
?>
        <div class="row-fluid project-wrap">
            <?php if ($rowSpan > 6): ?>
            <div class="span8">
            <?php endif; ?>
                <div class="project-description">
                <!-- BEGIN bluebox heading -->

                <div class="bluebox-heading-wrap">
                    <h3 class="bluebox-heading"><?php _e('Description', 'gummfw'); ?></h3>
                </div>
        
                <!-- END bluebox heading -->
                <?php the_content(); ?>
                </div>
            <?php if ($rowSpan > 6): ?>
            </div>
            <?php endif; ?>
            <?php if ($rowSpan > 6): ?>
            <div class="span4"> 
            <?php endif; ?>
                <div class="project-details">
                    <!-- BEGIN bluebox heading -->

                    <div class="bluebox-heading-wrap">
                        <h3 class="bluebox-heading"><?php _e('Details', 'gummfw'); ?></h3>
                    </div>
        
                    <!-- END bluebox heading -->
                    <?php if (isset($post->PostMeta['project_client']) && $post->PostMeta['project_client']): ?>
                    <p>
                        <span class="span-bb-label"><?php _e('Client', 'gummfw'); ?>:</span>
                        <strong><?php echo $post->PostMeta['project_client']; ?></strong>
                    </p>
                    <?php endif; ?>
                    <?php if (isset($post->PostMeta['project_date']) && $post->PostMeta['project_date']): ?>
                    <p>
                        <span class="span-bb-label"><?php _e('Date', 'gummfw'); ?>:</span>
                        <strong><?php echo $post->PostMeta['project_date']; ?></strong>
                    </p>
                    <?php endif; ?>
                    <?php if ($tags = $this->Wp->getPostTags($post, true)): ?>
                    <p>
                        <span class="span-bb-label"><?php _e('Tags', 'gummfw'); ?>:</span>
                        <strong>
                            <?php
                            $items = array();
                            foreach ($tags as $tag) {
                                $items[] = '<a href="' . $tag['url'] . '">' . $tag['title'] . '</a>';
                            }
                            echo implode(', ', $items);
                            ?>
                        </strong>
                        </p>
                    <?php endif; ?>
                    <?php if (isset($post->PostMeta['project_link_url']) && $post->PostMeta['project_link_url']): ?>
                        <a href="<?php echo $this->Html->url($post->PostMeta['project_link_url']); ?>" class="bluebox-button light extra" style="margin:12px 0 0 0;" target="_blank">
                            <?php echo $post->PostMeta['project_link_title']; ?><span class="icon-chevron-right"></span>
                        </a>
                    <?php endif; ?>
                </div>
            <?php if ($rowSpan > 6): ?>
            </div>
            <?php endif; ?>
        </div>
<?php
    }
}
?>