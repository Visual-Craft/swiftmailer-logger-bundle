<?php

namespace TweedeGolf\SwiftmailerLoggerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class TweedeGolfSwiftmailerLoggerExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // add loggers to send listener
        if ($container->hasDefinition('tweedegolf_swiftmailer_logger.send_listener')) {
            $def = $container->getDefinition('tweedegolf_swiftmailer_logger.send_listener');

            if ($config['loggers']['entity_logger']['enabled']) {
                $def->addMethodCall('addLogger', array(new Reference('tweedegolf_swiftmailer_logger.entity_logger')));
            }

            unset($def);
        }
    }
}
