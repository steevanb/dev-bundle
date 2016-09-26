<?php

namespace steevanb\DevBundle\Service;

use Doctrine\ORM\Tools\SchemaValidator;
use steevanb\DevBundle\Exception\InvalidMappingException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;

class ValidateSchemaService
{
    /** @var RegistryInterface */
    protected $doctrine;

    /** @var KernelInterface */
    protected $kernel;

    /** @var array */
    protected $excludedEntities = array();

    /** @var array */
    protected $excludedProperties = array();

    /** @var string[] */
    protected $mappingPaths = [];

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
     * @param KernelInterface $kernel
     */
    public function __construct(RegistryInterface $doctrine, KernelInterface $kernel)
    {
        $this->doctrine = $doctrine;
        $this->kernel = $kernel;
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
     * @param string $path
     * @return $this
     */
    public function addMappingPath($path)
    {
        if (in_array($path, $this->mappingPaths) === false) {
            $this->mappingPaths[] = $path;
        }

        return $this;
    }

    /**
     * @param string $bundle
     * @return $this
     */
    public function addMappingBundle($bundle)
    {
        $path = $this->kernel->getBundle($bundle)->getPath();
        $path .= DIRECTORY_SEPARATOR . 'Resources';
        $path .= DIRECTORY_SEPARATOR . 'config';
        $path .= DIRECTORY_SEPARATOR . 'doctrine';
        $this->addMappingPath($path);

        return $this;
    }

    /**
     * @throws InvalidMappingException
     */
    public function assertSchemaIsValid()
    {
        if ($this->needValidate()) {
            foreach ($this->doctrine->getEntityManagers() as $managerName => $manager) {
                $validator = new SchemaValidator($manager);
                foreach ($validator->validateMapping() as $entity => $errors) {
                    $this->assertAuthorizedMappingErrors($managerName, $entity, $errors);
                }
            }

            $this->saveLastValidateTimestamp();
        }
    }

    /**
     * @return string
     */
    protected function getLastMappingValidateFilePath()
    {
        return $this->kernel->getCacheDir() . DIRECTORY_SEPARATOR . 'dev_bundle_last_mapping_validate';
    }

    /**
     * @return bool
     */
    protected function needValidate()
    {
        $lastValidateTimestamp = $this->getLastValidateTimestamp();
        $return = false;

        foreach ($this->mappingPaths as $path) {
            if (is_dir($path)) {
                $finder = new Finder();
                foreach ($finder->in($path)->files() as $file) {
                    if (filemtime($file) >= $lastValidateTimestamp) {
                        $return = true;
                        continue 2;
                    }
                }
            }
        }

        return $return;
    }

    /**
     * @return $this
     */
    protected function saveLastValidateTimestamp()
    {
        $filePath = $this->getLastMappingValidateFilePath();
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        file_put_contents($filePath, '');

        return $this;
    }

    /**
     * @return int
     */
    protected function getLastValidateTimestamp()
    {
        $filePath = $this->getLastMappingValidateFilePath();

        return file_exists($filePath) ? filemtime($filePath) : 0;
    }

    /**
     * @param string $managerName
     * @param string $entityName
     * @param array $errors
     * @return $this
     * @throws InvalidMappingException
     */
    protected function assertAuthorizedMappingErrors($managerName, $entityName, array $errors)
    {
        if (in_array($entityName, $this->excludedEntities) === false) {
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

        return $this;
    }
}
