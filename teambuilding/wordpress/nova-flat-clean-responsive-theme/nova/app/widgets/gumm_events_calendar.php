<?php
class GummEventsCalendarWidget extends GummWidget {
	
	protected $customName = 'Events Calendar';

	protected $options = array(
		'classname' => 'gumm_events_calendar',
		'description' => 'Display your latest events.',
	);
	
	protected $fields = array(
	);
	
	protected function fields() {
	    return array();
	}
	
	public function render($fields) {
	    App::import('LayoutElement', 'Event');
	    
	    $EventsElement = new EventLayoutElement(array(
	       'settings' => array(
	           'layout' => 'calendar',
	       ),
	    ));
	    $EventsElement->render(array(
	        'headerStyle' => 'note',
            'wrapClass' => '',
	    ));
?>
<?php
	}

	public function form($instance) {
?>
        <p><?php _e('The calendar will be displayed starting with today\'s date', 'gummfw'); ?></p>
<?php
    }
	
}	
?>