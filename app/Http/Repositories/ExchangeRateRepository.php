<?php

namespace App\Http\Repositories;

use App\Models\ExchangeRate;

class ExchangeRateRepository extends BaseRepository
{
    public function __construct(ExchangeRate $model)
    {
        $this->model = $model;
    }
}
