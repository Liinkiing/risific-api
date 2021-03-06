<?php

namespace App\GraphQL\Resolver\Query;

use App\Repository\RisificRepository;
use App\Utils\Str;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Relay\Connection\Output\Connection;
use Overblog\GraphQLBundle\Relay\Connection\Output\ConnectionBuilder;

class RisificsResolver implements ResolverInterface
{
    private $repository;

    public function __construct(RisificRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Argument $args): Connection
    {
        $orderBy = $args->offsetGet('orderBy');
        $risifics = $this->repository->findBy(
            [],
            [$orderBy['field'] => $orderBy['direction']]
        );
        $connection = ConnectionBuilder::connectionFromArray($risifics, $args);
        $connection->totalCount = \count($risifics);

        return $connection;
    }
}