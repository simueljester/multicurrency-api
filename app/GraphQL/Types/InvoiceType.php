<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Invoice;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class InvoiceType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Invoice',
        'description' => 'A type',
        'model' => Invoice::class,
    ];

    public function fields(): array
    {
        return [
            'id' => ['type' => Type::nonNull(Type::int())],
            'company_id' => ['type' => Type::int()],
            'company' => [
                'type' => GraphQL::type('Company'),
                'description' => 'The company this invoice belongs to',
            ],
            'title' => ['type' => Type::string()],
            'amount' => ['type' => Type::float()],
            'currency_code' => ['type' => Type::string()],
            'base_currency' => ['type' => Type::string()],
            'exchange_rate_id' => ['type' => Type::int()],
            'exchange_rate' => [
                'type' => GraphQL::type('ExchangeRate'),
                'description' => 'The exchange rate this invoice belongs to. Uses resolve function to call the exchangeRate method',
                'resolve' => function ($root) {
                    return $root->exchangeRate;
                },
            ],
            // 'exchange_rate' => ['type' => Type::float()],
            'exchange_rate_timestamp' => ['type' => Type::string()],
            'amount_in_base_currency' => ['type' => Type::float()],
            'created_at' => ['type' => Type::string()],
            'updated_at' => ['type' => Type::string()],
        ];
    }
}
