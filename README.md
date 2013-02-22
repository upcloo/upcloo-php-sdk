# UpCloo PHP SDK [![Build Status](https://travis-ci.org/wdalmut/upcloo-php-sdk.png?branch=master)](https://travis-ci.org/wdalmut/upcloo-php-sdk)

This library is intended for working with PHP 5.2+

## Getting started

```php
<?php
$manager = UpCloo_Manager::getInstance();
//Setting up credentials
$manager->setCredential("en-xx00XXxxx");

//Get correlation of the indexed content
$manager->get("http://www.domain.tld/folder/content.html");
```

See [wiki pages](upcloo-php-sdk/wiki) for more information.

## Library autoloader

This library provides a simple autoloader. You can
require for ```UpCloo/Autoloader.php```. That's it. See this
running example:

```php
<?php
require_once 'path/to/UpCloo/Autoloader.php';

//now the library is ready.
```

The UpCloo PHP Autoloader use a classmap method for links all
dependencies.

## How to use the PHAR package

First of all download the latest phar archive from the download section.
After that you can use like this:

```php
<?php
require_once 'upcloo-sdk.phar';

$manager = UpCloo_Manager::getInstance();
$manager->setCredential("it-xx00XXxxx");

```

Consider that the PHAR archive autoload all the library by it self.

## Running Tests & Reports

If you want to run tests and get the reports of coverage you can
simply use the ```phpunit```.

For more information on ```phpunit``` consider
[the project page](http://www.phpunit.de/manual/current/en/)

```
$ phpunit
```

