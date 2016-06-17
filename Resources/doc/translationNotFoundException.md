Translation not found exception
-------------------------------

You can set [configuration](configuration.md) to throws steevanb\Exception\TranslationsNotFoundException
at kernel.response event when at least one translation is not found.

All translations not found throws this exception, especially when fallbacks are used.

For example, if you have a messages.fr.yml in your bundle, and you try to translate a string with fr_FR locale,
exception will be throwned.

You can configure it with dev.translation_not_found.allow_fallbacks.

[Back to index](../../README.md)
