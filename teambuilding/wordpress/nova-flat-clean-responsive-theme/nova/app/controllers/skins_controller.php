<?php
class SkinsController extends AppController {
    
    /**
     * @var array
     */
    public $uses = array('Skin', 'Option');
    
    public function get_styles_structure() {
        $this->autoRender = false;
        if (!$this->RequestHandler->isAjax()) return;
        
        echo json_encode(GummRegistry::get('Helper', 'Html')->getStylesStructureForLayout()); die;
    }
    
    /**
     * @param string $optionId
     * @return void
     */
    public function admin_index($optionId=null) {
        if (!$optionId) $optionId = $this->RequestHandler->getNamed('optionId');
        
        $skins = $this->Skin->find('all');
        $activeSkinId = $this->Option->find('_activeThemeSkin');
        
        $this->set(compact('skins', 'activeSkinId'));
    }
    
    /**
     * @param string $id
     * @return void
     */
    public function admin_edit($id=null) {
        if (!$id) $id = $this->RequestHandler->getNamed('id');
        
        if (!$id) $id = uniqid();
        
        $skin = $this->Skin->findById($id);
        
        if ($this->data) {
            if (!$this->validates()) die(__('Security check failed.', 'gummfw'));
            
            $this->Skin->save($this->data);
            if ($this->RequestHandler->isAjax()) {
                $skins = $this->Skin->find('list');
                $activeSkinId = null;
                if ($this->Skin->lastModifiedId && isset($skins[$this->Skin->lastModifiedId])) {
                    $activeSkinId = $this->Skin->lastModifiedId;
                }
                
                $this->set(compact('skins', 'activeSkinId'));
                $this->render('admin_index');
            }
        }
        App::import('Core', 'GummFolder');
        $Folder = new GummFolder(GUMM_ASSETS . 'css' . DS . 'skins');
        if ($cssFilesAvailable = end($Folder->read(false))) {
            $cssFilesAvailable = array_combine($cssFilesAvailable, $cssFilesAvailable);
        }
        
        array_unshift($cssFilesAvailable, 'none');
        
        $this->set(compact('id', 'skin', 'cssFilesAvailable'));
    }
    
    /**
     * @param string $id
     * @return boolean|json string
     */
    public function admin_delete($id=null) {
        $this->autoRender = false;
        if (!$id) $id = $this->RequestHandler->getNamed('id');

        $success = false;
        if ($id) {
            $success = $this->Skin->remove($id);
        }
        
        if ($this->RequestHandler->isAjax()) {
            $response = array('status' => 'ko', 'msg' => __('There was an error deleting skin.', 'gummfw'));
            if ($success) {
                $response['status'] = 'ok';
                $response['msg'] = __('Skin deleted', 'gummfw');
                $response['itemId'] = $id;
            }
            echo json_encode($response);
            die;
        }

        return $success;
    }
    
    /**
     * @param string $id
     * @return void
     */
    public function admin_set_active($id=null) {
        $this->autoRender = false;
        if (!$id) $id = $this->RequestHandler->getNamed('id');
        if (!$id) return;
        
        $this->Skin->setActiveSkin($id);
    }
}
?>