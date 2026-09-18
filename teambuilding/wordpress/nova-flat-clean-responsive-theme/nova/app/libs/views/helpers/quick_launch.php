<?php
class QuickLaunchHelper extends GummHelper {
    
    /**
     * @var array
     */
    private $_defaultSettings = array(
        'title' => 'Quick Launch',
        'itemsPerView' => -1,
        'wrap' => true,
    );
    
    /**
     * @var array
     */
    private $_defaultViewSettings = array(
        'title' => 'View',
        'itemsPerRow' => 4,
        'wrap' => false,
    );
    
    /**
     * @param array $items
     * @param array $params
     * @return string
     */
    public function quickLaunch(array $items, array $params=array()) {
        $params = array_merge($this->_defaultSettings, $params);

        if ($params['itemsPerView'] === -1) {
            $items = array($items);
        } else {
            $items = array_chunk($items, $params['itemsPerView']);
        }
        
        $outputHtml = '<div class="quickLaunch">';
        $outputHtml .= '
            <div class="quickLaunchNavBar ">
                <h6 class="popup-nav-heading quickLaunchViewTitle"><span>' . $params['title'] . '</span></h6>
                <a class="back-button quickLaunchBackButton"><span>' . __('Back', 'gummfw') . '</span></a>
            </div>
        ';
        
        foreach ($items as $itemsForView) {
            $outputHtml .= $this->quickLaunchView($itemsForView, $params);
        }
        
        $outputHtml .= '</div>';
        
        return $outputHtml;
    }
    
    /**
     * @param array $items
     * @param array $params
     * @return string
     */
    public function quickLaunchView(array $items, array $params=array()) {
        $params = array_merge($this->_defaultViewSettings, $params);

        foreach ($items as &$qlItem) {
            $qlItem = array_merge(array(
                'id' => uniqid(),
                'name' => 'Quick Launch Item',
                'qlItemContent' => '',
                'qlItemAction' => '#',
                'qlItemNavigateTo' => 'content',
            ), (array) $qlItem);

        }
        
        $itemsForView = array_chunk($items, $params['itemsPerRow']);
        
        ob_start();
?>
        <?php if ($params['wrap']): ?>
        <div class="quickLaunchView" title="<?php echo $params['title']; ?>">
        <?php else: ?>
        <div class="quickLaunchViewTitle" title="<?php echo $params['title']; ?>"></div>
        <?php endif; ?>

            <?php foreach ($itemsForView as $rowNumber => $itemsForRow): ?>
            <div class="quickLaunchRow row-<?php echo $rowNumber; ?>">

                <div class="quickLaunchRowIconsWrap">
                    <?php foreach ($itemsForRow as $item): ?>
                        <?php
                        $navigateClass = 'ql-navigate-open';
                        switch ($item['qlItemNavigateTo']) {
                         case 'view':
                            $navigateClass = 'ql-navigate-view';
                            break;
                         case 'content':
                            $navigateClass = 'ql-navigate-content';
                            break;
                         case 'none':
                            $navigateClass = 'ql-navigate-none';
                            break;
                        }
                        
                        ?>
                        <a class="quickLaunchItem <?php echo $navigateClass; ?>" href="<?php echo $item['qlItemAction']; ?>">
                            <?php
                            $styleBgImage = '';
                            if ($item['icon']) {
                                if (!is_array($item['icon'])) {
                                    $item['icon'] = array('url' => $item['icon']);
                                }
                                $item['icon'] = array_merge(array('url' => '', 'repeat' => 'repeat', 'position' => 'left top'), $item['icon']);
                                
                                if ($item['icon']['url']) {
                                    $styleBgImage = "background-image: url('{$item['icon']['url']}'); background-repeat: {$item['icon']['repeat']}; background-position: {$item['icon']['position']};";
                                }
                            }
                            ?>

                            <div class="quickLaunchIcon" style="<?php echo $styleBgImage;?>">

                            </div>
                            <p class="quickLaunchItemTitle"><?php echo $item['name']; ?></p>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="quickLaunchRowContentsWrap">
                    <?php foreach ($itemsForRow as $item): ?>
                    <div id="<?php echo $item['id']; ?>-quickLaunchContent" class="quickLaunchItemContent">
                        <div class="quickLaunchContentPointer"></div>
                        <div class="quickLaunchContentPointerInvert"></div>
                        <div class="quickLaunchContentWrap">
                            <?php echo $item['qlItemContent'];?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

            </div>
            <?php endforeach; ?>
            <div class="clear"></div>

        <?php if ($params['wrap']): ?>
        </div>
        <?php endif; ?>

<?php
        $outputHtml = ob_get_clean();
        
        return $outputHtml;
    }
    
    /**
     * @param array $items
     * @return array
     */
    public function prepareForQuickLaunchDisplay(array $items=array(), array $params=array()) {
        $params = array_merge(array(
            'items' => 'types',
            'url' => array(),
            'urlParams' => array(),
            'contentCallback' => null,
            'showContent' => true,
        ), $params);
        extract($params, EXTR_PREFIX_ALL, 'param');
        
        foreach ($items as &$item) {
            $item = array_merge(array(
                'id' => uniqid(),
                'contentHtml' => '',
                'icon' => '',
            ),(array) $item);
            
            if (isset($item[$param_items]) && $item[$param_items]) {
                $url = $param_url;
                if ($param_urlParams) {
                    foreach ($param_urlParams as $k => $v) {
                        if (isset($item[$v])) $url[$k] = $item[$v];
                    }
                }
                
                $item['qlItemNavigateTo'] = 'view';
                $item['qlItemAction'] = GummRouter::url($url);
                $item['qlItemContent'] = '';
            } else {
                $item['qlItemNavigateTo'] = ($param_showContent) ? 'content' : 'none';
                
                $item['qlItemAction'] = '#' . $item['id'] . '-quickLaunchContent';
                
                if ($params['contentCallback']) {
                    if (is_callable($params['contentCallback'])) {
                        $item['qlItemContent'] = call_user_func_array($params['contentCallback'], array($item));
                    } elseif (is_array($params['contentCallback']) && isset($params['contentCallback']['action'])) {
                        $requestAction = $params['contentCallback'];
                        $requestAction[] = $item;
                        ob_start();
                        $this->requestAction($requestAction);
                        $item['qlItemContent'] = ob_get_clean();
                    }
                }
    
            }
        }
        
        return $items;
    }
}
?>