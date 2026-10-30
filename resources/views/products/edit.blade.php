<x-layout :title="'Edit ' . $product->name">
    <h1>Edit product</h1>

    <form method="POST" action="{{ route('products.update', $product) }}">
        @csrf
        @method('PUT')
        @include('products._form')
    </form>
</x-layout>