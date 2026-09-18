<?php
class MailComponent extends GummObject {
	private $params = array(
		'from' => '',
		'to' => '',
		'subject' => '',
		'body' => '',
		'senderName' => '',
		'senderUrl' => '',
	);
	
	public $layout;
	
	protected $validates = array(
		'from' => array('empty','email'),
		'to' => array('empty','email'),
		'body' => 'empty'
	);
	
	private $invalidFields = array();
	
	public function __construct($params=array()) {
		parent::__construct();
		
		$this->sanitize($params);
	}
	
	public function send() {
		if (!$this->validate()) return false;
		
		extract($this->params, EXTR_OVERWRITE);
		
		$headers = $this->constructHeaders();
		$body = $this->constructBody();
		
		mail($to, $subject, $body, $headers);
		
		return true;
	}
	
	public function setSubject($subject) {
		$this->params['subject'] = $subject;
	}
	
	private function constructHeaders() {
		extract($this->params, EXTR_OVERWRITE);
		
		$headers = 'From:';
		if ($senderName) $headers .= ' ' . $senderName;
		$headers .= ' <' . $from . '>' . "\r\n" . 'Reply-to: ' . $from;
		
		return $headers;
	}
	
	private function constructBody() {
		extract($this->params, EXTR_OVERWRITE);
		
		return 'From: ' . $senderName . "\n\n" . 'Email: ' . $from . "\n\n" . 'Website: ' . $senderUrl . "\n\n" . 'Message: ' . $body;
		
		
	}
	
	public function setValidationFields($fields) {
		foreach ($fields as $field => $params) {
			if (is_string($params)) {
				$this->setValidationField($params);
			} else {
				$this->setValidationField($field, $params);
			}
		}
	}
	
	public function setValidationField($field, $params=array()) {
		$this->validates[$field] = $params;
	}
	
	public function getInvalidFields() {
		return $this->invalidFields;
	}
	
	private function validate() {
		foreach ($this->validates as $field => $rules) {
			if (is_array($rules)) {
				foreach ($rules as $rule => $settings) {
					if (is_string($settings)) {
						$this->validateField($field, $settings);
					} else {
						$this->validateField($field, $rule, $settings);
					}
				}
			} else {
				$this->validateField($field, $rules);
			}
		}
		return empty($this->invalidFields);
	}
	
	private function validateField($field, $rule, $settings=array()) {
		if (!isset($this->params[$field])) {
			$this->invalidFields[$field] = 'Field "'.$field.'" is not supported.';
			return;
		}
		$value = trim($this->params[$field]);
		extract($settings, EXTR_SKIP);
		$valid = true;
		switch ($rule) {
		 case 'empty':
			if (empty($value) || $value == '' || $value == '0') {
				$msg = (isset($msg)) ? $msg : __('Field cannot be empty', 'gummfw');
				$this->invalidFields[$field][] = $msg;
			}
			break;
		 case 'email':
		    if (!is_email($value)) {
				$msg = (isset($msg)) ? $msg : __('Not a valid email', 'gummfw');
				$this->invalidFields[$field][] = $msg;
			}
			break;
		}
	}
	
	private function sanitize($params) {
		if (!isset($params['to'])) {
			$mailTo = GummRegistry::get('Helper', 'Wp')->getOption('email');
			$params['to'] = ($mailTo) ? $mailTo : get_option('admin_email');
		}
		foreach ($params as &$param) {
		    wp_filter_nohtml_kses(trim($param));
		}
		$this->params = array_merge($this->params, $params);
	}
}
?>