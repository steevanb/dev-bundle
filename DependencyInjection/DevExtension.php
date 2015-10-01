<?php

namespace steevanb\DevBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class DevExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->parseTranslationConfig($config, $container);
        $this->parseValidateSchemaConfig($config, $container);
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     */
    protected function parseTranslationConfig(array $config, ContainerBuilder $container)
    {
        if ($config['translation_not_found']) {
            $container->setParameter('translator.class', 'steevanb\\DevBundle\\Translation\\Translator');
        }
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     */
    protected function parseValidateSchemaConfig(array $config, ContainerBuilder $container)
    {
        if ($config['validate_schema']['enabled']) {
            $service = $container->getDefinition('dev.validateschema');
            $service->addMethodCall('setExcludes', array($config['validate_schema']['excludes']));

            $listener = new Definition('steevanb\\DevBundle\\Listener\\ValidateSchemaListener');
            $listener->addArgument(new Reference('dev.validateschema'));

            if ($config['validate_schema']['event'] == 'kernel.request') {
                $event = 'kernel.request';
                $method = 'onKernelRequest';
            } else {
                $event = 'kernel.response';
                $method = 'onKernelResponse';
            }
            $listener->addTag('kernel.event_listener', array(
                'event' => $event,
                'method' => $method
            ));

            $container->setDefinition('devbundle.validateschema', $listener);
        }
    }
}
