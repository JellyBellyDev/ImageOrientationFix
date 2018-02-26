<?php

class Image
{
    /** @var  string */
    private $filePathInput = null;
    private $mimeType = null;
    private $exifData = null;
    private $orientation = null;
    private $extension = null;
    private static $mimeTypesValid = array(
        'jpe' => 'image/jpe',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpg',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
    );

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
     * @throws Exception
     */
    private function setFilePathInput($filePathInput)
    {
        if (!file_exists($filePathInput)) {
            throw new Exception('FilePathInput not exists');
        }
        $this->filePathInput = $filePathInput;
    }

    /**
     * @return string
     */
    public function getFilePathInput()
    {
        return $this->filePathInput;
    }

    /**
     * Set the mime type
     */
    private function setMimeType()
    {
        $mimeType = MimeType::get($this->getFilePathInput());
        if (empty($mimeType) || !in_array($mimeType, self::$mimeTypesValid)) {
            throw new Exception("$mimeType: mimeType not valid");
        }
        $this->mimeType = $mimeType;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set all the exif data from the file
     */
    private function setExifData()
    {
        $exifData = exif_read_data($this->getFilePathInput(), 'IFD0', 0);
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
    private function setOrientation()
    {
        $exifData = $this->getExifData();
        if ($exifData && is_array($exifData) && array_key_exists('Orientation', $exifData)) {
            if (1 <= $exifData['Orientation'] && $exifData['Orientation'] <= 8) {
                $this->orientation = ($exifData && isset($exifData['Orientation'])) ? $exifData['Orientation'] : false;
            }
        }
    }

    /**
     * @return int
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    /**
     * Set the extension of the image
     */
    private function setExtension()
    {
        $r = array_keys(self::$mimeTypesValid, $this->getMimeType());
        if (!empty($r) && isset($r[0])) {
            $this->extension = $r[0];
        }
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }
}
