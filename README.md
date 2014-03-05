ImageOrientationFix
===================

This repository contains a php class that fix image orientation by exif data with the method [exif_read_data](http://it2.php.net/manual/en/function.exif-read-data.php)

####Do not use this class!!! Work in progress!!!

[![Latest Stable Version](https://poser.pugx.org/jellybellydev/image-orientation-fix/v/stable.png)](https://packagist.org/packages/jellybellydev/image-orientation-fix) [![Total Downloads](https://poser.pugx.org/jellybellydev/image-orientation-fix/downloads.png)](https://packagist.org/packages/jellybellydev/image-orientation-fix) [![Latest Unstable Version](https://poser.pugx.org/jellybellydev/image-orientation-fix/v/unstable.png)](https://packagist.org/packages/jellybellydev/image-orientation-fix) [![License](https://poser.pugx.org/jellybellydev/image-orientation-fix/license.png)](https://packagist.org/packages/jellybellydev/image-orientation-fix)

## How to use

```php
$iof = new ImageOrientationFix();
$iof->fix('foo.jpg');
```


## Credits

Thanks to [recurser](https://github.com/recurser) for the [image example](https://github.com/recurser/exif-orientation-examples)
