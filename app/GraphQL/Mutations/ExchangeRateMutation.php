<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use Closure;
use App\Models\ExchangeRate;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use App\Enums\ExchangeRateSourceEnum;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreExchangeRequest;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ExchangeRateMutation extends Mutation
{
    protected $attributes = [
        'name' => 'exchangeRate',
        'description' => 'A mutation'
    ];

    public function type(): Type
    {
       return GraphQL::type('ExchangeRate');
    }

    public function args(): array
    {
         return [
            'from_currency' => [
                'type' => Type::nonNull(Type::string()),
            ],
            'to_currency' => [
                'type' => Type::nonNull(Type::string()),
            ],
            'rate' => [
                'type' => Type::nonNull(Type::int()),
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        $request = new StoreExchangeRequest;

        $validator = Validator::make($args, $request->rules(), $request->messages());

        if ($validator->fails()) {
            throw new UserError(json_encode($validator->errors()->toArray()));
        }

        return ExchangeRate::create([
            'from_currency' => $args['from_currency'],
            'to_currency' => $args['to_currency'],
            'rate' => $args['rate'],
            'fetched_at' => now(),
            'source' =>  $source = ExchangeRateSourceEnum::Manual->value
        ]);
    }
}
