<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Http\Requests\StoreCompanyRequest;
use App\Models\Company;
use Closure;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Validator;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class CreateCompanyMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createCompany',
        'description' => 'A mutation',
    ];

    public function type(): Type
    {
        return GraphQL::type('Company');
    }

    public function args(): array
    {
        return [
            'name' => [
                'type' => Type::nonNull(Type::string()),
            ],
            'base_currency' => [
                'type' => Type::nonNull(Type::string()),
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        $request = new StoreCompanyRequest;

        $validator = Validator::make($args, $request->rules(), $request->messages());

        if ($validator->fails()) {
            throw new UserError(json_encode($validator->errors()->toArray()));
        }

        return Company::create([
            'name' => $args['name'],
            'base_currency' => $args['base_currency'],
        ]);
    }
}
