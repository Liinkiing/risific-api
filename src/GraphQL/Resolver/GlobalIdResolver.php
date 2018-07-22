<?php

namespace App\GraphQL\Resolver;

use App\Repository\ChapterRepository;
use App\Repository\RisificRepository;
use App\Repository\UserRepository;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Error\UserError;

class GlobalIdResolver implements ResolverInterface
{

    private $risificRepository;
    private $chapterRepository;
    private $userRepository;

    public function __construct(RisificRepository $risificRepository, ChapterRepository $chapterRepository, UserRepository $userRepository)
    {
        $this->risificRepository = $risificRepository;
        $this->chapterRepository = $chapterRepository;
        $this->userRepository = $userRepository;
    }

    public function __invoke(string $id)
    {
        $node = $this->userRepository->find($id);

        if(!$node) {
            $node = $this->risificRepository->find($id);
        }

        if(!$node) {
            $node = $this->chapterRepository->find($id);
        }

        if(!$node) {
            throw new UserError('Could not find node!');
        }

        return $node;
    }

}