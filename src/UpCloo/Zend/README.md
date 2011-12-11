# The Zend path

If you use the Zend Framework you can use this section
for bootstraping the library and other things.

## Load Resources

In your ```application.ini``` you have to load the UpCloo
resources namespace and the UpCloo general namespace as follow

```
autoloadernamespaces.UpCloo = "UpCloo_"
```

After that you have to load the resource section

```
;Consider the real path where you put UpCloo library
pluginPaths.UpCloo_Zend_Application_Resource = APPLICATION_PATH "/library/UpCloo/Zend/Application/Resource"
```

Now you are ready for load it as follow:

```
resources.upcloo.username = "your-username"
resources.upcloo.password = "your-password"
resources.upcloo.sitekey = "your-sitekey"
resources.upcloo.virtuals.mykey = "this-vsitekey"
resources.upcloo.virtuals.ankey = "another-vsitekey"
```

## Bootstrap resource in test

If you want a read-only UpCloo instance for your testing scope you can use the
```UpClooMock``` client.

```
[testing : production]
resources.upcloo.client = "UpClooMock"

[development : production]
resources.upcloo.client = "UpClooMock"
```