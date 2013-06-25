<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Tests for Images. 
 * 
 * @todo finish complete testing
 * 
 * @package   Images
 * @category  Tests
 * @author    Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @copyright (c) 2013, Hète.ca Inc.
 */
class Images_Test extends Unittest_TestCase {

    /**
     *
     * @var Model_Image
     */
    private $image;

    public function test_store() {

        $filepath = readline('Enter a image filepath: ');

        $this->assertFileExists($filepath);

        $file = array(
            array(
                'name' => 'test_file',
                'tmp_name' => $filepath,
                'error' => 0,
                'size' => filesize($filepath)
            )
        );

        list($this->image) = Images::instance()->store($file);

        $this->assertInstanceOf($this->image, 'Model_Image');

        $this->assertEquals($this->image->hash, sha1_file($filepath));

        $this->assertTrue($this->image->image_exists());
    }

    /**
     * @depends test_store
     */
    public function test_delete() {

        $path = $this->image->image_path();

        $this->image->delete();

        $this->assertFileNotExists($path);
    }

}

?>
