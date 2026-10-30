@extends('layouts.app')

@section('title', 'Edit ' . $product->name)

@section('content')
    <h1 class="mb-4 text-2xl font-bold">Edit product</h1>

    <form method="POST" action="{{ route('products.update', $product) }}">
        @csrf
        @method('PUT')
        @include('products._form')
    </form>
@endsection