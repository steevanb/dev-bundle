4.0.0 (2016-26-09)
------------------

- Refactor since beginning Doctrine schema validation
   - Remove SessionCache
   - Validate mapping only when a mapping file is modified
- Remove useless steevanb\Exception\TranslationNotFoundException
- Add dev.validate_schema.disabled_urls configuration

3.0.0 (2016-16-17)
------------------

- Change Symfony required version for translation not found validator is now ^2.6|^3.0 instead of ^2.3
- Refactor since beginning translation not found validator :
   - Remove steevanb\DevBundle\Translation
   - Use Symfony\Component\Translation\DataCollectorTranslator to retrieve missings translations
   - Now throws a steevanb\Exception\TranslationsNotFoundException, instead of steevanb\Exception\TranslationNotFoundException
- Rename steevanb\DevBundle\Listener namespace to steevanb\DevBundle\EventListener

2.1.1 (2016-06-11)
------------------

- Do not validate Doctrine schema on sub-requests

2.1.0 (2016-01-19)
------------------

- Rename schema validation listener devbundle.validateschema to dev.validateschema.listener
- SessionCache will not automatically scan bundles to find modified files, it will now use configuration
- Add dev.validate_schema.paths configuration
- Add dev.validate_schema.bundles.enabled and dev.validate_schema.bundles.bundles configuration
- Add SessionCache::addPathToScan() and SessionCache::addBundleToScan()

2.0.0 (2015-12-09)
------------------

- [[Config](configuration.md)] Change configuration tree
- Add dev.translation_not_found.allow_callbacks configuration

1.1.5 (2015-12-04)
------------------

- Fix Symfony 2.8 deprecated and Symfony 3.0 compatibility for yaml files

1.1.4 (2015-20-26)
------------------

- Schema validation only when an ORM file is modified

1.1.3 (2015-10-05)
------------------

- Fix schema validation, now validate all mappings, instead of just thoe used in actual Request

1.1.2 (2015-10-02)
------------------

- Fix empty validate_schema configuration

1.1.1 (2015-10-01)
------------------

- Create ValidateSchemaService, and fix schema validation with ValidateSchemaService::assertSchemaIsValid()

1.1.0 (2015-09-21)
------------------

- Add schema validation, who throws steevanb\DevBundle\Exception\InvalidMappingException on mapping error

1.0.0 (2015-09-21)
------------------

- Create bundle
- Add steevanb\Exception\TranslationNotFoundException
- Add steevanb\Translation\Translator
- [[Config](configuration.md)] Add translation_not_found configuration, to throws TranslationsNotFoundException

[Back to index](../../README.md)
