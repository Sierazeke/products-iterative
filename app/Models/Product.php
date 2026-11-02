<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\ProductStatus;


class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'quantity', 'description', 'expiration_date', 'status'];

    protected $attributes = [
        'quantity' => 0,
        'status' => 'active',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'expiration_date' => 'date',
            'status' => ProductStatus::class,
        ];
    }

    
}
