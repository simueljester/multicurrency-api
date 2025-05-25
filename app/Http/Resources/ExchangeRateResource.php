<?php

namespace App\Http\Resources;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExchangeRateResource extends JsonResource
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
            'from_currency' => $this->from_currency,
            'to_currency' => $this->to_currency,
            'rate' => $this->rate,
            'fetched_at' => $this->fetched_at->toDateTimeString(),
            'source' => $this->source,
        ];

        return $record;
    }
}
