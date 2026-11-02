<?php

namespace Tests\Feature;

use App\Enums\ProductStatus;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // @vite nevajag testos (nav nedz build, nedz Vite servera)
        $this->withoutVite();
    }

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

    public static function invalidFields(): array
    {
        return [
            'name is required' => ['name', ''],
            'name is too long' => ['name', str_repeat('a', 256)],
            'quantity is required' => ['quantity', ''],
            'quantity must be integer' => ['quantity', 'abc'],
            'quantity cannot be negative' => ['quantity', -1],
            'expiration date must be a date' => ['expiration_date', 'not-a-date'],
            'status is required' => ['status', ''],
            'status must be known' => ['status', 'bogus'],
        ];
    }

    public function test_root_redirects_to_products_index(): void
    {
        $this->get('/')->assertRedirect(route('products.index'));
    }

    public function test_index_shows_empty_message(): void
    {
        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('No products yet.');
    }

    public function test_index_lists_products(): void
    {
        $products = Product::factory()->count(3)->create();

        $response = $this->get(route('products.index'))
            ->assertOk()
            ->assertViewIs('products.index');

        foreach ($products as $product) {
            $response->assertSee($product->name);
        }
    }

    public function test_create_shows_form(): void
    {
        $this->get(route('products.create'))
            ->assertOk()
            ->assertViewIs('products.create');
    }

    public function test_store_creates_product(): void
    {
        $response = $this->post(route('products.store'), $this->validData());

        $product = Product::firstOrFail();

        $response->assertRedirect(route('products.show', $product))
            ->assertSessionHas('status');
        $this->assertDatabaseHas('products', ['name' => 'Test milk 1 l', 'quantity' => 10]);
    }

    #[DataProvider('invalidFields')]
    public function test_store_rejects_invalid_data(string $field, mixed $value): void
    {
        $this->post(route('products.store'), $this->validData([$field => $value]))
            ->assertSessionHasErrors($field);

        $this->assertDatabaseCount('products', 0);
    }

    public function test_show_displays_product(): void
    {
        $product = Product::factory()->create();

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertViewIs('products.show')
            ->assertSee($product->name);
    }

    public function test_show_returns_404_for_missing_product(): void
    {
        $this->get(route('products.show', 999))->assertNotFound();
    }

    public function test_edit_shows_form_with_product(): void
    {
        $product = Product::factory()->create();

        $this->get(route('products.edit', $product))
            ->assertOk()
            ->assertViewIs('products.edit')
            ->assertSee($product->name);
    }

    public function test_update_changes_product(): void
    {
        $product = Product::factory()->create();

        $this->put(route('products.update', $product), $this->validData(['name' => 'Updated name']))
            ->assertRedirect(route('products.show', $product))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated name']);
    }

    public function test_update_rejects_invalid_data(): void
    {
        $product = Product::factory()->create(['name' => 'Original name']);

        $this->put(route('products.update', $product), $this->validData(['name' => '']))
            ->assertSessionHasErrors('name');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Original name']);
    }

    public function test_destroy_deletes_product(): void
    {
        $product = Product::factory()->create();

        $this->delete(route('products.destroy', $product))
            ->assertRedirect(route('products.index'))
            ->assertSessionHas('status');

        $this->assertModelMissing($product);
    }
}