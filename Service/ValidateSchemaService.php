<?php

namespace steevanb\DevBundle\Service;

use Doctrine\Bundle\DoctrineBundle\DataCollector\DoctrineDataCollector;
use steevanb\DevBundle\Exception\InvalidMappingException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class ValidateSchemaService
{
    /** @var DoctrineDataCollector */
    protected $dataCollector;

    /** @var RequestStack */
    protected $requestStack;

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
     * @param DoctrineDataCollector $dataCollector
     * @param RequestStack $requestStack
     */
    public function __construct(DoctrineDataCollector $dataCollector, RequestStack $requestStack)
    {
        $this->dataCollector = $dataCollector;
        $this->requestStack = $requestStack;
    }

    /**
     * @param array $excludes
     * @return $this
     */
    public function setExcludes(array $excludes)
    {
        $this->excludedEntities = array();
        $this->excludedProperties = array();

        foreach ($excludes as $exclude) {
            if (strpos($exclude, '#') === false) {
                $this->excludedEntities[] = $exclude;
            } else {
                $this->excludedProperties[] = $exclude;
            }
        }

        return $this;
    }

    /**
     * @param Response|null $response
     * @throws InvalidMappingException
     */
    public function assertSchemaIsValid(Response $response = null)
    {
        if ($response === null) {
            $response = new Response();
        }
        $this->dataCollector->collect($this->requestStack->getCurrentRequest(), $response);
        $errors = $this->dataCollector->getMappingErrors();

        if (count($errors) > 0) {
            foreach ($errors as $em => $emErrors) {
                foreach ($emErrors as $entity => $entityErrors) {
                    foreach ($entityErrors as $entityError) {

                        if (in_array($entity, $this->excludedEntities) === false) {
                            foreach ($this->excludedProperties as $excludedProperty) {
                                foreach ($this->schemaValidatorMessages as $schemaValidatorMessage) {
                                    $messageStart = sprintf($schemaValidatorMessage, $excludedProperty);
                                    if ($messageStart === substr($entityError, 0, strlen($messageStart))) {
                                        continue 3;
                                    }
                                }
                            }

                            throw new InvalidMappingException($em, $entityError);
                        }
                    }
                }
            }
        }
    }
}
