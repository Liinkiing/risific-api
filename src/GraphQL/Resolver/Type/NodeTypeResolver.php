<?php

namespace App\GraphQL\Resolver\Type;

use App\Entity\Chapter;
use App\Entity\Risific;
use App\Entity\User;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Error\UserError;
use Overblog\GraphQLBundle\Resolver\TypeResolver;

class NodeTypeResolver implements ResolverInterface
{

    private $resolver;

    public function __construct(TypeResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function __invoke($node)
    {
        if ($node instanceof User) {
            return $this->resolver->resolve('User');
        }
        if ($node instanceof Risific) {
            return $this->resolver->resolve('Risific');
        }
        if ($node instanceof Chapter) {
            return $this->resolver->resolve('Chapter');
        }

        throw new UserError("Can't resolve node type!");
    }

}