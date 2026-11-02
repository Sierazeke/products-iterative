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
}