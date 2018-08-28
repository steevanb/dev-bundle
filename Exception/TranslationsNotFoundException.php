<?php

declare(strict_types=1);

namespace steevanb\DevBundle\Exception;

class TranslationsNotFoundException extends \Exception
{
    public function __construct(array $messages)
    {
        $missings = array();
        foreach ($messages as $message) {
            $missing = '[ id : ' . $message['id'] . ', ';
            $missing .= 'domain : ' . $message['domain'] . ', ';
            $missing .= 'locale : ' . $message['locale'] . ' ]';

            $missings[] = $missing;
        }

        parent::__construct('Translations not found : ' . implode(', ', $missings));
    }
}
