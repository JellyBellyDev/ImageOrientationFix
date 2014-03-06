<?php

class Image
{
	/** @var  string */
	private $filePathInput;
	private $exifData;
	private $orientation = false;
	private $extension = false;

	public function __construct($filePathInput)
	{
		$this->setFilePathInput($filePathInput);
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
	 * Set all the exif data from the file
	 */
	private function setExifData()
	{
		$this->exifData = exif_read_data($this->getFilePathInput(), 'IFD0', 0);
	}

	/**
	 * @return array|false
	 */
	public function getExifData()
	{
		if (!$this->exifData || !is_array($this->exifData)) {
			return false;
		}
		return $this->exifData;
	}

	/**
	 *
	 */
	private function setOrientation()
	{
		if ($this->getExifData() && array_key_exists('Orientation', $this->getExifData())) {
			$exifData = $this->getExifData();
			$this->orientation = $exifData['Orientation'];
		}
	}

	/**
	 * @return mixed
	 */
	public function getOrientation()
	{
		return $this->orientation;
	}

	/**
	 *
	 */
	private function setExtension()
	{
		if ($this->getExifData() && array_key_exists('MimeType', $this->getExifData())) {
			$exifData = $this->getExifData();
			$mimeType = strtolower($exifData['MimeType']);
			$mt = explode("/", $mimeType);
			$this->extension = isset($mt[1]) ? $mt[1] : 'jpg';
		}
	}

	/**
	 * @return mixed
	 */
	public function getExtension()
	{
		return $this->extension;
	}
} 