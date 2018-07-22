<?php


namespace App\Exporters;


use App\Entity\Chapter;
use App\Entity\Risific;
use App\Repository\RisificRepository;
use App\Utils\Str;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DomCrawler\Crawler;

class RisificExporter extends JvcTopicExporter
{

    public const RISIFIC_CHAPTER_CHARS_MIN = 500;
    public const RISIFIC_CHAPTER_STICKERS_MIN = 12;
    public const BLOCKQUOTE_SELECTOR = '.blockquote-jv';

    protected $em;
    protected $repository;

    public function __construct(EntityManagerInterface $em, RisificRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
        parent::__construct();
    }

    public function export(?SymfonyStyle $io = null): void
    {
        $page = $this->client->request('GET', $this->getBaseUrl());
        $title = $page->filter('#bloc-title-forum')->text();
        if ($risific = $this->repository->findOneBy(['title' => $title])) {
            if ($io) {
                $io->warning('Risific "' . $title . '" already exist in database!');
            }
        } else {
            $risific = (new Risific())
                ->setTitle($title);
            $number = 1;
            do {
                echo $this->getPageTitle($page) . "\n";

                foreach ($this->getChapters($page) as $chapterPost) {
                    $chapter = (new Chapter())
                        ->setTitle($this->findChapterTitle($chapterPost))
                        ->setBody($chapterPost->html())
                        ->setPosition($number);

                    $risific->addChapter($chapter);
                    if ($io) {
                        $io->text('Adding chapter <info>' . $chapter->getTitle() . '</info>');
                    }
                    ++$number;
                }

                if ($nextPageLink = $this->getNextPageLink($page)) {
                    $page = $this->client->click(
                        $nextPageLink->link()
                    );
                }
            } while ($this->hasNextPage($page));
            $this->em->persist($risific);
            $this->em->flush();
            if ($io) {
                $io->success('Successfully persisted "' . $risific->getTitle() . '"" fic to database with ' . $risific->getChaptersCount() . ' chapters!');
            }
        }

    }

    private function findChapterTitle(Crawler $chapter): string
    {
        return Str::truncate(trim($chapter->text()), '', 40);
    }

    /**
     * @param Crawler $page
     * @return Crawler[]|array
     */
    private function getChapters(Crawler $page): array
    {
        $results = [];
        $this->getReplies($page)->each(function (Crawler $replyBloc) use (&$results) {
            $reply = $replyBloc->filter('.bloc-contenu .txt-msg');
            $wordsCount = str_word_count($reply->text());
            if ($wordsCount > self::RISIFIC_CHAPTER_CHARS_MIN &&
                \count($this->getStickers($reply)) > self::RISIFIC_CHAPTER_STICKERS_MIN &&
                $reply->filter(self::BLOCKQUOTE_SELECTOR)->count() === 0
            ) {
                $results[] = $reply;
            }
        });

        return $results;
    }

    private function getStickers(Crawler $reply): array
    {
        $results = [];
        $reply->filter('.img-shack')->each(function (Crawler $sticker) use (&$results) {
            $results[] = $sticker->image()->getUri();
        });

        return $results;
    }

}