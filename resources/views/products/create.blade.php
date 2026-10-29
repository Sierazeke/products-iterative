@extends('layouts.app')

@section('title', 'New product')

@section('content')
    <h1 class="mb-4 text-2xl font-bold">New product</h1>

    <form method="POST" action="{{ route('products.store') }}">
        @csrf
        @include('products._form')
    </form>
@endsection