<?php

namespace App\Http\Resources;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $includes = ApiHelper::parseIncludes();

        $record = [
            'id' => $this->id,
            'title' => $this->title,
            'amount' => (float) $this->amount,
            'currency_code' => $this->currency_code,
            'base_currency' => $this->base_currency,
            'exchange_rate' => $this->exchange_rate,
            'exchange_rate_timestamp' => $this->exchange_rate_timestamp->toDateTimeString(),
            'amount_in_base_currency' => (float) $this->amount_in_base_currency,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];

        $additionalFields = [

            'company' => [
                'company' => new CompanyResource($this->company),
            ],
            'exchange_rate' => [
                'exchange_rate' => new ExchangeRateResource($this->exchangeRate),
            ],
        ];

        foreach ($additionalFields as $key => $value) {
            if ($includes->contains($key)) {
                $record = array_merge($record, $value);
            }
        }

        return $record;
    }
}
