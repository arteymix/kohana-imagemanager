<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 
 */
Route::set('images', 'images/<hash>', array(
    'hash' => '[a-f0-9]{20}', // sha1
))->defaults(array(
    'controller' => 'images',
))
?>
