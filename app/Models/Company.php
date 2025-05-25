<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are fillable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'base_currency',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    protected function baseCurrency(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtoupper($value)
        );
    }
}
