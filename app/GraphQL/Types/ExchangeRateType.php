<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\ExchangeRate;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ExchangeRateType extends GraphQLType
{
    protected $attributes = [
        'name' => 'ExchangeRate',
        'description' => 'A type',
        'model' => ExchangeRate::class,
    ];

    public function fields(): array
    {
        return [
            'id' => ['type' => Type::nonNull(Type::int())],
            //    'id' => ['type' => Type::int()],
            'from_currency' => ['type' => Type::string()],
            'to_currency' => ['type' => Type::string()],
            'rate' => ['type' => Type::float()],
            'fetched_at' => ['type' => Type::string()],
            'source' => ['type' => Type::string()],
            'created_at' => ['type' => Type::string()],
            'updated_at' => ['type' => Type::string()],
        ];
    }
}
