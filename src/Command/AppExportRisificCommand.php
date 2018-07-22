<?php

namespace App\Command;

use App\Exporters\RisificExporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppExportRisificCommand extends Command
{
    protected static $defaultName = 'app:export-risific';
    protected $exporter;
    protected $projectDir;
    protected $filesystem;

    public function __construct(RisificExporter $exporter)
    {
        $this->exporter = $exporter;
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
                $this->exporter->getChapterCharsMin()
            )
            ->addOption(
                'min-stickers',
                's',
                InputOption::VALUE_REQUIRED,
                'Set the minimum stickers a reply has to have to be considered as a Risific chapter',
                $this->exporter->getChapterStickersMin()
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);
        [$url, $charsMin, $stickersMin] = [$input->getArgument('topic_url'), $input->getOption('min-chars'), $input->getOption('min-stickers')];
        $io->text("Extracting fic from <info>$url</info>... ");

        $this->exporter
            ->setChapterCharsMin($charsMin)
            ->setChapterStickersMin($stickersMin)
            ->setBaseUrl($url)
            ->export($io);

    }


}
