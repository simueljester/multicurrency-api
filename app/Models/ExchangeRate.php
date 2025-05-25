<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    use HasFactory;

    /**
     * The attributes that are fillable.
     *
     * @var array
     */
    protected $fillable = [
        'from_currency',
        'to_currency',
        'rate',
        'fetched_at',
        'source',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'rate' => 'float',
        'fetched_at' => 'datetime',
        'source' => 'string',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
