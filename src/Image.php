<?php
/*
 * Copyright (c) 2014-2020 Andrea Giannantonio
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ImageOrientationFix;

use RuntimeException;

/**
 * Class Image
 * @package ImageOrientationFix
 */
class Image
{
    private ?string $filePathInput       = null;
    private ?string $mimeType            = null;
    private $exifData                    = null;
    private ?int $orientation            = null;
    private ?string $extension           = null;
    private static array $mimeTypesValid = [
        'jpe'  => 'image/jpe',
        'jpeg' => 'image/jpeg',
        'jpg'  => 'image/jpg',
        'tiff' => 'image/tiff',
        'tif'  => 'image/tiff',
    ];

    public function __construct($filePathInput)
    {
        $this->setFilePathInput($filePathInput);
        $this->setMimeType();
        $this->setExifData();
        $this->setOrientation();
        $this->setExtension();
    }

    /**
     * @param $filePathInput
     * @throws RuntimeException
     */
    private function setFilePathInput($filePathInput): void
    {
        if (!file_exists($filePathInput)) {
            throw new RuntimeException('FilePathInput not exists');
        }
        $this->filePathInput = $filePathInput;
    }

    /**
     * @return string
     */
    public function getFilePathInput(): string
    {
        return $this->filePathInput;
    }

    /**
     * Set the mime type
     */
    private function setMimeType(): void
    {
        $mimeType = MimeType::get($this->getFilePathInput());
        if (empty($mimeType) || !in_array($mimeType, self::$mimeTypesValid, true)) {
            throw new RuntimeException("$mimeType: mimeType not valid");
        }
        $this->mimeType = $mimeType;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * Set all the exif data from the file
     */
    private function setExifData(): void
    {
        $exifData = @exif_read_data($this->getFilePathInput(), 'IFD0', 0);
        if (!empty($exifData) && is_array($exifData)) {
            $this->exifData = $exifData;
        }
    }

    /**
     * @return array|false
     */
    public function getExifData()
    {
        return $this->exifData;
    }

    /**
     * Set the orientation of image
     */
    private function setOrientation(): void
    {
        $exifData = $this->getExifData();
        if ($exifData && is_array($exifData) && array_key_exists('Orientation', $exifData) && 1 <= $exifData['Orientation'] && $exifData['Orientation'] <= 8) {
            $this->orientation = $exifData['Orientation'];
        }
    }

    /**
     * @return int|null
     */
    public function getOrientation(): ?int
    {
        return $this->orientation;
    }

    /**
     * Set the extension of the image
     */
    private function setExtension(): void
    {
        $r = array_keys(self::$mimeTypesValid, $this->getMimeType());
        if (!empty($r) && isset($r[0])) {
            $this->extension = $r[0];
        }
    }

    /**
     * @return string|null
     */
    public function getExtension(): ?string
    {
        return $this->extension;
    }
}
