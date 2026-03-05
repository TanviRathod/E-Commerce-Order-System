<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">
                    {{ isset($product) ? 'Edit Product' : 'Add Product' }}
                </h2>
                <a href="{{ route('products.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow text-sm">
                    Back to List
                </a>
            </div>
            <div class="bg-white shadow-md rounded-xl p-6">
                <form action="{{ isset($product)  ? route('products.update', $product->id)  : route('products.store') }}" 
                    method="POST">
                    @csrf
                    @if(isset($product))
                        @method('PUT')
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">
                                Product Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name"
                                value="{{ old('name', $product->name ?? '') }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-200 focus:border-green-400"
                                placeholder="Enter Product Name">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">
                                SKU <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="sku"
                                value="{{ old('sku', $product->sku ?? '') }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-200 focus:border-green-400"
                                placeholder="Enter SKU">
                            @error('sku')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">
                                Price <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" name="price"
                                value="{{ old('price', $product->price ?? '') }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-200 focus:border-green-400"
                                placeholder="Enter Price">
                            @error('price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">
                                Stock <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="stock"
                                value="{{ old('stock', $product->stock ?? '') }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-200 focus:border-green-400"
                                placeholder="Enter Stock Quantity">
                            @error('stock')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-200 focus:border-green-400">
                                <option value="active"
                                    {{ old('status', $product->status ?? '') == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive"
                                    {{ old('status', $product->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-end mt-8">
                        <button type="submit"
                            class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg shadow transition duration-200">
                            {{ isset($product) ? 'Update Product' : 'Save Product' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>