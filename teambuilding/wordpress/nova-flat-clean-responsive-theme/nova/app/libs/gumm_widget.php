<?php
abstract class GummWidget extends WP_Widget {
    abstract public function render($instance);
    abstract protected function fields();
    
    /**
     * @var boolean
     */
    protected $supports = array(
        'title',
        'pagination' => false,
    );
    
    /**
     * @var array
     */
    public $helpers = array(
        'Html',
        'Form',
        'Text',
        'Layout',
        'Media',
        'Wp',
    );
    
    protected $fields = array();
    
    protected $posts = array();
    
    protected $queryPostType = 'post';
    
    protected $resetQueryAfterRender = false;
    
    /**
     * @var string
     */
    protected $widgetBodyClass = '';
	
    public function __construct() {
        $this->options['classname'] = Inflector::slug(Inflector::underscore(get_class($this)), '-');
        $this->WP_Widget($this->options['classname'], GUMM_THEME . ' ' . $this->customName, $this->options);
		
		foreach ($this->helpers as $helper) {
		    $helper = Inflector::camelize($helper);
		    $this->$helper = GummRegistry::get('Helper', $helper);
		}
		
		if ($fields = $this->_fields()) {
		    foreach ($fields as $k => $v) {
                $val = isset($v['value']) ? $v['value'] : null;
		        $this->fields[$k] = $val;
		    }
		}
    }
    
    public function widget($args, $instance) {
        $this->beforeRender($instance);
		extract($args);
		extract($this->getInstanceFieldsData($instance), EXTR_OVERWRITE);

		// Before widget (defined by theme functions file)
		echo $before_widget;

		// Display the widget title if one was input
		if (isset($title) && $title) {
		    if ($this->supports('pagination')) {
		        $this->displayPaginationTitle($title);
		    } else {
                echo $before_title . $title . $after_title;
		    }
            
		}
		
		$widgetBodyClass = Set::filter(array(
		    'widget-body',
		    $this->options['classname'],
		    $this->widgetBodyClass,
		));
		if ($this->supports('pagination')) $widgetBodyClass[] = 'paged-widget';
		
		echo '<div class="' . implode(' ', $widgetBodyClass) . '">';
		    $instanceFieldsData = $this->getInstanceFieldsData($instance);
		    $instanceFieldsData['id'] = $this->id;
    		$this->render($instanceFieldsData);
		echo '</div>';
		
		// After widget (defined by theme functions file)
		echo $after_widget;
		
		if ($this->resetQueryAfterRender) {
		    wp_reset_query();
		    $this->posts = array();
		}
		$this->afterRender();
    }
    
    public function form($instance) {
        $fieldsData = $this->getInstanceFieldsData($instance);
        $fields = $this->_fields();
        
        foreach ($fields as $k => $field) {
            $field['id'] = $this->get_field_name($k);
            $_settings = array_merge(array(
                'before' => '',
                'after' => '',
                'inputAttributes' => array(
                    'name' => $this->get_field_name($k),
                    'value' => $fieldsData[$k],
                ),
                'inputSettings' => array(),
            ), $field);
            $inputAttributes = $_settings['inputAttributes'];
            $inputSettings = $_settings['inputSettings'];
            
            echo '<div class="row-fluid bluebox-admin">';
            echo $_settings['before'];
            echo $this->Form->input('', $field, $inputAttributes, $inputSettings);
            echo $_settings['after'];
            echo '</div>';
        }
    }
    
    private function _fields() {
        $fields = $this->fields();
        if ($this->supports('postType')) $fields = array_merge($this->_postTypeFields(), $fields);        
        if ($this->supports('title')) $fields = array_merge($this->_titleFields(), $fields);
    
        return $fields;
    }
    
    protected function _titleFields() {
        $val = (isset($this->supports['title'])) ? $this->supports['title'] : '';
        return array(
            'title' => array(
                'name' => __('Title', 'gummfw'),
                'type' => 'text',
                'value' => $val,
            ),
        );
    }
    
    protected function _postTypeFields() {
        $val = (isset($this->supports['postType'])) ? $this->supports['postType'] : '';
        return array(
            'post_type' => array(
                'name' => '',
                'type' => 'post-type',
                'value' => $val,
            ),
        );
    }
    
    protected function displayPaginationTitle($title) {
?>
        <div class="heading-wrap paged-heading-wrap">
    		<h3><span></span><?php echo $title; ?></h3>
            
            <!-- arrows -->
            <div class="nav-arrows">
            	<a href="#" class="prev"></a>
                <a href="#" class="next"></a>
            </div>
            
    	</div>
<?php
    }
    
	public function update($new_instance, $old_instance) {
		$instance =  array_merge($old_instance, $new_instance);
		
        foreach ($instance as $key => &$value) {
            if ($key == 'title' || $key == 'flickrId') {
                $value = strip_tags($value);
            } elseif (is_array($value)) {
                $value = serialize($value);
            } else {
                $value = stripslashes($value);
            }
        }
		return $instance;
	}

	protected function getInstanceFieldsData($instance) {
	    $_arrayedData = array();
		foreach ($instance as $field => $value) {
		    if (preg_match_all("'^([a-z]+)\.(\d)\.([a-z]+)$'iU", $field, $match)) {
		        $l = $match[1][0];
		        $i = (int) $match[2][0];
		        $f = $match[3][0];
		        if (!isset($_arrayedData[$l])) $_arrayedData[$l] = array();
		        if (!isset($_arrayedData[$l][$i])) $_arrayedData[$l][$i] = array();

                $_arrayedData[$l][$i][$f] = $value;
		    } else {
		        $unserializedData = @unserialize($value);
		        if ($unserializedData !== false) {
                    $this->fields[$field] = $unserializedData;
		        } else {
                    $this->fields[$field] = $value;
		        }
		    }
		}
		
		if ($_arrayedData) {
		    foreach ($_arrayedData as $field => $_data) {
		        if (isset($this->fields[$field])) {
		            $this->fields[$field] = $_data;
		        }
		    }
		}
		$this->fields = $this->afterGetFieldsInstanceData($this->fields);
		
		return $this->fields;
	}
	
    /**
     * @param mixed $type
     * @return bool
     */
    protected function supports($type) {
        $supports = true;
        foreach ((array) $type as $typeToCheck) {
            if (!array_key_exists($typeToCheck, (array) $this->supports) && !in_array($typeToCheck, (array) $this->supports)) {
                $supports = false;
            } elseif (array_key_exists($typeToCheck, (array) $this->supports) && !$this->supports[$typeToCheck]) {
                $supports = false;
            }
        }
        
        return $supports;
    }
    
    /**
     * @param string $param
     * @return string
     */
    protected function getParam($param) {
        $value = '';
        if (isset($this->fields[$param])) {
            $value = $this->fields[$param];
        } else {
            $value = Set::classicExtract($this->fields(), $param . '.value');
        }

        return $value;
    }
    
    protected function queryPosts($instanceData=array()) {
        if (!$this->supports('postType')) return;
        
        $this->resetQueryAfterRender = true;

        if ($this->posts) return $this->posts;
        
        $postType = GummHash::get($instanceData, 'post_type.post_type');
        if (!$postType) $postType = $this->queryPostType;
        $postsNumber = (int) GummHash::get($instanceData, 'post_type.posts_number');
        if (!$postsNumber) $postsNumber = 4;

        $args = array(
            'post_type' => $postType,
            'posts_per_page' => $postsNumber,
        );
        
        $termIds = Set::filter(Set::booleanize(GummHash::get($instanceData, 'post_type.' . $postType . '-category')));
        if ($termIds) {
            $termName = ($postType == 'post') ? 'category' : $postType .'_category';
            $args['tax_query'] = array(
                array(
                    'taxonomy' => $termName,
                    'field' => 'term_id',
                    'terms' => (array) $termIds
                ),
            );
        }
        $this->posts = query_posts($args);
        
        return $this->posts;
    }
    
    // ========= //
    // CALLBACKS //
    // ========= //
    
    protected function afterGetFieldsInstanceData($fields){ return $fields; }
    
    protected function beforeRender($instance) {
        if ($this->supports('postType')) $this->posts = $this->queryPosts($this->getInstanceFieldsData($instance));
        return true;
    }
    
    protected function afterRender() {
    }
    
}
?>