Validate schema
---------------

You can set [configuration](configuration.md) to throws steevanb\Exception\InvalidMappingException
when mapping informations are invalid.

You can exclude entity, or entity property.

Speed up schema validation
--------------------------

Doctrine schema validation will be executed on each request (onKernelRequest event).
It takes some time to retrieve metadatas, if it's not cached (default behavior in dev environment).

You can use steevanb\DevBundle\Cache\SessionCache to store metadatas, who will auto-refresh when doctrine mapping change.
It's not necessary to use validation schema feature, it will just take less time to do it.

Add this config to your app/config_dev.yml (and app/config_test.yml, and any other environment who use DevBundle) :

```php
doctrine:
    orm:
        metadata_cache_driver:
            type: service
            id: dev.session_cache
```

[Back to index](../../README.md)
