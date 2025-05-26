<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class InvoicePaginationType extends GraphQLType
{
    protected $attributes = [
        'name' => 'InvoicePagination',
        'description' => 'Paginated list of invoices',
    ];

    public function fields(): array
    {
        return [
            'data' => [
                'type' => Type::listOf(GraphQL::type('Invoice')),
                'description' => 'List of companies',
                'resolve' => function ($root) {
                    return $root->items();  // Laravel paginator items
                },
            ],
            'total' => [
                'type' => Type::int(),
                'description' => 'Total number of companies',
                'resolve' => fn ($root) => $root->total(),
            ],
            'per_page' => [
                'type' => Type::int(),
                'description' => 'Number of companies per page',
                'resolve' => fn ($root) => $root->perPage(),
            ],
            'current_page' => [
                'type' => Type::int(),
                'description' => 'Current page number',
                'resolve' => fn ($root) => $root->currentPage(),
            ],
            'last_page' => [
                'type' => Type::int(),
                'description' => 'Last page number',
                'resolve' => fn ($root) => $root->lastPage(),
            ],
        ];
    }
}
