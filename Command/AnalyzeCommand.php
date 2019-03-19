<?php

namespace FileAnalyzer\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use FileAnalyzer\Services\FileAnalyzer;

class AnalyzeCommand extends ContainerAwareCommand
{
    private $fileAnalyzer;

    protected static $defaultName = 'kbunel:app:analyze';

    public function __construct(FileAnalyzer $fileAnalyzer)
    {
        $this->fileAnalyzer = $fileAnalyzer;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Analyze content of the application')
            ->addArgument('path', InputArgument::OPTIONAL, 'Specify path to get files informations')
            ->addOption('kind', null, InputOption::VALUE_OPTIONAL, 'if specify, will output files from thins kind with their paths')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = $this->fileAnalyzer->analyze($input->getArgument('path') ?? 'src');

        if ($kind = $input->getOption('kind')) {
            $this->getPathForFilesInAKind($files, $kind);

            return;
        }

        $this->getGlobalInformations($files);
    }

    public function getPathForFilesInAKind(array $files, string $kind): void
    {
        foreach ($files as $file) {
            if ($file->kind == $kind) {
                echo $file->originPath . PHP_EOL;
            }
        }
    }

    private function getGlobalInformations(array $files): void
    {
        $infos = [];
        foreach ($files as $file) {
            if (isset($infos[$file->kind])) {
                $infos[$file->kind] += 1;

                continue;
            }

            $infos[$file->kind] = 1;
        }

        dump($infos);
    }
}
