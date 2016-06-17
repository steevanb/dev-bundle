<?php

namespace steevanb\DevBundle\EventListener;

use steevanb\DevBundle\Exception\TranslationsNotFoundException;
use Symfony\Component\Translation\DataCollectorTranslator;

class TranslationsNotFoundListener
{
    /** @var DataCollectorTranslator */
    protected $dataCollectorTranslator;

    /** @var bool */
    protected $allowFallbacks = false;

    /**
     * @param DataCollectorTranslator $dataCollectorTranslator
     */
    public function __construct(DataCollectorTranslator $dataCollectorTranslator)
    {
        $this->dataCollectorTranslator = $dataCollectorTranslator;
    }

    /**
     * @param bool $allow
     * @return $this
     */
    public function setAllowFallbacks($allow)
    {
        $this->allowFallbacks = $allow;

        return $this;
    }

    /**
     * @throws TranslationsNotFoundException
     */
    public function assertAllTranslationsFound()
    {
        $missings = array();
        foreach ($this->dataCollectorTranslator->getCollectedMessages() as $message) {
            if ($message['state'] === DataCollectorTranslator::MESSAGE_MISSING) {
                $missings[] = $message;
            } elseif (
                $message['state'] === DataCollectorTranslator::MESSAGE_EQUALS_FALLBACK
                && $this->allowFallbacks === false
            ) {
                $message['locale'] = 'fallback ' . $message['locale'] . ' found ';
                $message['locale'] .= 'but not allowed by config dev.translation.allow_fallbacks)';
                $missings[] = $message;
            }
        }

        if (count($missings) > 0) {
            throw new TranslationsNotFoundException($missings);
        }
    }
}
