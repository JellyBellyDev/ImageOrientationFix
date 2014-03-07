<?php

class ImageTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
	}

	public function tearDown()
	{
	}

	public function testConstructImageClass()
	{
		$image = new Image($this->getInputImagesPath() . 'Landscape_1.jpg');
		$this->assertInstanceOf('Image', $image);
	}

	public function testGetFilePathInput()
	{
		$image = new Image($this->getInputImagesPath() . 'Landscape_1.jpg');
		$this->assertTrue(file_exists($image->getFilePathInput()));
	}

	public function testSetExifData()
	{
		$image = new Image($this->getInputImagesPath() . 'Landscape_1.jpg');
		$exifData = exif_read_data($this->getInputImagesPath() . 'Landscape_1.jpg', 'IFD0', 0);
		$this->assertEquals($exifData, $image->getExifData());
	}

	public function testGetExifData()
	{
		$image = new Image($this->getInputImagesPath() . 'Landscape_1.jpg');
		$this->assertTrue(is_array($image->getExifData()));
	}

	/**
	 * @expectedException Exception
	 */
	public function testFileExistException()
	{
		$image = new Image($this->getInputImagesPath() . 'ThisFileNotExist.jpg');
	}

	/**
	 * INPUT_IMAGES is defined in phpunit.xml.dist
	 * @return string
	 */
	public function getInputImagesPath()
	{
		echo __DIR__;
		return __DIR__ . INPUT_IMAGES;
	}
}