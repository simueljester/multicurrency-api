<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\ExchangeRate;
use Closure;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ExchangeRateQuery extends Query
{
    protected $attributes = [
        'name' => 'exchangeRate',
        'description' => 'A query'
    ];

    public function type(): Type
    {
         return GraphQL::type('ExchangeRatePagination');
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
            'sortFetchedAt' => [
                'type' => Type::string(),
                'description' => 'Sort by fetched_at',
                'defaultValue' => 'ASC',
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $with = $fields->getRelations();

        $query = ExchangeRate::with($with)->when(isset($args['sortFetchedAt']), fn ($q) => $q->orderBy('fetched_at', $args['sortFetchedAt']));;

        // Paginate with default args or passed ones
        $paginator = $query->paginate($args['limit'] ?? 10, ['*'], 'page', $args['page'] ?? 1);

        return $paginator;
    }
}
