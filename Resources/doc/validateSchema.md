Validate schema
---------------

You can set [configuration](configuration.md) to throws steevanb\Exception\InvalidMappingException
when mapping informations are invalid.

You can exclude entities, or entity properties.

Doctrine schema validation will be executed on each request (kernel.request or kernel.response event), only for main request.
It takes some time to retrieve metadatas, if it's not cached (default behavior in dev environment).

[Back to index](../../README.md)
