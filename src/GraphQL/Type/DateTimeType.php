<?php

namespace App\GraphQL\Type;

class DateTimeType
{
    public static function serialize(\DateTime $value): string
    {
        return $value->format('Y-m-d H:i:s');
    }

    public static function parseValue($value): \DateTime
    {
        return new \DateTime($value);
    }

    public static function parseLiteral($valueNode): string
    {
        return new \DateTime($valueNode->value);
    }
}