Urodoz CacheManager
============

Bundle to handle cache management for Symfony 2


Installation
------------

Update your composer.json file adding the Urodoz CacheManager bundle

```json
...
    "require": {
        "urodoz/cachemanager": "dev-master",
        "predis/predis": "0.8.*@dev"
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

Configuration
-------------

Update your config.yml file to configure memcache connections and/or redis connection (temporary in development for multiple redis servers)

```yml
urodoz_cache:
    memcache:
        servers: ["127.0.0.1:11211"]
    redis:
        servers: ["192.168.1.120:6379"]
```

Usage
-----

To store and retrieve data from memcache servers pool (as service)

```php
//Retrieve the service from the ContainerInterface
$cacheManager = $container->get("urodoz_cachemanager");
//Store value
$cacheManager->implementation("memcache")->set($key, $value, 3600);
//Retrieve value
$cacheManager->implementation("memcache")->get($key);
```

To store and retrieve data from the redis server (as service), you only need to change the implementation requested to cache manager service

```php
//Retrieve the service from the ContainerInterface
$cacheManager = $container->get("urodoz_cachemanager");
//Store value
$cacheManager->implementation("redis")->set($key, $value, 3600);
//Retrieve value
$cacheManager->implementation("redis")->get($key);
```


Indexes Keys
------------

Currently on development
