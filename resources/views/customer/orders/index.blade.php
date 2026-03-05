<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                Your Orders
            </h2>

            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if(!empty($orders) && $orders->count())
                @foreach($orders as $order)
                    <div class="bg-white shadow-md rounded-xl overflow-hidden mb-6">
                        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="font-semibold text-lg">Order #{{ $order->id }}</h3>
                            <span class="px-3 py-1 rounded-full text-sm 
                                {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->status == 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($order->items as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $item->product->name }}</td>
                                    <td class="px-6 py-4">₹ {{ $item->price }}</td>
                                    <td class="px-6 py-4">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4">₹{{ $item->price * $item->quantity }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                       <div class="flex justify-between items-center p-4 border-t border-gray-200">
                            <span class="font-semibold text-lg">Total: ₹ {{ $order->total_amount }}</span>
                            <div class="flex items-center gap-2">
                                @if($order->status == 'pending')
                                    <form action="{{ route('orders.pay', $order->id) }}" method="POST" class="pay-form">
                                        @csrf
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg shadow">
                                            Pay Now
                                        </button>
                                    </form>
                                @elseif($order->status == 'paid')
                                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="cancel-form">
                                        @csrf
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg shadow">
                                            Cancel Order
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-gray-500 text-center py-6">You have no orders yet.</p>
            @endif
        </div>
    </div>
</x-app-layout>