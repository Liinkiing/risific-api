<?php
namespace App\GraphQL\Type;

use GraphQL\Error\Error;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Utils\Utils;

class UrlType
{
    public function serialize($value)
    {
        return $value;
    }

    public function parseValue($value)
    {
        if (!\is_string($value) || !filter_var($value, FILTER_VALIDATE_URL)) {
            throw new Error('Cannot represent value as URL: ' . Utils::printSafe($value));
        }
        return $value;
    }

    public function parseLiteral($valueNode, array $variables = null): ?string
    {
        if (!($valueNode instanceof StringValueNode)) {
            throw new Error('Query error: Can only parse strings got: ' . $valueNode->kind, [$valueNode]);
        }
        if (!\is_string($valueNode->value) || !filter_var($valueNode->value, FILTER_VALIDATE_URL)) {
            throw new Error('Query error: Not a valid URL', [$valueNode]);
        }
        return $valueNode->value;
    }
}
