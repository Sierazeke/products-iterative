<div>
    <label for="name">Name</label>
    <input id="name" name="name" type="text" value="{{ old('name', $product->name) }}">
    @error('name') <p>{{ $message }}</p> @enderror
</div>

<div>
    <label for="description">Description</label>
    <textarea id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
    @error('description') <p>{{ $message }}</p> @enderror
</div>

<div>
    <label for="price">Price</label>
    <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $product->price) }}">
    @error('price') <p>{{ $message }}</p> @enderror
</div>

<button type="submit">Save</button>
<a href="{{ route('products.index') }}">Cancel</a>