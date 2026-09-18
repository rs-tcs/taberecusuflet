<?php
class CookieComponent extends GummObject {
    
    /**
     * @param string $path
     * @param bool $forUser
     * @return void
     */
    public function read($path='', $forUser=true) {
        if (!$path) return;
        
        $path = $this->_getPath($path, $forUser);
        
        $__cookie = $this->_getGummCookie();

        return Set::classicExtract($__cookie, $path);
    }
    
    /**
     * @param string $path
     * @param mixed $data
     * @param bool $forUser
     * @return void
     */
    public function write($path='', $data, $forUser=true) {
        if (!$path) return;
        
        $path = $this->_getPath($path, $forUser);
        
        $__cookie = $this->_getGummCookie();
        
        if (strpos($path, '.') === false) {
            $__cookie[$path] = $data;
        } else {
            $__cookie = Set::insert($__cookie, $path, $data);
        }
        
        $this->_storeGummCookie($__cookie);
    }
    
    /**
     * @param array $__cookie
     * @return void
     */
    private function _storeGummCookie($__cookie) {
        $__cookie = http_build_query($__cookie);
        
        setcookie(GUMM_COOKIE, $__cookie, time() + 31536000, SITECOOKIEPATH );
        $_COOKIE[GUMM_COOKIE] = $__cookie;
    }
    
    /**
     * @return array
     */
    private function _getGummCookie() {
        $__cookie = array();
        if (isset($_COOKIE[GUMM_COOKIE])) {
            parse_str(urldecode((string) $_COOKIE[GUMM_COOKIE]), $__cookie);
        }

        return $__cookie;
    }
    
    /**
     * @param string $path
     * @param bool $forUser
     * @return string
     */
    private function _getPath($path, $forUser) {
        if ($forUser === true) {
            $user = wp_get_current_user();
            if (!$user) return;
            
            $path = 'user_settings_' . $user->ID . '.' . $path;
        }
        
        return $path;
    }
}
?>