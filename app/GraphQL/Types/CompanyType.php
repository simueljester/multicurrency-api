<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Company;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CompanyType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Company',
        'description' => 'A type',
        'model' => Company::class,
    ];

    public function fields(): array
    {
        return [
            'id' => ['type' => Type::nonNull(Type::int())],
            'name' => ['type' => Type::string()],
            'base_currency' => ['type' => Type::string()],
            'name' => ['type' => Type::string()],
            'created_at' => ['type' => Type::string()],
            'updated_at' => ['type' => Type::string()],
        ];
    }
}
