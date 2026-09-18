<?php
class LayoutModel extends GummModel {
    /**
     * @var array
     */
    public $inRelation = array('Option', 'Sidebar', 'PostMeta');
    
    /**
     * @var string
     */
    private $currentLayoutPage;
    
    /**
     * @var array
     */
    private $layoutTypes = array();
    
    /**
     * @var array
     */
    private $layoutSchemas = array();
    
    /**
     * @var array
     */
    private $layoutSchemaPriorityMap = array(
        'index' => array(
            'blog' => array(
                'blog-loop',
                'blog-post' => array(
                    'post-post-{n}'
                ),
            ),
            'portfolio' => array(
                'portfolio-loop',
                'portfolio-post' => array(
                    'portfolio-post-{n}',
                ),
            ),
            'gallery' => array(
                'gallery-post' => array(
                    'gallery-post-{n}',
                ),
            ),
            'news' => array(
                'news-post' => array(
                    'news-post-{n}',
                ),
            ),
            'video' => array(
                'video-post' => array(
                    'video-post-{n}',
                ),
            ),
            'staff' => array(
                'staff-post' => array(
                    'staff-post-{n}',
                ),
            ),
            'event' => array(
                'event-post' => array(
                    'event-post-{n}',
                ),
            ),
            'post-page-{n}',
            'woocommerce_shop' => array(
                'product-post' => array(
                    'product-post-{n}'
                ),
            ),
        ),
        '404',
    );
    
    /**
     * @var array
     */
    private $layoutTypePriorityMap = array(
        'default' => array(
            'blog-loop' => array(
                'blog-post',
            ),
            'portfolio-loop' => array(
                'portfolio-post'
            ),
        ),
    );
    
    /**
     * @return string
     */
    public function getCurrentLayoutPage() {
        global $post;
        if (!$this->currentLayoutPage) {
            $layoutPage = 'index';
            if (is_page()) {
                if (is_page_template('template_portfolio.php')) {
                    $layoutPage = 'portfolio';
                } else {
                    $layoutPage = 'post-page-' . get_the_ID();
                }
            } elseif(is_tax('portfolio_cat')) {
                $layoutPage = 'portfolio';
            } elseif (is_home()) {
                // if ($id = $this->getBlogPageId()) {
                //     $layoutPage = 'post-page-' . $id;
                // } else {
                    $layoutPage = 'blog';
                // }
            } elseif (function_exists('is_shop') && is_shop() || is_tax('product_cat')){
                $layoutPage = 'woocommerce_shop';
            } elseif (is_category()) {
                $layoutPage = 'blog';
            } elseif (is_archive()) {
                $layoutPage = 'blog';
            } elseif (is_search()) {
                $layoutPage = 'index';
            } elseif (is_single()) {
                $postType = get_post_type();
                $layoutPage = $postType . '-post';
                switch ($postType) {
                 case 'post':
                    $layoutPage = 'blog-post';
                    break;   
                }
            } elseif (is_404()) {
                $layoutPage = '404';
            }
            $this->currentLayoutPage = $layoutPage;
        }
    
        return $this->currentLayoutPage;
    }
    
    /**
     * @var string
     * @return void
     */
    public function setCurrentLayoutPage($layoutPage) {
        $this->currentLayoutPage = $layoutPage;
    }
    
    /**
     * @param string $optionId
     * @param bool $recursive
     * @return array
     */
    public function findSchemaStringForOption($optionId, $recursive=true) {
        $parts = explode('.', $optionId);

        if (count($parts) == 1) {
            $schema = $this->findSchemaStringForLayout($optionId, $recursive);
        } else {
            $schema = $this->findSchemaStringForLayout($parts[1], $recursive);
        }
        
        return $schema;
    }
    
    /**
     * @param string $layout
     * @return array
     */
    public function findSchemaForLayout($layout=null) {
        if (!$layout) $layout = $this->getCurrentLayoutPage();
        
        $schemaString = $this->findSchemaStringForLayout($layout);
        
        $schema = array('sidebars' => array(), 'content' => array());
        switch ($schemaString) {
         case 'l-c-r':
            $schema['sidebars'] = array('left' => true, 'right' => true);
            break;
         case 'c-r':
            $schema['sidebars'] = array('right' => true);
            break;
         case 'l-c':
            $schema['sidebars'] = array('left' => true);
            break;
        }
        
        return $schema;
    }
    
    /**
     * @param string $layout
     * @param bool $recursive
     * @return string
     */
    public function findSchemaStringForLayout($layout=null, $recursive=true) {
        if (!$layout) $layout = $this->getCurrentLayoutPage();
        
        if (isset($this->layoutSchemas[$layout][(int) $recursive])) {
            return $this->layoutSchemas[$layout][(int) $recursive];
        }
        
        $genericKey = preg_replace("'^post-([a-z]+)-([0-9]+)$'iU", 'post-${1}-{n}', $layout);

        if (preg_match("'^post-[a-z]+-([0-9]+)$'iU", $layout, $layoutPostId)) {
            $layoutPostId = $layoutPostId[1];
        }
        
        if ($recursive === false) {
            $optionId = $this->constructOptionId('layoutSchema', $layout);
            if ($layoutPostId) {
                $schemaString = $this->PostMeta->find($layoutPostId, $optionId);
            } else {
                $schemaString = $this->Option->find($optionId);
            }
        } else {
            $schemaMap = Set::prioritize($this->layoutSchemaPriorityMap, $genericKey);
            $schemaString = '';
            foreach ($schemaMap as $layoutKey) {
                $optionId = $this->constructOptionId('layoutSchema', $layoutKey);
                if (strpos($layoutKey, '{n}') !== false && $layoutPostId) {
                    $optionId = $this->constructOptionId('layoutSchema', $layout);
                    $schemaString = $this->PostMeta->find($layoutPostId, $optionId);
                } else {
                    $schemaString = $this->Option->find($optionId);
                }

                if ($schemaString) break;
            }
        }
        
        $this->layoutSchemas[$layout][(int) $recursive] = $schemaString;
        
        return $schemaString;
    }
    
    /**
     * @param string $schemaString
     * @param string $layout
     * @return void
     */
    public function setSchemaStringForLayout($schemaString, $layout=null) {
        if (!$layout) $layout = $this->getCurrentLayoutPage();
        
        $this->layoutSchemas[$layout][0] = $schemaString;
        $this->layoutSchemas[$layout][1] = $schemaString;
    }
    
    /**
     * @param string $layout
     * @return array
     */
    public function findSidebarsForLayout($layout=null) {
        if (!$layout) $layout = $this->getCurrentLayoutPage();
        
        if ($layout === 'blog' && $id = $this->getBlogPageId()) {
            $layout = 'post-page-' . $id;
        }
        
        $genericKey = preg_replace("'^post-([a-z]+)-([0-9]+)$'iU", 'post-${1}-{n}', $layout);
        
        $schemaMap = Set::prioritize($this->layoutSchemaPriorityMap, $genericKey);
        $sidebarsSchema = $this->Sidebar->getSchema();
        foreach ($sidebarsSchema as $sidebarOrientation => &$sidebarData) {
            foreach ($schemaMap as $layoutKey) {
                if (strpos($layoutKey, '{n}') !== false) $layoutKey = $layout;
                $sidebarData = $this->Sidebar->findForLayoutByOrientation($layoutKey, $sidebarOrientation);
                if ($sidebarData) break;
            }
        }

        foreach ($sidebarsSchema as $orientation => &$sidebarData) {
            if (!$sidebarData) $sidebarData = $this->Sidebar->getDefaultForOrientation($orientation);
        }
        
        return $sidebarsSchema;
    }
    
    /**
     * @param string $layout
     * @return string
     */
    public function findLayoutType($layout) {
        if (isset($this->layoutTypes[$layout])) {
            return $this->layoutTypes[$layout];
        }
        
        $_replaceSearch = array('blog-loop');
        $_replaceWith   = array('blog-post');
        
        $type = '';
        $typesPriorityMap = Set::prioritize($this->layoutTypePriorityMap, $layout);
        array_pop($typesPriorityMap); // Get rid of the default key

        foreach ($typesPriorityMap as $type) {
            $type = $this->Option->find($this->constructOptionId('layoutType', $type));
            if ($type) break;
        }
        
        if (strpos($type, $layout) === false && $type) $type = str_replace($_replaceSearch, $_replaceWith, $type);
        
        if ($type) $this->layoutTypes[$layout] = $type;
        
        return $type;
    }
    
    /**
     * @var string $type
     * @var string $layout
     * @return void
     */
    public function setLayoutType($type, $layout=null) {
        if (!$layout) $layout = $this->getCurrentLayoutPage();

        $this->layoutTypes[$layout] = $type;
    }
    
    /**
     * @param int $postId
     * @return string
     */
    public function findLayoutForPortfolioPost($postId) {
        $structure = '';
        $structureArray = $this->PostMeta->find($postId, 'post_loop_dimensions');
        if ($structureArray && is_array($structureArray) && isset($structureArray['rows']) && isset($structureArray['cols'])) {
            $structure = 'w-' . $structureArray['cols'] . '-h-' . $structureArray['rows'];
        }
        
        return $structure;
    }
    
    public function findPageByTemplate($template) {
        global $wpdb;

        $sql = "
            SELECT DISTINCT * FROM $wpdb->posts AS post
            JOIN $wpdb->postmeta AS postmeta ON (post.id = postmeta.post_id)
            WHERE postmeta.meta_key = '_wp_page_template'
            AND postmeta.meta_value = '$template'
            AND post.post_status = 'publish'
            LIMIT 1
        ";
        
        if ($page = $wpdb->get_results($sql)) {
            $page = reset($page);
        }
        
        return $page;
    }
    
    public function findLayoutElements() {
        
    }
    
    public function findLayoutElementsForPost() {
        
    }
    
    public function getPageIdForPostType($postType) {
        $id = 0;
        if ($postType === 'post') {
            $id = $this->getBlogPageId();
        } else {
            $id = $this->Option->find($postType . '_page');
        }
        
        return $id;
    }
    
    public function getBlogPageId() {
        return (int) get_option('page_for_posts');
    }
    
    public function getFrontPageId() {
        return (int) get_option('page_on_front');
    }

}
?>