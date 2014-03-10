ImageOrientationFix
===================

This repository contains a php class that fix image orientation by exif data with the method [exif_read_data](http://it2.php.net/manual/en/function.exif-read-data.php)

####Test work in progress!!!

[![Latest Stable Version](https://poser.pugx.org/jellybellydev/image-orientation-fix/v/stable.png)](https://packagist.org/packages/jellybellydev/image-orientation-fix) [![Total Downloads](https://poser.pugx.org/jellybellydev/image-orientation-fix/downloads.png)](https://packagist.org/packages/jellybellydev/image-orientation-fix) [![Latest Unstable Version](https://poser.pugx.org/jellybellydev/image-orientation-fix/v/unstable.png)](https://packagist.org/packages/jellybellydev/image-orientation-fix) [![License](https://poser.pugx.org/jellybellydev/image-orientation-fix/license.png)](https://packagist.org/packages/jellybellydev/image-orientation-fix)[![Build Status](https://travis-ci.org/JellyBellyDev/ImageOrientationFix.png?branch=master)](https://travis-ci.org/JellyBellyDev/ImageOrientationFix)

## How to use

```php
$iof = new ImageOrientationFix('foo.jpg');
$iof->fix();
```


## Credits

Thanks to [recurser](https://github.com/recurser) for the [image example](https://github.com/recurser/exif-orientation-examples)
