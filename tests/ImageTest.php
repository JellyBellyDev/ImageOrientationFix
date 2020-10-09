<?php

namespace ImageOrientationFixTest;

use Exception;
use ImageOrientationFix\Image;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Class ImageTest
 * @package ImageOrientationFixTest
 */
class ImageTest extends TestCase
{
    public string $fileNameImageLandscape = 'Landscape_1.jpg';

    public function testConstructImageClass(): void
    {
        $image = new Image($this->getInputImagesPath() . $this->fileNameImageLandscape);
        self::assertInstanceOf(Image::class, $image);
    }

    public function testGetFilePathInput(): void
    {
        $image = new Image($this->getInputImagesPath() . $this->fileNameImageLandscape);
        self::assertFileExists($image->getFilePathInput());
    }

    public function testFileExistException(): void
    {
        $this->expectException(RuntimeException::class);
        $image = new Image($this->getInputImagesPath() . 'ThisFileNotExist.jpg');
    }

    public function testSetMimeType(): void
    {
        $image = new Image($this->getInputImagesPath() . $this->fileNameImageLandscape);
        self::assertEquals('image/jpeg', $image->getMimeType());
    }

    public function testMimeTypeNotValidException(): void
    {
        $this->expectException(RuntimeException::class);
        $image = new Image($this->getInputImagesPath() . 'mimeTypeNotValid.gif');
    }

    public function testSetExifData(): void
    {
        $image    = new Image($this->getInputImagesPath() . $this->fileNameImageLandscape);
        $exifData = exif_read_data($this->getInputImagesPath() . $this->fileNameImageLandscape, 'IFD0', 0);
        self::assertEquals($exifData, $image->getExifData());
    }

    public function testGetExifData(): void
    {
        $image    = new Image($this->getInputImagesPath() . $this->fileNameImageLandscape);
        $exifData = $image->getExifData();
        self::assertIsArray($exifData);
        self::assertNotEmpty($exifData);
    }

    public function testGetOrientation(): void
    {
        $image       = new Image($this->getInputImagesPath() . $this->fileNameImageLandscape);
        $orientation = $image->getOrientation();
        self::assertNotEmpty($orientation);
        self::assertEquals(1, $orientation);
    }

    public function testGetOrientation3(): void
    {
        $image       = new Image($this->getInputImagesPath() . 'Landscape_3.jpg');
        $orientation = $image->getOrientation();
        self::assertNotEmpty($orientation);
        self::assertEquals(3, $orientation);
    }

    public function testGetExtension(): void
    {
        $image     = new Image($this->getInputImagesPath() . $this->fileNameImageLandscape);
        $extension = $image->getExtension();
        self::assertNotEmpty($extension);
        self::assertEquals('jpeg', $extension);
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
