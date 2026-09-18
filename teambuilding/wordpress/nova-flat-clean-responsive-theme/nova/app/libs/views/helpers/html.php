<?php
class HtmlHelper extends GummHelper {
    
    /**
     * @var array
     */
    public $helpers = array('Wp', 'Number');
    
    /**
     * @var array
     */
    public $wpActions = array(
        'admin_print_footer_scripts' => array('_actionGummSwitchJs'),
        'init' => array('_actionAddEditorStyle'),
    );
    
    public $wpFilters = array(
        'wp_nav_menu_items' => array(
            'args' => 2,
            'priority' => 10,
            'func' => '_filterWpNavMenuItems',
        ),
        'gumm_menu_list_pages' => '_filterWpNavMenuPages',
    );
    
    /**
     * @var array
     */
    private $_gummSwitchJsSettings = array();
    
    public function registerStyles() {
        if (is_admin()) {
            add_action('admin_print_styles', array(&$this, 'registerAdminStyles'));
        } else {
            add_action('wp_print_styles', array(&$this, 'registerPublicStyles'));
        }
    }
    
    public function registerPublicStyles() {
        $publicCss = Configure::read('css.public');
        if ($publicCss) {
            foreach ($publicCss as $styleData) {
                $this->registerAndEnqueueStyle($styleData);
            }
        }
        
        if ($skinCssUrl = GummRegistry::get('Model', 'Skin')->getActiveSkinCssUrl()) {
            $skinCssData = array(
                'url' => array(
                    'skin' => $skinCssUrl,
                ),
            );
            $this->registerAndEnqueueStyle($skinCssData);
        }
        if ($externalFontFamilies = $this->getExternalFontFamilies()) {
            $this->registerAndEnqueueStyle(array('url' => $externalFontFamilies));
        }
        wp_add_inline_style('mainStyle', $this->getInlineCssForLayout());
    }
    
    public function registerAdminStyles() {
        $adminCss = Configure::read('css.admin');
        if ($adminCss) {
            foreach ($adminCss as $styleData) {
                $this->registerAndEnqueueStyle($styleData);
            }
        }
    }
    
    public function registerAndEnqueueStyle($styleData) {
        if (isset($styleData['template'])) {
            if (!is_page_template($styleData['template'])) return;
        } elseif (isset($styleData['page'])) {
            // if (get_query_var('page') != $styleData['page']) return;
        }
        foreach ($styleData['url'] as $name => $url) {
            wp_register_style($name, $url);
            wp_enqueue_style($name);
        }
    }
    
    public function getInlineCssForLayout() {
        $skinCustomStyle = GummRegistry::get('Model', 'Skin')->getActiveSkin('inlineCss');
        $styles = $this->getStylesForLayout('output', 'all');

        $inlineCss = '';
        if ($styles) {
            foreach ($styles as $declaration => $props) {
                $inlineCss .= $declaration . ' {' . "\n";
                foreach ($props as $prop => $propValue) {
                    $inlineCss .= "\t" . $prop . ': ' . $propValue . ';' . "\n";
                }
                $inlineCss .= '}' . "\n";
            }
        }
        if ($skinCustomStyle) {
            $inlineCss .= $skinCustomStyle;
        }

        return $inlineCss;
    }
    
    public function link($title, $url, $htmlAttributes=array(), $options=array()) {
        $htmlAttributes = array_merge(array(
            'class' => ''
        ), $htmlAttributes);
        
        $options = array_merge(array(
            'popup' => false,
            'confirm' => false,
            'ajax' => false,
        ), $options);
        
        if (is_array($url) && $options['popup'] == 'ajax' || $options['ajax'] === true) $url['ajax'] = true;
        
        $href = GummRouter::url($url);
        
        if ($options['popup']) {
            if ($options['popup'] == 'ajax') $htmlAttributes['class'] .= ' gumm-open-ajax-popup';
        }

        $htmlAttributes = array_merge(array(
            'href' => $href,
            'onclick' => ($options['confirm']) ? 'return confirm(\'' . $options['confirm'] . '\');' : false,
        ), $htmlAttributes);
        
        return '<a' . $this->_constructTagAttributes($htmlAttributes) . '>' . $title . '</a>';
    }
    
    public function getStyleSheetUrlForBackground($backgroundFile) {
        $pi = pathinfo($backgroundFile);
        
        if ($pi['filename'] == 'bg-tile') return false;
        
        $cssFile = $pi['filename'] . '.css';
        $cssUrl = GUMM_THEME_URL . '/css/' . $cssFile;
        
        return $cssUrl;
    }
    
    public function getBackgroundFileUrl() {
        return GUMM_THEME_URL . '/images/bg-html/' . $this->Wp->getOption('template_background');
    }
    
    public function css($filename, $base=false) {
        $styleSheetUrl = GUMM_THEME_CSS_URL;
        $versionParam = '?ver=' . $this->Wp->getThemeVersion();
        if ($base === true) {
            $styleSheetUrl = GUMM_THEME_URL . '/';
        } elseif (strpos($filename, '/') === 0) {
            $styleSheetUrl = GUMM_THEME_ASSETS_URL;
        } elseif (strpos($filename, 'http') !== false) {
            $styleSheetUrl = ''; $versionParam = '';
        }
        
        $filename = trim($filename, '/');
        
        return '<link rel="stylesheet" href="' . $styleSheetUrl . $filename .  $versionParam  . '" type="text/css" media="all" />';
    }
    
    /**
     * @return void
     */
    public function loadSkinStyles() {
        
    }
    
    /**
     * @return array
     */
    public function getExternalFontFamilies() {
        $result = array();
        
        $fontStyles = array_values((array) $this->Wp->getOption('styles.font_options'));
        if ($fontFamilies = Set::filter(Set::classicExtract($fontStyles, '{n}.font-family'))) {
            foreach ($fontFamilies = array_unique($fontFamilies) as $fontFamily) {
                if (GummRegistry::get('Component', 'Fonts')->getFontsVendor($fontFamily) == 'google') {
                    $result[Inflector::underscore($fontFamily) . '_vendor_font_family'] = is_ssl() ? 'https' : 'http' . '://fonts.googleapis.com/css?family=' . urlencode($fontFamily);
                }
            }
        }
        
        return $result;
    }
    
    /**
     * @return void
     */
    public function loadExternalFontFamilies() {
        $fontFamilies = $this->getExternalFontFamilies();
        foreach ($fontFamilies as $ff) {
            echo $this->css($ff);
        }
    }
    
    public function getFontsForLayout() {
        $fontStyles = $this->mapGroupSelectorsToCssSelectors((array) $this->Wp->getOption('styles.font_options'));
        
        return $fontStyles;
    }
    
    public function getBackgroundsForLayout() {
        global $post;
        $backgroundStyles = $this->mapGroupSelectorsToCssSelectors((array) $this->Wp->getOption('styles.background_options'));
        if ($post && is_a($post, 'WP_Post')) {
            $postBackgroundStyles = GummRegistry::get('Model', 'PostMeta')->find($post->ID, 'styles.background_options');
            if ($postBackgroundStyles && is_array($postBackgroundStyles)) {
                foreach ($postBackgroundStyles as $selector => &$decl) {
                    if (isset($decl['background-image'])) {
                        foreach ($decl['background-image'] as $k => $vals) {
                            if (!isset($vals['background-image'])) unset($decl['background-image'][$k]);
                            elseif (!$vals['background-image']) unset ($decl['background-image'][$k]);
                        }
                    }
                }
                $postBackgroundStyles = Set::filter($postBackgroundStyles);
                if ($postBackgroundStyles) {
                    $postBackgroundStyles = $this->mapGroupSelectorsToCssSelectors($postBackgroundStyles);
                    $backgroundStyles = Set::merge($backgroundStyles, $postBackgroundStyles);
                }
            }
            

        }
        
        return $backgroundStyles;
    }
    
    public function mapGroupSelectorsToCssSelectors($styles=array()) {
        $result = array();
        $styles = Set::filter($styles);
        
        if ($styles) {
            foreach ($styles as $selector => $params) {
                if ($groupSelector = Configure::read('styleGroups.' . $selector)) {
                    foreach ((array) $groupSelector as $_selector) {
                        if (!isset($result[$_selector])) $result[$_selector] = $params;
                        else $result[$_selector] = array_merge($_result[$_selector], $params);
                    }
                } else {
                    $result[$selector] = $params;
                }
            }
        }
        
        return $result;
    } 
    
    /**
     * @return array
     */
    public function getStylesForLayout($format='output', $type="color") {
        if(!$colors = $this->Wp->getOption('styles.color_options')) {
            return array();
        }
        
        if (isset($_COOKIE['__gumm_user_preview_skin']) && $_COOKIE['__gumm_user_preview_skin'] == Configure::read('Skin.customUserSkinId')) {
            foreach ($colors as $styleName => &$styleVal) {
                if (isset($_COOKIE['__gumm_user_preview_color_selection']) && isset($_COOKIE['__gumm_user_preview_color_selection'][$styleName]))
                    $styleVal = $_COOKIE['__gumm_user_preview_color_selection'][$styleName];
            }
        }
        
        $colorMap = array();
        foreach ($colors as $name => $val) {
            $colorMap[$name] = array(
                'hex' => $val,
                'rgb' => implode(', ', $this->Number->hex2rgb($val)),
            );
        }
        
        $results = array();
        $items = array();
        $styleDeclarations = Configure::read('dynamicStylesOptionsMap');
        foreach ($styleDeclarations as $styleName => $declarations) {
            if (!isset($colorMap[$styleName])) continue;
            foreach ($declarations as $styleProp => $declaration) {
                if (isset($declaration['selectors']) && isset($declaration['props'])) {
                    $props = array();
                    foreach ($declaration['props'] as $prop => $propParams) {
                        $props[$prop] = $this->constructColorValueFromParam($colorMap[$styleName]['rgb'], $propParams, $format, $styleName);
                    }
                    foreach ($declaration['selectors'] as $selector) {
                        if (!isset($items[$selector])) $items[$selector] = array();
                        $items[$selector] = array_merge($items[$selector], $props);
                    }
                } elseif (is_array($declarations)) {
                    foreach ($declaration as $selectorCandidate => $selector) {
                        $propValue = $this->constructColorValueFromParam($colorMap[$styleName]['rgb'], null, $format, $styleName);
                        if (is_array($selector)) {
                            if (isset($selector['selectors']) && isset($selector['params'])) {
                                $selectors = $selector['selectors'];
                                $propValue = $this->constructColorValueFromParam($colorMap[$styleName]['rgb'], $selector['params'], $format, $styleName);
                            } else {
                                $selectors = array($selectorCandidate);
                                $propValue = $this->constructColorValueFromParam($colorMap[$styleName]['rgb'], $selector, $format, $styleName);
                            }
                        } else {
                            $selectors = array($selector);
                        }
                        foreach ($selectors as $_selector) {
                            if (!isset($items[$_selector])) $items[$_selector] = array();
                            $items[$_selector] = array_merge($items[$_selector], array($styleProp => $propValue));
                        }
                    }
                }
            }
        }
        
        if ($type == 'all') {
            if ($fonts = $this->getFontsForLayout()) {
                foreach ($fonts as $selector => $fontSettings) {
                    if (!isset($items[$selector])) $items[$selector] = array();
                    $items[$selector] = array_merge($items[$selector], array('font-family' => $fontSettings['font-family']));
                }
            }
            if ($backgrounds = $this->getBackgroundsForLayout()) {
                foreach ($backgrounds as $selector => $bgSettings) {
                    if (!isset($items[$selector])) $items[$selector] = array();

                    $props = array();
                    if (isset($bgSettings['background-color']) && $bgSettings['background-color']) {
                        $props['background-color'] = '#' . $bgSettings['background-color'];
                    }
                    if (isset($bgSettings['background-image'])) {
                        $imgProps = array(
                            'background-image' => array(),
                            'background-repeat' => array(),
                            'background-position' => array(),
                        );
                        foreach ($bgSettings['background-image'] as $bgImageSettings) {
                            if (!$bgImageSettings['background-image']) continue;
                            
                            $imgProps['background-image'][] = "url('" . $bgImageSettings['background-image'] . "')";
                            $imgProps['background-repeat'][] = $bgImageSettings['background-repeat'];
                            $imgProps['background-position'][] = $bgImageSettings['background-position-left'] . ' ' . $bgImageSettings['background-position-top'];
                        }
                        $props['background-image'] = implode(', ', $imgProps['background-image']);
                        $props['background-repeat'] = implode(', ', $imgProps['background-repeat']);
                        $props['background-position'] = implode(', ', $imgProps['background-position']);
                        
                        $props = Set::filter($props);
                    }
                    
                    $items[$selector] = array_merge($items[$selector], $props);
                }
            }
            
            if ($wrapWidth = GummRegistry::get('Helper', 'Layout')->getWrapWidth()) {
                if (!isset($items['.bluebox-wrap'])) $items['.bluebox-wrap'] = array();
                $items['.bluebox-wrap'] = array_merge($items['.bluebox-wrap'], array(
                    'max-width' => $wrapWidth,
                ));
            }
            if ($contentWidth = GummRegistry::get('Helper', 'Layout')->getContentWidth()) {
                if (!isset($items['.bluebox-container'])) $items['.bluebox-container'] = array();
                $items['.bluebox-container'] = array_merge($items['.bluebox-container'], array(
                    'max-width' => $contentWidth,
                ));
            }
            
            // Calculate the margin for content elements without marign: 0, auto;
            if ($wrapWidth && $contentWidth) {
                $clientResolution = GummRegistry::get('Helper', 'Media')->getClientResolution();
                $autoMargin = 0;
                
                if ($wrapWidth === '100%') $wrapWidth = $clientResolution;
                if ($contentWidth === '100%') $contentWidth = $clientResolution;
                
                $wrapWidth      = (int) $wrapWidth;
                $contentWidth   = (int) $contentWidth;
                
                if ($wrapWidth > $contentWidth) {
                    // $autoMargin = $contentWidth / $wrapWidth;
                    $autoMargin = ($wrapWidth - $contentWidth) / 2;
                    $autoMargin = ($autoMargin / $wrapWidth) * 100;
                }
                
                $items['.bluebox-snap-content-left'] = array(
                    'left' => $autoMargin . '%;',
                );
                $items['.bluebox-snap-content-right'] = array(
                    'right' => $autoMargin . '%;',
                );
            }
        }
        
        return $items;
    }
    
    private function constructColorValueFromParam($rgbString, $param, $format='output', $styleOptionName='') {
        $struct = array(
            'styleOptionName' => $styleOptionName,
            'alpha' => false,
            'declaration' => false,
            'important' => false,
        );
        $val = 'rgb(' . $rgbString . ')';
        if (is_array($param)) {
            $struct = array_merge($struct, $param);
            
            if (isset($param['alpha']) && !preg_match('/(?i)msie [1-8]/',env('HTTP_USER_AGENT'))) {
                $val = 'rgba(' . $rgbString . ', ' . $param['alpha'] . ')';
            }
            if (isset($param['declaration'])) {
                $val = sprintf($param['declaration'], $val);
            }
            if (isset($param['important']) && $param['important']) {
                $val .= ' !important';
            }
        }
        
        return ($format == 'output') ? $val : $struct;
    }
    
    public function getStylesStructureForLayout() {
        $styleItems = $this->getStylesForLayout('structure');
        
        $result = array();
        foreach ($styleItems as $selector => $properties) {
            foreach ($properties as $propName => $prop) {
                $styleOptionName = $prop['styleOptionName'];
                unset($prop['styleOptionName']);
                if (!isset($result[$styleOptionName])) $result[$styleOptionName] = array();
                if (!isset($result[$styleOptionName][$selector])) $result[$styleOptionName][$selector] = array();
                $result[$styleOptionName][$selector][$propName] = $prop;
            }
        }
        
        return $result;
    }
    
    /* =============== */
    /* HTML GENERATION */
    /* =============== */
    
    public function nestedList($listData, $options=array()) {
        $options = array_merge(array(
            'tag' => 'ul',
            'id' => '',
            'class' => '',
            'keyNestedItem' => 'tabs',
            'keyTitle' => 'title',
        ), $options);
        
        extract($options, EXTR_OVERWRITE);
        
        $outputHtml = '<' . $tag;
        $outputHtml .= ($id) ? ' id="' . $id . '"' : '';
        $outputHtml .= ($class) ? ' class="' . $class . '"' : '';
        $outputHtml .= '>';
        
        foreach ($listData as $listItem) {
            $outputHtml .= '<li>';
            $outputHtml .= '<a href="#">' . $listItem[$keyTitle] . '</a>';
            $outputHtml .= '</li>';
        }
        
        $outputHtml .= '</' . $tag . '>';
        
        return $outputHtml;
    }
    
    public function displayMenu($options = array()) {
        $options = array_merge(array(
            'themeLocation' => 'prime_nav_menu',
            'class' => 'menu',
            'id' => 'prime-nav',
            'depth' => 4,
            'before' => '',
            'after' => '',
            'link_before' => '',
            'link_after' => '',
            'container' => '',
            'walker' => 'GummNavMenuWalker',
        ), $options);
        extract($options, EXTR_OVERWRITE);
        
        //If this is WordPress 3.0 and above AND if the menu location registered in functions/register-wp3.php has a menu assigned to it
        if(function_exists('wp_nav_menu') && has_nav_menu($themeLocation)):
            if ($walker) {
                App::uses($walker, 'Lib/Walker');
            }
            
            wp_nav_menu(
                array(
                    'theme_location' => $themeLocation, 
                    'container' => '', 
                    'menu_class' => $class,
                    'menu_id' => $id,
                    'depth' => $depth,
                    'before' => $before,
                    'after' => $after,
                    'link_before' => $link_before,
                    'link_after' => $link_after,
                    'walker' => $walker ? new $walker : null,
            ));

        //If either this is WP version<3.0 or if a menu isn't assigned, use wp_list_pages()
        else:
            echo '<ul id="'.$id.'" class="'.$class.'">';
                $pagesList = wp_list_pages(array(
                    'depth' => $depth,
                    'title_li' => '',
                    'link_before' => $link_before,
                    'link_after' => $link_after,
                    'echo' => 0
                ));
                if ($themeLocation === 'prime_nav_menu' && $walker === 'GummNavMenuWalker') {
                    $pagesList = apply_filters('gumm_menu_list_pages', $pagesList);
                }
                
                echo $pagesList;
            echo '</ul>';

        endif;
    }
    
    public function displayResponsiveMenu($options = array()) {
        $options = array_merge(array(
            'themeLocation' => 'prime_nav_menu',
            'class' => 'menu-responsive',
            'id' => 'prime-nav-responsive',
            'depth' => 4,
            'container' => false,
            'container_class' => 'main-menu-responsive-wrap',
        ), $options);
        extract($options, EXTR_OVERWRITE);
        
        App::import('Vendor', 'GummResponsiveMenuWalker');
        wp_nav_menu(
            array(
                'theme_location' => $themeLocation, 
                'container' => $container,
                'container_class' => $container_class,
                'menu_id' => $id,
                'menu_class' => $class,
                'depth' => $depth,
                'walker' => new GummResponsiveMenuWalker(),
                'items_wrap' => '<select id="%1$s" class="%2$s">%3$s</select>',
        ));
    }
    
    public function displayLogo() {
        $pixelRatio = GummRegistry::get('Helper', 'Media')->getClientPixelRatio();
        
        $attributes = array(
            'src' => null,
            'alt' => 'Nova Logo',
            'width' => null,
            'height' => null,
        );
        
        $tmp = $this->Wp->getOption('logo');
        
        $logoUrl = GUMM_THEME_URL . '/images/' . GUMM_THEME_PREFIX . '-logo.png';
        if ($pixelRatio > 1 && $retinaLogoParams = $this->Wp->getOption('retina_logo')) {
            $logoUrl = isset($retinaLogoParams['url']) && $retinaLogoParams['url'] ? $retinaLogoParams['url'] : GUMM_THEME_URL . '/images/' . GUMM_THEME_PREFIX . '-logo@2x.png';
            
            $attributes['width'] = $retinaLogoParams['width'] / 2;
            $attributes['height'] = $retinaLogoParams['height'] / 2;
        } elseif ($customLogoUrl = $this->Wp->getOption('logo')) {
            $logoUrl = $customLogoUrl;
        }
        
        $attributes['src'] = $logoUrl;
        ?>
        <a href="<?php echo home_url(); ?>">
            <img <?php echo $this->_constructTagAttributes($attributes); ?> />
        </a>
        <?php
    }
    
    public function googleMap($params = array()) {
        $params = array_merge(array(
            'elementId' => 'map_canvas',
            'lat' => 41.659,
            'lng' => -4.714,
            'class' => 'gmap-canvas',
            'zoom' => 15,
        ), $params);
        extract($params, EXTR_OVERWRITE);
        if (isset($getLatFromOption)) {
            $latFromOption = $this->Wp->getOption($getLatFromOption);
            if (!$latFromOption) return '';
            $lat = $latFromOption;
        }
        if (isset($getLngFromOption)) {
            $lngFromOption = $this->Wp->getOption($getLngFromOption);
            if (!$lngFromOption) return '';
            $lng = $lngFromOption;
        }
        $styleAttr = '';
        if (isset($width) && isset($height)) {
            $styleAttr = ' style="width:'.$width.';height:'.$height.';"';
        }
        $outputHtml = '<div id="'.$elementId.'"'.$styleAttr.' class="'.$class.'"></div>';
        
        $outputHtml .= '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=true"></script>
        
        <script type="text/javascript">
            var gGeocoder;
            var gMap;
            var gMapMarker;
            function initializeGoogleMap('.$elementId.') {
                if (jQuery("#'.$elementId.'").is(":hidden") || jQuery.data(jQuery("#'.$elementId.'"), "gMapInitialized") == "initialized") {
                    return false;
                }
                var latlng = new google.maps.LatLng('.$lat.', '.$lng.');
                var myOptions = {
                    zoom: '.$zoom.',
                    center: latlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    disableDefaultUI: true
                };
                gMap = new google.maps.Map(document.getElementById("'.$elementId.'"), myOptions);
                gMapMarker = new google.maps.Marker({
                    position: latlng,
                    map: gMap
                });
                
                jQuery.data(jQuery("#'.$elementId.'"), "gMapInitialized", "initialized");
            }
            jQuery(document).ready(function() {
                initializeGoogleMap();
            });
        </script>';
        
        return $outputHtml;
    }
    
    /**
     * @param mixed $type
     * @param array $options
     * @return string
     */
    public function postDetails($type='all', array $options=array()) {
        global $post;
        
        $type = (array) $type;
        $defaults = array(
            'linkAuthor' => true,
            'linkCategories' => true,
            'linkComments' => true,
            'beforeDetail' => '<span class="%s">',
            'afterDetail' => '</span> ',
            'prefixes' => array(
                'author' => __('By', 'gummfw'),
                'date' => __('on', 'gummfw'),
                'category' => __('in', 'gummfw'),
                'comments' => __('with', 'gummfw'),
            ),
            'formats' => array(
                'date' => 'F j, Y',
                'comments' => array(__('No Comments', 'gummfw'), __('1 Comment', 'gummfw'), '% ' . __('Comments', 'gummfw')),
            ),
            'attributes' => array(
                'comments' => array()
            ),
        );
        
        $options = array_merge($defaults, $options);
        extract($options, EXTR_OVERWRITE);
        
        if (!$prefixes) $prefixes = array_fill_keys(array_keys($defaults['prefixes']), '');
        elseif (is_string($prefixes)) $prefixes = array_fill_keys(array_keys($defaults['prefixes']), $prefixes);
        
        $formats = array_merge($defaults['formats'], $formats);
        
        $commentsLink = get_permalink() . '#comments';
        
        $detailsClassesMap = array(
            'author' => 'author',
            'date' => 'published',
            'categories' => 'loop-categories',
            'comments' => 'comment-count',
        );
        
        $outputHtml = '';
        foreach ($detailsClassesMap as $detail => $detailClass) {
            if (!in_array('all', $type) && !in_array($detail, $type)) continue;
            
            $beforeField = sprintf($beforeDetail, $detailClass);
            $afterField = $afterDetail;
            
            switch ($detail) {
             case 'author':
                $outputHtml .= $beforeField . $prefixes['author'] . ' ';
                $outputHtml .= ($linkAuthor) ? get_the_author_link() : get_the_author();
                $outputHtml .= $afterField;
                break;
             case 'date':
                 $outputHtml .= $beforeField . $prefixes['date'] . ' ' . get_the_time($formats['date']) . $afterField;
                break;
             case 'categories':
                 $outputHtml .= $beforeField . $prefixes['category'];
                 $postCategories = $this->Wp->getPostCategories($post);
                 if ($postCategories) {
                     $numCategories     = count($postCategories);
                     $maxNumCategories  = Configure::read('Settings.maxNumCategories');
                     $counter           = 0;
                     $categoryEntries   = array();
                     foreach ($postCategories as $catId => $postCategory) {
                         if ($counter >= $maxNumCategories) {
                             $leftNum   = abs($numCategories - $counter);
                             $leftLabel = sprintf(__('... %d more', 'gummfw'), $leftNum);
                             $moreLinkAtts = array(
                                'href' => '#',
                                'class' => 'b-popover',
                             );
                             $postCategoriesIds     = array_keys($postCategories);
                             $leftCategoryEntries   = array();
                             for ($i=$counter; $i<$numCategories; $i++) {
                                 $theCatId  = $postCategoriesIds[$i];
                                 $theCat    = $postCategories[$theCatId];
                                 
                                 if ($linkCategories) {
                                     $leftCategoryEntries[] = '<a href="' . get_category_link($theCatId) . '">' . $theCat . '</a>';
                                 } else {
                                     $leftCategoryEntries[] = $theCat;
                                 }
                                 
                             }
                             $moreLinkAtts['data-content'] = implode(', ', $leftCategoryEntries);
                             
                             $categoryEntries[] = '<a' . $this->_constructTagAttributes($moreLinkAtts) . '>' . $leftLabel . '</a>';
                             break;
                         }
                         if ($linkCategories) {
                             $categoryEntries[] = '<a href="' . get_category_link($catId) . '">' . $postCategory . '</a>';
                         } else {
                             $categoryEntries[] = ' ' . $postCategory;
                         }
                         
                         $counter++;
                     }
                     if ($categoryEntries) {
                         $outputHtml .= ' ' . implode(', ', $categoryEntries);
                     }
                 }
                 $outputHtml .= $afterField;
                break;
             case 'comments':
                $commentsAtrributes = array_merge(array(
                    'href' => $commentsLink,
                ), $attributes['comments']);
                $outputHtml .= $beforeField . $prefixes['comments'] . ' <a' . $this->_constructTagAttributes($commentsAtrributes) . '>';
                ob_start();
                
                list($commentsOne, $commentsTwo, $commentsThree) = $formats['comments'];
                comments_number($commentsOne, $commentsTwo, $commentsThree);
                
                $outputHtml .= ob_get_clean();
                $outputHtml .= '</a>' . $afterField;
                break;
            }
        }
        
        return $outputHtml;
    }
    
    public function image($filename, $attributes=array()) {
        if (strpos($filename, 'https://') === false && strpos($filename, 'https://') === false) {
            $url = GUMM_THEME_URL . '/images/' . $filename;
        } else {
            $url = $filename;
        }
        
        $attributes['src'] = $url;
        
        return '<img' . $this->_constructTagAttributes($attributes) . ' />';
    }
    
    /**
     * @param string $label
     * @param array $options
     * @return string
     */
    public function nextPostsLink($label, array $options=array()) {
    	global $paged, $wp_query;
    	
        $options = array_merge(array(
            'maxPage' => 0,
            'data-loadingtext' => __('Loading', 'gummfw') . ' ...',
            'data-origintext' => $label,
        ), $options);
        $maxPage = $options['maxPage'];

        if ( !$maxPage )
            $maxPage = $wp_query->max_num_pages;

        if ( !$paged )
            $paged = 1;

        $nextPage = intval($paged) + 1;

        $link = '';
        if ( !is_single() && ( $nextPage <= $maxPage ) ) {
            unset($options['maxPage']);
            $options['href'] = next_posts($maxPage, false);
            $link = '<a' . $this->_constructTagAttributes($options) . '>' . $label . '</a>';
        }

        return $link;
    }
    
    /**
     * @param string $on
     * @param string $off
     * @param array $options
     * @return string
     */
    public function gummSwitchPanel($on, $off, array $options=array()) {
        $this->_gummSwitchJsSettings[] = true;
        
        $options = Set::merge(array(
            'tabs' => array('on' => '', 'off' => ''),
        ), $options);
        
        ob_start();
?>
        <div class="gumm-switch-container">
            
            <div class="gumm-switch">
                <div class="gumm-switch-buttons">
                    <span class="on"><?php echo $on; ?></span>
                    <span class="divider"></span>
                    <span class="off"><?php echo $off; ?></span>
                </div>
            </div>
            
            <?php if ($options['tabs']['on'] && $options['tabs']['off']): ?>
            <div class="gumm-switch-tabs">
                <div class="gumm-switch-tab-on"><?php echo $options['tabs']['on']; ?></div>
                <div class="gumm-switch-tab-off"><?php echo $options['tabs']['off']; ?></div>
            </div>
            <?php endif; ?>
            
        </div>
        
<?php
        return ob_get_clean();
    }
    
    public function getSidebarHtml($index) {
        ob_start();
        if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( $index ) ) {}
        return ob_get_clean();
    }
    
    public function url($url, $protocol='http') {
        $pref = $protocol . '://';
        if (strpos($url, $pref) !== 0) {
            $url = $pref . $url;
        }
        
        return $url;
    }
    
    // =============== //
    // WP ACTION HOOKS //
    // =============== //
    
    public function _actionGummSwitchJs() {
        if (!$this->_gummSwitchJsSettings) return;
?>
<script type="text/javascript">
//<![CDATA[
    try {
        jQuery('.gumm-switch').gummSwitch();
    } catch(err){};
    
//]]>
</script>

<?php
    }
    
    public function _actionAddEditorStyle() {
        add_editor_style('app/assets/css/style-editor.css');
    }
    
    public function _filterWpNavMenuItems($items, $args) {
        if (is_object($args->walker) && get_class($args->walker) === 'GummNavMenuWalker' && $args->theme_location === 'prime_nav_menu') {
            $displaySearchIcon = $this->Wp->getOption('header_menu_search_icon', array('booleanize' => true));
            $post = GummRegistry::get('Model', 'Post')->getQueriedObject();

            if (is_a($post, 'WP_POST')) {
                if (
                    isset($post->PostMeta['header_settings']) &&
                    $post->PostMeta['header_settings'] === 'custom' &&
                    isset($post->PostMeta['header_menu_search_icon'])
                ) {
                    $displaySearchIcon = $post->PostMeta['header_menu_search_icon'];
                }
            }
            
            if ($displaySearchIcon) {
                ob_start();
                get_search_form();
                $searchFormHtml = ob_get_clean();
                
                $items .= '<li class="search">';
                $items .= '<a id="prime-nav-searchform-button" class="icon-search" href="#"></a>';
                $items .= '<div id="prime-nav-searchform" class="">' . $searchFormHtml . '</div>';
                $items .= '</li>';
            }

        }
        
        return $items;
    }
    
    public function _filterWpNavMenuPages($items) {
        $displaySearchIcon = $this->Wp->getOption('header_menu_search_icon', array('booleanize' => true));
        $post = GummRegistry::get('Model', 'Post')->getQueriedObject();

        if (is_a($post, 'WP_POST')) {
            if (
                isset($post->PostMeta['header_settings']) &&
                $post->PostMeta['header_settings'] === 'custom' &&
                isset($post->PostMeta['header_menu_search_icon'])
            ) {
                $displaySearchIcon = $post->PostMeta['header_menu_search_icon'];
            }
        }
        
        if ($displaySearchIcon) {
            ob_start();
            get_search_form();
            $searchFormHtml = ob_get_clean();
            
            $items .= '<li class="search">';
            $items .= '<a id="prime-nav-searchform-button" class="icon-search" href="#"></a>';
            $items .= '<div id="prime-nav-searchform" class="">' . $searchFormHtml . '</div>';
            $items .= '</li>';
        }
        
        return $items;
    }
    
}
?>
