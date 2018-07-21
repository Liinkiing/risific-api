<?php

namespace App\GraphQL\Resolver\Risific;

use App\Entity\Risific;
use App\Repository\ChapterRepository;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class RisificChaptersCountResolver implements ResolverInterface
{
    private $repository;

    public function __construct(ChapterRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Risific $risific): int
    {
        return $this->repository->countByRisific($risific);
    }

}