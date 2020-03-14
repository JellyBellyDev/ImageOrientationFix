<?php

namespace ImageOrientationFixTest;

use Exception;
use ImageOrientationFix\Image;
use PHPUnit\Framework\TestCase;

/**
 * Class ImageTest
 * @package ImageOrientationFixTest
 */
class ImageTest extends TestCase
{
    public $fileNameImageLandscape = 'Landscape_1.jpg';

    public function testConstructImageClass(): void
    {
        $image = new Image($this->getInputImagesPath() . $this->fileNameImageLandscape);
        $this->assertInstanceOf(Image::class, $image);
    }

    public function testGetFilePathInput(): void
    {
        $image = new Image($this->getInputImagesPath() . $this->fileNameImageLandscape);
        $this->assertTrue(file_exists($image->getFilePathInput()));
    }

    /**
     * @expectedException Exception
     */
    public function testFileExistException(): void
    {
        $image = new Image($this->getInputImagesPath() . 'ThisFileNotExist.jpg');
    }

    public function testSetMimeType(): void
    {
        $image = new Image($this->getInputImagesPath() . $this->fileNameImageLandscape);
        $this->assertEquals('image/jpeg', $image->getMimeType());
    }

    /**
     * @expectedException Exception
     */
    public function testMimeTypeNotValidException(): void
    {
        $image = new Image($this->getInputImagesPath() . 'mimeTypeNotValid.gif');
    }

    public function testSetExifData(): void
    {
        $image    = new Image($this->getInputImagesPath() . $this->fileNameImageLandscape);
        $exifData = exif_read_data($this->getInputImagesPath() . $this->fileNameImageLandscape, 'IFD0', 0);
        $this->assertEquals($exifData, $image->getExifData());
    }

    public function testGetExifData(): void
    {
        $image    = new Image($this->getInputImagesPath() . $this->fileNameImageLandscape);
        $exifData = $image->getExifData();
        $this->assertTrue(is_array($exifData));
        $this->assertNotEmpty($exifData);
    }

    public function testGetOrientation(): void
    {
        $image       = new Image($this->getInputImagesPath() . $this->fileNameImageLandscape);
        $orientation = $image->getOrientation();
        $this->assertNotEmpty($orientation);
        $this->assertEquals(1, $orientation);
    }

    public function testGetOrientation3(): void
    {
        $image       = new Image($this->getInputImagesPath() . 'Landscape_3.jpg');
        $orientation = $image->getOrientation();
        $this->assertNotEmpty($orientation);
        $this->assertEquals(3, $orientation);
    }

    public function testGetExtension(): void
    {
        $image     = new Image($this->getInputImagesPath() . $this->fileNameImageLandscape);
        $extension = $image->getExtension();
        $this->assertNotEmpty($extension);
        $this->assertEquals('jpeg', $extension);
    }

    /**
     * INPUT_IMAGES is defined in phpunit.xml.dist
     * @return string
     */
    public function getInputImagesPath(): string
    {
        return __DIR__ . INPUT_IMAGES;
    }

    /**
     * OUTPUT_IMAGES is defined in phpunit.xml.dist
     * @return string
     */
    public function getOutputImagesPath(): string
    {
        return __DIR__ . OUTPUT_IMAGES;
    }
}
