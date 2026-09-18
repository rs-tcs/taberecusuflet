<?php
class GummTwitterWidget extends GummWidget {
	
	protected $customName = 'Tweets';

	protected $options = array(
		'description' => 'Display latest tweets',
	);
	
	protected $supports = array('title');
	
	protected function fields() {
        return array(
            'twitterUsername' => array(
                'name' => __('Twitter username', 'gummfw'),
                'type' => 'text',
                'value' => $this->Wp->getOption('social.twitter.username'),
            ),
            'tweetsNumber' => array(
                'name' => __('Number of tweets to display', 'gummfw'),
                'type' => 'number',
                'value' => 5,
                'inputSettings' => array(
                    'slider' => array(
                        'min' => 1,
                        'max' => 10,
                        'numberType' => ''
                    ),
                ),
            ),
        );
	}

    /**
     * @return void
     */
    public function render($fields) {
        request_action(array('controller' => 'layout_elements', 'action' => 'display', 'TwitterTweets', $fields));
    }
}	
?>