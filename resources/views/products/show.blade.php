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

    <h2>Change stock</h2>
    <form method="POST" action="{{ route('products.adjust-quantity', $product) }}" class="quantity-form">
        @csrf
        @method('PATCH')

        <label for="amount">Amount</label>
        <input id="amount" name="amount" type="number" min="1" step="1" value="1" required>

        <button type="submit" name="action" value="increase">Receive (+)</button>
        <button type="submit" name="action" value="decrease">Sell (&minus;)</button>
    </form>

    <a href="{{ route('products.edit', $product) }}">Edit</a>
    <a href="{{ route('products.index') }}">Back to list</a>

</x-layout>