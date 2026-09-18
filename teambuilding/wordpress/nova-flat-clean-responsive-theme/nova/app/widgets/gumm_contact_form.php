<?php
class GummContactFormWidget extends GummWidget {
	
	protected $customName = 'Contact Form';

	protected $options = array(
		'description' => 'Display contact form in your sidebar',
	);
	
	protected $extens = 'ContactForm';
	
	protected $supports = array('title');
	
	protected function fields() {
        return array(
            'contactEmail' => array(
                'name' => __('Email address this form will send to', 'gummfw'),
                'type' => 'text',
                'value' => $this->Wp->getOption('email'),
            ),
            'layout' => array(
                'name' => __('Form layout', 'gummfw'),
                'type' => 'select',
                'inputOptions' => array(
                    'one' => __('Light, labels inside inputs', 'gummfw'),
                    'two' => __('Light, labels before inputs', 'gummfw'),
                    'three' => __('Plain, labels inside inputs', 'gummfw'),
                    'four' => __('Plain, labels before inputs', 'gummfw'),
                ),
                'value' => 'one',
            ),
        );
	}

    /**
     * @return void
     */
    public function render($fields) {
        request_action(array('controller' => 'layout_elements', 'action' => 'display', 'ContactForm', $fields));
    }
}	
?>