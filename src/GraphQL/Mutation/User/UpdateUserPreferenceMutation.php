<?php


namespace App\GraphQL\Mutation\User;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

class UpdateUserPreferenceMutation implements MutationInterface
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Argument $args, User $viewer)
    {
        $theme = $args->offsetGet('theme');
        $viewer->getPreference()->setTheme($theme);
        $this->em->flush();

        return ['userPreference' => $viewer->getPreference()];
    }

}