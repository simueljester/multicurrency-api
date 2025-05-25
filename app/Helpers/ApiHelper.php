<?php

namespace App\Helpers;

use Illuminate\Support\Collection;

class ApiHelper
{
    public static function parseIncludes(): Collection
    {
        $includes = request()->input('include', '');
        $includes = str($includes)->split('/[\s,]+/') ?? [];

        return $includes;
    }
}
