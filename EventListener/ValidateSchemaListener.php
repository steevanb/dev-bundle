<?php

namespace steevanb\DevBundle\EventListener;

use steevanb\DevBundle\Service\ValidateSchemaService;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ValidateSchemaListener
{
    /** @var ValidateSchemaService */
    protected $validateSchema;

    /**
     * @param ValidateSchemaService $validateSchema
     */
    public function __construct(ValidateSchemaService $validateSchema)
    {
        $this->validateSchema = $validateSchema;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function validateSchema(GetResponseEvent $event)
    {
        if ($event->getRequestType() === HttpKernelInterface::MASTER_REQUEST) {
            $this->validateSchema->assertSchemaIsValid();
        }
    }
}
