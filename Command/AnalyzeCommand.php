<?php

namespace FileAnalyzer\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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
            ->addArgument('path', InputArgument::OPTIONAL)
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = $this->fileAnalyzer->analyze($input->getArgument('path') ?? 'src');
        $this->displayInformations($files);
    }

    private function displayInformations(array $files): void
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
