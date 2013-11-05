Urodoz CacheManager
============

Bundle to handle cache management for Symfony 2


* [Installation](../../wiki/Installation)


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

Events
------

**Cache hit**

On cache hit the event *urodoz.events.cachehit* is dispatched on the Symfony 2 EventDispatcher. The event is from class : *Urodoz\Bundle\CacheBundle\Event\CacheHitEvent*.
Contains information about the key used, the content stored on the cache implementation and implementation used.

**Missed cache hit**

On cache hit the event *urodoz.events.missed_cachehit* is dispatched on the Symfony 2 EventDispatcher. The event is from class : *Urodoz\Bundle\CacheBundle\Event\MissedCacheHitEvent*.
Contains information about the key used and the implementation.

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

When calling to method implementation, it sets the active implementation on the CacheManager service. You can avoid to set again the implementation on the next calls

```php
//Storing on memcache
$cacheManager = $container->get("urodoz_cachemanager");
$cacheManager->implementation("memcache")->set($key1, $value1);
$cacheManager->set($key2, $value2);
$cacheManager->set($key3, $value3);
$cacheManager->set($key4, $value4);
//Retrieving from memcache
$data["one"] = $cacheManager->get($key2);
$data["two"] = $cacheManager->get($key3);
$data["three"] = $cacheManager->get($key4);
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

Prefix generation
-----------------

The event throwed to update the cache key is *urodoz.events.update_cachekey* , this is a sample configuration

```yml
urodoz_cache:
    memcache:
        servers: ["127.0.0.1:11211"]
    redis:
        servers: ["192.168.1.120:6379"]
```

Service configuration attached as listener:

```yml
    sample.cachePrefixGenerator:
        class: Sample\Bundle\CoreBundle\Service\CachePrefixGenerator
        calls:
            - ["setContainer", [@service_container]]
        tags:
            - { name: kernel.event_listener, event: urodoz.events.update_cachekey, method: onCacheKeyUpdate }
```

Service class:

```php
<?php

namespace Urodoz\Bundle\CacheBundle\Tests\Service\Mocks;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Urodoz\Bundle\CacheBundle\Event\UpdateCacheKeyEvent;

class PrefixGenerator implements ContainerAwareInterface
{

    //....

    public function onCacheKeyUpdate(UpdateCacheKeyEvent $event)
    {
        $event->addPrefix($this->container->getParameter("applicationName")."_");
        return $event;
    }

}
```

