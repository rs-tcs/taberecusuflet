<?php
class MediaController extends AppController {

    /**
     * @var array
     */
    public $uses = array('Media');
    
    /**
     * @var array
     */
    public $components = array('MediaHandler', 'RequestHandler');
    
    /**
     * @param string
     * @return void
     */
    public function display($url) {
        $this->autoRender = false;
        
        $fileParts = $this->MediaHandler->urlToRequestParts(urldecode($url));
        
        extract($fileParts, EXTR_OVERWRITE);
        if ($width || $height) {
            $MediaHandler = new MediaHandlerComponent($filename);
            if (!is_file($resizedPath = $MediaHandler->thumbnail($width, $height))) {
                throw new NotFoundException(__('404. Not found.', 'gummfw'));
                die;
            }
            header('Content-Type: ' . $MediaHandler->info('mime'));
            echo file_get_contents($resizedPath);

        } else {
            header('Content-Type: image/jpeg');
            echo file_get_contents($filename);   
        }
        
    }
    
    /**
     * @param int $id
     * @return void
     */
    public function admin_edit($id=null) {
        if (!$id) $id = $this->RequestHandler->getNamed('mediaId');
        
        $mediaPost = null;
        if ($this->data) {
            $success = false;
            if (isset($this->data['Media']) && $this->validates()) {
                $success = true;
                foreach ($this->data['Media'] as $id => $mediaMeta) {
                    $this->Media->id = $id;
                    if (!$this->Media->updateMediaFields($mediaMeta)) $success = false;
                }
            }
            if ($this->RequestHandler->isAjax()) {
                $response = array('status' => 'ko', 'msg' => __('There was an error saving media meta data.', 'gummfw'));
                if ($success) {
                    $response['status'] = 'ok';
                    $response['msg'] = __('Media meta data saved.', 'gummfw');
                }
                echo json_encode($response);
                die;
            }
        }
        
        if ($id) $mediaPost = get_post($id);
        
        $this->set(compact('mediaPost'));
    }
    
    /**
     * @return void;
     */
    public function admin_upload() {
        $mediaFile = array('error' => __('No files to upload', 'gummfw'));
                
        if ($this->data && $_FILES && $this->validates()) {
            $override = array(
                'test_form' => false,
                'action' => 'wp_handle_upload',
            );
            if ($mediaFile = wp_handle_upload($_FILES['Filedata'], $override)) {
                list ($mediaFile['width'], $mediaFile['height']) = getimagesize($mediaFile['file']);
            }
        }
        
        $render = true;
        if (isset($this->data['_render'])) {
            if ($this->data['_render'] == 0) $render = false;
            elseif (strpos($this->data['_render'], 'x') !== false) $render = $this->data['_render'];
        }
        
        if ($render) {
            $this->set(compact('mediaFile', 'optionId', 'render'));
            $this->render();
        }
        
        if ($this->RequestHandler->isAjax()) {
            if (!$render) {
                echo json_encode($mediaFile);
            }
            die;
        }
    }
    
    /**
     * @return void
     */
    public function admin_add_to_library() {
        $attachmentPost = array();
        $optionId = (isset($this->data['optionId'])) ? $this->data['optionId'] : false;
        $postId = (isset($this->data['postId'])) ? $this->data['postId'] : false;
        
        if ($this->data && $_FILES && $this->validates()) {
            $id = media_handle_upload('Filedata', $postId);
            
            if (is_wp_error($id)) {
                $uploadErrors = $id->get_error_messages();
                $this->set(compact('uploadErrors'));
                $this->render();
                return;
            }
            $attachmentPost = get_post($id);
        }
        
        $this->set(compact('attachmentPost', 'optionId', 'postId'));
    }
    
    public function admin_index() {
        $attachmentPosts = array();
        $optionId = (isset($this->data['optionId'])) ? $this->data['optionId'] : false;
        if (isset($this->data['gummMedia']) && is_array($this->data['gummMedia'])) {
            $attachmentPosts = get_posts(array(
                'post__in' => $this->data['gummMedia'],
                'posts_per_page' => -1,
                'post_type' => 'attachment',
            ));
        }

        $this->set(compact('attachmentPosts', 'optionId'));
    }
    
    /**
     * @param int $mediaId
     * @return mixed boolean false on error | array with post data on success
     */
    public function admin_delete($mediaId=null) {
        $this->autoRender = false;
        
        if (!$mediaId) $mediaId = $this->RequestHandler->getNamed('mediaId');
        
        $success = false;
        if ($mediaId) {
            $success = $this->Media->delete($mediaId);
        }
        
        if ($this->RequestHandler->isAjax()) {
            $response = array('status' => 'ko', 'msg' => sprintf(__('There was an error deleting media. Function %1$s wp_delete_attachment() %2$s returned an empty result.', 'gummfw'), '<strong>', '</strong>'));
            if ($success) {
                $response['status'] = 'ok';
                $response['msg'] = __('Media item deleted', 'gummfw');
            }
            echo json_encode($response);
            die;
        }
        
        return $success;
    }
    
    public function admin_remove() {
        global $wpdb;
        
        $imageId = $_POST['data'];
        
        $query = "DELETE FROM $wpdb->options WHERE option_name LIKE '$imageId'";
        $wpdb->query($query);
                
        die();
    }
    
    public function admin_add_embed_video() {
        $optionId = $this->RequestHandler->getNamed('optionId');
        $postId = $this->RequestHandler->getNamed('postId');
        
        $this->set(compact('optionId', 'postId'));
    }
    
    /**
     * @return string | renders
     */
    public function admin_save_embed_video() {
        $insertedId = null;
        $postId = null;
        $optionId = null;
        
        if ($this->data && $this->data['EmbedVideo'] && $this->validates()) {
            extract($this->data['EmbedVideo'], EXTR_OVERWRITE);
            $insertedId = $this->Media->saveVideo($code);
        }
        
        if (!$insertedId) {
            $this->set('uploadErrors', array(__('Could not insert media as attachment. Function <strong>wp_insert_attachment</strong> returned empty value.', 'gummfw')));
        } else {
            $this->set('attachmentPosts', array(get_post($insertedId)));
        }
        
        $this->set(compact('optionId'));
        
        $this->render('admin_index');
    }
    
    /**
     * @return string | renders
     */
    public function admin_embed_video_info() {
        $data = array();
        // $data = $this->Media->getVideoData('https://vimeo.com/59740099');
        $data = $this->Media->getVideoData('asdasd');
        if ($this->data && isset($this->data['embedCode'])) {
            $data = $this->Media->getVideoData($this->data['embedCode']);
        }
        
        $this->set(compact('data'));
    }
}