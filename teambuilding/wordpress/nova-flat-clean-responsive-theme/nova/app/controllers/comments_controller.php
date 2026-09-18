<?php
class CommentsController extends AppController {
	
	private $loopCount = 0;
	
	public function view($comment, $args, $depth) {
		$loopCount = ++$this->loopCount;
		$GLOBALS['comment'] = $comment;
		$this->set(compact('comment', 'args', 'depth', 'loopCount'));
		
		$this->hasRendered = false;
		$this->render('view');
	}
}
?>