Configuration
-------------

```yml
# app/config/config.yml
dev:
    # throws steevanb\DevBundle\Exception\TranslationsNotFoundException on translations not found
    translation_not_found:
        enabled: [TRUE|false]
        # allow Translator to search your translation in fallbacks, or not
        allow_fallbacks: [true|FALSE]
    # validate Doctrine schema
    validate_schema:
        enabled: [TRUE|false]
        disabled_urls:
            - /_wdt
            - /_profiler/
            - /_errors
        event: [KERNEL.REQUEST|kernel.response]
        excludes:
            - Foo\Bar\Entity
            - Foo\Bar\Entity#property
        bundles:
            # enable scan of Resources/config/doctrine dir of bundles
            enabled: [TRUE|false]
            # bundles to scan. if empty or not specified, will add all installed bundles
            bundles:
                - FooBundle
                - BarBundle
```

Validate schema section
-----------------------

You can exclude entities or properties validation, by adding values in dev.validate_schema.excludes array.

If value is a fully classified entity, all validation errors on this entity will be ignored.

If value if a fully qualifier entity, plus a property (ex : Foo\Bar\Entity#property),
only validation errors on this property will be ignored.

[Back to index](../../README.md)
