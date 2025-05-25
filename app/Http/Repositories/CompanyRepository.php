<?php

namespace App\Http\Repositories;

use App\Models\Company;

class CompanyRepository extends BaseRepository
{
    public function __construct(Company $model)
    {
        $this->model = $model;
    }
}
