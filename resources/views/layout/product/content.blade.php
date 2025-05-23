@extends('welcome')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Products</h3>

                                <div class="card-tools">
                                    <form action="{{ url('/search/products') }}" method="GET">
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <input type="text" name="query" class="form-control float-right"
                                                placeholder="Search" value="{{ request('query') }}">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-default">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <button class="btn btn-primary mt-2" data-toggle="modal" data-target="#modal-product"
                                        id="addProductButton">
                                        Add Product
                                    </button>
                                </div>
                            </div>

                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Image</th>

                                            <th>Category</th>
                                            <th>Description</th>
                                            <th>Unit</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                        <tr>
                                            <td style="max-width: 15%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                {{ $product->product_id }}
                                            </td>
                                            <td style="max-width: 15%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                {{ $product->name }}
                                            </td>
                                            <td style="max-width: 15%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                @if ($product->image)
                                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                                @else
                                                    <span>No image</span>
                                                @endif
                                            </td>
                                            <td style="max-width: 15%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                {{ $product->category->name ?? 'N/A' }}
                                            </td>
                                            <td style="max-width: 15%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                {{ $product->description }}
                                            </td>
                                            <td style="max-width: 15%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                {{ $product->unit }}
                                            </td>
                                            <td style="max-width: 15%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                {{ $product->quantity }}
                                            </td>
                                            <td style="max-width: 15%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                {{ number_format($product->price, 2) }}
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-product" 
                                                        data-id="{{ $product->product_id }}" data-name="{{ $product->name }}"
                                                        data-category="{{ $product->category_id }}" data-description="{{ $product->description }}"
                                                        data-unit="{{ $product->unit }}" data-quantity="{{ $product->quantity }}"
                                                        data-image="{{ $product->image }}" data-price="{{ $product->price }}">
                                                    Edit
                                                </button>
                                                <form action="{{ url('/products/delete/' . $product->product_id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" 
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="card-footer clearfix">
                                    <div class="float-right">
                                        {{ $products->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal thêm/sửa sản phẩm --}}
                <div class="modal fade" id="modal-product">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="editProductForm" action="" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h4 class="modal-title">Product Form</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="modalProductName">Name</label>
                                        <input type="text" name="name" id="modalProductName" class="form-control"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="modalCategoryId">Category</label>
                                        {{-- <select name="category_id" id="modalCategoryId" class="form-control" required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->category_id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select> --}}
                                        <select name="category_id" id="modalCategoryId" class="form-control" required>
                                            <option value="22969832-49b8-3219-95b8-700a469d5d45">ut</option>
                                        </select>

                                    </div>

                                    <div class="form-group">
                                        <label for="modalUnit">Unit</label>
                                        <input type="text" name="unit" id="modalUnit" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="modalQuantity">Quantity</label>
                                        <input type="number" name="quantity" id="modalQuantity" class="form-control"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="modalPrice">Price</label>
                                        <input type="number" step="0.01" name="price" id="modalPrice"
                                            class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="modalDescription">Description</label>
                                        <textarea name="description" id="modalDescription" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="modalImage">Image</label>
                                    <input type="file" name="image" id="modalImage" class="form-control">
                                </div>
                                <input type="hidden" name="old_image" id="modalOldImage">

                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>


                            </form>
                        </div>
                    </div>
                </div>

                {{-- Hiển thị thông báo thành công --}}
                @if (session('success'))
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: '{{ session('success') }}',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        });
                    </script>
                @endif

                {{-- Hiển thị thông báo lỗi từ session (ví dụ dùng session('error') để báo lỗi tùy chỉnh) --}}
                @if (session('error'))
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: '{{ session('error') }}',
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'OK'
                            });
                        });
                    </script>
                @endif

                {{-- Hiển thị lỗi validation (lấy lỗi đầu tiên) --}}
                @if ($errors->any())
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi dữ liệu!',
                                text: '{{ $errors->first() }}',
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'OK'
                            });
                        });
                    </script>
                @endif

            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
$('#modal-product').on('show.bs.modal', function(event) {
    const button = $(event.relatedTarget);
    const modal = $(this);
    const id = button.data('id');

    if (id) {
        // === EDIT MODE ===
        modal.find('#modalProductName').val(button.data('name'));
        modal.find('#modalCategoryId').val(button.data('category'));
        modal.find('#modalUnit').val(button.data('unit'));
        modal.find('#modalQuantity').val(button.data('quantity'));
        modal.find('#modalPrice').val(button.data('price'));
        modal.find('#modalDescription').val(button.data('description'));
        modal.find('#modalOldImage').val(button.data('image')); // thêm dòng này

        modal.find('#editProductForm').attr('action', `/products/update/${id}`);
        modal.find('input[name="_method"]').val('PUT');
    } else {
        // === ADD MODE ===
        modal.find('#editProductForm')[0].reset(); // reset form
        modal.find('#editProductForm').attr('action', '/products/create');
        modal.find('input[name="_method"]').val('POST');
    }
});

</script>
@endsection