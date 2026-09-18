<?php
App::uses('GummExternalPluginBase', 'Plugin/GummExternalPluginIntegration');

class GummExternalPluginIntegration extends GummObject {
    
    protected $Wp;
    
    public function __construct() {
        parent::__construct();
        $this->Wp = GummRegistry::get('Helper', 'Wp');
    }
    
    public function integrate() {
        $externalPlugins = (array) Configure::read('Data.externalPluginIntegraion');
        
        foreach ($externalPlugins as $pluginName => $pluginSettings) {
            if ($this->Wp->isPluginActive($pluginSettings['path'])) {
                
                $gummPluginClassName = Inflector::camelize('Gumm' . $pluginName);
                App::uses($gummPluginClassName, 'Plugin/GummExternalPluginIntegration');

                $gummPluginObject = new $gummPluginClassName($pluginSettings);
            }
        }
    }
}
?>