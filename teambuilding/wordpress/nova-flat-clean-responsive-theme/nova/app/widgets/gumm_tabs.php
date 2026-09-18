<?php
class GummTabsWidget extends GummWidget {
	
	protected $customName = 'Tabs';

	protected $options = array(
		'description' => 'Display tabbed content',
	);
	
	protected $supports = array();
	
	protected function fields() {
        return array(
            'tabs' => array(
                'name' => '',
                'type' => 'content-tabs',
                'inputSettings' => array(
                    'fields' => array(
                        'post',
                        'textarea' => 'plain',
                    ),
                ),
            ),
        );
	}

    /**
     * @return void
     */
    public function render($fields) {
        request_action(array('controller' => 'layout_elements', 'action' => 'display', 'Tabs', $fields, array('featured' => false)));
    }
}	
?>