#ImageOrientationFix

PHP library to fix image orientation by exif data with thanks to method [exif_read_data](http://it2.php.net/manual/en/function.exif-read-data.php)

[![Build Status](https://travis-ci.org/JellyBellyDev/ImageOrientationFix.svg?branch=master)](https://travis-ci.org/JellyBellyDev/ImageOrientationFix)
[![Latest Stable Version](https://poser.pugx.org/jellybellydev/image-orientation-fix/v/stable)](https://packagist.org/packages/jellybellydev/image-orientation-fix)
[![Total Downloads](https://poser.pugx.org/jellybellydev/image-orientation-fix/downloads)](https://packagist.org/packages/jellybellydev/image-orientation-fix)
[![composer.lock](https://poser.pugx.org/jellybellydev/image-orientation-fix/composerlock)](https://packagist.org/packages/jellybellydev/image-orientation-fix)
[![License](https://poser.pugx.org/jellybellydev/image-orientation-fix/license)](https://packagist.org/packages/jellybellydev/image-orientation-fix)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/JellyBellyDev/ImageOrientationFix/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/JellyBellyDev/ImageOrientationFix/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/JellyBellyDev/ImageOrientationFix/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/JellyBellyDev/ImageOrientationFix/?branch=master)

## Example

| Input                                  | Output |
|---                                     |---                                       |
| ![after](images/input_landscape_3.jpg) | ![output](images/output_landscape_3.jpg) |


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


## Contributing

Dependencies are managed through composer:

```
$ docker-compose up --build -d
$ docker-compose run php71 composer install
```


### Run phpunit:

```
$ docker-compose run php71 composer test
```


### Run php-cs-fixer
``` bash
docker-compose run php71 composer cs-fixer
```


## Credits

Thanks to [recurser](https://github.com/recurser) for the [image example](https://github.com/recurser/exif-orientation-examples)
