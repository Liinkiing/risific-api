<?php

namespace App\GraphQL\Resolver;

use App\Repository\ChapterRepository;
use App\Repository\RisificRepository;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Error\UserError;

class GlobalIdResolver implements ResolverInterface
{

    private $risificRepository;
    private $chapterRepository;

    public function __construct(RisificRepository $risificRepository, ChapterRepository $chapterRepository)
    {
        $this->risificRepository = $risificRepository;
        $this->chapterRepository = $chapterRepository;
    }

    public function __invoke(string $id)
    {
        $node = $this->risificRepository->find($id);

        if(!$node) {
            $node = $this->chapterRepository->find($id);
        }

        if(!$node) {
            throw new UserError('Could not find node!');
        }

        return $node;
    }

}