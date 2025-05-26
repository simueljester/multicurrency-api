<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Invoice;
use Closure;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class InvoiceQuery extends Query
{
    protected $attributes = [
        'name' => 'invoice',
        'description' => 'A query'
    ];

    public function type(): Type
    {
        return GraphQL::type('InvoicePagination');
    }

    public function args(): array
    {
          return [
            'page' => [
                'type' => Type::int(),
                'description' => 'Page number',
                'defaultValue' => 1,
            ],
            'limit' => [
                'type' => Type::int(),
                'description' => 'Number of items per page',
                'defaultValue' => 10,
            ],
            'id' => [
                'type' => Type::int(),
                'description' => 'Filter by id',
                'defaultValue' => null,
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        $query = Invoice::with($with)
        ->when(isset($args['id']), fn ($q) => $q->where('id', $args['id']));

        // Paginate with default args or passed ones
        $paginator = $query->paginate($args['limit'] ?? 10, ['*'], 'page', $args['page'] ?? 1);

        return $paginator;
    }
}
