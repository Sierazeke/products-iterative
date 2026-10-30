@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <h1 class="mb-2 text-2xl font-bold">{{ $product->name }}</h1>
    <p class="mb-4 text-lg">{{ number_format($product->price, 2) }}</p>
    <p class="mb-6 whitespace-pre-line">{{ $product->description }}</p>

    <a href="{{ route('products.edit', $product) }}" class="text-blue-600">Edit</a>
    <a href="{{ route('products.index') }}" class="ml-2 text-gray-600">Back to list</a>
@endsection