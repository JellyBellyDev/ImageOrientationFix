<?php
/*
 * Copyright (c) 2014 Andrea Giannantonio
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Class ImageOrientationFix
 * @see https://github.com/JellyBellyDev/FixImageOrientationByExif
 * @author Andrea Giannantonio a.giannantonio@gmail.com
 */
class ImageOrientationFix
{
    private $filePathInput;
    private $filePathOutput;
    private $exifData;
    private $orientation;
    private $extension;
    private $resourceImage;
    private $resourceImageFixed;

    public function __construct()
    {
        if (!function_exists('imageflip')) {
            //only php < 5.5
            define("IMG_FLIP_HORIZONTAL", 1);
            define("IMG_FLIP_VERTICAL", 2);
            define("IMG_FLIP_BOTH", 3);
        }
    }

    /**
     * Function manager to fix orientation image
     *
     * @param $filePathInput
     * @param bool $filePathOutput
     * @return bool
     * @throws Exception
     */
    public function fix($filePathInput, $filePathOutput = false)
    {
        try {

            if (!file_exists($filePathInput)) {
                throw new Exception('FilePathInput not exists');
            }

            if (!$this->setData($filePathInput, $filePathOutput)) {
                return false;
            }

            if ($this->getOrientation() == 1) {
                // correct orientation
                return true;
            }

            // Set the GD image resource for loaded image
            $this->setResourceImage();
            // If it failed to load a resource, give up
            if (is_null($this->getResourceImage())) {
                throw new Exception('Unable load resource image');
            }

            // Set the GD image resource fixed
            $this->setResourceImageFixed();
            if (is_null($this->getResourceImageFixed())) {
                throw new Exception('Unable fix image');
            }

            // Save the image fixed
            return $this->saveFix();

        } catch (Exception $e) {
            var_dump($e->getMessage());
            throw $e;
        }
    }

    /**
     * @param $filePathInput
     * @param $filePathOutput
     * @return bool
     */
    private function setData($filePathInput, $filePathOutput)
    {
        $this->setFilePathInput($filePathInput);
        $this->setFilePathOutput($filePathOutput);

        // Get all the exif data from the file
        $exifData = exif_read_data($filePathInput, 'IFD0', 0);
        $this->setExifData($exifData);

        // If we don't get any exif data at all, then we may as well stop now
        if (!$this->getExifData() || !is_array($this->getExifData())) {
            return false;
        }

        if (!array_key_exists('Orientation', $this->getExifData())) {
            return false;
        }
        $this->setOrientation($exifData['Orientation']);

        $mimeType = strtolower($exifData['MimeType']);
        $mt = explode("/", $mimeType);
        if (!isset($mt[1])) {
            return false;
        }
        $this->setExtension($mt[1]);

        return true;
    }

    /**
     * Set the GD image resource for loaded image
     */
    private function setResourceImage()
    {
        $this->resourceImage = null;
        switch ($this->getExtension()) {
            case "png":
                $this->resourceImage = imagecreatefrompng($this->getFilePathInput());
                break;
            case "jpg":
            case "jpeg":
                $this->resourceImage = imagecreatefromjpeg($this->getFilePathInput());
                break;
            case "gif":
                $this->resourceImage = imagecreatefromgif($this->getFilePathInput());
                break;
        }
    }

    /**
     * @return mixed
     */
    public function getResourceImage()
    {
        return $this->resourceImage;
    }

    /**
     * Set the resource image fixed
     */
    private function setResourceImageFixed()
    {
        $this->resourceImageFixed = null;
        switch ($this->getOrientation()) {
            // horizontal flip
            case 2:
                $this->resourceImageFixed = $this->executeImageFlip($this->getResourceImage(), IMG_FLIP_HORIZONTAL);
                break;

            // 180 rotate left
            case 3:
                $this->resourceImageFixed = imagerotate($this->getResourceImage(), 180, 0);;
                break;

            // vertical flip
            case 4:
                $this->resourceImageFixed = $this->executeImageFlip($this->getResourceImage(), IMG_FLIP_VERTICAL);
                break;

            // vertical flip + 90 rotate right
            case 5:
                $this->resourceImageFixed = $this->executeImageFlip($this->getResourceImage(), IMG_FLIP_VERTICAL);
                $this->resourceImageFixed = imagerotate($this->resourceImageFixed, -90, 0);
                break;

            // 90 rotate right
            case 6:
                $this->resourceImageFixed = imagerotate($this->getResourceImage(), -90, 0);
                break;

            // horizontal flip + 90 rotate right
            case 7:
                $this->resourceImageFixed = $this->executeImageFlip($this->getResourceImage(), IMG_FLIP_HORIZONTAL);
                $this->resourceImageFixed = imagerotate($this->resourceImageFixed, -90, 0);
                break;

            // 90 rotate left
            case 8:
                $this->resourceImageFixed = imagerotate($this->getResourceImage(), 90, 0);
                break;
        }
    }

    /**
     * @return mixed
     */
    public function getResourceImageFixed()
    {
        return $this->resourceImageFixed;
    }

    /**
     * @param $resourceImage
     * @param int $mode - possible parameters: IMG_FLIP_HORIZONTAL || IMG_FLIP_VERTICAL || IMG_FLIP_BOTH
     * @return resource
     */
    private function executeImageFlip($resourceImage, $mode)
    {
        if (function_exists('imageflip')) {
            //only php >= 5.5
            imageflip($resourceImage, $mode);
        } else {
            if ($mode == IMG_FLIP_VERTICAL || $mode == IMG_FLIP_BOTH) {
                $resourceImage = $this->flipVertical($resourceImage);
            }
            if ($mode == IMG_FLIP_HORIZONTAL || $mode == IMG_FLIP_BOTH) {
                $resourceImage = $this->flipHorizontal($resourceImage);
            }
        }

        return $resourceImage;
    }

    /**
     * Flip vertical
     * @param $resourceImage
     * @return resource
     * @throws Exception
     */
    private function flipVertical($resourceImage)
    {
        $size_x = imagesx($resourceImage);
        $size_y = imagesy($resourceImage);
        $temp = imagecreatetruecolor($size_x, $size_y);
        $x = imagecopyresampled($temp, $resourceImage, 0, 0, 0, ($size_y - 1), $size_x, $size_y, $size_x, 0 - $size_y);
        if ($x) {
            return $temp;
        } else {
            throw new Exception('Unable to flip vertical image');
        }
    }

    /**
     * Flip horizontal
     * @param $resourceImage
     * @return resource
     * @throws Exception
     */
    private function flipHorizontal($resourceImage)
    {
        $size_x = imagesx($resourceImage);
        $size_y = imagesy($resourceImage);
        $temp = imagecreatetruecolor($size_x, $size_y);
        $x = imagecopyresampled($temp, $resourceImage, 0, 0, ($size_x - 1), 0, $size_x, $size_y, 0 - $size_x, $size_y);
        if ($x) {
            return $temp;
        } else {
            throw new Exception('Unable to flip horizontal image');
        }
    }

    /**
     * Save the new image fixed
     * @return bool
     */
    private function saveFix()
    {
        //if isset file path output the location is file path output otherwise override exist file
        $location = $this->getFilePathOutput() ? $this->getFilePathOutput() : $location = $this->getFilePathInput();

        $success = false;
        switch ($this->getExtension()) {
            case "png":
                $success = imagepng($this->getResourceImageFixed(), $location);
                break;
            case "jpg":
            case "jpeg":
                $success = imagejpeg($this->getResourceImageFixed(), $location);
                break;
            case "gif":
                $success = imagegif($this->getResourceImageFixed(), $location);
                break;
        }
        return $success;
    }

    /**
     * @param mixed $filePathInput
     */
    public function setFilePathInput($filePathInput)
    {
        $this->filePathInput = $filePathInput;
    }

    /**
     * @return mixed
     */
    public function getFilePathInput()
    {
        return $this->filePathInput;
    }

    /**
     * @param mixed $filePathOutput
     */
    public function setFilePathOutput($filePathOutput)
    {
        $this->filePathOutput = $filePathOutput;
    }

    /**
     * @return mixed
     */
    public function getFilePathOutput()
    {
        return $this->filePathOutput;
    }

    /**
     * @param mixed $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param array|false $exifData
     */
    public function setExifData($exifData)
    {
        $this->exifData = $exifData;
    }

    /**
     * @return array|false
     */
    public function getExifData()
    {
        return $this->exifData;
    }

    /**
     * @param mixed $orientation
     */
    public function setOrientation($orientation)
    {
        $this->orientation = $orientation;
    }

    /**
     * @return mixed
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

} 