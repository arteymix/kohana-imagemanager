<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 * 
 * @package Images
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @copyright (c) 2013, Hète.ca Inc.
 */
return array(
    'path' => 'images/',
    'max_size' => '2MB',
    'rules' => array(
        'height' => array(
            array('digit')
        ),
        'width' => array(
            array('digit')
        ),
        'quality' => array(
            array('range', array(':value', 0, 100))
        )
    ),
    'watermark' => array(
        'enabled' => FALSE,
        'path' => NULL
    )
);
?>
