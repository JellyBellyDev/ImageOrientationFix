<?php

namespace ImageOrientationFixTest;

use Exception;
use ImageOrientationFix\ImageOrientationFixer;
use PHPUnit\Framework\TestCase;

/**
 * Class ImageOrientationFixerTest
 * @package ImageOrientationFixTest
 */
class ImageOrientationFixerTest extends TestCase
{
    public function dataProviderImages(): array
    {
        return [
            ['Landscape_1.jpg', 1],
            ['Landscape_2.jpg', 2],
            ['Landscape_3.jpg', 3],
            ['Landscape_4.jpg', 4],
            ['Landscape_5.jpg', 5],
            ['Landscape_6.jpg', 6],
            ['Landscape_7.jpg', 7],
            ['Landscape_8.jpg', 8],
            ['Portrait_1.jpg', 1],
            ['Portrait_2.jpg', 2],
            ['Portrait_3.jpg', 3],
            ['Portrait_4.jpg', 4],
            ['Portrait_5.jpg', 5],
            ['Portrait_6.jpg', 6],
            ['Portrait_7.jpg', 7],
            ['Portrait_8.jpg', 8],
        ];
    }

    /**
     * @dataProvider dataProviderImages
     * @param string $filename
     * @param int $orientation
     * @throws Exception
     */
    public function testFix(string $filename, int $orientation): void
    {
        $time                 = time();
        $inputFullFilePath    = $this->getInputImagesPath() . $filename;
        $inputFullFilePathTmp = $this->getInputImagesPath() . $time . '_' . $filename;
        copy($inputFullFilePath, $inputFullFilePathTmp);

        try {
            $iof = new ImageOrientationFixer($inputFullFilePathTmp);
            $iof->fix();
        } catch (Exception $e) {
        }

        self::assertFileExists($inputFullFilePathTmp);
        $exifData = exif_read_data($inputFullFilePathTmp, 'IFD0', 0);

        if (1 === $orientation) {
            self::assertEquals($orientation, $exifData['Orientation']);
        } else {
            self::assertFalse($exifData);
        }

        @unlink($inputFullFilePathTmp);
    }

    /**
     * @dataProvider dataProviderImages
     * @param string $filename
     * @param int $orientation
     */
    public function testFixWithOutput(string $filename, int $orientation): void
    {
        $inputFullFilePath  = $this->getInputImagesPath() . $filename;
        $outputFullFilePath = $this->getOutputImagesPath() . $filename;

        try {
            $iof = new ImageOrientationFixer($inputFullFilePath, $outputFullFilePath);
            $iof->fix();
        } catch (Exception $e) {
        }

        if (1 === $orientation) {
            self::markTestSkipped('no action');
        } else {
            self::assertFileExists($outputFullFilePath);
            $exifData = exif_read_data($outputFullFilePath, 'IFD0', 0);
            self::assertFalse($exifData);
        }

        @unlink($outputFullFilePath);
    }

    /**
     * INPUT_IMAGES is defined in phpunit.xml
     * @return string
     */
    public function getInputImagesPath(): string
    {
        return __DIR__ . INPUT_IMAGES;
    }

    /**
     * OUTPUT_IMAGES is defined in phpunit.xml
     * @return string
     */
    public function getOutputImagesPath(): string
    {
        $path = __DIR__ . OUTPUT_IMAGES;
        if (!file_exists($path)) {
            mkdir($path);
        }

        return $path;
    }
}
