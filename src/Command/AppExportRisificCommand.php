<?php

namespace App\Command;

use App\Exporters\RisificImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppExportRisificCommand extends Command
{
    protected static $defaultName = 'app:export-risific';
    protected $importer;
    protected $projectDir;
    protected $filesystem;

    public function __construct(RisificImporter $exporter)
    {
        $this->importer = $exporter;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Allows to export to markdown a complete risific based on a topic')
            ->addArgument('topic_url', InputArgument::REQUIRED, 'Put here the topic where the fic is')
            ->addOption(
                'min-chars',
                'c',
                InputOption::VALUE_REQUIRED,
                'Set the minimum characters a reply has to have to be considered as a Risific chapter',
                $this->importer->getChapterCharsMin()
            )
            ->addOption(
                'min-stickers',
                'k',
                InputOption::VALUE_REQUIRED,
                'Set the minimum stickers a reply has to have to be considered as a Risific chapter',
                $this->importer->getChapterStickersMin()
            )
            ->addOption(
                'safe-mode',
                's',
                InputOption::VALUE_NONE,
                'Activate the safe mode. Crawler will navigate between pages every 2s to prevent JVC from blocking it'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);
        [$url, $charsMin, $stickersMin] = [$input->getArgument('topic_url'), $input->getOption('min-chars'), $input->getOption('min-stickers')];
        $io->text("Extracting fic from <info>$url</info>... ");

        $this->importer
            ->setSafeMode($input->getOption('safe-mode'))
            ->setChapterCharsMin($charsMin)
            ->setChapterStickersMin($stickersMin)
            ->setBaseUrl($url)
            ->export($io);

    }


}
