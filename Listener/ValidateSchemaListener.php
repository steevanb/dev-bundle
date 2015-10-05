<?php

namespace steevanb\DevBundle\Listener;

use steevanb\DevBundle\Service\ValidateSchemaService;

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

    public function onKernelRequest()
    {
        $this->validateSchema->assertSchemaIsValid();
    }

    public function onKernelResponse()
    {
        $this->validateSchema->assertSchemaIsValid();
    }
}
