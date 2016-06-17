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
    protected $excludedEntities = array();

    /** @var array */
    protected $excludedProperties = array();

    /**
     * Messages from Doctrine\ORM\Tools\SchemaValidator
     *
     * @var array
     */
    protected $schemaValidatorMessages = array(
        'The field \'%s\' ',
        'The field %s ',
        'The association %s ',
        'Cannot map association \'%s\' ',
        'The mappings %s and ',
        'If association %s '
    );

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
