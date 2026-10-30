@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Products</h1>
        <a href="{{ route('products.create') }}" class="rounded bg-blue-600 px-3 py-2 text-white">New product</a>
    </div>

    <table class="w-full bg-white shadow">
        <thead>
            <tr class="border-b text-left">
                <th class="p-3">Name</th>
                <th class="p-3">Price</th>
                <th class="p-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr class="border-b">
                    <td class="p-3">{{ $product->name }}</td>
                    <td class="p-3">{{ number_format($product->price, 2) }}</td>
                    <td class="p-3 text-right">
                        <a href="{{ route('products.show', $product) }}" class="text-blue-600">Show</a>
                        <a href="{{ route('products.edit', $product) }}" class="ml-2 text-blue-600">Edit</a>
                    </td>
                    <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline"
                        onsubmit="return confirm('Delete this product?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="ml-2 text-red-600">Delete</button>
                    </form>
                </tr>
            @empty
                <tr><td colspan="3" class="p-3">No products yet.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection