<?php
class SingleTaxonomiesLayoutElement extends GummLayoutElement {
    protected $id = '5C227313-1864-4CE3-96F3-1AFAE72B439D';
    
    /**
     * @var string
     */
    public $group = 'single';
    
    public $supports = array();
    
    protected $gridColumns = 12;
    
    public function title() {
        return __('Post Tags/Categories', 'gummfw');
    }
    
    protected function _fields() {
        return array(
            'taxonomy' => array(
                'name' => __('Source Settings', 'gummfw'),
                'type' => 'radio',
                'value' => 'tag',
                'inputOptions' => array(
                    'tag' => __('Tag', 'gummfw'),
                    'category' => __('Category', 'gummfw'),
                )
            ),
        );
    }
    
    protected function _render($options) {
        global $post;
        $items = array();
        switch ($this->getParam('taxonomy')) {
         case 'tag':
            $tags = $this->Wp->getPostTags($post);
            foreach ($tags as $tag) {
                $items[] = array(
                    'title' => $tag->name,
                    'url'   => get_tag_link($tag->term_id),
                );
            }
            break;
         case 'category':
            $categories = $this->Wp->getPostCategories($post);
            foreach ($categories as $catId => $catName) {
                $items[] = array(
                    'title' => $catName,
                    'url'   => get_category_link($catId),
                );
            }
            break;
        }
        
        echo '<div class="tagline">';
        foreach ($items as $item) {
            echo '<a href="' . $item['url'] . '">' . $item['title'] . '</a>';
        }
        echo '</div>';

    }
}
?>