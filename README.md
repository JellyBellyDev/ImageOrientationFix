# ImageOrientationFix

PHP library to fix image orientation by exif data with thanks to method [exif_read_data](http://it2.php.net/manual/en/function.exif-read-data.php)

[![Build Status](https://travis-ci.org/JellyBellyDev/ImageOrientationFix.svg?branch=master)](https://travis-ci.org/JellyBellyDev/ImageOrientationFix)
[![Latest Stable Version](https://poser.pugx.org/jellybellydev/image-orientation-fix/v/stable)](https://packagist.org/packages/jellybellydev/image-orientation-fix)
[![Total Downloads](https://poser.pugx.org/jellybellydev/image-orientation-fix/downloads)](https://packagist.org/packages/jellybellydev/image-orientation-fix)
[![composer.lock](https://poser.pugx.org/jellybellydev/image-orientation-fix/composerlock)](https://packagist.org/packages/jellybellydev/image-orientation-fix)
[![License](https://poser.pugx.org/jellybellydev/image-orientation-fix/license)](https://packagist.org/packages/jellybellydev/image-orientation-fix)
[![codecov](https://codecov.io/gh/JellyBellyDev/ImageOrientationFix/branch/master/graph/badge.svg?token=BB4JWMOI62)](undefined)

## Image Example

![after](images/after_and_before.png)


## How to install

```bash
composer require jellybellydev/image-orientation-fix
```


## How to use

```php
use ImageOrientationFix\ImageOrientationFixer;

$iof = new ImageOrientationFixer('foo.jpg');
$iof->fix();
```
or
```php
use ImageOrientationFix\ImageOrientationFixer;

$iof = new ImageOrientationFixer('/path/foo.jpg', '/path/foo_fixed.jpg');
$iof->fix();
```

## Contributing

Dependencies are managed through composer:

```
$ docker-compose up --build -d
$ docker-compose run php74 composer install
```


### Run phpunit:

```
$ docker-compose run php74 composer test
```


### Run php-cs-fixer
``` bash
docker-compose run php74 composer cs-fixer
```


## Changelog

Please refer to the [changelog notes](CHANGELOG.md).


## Credits

Thanks to [recurser](https://github.com/recurser) for the [image example](https://github.com/recurser/exif-orientation-examples)
