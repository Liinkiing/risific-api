<?php

namespace App\GraphQL\Resolver\Risific;

use App\Entity\Risific;
use App\Repository\ChapterRepository;
use App\Utils\Str;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Relay\Connection\Output\Connection;
use Overblog\GraphQLBundle\Relay\Connection\Output\ConnectionBuilder;

class RisificChaptersResolver implements ResolverInterface
{
    private $repository;

    public function __construct(ChapterRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Risific $risific, Argument $args): Connection
    {
        $orderBy = $args->offsetGet('orderBy');
        $chapters = $this->repository->findBy(
            ['risific' => $risific],
            [Str::camelize($orderBy['field']) => $orderBy['direction']]
        );
        $connection = ConnectionBuilder::connectionFromArray($chapters, $args);
        $connection->totalCount = \count($chapters);

        return $connection;
    }

}