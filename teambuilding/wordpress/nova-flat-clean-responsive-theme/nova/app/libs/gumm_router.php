<?php
class GummRouter extends GummObject {
    
    /**
     * @var bool
     */
    public $hookInitialized = false;
    
    /**
     * @var array
     */
    public $rules = array();
    
    /**
     * @var array
     */
    private $_requestParamsSchema = array(
        'action' => '',
        'controller' => '',
        'ajax' => '',
    );
    
    /**
     * Returns a singleton instance of the Configure class.
     *
     * @return Configure instance
     * @access public
     */
    public static function &getInstance() {
        static $instance = array();
        if (!$instance) {
		    $_inst = new GummRouter();
			$instance[0] =& $_inst;
        }
        return $instance[0];
    }
    
    /**
     * @param string $name
     * @param string $path
     * @param array $options
     * @return void
     */
    public static function connect($name, $path, array $options=array()) {
        $_this = self::getInstance();
        $_this->rules[$name] = array_merge(array(
            'path' => $path,
        ), $options);
        
        if (!$_this->hookInitialized) {
            add_action('init', array(&$_this, 'registerPermastructs'));
        }
    }
    
    /**
     * @param mixed $url
     * @return string
     */
    public static function url($url=null) {
        if ($url === null) {
            $http = env('HTTPS') ? 'https://' : 'https://';
            
            return $http . env('HTTP_HOST') . env('REQUEST_URI');
        }
        $routedUrl = $url;
        if (is_array($url)) {
            if (is_admin() || isset($url['admin']) && $url['admin'] === true) {
                if (isset($url['ajax']) && $url['ajax'] === true)
                    $routedUrl = admin_url('admin-ajax.php');
                else
                    $routedUrl = get_admin_url();
            } else {
                $routedUrl = get_site_url();
            }
            $routedUrl = trim($routedUrl, '/');
            
            $urlParams = array();
            if (isset($url['controller'])) $urlParams['gummcontroller'] = $url['controller'];
            if (isset($url['action'])) $urlParams['action'] = GUMM_FW_PREFIX . $url['action'];
            
            $requestParams = GummRouter::getUrlParams($url);
            foreach ($requestParams['params'] as $value) $urlParams['gummparams[]'] = $value;
            foreach ($requestParams['named'] as $param => $value) $urlParams["gummnamed[{$param}]"] = $value;
            
            $routedUrl .= ($urlParams) ? '?' . http_build_query($urlParams) : '';
        }
        
        return $routedUrl;
    }
    
    /**
     * @param mixed $url
     * @return array
     */
    public static function getUrlParams($url) {
        $params = array(
            'params' => array(),
            'named' => array(),
        );
        
        if (is_array($url)) {
            $urlParams = array_diff_key($url, array(
                'action' => '',
                'controller' => '',
                'ajax' => '',
                'admin' => '',
            ));

            foreach ($urlParams as $param => $value) {
                if (is_numeric($param)) $params['params'][] = $value;
                else $params['named'][$param] = $value;
            }
        }
        
        return $params;
    }
    
    // ======== //
    // WP HOOKS //
    // ======== //
    
    /**
     * @return void
     */
    public function registerPermastructs() {
        global $wp_rewrite;
        
        $_defaults = array(
            'path' => false,
            'withFront' => false,
            'reverseRewrite' => array(
                'filter' => false,
                'action' => false,
            ),
        );
        foreach ($this->rules as $name => $settings) {
            $settings = Set::merge($_defaults, $settings);
            extract($settings);
            if (!$path) continue;
            
            $wp_rewrite->add_permastruct($name, $path, $withFront);
            
            if ($reverseRewrite['filter']) {
                add_filter($reverseRewrite['filter'], array(&$this, 'reverseLinkLookup'));
            }
            if ($reverseRewrite['action']) {
                add_action($reverseRewrite['filter'], array(&$this, 'reverseLinkLookup'));
            }
        }
    }
    
    public function reverseLinkLookup() {
        
    }
    
    function categoryLinkHook($link) {
        if (is_page_template('template_portfolio.php') && strpos($link, '/portfolio_category/') !== false && get_option('permalink_structure')) {
            global $wp_query, $post;
            $parts = Set::filter(explode('/', $link));

            if ($identifierOffset = array_search('portfolio_category', $parts)) {
                $portfolioParts = array_splice($parts, $identifierOffset);
                array_pop($parts);

                array_unshift($portfolioParts, $wp_query->queried_object->post_name);
                array_unshift($portfolioParts, 'work');

                $parts = array_merge($parts, $portfolioParts);

                if ($parts[0] == 'http:') $parts[0] .= '/';

                $link = implode('/', $parts);
            }
        }

        return $link;
    }

}
?>