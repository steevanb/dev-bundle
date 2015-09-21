Configuration
-------------

```yml
# app/config/config.yml
dev:
    # throws TranslationNotFoundException on translation not found
    translation_not_found: [TRUE|false]
    validate_schema:
        enabled: [TRUE|false]
        excludes:
            - Foo\Bar\Entity
            - Foo\Bar\Entity#property
```

Validate schema section
-----------------------

You can exclude entities or properties validation, by adding values in dev.validate_schema.excludes array.

If value is a fully classified entity, all validation errors on this entity will be ignored.

If value if a fully qualifier entity, plus a property (ex : Foo\Bar\Entity#property),
only validation errors on this property will be ignored.

[Back to index](../../README.md)
