<?php
class LayoutElementsController extends AppController {
    
    public function display($name=null, $settings=array(), $renderOptions=array()) {
        if (!$name) return false;
        $id = uniqid();
        if (isset($settings['id'])) {
            $id = $settings['id'];
            unset($settings['id']);
        }
        $settings = array(
            'settings' => $settings,
        );
        
        $this->autoRender = false;
        
        $name = Inflector::camelize($name);
        $className = $name . 'LayoutElement';
        
	    App::import('LayoutElement', $name);
	    
        if ($_settings = $this->RequestHandler->getNamed('settings')) {
            $settings = array('settings' => $_settings);
        }
        if ($_renderOptions = $this->RequestHandler->getNamed('renderOptions')) {
            $renderOptions = $_renderOptions;
        }
        
	    $Element = new $className($settings);
        $Element->id($id);
	    $Element->render($renderOptions);
    }
    
    public function admin_content_tab_add() {
        $optionId = $this->RequestHandler->getNamed('optionId');
        $modelName = $this->RequestHandler->getNamed('modelName');
        $inputSettings = (array) $this->RequestHandler->getNamed('inputSettings');
        
        
        $this->set(compact('optionId', 'modelName', 'inputSettings'));
    }
    
    public function admin_index_icons() {
        $activeIcon = $this->RequestHandler->getNamed('icon');
        App::uses('FontAwesome/FontAwesome', 'Vendor');
        $FontAwesome = new FontAwesome();
        $icons = $FontAwesome->getData();
        
        $this->set(compact('icons', 'activeIcon'));
    }
    
    public function latest_tweets() {
        App::uses('GummTwitterTweet', 'Vendor/Twitter');
        $Tweet = new GummTwitterTweet();
        $tweets = $Tweet->getLatest($this->RequestHandler->getNamed('twitterUsername'), $this->RequestHandler->getNamed('tweetsLimit'));
        
        $this->set(compact('tweets'));
    }
}
?>