<?php


namespace App\GraphQL\Mutation\User;


use App\Entity\User;
use App\Entity\UserFavorite;
use App\Repository\RisificRepository;
use App\Repository\UserFavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Overblog\GraphQLBundle\Error\UserError;

class AddUserFavoriteMutation implements MutationInterface
{

    private $manager;
    private $risificRepository;
    private $favoriteRepository;

    public function __construct(EntityManagerInterface $manager, RisificRepository $risificRepository, UserFavoriteRepository $favoriteRepository)
    {
        $this->manager = $manager;
        $this->risificRepository = $risificRepository;
        $this->favoriteRepository = $favoriteRepository;
    }

    public function __invoke(Argument $args, User $user)
    {
        $risificId = $args->offsetGet('risificId');

        if ($risific = $this->risificRepository->find($risificId)) {
            if ($this->favoriteRepository->findOneBy(compact('user', 'risific'))) {
                throw new UserError('Risific already in favorites!');
            }
            $userFavorite = new UserFavorite($user, $risific);
            $this->manager->persist($userFavorite);
            $this->manager->flush();

            return compact('userFavorite');
        }

        throw new UserError('Risific does not exist!');

    }
}