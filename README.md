ImageOrientationFix
===================

This repository contains a php class that fix image orientation by exif data with the method [exif_read_data](http://it2.php.net/manual/en/function.exif-read-data.php)

[![Build Status](https://travis-ci.org/JellyBellyDev/ImageOrientationFix.svg?branch=master)](https://travis-ci.org/JellyBellyDev/ImageOrientationFix)
[![Latest Stable Version](https://poser.pugx.org/jellybellydev/image-orientation-fix/v/stable)](https://packagist.org/packages/jellybellydev/image-orientation-fix)
[![Total Downloads](https://poser.pugx.org/jellybellydev/image-orientation-fix/downloads)](https://packagist.org/packages/jellybellydev/image-orientation-fix)
[![composer.lock](https://poser.pugx.org/jellybellydev/image-orientation-fix/composerlock)](https://packagist.org/packages/jellybellydev/image-orientation-fix)
[![License](https://poser.pugx.org/jellybellydev/image-orientation-fix/license)](https://packagist.org/packages/jellybellydev/image-orientation-fix)

## How to use

```bash
composer require jellybellydev/image-orientation-fix
```

```php
$iof = new ImageOrientationFix('foo.jpg');
$iof->fix();
```

## Credits

Thanks to [recurser](https://github.com/recurser) for the [image example](https://github.com/recurser/exif-orientation-examples)
