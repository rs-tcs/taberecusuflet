<?php
class SidebarsController extends AppController {
    
    const WPNONCE = '9638D670-B322-4AC2-9757-280518D90813';
    
    /**
     * @var array
     */
    public $uses = array('Sidebar');
    
    /**
     * @var array
     */
    private $_defaultSidebarSettings = array(
        'name'          => '',
        'description'   => '',
        'before_widget' => '<div class="widget-wrap">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="bluebox-heading-wrap"><h3 class="bluebox-heading">',
        'after_title'   => '</h3></div>',
    );
    
    /**
     * Registers default and custom created sidebars
     * Default ones are taken from Configure::write('sidebars'...)
     * 
     * @return void
     */
    public function registerSidebars() {
        if (!function_exists('register_sidebar')) return; 

        $customSidebars = $this->Sidebar->find('all', array('conditions' => array('custom' => true)));
        
        $defaultSidebars = Configure::read('sidebars');
        
        $sidebars = (array) $customSidebars;
        
        foreach ($defaultSidebars as $orientation => $sidebarsForOrientation) {
            $this->Sidebar->storeDefaultForOrientation($orientation, reset($sidebarsForOrientation));

            foreach ($sidebarsForOrientation as $sidebarForOrientation) {
                $sidebars[] = $sidebarForOrientation;
            }
        }

        foreach ($sidebars as $sidebarSettings) {
            register_sidebar(array_merge($this->_defaultSidebarSettings, $sidebarSettings));
        }

    }
    
    /**
     * @param array $sidebars
     * @return void
     */
    public function admin_index($sidebars=null) {
        if (!$sidebars) $sidebars = $this->Sidebar->find('all');

        $this->set(compact('sidebars'));
    }
    
    /**
     * @param array $sidebar
     * @return void
     */
    public function admin_view($sidebar) {
        $this->set(compact('sidebar'));
    }
    
    /**
     * @return void
     */
    public function admin_add() {
    }
    
    public function admin_save() {
        if ($this->data && isset($this->data['Sidebar'])) {
            if (!$this->validates()) die(__('Security check failed.', 'gummfw'));

            $this->Sidebar->saveAll();
            
            if ($this->RequestHandler->isAjax()) {
                $sidebar = reset($this->data['Sidebar']);
                $sidebar = reset($sidebar);
                $sidebar['custom'] = true;
                $this->set('sidebar', $sidebar);
                $this->render('admin_view');
            }
        }
    }
    
    /**
     * @param string $sidebarId
     * @return void | bool
     */
    public function admin_delete($sidebarId=null) {
        $this->autoRender = false;
        
        if (!$sidebarId) $sidebarId = $this->RequestHandler->getNamed('sidebarId');
        
        $success = false;
        if ($sidebarId) {
            $success = $this->Sidebar->delete($sidebarId);
        }
        
        if ($this->RequestHandler->isAjax()) {
            echo json_encode($success);
        }
        
        return $success;
    }
}
?>