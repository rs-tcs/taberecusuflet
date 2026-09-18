<?php
class TaxonomiesController extends AppController {

	public function __construct() {
		parent::__construct();
		add_action('init', array(&$this, 'registerCustomTaxonomies'), 0);
	}
	
	public function registerCustomTaxonomies() {
		$customTaxonomies = Configure::read('customTaxonomies');
		if (isset($customTaxonomies['args'])) $customPostTypes = array($customPostTypes);
		
		foreach ($customTaxonomies as $taxonomy => $taxonomyArgs) {
			if (function_exists('register_taxonomy')) {
                register_taxonomy($taxonomy, $taxonomyArgs['objectType'], $taxonomyArgs['args']);
			}
		}
		
		$customPostTypes = Configure::read('customPostTypes');
		
		foreach($customPostTypes as $postType => $args) {
		    $categoryCandidate = strtolower($postType) . '_category';
		    if (isset($customTaxonomies[$categoryCandidate])) {
                continue;
		    }
            if (isset($args['args']['taxonomies']) && in_array($categoryCandidate, $args['args']['taxonomies'])) {
    			if (function_exists('register_taxonomy')) {
    			    $objectName = ucwords($postType);
    			    $taxonomyArgs = array(
                        'hierarchical' => true,
                        'labels' => array(
                            'name' => $objectName . _x(' Categories', 'taxonomy general name', 'gummfw'),
                            'singular_name' => $objectName . _x(' Category', 'taxonomy singular name', 'gummfw'),
                            'search_items' =>  __('Search Categories', 'gummfw'),
                            'all_items' => sprintf(__('All %s Categories', 'gummfw'), $objectName),
                            'parent_item' => sprintf(__('Parent %s Category', 'gummfw'), $objectName),
                            'parent_item_colon' => sprintf(__('Parent %s Category:', 'gummfw'), $objectName),
                            'edit_item' => sprintf(__('Edit %s Category', 'gummfw'), $objectName),
                            'update_item' => sprintf(__('Update %s Category', 'gummfw'), $objectName),
                            'add_new_item' => sprintf(__('Add New %s Category', 'gummfw'), $objectName),
                            'new_item_name' => sprintf(__('New %s Category Name', 'gummfw'), $objectName),
                        ),
                        'show_ui' => true,
                        'query_var' => true,
    			    );
                    register_taxonomy($categoryCandidate, array($postType), $taxonomyArgs);
    			}
            }
		}
	}
}
?>