<?php

namespace steevanb\DevBundle\Service;

use Doctrine\ORM\Tools\SchemaValidator;
use steevanb\DevBundle\Exception\InvalidMappingException;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ValidateSchemaService
{
    /** @var RegistryInterface */
    protected $doctrine;

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
     * @param RegistryInterface $doctrine
     */
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
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
     * @throws InvalidMappingException
     */
    public function assertSchemaIsValid()
    {
        foreach ($this->doctrine->getManagers() as $managerName => $manager) {
            $validator = new SchemaValidator($manager);
            foreach ($validator->validateMapping() as $entity => $errors) {
                if (in_array($entity, $this->excludedEntities)) {
                    continue;
                }
                foreach ($errors as $error) {
                    foreach ($this->excludedProperties as $excludedProperty) {
                        foreach ($this->schemaValidatorMessages as $schemaValidatorMessage) {
                            $messageStart = sprintf($schemaValidatorMessage, $excludedProperty);
                            if ($messageStart === substr($error, 0, strlen($messageStart))) {
                                continue 3;
                            }
                        }
                    }

                    throw new InvalidMappingException($managerName, $error);
                }
            }
        }
    }
}
