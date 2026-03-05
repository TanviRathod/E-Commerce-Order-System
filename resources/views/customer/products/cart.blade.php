<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                Your Cart
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

            @if(!empty($cart))
                <div class="bg-white shadow-md rounded-xl overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Subtotal</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                           @foreach($cart as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $item->product->name }}</td>
                                <td class="px-6 py-4">₹ {{ $item->product->price }}</td>
                                <td class="px-6 py-4">
                                        <button 
                                            class="update-qty bg-gray-300 hover:bg-gray-400 px-2 rounded"
                                            data-id="{{ $item->id }}"
                                             data-url="{{ route('cart.update', $item->id) }}"
                                            data-type="decrease">
                                            −
                                        </button>
                                        <span class="qty-text font-semibold">
                                            {{ $item->quantity }}
                                        </span>
                                        <button 
                                           class="update-qty bg-gray-300 px-2 rounded
                                            {{ $item->quantity >= $item->product->stock ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}
                                            data-id="{{ $item->id }}"
                                             data-url="{{ route('cart.update', $item->id) }}"
                                            data-type="increase">
                                            +
                                        </button>
                                      <!-- @if($item->quantity >= $item->product->stock)
                                            <p class="text-red-500 text-xs font-medium stock-msg">
                                                Out of Stock
                                        </p>
                                        @endif -->
                                </td>
                                <td class="px-6 py-4">₹ {{ $item->product->price * $item->quantity }}</td>
                                <td class="px-6 py-4 text-center">
                                   <button 
                                        data-id="{{ $item->id }}" data-url="{{ route('cart.remove', $item->id) }}"
                                        class="remove-item bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                   
                    <div class="flex justify-end mt-4">
                        <h3 class="text-xl font-semibold">   Total: ₹ <span class="total-amount">{{ $total }}</span></h3>
                    </div>

                    <div class="flex justify-end mt-4">
                        <form action="{{route('orders.store')}}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow">
                                Place Order
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <p class="text-gray-500 text-center py-6">Your cart is empty.</p>
            @endif
             <div class="mt-4">
                        {{ $cart->links() }}
                    </div>
        </div>
        
    </div>
</x-app-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

 $('.update-qty').click(function(){

        let btn = $(this);
        let id = $(this).data('id');
        let type = $(this).data('type');
        let row = $(this).closest('tr');
        let url = $(this).data('url');
        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                type: type
            },
            success: function(response){
                if(response.success){
                    row.find('.qty-text').text(response.quantity);
                    row.find('td:eq(3)').text('₹ ' + response.subtotal);
                    $('.total-amount').text(response.total);
                     if(response.cart_count > 0){
                        $('#navbar-cart-count').text(response.cart_count).removeClass('hidden');
                    } else {
                        $('#navbar-cart-count').addClass('hidden');
                    }
                     if(type === 'decrease'){
                    row.find('[data-type="increase"]').prop('disabled', false);
                    row.find('[data-type="increase"]').removeClass('opacity-50 cursor-not-allowed');
                }
                }else {
                   if(type === 'increase'){
                    btn.prop('disabled', true);
                    btn.addClass('opacity-50 cursor-not-allowed');
                }
                }
            }
        });

    });

    $('.remove-item').on("click",function() {
        let id = $(this).data('id');
        let row = $(this).closest('tr');
        let url = $(this).data('url');
        $.ajax({
            url: url,
            type: "DELETE",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if(response.success) {
                    row.remove();
                    location.reload(); 
                }
            },
            error: function() {
                alert('Something went wrong!');
            }
        });

    });

});
</script>
