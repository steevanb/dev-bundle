Validate schema
---------------

You can set [configuration](configuration.md) to throws steevanb\Exception\InvalidMappingException
when mapping informations are invalid.

You can exclude entity, or entity property.

Installation
------------

Add this config to your app/config_dev.yml (and app/config_test.yml, and any other environment who use DevBundle) :

doctrine:
    orm:
        metadata_cache_driver:
            type: service
            id: dev.session_cache

It will use steevanb\DevBundle\Cache\SessionCache to store metadata, who will auto-refresh when doctrine mapping change.

[Back to index](../../README.md)
