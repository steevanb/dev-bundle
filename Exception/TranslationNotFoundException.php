<?php

namespace steevanb\DevBundle\Exception;

class TranslationNotFoundException extends \Exception
{
    /**
     * @param string $id
     * @param int $locale
     * @param string $domain
     */
    public function __construct($id, $locale, $domain)
    {
        $message = 'Translation "' . $id . '" not found for domain "' . $domain . '", locale "' . $locale . '".';
        parent::__construct($message);
    }
}
