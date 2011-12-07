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

### Publish method

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
        'link' => 'http:pro.ltd/link',          //The link
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

### Retrive indexed content

Retrive and indexed content or ask for it...

```php
<?php
// Get related contents from main sitekey
$correlation = UpCloo_Manager::get("post_124");

$correlation = UpCloo_Manager:.get("post_124", $virtualSiteKey);
```