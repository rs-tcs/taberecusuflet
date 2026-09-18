<?php
class BreadcrumbHelper extends GummHelper {
    public $helpers = array(
        'Wp',
        'Text',
    );
    
    private $post;
    private $menuItems;
    
    public function display($options=array()) {
        $options = array_merge(array(
            'separator' => ' / ',
        ), $options);
        
        $crumbs = array();
        
        $path = array();
    
        $post = GummRegistry::get('Model', 'Post')->getQueriedObject();
        if (is_page() && $post->ID != $this->Wp->getFrontPageId()) {
            $path = $this->getPathFromNavMenu($post->ID);
            if (!$path) {
                $path = $this->getPathFromAncestors($post);
            }

        } elseif ($post && is_home()) {
            $path[] = $this->getNode($post, 'page');
        } elseif (is_single()) {
            $postCategories = $this->Wp->getPostCategories($post);
            $counter = 1;
            foreach ($postCategories as $catId => $postCategory) {
                if ($counter > 4) {
                    break;
                }
                $path[] = array(
                    'id' => $catId,
                    'title' => $postCategory,
                    'url' => $this->Wp->getPostCategoryLink($post, $catId),// get_category_link($catId),
                );
                
                $counter++;
            }
            $path[] = $this->getNode($post, 'page');
        } elseif (is_search()) {
            $path[] = array(
                'id' => null,
                'title' => __('Search', 'gummfw'),
                'url' => null,
            );
        }
        
        array_unshift($path, array(
            'id'    => $this->Wp->getFrontPageId(),
            'title' => __('Home', 'gummfw'),
            'url'   => home_url(),
        ));
        
        $path = apply_filters('gumm_breadcrumb_path', $path);
        
        foreach ($path as $item) {
            $crumbs[] = $this->crumb($item);
        }
        
        return implode($options['separator'], $crumbs);
    }
    
    public function crumb($item) {
        $linkAtts = array(
            'href' => $item['url'],
            'title' => $item['title'],
        );
        
        $displayTitle = $this->Text->truncate($item['title'], 100, array('exact' => true));
        
        return '<a' . $this->_constructTagAttributes($linkAtts) . '>' . $displayTitle . '</a>';
    }
    
    public function getPathFromNavMenu($postId) {
        $path = array();
        if (is_page()) {
            if ($this->menuItems === null) {
                $locations = get_nav_menu_locations();
                if (isset($locations['prime_nav_menu'])) {
                    $this->menuItems = wp_get_nav_menu_items($locations['prime_nav_menu']);
                } else {
                    $this->menuItems = false;
                }
            }
            
            if ($this->menuItems) {
                foreach ($this->menuItems as $menuItem) {
                    if ($menuItem->object_id == $postId) {
                        $node = $this->getNode($menuItem);
                        if ($node) {
                            $path[] = $node;
                        }
                        if ($menuItem->menu_item_parent) {
                            $parentNodes = $this->getParentNodes($menuItem->menu_item_parent);
                            $path = array_merge($path, $parentNodes);
                        }
                        
                        break;
                    }
                }
            }
        }
        $path = array_reverse($path);
        
        return $path;
    }
    
    public function getParentNodes($objId) {
        $nodes = array();
        foreach ($this->menuItems as $menuItem) {
            if ($menuItem->ID == $objId) {
                $node = $this->getNode($menuItem);
                if ($node) {
                    $nodes[] = $node;
                }
                if ($menuItem->menu_item_parent) {
                    $nodes = array_merge($nodes, $this->getParentNodes($menuItem->menu_item_parent));
                }
                
                break;
            }
        }
        
        return $nodes;
    }
    
    public function getPathFromAncestors($post) {
        $path = array();
        $node = $this->getNode($post, 'page');
        if ($node !== false) {
            $path[] = $node;
        }
        foreach ($post->ancestors as $ancestorId) {
            $node = $this->getNode($ancestorId, 'postId');
            if ($node !== false) {
                $path[] = $node;
            }
        }
        
        $path = array_reverse($path);
        
        return $path;
    }
    
    private function getNode($item, $type='menuItem') {
        $result = false;
        
        if ($type === 'menuItem') {
            $id     = $item->object_id;
            $title  = apply_filters('the_title', $item->title, $item->ID);
            $url    = esc_attr($item->url);
        } elseif ($type === 'postId') {
            $id     = $item;
            $title  = get_the_title($id);
            $url    = get_permalink($id);
        } else {
            $id     = $item->ID;
            $title  = $item->post_title;
            $url    = get_permalink($item->ID);
        }
        if ($id != $this->Wp->getFrontPageId()) {
            $result = array(
                'id'    => $id,
                'title' => $title,
                'url'   => $url,
            );
        }
        
        return $result;
    }
    
}
?>