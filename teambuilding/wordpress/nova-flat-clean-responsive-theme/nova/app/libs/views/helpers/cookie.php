<?php
if (!class_exists('CookieComponent')) App::import('Component', 'Cookie');

class CookieHelper extends CookieComponent {
    
    public function write($path, $data, $forUser = true) {
        trigger_error(__('Cannot call write method from views', 'gummfw'));
    }
}
?>