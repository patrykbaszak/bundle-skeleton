<?php

declare(strict_types=1);

namespace $NAMESPACE\DependencyInjection;

use $NAMESPACE\$PACKAGEBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class $PACKAGEExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        // do nothing
    }

    public function getAlias(): string
    {
        return $PACKAGEBundle::ALIAS;
    }

    public function prepend(ContainerBuilder $container): void
    {
        if ($container->hasParameter('$LC_VENDOR.$LC_PACKAGE.dev_mode') && true === $container->getParameter('$LC_VENDOR.$LC_PACKAGE.dev_mode')) {
            return;
        }

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
    }
}
