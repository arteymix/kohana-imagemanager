<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Model for image.
 * 
 * @package   Images
 * @category  Models
 * @author    Hète.ca Team
 * @copyright (c) 2013, Hète.ca Inc.
 */
class Kohana_Model_Image extends ORM {

    /**
     * Create a validation object for image file.
     * 
     * @param array $file
     * @param integer $max_width
     * @param integer $max_height
     * @param boolean $exact
     * @param integer $max_size
     * @return Validation
     */
    public static function get_image_validation(array $file, $max_width = NULL, $max_height = NULL, $exact = FALSE, $max_size = NULL) {

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
        $this->image_exists() AND unlink($this->image_path());

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
     * @return integer NULL if image file does not exist
     */
    public function width() {

        if ($this->image_exists()) {
            list($width, $height) = getimagesize($this->image_path());
            return $width;
        }

        return NULL;
    }

    /**
     * Get the height of the image in pixel
     * 
     * @return integer NULL if image file does not exist
     */
    public function height() {

        if ($this->image_exists()) {
            list($width, $height) = getimagesize($this->image_path());
            return $height;
        }

        return NULL;
    }

    public function size() {

        if ($this->image_exists()) {
            return filesize($this->image_path());
        }

        return NULL;
    }

    public function mime_type() {

        if ($this->image_exists()) {
            return mime_content_type($this->image_path());
        }

        return NULL;
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
