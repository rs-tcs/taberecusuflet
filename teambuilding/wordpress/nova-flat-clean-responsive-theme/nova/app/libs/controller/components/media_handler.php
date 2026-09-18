<?php
class MediaHandlerComponent {
    public $info = array(
        'width' => null,
        'height' => null,
        'mime' => null,
        'type' => null,
        'size' => null,
        'aspectRatio' => null,
    );
    
    public $fullPath;
    public $filename;
    public $dirname;
    public $extension;
    // public $basePath;
    public $aspectRatio;
    
    // private 
    
    public function __construct($filename=null) {
        if (!$filename) return;
        
        $this->initialize($filename);
    }
    
    public function initialize($filename) {
        if (!is_file($filename) && filter_var($filename, FILTER_VALIDATE_URL) === FALSE) return;
        
        $this->fullPath = $filename;
        
        $info = getimagesize($filename);
        
        if (!$info) return;
        
        list($this->info['width'], $this->info['height'], $this->info['type']) = $info;
        if (isset($info['mime'])) $this->info['mime'] = $info['mime'];

        $this->info('size', $this->getSizeFile($this->fullPath));
        
        $pathinfo = pathinfo($filename);
        $this->extension = $pathinfo['extension'];
        $this->basename = $pathinfo['basename'];
        $this->filename = $pathinfo['filename'];
        $this->dirname = $pathinfo['dirname'];
        // $this->basePath = str_replace(DATA_DIR, '', $filename);
        $this->aspectRatio = $this->info['aspectRatio'] = $this->info('width') / $this->info('height');
    }
    
    public function info($name) {
        App::import('Core', 'GummHash');
        $args = func_get_args();
        if (count($args) == 2) {
            $this->info = GummHash::insert($this->info, $name, $args[1]);
            return $this;
        }
        return GummHash::get($this->info, $name);
    }
    
    public function thumbnail($width, $height) {
        if ($width == $this->info('width') && $height == $this->info('height')) {
            return $this->fullPath;
        }
        $destinationFilePath = $this->dirname . DS . $this->filename . '-' . $width . 'x' . $height . '.' . $this->extension;
        if (is_file($destinationFilePath)) {
            return $destinationFilePath;
        }
        
        switch ($this->info('type')) {
         case 1:
            $resource = imagecreatefromgif($this->fullPath);
            break;
         case 2:
            $resource = imagecreatefromjpeg($this->fullPath);
            break;
         case 3:
            $resource = imagecreatefrompng($this->fullPath);
            break;
         default:
            $resource = imagecreatefromstring( file_get_contents( $this->fullPath ) );
            break;
        }
        
        if ($width && !$height) {
            $deviation = $width / $this->info('width');
            $height = $deviation * $this->info('height');
        } elseif  ($height && !$width) {
            $deviation = $height / $this->info('height');
            $width = $deviation * $this->info('width');
        }
        $image = imagecreatetruecolor($width, $height);
        imagealphablending($image, false);
        imagesavealpha($image, true);
        
        $aspectRatio = $width/$height;
        if ($aspectRatio != $this->aspectRatio) {
            if ($aspectRatio > $this->aspectRatio) {
                $bufferHeight = $width/$this->aspectRatio;
                $bufferWidth = $width;
            } else {
                $bufferWidth = $height*$this->aspectRatio;
                $bufferHeight = $height;
            }
            $bufferWidth = round($bufferWidth);
            $bufferHeight = round($bufferHeight);
            
            $bufferImage = imagecreatetruecolor($bufferWidth, $bufferHeight);
            imagealphablending($bufferImage, false);
            imagesavealpha($bufferImage, true);
            imagecopyresampled($bufferImage, $resource, 0, 0, 0, 0, $bufferWidth, $bufferHeight, $this->info('width'), $this->info('height'));
            
            $xCoord = round(($bufferWidth / 2) - ($width / 2));
            $yCoord = round(($bufferHeight / 2) - ($height / 2));


            imagecopy($image, $bufferImage, 0, 0, $xCoord, $yCoord, $bufferWidth, $bufferHeight);

            imagedestroy($bufferImage);
        } else {
            imagecopyresampled($image, $resource, 0, 0, 0, 0, $width, $height, $this->info('width'), $this->info('height'));
        }
        
        switch ($this->info('type')) {
         case 1:
            imagegif($image, $destinationFilePath);
            break;
         case 2:
            imagejpeg($image, $destinationFilePath, 100);
            break;
         case 3:
            imagepng($image, $destinationFilePath);
            break;
         default:
            $destPi = pathinfo($destinationFilePath);
            $destinationFilePath = str_replace('.' . $destPi['extension'], '.jpg', $destinationFilePath);
            imagejpeg($image, $destinationFilePath, 100);
            break;
        }
        
        imagedestroy($image);
        
        return $destinationFilePath;
    }
    
    // ======================== //
    // PUBLIC COMPONENT METHODS //
    // ======================== //
    
    /**
     * Returns absolute filepath to a file on the server's wp installation.
     * Uses the upload_dir() to retrieve this.
     * 
     * @param string
     * @return mixed string or boolean false on failure
     */
    public function urlToFilePath($url) {
        $fp = false;
        $uploadDir = wp_upload_dir();
        
        $baseName = basename(str_replace('/', DS, $uploadDir['baseurl']));
        
        preg_match_all("'^.*\/".$baseName."(\/.*)$'", $url, $dirMatches);
        if (!empty($dirMatches[1])) {
            $fp = $uploadDir['basedir'] . str_replace('/', DS, $dirMatches[1][0]);
            if (!is_file($fp)) {
                $fp = false;
            }
        }
            
        return $fp;
    }
    
    /**
     * Returns an array with full url and requested dimensions
     * from url string.
     * 
     * @param string
     * @return array
     */
    public function urlToRequestParts($url) {
        $urlSafePath = str_replace('https://', '', $url);
        $urlSafePath = str_replace('/', DS, $url);
        $pi = pathinfo($urlSafePath);
        
        $result = array(
            'filename' => null,
            'width' => null,
            'height' => null,
        );
        
        
        if ($sizeString = ltrim(strrchr($pi['filename'], '-'), '-')) {
            
            $originFilename = str_replace('-' . $sizeString, '', $pi['basename']);
            $originFilepath = $pi['dirname'] . DS . $originFilename;
            $originUrl = str_replace(DS, '/', $originFilepath);
            
            $filepath = $this->urlToFilePath($originUrl);
            
            if (!is_file($filepath)) {
                return $result;
                // throw new Exception(__('404. Not found.', 'gummfw'));
            }
            
            if (strpos($sizeString, 'x') === false) {
                $width = $height = null;
            } elseif (strpos($sizeString, 'x') === 0) {
                $width = null;
                $height = (int) ltrim($sizeString, 'x');
            } elseif (strpos($sizeString, 'x') === (strlen($sizeString) - 1) ) {
                $width = (int) rtrim($sizeString, 'x');
                $height = null;
            } else {
                list($width, $height) = explode('x', $sizeString);
                $width = (int) $width;
                $height = (int) $height;
            }
            
            $result['filename'] = $filepath;
            $result['width'] = $width;
            $result['height'] = $height;
        } else {
            $originFilename = str_replace('-.' . $pi['extension'], '.' . $pi['extension'], $pi['basename']);
            $originFilepath = $pi['dirname'] . DS . $originFilename;
            $originUrl = str_replace(DS, '/', $originFilepath);
            
            $result['filename'] = $this->urlToFilePath($originUrl);
        }
        
        return $result;
    }
    
    public function getSizeFile($url) { 
        if (substr($url,0,4) == 'http') { 
        $x = array_change_key_case(get_headers($url, 1),CASE_LOWER); 
        if ( strcasecmp($x[0], 'HTTP/1.1 200 OK') != 0 ) { $x = $x['content-length'][1]; } 
        else { $x = $x['content-length']; } 
        } 
        else { $x = @filesize($url); } 

        return $x; 
    }
}
?>