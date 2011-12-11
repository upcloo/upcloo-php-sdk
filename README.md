# UpCloo PHP SDK

This library is intended for working with PHP 5.2+

## Getting started

```php
<?php
$manager = UpCloo_Manager::getInstance();
//Setting up credentials
$manager->setCredential("username", "password", "sitekey");

//Index a new content
$manager->index(
    array(
        'id' => '1243',
        'title' => 'Hello world',
        'link' => 'http://my-domain.ltd/hello-world'
    )
);

//Get correlation of the indexed content
$manager->get("1243");
```

See [wiki pages](wiki) for more information.

## Use the PHAR package

First of all download the latest phar archive from the download section.
After that you can use as this:

```php
<?php 
require_once 'upcloo-sdk.phar';

$manager = UpCloo_Manager::getInstance();
$manager->setCredential("username", "sitekey", "password");

```

Consider that the PHAR archive autoload all the library by it self.

## Running Tests & Reports

If you want to run tests and get the reports of coverage you can
simply use the ```phpunit```.

For more information on ```phpunit``` consider 
[the project page](http://www.phpunit.de/manual/current/en/) 

```
$ phpunit
PHPUnit 3.5.15 by Sebastian Bergmann.

.......

Time: 1 second, Memory: 4.50Mb

OK (7 tests, 20 assertions)

Generating code coverage report, this may take a moment.
```