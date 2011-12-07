# UpCloo PHP SDK

## Proposal
This is the proposal for implementation

### Boot the library
Set up the sitekey, username and password before use the library

```php
<?php
//Username, SiteKeys, Password and virtual site keys
Upcloo_Manager::setCredential("username", "sitekey", "password", ...);
```

### Publish method - index new contents or updates
When you want to put a content 

```php
<?php
// Index a new content or update and exists one.
UpCloo_Manager::index(
    array(
        'id' => 'post_124',                     //unique id of cotent
        'type' => 'post',                       //Your type
        'title' => 'title',                     //Your title
        'summary' => 'this is summary',         //Summary
        'image' => 'http://pro.ltd/link.png',   //Image link
        'link' => 'http://pro.ltd/link',          //The link
        'tags' => array(                        //List of tags
            'example',
            'another'
        ),
        'categories' => array(                  //List of categories
            'one',
            'two'
        )
        // other dynamic fields...
    )
);
```

### Retrive indexed contents
Retrive and indexed content or ask for it...

```php
<?php
// Get related contents from main sitekey
$correlation = UpCloo_Manager::get("post_124");

// Get related contents from a virtual sitekey
$correlation = UpCloo_Manager:.get("post_124", $virtualSiteKey);
```

### Related models

The concept of SDK is to provide supports for merge operation of different requests
and other features.

 * Using models as arrays
 * Merge operation of different requests
 
```php
<?php
// Mix responses with a rule
$mixed = UpCloo_Manager::mix($model, ..., UpCloo_Manager::RANDOM);

// Mix responses with a rule and limit each model on two elements
$mixed = UpCloo_Manager::mix($model, ..., UpCloo_Manager::RANDOM, 2);

// Mix responses with a rule and limit models
$mixed = UpCloo_Manager::mix($model, ..., UpCloo_Manager::RANDOM, array(2, 5, 2));

```

Merge operation support different mixing:

 * Random mix
 * Order mix
 * Reverse order mix 
 * Order by a field