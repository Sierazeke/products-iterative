<div class="mb-4">
    <label for="name" class="mb-1 block font-medium">Name</label>
    <input id="name" name="name" type="text" value="{{ old('name', $product->name) }}" class="w-full rounded border p-2">
    @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label for="description" class="mb-1 block font-medium">Description</label>
    <textarea id="description" name="description" rows="4" class="w-full rounded border p-2">{{ old('description', $product->description) }}</textarea>
    @error('description') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label for="price" class="mb-1 block font-medium">Price</label>
    <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $product->price) }}" class="w-full rounded border p-2">
    @error('price') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white">Save</button>
<a href="{{ route('products.index') }}" class="ml-2 text-gray-600">Cancel</a>