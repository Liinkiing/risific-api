<?php

namespace App\GraphQL\Resolver\Chapter;

use App\Entity\Chapter;
use App\Repository\ChapterRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class ChapterResolver implements ResolverInterface
{
    private $repository;

    public function __construct(ChapterRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Argument $args): Chapter
    {
        [$slug, $position] = [$args->offsetGet('risificSlug'), $args->offsetGet('position')];

        return $this->repository->findByRisificSlugAndPosition($slug, $position);
    }

}