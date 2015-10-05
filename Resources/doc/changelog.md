1.1.3 (2015-10-05)
------------------

- Fix schema validation, now validate all mappings, not just those used in Request scope

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
