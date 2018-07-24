<?php


namespace App\GraphQL\Mutation\User;


use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Overblog\GraphQLBundle\Error\UserError;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginUserMutation implements MutationInterface
{

    private $encoder;
    private $tokenManager;
    private $repository;

    public function __construct(UserPasswordEncoderInterface $encoder,
                                UserRepository $repository,
                                JWTTokenManagerInterface $tokenManager)
    {
        $this->encoder = $encoder;
        $this->tokenManager = $tokenManager;
        $this->repository = $repository;
    }

    public function __invoke(Argument $args)
    {
        [$username, $password] = [$args->offsetGet('username'), $args->offsetGet('password')];
        if ($viewer = $this->repository->findOneBy(compact('username'))) {
            if ($this->encoder->isPasswordValid($viewer, $password)) {
                $token = $this->tokenManager->create($viewer);

                return compact('token');
            }

            throw new UserError('Bad credentials.');
        }

        throw new UserError('Bad credentials.');
    }
}