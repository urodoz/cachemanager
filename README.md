Urodoz CacheManager
============

Bundle to handle cache management for Symfony 2


Installation
------------

Update your composer.json file adding the Urodoz CacheManager bundle

```json
...
    "require": {
        "urodoz/cachemanager": "dev-master"
    },
...
```

And add the bundle to Symfony 2 AppKernel.php

```php
    $bundles = array(
        //...
        new Urodoz\Bundle\CacheBundle\UrodozCacheBundle(),
    );
```
