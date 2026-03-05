<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                Browse Products
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $product)
                    <div class="bg-white shadow-md rounded-xl p-4 flex flex-col justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h3>
                            <p class="text-gray-600">SKU: {{ $product->sku }}</p>
                            <p class="mt-2 text-gray-800 font-bold">₹ {{ $product->price }}</p>
                        </div>
                        <div class="mt-4">
                            <form action="{{ route('customer.cart.add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                    class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow
                                    {{ $product->stock === 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    @if($product->stock === 0) disabled @endif>
                                    {{ $product->stock === 0 ? 'Out of Stock' : 'Add to Cart' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 col-span-3 text-center">No products available.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
