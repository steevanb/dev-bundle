### [5.0.1](../../compare/5.0.0...5.0.1) - 2018-08-29

- [@ZeMarine](https://github.com/zemarine) Fix filemtime() call for strict_types

### [5.0.0](../../compare/4.1.1...5.0.0) - 2018-08-28

- Fix symfony 4 dependency : remove symfony/symfony, add good ones
- PHP 7.1 syntax

### [4.1.1](../../compare/4.1.0...4.1.1) - 2018-08-23

- [@ZeMarine](https://github.com/zemarine) Allow Symfony ^4.0

### [4.1.0](../../compare/4.0.0...4.1.0) - 2016-08-08

- Add Symfony WebProfiler panel : Loaded, who show loaded classes, services etc

### [4.0.0](../../compare/3.0.0...4.0.0) - 2016-26-09

- Refactor since beginning Doctrine schema validation
   - Remove _SessionCache_
   - Validate mapping only when a mapping file is modified
- Remove useless _steevanb\Exception\TranslationNotFoundException_
- Add _dev.validate_schema.disabled_urls_ configuration

### [3.0.0](../../compare/2.1.1...3.0.0) - 2016-16-17

- Change Symfony required version for translation not found validator: ^2.6|^3.0 instead of ^2.3
- Refactor since beginning translation not found validator :
   - Remove _steevanb\DevBundle\Translation_
   - Use _Symfony\Component\Translation\DataCollectorTranslator_ to retrieve missings translations
   - Now throws a s_teevanb\Exception\TranslationsNotFoundException_, instead of _steevanb\Exception\TranslationNotFoundException_
- Rename _steevanb\DevBundle\Listener_ namespace to _steevanb\DevBundle\EventListener_

### [2.1.1](../../compare/2.1.0...2.1.1) - 2016-06-11

- Do not validate Doctrine schema on sub-requests

### [2.1.0](../../compare/2.0.0...2.1.0) - 2016-01-19

- Rename schema validation listener _devbundle.validateschema_ to _dev.validateschema.listener_
- _SessionCache_ will not automatically scan bundles to find modified files, it will now use configuration
- Add _dev.validate_schema.paths_ configuration
- Add _dev.validate_schema.bundles.enabled_ and _dev.validate_schema.bundles.bundles_ configuration
- Add _SessionCache::addPathToScan()_ and _SessionCache::addBundleToScan()_

### [2.0.0](../../compare/1.1.5...2.0.0) - 2015-12-09

- Change configuration tree
- Add _dev.translation_not_found.allow_callbacks_ configuration

### [1.1.5](../../compare/1.1.4...1.1.5) - 2015-12-04

- Fix Symfony 2.8 deprecated and Symfony 3.0 compatibility for yaml files

### [1.1.4](../../compare/1.1.3...1.1.4) - 2015-20-26

- Schema validation only when an ORM file is modified

### [1.1.3](../../compare/1.1.2...1.1.3) - 2015-10-05

- Fix schema validation, now validate all mappings, instead of just thoe used in actual Request

### [1.1.2](../../compare/1.1.1...1.1.2) - 2015-10-02

- Fix empty _validate_schema_ configuration

### [1.1.1](../../compare/1.1.0...1.1.1) - 2015-10-01

- Create _ValidateSchemaService_, and fix schema validation with _ValidateSchemaService::assertSchemaIsValid()_

### [1.1.0](../../compare/1.0.0...1.1.0) - 2015-09-21

- Add schema validation, who throws _steevanb\DevBundle\Exception\InvalidMappingException_ on mapping error

### 1.0.0 - 2015-09-21

- Create bundle
- Add _steevanb\Exception\TranslationNotFoundException_
- Add _steevanb\Translation\Translator_
- Add _translation_not_found_ configuration, to throws _TranslationsNotFoundException_

[Back to index](README.md)
