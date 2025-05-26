<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Invoice;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class InvoiceQuery extends Query
{
    protected $attributes = [
        'name' => 'invoice',
        'description' => 'A query',
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
            'keyword' => [
                'type' => Type::string(),
                'description' => 'Search title via keyword',
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

        $id = $args['id'];
        $keyword = $args['keyword'];

        $query = Invoice::with([...$with])
            ->when($id, fn ($q) => $q->where('id', $id))
            ->when($keyword, fn ($q, $keyword) => $q->where('title', 'like', "%{$keyword}%"));

        // Paginate with default args or passed ones
        $paginator = $query->paginate($args['limit'] ?? 10, ['*'], 'page', $args['page'] ?? 1);

        return $paginator;
    }
}
