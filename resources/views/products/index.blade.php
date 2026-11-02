<x-layout title="Products">
    <h1>Products</h1>
    <p><a href="{{ route('products.create') }}">New product</a></p>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Expires</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ $product->expiration_date?->format('d.m.Y') ?? '-' }}</td>
                    <td>{{ $product->status->label() }}</td>
                    <td>
                        {{-- Show / Edit / Delete kā iepriekš --}}
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No products yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</x-layout>