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
