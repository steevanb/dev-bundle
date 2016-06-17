Composer
--------
```
composer require steevanb/dev-bundle 3.0.*
```

Or add it manually, and then execute composer update steevanb/dev-bundle :

```json
# composer.json
{
    "require": {
        "steevanb/dev-bundle": "3.0.*"
    }
}
```

Add bundle to your AppKernel
----------------------------

```php
# app/AppKernel.php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        if ($this->getEnvironment() == 'dev') {
            $bundles[] = new steevanb\DevBundle\DevBundle();
        }
    }
}
```

[Back to index](../../README.md)
