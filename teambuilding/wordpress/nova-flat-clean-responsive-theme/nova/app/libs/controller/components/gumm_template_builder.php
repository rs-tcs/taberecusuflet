<?php
class GummTemplateBuilderComponent extends GummObject {
    const DYNAMIC_TEMPLATE_NAME = 'dynamic_template';
    
    /**
     * @var object
     */
    private $post;
    
    /**
     * @var mixed string|int
     */
    private $objectIdentifier;
    
    /**
     * @var string
     */
    private $template = 'default';
    
    /**
     * @var string
     */
    private $model;
    
    /**
     * @var array
     */
    private $elementsAvailable = array();
    
    /**
     * @var array
     */
    private $elementsEnabled = array();
    
    /**
     * @param object $post
     * @param string $template
     * @return void
     */
    public function __construct($post=null, $template='', $model='GummPostMeta') {
        $this->model = $model;
        $this->objectIdentifier = $template;
        if ($post) {
            if ($model === 'GummPostMeta') {
                $this->objectIdentifier = $post->ID;
            }
            // $template = get_post_meta($post->ID, '_wp_page_template', true);
            // if (!$template) $template = $this->getDefaultTemplate($post);
            // $this->template = $template;
            
            $this->post =& $post;
        }
        
        $this->importElements();
    }
    
    private function importElements() {
        App::import('Core', 'GummFolder');

        $Folder = new GummFolder(GUMM_LAYOUT_ELEMENTS);
        $elementFiles = $Folder->findRecursive('.*\.php');
        $Folder->cd(GUMM_LAYOUT_ELEMENTS_SINGLE);
        $elementFiles = array_merge($elementFiles, $Folder->findRecursive('.*\.php'));
        
        $availableElements = Set::flatten(Set::classicExtract(array_values(Configure::read('Data.BuilderElements')), '{n}.elements'));

        $elementsAvaialbleMap = array();
        foreach ($elementFiles as $layoutElementFullPath) {
            $basename = basename($layoutElementFullPath, '.php');
            if (in_array($basename, $availableElements)) {
                $elementsAvaialbleMap[$basename] = $layoutElementFullPath;
            }
        }

        foreach ($availableElements as $basename) {
            if (isset($elementsAvaialbleMap[$basename])) {
                require_once($elementsAvaialbleMap[$basename]);
                
                $className = Inflector::camelize($basename) . 'LayoutElement';
                $settings = array();
                if ($this->post) {
                    $settings['postId'] = $this->post->ID;
                }
                $obj = new $className($settings);
                $this->elementsAvailable[Inflector::underscore($basename)] = $obj;
            }
        }
    }
    
    /**
     * @DEPRICATED
     */
    public function getDefaultTemplate($post) {
        if ($post->post_status == 'auto-draft') return false;
        
        $args = array(
            'p' => $post->ID,
            'post_type' => $post->post_type
        );
        if ($post->post_type == 'page') {
            $args = array(
                'page_id' => $post->ID
            );
        }

        $template = false;
        if     ( is_404()            && $template = get_404_template()            ) :
        elseif ( is_search()         && $template = get_search_template()         ) :
        elseif ( is_tax()            && $template = get_taxonomy_template()       ) :
        elseif ( is_front_page()     && $template = get_front_page_template()     ) :
        elseif ( is_home()           && $template = get_home_template()           ) :
        elseif ( is_attachment()     && $template = get_attachment_template()     ) :
        elseif ( is_single()         && $template = get_single_template()         ) :
        elseif ( is_page()           && $template = get_page_template()           ) :
        elseif ( is_category()       && $template = get_category_template()       ) :
        elseif ( is_tag()            && $template = get_tag_template()            ) :
        elseif ( is_author()         && $template = get_author_template()         ) :
        elseif ( is_date()           && $template = get_date_template()           ) :
        elseif ( is_archive()        && $template = get_archive_template()        ) :
        elseif ( is_comments_popup() && $template = get_comments_popup_template() ) :
        elseif ( is_paged()          && $template = get_paged_template()          ) :
        else :
        	$template = get_index_template();
        endif;
        
        // wp_reset_query();
        
        if ($template && is_file($template)) $template = basename($template);
        else $template = false;
        
        return $template;
    }
    
    /**
     * @param string $type
     * @return array
     */
    public function getTemplateElementsEnabled($type=null) {
        if (isset($this->elementsEnabled[$this->objectIdentifier])) {
            if ($type && isset($this->elementsEnabled[$this->objectIdentifier][$type])) {
                return $this->elementsEnabled[$this->objectIdentifier][$type];
            } else {
                return $this->elementsEnabled[$this->objectIdentifier];
            }
        }
        
        $basename = basename($this->template, '.php');
        
        if ($this->model === 'GummPostMeta' && $this->post) {
            $elementsForPost = GummRegistry::get('Model', 'PostMeta')->find($this->post->ID, 'layout_components');
            // debug(is_home());
            if (!$elementsForPost && $this->post->post_type !== 'page') {
                $this->objectIdentifier = $this->post->post_type === 'post' ? 'blog-post' : $this->post->post_type . '-post';
                $optionId = $this->constructOptionId('layoutComponents', $this->objectIdentifier);
                $elementsForPost = GummRegistry::get('Model', 'Option')->find($optionId);
            }
        } else {
            $optionId = $this->constructOptionId('layoutComponents', $this->objectIdentifier);
            $elementsForPost = GummRegistry::get('Model', 'Option')->find($optionId);
        }
        
        if (!$elementsForPost) {
            $configTemplates = Configure::read('Data.BuilderTemplates');
            if (isset($configTemplates[$this->objectIdentifier])) {
                $elementsForPost = $configTemplates[$this->objectIdentifier];
            } else {
                $elementsForPost = $configTemplates['default'];
            }
        }
        
        $layoutElements = array('content' => array(), 'header' => array());
        foreach ($elementsForPost as $position => $elements) {
            foreach ($elements as $key => $layoutElement) {
                $layoutElementData = array();
                if ($this->post) {
                    $layoutElementData = array('postId' => $this->post->ID);                    
                }
                $layoutElementId = null;
                if (is_array($layoutElement) && isset($layoutElement['basename'])) {
                    $layoutElementName = $layoutElement['basename'];
                    $layoutElementData = array_merge($layoutElementData, $layoutElement);
                    $layoutElementId = $key;
                } else {
                    if (is_array($layoutElement)) {
                        $layoutElementName = $key;
                        $layoutElementData = array_merge($layoutElementData, array('settings' => $layoutElement));
                    } else {
                        $layoutElementName = $layoutElement;
                    }
                }

                $layoutElementName = Inflector::underscore($layoutElementName);
                
                if (isset($this->elementsAvailable[$layoutElementName])) {
                    $className = Inflector::camelize($layoutElementName) . 'LayoutElement';

                    $layoutElementObj = new $className($layoutElementData);
                    if ($layoutElementId) {
                        $layoutElementObj->id($layoutElementId);
                    }
                    if (isset($optionId)) {
                        $layoutElementObj->metaKey = $optionId;
                    }
                    $layoutElementObj->setLayoutPosition($position);
                    $layoutElements[$position][] = $layoutElementObj;
                }
            }
        }
        
        $this->elementsEnabled[$this->objectIdentifier] = $layoutElements;
        
        return ($type && isset($layoutElements[$type])) ? $layoutElements[$type] : $layoutElements;
    }
    
    public function getTemplateElementsAvailable($separateBy='position') {
        if ($separateBy !== false) {
            if ($separateBy === 'position') {
                $elements = array('content' => array(), 'header' => array(), 'footer' => array(), 'all' => array());
            } else {
                $elements = array();
            }

            foreach ($this->elementsAvailable as $name => $element) {
                $key = $separateBy === 'position' ? $element->getLayoutPosition() : $element->group();
                $elements[$key][$name] = $element;
            }
        } else {
            $elements = $this->elementsAvailable;
        }

        return $elements;
    }
    
    /**
     * @return bool
     */
    public function isHeaderBackgroundTransparent() {
        $return = false;
        foreach ($this->getTemplateElementsEnabled('header') as $element) {
            if ($element->renderBehindContent()) {
                $return = true;
                break;
            }
        }
        
        return $return;
    }
}

?>