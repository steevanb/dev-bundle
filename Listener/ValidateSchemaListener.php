<?php

namespace steevanb\DevBundle\Listener;

use steevanb\DevBundle\Service\ValidateSchemaService;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

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
     * @throws \steevanb\DevBundle\Exception\InvalidMappingException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->validateSchema->assertSchemaIsValid();
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $this->validateSchema->assertSchemaIsValid($event->getResponse());
    }
}
