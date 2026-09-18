<?php
class ContactFormLayoutElement extends GummLayoutElement {
    /**
     * @var string
     */
    protected $id = 'C17A6023-9942-4E3C-884A-2AB85D6AE6B1';
    
    /**
     * @var string
     */
    public $group = 'contact';
    
    /**
     * @var array
     */
    protected $supports = array('title');
    
    /**
     * @return string
     */
    public function title() {
        return __('Contact Form', 'gummfw');
    }
    
    /**
     * @return array
     */
    protected function _fields() {
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
    protected function _render($options) {
        $id = $this->id();
    	$invalidFields = array();
    	$mailSent = false;
    	
        $labels = array(
            'senderName' => __('Name *', 'gummfw'),
            'from' => __('Email *', 'gummfw'),
            'body' => __('Message *', 'gummfw'),
        );
        if (isset($_POST['contactSendMail']) && isset($_POST['contact_form_id']) && $_POST['contact_form_id'] == $id) {
            $_POST = array_diff_assoc($_POST, $labels);
            
        	App::import('Component', 'Mail');
        	$params = array_merge($_POST, array(
        	   'to' => $this->getParam('contactEmail'),
        	));
        	$MailComponent = new MailComponent($params);

        	$MailComponent->setValidationFields(array(
        		'senderName' => array(
        			'empty' => array('msg' => __('Please enter your name', 'gummfw'))
        		),
        		'from' => array(
        			'empty' => array('msg' => __('Please enter your email address', 'gummfw')),
        			'email' => array('msg' => __('Your email address must be in the format of name@domain.com', 'gummfw')),
        		),
        		'body' => array(
        			'empty' => array('msg' => __('Please enter a message', 'gummfw')),
        		),
        	));
        	$MailComponent->setSubject('[Contact Form] ' . get_bloginfo('name'));

        	if ($MailComponent->send()) {
        	    $mailSent = true;
        		$successMessage = __('Your email has been sent.', 'gummfw');
        	} else {
        		$invalidFields = $MailComponent->getInvalidFields();
        	}
        }
        
        $fieldsData = array(
            'senderName' => (isset($_POST['senderName']) && !$mailSent) ? $_POST['senderName'] : '',
            'from' => (isset($_POST['from']) && !$mailSent) ? $_POST['from'] : '',
            'body' => (isset($_POST['body']) && !$mailSent) ? $_POST['body'] : '',
        );
        
        
        
        $displayLabels = true;
        $formClass = array('gumm-contact-form');
        $senderInputClass = array('required');
        $fromInputClass = array('required');
        $bodyInputClass = array('required');
        if (in_array($this->getParam('layout'), array('one', 'three'))) {
            $senderInputClass[] = 'labeled-input';
            $fromInputClass[] = 'labeled-input';
            $bodyInputClass[] = 'labeled-input';
            
            $displayLabels = false;
            $formClass[] = 'form-discrete-labels';
            
            if (!$fieldsData['senderName']) {
                $fieldsData['senderName'] = $labels['senderName'];
                $senderInputClass[] = 'default-label-on';
            }
            if (!$fieldsData['from']) {
                $fieldsData['from'] = $labels['from'];
                $fromInputClass[] = 'default-label-on';
            }
            if (!$fieldsData['body']) {
                $fieldsData['body'] = $labels['body'];
                $bodyInputClass[] = 'default-label-on';
            }
        }
        if (isset($invalidFields['senderName'])) $senderInputClass[] = 'form-error';
        if (isset($invalidFields['from'])) $fromInputClass[] = 'form-error';
        if (isset($invalidFields['body'])) $bodyInputClass[] = 'form-error';
        
        $divAtts = array(
            'id' => 'contact-form-' . $id,
            'class' => 'bluebox-contact type-' . $this->getParam('layout'),
        );
?>
        <div<?php echo $this->Html->_constructTagAttributes($divAtts); ?>>
        	<?php if(isset($successMessage)): ?>
        	<div class="msg success email-sent-msg centered-alert-message">
        		<a class="close" href="#">×</a>
        		<p><?php echo $successMessage; ?></p>
        	</div>
        	<?php endif; ?>
    
            <form action="<?php the_permalink(); ?>" method="post" class="<?php echo implode(' ', $formClass); ?>">
                <input name="contact_form_id" value="<?php echo $id; ?>" type="hidden" />
                <input name="contactSendMail" value="1" type="hidden" />
                <div class="contact-form-inputs">
                    <div class="input-wrap-text input-name">
                        <?php if ($displayLabels): ?>
                        <label for="gumm-contact-name-<?php echo $id; ?>"><?php _e('Name', 'gummfw')?> *</label>
                        <?php endif; ?>
                        <input type="text" id="gumm-contact-name-<?php echo $id; ?>" class="<?php echo implode(' ', $senderInputClass); ?>" name="senderName" value="<?php echo $fieldsData['senderName']; ?>" data-default-label="<?php echo $labels['senderName']; ?>" />
            
            			<?php if (isset($invalidFields['senderName'])): ?>
            				<p class="error"><?php echo implode('<br />', $invalidFields['senderName']); ?></p>
            			<?php endif;?>                    
			
                    </div>
        
                    <div class="input-wrap-text input-email">
                        <?php if ($displayLabels): ?>
                        <label for="gumm-contact-email-<?php echo $id; ?>"><?php _e('Email', 'gummfw')?> *</label>
                        <?php endif; ?>
                        <input type="text" id="gumm-contact-email-<?php echo $id; ?>" class="<?php echo implode(' ', $fromInputClass); ?>" name="from" value="<?php echo $fieldsData['from']; ?>" data-default-label="<?php echo $labels['from']; ?>" />
            
            			<?php if (isset($invalidFields['from'])): ?>
            				<p class="error"><?php echo implode('<br />', $invalidFields['from']); ?></p>
            			<?php endif;?>
                    </div>
        
                    <div class="input-wrap-textarea input-message">
                        <?php if ($displayLabels): ?>
                        <label for="gumm-contact-message-<?php echo $id; ?>"><?php _e('Message', 'gummfw')?> *</label>
                        <?php endif; ?>
                        <textarea id="gumm-contact-message-<?php echo $id; ?>" class="<?php echo implode(' ', $bodyInputClass); ?>" name="body" data-default-label="<?php echo $labels['body']; ?>" rows="6"><?php echo $fieldsData['body']; ?></textarea>
            
            			<?php if (isset($invalidFields['body'])): ?>
            				<p class="error"><?php echo implode('<br />', $invalidFields['body']); ?></p>
            			<?php endif;?>
                    </div>
                </div>
        
                <div class="input-wrap-submit input-submit">
                    <input type="submit" class="gumm-contact-submit" data-title="<?php _e('Submit', 'gummfw'); ?>" data-action-title="<?php _e('Sending...', 'gummfw'); ?>" value="<?php _e('Submit', 'gummfw'); ?>" />
                </div>
        
            </form>
        </div>
<?php
    }
}
?>