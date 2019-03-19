<?php

namespace FileAnalyzer\Analyzer;

use FileAnalyzer\Services\Tools;

class DiversAnalyzer
{
    private $tools;

    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    public function isRoutingFile(string $filePath): bool
    {
        return preg_match('/routing.yml$/', $filePath);
    }

    public function isInterface(string $filePath): bool
    {
        if (pathinfo($filePath)['extension'] != 'php') {
            return false;
        }

        $lines = file($filePath);
        foreach ($lines as $line) {
            $line = trim($line);

            if (preg_match('/^interface/', $line)) {
                return true;
            }

            if (preg_match('/^class/', $line)) {
                return false;
            }
        }

        return false;
    }

    public function isHiddenfile(string $filePath): bool
    {
        return pathinfo($filePath)['basename'][0] == '.';
    }

    public function isTrait(string $filePath): bool
    {
        if (pathinfo($filePath)['extension'] != 'php') {
            return false;
        }

        $lines = file($filePath);
        foreach ($lines as $line) {
            $line = trim($line);

            if (preg_match('/^trait/', $line)) {
                return true;
            }

            if (preg_match('/^class/', $line)) {
                return false;
            }
        }

        return false;
    }

    public function isPublic(string $filePath): bool
    {
        return preg_match('/Resources\/public/', $filePath);
    }

    public function isEntity(string $filePath): bool
    {
        $lines = file($filePath, FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);

            if (preg_match('/^\*\ *\@ORM\\\Entity/', $line)) {
                return true;
            }

            if (preg_match('/^class/', $line)) {
                return false;
            }
        }

        return false;
    }
}
