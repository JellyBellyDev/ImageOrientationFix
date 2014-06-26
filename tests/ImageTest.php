<?php

class ImageTest extends PHPUnit_Framework_TestCase
{
	public $fileNameImageLandscape = "Landscape_1.jpg";

	public function setUp()
	{
	}

	public function tearDown()
	{
	}

	public function testConstructImageClass()
	{
		$image = new Image($this->getInputImagesPath() . $this->fileNameImageLandscape);
		$this->assertInstanceOf('Image', $image);
	}

	public function testGetFilePathInput()
	{
		$image = new Image($this->getInputImagesPath() . $this->fileNameImageLandscape);
		$this->assertTrue(file_exists($image->getFilePathInput()));
	}

	/**
	 * @expectedException Exception
	 */
	public function testFileExistException()
	{
		$image = new Image($this->getInputImagesPath() . 'ThisFileNotExist.jpg');
	}

	public function testSetMimeType()
	{
		$image = new Image($this->getInputImagesPath() . $this->fileNameImageLandscape);
		$this->assertEquals('image/jpeg', $image->getMimeType());
	}

	/**
	 * @expectedException Exception
	 */
	public function testMimeTypeNotValidException()
	{
		$image = new Image($this->getInputImagesPath() . 'mimeTypeNotValid.gif');
	}

	public function testSetExifData()
	{
		$image = new Image($this->getInputImagesPath() . $this->fileNameImageLandscape);
		$exifData = exif_read_data($this->getInputImagesPath() . $this->fileNameImageLandscape, 'IFD0', 0);
		$this->assertEquals($exifData, $image->getExifData());
	}

	public function testGetExifData()
	{
		$image = new Image($this->getInputImagesPath() . $this->fileNameImageLandscape);
		$this->assertTrue(is_array($image->getExifData()));
		$this->assertTrue(!empty($image->getExifData()));
	}

	public function testGetOrientation()
	{
		$image = new Image($this->getInputImagesPath() . $this->fileNameImageLandscape);
		$this->assertTrue(!empty($image->getOrientation()));
		$this->assertEquals(1, $image->getOrientation());
	}

	public function testGetOrientation3()
	{
		$image = new Image($this->getInputImagesPath() . 'Landscape_3.jpg');
		$this->assertTrue(!empty($image->getOrientation()));
		$this->assertEquals(3, $image->getOrientation());
	}

	public function testGetExtension()
	{
		$image = new Image($this->getInputImagesPath() . $this->fileNameImageLandscape);
		$this->assertTrue(!empty($image->getExtension()));
		$this->assertEquals('jpeg', $image->getExtension());
	}

	/**
	 * INPUT_IMAGES is defined in phpunit.xml.dist
	 * @return string
	 */
	public function getInputImagesPath()
	{
		return __DIR__ . INPUT_IMAGES;
	}
}