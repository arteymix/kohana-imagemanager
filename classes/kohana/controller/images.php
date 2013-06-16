<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * @package Images
 * @category Controllers
 * @author Guillaume Poirier-Morency <john.doe@example.com>
 */
class Kohana_Controller_Images extends Controller {

    /**
     *
     * @var Image
     */
    protected $image;

    /**
     * Show a simple image
     */
    public function action_index() {

        // Validate the query
        $validation = Validation::factory($this->request->query());

        foreach (Kohana::$config->load('images.rules') as $field => $rules) {
            $validation->rules($field, $rules);
        }

        if ($validation->check()) {

            $this->image = Image::factory(Kohana::$config->load('images.path') . $this->request->param('hash'))
                    ->resize($this->request->query('width'), $this->request->query('height'));

            if (Kohana::$config->load('images.watermark.enabled') === TRUE) {
                $this->image->watermark(Image::factory(Kohana::$config->load('images.watermark.path')));
            }

            $identifier = sha1(serialize($this->image));

            $render = Kohana::cache($identifier);

            if ($render === NULL) {
                $render = (string) $this->image;
                Kohana::cache($identifier, $render);
            }

            $this->response
                    ->headers('Content-type', $this->image->mime)
                    ->body($render);
        } else {

            // Throw exception with validation exceptions        
            $this->response
                    ->headers('Content-type', 'application/json')
                    ->body(json_encode($validation->errors()))
                    ->status(401);
        }
    }

}

?>
