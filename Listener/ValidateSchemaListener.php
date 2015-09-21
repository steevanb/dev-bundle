<?php

namespace steevanb\DevBundle\Listener;

use Doctrine\Bundle\DoctrineBundle\DataCollector\DoctrineDataCollector;
use steevanb\DevBundle\Exception\InvalidMappingException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ValidateSchemaListener
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
     * @param Response $response
     * @throws InvalidMappingException
     */
    protected function assertSchemaIsValid(Response $response)
    {
        $this->dataCollector->collect($this->requestStack->getCurrentRequest(), $response);
        $errors = $this->dataCollector->getMappingErrors();
        if (count($errors) > 0) {
            reset($errors);
            $em = key($errors);
            $entity = array_shift($errors);
            if (in_array(key($entity), $this->excludedEntities) === false) {
                $error = array_shift($entity)[0];

                foreach ($this->excludedProperties as $excludedProperty) {
                    foreach ($this->schemaValidatorMessages as $schemaValidatorMessage) {
                        $messageStart = sprintf($schemaValidatorMessage, $excludedProperty);
                        if ($messageStart === substr($error, 0, strlen($messageStart))) {
                            return ;
                        }
                    }
                }

                throw new InvalidMappingException($em, $error);
            }
        }
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

    public function onKernelRequest()
    {
        $this->assertSchemaIsValid(new Response());
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $this->assertSchemaIsValid($event->getResponse());
    }
}
