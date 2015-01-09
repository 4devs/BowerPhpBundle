<?php

namespace FDevs\BowerPhpBundle\DependencyInjection;

use Github\Client;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FDevsBowerPhpExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter($this->getAlias().'.cache_dir', $container->getParameterBag()->resolveValue($config['cache_dir']));
        $container->setParameter($this->getAlias().'.install_dir', $container->getParameterBag()->resolveValue($config['install_dir']));
        $container->setParameter($this->getAlias().'.bower_path', $container->getParameterBag()->resolveValue($config['bower_path']));

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if ($config['github_token']) {
            $container->getDefinition('f_devs_bower_php.github_client')->addMethodCall('authenticate', [$config['github_token'], null, Client::AUTH_HTTP_TOKEN]);
        }
    }
}
