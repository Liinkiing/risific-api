<?php


namespace App\GraphQL\Resolver\Risific;


use App\Entity\Risific;
use App\Entity\User;
use App\Repository\UserFavoriteRepository;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class RisificViewerHasFavorited implements ResolverInterface
{
    private $repository;

    public function __construct(UserFavoriteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Risific $risific, ?User $user)
    {
        return $this->repository->findOneBy([
            'risific' => $risific,
            'user' => $user
        ]) !== null;
    }
}