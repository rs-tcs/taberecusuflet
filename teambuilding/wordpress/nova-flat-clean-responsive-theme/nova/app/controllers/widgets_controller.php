<?php
class WidgetsController extends AppController {
	
	private $widgets;
	
	public function registerWidgets($widgets=null) {
		if (!$widgets) return;
		$this->widgets = $widgets;
		add_action('widgets_init', array(&$this, '_registerWidgets'));
	}
	
	public function _registerWidgets() {
		foreach ($this->widgets as $widget) {
			App::import('Widget', $widget);
			register_widget($widget);
		}
	}
}
?>