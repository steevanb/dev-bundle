<?php

namespace steevanb\DevBundle\Translation;

use Symfony\Component\Translation\MessageCatalogue as BaseMessageCatalogue;

/**
 * MessageCatalogue.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class MessageCatalogue extends BaseMessageCatalogue
{
    /**
     * @param string $id
     * @param string $domain
     * @param bool $allowFallbacks
     * @return bool
     */
    public function has($id, $domain = 'messages', $allowFallbacks = true)
    {
        $messages = $this->all($domain);
        if (isset($messages[$id])) {
            return true;
        }

        if ($allowFallbacks && null !== $this->getFallbackCatalogue()) {
            return $this->getFallbackCatalogue()->has($id, $domain);
        }

        return false;
    }
}
