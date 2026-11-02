<x-layout :title="$product->name">
    <h1>{{ $product->name }}</h1>
    <dl>
        <dt>Quantity</dt>
        <dd>{{ $product->quantity }}</dd>
        <dt>Expiration date</dt>
        <dd>{{ $product->expiration_date?->format('d.m.Y') ?? '-' }}</dd>
        <dt>Status</dt>
        <dd>{{ $product->status->label() }}</dd>
        <dt>Description</dt>
        <dd>{{ $product->description }}</dd>
    </dl>

    <a href="{{ route('products.edit', $product) }}">Edit</a>
    <a href="{{ route('products.index') }}">Back to list</a>
</x-layout>