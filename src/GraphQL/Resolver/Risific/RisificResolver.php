<?php

namespace App\GraphQL\Resolver\Risific;

use App\Repository\RisificRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class RisificResolver implements ResolverInterface
{
    private $repository;

    public function __construct(RisificRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Argument $args)
    {
        return $this->repository->find($args->offsetGet('id'));
    }
}