<?php
class ShortcodesController extends AppController {
    const TINY_MCE_BUTTON_ID = 'gumm-shortcodes-editor-button';
    const TINY_MCE_BUTTON_LIST_ID = 'gumm-shortcodes-list-button';
    const TINY_MCE_BUTTON_COLUMN_ID = 'gumm-shortcodes-column-button';
    const TINY_MCE_PLUGIN_ID = 'gumm_shortcodes_editor';
    const TINY_MCE_POKER_HAND_ID = 'gumm-2p2-poker-hand';
    const TINY_MCE_PRICING_TABLES_ID = 'gumm_sortcodes_pricing_tables_editor';
    
    /**
     * @var array
     */
    public $uses = array('Shortcode');
    
    /**
     * @var array
     */
    public $helpers = array('QuickLaunch', 'Shortcodes', 'Form');
    
    /**
     * @var array
     */
    public $wpActions = array(
        'admin_init' => '_actionInitTinyMcePlugin',
    );
    
    /**
     * @var array
     */
    private $tabsHelperContent = array();
    
    /**
     * @var array
     */
    private $tabsHelperActive = 1;
    
    /**
     * @return void
     */
    public function __construct() {
        parent::__construct();
    
        $this->addShortcodes($this->Shortcode->find('all'));
    }
    
    /**
     * @param array $shortcodes
     * @return void
     */
    private function addShortcodes($shortcodes) {

        foreach ($shortcodes as $shortcode) {
            $method = '__initShortcode_' . Inflector::variable(Inflector::slug($shortcode['id'], '_'));

            add_shortcode($shortcode['id'], array(&$this, $method));
        
            if (isset($shortcode['shortcodes'])) $this->addShortcodes($shortcode['shortcodes']);
        }
    }
    
    /**
     * @param string $method
     * @param mixed $arguments
     * @return void
     */
    public function __call($method, $arguments) {
        if (method_exists($this, $method)) {
            $this->$method();
        } elseif(strpos($method, '__initShortcode') === 0) {
            $methodSuffix = str_replace('__initShortcode', '', $method);
            $method = 'initShortcode' . $methodSuffix;

            $content = do_shortcode(trim($arguments[1]));
            // $content = preg_replace("/^<\\/p>/", '', $content);
            // $content = preg_replace("/<p>$/", '', $content);
            // $content = preg_replace("/<p[^>]*>\s?<\\/p[^>]*>/imsU", '', $content);
            
            return $this->$method($arguments[0], $content, $arguments[2]);

        } elseif (strpos($method, 'initShortcode_') === 0) {
            $atts = $arguments[0];
            $content = do_shortcode($arguments[1]);
            //         
            // $content = preg_replace("/^<\\/p>/", '', $content);
            // $content = preg_replace("/<p>$/", '', $content);
            // $content = preg_replace("/<p[^>]*>\s?<\\/p[^>]*>/imsU", '', $content);
            
            $shortcode = $this->Shortcode->find('first', array('conditions' => array('id' => $arguments[2])));
        }
    }
    
    /**
     * @param mixed $atts
     * @param string $content
     * @param string $shortcodeId
     * @return string
     */
    public function initShortcode_column($atts, $content, $shortcodeId) {
        extract($this->shortcodeAttributes($atts, $shortcodeId), EXTR_SKIP);
        
        if (!preg_match("'^[1-9]\-[1-9]$'", $type)) return;
        
        $columnTypes = explode('-', $type);
        
        $rowSpan = 12/$columnTypes[1]*$columnTypes[0];
        $class = 'span' . $rowSpan;
        
        $styleAttribute = '';
        if ($first) {
            $styleAttribute = ' style="margin-left:0;"';
        }

        $columnHtml = '<div' . $styleAttribute . ' class="' . $class . '">' . wpautop($content) . '</div>';
        
        return $columnHtml;
    }

    /**
     * @param mixed $atts
     * @param string $content
     * @param string $shortcodeId
     * @return string
     */
    public function initShortcode_button($atts, $content, $shortcodeId) {
        extract($this->shortcodeAttributes($atts, $shortcodeId), EXTR_SKIP);
        
        // d($content);
        
        // return 'asd';
        return '<a class="button '.$type.' '.$size.' '.$shape.'" href="'.$link.'">'.$content.'</a>';
        ob_start();
?>
 <a class="button <?php echo $type . ' ' . $size . ' ' . $shape; ?>" href="<?php echo $link; ?>"><?php echo $content; ?></a>
<?php
        return ob_get_clean();
    }
    
    /**
     * @param mixed $atts
     * @param string $content
     * @param string $shortcodeId
     * @return string
     */    
    public function initShortcode_buttonrnd($atts, $content, $shortcodeId) {
        return $this->initShortcode_button($atts, $content, $shortcodeId);
    }
    
    /**
     * @param mixed $atts
     * @param string $content
     * @param string $shortcodeId
     * @return string
     */
    public function initShortcode_dropcap($atts, $content, $shortcodeId) {
        extract($this->shortcodeAttributes($atts, $shortcodeId), EXTR_SKIP);

        if (!$shape && $type) $type .= '-text';
        
        // return '<p>';
        return '<span class="dropcap '.$type.' '.$size.' '.$shape.'">' . $content . '</span>';
        ob_start();
?>

<span class="dropcap <?php echo $type . ' ' . $size . ' ' . $shape; ?>"><?php echo $content; ?></span>

<?php
        return ob_get_clean();
    }
    
    /**
     * @param mixed $atts
     * @param string $content
     * @param string $shortcodeId
     * @return string
     */
    public function initShortcode_dropcapsqr($atts, $content, $shortcodeId) {
        return $this->initShortcode_dropcap($atts, $content, $shortcodeId);
    }
    
    /**
     * @param mixed $atts
     * @param string $content
     * @param string $shortcodeId
     * @return string
     */
    public function initShortcode_dropcaprnd($atts, $content, $shortcodeId) {
        return $this->initShortcode_dropcap($atts, $content, $shortcodeId);
    }
    
    public function initShortcode_tabs($atts, $content, $shortcodeId) {
        ob_start();
?>

<div class="bluebox-tabs">
    <ul class="nav nav-tabs">
        <?php echo $content; ?>
    </ul>
    <div class="tab-content">
        <?php $counter = 1; ?>
        <?php foreach ($this->tabsHelperContent as $tabId => $tabContent): ?>
            <?php
            $tabClass = array('tab-pane');
            if ($counter == $this->tabsHelperActive) $tabClass[] = 'active';
            ?>
            <div id="<?php echo $tabId; ?>" class="<?php echo implode(' ', $tabClass); ?>">
                <?php echo GummRegistry::get('Helper', 'Text')->paragraphize($tabContent); ?>
            </div>
            <?php $counter++; ?>
        <?php endforeach; ?>
    </div>
</div>

<?php
        $this->tabsHelperContent = array();
        $this->tabsHelperActive = 1;
        
        return ob_get_clean();

    }
    
    /**
     * @param mixed $atts
     * @param string $content
     * @param string $shortcodeId
     * @return string
     */
    public function initShortcode_tabContent($atts, $content, $shortcodeId) {
        $atts = $this->shortcodeAttributes($atts, $shortcodeId);
        
        $tabId = 'tab' .  uniqid();
        $this->tabsHelperContent[$tabId] = $content;
        
        $iconClass = 'icon-chevron-right';
        $additionalClasses = 'tab';
        if (in_array('active', $atts)) {
            $additionalClasses .= ' active';
            $this->tabsHelperActive = count($this->tabsHelperContent);
            $iconClass = 'icon-chevron-down';
        }
        
        ob_start();
?>
        <li class="<?php echo $additionalClasses; ?>">
            <a href="#<?php echo $tabId; ?>" data-toggle="tab"><span class="icon-chevron-down"></span><span class="icon-chevron-right"></span><?php echo $atts['title']; ?></a>
        </li>
<?php   
        return ob_get_clean();
    }
    
    public function initShortcode_skillBars($atts, $content, $shortcodeId) {
        $elementClass = 'progress-bars';
        if (GummRegistry::get('Helper', 'Wp')->getOption('enable_effects') === 'true') {
            $elementClass .= ' not-initialized gumm-scrollr-item';
        }
        ob_start();
?>
        <div class="<?php echo $elementClass; ?>"><?php echo $content; ?></div>
<?php
        return ob_get_clean();
    }
    
    public function initShortcode_skillBar($atts, $content, $shortcodeId) {
        $atts = $this->shortcodeAttributes($atts, $shortcodeId);
        
        $title = $atts['title'];
        if (isset($atts['numberformat']) && $atts['numberformat']) {
            $title .= ' <span>/ ' . (string) $atts['level'] . $atts['numberformat'] . '</span> ';
        }
        ob_start();
?>
        <div class="progress">
            <div class="bar" style="width: <?php echo (int) $atts['level']; ?>%;"><p><?php echo $title; ?></p></div>
        </div>
<?php
        return ob_get_clean();
    }
    
    /**
     * @param mixed $atts
     * @param string $content
     * @param string $shortcodeId
     * @return string
     */
    public function initShortcode_accordion($atts, $content, $shortcodeId) {
        $outputHtml = '<div class="bluebox-accordion">';
        $outputHtml .= $content;
        $outputHtml .= '</div>';

        return $outputHtml;
    }
    
    /**
     * @param mixed $atts
     * @param string $content
     * @param string $shortcodeId
     * @return string
     */
    public function initShortcode_accordionTab($atts, $content, $shortcodeId) {
        $class = array('head-link accordion-heading');
        $spanClass = array('accordion-button icon-plus');
        if (in_array('active', $atts)) {
            $class[] = 'ui-state-active';
            $spanClass[] = 'icon-minus';
        } else {
            $spanClass[] = 'icon-plus';
        }
    
        $atts = shortcode_atts(array(
   	      'title' => 'Accordion Title',
        ), $atts);
        
        $outputHtml = '';
        $outputHtml .= '<h3 class=" ' . implode(' ', $class) .  '">';
            $outputHtml .= '<a class="' . implode(' ', $spanClass) .'"></a>';
            $outputHtml .= $atts['title'];
        $outputHtml .= '</h3>';
        $outputHtml .= '<div><div class="accordion-content">' . $content . '</div></div>';
        
        return $outputHtml;
    }
    
    /**
     * @param mixed $atts
     * @param string $content
     * @param string $shortcodeId
     * @return string
     */    
    public function initShortcode_messagebox($atts, $content, $shortcodeId, $closeButton=false) {
        extract($this->shortcodeAttributes($atts, $shortcodeId), EXTR_SKIP);

        ob_start();
?>
        <div class="msg <?php echo $type; ?>">
            <?php if ($closeButton): ?>
                <a class="close" href="#">×</a>
            <?php endif;?>
            <?php echo $content; ?>
        </div>
<?php
        return ob_get_clean();
    }
    
    /**
     * @param mixed $atts
     * @param string $content
     * @param string $shortcodeId
     * @return string
     */
    public function initShortcode_alertbox($atts, $content, $shortcodeId) {
        return $this->initShortcode_messagebox($atts, $content, $shortcodeId, true);
    }
    
    /**
     * @param mixed $atts
     * @param string $content
     * @param string $shortcodeId
     * @return string
     */    
    public function initShortcode_divider($atts, $content, $shortcodeId) {
        extract($this->shortcodeAttributes($atts, $shortcodeId), EXTR_SKIP);
        
        
        return "\r\n" . '<div class="divide ' . $type . '"></div>' . "\r\n";
        ob_start();
?>
<div class="divide <?php echo $type; ?>"></div>
<?php
        return ob_get_clean();
    }
    
    /**
     * @param mixed $atts
     * @param string $content
     * @param string $shortcodeId
     * @return string
     */
    public function initShortcode_magnifyingGlass($atts, $content, $shortcodeId) {
        extract($this->shortcodeAttributes($atts, $shortcodeId), EXTR_SKIP);
        if (!$largeurl || !$smallurl) {
            return;
        }
        
        ob_start();
        $id = 'gumm-mg-' . uniqid();
?>
        <a id="<?php echo $id; ?>" href="<?php echo $largeurl; ?>">
            <img src="<?php echo $smallurl; ?>" title="<?php echo $imgtitle; ?>" />
        </a>
<?php
        $this->scriptBlockStart();
?>
        $('#<?php echo $id; ?>').gummMagnifyingGlass({width: <?php echo $glasssize?>, height: <?php echo $glasssize; ?>})
<?php
        $this->scriptBlockEnd();

        return ob_get_clean();
    }
    
    /**
     * @param mixed $atts
     * @param string $content
     * @param string $shortcodeId
     * @return string
     */
    public function initShortcode_lbimg($atts, $content, $shortcodeId) {
        extract($this->shortcodeAttributes($atts, $shortcodeId), EXTR_SKIP);
        if (!$largeurl) {
            return;
        }
        if (!$smallurl) {
            $smallurl = $largeurl;
        }
    
        ob_start();
?>
        <div class="image-wrap">
            <div class="image-details">
                <img src="<?php echo $smallurl; ?>" />
                <a href="<?php echo $largeurl; ?>" class="image-details-link image-wrap-mask" rel="prettyPhoto[<?php echo uniqid(); ?>]">
                    <i class="icon-search"></i>
                </a>
            </div>
        </div>

<?php
        return ob_get_clean();
    }
    
    public function initShortcode_gauge($atts, $content, $shortcodeId) {
        /**
         * @param mixed $atts
         * @param string $content
         * @param string $shortcodeId
         * @return string
         */
        extract($this->shortcodeAttributes($atts, $shortcodeId), EXTR_SKIP);
        
        $fontSize = null;
        switch ($textsource) {
         case 'symbol':
            $fontSize = $symbolsize;
            break;
         case 'icon':
            $fontSize = $iconsize;
            break;
        }
        
        return GummRegistry::get('Helper', 'Layout')->gaugeChart(array(
            'size' => $size,
            'percent' => $percent,
            // 'color' => '#' . $color,
            'backgroundColor' => '#' . $bgcolor,
            'strokeWidth' => $linewidth,
            'icon' => $icon,
            'symbol' => $symbol,
            'iconStyle' => $textsource,
            'fontSize' => $fontSize
        ));
    }
    
    /**
     * @param mixed $atts
     * @param string $content
     * @param string $shortcodeId
     * @return string
     */
    public function initShortcode_card($atts, $content, $shortcodeId) {
        return GummRegistry::get('Helper', 'PokerHand')->getCardHtml($content, array('size' => 'small'));
    }
    
    /**
     * @param mixed $atts
     * @param string $content
     * @param string $shortcodeId
     * @return string
     */
    public function initShortcode_fancyList($atts, $content, $shortcodeId) {
        return '<ul class="bluebox-fancy-list">' . $content . '</ul>';
    }
    
    /**
     * @param mixed $atts
     * @param string $content
     * @param string $shortcodeId
     * @return string
     */
    public function initShortcode_fancyListItem($atts, $content, $shortcodeId) {
        $liClassAtt = '';
        if (GummRegistry::get('Helper', 'Wp')->getOption('enable_effects') === 'true') {
            $liClassAtt = ' class="not-initialized gumm-scrollr-item"';
        }
        return '<li ' . $liClassAtt . '>' . $content . '<span class="icon-chevron-right"></span></li><span style="clear:both; display:block;"></span>';
    }
    
    
    
    /**
     * @param mixed $atts
     * @param string $shortcodeId
     */
    protected function shortcodeAttributes($atts, $shortcodeId) {
        $shortcode = $this->Shortcode->findById($shortcodeId);
        if (isset($shortcode['attributes'])) {
            $atts = shortcode_atts($shortcode['attributes'], $atts);
        }

        return $atts;
    }
    
    // ============= //
    // MVC RENDERING //
    // ============= //
    
    /**
     * @return void
     */
    public function admin_index() {
        $shortcodes = $this->Shortcode->find('all', array('conditions' => array('group_id' => 'default')));
        
        $this->set(compact('shortcodes'));
    }
    
    /**
     * @param string $shortcodeId
     * @return void
     */
    public function admin_view($shortcodeId=null) {
        if (!$shortcodeId) $shortcodeId = $this->RequestHandler->getNamed('shortcodeId');
        
        $shortcodes = $this->Shortcode->getChildrenForShortcode($shortcodeId);
        $shortcode = $this->Shortcode->find('first', array('conditions' => array('id' => $shortcodeId)));
        $title = $shortcode['name'];
        
        $this->set(compact('shortcodeId', 'shortcodes', 'title'));
    }
    
    /**
     * @param mixed $shortcode
     * @return void
     */
    public function admin_edit($shortcode) {
        if (!is_array($shortcode)) $shortcode = $this->Shortcode->findById($shortcode);
        
        $this->set(compact('shortcode'));
    }
    
    /**
     * @return void
     */
    public function admin_index_list_types() {
        $listTypes = $this->Shortcode->findListTypes();
        
        $this->set(compact('listTypes'));
    }
    
    /**
     * @param array $listType
     * @return void
     */
    public function admin_edit_list_type($listType) {
        $this->set(compact('listType'));
    }
    
    /**
     * @return void
     */
    public function admin_index_column_types() {
        $shortcode = $this->Shortcode->find('first', array('conditions' => array('group_id' => 'columns')));
        $columnTypes = $this->Shortcode->getChildrenForShortcode($shortcode['id']);
        
        $this->set(compact('columnTypes'));
    }
    
    public function admin_edit_column_type($shortcode) {
        $this->set(compact('shortcode'));
    }
    
    /**
     * @return void
     */
    public function admin_poker_hand_parser() {
        if ($this->data && isset($this->data['2p2_text'])) {
            App::uses('PokerHandExtractor', 'Plugin/PokerHand');
            $Parser = new PokerHandExtractor();
            
            $PokerHand = $Parser->extract($this->data['2p2_text']);
            
            $this->set(compact('PokerHand'));
        }
    }
    
    /**
     * @param string $content
     * @return mixed
     */
    public function admin_do_shortcode($content=null) {
        if (!$content) $content = $this->RequestHandler->getNamed('content');
        
        // d($content);
        $this->autoRender = false;
        
        echo do_shortcode($content);
    }
    
    // ================ //
    // TinyMCE HANDLING //
    // ================ //
    
    /**
     * @return void
     */
    public function _actionInitTinyMcePlugin() {
        if (current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' )) {
            add_filter('mce_buttons', array(&$this, '_filterInitTinyMceButton'));
            add_filter('mce_external_plugins', array(&$this, '_filterInitTinyMcePlugin'));
        }
    }
    
    /**
     * @param array $buttons
     * @return array
     */
    public function _filterInitTinyMceButton($buttons) {
        // d($buttons);
        array_push($buttons, '|', self::TINY_MCE_BUTTON_LIST_ID, self::TINY_MCE_BUTTON_COLUMN_ID, self::TINY_MCE_BUTTON_ID);
        
        return $buttons;
    }
    
    /**
     * @param array $plugins
     * @return array
     */
    public function _filterInitTinyMcePlugin($plugins) {
        $plugins[self::TINY_MCE_PLUGIN_ID] = GUMM_THEME_JS_URL . 'gumm-shortcodes-editor/gumm-admin-shortcodes.js';
        
        return $plugins;
    }
}
?>