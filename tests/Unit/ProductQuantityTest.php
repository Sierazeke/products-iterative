<?php

namespace Tests\Unit;

use App\Models\Product;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductQuantityTest extends TestCase
{
    use RefreshDatabase;

    public function test_adjust_quantity_adds_positive_delta(): void
    {
        $product = Product::factory()->create(['quantity' => 10]);

        $product->adjustQuantity(5);

        $this->assertSame(15, $product->quantity);
        $this->assertSame(15, $product->fresh()->quantity);
    }

    public function test_adjust_quantity_subtracts_negative_delta(): void
    {
        $product = Product::factory()->create(['quantity' => 10]);

        $product->adjustQuantity(-4);

        $this->assertSame(6, $product->fresh()->quantity);
    }

    public function test_adjust_quantity_can_reach_zero(): void
    {
        $product = Product::factory()->create(['quantity' => 3]);

        $product->adjustQuantity(-3);

        $this->assertSame(0, $product->fresh()->quantity);
    }

    public function test_adjust_quantity_throws_when_stock_would_go_negative(): void
    {
        $product = Product::factory()->create(['quantity' => 3]);

        try {
            $product->adjustQuantity(-4);
            $this->fail('DomainException was not thrown.');
        } catch (DomainException) {
            $this->assertSame(3, $product->fresh()->quantity);
        }
    }
        public static function nonPositiveAmounts(): array
    {
        return [
            'zero' => [0],
            'negative' => [-3],
        ];
    }

    public function test_increase_quantity_adds_amount_and_returns_model(): void
    {
        $product = Product::factory()->create(['quantity' => 10]);

        $result = $product->increaseQuantity(5);

        $this->assertSame($product, $result);
        $this->assertSame(15, $product->quantity);
        $this->assertSame(15, $product->fresh()->quantity);
    }

    public function test_decrease_quantity_subtracts_amount_and_returns_model(): void
    {
        $product = Product::factory()->create(['quantity' => 10]);

        $result = $product->decreaseQuantity(4);

        $this->assertSame($product, $result);
        $this->assertSame(6, $product->fresh()->quantity);
    }

    public function test_decrease_quantity_can_reach_zero(): void
    {
        $product = Product::factory()->create(['quantity' => 3]);

        $product->decreaseQuantity(3);

        $this->assertSame(0, $product->fresh()->quantity);
    }

    public function test_decrease_quantity_throws_when_stock_is_insufficient(): void
    {
        $product = Product::factory()->create(['quantity' => 3]);

        try {
            $product->decreaseQuantity(4);
            $this->fail('DomainException was not thrown.');
        } catch (DomainException) {
            $this->assertSame(3, $product->fresh()->quantity);
        }
    }

    public function test_decrease_quantity_uses_database_value_not_stale_model(): void
    {
        $product = Product::factory()->create(['quantity' => 5]);
        $stale = Product::find($product->id); // otra kopija ar quantity = 5

        $product->decreaseQuantity(3); // datubāzē tagad 2

        $this->expectException(DomainException::class);
        $stale->decreaseQuantity(3);   // vecā kopija "domā", ka ir 5, bet DB ir 2
    }

    #[DataProvider('nonPositiveAmounts')]
    public function test_increase_quantity_rejects_non_positive_amount(int $amount): void
    {
        $product = Product::factory()->create(['quantity' => 5]);

        $this->expectException(InvalidArgumentException::class);
        $product->increaseQuantity($amount);
    }

    #[DataProvider('nonPositiveAmounts')]
    public function test_decrease_quantity_rejects_non_positive_amount(int $amount): void
    {
        $product = Product::factory()->create(['quantity' => 5]);

        $this->expectException(InvalidArgumentException::class);
        $product->decreaseQuantity($amount);
    }
}