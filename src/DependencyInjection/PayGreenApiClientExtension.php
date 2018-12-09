<?php

namespace PayGreen\ApiClientBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class PayGreenApiClientExtension extends Extension
{
    const SERVICES_DIR = __DIR__.'/../resources/config';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
//        $this->loadConfig($configs, $container);
        $this->loadServices($container);
    }

//    private function loadConfig(array $configs, ContainerBuilder $container)
//    {
//        $configuration = new Configuration();
//        $processedConfiguration = $this->processConfiguration($configuration, $configs);
//
//        foreach ($processedConfiguration as $key => $value) {
//            $container->setParameter(
//                $this->getAlias().'.'.$key,
//                $value
//            );
//        }
//    }

    private function loadServices(ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(self::SERVICES_DIR));
//        $finder = new Finder();

//        $finder->files()->in(self::SERVICES_DIR);

        $loader->load('services.yaml');
//        foreach ($finder as $file) {
//            $loader->load(
//                $file->getRelativePathname()
//            );
//        }
    }
}
