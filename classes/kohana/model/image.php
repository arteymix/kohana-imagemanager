<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Model for image.
 * 
 * @package ImageManager
 * @category Models
 * @author Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 */
class Kohana_Model_Image extends ORM {

    /**
     * 
     * @param array $file
     * @param integer $max_width
     * @param integer $max_height
     * @param boolean $exact
     * @param string $max_size
     * @return Validation
     */
    public static function get_image_validation(array $file, $max_width = NULL, $max_height = NULL, $exact = FALSE, $max_size = NULL) {

        if ($max_size === NULL) {
            $max_size = Kohana::$config->load('images.max_size');
        }

        return Validation::factory($file)
                        ->rule('name', 'not_empty')
                        ->rule('tmp_name', 'not_empty')
                        ->rule('error', 'not_empty')
                        ->rule('size', 'not_empty')
                        ->rule('name', 'Upload::not_empty', array(':file'))
                        ->rule('name', 'Upload::image', array(':file', $max_width, $max_height, $exact))
                        ->rule('name', 'Upload::size', array(':file', $max_size))
                        ->bind(':file', $file);
    }

    public function delete() {
        // Unlink only if the file exists
        if ($this->image_exists()) {
            unlink($this->path());
        }
        return parent::delete();
    }

    /**
     * Get the image relative path.
     * 
     * @return string
     */
    public function image_path() {
        return Images::hash_to_path($this->hash);
    }

    /**
     * Tells if the image exists.
     * 
     * This function will also check sha1 hash to ensure image is still valid.
     * 
     * @return boolean
     */
    public function image_exists() {
        return is_file($this->image_path()) && sha1_file($this->image_path()) === $this->hash;
    }

    /**
     * Get the width of the image in pixel.
     * 
     * @return integer
     */
    public function width() {
        list($width, $height) = getimagesize($this->image_path());
        return $width;
    }

    /**
     * Get the height of the image in pixel
     * 
     * @return integer
     */
    public function height() {
        list($width, $height) = getimagesize($this->image_path());
        return $height;
    }

    public function rules() {
        return array(
            'hash' => array(
                array('not_empty'),
                array('alpha_numeric'),
                array('exact_length', array(':value', 40))
            ),
        );
    }

}

?>
