<?php

class ImageOrientationFixTest extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{
	}

	public function tearDown()
	{
	}

	public function dataProviderImages()
	{
		return array(
			array('Landscape_1.jpg', 1),
			array('Landscape_2.jpg', 2),
			array('Landscape_3.jpg', 3),
			array('Landscape_4.jpg', 4),
			array('Landscape_5.jpg', 5),
			array('Landscape_6.jpg', 6),
			array('Landscape_7.jpg', 7),
			array('Landscape_8.jpg', 8),
			array('Portrait_1.jpg', 1),
			array('Portrait_2.jpg', 2),
			array('Portrait_3.jpg', 3),
			array('Portrait_4.jpg', 4),
			array('Portrait_5.jpg', 5),
			array('Portrait_6.jpg', 6),
			array('Portrait_7.jpg', 7),
			array('Portrait_8.jpg', 8),
		);
	}

	/**
	 * @dataProvider dataProviderImages
	 */
	public function testFix($filename, $orientation)
	{
		$time = time();
		$inputFullFilePath = $this->getInputImagesPath() . $filename;
		$inputFullFilePathTmp = $this->getInputImagesPath() . $time . $filename;
		copy($inputFullFilePath, $inputFullFilePathTmp);

		$iof = new ImageOrientationFix($inputFullFilePathTmp);
		$iof->fix();

		$this->assertTrue(file_exists($inputFullFilePathTmp));
		$exifData = exif_read_data($inputFullFilePathTmp, 'IFD0', 0);

		if ($orientation == 1) {
			$this->assertEquals($orientation, $exifData['Orientation']);
		} else {
			$this->assertFalse($exifData);
		}

		@unlink($inputFullFilePathTmp);
	}

	/**
	 * @dataProvider dataProviderImages
	 */
	public function testFixWithOutput($filename, $orientation)
	{
		$inputFullFilePath = $this->getInputImagesPath() . $filename;
		$outputFullFilePath = $this->getOutputImagesPath() . $filename;

		$iof = new ImageOrientationFix($inputFullFilePath, $outputFullFilePath);
		$iof->fix();

		if ($orientation != 1) {
			$this->assertTrue(file_exists($outputFullFilePath));
			$exifData = exif_read_data($outputFullFilePath, 'IFD0', 0);
			$this->assertFalse($exifData);
		}
		@unlink($outputFullFilePath);
	}

	/**
	 * INPUT_IMAGES is defined in phpunit.xml.dist
	 * @return string
	 */
	public function getInputImagesPath()
	{
		return __DIR__ . INPUT_IMAGES;
	}

	/**
	 * OUTPUT_IMAGES is defined in phpunit.xml.dist
	 * @return string
	 */
	public function getOutputImagesPath()
	{
		$path = __DIR__ . OUTPUT_IMAGES;
		if (!file_exists($path)) {
			mkdir($path);
		}
		return $path;
	}
}
