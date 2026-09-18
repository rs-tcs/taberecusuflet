<?php
class GoogleMapLayoutElement extends GummLayoutElement {
    /**
     * @var string
     */
    protected $id = 'D29FF4AA-D671-423E-96B5-832B1E105F4F';
    
    /**
     * @var string
     */
    public $group = 'contact';
    
    /**
     * @var int
     */
    // protected $gridColumns = 1;
    
    /**
     * @var int
     */
    protected $layoutPosition = 'all';
    
    /**
     * @var string
     */
    private $mapCanvasId;
    
    /**
     * @var array
     */
    protected $supports = array('title');
    
    public function title() {
        return __('Map', 'gummfw');
    }
    
    /**
     * @return array
     */
    protected function _fields() {
        
        $this->mapCanvasId = 'gumm-meta-map-canvas-' . uniqid();
        
        return array(
            'googleMap' => array(
                'type' => 'google-map',
                'name' => __('Map Settings', 'gummfw'),
            ),
        );
    }
    
    public function contentEditor() {
        echo '<div class="gumm-editor-google-maps">' . "\n";
            parent::contentEditor();
        echo '</div>' . "\n";
        
        $this->scriptBlockStart();
?>
        $('#<?php echo $this->mapCanvasId; ?>').gummGoogleMap({
            editor: '.gumm-editor-google-maps',
            width: '100%',
            height: 300,
            useEditorInputsToInit: true,
            controls: {
                pan: false,
                scale: false,
                streetView: false,
                overviewMap: false
            }
        });
<?php      
        $this->scriptBlockEnd();
    }
    
    protected function _render($options) {
        View::renderElement('google-map', $this->getParam('googleMap'));
    }
}
?>