<?php

namespace FileAnalyzer\Analyzer;

use FileAnalyzer\Services\Tools;
use Symfony\Component\Yaml\Yaml;

class ServiceAnalyzer
{
    public const DOCTRINE_EVENT_LISTENER          = 'doctrine.event_listener';
    public const DOCTRINE_EVENT_SUBSCRIBER        = 'doctrine.event_subscriber';
    public const DOCTRINE_ORM_ENTITY_LISTENER     = 'doctrine.orm.entity_listener';
    public const JMS_SUBSCRIBING_HANDLER          = 'jms_serializer.subscribing_handler';
    public const JMS_EVENT_SUBSCRIBER             = 'jms_serializer.event_subscriber';
    public const VALIDATOR_CONSTRAINT_VALIDATOR   = 'validator.constraint_validator';
    public const KERNEL_EVENT_LISTENER            = 'kernel.event_listener';
    public const KERNEL_EVENT_SUBSCRIBER          = 'kernel.event_subscriber';

    // TODO -> put this in Configuration files so it can be customized
    private const CONFIG_FILES                    = ['app/config/config.yml', 'app/config/config_test.yml'];

    private $tools;
    private $servicesFilePath;
    private $services;

    public function __construct(Tools $tools, ?string $servicesFilePath = null)
    {
        $this->tools = $tools;
        $this->servicesFilePath = $servicesFilePath;
    }

    public function __call(string $method, array $args)
    {
        $tag = $this->tools->getConstanteFromIsMethod(self::class, $method);

        return $this->isTagged($args[0], $tag);
    }

    private function isTagged(string $filePath, string $tag): bool
    {
        $namespace = $this->tools->getNamespace($filePath);

        if (!$this->services) {
            $this->services = $this->getServicesFromFiles($this->servicesFilePath ? [$this->servicesFilePath] : self::CONFIG_FILES);
        }

        if (!isset($this->services[$namespace], $this->services[$namespace]['tags'])) {
            return false;
        }


        foreach ($this->services[$namespace]['tags'] as $t) {
            if ($t['name'] == $tag) {
                return true;
            }
        }

        return false;
    }

    private function getServicesFromFiles(array $files): array
    {
        $services = [];
        $configs = $this->getContent($files);

        foreach ($configs as $config) {
            $services += array_filter($config, function(string $key) {
                return $key == 'services';
            }, ARRAY_FILTER_USE_KEY);
        }

        return $services['services'];
    }

    private function getContent(array $files): array
    {
        $configs = [];
        foreach ($files as $file) {
            $configs[$file] = Yaml::parseFile($file);

            if (isset($configs[$file]['imports'])) {
                foreach ($configs[$file]['imports'] as $import) {
                    $directory = pathinfo($file)['dirname'];
                    $filePath = $directory . DIRECTORY_SEPARATOR . $import['resource'];
                    $configs[$filePath] = $this->getContent([$filePath]);
                }
            }
        }

        return $configs;
    }
}
