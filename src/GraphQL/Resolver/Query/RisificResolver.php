<?php

namespace App\GraphQL\Resolver\Query;

use App\Entity\Risific;
use App\Repository\RisificRepository;
use App\Utils\Str;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class RisificResolver implements ResolverInterface
{
    private $repository;

    public function __construct(RisificRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Argument $args): ?Risific
    {
        $search = $args->offsetGet('search');
        [$field, $value] = [$search['field'], $search['value']];

        return $this->repository->findOneBy(
            [$field => $value]
        );
    }
}