<?php

namespace steevanb\DevBundle\Translation;

use steevanb\DevBundle\Exception\TranslationNotFoundException;
use Symfony\Bundle\FrameworkBundle\Translation\Translator as BaseTranslator;

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
        $translations = $this->getCatalogue($locale)->all($domain);
        if (array_key_exists($id, $translations) === false) {
            $locale = ($locale === null) ? $this->getLocale() : $locale;
            throw new TranslationNotFoundException($id, $locale, $domain);
        }
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
