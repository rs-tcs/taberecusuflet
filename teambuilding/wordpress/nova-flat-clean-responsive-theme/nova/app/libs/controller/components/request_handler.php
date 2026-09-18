<?php
class RequestHandlerComponent {
	
    /**
     * Returns true if the current HTTP request is Ajax, false otherwise
     *
     * @return boolean True if call is Ajax
     * @access public
     */
	public function isAjax() {
        return (isset($_REQUEST['X_GUMM_REQUESTED_WITH']) && $_REQUEST['X_GUMM_REQUESTED_WITH'] === 'XMLHttpRequest') || ($this->isAdminAjax());
	}
	
	/**
	 * @return bool
	 */
	public function isAdminAjax() {
	    return strpos(admin_url('admin-ajax.php'), env('SCRIPT_NAME')) !== false;
	}
	
	/**
	 * @return string
	 */
	public function getAction() {
		return (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
	}
	
	/**
	 * @return string
	 */
	public function getController() {
	    return (isset($_REQUEST['gummcontroller'])) ? $_REQUEST['gummcontroller'] : '';
	}
	
	/**
	 * @return array
	 */
	public function getRequestParams() {
	    return (isset($_REQUEST['gummparams'])) ? $_REQUEST['gummparams'] : array();
	}
	
	/**
	 * @param string $param
	 * @return mixed
	 */
	public function getNamed($param) {
	    $named = false;
	    if (isset($_REQUEST['gummnamed']) && isset($_REQUEST['gummnamed'][$param])) $named = $_REQUEST['gummnamed'][$param];
	        
        return $named;
	}
	
	/**
	 * @return false | string
	 */
	public function getPage() {
	    return (isset($_REQUEST['page'])) ? $_REQUEST['page'] : false;
	}
}
?>