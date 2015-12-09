<?php

namespace steevanb\DevBundle\Translation;

use steevanb\DevBundle\Exception\TranslationNotFoundException;
use Symfony\Bundle\FrameworkBundle\Translation\Translator as BaseTranslator;
use Symfony\Component\Config\ConfigCacheInterface;

class Translator extends BaseTranslator
{
    /**
     * @param string $id
     * @param string $locale
     * @param string $domain
     * @throws \Exception
     */
    protected function assertTranslationExists($id, $locale, $domain = 'messages')
    {
        $allowFallbacks = $this->container->getParameter('translator.allow_fallbacks');
        if ($this->getCatalogue($locale)->has($id, $domain, $allowFallbacks) === false) {
            $locale = ($locale === null) ? $this->getLocale() : $locale;
            throw new TranslationNotFoundException($id, $locale, $domain);
        }
    }

    /**
     * @param string|null $locale
     * @return MessageCatalogue
     */
    public function getCatalogue($locale = null)
    {
        return parent::getCatalogue($locale);
    }

    /**
     * @param string $locale
     * @param ConfigCacheInterface $cache
     * @throws \Exception
     */
    public function dumpCatalogue($locale, ConfigCacheInterface $cache)
    {
        parent::dumpCatalogue($locale, $cache);

        // now, change MessageCatalogue namespace in cache
        $content = file_get_contents($cache->getPath());
        $use = 'use Symfony\\Component\\Translation\\MessageCatalogue;';
        if (strpos($content, $use) !== 7) {
            throw new \Exception('Invalid MessageCatalogue cache format in file "' . $cache->getPath() . '".');
        }
        $newUse = 'use steevanb\\DevBundle\\Translation\\MessageCatalogue;';
        $newContent = substr_replace($content, $newUse, 7, strlen($use));
        $cache->write($newContent, $this->catalogues[$locale]->getResources());
    }

    /**
     * @param string $id
     * @param array $parameters
     * @param string $domain
     * @param string $locale
     * @return string
     */
    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        $this->assertTranslationExists($id, $locale, $domain);

        return parent::trans($id, $parameters, $domain, $locale);
    }

    /**
     * @param string $id
     * @param int $number
     * @param array $parameters
     * @param string $domain
     * @param string $locale
     * @return string
     * @throws \Exception
     */
    public function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null)
    {
        $this->assertTranslationExists($id, $locale, $domain);

        return parent::transChoice($id, $number, $parameters, $domain, $locale);
    }
}
