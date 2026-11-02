@use('App\Enums\ProductStatus')

<div>
    <label for="name">Name</label>
    <input id="name" name="name" type="text" value="{{ old('name', $product->name) }}">
    @error('name') <p>{{ $message }}</p> @enderror
</div>

<div>
    <label for="quantity">Quantity</label>
    <input id="quantity" name="quantity" type="number" min="0" step="1" value="{{ old('quantity', $product->quantity) }}">
    @error('quantity') <p>{{ $message }}</p> @enderror
</div>

<div>
    <label for="description">Description</label>
    <textarea id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
    @error('description') <p>{{ $message }}</p> @enderror
</div>

<div>
    <label for="expiration_date">Expiration date</label>
    <input id="expiration_date" name="expiration_date" type="date" value="{{ old('expiration_date', $product->expiration_date?->format('Y-m-d')) }}">
    @error('expiration_date') <p>{{ $message }}</p> @enderror
</div>

<div>
    <label for="status">Status</label>
    <select id="status" name="status">
        @foreach (ProductStatus::cases() as $status)
            <option value="{{ $status->value }}" @selected(old('status', $product->status?->value) === $status->value)>
                {{ $status->label() }}
            </option>
        @endforeach
    </select>
    @error('status') <p>{{ $message }}</p> @enderror
</div>

<button type="submit">Save</button>
<a href="{{ route('products.index') }}">Cancel</a>