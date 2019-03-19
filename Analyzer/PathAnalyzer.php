<?php

namespace FileAnalyzer\Analyzer;

class PathAnalyzer
{
    public function __call(string $method, array $args)
    {
        if (!preg_match('/^contains[a-zA-Z0-9]+InPath$/', $method)) {
            throw new \Exception('Error: ' . $method . ' is unavailable, function must start by `contains` and finish by `InPath`');
        }

        return preg_match('/' . str_replace(['contains', 'InPath'], '', $method) . '/', $args[0]);
    }
}
