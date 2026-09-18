<?php
abstract class GummExternalPluginBase extends GummObject {
    abstract protected function initialize();
    /**
     * @var bool
     */
    protected $initialized = false;
    
    /**
     * @var WpHelper
     */
    protected $Wp;
    
    public function __construct(array $settings) {
        parent::__construct();
        
        $this->Wp = GummRegistry::get('Helper', 'Wp');
        
        if (!isset($settings['path'])) {
            trigger_error(__('Cannot intialize external plugin without supplying path', 'gummfw'));
        }
        
        if ($this->Wp->isPluginActive($settings['path'])) {
            $this->initialize();
        }
    }
}
?>