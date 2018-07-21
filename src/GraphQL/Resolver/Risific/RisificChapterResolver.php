<?php

namespace App\GraphQL\Resolver\Risific;

use App\Entity\Chapter;
use App\Entity\Risific;
use App\Repository\ChapterRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class RisificChapterResolver implements ResolverInterface
{
    private $repository;

    public function __construct(ChapterRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Risific $risific, Argument $args): ?Chapter
    {
        $position = $args->offsetGet('position');

        return $this->repository->findOneBy(['risific' => $risific, 'position' => $position]);
    }

}