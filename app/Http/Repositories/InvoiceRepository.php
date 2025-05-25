<?php

namespace App\Http\Repositories;

use App\Models\Invoice;

class InvoiceRepository extends BaseRepository
{
    public function __construct(Invoice $model)
    {
        $this->model = $model;
    }
}
