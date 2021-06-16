<?php

trait File{

    protected $errors = array();
    protected $upload_errors_arr = array(
        UPLOAD_ERR_OK         => 'There is no error, the file uploaded with success',
        UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        UPLOAD_ERR_PARTIAL    => 'The uploaded file was only partially uploaded',
        UPLOAD_ERR_NO_FILE    => 'No file was uploaded in either thumbnail or portrait',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
        UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.',
    );

    protected function image_path(){
        return strpos(getcwd(), 'admin') ? 'images' . DS  : "admin" . DS . "images" . DS;
    }
    
    protected function check_files($file){
        if(empty($file) || !is_array($file)){
            $this->errors[] = "There is no file uploaded";
            return false;
        }

        if($file['error'] != 0){
            $this->errors = $this->upload_errors_arr[$file['error']];
            return false;
        }

        return true;
    }

    static function rename_if_exists($path, $filename){
        if(file_exists($path . DS . $filename)){
            $thumbnailCount = 1;
            list($name, $extension) = explode('.', $filename);
            while(file_exists($path . DS . $filename)) {
                $filename = $name . "(" . $thumbnailCount . ")" . '.' . $extension;    
                $thumbnailCount++;
            }
        }

        return $filename;
    }


}