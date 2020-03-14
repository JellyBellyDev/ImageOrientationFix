<?php
/*
 * Copyright (c) 2014-2020 Andrea Giannantonio
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ImageOrientationFix;

use Exception;

/**
 * Class ImageOrientationFixer
 * @package ImageOrientationFix
 */
class ImageOrientationFixer
{
    private $image;
    private $filePathOutput;
    private $resourceImage;
    private $resourceImageFixed;

    public function __construct($filePathInput, $filePathOutput = false)
    {
        if (!function_exists('imageflip')) {
            //only php < 5.5
            if (!defined('IMG_FLIP_HORIZONTAL')) {
                define('IMG_FLIP_HORIZONTAL', 1);
            }
            if (!defined('IMG_FLIP_VERTICAL')) {
                define('IMG_FLIP_VERTICAL', 2);
            }
            if (!defined('IMG_FLIP_BOTH')) {
                define('IMG_FLIP_BOTH', 3);
            }
        }

        try {
            $this->image = new Image($filePathInput);
            $this->setFilePathOutput($filePathOutput);
        } catch (Exception $e) {
            exit;
        }
    }

    /**
     * Function manager to fix orientation image
     * @return bool
     * @throws Exception
     */
    public function fix()
    {
        try {

            // If we don't get any exif data at all, then we may as well stop now
            if (!$this->image->getExifData()) {
                return false;
            }

            // do nothing if orientation cannot be determined
            if (null === $this->image->getOrientation()) {
                return false;
            }

            // correct orientation
            if (1 === $this->image->getOrientation()) {
                return true;
            }

            // Set the GD image resource for loaded image
            $this->setResourceImage();
            // If it failed to load a resource, give up
            if (null === $this->getResourceImage()) {
                throw new Exception('Unable load resource image');
            }

            // Set the GD image resource fixed
            $this->setResourceImageFixed();
            if (null === $this->getResourceImageFixed()) {
                throw new Exception('Unable fix image');
            }

            // Save the image fixed
            return $this->saveFix();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Set the GD image resource for loaded image
     */
    private function setResourceImage()
    {
        $this->resourceImage = null;
        switch ($this->image->getExtension()) {
            case 'png':
                $this->resourceImage = imagecreatefrompng($this->image->getFilePathInput());
                break;
            case 'jpg':
            case 'jpeg':
                $this->resourceImage = imagecreatefromjpeg($this->image->getFilePathInput());
                break;
            case 'gif':
                $this->resourceImage = imagecreatefromgif($this->image->getFilePathInput());
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
        switch ($this->image->getOrientation()) {
            // horizontal flip
            case 2:
                $this->resourceImageFixed = $this->executeImageFlip($this->getResourceImage(), IMG_FLIP_HORIZONTAL);
                break;

            // 180 rotate left
            case 3:
                $this->resourceImageFixed = imagerotate($this->getResourceImage(), 180, 0);
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
     * @throws Exception
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
        $temp   = imagecreatetruecolor($size_x, $size_y);
        $x      = imagecopyresampled($temp, $resourceImage, 0, 0, 0, ($size_y - 1), $size_x, $size_y, $size_x, 0 - $size_y);
        if (!$x) {
            throw new Exception('Unable to flip vertical image');
        }

        return $temp;
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
        $temp   = imagecreatetruecolor($size_x, $size_y);
        $x      = imagecopyresampled($temp, $resourceImage, 0, 0, ($size_x - 1), 0, $size_x, $size_y, 0 - $size_x, $size_y);
        if (!$x) {
            throw new Exception('Unable to flip horizontal image');
        }

        return $temp;
    }

    /**
     * Save the new image fixed
     * @return bool
     */
    private function saveFix()
    {
        //if isset file path output the location is file path output otherwise override exist file
        $location = $this->getFilePathOutput() ? $this->getFilePathOutput() : $this->image->getFilePathInput();

        $success = false;
        switch ($this->image->getExtension()) {
            case 'png':
                $success = imagepng($this->getResourceImageFixed(), $location);
                break;
            case 'jpg':
            case 'jpeg':
                $success = imagejpeg($this->getResourceImageFixed(), $location);
                break;
            case 'gif':
                $success = imagegif($this->getResourceImageFixed(), $location);
                break;
        }

        return $success;
    }

    /**
     * @param $filePathOutput
     * @throws Exception
     */
    public function setFilePathOutput($filePathOutput = false)
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
}
