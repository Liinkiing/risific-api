<?php


namespace App\GraphQL\Resolver\User;


use App\Entity\User;
use App\Repository\UserFavoriteRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Relay\Connection\Output\ConnectionBuilder;

class UserFavoriteResolver implements ResolverInterface
{

    private $repository;

    public function __construct(UserFavoriteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(User $user, Argument $args)
    {
        $orderBy = $args->offsetGet('orderBy');
        $favorites = $this->repository->findBy(
            ['user' => $user],
            [$orderBy['field'] => $orderBy['direction']]
        );
        $connection = ConnectionBuilder::connectionFromArray($favorites, $args);
        $connection->totalCount = \count($favorites);

        return $connection;

    }

}