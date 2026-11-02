<?php

namespace App\Models;

use App\Enums\ProductStatus;
use DomainException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'description',
        'expiration_date',
        'status',
    ];

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

    /** 
     * @throws DomainException
     */
    public function adjustQuantity(int $delta): void
    {
        $newQuantity = $this->quantity + $delta;

        if ($newQuantity < 0) {
            throw new DomainException(
                "Not enough stock: have {$this->quantity}, tried to remove " . abs($delta) . '.'
            );
        }

        $this->update(['quantity' => $newQuantity]);
    }
}