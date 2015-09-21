Translation not found exception
-------------------------------

You can set [configuration](configuration.md) to throws steevanb\Exception\TranslationNotFoundException
when a translation is not found.

All translations not found throws this exception, especially fallbacks.

For example, if you have a messages.fr.yml in your bundle, and you try to translate a string with fr_FR locale,
exception will be throwned.

[Back to index](../../README.md)
