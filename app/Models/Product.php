<?php

namespace App\Models;

use App\Enums\ProductStatus;
use DomainException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

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

    public function increaseQuantity(int $amount): static
    {
        $this->assertPositiveAmount($amount);

        $this->newQuery()->whereKey($this->getKey())->increment('quantity', $amount);

        return $this->refresh();
    }

    /**
     * @throws DomainException ja krājumā nepietiek preču
     */
    public function decreaseQuantity(int $amount): static
    {
        $this->assertPositiveAmount($amount);

        $affected = $this->newQuery()
            ->whereKey($this->getKey())
            ->where('quantity', '>=', $amount)
            ->decrement('quantity', $amount);

        if ($affected === 0) {
            throw new DomainException("Not enough stock to remove {$amount} units.");
        }

        return $this->refresh();
    }

    private function assertPositiveAmount(int $amount): void
    {
        if ($amount < 1) {
            throw new InvalidArgumentException('Amount must be a positive integer.');
        }
    }
}