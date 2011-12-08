# PHAR Packager

If you want you can use the phar archive instead sources. 
The package engage the autoloader by it self and allow you
to stay focus on you app without consider the Zend library and
boot the library manually.

```php
<?php
require_once 'upcloo-sdk.phar';

$manager = UpCloo_Manager::getInstance();
$manager->setCredential("username", "sitekey", "password");

$manager->get("135");
```

## Engage the packager

For engage the packager you have to use the ```compile``` program.

```
$ ./compile
```

This command generate the ```upcloo-sdk.phar``` file and you can use 
directly.