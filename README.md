# UpCloo PHP SDK [![Build Status](https://secure.travis-ci.org/wdalmut/upcloo-php-sdk.png)](http://travis-ci.org/wdalmut/upcloo-php-sdk?branch=master)

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

See [wiki pages](upcloo-php-sdk/wiki) for more information.

## Search Queries

Now search query are handled by this library 

```php
<?php
$searchQuery = $manager->search()->query("Text to search");

$results = $manager->get($searchQuery);
```

### Complex queries

Search queries works chaining objects. You can start a new query
using ```search()``` method.

```php
<?php
$searchQuery = $manager->search()
    ->relevancy() //Force date relevancy
    ->query("Text to search")
    ->facet("category")
    ->range() //maybe much more complex
    ->filterBy("category", "Web")
    ->network("a-partner-sitekey")
;

$results = $manager->get($searchQuery);
```

### Query

The query is what you want to search, it could be a sentence or
simple a word but not empty.

### Relevancy

Relevancy is the boost operator that indicates that this query
must works with a relevancy on a particular rule. Possible 
values actually are only: date and default.

 * Date indicates that are better new contents (using ```publish_date```
field)
 * Default for a natural query execution.
 
### Filter By

This method indicates that you want to reduce your result set. You
can chain this operator.

```php
<?php
$search->filterBy("a", "b")->filterBy("c", "d");
```

### Facet

Facet operator is the "group by" and "count". You can chain this
operator.

```php
<?php
$search->facet("category")->facet("author");
```

### Network

If you have a network of sites that you query not only your
repository but involves other partner indexes for having more
results. You can chain this operator

```php
<?php
$search->network("first")->network("second");
``` 

Is not useful including your sitekey because the sistem involve
it by itself.

### Ranges

Range queries is a group and count with filter. It's a complex query. 
When you are asking for a range query the system filter results automatically on 
your range, after that group and counting elements into each group.
That's enabled your software for move users into a fine grain searching 
system.  

Here the range method prototype

```php
<?php
public function range($type=self::RANGE_DATE, 
            $field="publish_date", 
            $gap="1", 
            $direction=self::DIRECTION_FORWARD, 
            $from="1900-01-01T00:00:00Z", 
            $to=self::NOW, 
            $value=self::RANGE_DATE_YEAR);
```

Using into a call

```php
<?php
//Backward range query
$searchQuery = $manager->search()
    ->query("Text to search")
    ->range("date", "publish_date", 2, "-", "NOW", "2000-01-01T00:00:00Z")
    ->filterBy("category", "meteo")
;

$results = $manager->get($searchQuery);
```

You can chain this operator.

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
```

