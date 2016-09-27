<?php

namespace steevanb\DevBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\KernelEvents;

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

        $this
            ->parseTranslationConfig($config['translation_not_found'], $container)
            ->parseValidateSchemaConfig($config['validate_schema'], $container);
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     * @return $this
     */
    protected function parseTranslationConfig(array $config, ContainerBuilder $container)
    {
        if ($config['enabled']) {
            $definition = new Definition();
            $definition->setClass('steevanb\\DevBundle\\EventListener\\TranslationsNotFoundListener');
            $definition->addArgument(new Reference('translator'));
            $definition->addMethodCall('setAllowFallbacks', array($config['allow_fallbacks']));
            $definition->addTag('kernel.event_listener', array(
                'event' => KernelEvents::RESPONSE,
                'method' => 'assertAllTranslationsFound'
            ));

            $container->setDefinition('dev.translations_not_found', $definition);
        }

        return $this;
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     * @return $this
     */
    protected function parseValidateSchemaConfig(array $config, ContainerBuilder $container)
    {
        if ($config['enabled']) {
            $validateSchemaDefinition = $container->getDefinition('dev.validate_schema');

            foreach ($config['paths'] as $path) {
                $validateSchemaDefinition->addMethodCall('addMappingPath', array($path));
            }

            if ($config['bundles']['enabled']) {
                if (count($config['bundles']['bundles'])) {
                    $bundles = $config['bundles']['bundles'];
                } else {
                    $bundles = array_keys($container->getParameter('kernel.bundles'));
                }
                foreach ($bundles as $bundleName) {
                    $validateSchemaDefinition->addMethodCall('addMappingBundle', array($bundleName));
                }
            }

            $validateSchemaDefinition->addMethodCall('setExcludes', array($config['excludes']));

            $listener = new Definition('steevanb\\DevBundle\\EventListener\\ValidateSchemaListener');
            $listener->addArgument(new Reference('dev.validate_schema'));

            $listener->addTag('kernel.event_listener', array(
                'event' => ($config['event'] == 'kernel.request') ? 'kernel.request' : 'kernel.response',
                'method' => 'validateSchema'
            ));

            $container->setDefinition('dev.validate_schema.listener', $listener);
        }

        return $this;
    }
}
