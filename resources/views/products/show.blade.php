<x-layout :title="$product->name">
    <h1>{{ $product->name }}</h1>
    <p>{{ number_format($product->price, 2) }}</p>
    <p>{{ $product->description }}</p>

    <a href="{{ route('products.edit', $product) }}">Edit</a>
    <a href="{{ route('products.index') }}">Back to list</a>
</x-layout>