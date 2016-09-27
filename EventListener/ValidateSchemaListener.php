<?php

namespace steevanb\DevBundle\EventListener;

use steevanb\DevBundle\Service\ValidateSchemaService;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ValidateSchemaListener
{
    /** @var ValidateSchemaService */
    protected $validateSchema;

    /** @var array */
    protected $disabledUrls = array();

    /**
     * @param ValidateSchemaService $validateSchema
     * @param array $disabledUrls
     */
    public function __construct(ValidateSchemaService $validateSchema, array $disabledUrls)
    {
        $this->validateSchema = $validateSchema;
        $this->disabledUrls = $disabledUrls;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function validateSchema(GetResponseEvent $event)
    {
        if ($this->needValidate($event)) {
            $this->validateSchema->assertSchemaIsValid();
        }
    }

    /**
     * @param GetResponseEvent $event
     * @return bool
     */
    protected function needValidate(GetResponseEvent $event)
    {
        $return = false;

        if ($event->getRequestType() === HttpKernelInterface::MASTER_REQUEST) {
            $return = true;
            $urlParts = parse_url($event->getRequest()->getUri());
            foreach ($this->disabledUrls as $disabledUrl) {
                if (substr($urlParts['path'], 0, strlen($disabledUrl)) === $disabledUrl) {
                    $return = false;
                    continue;
                }
            }
        }

        return $return;
    }
}
