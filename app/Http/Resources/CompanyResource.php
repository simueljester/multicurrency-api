<?php

namespace App\Http\Resources;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'name' => $this->name,
            'base_currency' => $this->base_currency,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];

        return $record;
    }
}
