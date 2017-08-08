<?php

namespace steevanb\DevBundle\DataCollector;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class LoadedCollector extends DataCollector
{
    /**
     * I know we should try to not have Container sa dependency
     * But i need it to get fresh data in collect()
     * I need Container, not ContainerInterface
     * @var Container
     */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /** @return string */
    public function getName()
    {
        return 'loaded_data_collector';
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            'declaredClasses' => get_declared_classes(),
            'declaredInterfaces' => get_declared_interfaces(),
            'declaredTraits' => get_declared_traits(),
            'definedConstants' => get_defined_constants(),
            'definedFunctions' => get_defined_functions()['user'],
            'services' => $this->container->getServiceIds(),
            'instantiatedServices' => $this->getInstantiatedServicesData(),
            'parameters' => $this->container->getParameterBag()->all(),
            'listeners' => $this->getListenersData()
        ];
    }

    /** @return array */
    public function getDeclaredClasses()
    {
        static $sorted = false;
        if ($sorted === false) {
            $sorted = true;
            sort($this->data['declaredClasses']);
        }

        return $this->data['declaredClasses'];
    }

    /** @return int */
    public function countDeclaredClasses()
    {
        return count($this->data['declaredClasses']);
    }

    /** @return array */
    public function getDeclaredInterfaces()
    {
        static $sorted = false;
        if ($sorted === false) {
            $sorted = true;
            sort($this->data['declaredInterfaces']);
        }

        return $this->data['declaredInterfaces'];
    }

    /** @return int */
    public function countDeclaredInterfaces()
    {
        return count($this->data['declaredInterfaces']);
    }

    /** @return array */
    public function getDeclaredTraits()
    {
        static $sorted = false;
        if ($sorted === false) {
            $sorted = true;
            sort($this->data['declaredTraits']);
        }

        return $this->data['declaredTraits'];
    }

    /** @return int */
    public function countDeclaredTraits()
    {
        return count($this->data['declaredTraits']);
    }

    /** @return array */
    public function getDefinedConstants()
    {
        static $sorted = false;
        if ($sorted === false) {
            $sorted = true;
            ksort($this->data['definedConstants']);
        }

        return $this->data['definedConstants'];
    }

    /** @return int */
    public function countDefinedConstants()
    {
        return count($this->data['definedConstants']);
    }

    /** @return array */
    public function getDefinedFunctions()
    {
        static $sorted = false;
        if ($sorted === false) {
            $sorted = true;
            sort($this->data['definedFunctions']);
        }

        return $this->data['definedFunctions'];
    }

    /** @return int */
    public function countDefinedFunctions()
    {
        return count($this->data['definedFunctions']);
    }

    /** @return array */
    public function getServiceIds(): array
    {
        static $sorted = false;
        if ($sorted === false) {
            $sorted = true;
            sort($this->data['services']);
        }

        return $this->data['services'];
    }

    /** @return int */
    public function countServiceIds()
    {
        return count($this->data['services']);
    }

    /** @return array */
    public function getParameters()
    {
        static $sorted = false;
        if ($sorted === false) {
            $sorted = true;
            ksort($this->data['parameters']);
        }

        return $this->data['parameters'];
    }

    /** @return int */
    public function countParameters()
    {
        return count($this->data['parameters']);
    }

    /** @return array */
    public function getListeners()
    {
        static $sorted = false;
        if ($sorted === false) {
            $sorted = true;
            ksort($this->data['listeners']);
        }

        return $this->data['listeners'];
    }

    /** @return int */
    public function countListeners()
    {
        $return = 0;
        foreach ($this->getListeners() as $listeners) {
            $return += count($listeners);
        }

        return $return;
    }

    /** @return array */
    public function getInstantiatedServices()
    {
        static $sorted = false;
        if ($sorted === false) {
            $sorted = true;
            ksort($this->data['instantiatedServices']);
        }

        return $this->data['instantiatedServices'];
    }

    /** @return int */
    public function countInstantiatedServices()
    {
        return count($this->data['instantiatedServices']);
    }

    /** @return array */
    protected function getListenersData()
    {
        $return = [];
        foreach ($this->container->get('event_dispatcher')->getListeners() as $eventId => $listeners) {
            $return[$eventId] = [];
            if (is_array($listeners[0])) {
                foreach ($listeners as $listener) {
                    $return[$eventId][] = get_class($listener[0]);
                }
            } else {
                foreach ($listeners as $listener) {
                    $return[$eventId][] = get_class($listener);
                }
            }
        }

        return $return;
    }

    /** @return array */
    protected function getInstantiatedServicesData()
    {
        $reflectionProperty = new \ReflectionProperty(get_class($this->container), 'services');
        $reflectionProperty->setAccessible(true);
        $return = [];
        foreach ($reflectionProperty->getValue($this->container) as $id => $service) {
            $return[$id] = get_class($service);
        }
        $reflectionProperty->setAccessible(false);

        return $return;
    }
}
