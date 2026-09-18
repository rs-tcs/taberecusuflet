<?php
class EmailsController extends AppController {
	
	public function sendContactUs() {
		App::import('Component', 'Mail');
		$MailComponent = new MailComponent($this->data);

		$MailComponent->setValidationFields(array(
			'senderName' => array(
				'empty' => array('msg' => __('Please enter your name', GUMM_THEME_PREFIX))
			),
			'from' => array(
				'empty' => array('msg' => __('Please enter your email address', GUMM_THEME_PREFIX)),
				'email' => array('msg' => __('Your email address must be in the format of name@domain.com', GUMM_THEME_PREFIX)),
			),
			'body' => array(
				'empty' => array('msg' => __('Please enter a message', GUMM_THEME_PREFIX)),
			),
		));
		$MailComponent->setSubject('[Contact Form] ' . get_bloginfo('name'));

		$invalidFields = array();
		$success = array();
		if ($MailComponent->send()) {
			$success['success'] = true;
			$success['message'] = __('Your email has been sent.', GUMM_THEME_PREFIX);
			// $successMessage = __('Your email has been sent.', GUMM_THEME_PREFIX);
		} else {
			$invalidFields = $MailComponent->getInvalidFields();
			$success['success'] = false;
			$success['message'] = '<p>' . implode('</p><p>', $invalidFields) . '</p>';
		}
		
		// d($this->RequestHandler->isAjax());
		if ($this->RequestHandler->isAjax()) {
			echo json_encode($success);
			die();
		}
	}
}
?>