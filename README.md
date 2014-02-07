ImageOrientationFix
===================

This repository contains a php class that fix image orientation by exif data with the method [exif_read_data](http://it2.php.net/manual/en/function.exif-read-data.php)

####Do not use this class!!! Work in progress!!!



## How to use

```php
$iof = new ImageOrientationFix();
$iof->fix('foo.jpg');
```


## Credits

Thanks to [recurser](https://github.com/recurser) for the [image example](https://github.com/recurser/exif-orientation-examples)
