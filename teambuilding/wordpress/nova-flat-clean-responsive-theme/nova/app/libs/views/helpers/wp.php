<?php
class WpHelper extends GummHelper {
    private $excerptLengthHelper;
	
	public function getOption($optionId, $options=array()) {
        return GummRegistry::get('Model', 'Option')->find($optionId, $options);
	}
	
	public function getConfigOption($optionId, $options=array()) {
        return GummRegistry::get('Model', 'Option')->getConfigOption($optionId, $options);
	}
	
	public function getPostMeta($postId, $key, $single=true) {
        return GummRegistry::get('Model', 'PostMeta')->find($postId, $key, $single);
	}
	
	public function getPostTypeCategories($postType) {
	    return GummRegistry::get('Model', 'Post')->getPostTypeCategories($postType);
	}
	
    public function getPostCategories($post) {
	    return GummRegistry::get('Model', 'Post')->getPostCategories($post);
    }
    
    public function getPostCategoryLink($post, $categoryId) {
        return GummRegistry::get('Model', 'Post')->getPostCategoryLink($post, $categoryId);
    }
    
    public function getPostTags($post, $organized=false) {
        $result = array();
	    if ($tags = GummRegistry::get('Model', 'Post')->getPostTags($post)) {
	        if ($organized === true) {
    	        foreach ($tags as $tag) {
    	            $result[] = array(
                        'title' => $tag->name,
                        'url'   => get_tag_link($tag->term_id),
    	            );
    	        }
	        } else {
	            $result = $tags;
	        }
	    }
	    
	    return $result;
    }
    
    public function getPrevPostLink($attributes=array(), $format='%link', $label='') {
        return $this->getAdjacentPostLink('prev', $attributes, $format, $label);
    }
    
    public function getNextPostLink($attributes=array(), $format='%link', $label='') {
        return $this->getAdjacentPostLink('next', $attributes, $format, $label);
    }
    
    private function getAdjacentPostLink($adj, $attributes, $format, $label) {
        $attributes = array_merge(array(
            'class' => null,
            'showEmpty' => true,
        ), $attributes);
        ob_start();
        switch ($adj) {
         case 'next':
            next_post_link($format, $label);
            break;
         case 'prev':
            previous_post_link($format, $label);
            break;
        }
        $link = ob_get_clean();
        if (!$link && $attributes['showEmpty']) {
            $attributes['class'] .= ' disabled';
            $link = '<a href="#">' . $label . '</a>';
        }
        unset($attributes['showEmpty']);
        $attributes = $this->_constructTagAttributes($attributes);
        if ($attributes && $link) {
            $link = preg_replace("'(<a.*)(>.*</a>)'iU", '$1' . $attributes . '$2', $link);
        }
        
        return $link;
    }
	
	public function getThemeVersion() {
		$currTheme = $this->getThemeData();
		$themeVersion = trim($currTheme['Version']);

		if(!$themeVersion) $themeVersion = "1.0";
		
		return $themeVersion;
	}
	
	public function getThemeData() {
        if (!function_exists('wp_get_theme'))
            $currTheme = get_theme_data(GUMM_TEMPLATEPATH . '/style.css');
        else
            $currTheme = wp_get_theme();
        
        return $currTheme;
	}
	
	public function getCommentsNumber($options=array()) {
	    $options = array_merge(array(
	       'none' => __('No Comments', 'gummfw'),
	       'one' => __('1 Comment', 'gummfw'),
	       'many' => '%d ' . __('Comments', 'gummfw'),
	    ), $options);
	    $num = get_comments_number();
        if ( comments_open() ) {
        	if ( $num == 0 ) {
        		$comments = $options['none'];
        	} elseif ( $num > 1 ) {
        		$comments = sprintf($options['many'], $num);
        	} else {
        		$comments = $options['one'];
        	}
        } else {
        	$comments =  false;
        }
        
        return $comments;
	}
	
	public function getDescriptionFieldsForOption($optionName, $resultOptionId) {
	    $result = array();
	    $descriptions = $this->getOption($optionName);
	    if ($descriptions) {
	        foreach ($descriptions as $description) {
    	        $result[] = array(
    	           'type' => 'text',
    	           'name' => $description['title'],
    	           'id' => GUMM_THEME_PREFIX . '_' . $resultOptionId . '.' . strtolower(Inflector::slug($description['title'])),
    	        );
	        }
	    }
	    
	    return $result;
	}
	
	public function getTheExcerpt($excerptLength=100) {
	    global $post;
	    
	    $this->excerptLengthHelper = $excerptLength;
	    add_filter('excerpt_length', array(&$this, '_filterEnlargeExcerptLength'));
	    $theExcerpt = get_the_excerpt();
	    remove_filter('excerpt_length', array(&$this, '_filterEnlargeExcerptLength'));
	    
	    return $theExcerpt;
	}
	
	public function isPluginActive($path) {
        if (!function_exists('is_plugin_active')) include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        
        return is_plugin_active($path);
	}
	
    public function getBlogPageId() {
        return GummRegistry::get('Model', 'Layout')->getBlogPageId();
    }
    
    public function getFrontPageId() {
        return GummRegistry::get('Model', 'Layout')->getFrontPageId();
    }
    
    public function getPageIdForPostType($postType) {
        return GummRegistry::get('Model', 'Layout')->getPageIdForPostType($postType);
    }
    
    public function setupRawDataAsPost($data, $withRelations=true) {
        if (!is_array($data)) $data = (array) $data;
        
        $data = array_merge(array(
            'ID' => 0,
            'post_author' => 1,
            'post_date' => date('Y-m-d H:i:s'),
            'post_date_gmt' => date('Y-m-d H:i:s'),
            'post_content' => '',
            'post_title' => '',
            'post_excerpt' => '',
            'post_status' => '',
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_password' => '',
            'post_name' => '',
            'to_ping' => '',
            'pinged' => '',
            'post_modified' => date('Y-m-d H:i:s'),
            'post_modified_gmt' => date('Y-m-d H:i:s'),
            'post_content_filtered' => '',
            'post_parent' => 0,
            'guid' => '',
            'menu_order' => 0,
            'post_type' => 'dynamic-post-type',
            'post_mime_type' => '',
            'comment_count' => '0',
            'filter' => 'raw',
        ), $data);
        
        if ($withRelations === true) {
            $data = array_merge(array(
                'Media' => array(),
                'Thumbnail' => false,
                'PostMeta' => array(),
                'GummOption' => array(),
            ), $data);
        }
        
        return (object) $data;
    }
    
    public function getPostFormatIcon($post=null) {
        if (!$post) global $post;
        
        $result = '';
        switch ($post->post_type) {
         case 'post':
            $format = get_post_format($post);
            $result = 'icon-pencil';
            switch ($format) {
             case 'gallery':
                $result = 'icon-picture';
                break;
             case 'video':
                $result = 'icon-facetime-video';
                break;
            }
            break;
         case 'portfolio':
            $result = 'icon-th-large';
            break;
        }

        return $result;
    }
    
    public function _filterEnlargeExcerptLength($length) {
        return $this->excerptLengthHelper;
    }
    
}
?>