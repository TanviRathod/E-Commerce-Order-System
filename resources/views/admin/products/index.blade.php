<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-800">
                    Product List
                </h2>
                <a href="{{ route('products.create') }}"
                   class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow">
                    + Add Product
                </a>
            </div>
            <div class="bg-white shadow-md rounded-xl overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-green-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">SKU</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($products as $key => $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $product->name }}</td>
                                <td class="px-6 py-4">{{ $product->sku }}</td>
                                <td class="px-6 py-4">₹ {{ $product->price }}</td>
                                <td class="px-6 py-4">{{ $product->stock }}</td>
                               <td class="px-6 py-4">
                                    <button 
                                        data-id="{{ $product->id }}"
                                        data-url="{{ route('admin.product.status', $product->id) }}"
                                        class="status-toggle relative inline-flex items-center h-6 rounded-full w-11 transition-colors duration-300
                                        {{ $product->status === 'active' ? 'bg-green-500' : 'bg-gray-300' }}">

                                        <span class="toggle-circle inline-block w-4 h-4 transform bg-white rounded-full transition-transform duration-300
                                            {{ $product->status === 'active' ? 'translate-x-6' : 'translate-x-1' }}">
                                        </span>
                                    </button>
                                </td>
                                  <td class="px-6 py-4 text-center space-x-2">
                                    <a href="{{ route('products.edit', $product->id) }}"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button type="button"
                                            data-id="{{ $product->id }}"
                                            data-url="{{route('products.destroy',$product->id)}}"
                                            class="btn-delete bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-gray-500">
                                    No products found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
              <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
        $(document).ready(function(){
        $('.status-toggle').click(function() {
            var productId = $(this).data('id');
            var button = $(this);
            let url = button.data('url');
            var circle = button.find('.toggle-circle');
            $.ajax({
                url: url,
                type: 'PATCH',
                data: {
                     id: productId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                     if(response.status === 'active'){
                        button.removeClass('bg-gray-300').addClass('bg-green-500');
                        circle.removeClass('translate-x-1').addClass('translate-x-6');
                    } else {
                        button.removeClass('bg-green-500').addClass('bg-gray-300');
                        circle.removeClass('translate-x-6').addClass('translate-x-1');
                    }
                },
                error: function(xhr) {
                    alert('Failed to update status. Please try again.');
                }
            });
        });


        $('.btn-delete').click(function(e) {
        e.preventDefault();
        var productId = $(this).data('id'); 
        var url = $(this).data('url'); 
        Swal.fire({
            title: 'Are you sure?',
            text: "This product will be deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Deleted!', response.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            }
        });
    });
});
</script>