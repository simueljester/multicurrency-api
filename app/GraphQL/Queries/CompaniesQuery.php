<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Company;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class CompaniesQuery extends Query
{
    protected $attributes = [
        'name' => 'companies',
    ];

    public function type(): Type
    {
        return GraphQL::type('CompanyPagination');
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
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $with = $fields->getRelations();

        $query = Company::with($with);

        // Paginate with default args or passed ones
        $paginator = $query->paginate($args['limit'] ?? 10, ['*'], 'page', $args['page'] ?? 1);

        return $paginator;
    }
}
