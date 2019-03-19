<?php

namespace FileAnalyzer\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class FileAnalyzerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
		$loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if (isset($config['services_file_path'])) {
            $serviceAnalyzerDefinition = $container->getDefinition('FileAnalyzer\Analyzer\ServiceAnalyzer');
            $serviceAnalyzerDefinition->setArgument('$servicesFilePath', $config['services_file_path']);
        }

        if (isset($config['from_path'])) {
            $serviceAnalyzerDefinition = $container->getDefinition('FileAnalyzer\Services\FileAnalyzer');
            $serviceAnalyzerDefinition->setArgument('$customKinds', $config['from_path']);
        }
    }
}
