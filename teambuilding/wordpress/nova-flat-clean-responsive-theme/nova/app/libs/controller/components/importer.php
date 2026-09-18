<?php
require_once(GUMM_CONFIGS . 'imports.php');

class ImporterComponent extends GummObject {
    
    private $defaultData = array(
        'type' => 'string',
        'parse' => false,
        'data' => null,
    );
    
    private $importedSampleContentData = array(
        'page' => array(),
        'post' => array(),
        'category' => array(),
        'tag' => array(),
        'attachment' => array(),
    );
    
    private $currentSampleDataToImport = array(
        'page' => array(),
        'post' => array(),
        'attachment' => array(),
    );
    
    private $sampleTexts = array(
        'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
        'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?',
        'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.',
    );
    
    public function import() {
        if (!$data = Configure::read('importdata')) return;

        foreach ($data as $optionId => $optionData) {
            $optionData = array_merge($this->defaultData, $optionData);
            
            if (!$optionData['data']) continue;
            
            $toImport = $optionData['data'];
            switch ($optionData['type']) {
             case 'serialized':
                $toImport = unserialize($optionData['data']);
                break;
            }
            
            if ($optionData['parse']) {
                $toImport = $this->_parseOptionData($toImport, $optionData['parse']);
            }
            
            if ($toImport && $optionId) {
                $optionId = ($optionId == 'styles') ? GUMM_THEME_PREFIX . '_' . $optionId : $this->gummOptionId($optionId);
                update_option($optionId, $toImport);
            }
        }
    }
    
    private function _parseOptionData($data, $parsePatterns) {
        $parsePatterns = array_merge(array(
            'url' => false
        ), $parsePatterns);
        
        
        if ($parsePatterns['url']) {
            $data = $this->_fixImportDataUrl($parsePatterns['url'], $data);
        }
        
        return $data;
    }
    
    private function _fixImportDataUrl($patterns, $data) {
        foreach ($patterns as $pattern) {
            if (!isset($pattern['from']) || !isset($pattern['to'])) continue;
            
            foreach ($data as $k => &$v) {
                if (!$v) continue;
                
                if (is_array($v)) {
                    $v = $this->_fixImportDataUrl($patterns, $v);
                } elseif (is_string($v) && strpos($v, $pattern['from']) !== false) {
                    $v = str_replace($pattern['from'], $pattern['to'], $v);
                }
            }
        }
        return $data;
    }
    
    // =========== //
    // SAMPLE DATA //
    // ========== //
    
    public function deleteSampleContent() {      
        $sampleData = $this->getImportedSampleContentData();
        
        if (!$sampleData) return false;
        if (isset($sampleData['init_page_on_front'])) update_option('page_on_front', $sampleData['init_page_on_front']);
        if (isset($sampleData['init_show_on_front'])) update_option('show_on_front', $sampleData['init_show_on_front']);
        if (isset($sampleData['init_page_for_posts'])) update_option('page_for_posts', $sampleData['init_page_for_posts']);
        
        if (isset($sampleData['category'])) {
            foreach ($sampleData['category'] as $postType => $catIds) {
                $termName = $postType == 'post' ? 'category' : $postType . '_category';
                foreach ($catIds as $catId) {
                    wp_delete_term($catId, $termName);
                }
            }
            unset($sampleData['category']);
        }
        if (isset($sampleData['tag'])) {
            foreach ($sampleData['tag'] as $tagId) {
                wp_delete_term($tagId, 'post_tag');
            }
            unset($sampleData['tag']);
        }
        $ids = array_values(Set::filter(Set::flatten($sampleData)));
        if ($ids) {
            foreach ($ids as $id) {
                wp_delete_post($id, false);
            }
        }
        $this->deleteMenus(Configure::read('importContent.menus'));
        
        delete_option(GUMM_THEME_PREFIX . '__sample_content_data');
    }
    
    public function getImportedIds($fromData=null) {
        if ($fromData === null) $fromData = 
        
        $ids = array();
        foreach ($fromData as $k => $v) {
            if (is_array($v)) $ids[] = $this->getImportedIds($v);
            elseif (is_numeric($v)) $ids[] = $v;
        }
        
        return $ids;
    }
    
    public function importSamplePage($pageData) {
        $pageIdentifier = Inflector::slug(strtolower($pageData['name']), '-') . '-imported';
        $pageData['name'] .= ' (imported)';
        $pageData['postType'] = 'page';
        
        $pages = false;
        if ($id = $this->importPost($pageIdentifier, $pageData)) {
            $this->updateSamplePagesData($id);
            $pages = $this->getSamplePages();
        }
        
        return $pages;
    }
    
    public function importSampleContent() {
        $contentData = Configure::read('importContent');
        
        if (isset($contentData['tags'])) {
            $this->importTags($contentData['tags']);
        }
        if (isset($contentData['pages'])) {
            $this->importPages($contentData['pages']);
        }
        if (isset($contentData['posts'])) {
            $this->importPosts($contentData['posts']);
        }
        if (isset($contentData['widgets'])) {
            $this->importWidgets($contentData['widgets']);
        }
        
        if (Set::filter($this->currentSampleDataToImport)) {
            $this->updateSampleContentData($this->currentSampleDataToImport);
        }
        
        if (isset($contentData['menus'])) {
            $this->importMenus($contentData['menus']);
        }
    }
    
    public function importPosts(array $postsData) {
        $postsData = array_merge(array(
            'all' => array(
                'number' => 5
            ),
        ), $postsData);
        
        $postTypes = Configure::read('customPostTypes');
        if (!$postTypes || !is_array($postTypes)) $postTypes = array();
        
        $postTypes = array_merge(array(
            'post' => array(),
        ), $postTypes);
        
        foreach ($postTypes as $postType => $postData) {
            $this->importCategories($postType, $postData);
            $numPosts = isset($postsData[$postType]) ? $postsData[$postType]['number'] : $postsData['all']['number'];
            for ($i=1; $i<=$numPosts; $i++) {
                $postTypeDisplayName = $postType;
                
                
                $this->importPost('post', array(
                    'name' => ucwords('Sample ' . $postTypeDisplayName . ' ' . $i),
                    'postType' => $postType,
                    'postContent' => $this->sampleTexts[rand( 0, (count($this->sampleTexts) - 1) )],
                ), ($i-1));
            }
        }
    }
    
    public function importPages(array $pagesData) {
        foreach ($pagesData as $pageType => $pageData) {
            if ($pageType === 'home') $pageData['isFrontPage'] = true;
            elseif ($pageType === 'blog') $pageData['isBlogPage'] = true;
        
            $pageData['postType'] = 'page';
            $this->importPost($pageType, $pageData);
        }
    }
    
    public function importPost($postIdentifier, array $postData, $siblingCount=null) {
        $userId = get_current_user_id();
        
        $postData = array_merge(array(
            'name' => null,
            'postType' => 'post',
            'isFrontPage' => false,
            'isBlogPage' => false,
            'postContent' => '',
            'postExcerpt' => '',
            'postMimeType' => '',
            'meta' => array(),
        ), $postData);
        
        $existingData = $this->getImportedSampleContentData($postData['postType']);
        $lastInsertedId = false;
        if ($postData['name'] && !array_key_exists($postIdentifier, $existingData)) {
            $lastInsertedId = wp_insert_post(array(
                'post_title' => $postData['name'],
                'post_content' => $postData['postContent'],
                'post_excerpt' => GummRegistry::get('Helper', 'Text')->truncate($postData['postContent'], 200, array('exact' => false)),
                'post_status' => 'publish',
                // 'post_date' => date('Y-m-d H:i:s'),
                'post_author' => $userId,
                'post_type' => $postData['postType'],
                'post_mime_type' => $postData['postMimeType'],
                // 'post_category' => array(0)
            ));
            
            if ($postData['isFrontPage']) {
                $this->currentSampleDataToImport['init_page_on_front'] = get_option('page_on_front');
                $this->currentSampleDataToImport['init_show_on_front'] = get_option('show_on_front');
                update_option('page_on_front', $lastInsertedId);
                update_option('show_on_front', 'page');
            } elseif ($postData['isBlogPage']) {
                $this->currentSampleDataToImport['init_page_for_posts'] = get_option('page_for_posts');
                update_option('page_for_posts', $lastInsertedId);
            }
            
            if ($postData['postType'] != 'page') {
                if ($attachmentId = $this->getAttachmentIdForPostType($postData['postType'], $siblingCount)) {
                    add_post_meta($lastInsertedId, '_thumbnail_id', $attachmentId);
                }
                
                if ($catIds = $this->getRandomCategoriesForPostType($postData['postType'])) {
                    $termName = $postData['postType'] == 'post' ? 'category' : $postData['postType'] . '_category';
                    wp_set_object_terms($lastInsertedId, $catIds, $termName);
                }
                
                if ($tagIds = $this->getRandomTagIdsForPostType($postData['postType'])) {
                    wp_set_object_terms($lastInsertedId, $tagIds, 'post_tag');
                }
            }
            
            if ($postData['meta']) {
                foreach ($postData['meta'] as $k => $v) {
                    if (!is_array($v))
                        $metaData = @unserialize(stripcslashes($v));
                    else
                        $metaData = $v;
                        
                    if ($metaData === false) {
                        $metaData = $v;
                    } elseif ($k === 'layout_components') {
                        $metaData = $this->fixMediaIdsInArray($metaData);
                    } elseif ($k === 'layout') {
                        $val = reset($metaData);
                        $metaData = array('post-page-' . $lastInsertedId => $val);
                    }
                    update_post_meta($lastInsertedId, $this->gummOptionId($k), $metaData);
                }
            }
            
            $this->currentSampleDataToImport[$postData['postType']][$postIdentifier][] = $lastInsertedId;
        }
        
        return $lastInsertedId;
    }
    
    public function importCategories($postType, $postTypeData) {
        if (!$categoriesData = Configure::read('importContent.categories')) return false;
        $termName = $postType == 'post' ? 'category' : $postType . '_category';
        
        if ($postType !== 'post') {
            if (!isset($postTypeData['args'])) return false;
            if (!isset($postTypeData['args']['taxonomies'])) return false;
            if (!in_array($termName, $postTypeData['args']['taxonomies'])) return false;
        }
        
        $categoryData = $categoriesData['all'];
        if (isset($categoriesData[$postType])) $categoryData = $categoriesData[$postType];


        $categoryNames = array();
        if (!isset($categoryData['number']) && is_array($categoryData)) {
            $categoryNames = $categoryData;
        } else {
            for ($i=1; $i<=$categoryData['number']; $i++) {
                $categoryNames[] = 'Sample ' . ucwords($postType) . ' Category ' . $i;
            }
        }
        
        $categoriesIds = array();
        foreach ($categoryNames as $categoryName) {
            $slug = Inflector::slug(strtolower($categoryName));
            $existingId = $this->getImportedSampleContentData('category.' . $postType . '.' . $slug, false, true);
            if (is_array($existingId))
                $existingId = reset($existingId);
                
            if ($catId = $this->importCategory(array(
                'id' => (int) $existingId,
                'name' => $categoryName,
                'taxonomy' => $termName,
            ))) {
                if (!isset($this->currentSampleDataToImport['category'])) $this->currentSampleDataToImport['category'] = array();
                if (!isset($this->currentSampleDataToImport['category'][$postType])) $this->currentSampleDataToImport['category'][$postType] = array();
                $this->currentSampleDataToImport['category'][$postType][$slug] = $catId;
                
            }
        }
    }
    
    public function importCategory($categoryData) {
        $categoryData = array_merge(array(
            'id' => 0,
            'name' => null,
            'taxonomy' => null,
        ), $categoryData);
        
        if (!$categoryData['name'] || !$categoryData['taxonomy']) return false;
        $categoryData['slug'] = Inflector::slug(strtolower($categoryData['name']), '-');
        
        if ($categoryData['id']) {
            return false;
        } else {
            $result = wp_insert_category(array(
                'cat_ID' => $categoryData['id'],
                'cat_name' => $categoryData['name'],
                'taxonomy' => $categoryData['taxonomy'],
                'category_nicename' => $categoryData['slug'],
            ));
        }
        
        return $result;
    }
    
    public function importTags($num) {
        if ($this->getImportedSampleContentData('tag', false, true)) return false;
        
        $this->currentSampleDataToImport['tag'] = array();
        for ($i=1; $i<=$num; $i++) {
            if ($tag = wp_insert_term('tag ' . $i, 'post_tag')) {
                if (!is_wp_error($tag))
                    $this->currentSampleDataToImport['tag'][] = $tag['term_id'];
            }
        }
    }
    
    public function getRandomTagIdsForPostType($postType) {
        $result = false;
        
        $customPostData = Configure::read('customPostTypes.' . $postType . '.taxonomies');
        if ($postType === 'post' || ($customPostData && in_array('post_tag', $customPostData))) {
            if ($tagIds = $this->getImportedSampleContentData('tag', false, true)) {
                $numToPick = rand(1, count($tagIds));
                
                for ($i=1; $i<=$numToPick; $i++) {
                    $randIndex = rand(0, (count($tagIds)-1));
                    $result[] = $tagIds[$randIndex];
                }
            }
        }
        
        return $result;
    }
    
    public function getRandomCategoriesForPostType($postType) {
        $result = false;
        if ($categoriesAvailable = $this->getImportedSampleContentData('category.' . $postType, false, true)) {
            $result = array();
            $catIds = array_values($categoriesAvailable);
            
            $numToPick = rand(1, count($catIds));
            for ($i=1; $i<=$numToPick; $i++) {
                $randIndex = rand(0, (count($catIds)-1));
                $result[] = $catIds[$randIndex];
            }
            
            $result = array_unique($result);
        }
        
        return $result;
    }
    
    public function getAttachmentIdForPostType($postType, $siblingCount=null) {
        $id = false;
        $attachments = $this->getAttachments();
        
        if (!isset($attachments[$postType])) $postType = 'all';
        if (isset($attachments[$postType]) && $attachments[$postType]) {
            sort($attachments[$postType]);
            if ($siblingCount !== null && isset($attachments[$postType][$siblingCount])) {
                $id = $attachments[$postType][$siblingCount];
            } else {
                $id = $attachments[$postType][rand(0, (count($attachments[$postType]) - 1))];
            }
        }
        
        return $id;
    }
    
    public function getRandomAttachmentId() {
        $id = null;
        $attachments = $this->getAttachments();
        if ($attachments) {
            sort($attachments);
            $id = $attachments[rand(0, (count($attachments) - 1))];
        }
        
        return $id;
    }
    
    public function getAttachments() {
        $attachmentsData = $this->getImportedSampleContentData('attachment');
        if (!$attachmentsData) $attachmentsData = $this->importAttachments();
        
        return $attachmentsData;
    }
    
    public function importAttachments(array $attachmentsData=array()) {
        if (!$attachmentsData) $attachmentsData = Configure::read('importContent.attachments');
        if (!$attachmentsData) return array();
        
        foreach ($attachmentsData as $forPostType => $urls) {
            foreach ($urls as $url) {
                $this->importAttachment($url, $forPostType);
            }
        }
        
        $this->updateSampleContentData($this->currentSampleDataToImport);
        
        return $this->currentSampleDataToImport['attachment'];
    }
    
    public function importAttachment($url, $forPostType='all') {
        $existingData = $this->getImportedSampleContentData('attachment');
        if (is_array($existingData) && isset($existingData[$url])) return false;
            
        $upload = $this->fetchRemoteFile($url);
        if (!is_wp_error($upload)) {
    		// as per wp-admin/includes/upload.php
    		$postId = wp_insert_attachment(array(
    		  'guid' => $upload['url'],
    		  'post_title' => 'sample image',
    		), $upload['file']);
    		
    		wp_update_attachment_metadata($postId, wp_generate_attachment_metadata($postId, $upload['file']));
    		
    		$this->currentSampleDataToImport['attachment'][$forPostType][$url] = $postId;
        }
    }
    
    public function getImportedSampleContentData($type=null, $reset=false, $fromPending=false) {
        if (!Set::filter($this->importedSampleContentData) || $reset) {
            if (!$result = get_option(GUMM_THEME_PREFIX . '__sample_content_data')) {
                $result = array();
            }
            $this->importedSampleContentData = $result;
        }
        $contentData = $this->importedSampleContentData;
        if ($fromPending === true) {
            $contentData = Set::merge($contentData, $this->currentSampleDataToImport);
        }
        if ($type) {
            $data = GummHash::get($contentData, $type);
        } else {
            $data = $contentData;
        }
        
        if (!$data) $data = array();
        
        return $data;
    }
    
    private function updateSampleContentData(array $data=array()) {
        $data = array_merge($this->importedSampleContentData, $data);
        update_option(GUMM_THEME_PREFIX . '__sample_content_data', $data);
        
        $this->getImportedSampleContentData(null, true);
    }
    
    public function updateSamplePagesData($ids=null) {
        $data = $this->getSamplePagesData();
        $ids = (array) $ids;


        $data = array_merge($data, $ids);
        
        update_option(GUMM_THEME_PREFIX . '__sample_pages_data', $data);
    }
    
    public function getSamplePagesData() {
        $ids = (array) get_option(GUMM_THEME_PREFIX . '__sample_pages_data');
        
        return Set::filter($ids);
    }
    
    public function getSamplePages() {
        $result = array();
        if ($ids = $this->getSamplePagesData()) {
            $result = GummRegistry::get('Model', 'Post')->find('all', array(
                'conditions' => array(
                    'id' => $ids,
                    'postType' => 'page'
                ),
            ));
        }
        return $result;
    }
    
    public function deleteSamplePages() {
        if ($pages = $this->getSamplePages()) {
            foreach ($pages as $page) {
                wp_delete_post($page->ID, true);
            }
        }
        
        return delete_option(GUMM_THEME_PREFIX . '__sample_pages_data');
    }
    
    // ===== //
    // MENUS //
    // ===== //
    
    public function deleteMenus(array $data=array()) {
        if (!$data) return false;
        
        $themeLocations = get_registered_nav_menus();
        if (!$activeLocations = get_theme_mod('nav_menu_locations')) {
            $activeLocations = array();
        }
        
        foreach ($data as $menuData) {
            $menu = wp_get_nav_menu_object($menuData['name']);
            if ($menu !== false) {
                wp_delete_nav_menu($menuData['name']);
                if (isset($menuData['locations'])) {
                    $menuData['locations'] = (array) $menuData['locations'];
                    foreach ($menuData['locations'] as $location) {
                        if (isset($themeLocations[$location])) {
                            $activeLocations[$location] = '';
                        }
                    }
                }
            }
        }
        
        if ($activeLocations) {
            set_theme_mod('nav_menu_locations', $activeLocations);
        }
    }
    
    public function importMenus(array $data=array()) {
        if (!$data) return false;
        
        $themeLocations = get_registered_nav_menus();
        if (!$activeLocations = get_theme_mod('nav_menu_locations')) {
            $activeLocations = array();
        }
        
        foreach ($data as $menuData) {
            $menuId = $this->updateMenu($menuData);
            if (isset($menuData['locations'])) {
                $menuData['locations'] = (array) $menuData['locations'];
                foreach ($menuData['locations'] as $location) {
                    if (isset($themeLocations[$location])) {
                        $activeLocations[$location] = $menuId;
                    }
                }
            }
        }
        
        if ($activeLocations) {
            set_theme_mod('nav_menu_locations', $activeLocations);
        }
    }
    
    public function updateMenu($menuData) {
        $menu = wp_get_nav_menu_object($menuData['name']);
        if ($menu !== false) {
            return;
            wp_delete_nav_menu($menuData['name']);
        }
        $menuId = wp_create_nav_menu($menuData['name']);
        $menu = wp_get_nav_menu_object($menuData['name']);

        $position = 1;
        foreach ($menuData['items'] as $menuItem) {
            $this->addItemToMenu($menu, $menuItem, $position);
            $position++;
        }
        
        return $menuId;
    }
    
    private function addItemToMenu($menu, $menuItem, $position=1, $parentId=0) {
        if (isset($menuItem['page']) && $page = $this->getImportedSampleContentData('page.' . $menuItem['page'])) {
            $pageId = reset($page);
            $menuItemId = wp_update_nav_menu_item($menu->term_id, 0, array(
                // 'menu-item-title' => ucwords($menuItem['page']),
                'menu-item-object' => 'page',
                'menu-item-object-id' => $pageId,
                'menu-item-parent-id' => $parentId,
                'menu-item-type' => 'post_type',
                'menu-item-status' => 'publish'
            ));
            
            if (isset($menuItem['items'])) {
                $subMenuPosition = 1;
                foreach ($menuItem['items'] as $subMenuItem) {
                    $this->addItemToMenu($menu, $subMenuItem, $subMenuPosition, $menuItemId);
                    $subMenuPosition++;
                }
            }
        }
    }
    
    // ======= //
    // WIDGETS //
    // ======= //
    
    public function importWidgets($data) {
        $sidebars = wp_get_sidebars_widgets();
        $widgetsToSave = array();
        $widgetsCount = array();
        foreach ($data as $sidebarId => $widgets) {
            foreach ($widgets as $widgetData) {
                $id = 'widget_' . $widgetData['id'];
                if (!isset($widgetsToSave[$id])) $widgetsToSave[$id] = array();
                $widgetsToSave[$id][] = $widgetData['params'];
            }
        }
        
        // d($sidebars);

        foreach ($data as $sidebarId => $widgets) {
            if (!array_key_exists($sidebarId, $sidebars)) {
                continue;
            }
            if ($sidebars[$sidebarId]) $sidebars[$sidebarId] = array();
            foreach ($widgets as $widgetData) {
                $id = $widgetData['id'];
                if (!isset($widgetsCount[$id])) $widgetsCount[$id] = 1;
                
                $widgetFriendlyName = $id . '-' . $widgetsCount[$id];
                
                $sidebars[$sidebarId][] = $widgetFriendlyName;
                
                $widgetsCount[$id]++;
            }
        }
        
        foreach ($widgetsToSave as $widgetId => $data) {
            $dataToSave = array();
            $counter = 1;
            foreach ($data as $d) {
                $dataToSave[$counter] = $d;
                $counter++;
            }
            
            $dataToSave['_multiwidget'] = 1;
            
            update_option($widgetId, $dataToSave);
        }
        wp_set_sidebars_widgets($sidebars);
    }
    
    /**
     * Attempt to download a remote file attachment
     *
     * @param string $url URL of item to fetch
     * @param array $post Attachment details
     * @return array|WP_Error Local file location details on success, WP_Error otherwise
     */
    private function fetchRemoteFile( $url ) {
        // extract the file name and extension from the url
        $file_name = basename( $url );

        // get placeholder file in the upload dir with a unique, sanitized filename
        $upload = wp_upload_bits( $file_name, 0, '', date('Y-m-d H:i:s') );
        if ( $upload['error'] )
            return new WP_Error( 'upload_dir_error', $upload['error'] );

        // fetch the remote url and write it to the placeholder file
        $headers = wp_get_http( $url, $upload['file'] );

        // request failed
        if ( ! $headers ) {
            @unlink( $upload['file'] );
            return new WP_Error( 'import_file_error', __('Remote server did not respond', 'wordpress-importer') );
        }

        // make sure the fetch was successful
        if ( $headers['response'] != '200' ) {
            @unlink( $upload['file'] );
            return new WP_Error( 'import_file_error', sprintf( __('Remote server returned error response %1$d %2$s', 'wordpress-importer'), esc_html($headers['response']), get_status_header_desc($headers['response']) ) );
        }

        $filesize = filesize( $upload['file'] );

        if ( isset( $headers['content-length'] ) && $filesize != $headers['content-length'] ) {
            @unlink( $upload['file'] );
            return new WP_Error( 'import_file_error', __('Remote file is incorrect size', 'wordpress-importer') );
        }

        if ( 0 == $filesize ) {
            @unlink( $upload['file'] );
            return new WP_Error( 'import_file_error', __('Zero size file downloaded', 'wordpress-importer') );
        }

        $max_size = (int) $this->max_attachment_size();
        if ( ! empty( $max_size ) && $filesize > $max_size ) {
            @unlink( $upload['file'] );
            return new WP_Error( 'import_file_error', sprintf(__('Remote file is too large, limit is %s', 'wordpress-importer'), size_format($max_size) ) );
        }

        // keep track of the old and new urls so we can substitute them later
        // $this->url_remap[$url] = $upload['url'];
        // $this->url_remap[$post['guid']] = $upload['url']; // r13735, really needed?
        // // keep track of the destination if the remote url is redirected somewhere else
        // if ( isset($headers['x-final-location']) && $headers['x-final-location'] != $url )
        //     $this->url_remap[$headers['x-final-location']] = $upload['url'];

        return $upload;
    }
    
	/**
	 * Decide what the maximum file size for downloaded attachments is.
	 * Default is 0 (unlimited), can be filtered via import_attachment_size_limit
	 *
	 * @return int Maximum attachment file size to import
	 */
	function max_attachment_size() {
		return apply_filters( 'import_attachment_size_limit', 0 );
	}
	
	private function fixMediaIdsInArray($data) {
	    foreach ($data as $k => $v) {
	        if (is_array($v)) {
	            if (isset($v['media'])) {
	                if (is_array($v['media'])) {
	                    foreach ($v['media'] as $c => $mediaId) {
	                        if (is_numeric($mediaId)) {
                                $data[$k]['media'][$c] = $this->getAttachmentIdForPostType('all');
	                        }
	                    }
	                } elseif (is_numeric($v['media'])) {
	                    $data[$k]['media'] = $this->getAttachmentIdForPostType('all');
	                }
	            } else {
	                $data[$k] = $this->fixMediaIdsInArray($data[$k]);
	            }
	        }
	    }
	    
	    return $data;
	}
}
?>