<?php

namespace App\Http\Controllers;

use DomainException;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Enums\ProductStatus;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->get();

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create', ['product' => new Product]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validated($request);

        $product = Product::create($validated);

        return redirect()->route('products.show', $product)
            ->with('status', 'Product created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $this->validated($request);

        $product->update($validated);

        return redirect()->route('products.show', $product)
            ->with('status', 'Product Updated!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('status', 'Product Deleted!');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
            'expiration_date' => ['nullable', 'date'],
            'status' => ['required', Rule::enum(ProductStatus::class)],
        ]);
    }

    public function adjustQuantity(Request $request, Product $product)
    {
        $validated = $request->validate([
            'amount' => ['required', 'integer', 'min:1', 'max:100000'],
            'action' => ['required', Rule::in(['increase', 'decrease'])],
        ]);

        $amount = (int) $validated['amount'];

        $delta = $validated['action'] === 'increase' ? $amount : -$amount;

        try {
            match ($validated['action']) {
                'increase' => $product->increaseQuantity($amount),
                'decrease' => $product->decreaseQuantity($amount),
            };
        } catch (DomainException $e) {
            throw ValidationException::withMessages(['amount' => $e->getMessage()]);
        }

        return redirect()->route('products.show', $product)
            ->with('status', "Quantity changed to {$product->quantity}.");
    }
}
