<?php

declare(strict_types=1);

namespace MarcelStrahl\TemporaryEmailValidatorBundle\DependencyInjection;

use MarcelStrahl\TemporaryEmailValidatorBundle\Validator\IsNotTemporaryEmail;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
 */
final class MarcelStrahlTemporaryEmailValidatorExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yml');

        $this->addAnnotatedClassesToCompile([
            IsNotTemporaryEmail::class,
        ]);
    }
}
