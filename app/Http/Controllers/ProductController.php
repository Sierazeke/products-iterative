<?php

namespace App\Http\Controllers;

use App\Enums\ProductStatus;
use App\Models\Product;
use DomainException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create', ['product' => new Product]);
    }

    public function store(Request $request)
    {
        $product = Product::create($this->validated($request));

        return redirect()->route('products.show', $product)
            ->with('status', 'Product created.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $product->update($this->validated($request));

        return redirect()->route('products.show', $product)
            ->with('status', 'Product Updated!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('status', 'Product Deleted!');
    }

    public function adjustQuantity(Request $request, Product $product)
    {
        $validated = $request->validate([
            'amount' => ['required', 'integer', 'min:1', 'max:100000'],
            'action' => ['required', Rule::in(['increase', 'decrease'])],
        ]);

        $amount = (int) $validated['amount'];

        try {
            match ($validated['action']) {
                'increase' => $product->increaseQuantity($amount),
                'decrease' => $product->decreaseQuantity($amount),
            };
        } catch (DomainException $e) {
            throw ValidationException::withMessages(['amount' => $e->getMessage()]);
        }

        $message = "Quantity changed to {$product->quantity}.";

        if ($request->expectsJson()) {
            return response()->json([
                'quantity' => $product->quantity,
                'message' => $message,
            ]);
        }

        return redirect()->route('products.show', $product)->with('status', $message);
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
}