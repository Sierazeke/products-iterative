<?php

namespace Tests\Feature;

use App\Enums\ProductStatus;
use App\Models\Product;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Test milk 1 l',
            'quantity' => 10,
            'description' => 'Fresh milk',
            'expiration_date' => '2030-01-31',
            'status' => ProductStatus::Active->value,
        ], $overrides);
    }

    public function test_user_can_create_a_product_and_see_it_in_the_list(): void
    {
        $this->followingRedirects()
            ->post(route('products.store'), $this->validData())
            ->assertOk()
            ->assertSee('Product created.')
            ->assertSee('Test milk 1 l');

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('Test milk 1 l');
    }

    public function test_invalid_creation_shows_errors_and_keeps_old_input(): void
    {
        $this->from(route('products.create'))
            ->followingRedirects()
            ->post(route('products.store'), $this->validData(['name' => '', 'quantity' => 7]))
            ->assertOk()
            ->assertSee('The name field is required.')
            ->assertSee('value="7"', false);

        $this->assertDatabaseCount('products', 0);
    }

    public function test_user_can_edit_a_product(): void
    {
        $product = Product::factory()->create(['name' => 'Old name']);

        $this->get(route('products.edit', $product))
            ->assertOk()
            ->assertSee('Old name');

        $this->followingRedirects()
            ->put(route('products.update', $product), $this->validData(['name' => 'New name']))
            ->assertOk()
            ->assertSee('Product Updated!')
            ->assertSee('New name');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'New name']);
        $this->assertDatabaseMissing('products', ['name' => 'Old name']);
    }

    public function test_user_can_receive_and_sell_stock(): void
    {
        $product = Product::factory()->create(['quantity' => 10]);
        $show = route('products.show', $product);
        $adjust = route('products.adjust-quantity', $product);

        $this->from($show)->followingRedirects()
            ->patch($adjust, ['amount' => 5, 'action' => 'increase'])
            ->assertSee('Quantity changed to 15.');

        $this->from($show)->followingRedirects()
            ->patch($adjust, ['amount' => 3, 'action' => 'decrease'])
            ->assertSee('Quantity changed to 12.');

        $this->assertSame(12, $product->fresh()->quantity);
    }

    public function test_user_cannot_sell_more_than_in_stock(): void
    {
        $product = Product::factory()->create(['quantity' => 12]);

        $this->from(route('products.show', $product))->followingRedirects()
            ->patch(route('products.adjust-quantity', $product), ['amount' => 100, 'action' => 'decrease'])
            ->assertOk()
            ->assertSee('Not enough stock');

        $this->assertSame(12, $product->fresh()->quantity);
    }

    public function test_full_product_lifecycle(): void
    {
        // create
        $this->followingRedirects()
            ->post(route('products.store'), $this->validData(['quantity' => 2]))
            ->assertSee('Product created.');
        $product = Product::firstOrFail();

        // edit
        $this->followingRedirects()
            ->put(route('products.update', $product), $this->validData(['name' => 'Renamed', 'quantity' => 2]))
            ->assertSee('Renamed');

        // receive stock
        $this->from(route('products.show', $product))->followingRedirects()
            ->patch(route('products.adjust-quantity', $product), ['amount' => 8, 'action' => 'increase'])
            ->assertSee('Quantity changed to 10.');

        // delete
        $this->followingRedirects()
            ->delete(route('products.destroy', $product))
            ->assertSee('Product Deleted!')
            ->assertSee('No products yet.');

        $this->assertModelMissing($product);
    }

    public function test_seeded_products_appear_in_the_list(): void
    {
        $this->seed(ProductSeeder::class);

        $this->assertDatabaseCount('products', 30);
        $this->get(route('products.index'))->assertOk();
    }
}