<?php
if (!class_exists('phpFlickr')) {
    App::uses('phpFlickr', '/Vendor/Flickr');
}
class GummFlickr {
    const API_KEY = '580791bc9a61ccbf0b7e8e1fe9edd462';
    
    private $userId;
    private $phpFlickr;
    private $gummWpHelper;
    
    public function __construct() {
        $this->phpFlickr    = new phpFlickr(self::API_KEY);
        $this->wpHelper     = GummRegistry::get('Helper', 'Wp');
    }
    
    public function findPhotos($username, $args=array()) {
        $args = array_merge(array(
            'limit' => 5,
            'asPosts' => true
        ), $args);
        
        $user = $this->phpFlickr->people_findByUsername($username);
        
        $photos = $this->phpFlickr->people_getPublicPhotos($user['id'], NULL, NULL, $args['limit']);
        
        if ($args['asPosts']) {
            $photos = $this->preparePhotosAsPosts($photos['photos']['photo']);
        }
        
        return $photos;
    }
    
    public function preparePhotosAsPosts($photos) {
        global $post;

        $posts = array();
        foreach ($photos as $photo) {
            $_mediaPostData = $this->wpHelper->setupRawDataAsPost(array(
                'ID' => 'flickrphoto_' . $photo['id'],
                'guid' => $this->phpFlickr->buildPhotoURL($photo, 'medium'),
                'permalink' => $this->phpFlickr->buildPhotoURL($photo, 'large'),
            ), false);
            $_postData = $this->wpHelper->setupRawDataAsPost(array(
                'ID' => 'flickrpost_' . $photo['id'],
                'post_author' => $photo['owner'],
                'post_title' => $photo['title'],
                'post_type' => 'flickr',
                'post_content' => '',
                'post_excerpt' => ' ',
                'Media' => array($_mediaPostData),
                'Thumbnail' => $_mediaPostData
            ));
            
            $posts[] = $_postData;
        }

        return $posts;
    }
}
?>