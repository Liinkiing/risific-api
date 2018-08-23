<?php


namespace App\GraphQL\Mutation\Risific;


use App\Message\AddRisificMessage;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class AddRisificMutation implements MutationInterface
{

    private $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public function __invoke(Argument $args)
    {
        $risificUrl = $args->offsetGet('input')['risificUrl'];

        $this->bus->dispatch(new AddRisificMessage($risificUrl));

        return [
            'processing' => true
        ];
    }
}