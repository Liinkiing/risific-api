<?php


namespace App\GraphQL\Mutation\User;


use App\Entity\User;
use App\Repository\RisificRepository;
use App\Repository\UserFavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Overblog\GraphQLBundle\Error\UserError;

class DeleteUserFavoriteMutation implements MutationInterface
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
            if ($userFavorite = $this->favoriteRepository->findOneBy(compact('user', 'risific'))) {
                $deletedRisificFavoriteId = $risific->getId();
                $this->manager->remove($userFavorite);
                $this->manager->flush();

                return compact('deletedRisificFavoriteId');
            }

            throw new UserError('Risific is not in favorites!');
        }

        throw new UserError('Risific does not exist!');
    }
}