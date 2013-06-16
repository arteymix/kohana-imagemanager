<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Image manager.
 * 
 * @package Images
 * @author Guillaume Poirier-Morency <john.doe@example.com>
 * @copyright (c) 2013, Hète.ca Inc.
 */
class Kohana_Images {

    /**
     * Singleton
     * 
     * @var ImageManager
     */
    protected static $instance;

    /**
     * Get the instance of ImageManager singleton.
     * 
     * @return ImageManager
     */
    public static function instance() {
        return Images::$instance ? Images::$instance : Images::$instance = new Images();
    }

    public static function parse_multiple_files(array $files) {
        foreach ($files as $field => $values) {            
            foreach ($values as $index => $value) {
                unset($files[$field]);
                $files[$index][$field] = $value;
            }            
        }
        return array_filter($files, 'Upload::not_empty');
    }

    /**
     * Take an hash and return its filepath.
     * 
     * @param string $hash
     * @return string
     */
    public static function hash_to_path($hash) {
        return Kohana::$config->load('images.path') . $hash;
    }

    protected function __construct() {
        
    }

    /**
     * Store an image data on hard drive and database.
     * 
     * @param array $files
     * @throws ORM_Validation_Exception 
     * @return array a list of stored Model_Image.
     */
    public function store(array $files, $max_width = NULL, $max_height = NULL, $exact = FALSE, $max_size = NULL) {

        $images = array();

        $errors = NULL;

        $files = isset($files['tmp_name']) ? Images::parse_multiple_files($files) : $files;
        
        foreach ($files as $file) {

            $tmp_name = $file['tmp_name'];

            $hash = sha1_file($tmp_name);

            $image = ORM::factory('image', array("hash" => $hash));

            $image->hash = $hash;

            try {
                $images[] = $image->save(Model_Image::get_image_validation($file, $max_width, $max_height, $exact, $max_size));
                Upload::save($file, $hash, Kohana::$config->load('images.path'));
            } catch (ORM_Validation_Exception $ove) {
                $errors = $errors instanceof ORM_Validation_Exception ? $errors->merge($ove) : $ove;
            }
        }

        if ($errors instanceof ORM_Validation_Exception) {
            throw $errors;
        }

        return $images;
    }
}

?>
