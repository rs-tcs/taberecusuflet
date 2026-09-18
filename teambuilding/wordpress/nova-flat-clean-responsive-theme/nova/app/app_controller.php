<?php
class AppController extends Controller {
	
    public $helpers = array('Wp', 'Html', 'Media', 'Form', 'Text', 'Number', 'Js');
	
	public function __construct() {
		parent::__construct();
	}
}
?>