@extends('welcome')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Imports</h3>

                                <div class="card-tools">
                                    <form action="{{ url('/search/imports') }}" method="GET">
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
                                    <button class="btn btn-primary" style="margin-top: 10px; padding-right: 5px;"
                                        data-toggle="modal" data-target="#modal-import" id="addImportButton">
                                        Add Import
                                    </button>
                                </div>
                            </div>

                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Import ID</th>
                                            <th>Supplier</th>
                                            <th>Total Amount</th>
                                            <th>Note</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Create by</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($imports as $import)
                                            <tr class="import-row" data-id="{{ $import['import_id'] }}">
                                                <td
                                                    style="max-width: 15%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                    {{ $import['import_id'] }}</td>
                                                <td
                                                    style="max-width: 20%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                    {{ $import['supplier']['name'] }}</td>
                                                <td
                                                    style="max-width: 15%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                    {{ number_format($import['total_amount'], 2) }}</td>
                                                <td
                                                    style="max-width: 20%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                    {{ $import['note'] }}</td>
                                                <td
                                                    style="max-width: 20%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                    {{ \Carbon\Carbon::parse($import['created_at'])->format('Y-m-d H:i:s') }}
                                                </td>
                                                <td
                                                    style="max-width: 20%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                    {{ \Carbon\Carbon::parse($import['updated_at'])->format('Y-m-d H:i:s') }}
                                                </td>
                                                <td
                                                    style="max-width: 20%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                    {{ $import['account']['name'] }}</td>


                                                <td>
                                                    <form action="{{ url('/imports/delete/' . $import->import_id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"
                                                        onclick="event.stopPropagation(); return confirm('Bạn có chắc chắn muốn xóa nhập hàng này không?')">
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
                                        {{ $imports->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Improved Modal for adding imports -->
            <div class="modal fade" id="modal-import">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Import Details</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="importForm" action="{{ url('/imports/create') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="supplier_id">Supplier</label>
                                    <select class="form-control select2" id="supplier_id" name="supplier_id" required>
                                        <option value="">-- Select Supplier --</option>
                                        {{-- @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach --}}
                                        <option value="0387d689-fca6-4e88-b512-32bbd9231cca">Spencer Ltd
                                        </option>

                                    </select>
                                </div>

                                <!-- Người lập phiếu -->
                                <div class="form-group">
                                    <label>Người tạo</label>
                                    <!-- Hiển thị tên (readonly, không gửi về server) -->
                                    <input type="text" class="form-control" value="Nam" readonly>

                                    <!-- Trường ẩn để gửi account_id -->
                                    <input type="hidden" name="account_id" value="0990d54e-6cf7-47ca-af95-29237e815e78">
                                </div>


                                <!-- Ghi chú -->
                                <div class="form-group">
                                    <label for="note">Note</label>
                                    <textarea name="note" id="note" rows="3" class="form-control" placeholder="Enter note (optional)"></textarea>
                                </div>

                                <hr>
                                <h5>Products</h5>

                                <div id="product-container">
                                    <div class="product-entry row mb-3">
                                        <div class="col-md-5">
                                            <label>Product</label>
                                            <select class="form-control select2 product-select"
                                                name="details[0][product_id]" required>
                                                <option value="">-- Select Product --</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->product_id }}">{{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Quantity</label>
                                            <input type="number" class="form-control quantity-input"
                                                name="details[0][quantity]" min="1" value="1" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Price</label>
                                            <input type="number" class="form-control price-input" name="details[0][price]"
                                                min="0" step="0.01" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Subtotal</label>
                                            <input type="text" class="form-control subtotal" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2 mb-4">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-info" id="add-product">
                                            <i class="fas fa-plus"></i> Add Product
                                        </button>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8"></div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="total_amount">Total Amount</label>
                                            <input type="text" name="total_amount" id="total_amount"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Import</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- detail modal --}}
            <!-- Modal hiển thị chi tiết -->
            <div class="modal fade" id="modal-import-detail">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Import Detail</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Import ID:</strong> <span id="detail-import-id"></span></p>
                            <p><strong>Supplier:</strong> <span id="detail-supplier"></span></p>
                            <p><strong>Created by:</strong> <span id="detail-account"></span></p>
                            <p><strong>Note:</strong> <span id="detail-note"></span></p>
                            <p><strong>Total Amount:</strong> <span id="detail-total"></span></p>
                            <p><strong>Import Date:</strong> <span id="detail-date"></span></p>
                            <hr>
                            <h5>Products</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qantity</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="detail-products">
                                    <!-- Fill bằng JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


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

    <!-- JavaScript to handle dynamic product addition and calculations -->
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            // $('#importForm').submit(function(event) {
            //     event.preventDefault();
            //     var formData = $(this).serialize(); // Lấy toàn bộ dữ liệu form
            //     console.log(formData); // Log dữ liệu ra console
            //     // Sau khi log, gửi dữ liệu đến server
            // });

            $('#modal-import').on('show.bs.modal', function(event) {
                const modal = $(this);
                const form = modal.find('#importForm')[0];

                form.reset(); // Reset lại form
                // modal.find('#importForm').attr('action', '/imports/add');
                // modal.find('input[name="_method"]').remove();
            });

            let productCounter = 1;

            $('#add-product').click(function() {
                console.log("Add product button clicked");

                const productHtml = `
             <div class="product-entry row mb-3">
                <div class="col-md-5">
                    <select class="form-control select2 product-select" name="details[${productCounter}][product_id]" required>
                        <option value="">-- Select Product --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->product_id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control quantity-input" name="details[${productCounter}][quantity]" min="1" value="1" required>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control price-input" name="details[${productCounter}][price]" min="0" step="0.01" required>
                </div>
                <div class="col-md-2 d-flex">
                    <input type="text" class="form-control subtotal" readonly>
                    <button type="button" class="btn btn-danger ml-1 remove-product">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            `;

                $('#product-container').append(productHtml);

                $('#product-container .product-select').last().select2({
                    width: '100%'
                });


                bindProductEvents();
                productCounter++;
            });

            function bindProductEvents() {
                $('#product-container').off('click', '.remove-product').on('click', '.remove-product', function() {
                    $(this).closest('.product-entry').remove();
                    calculateTotal();
                });


                $('#product-container').off('input', '.quantity-input, .price-input')
                    .on('input', '.quantity-input, .price-input', function() {
                        const row = $(this).closest('.product-entry');
                        const quantity = parseFloat(row.find('.quantity-input').val()) || 0;
                        const price = parseFloat(row.find('.price-input').val()) || 0;
                        const subtotal = quantity * price;

                        row.find('.subtotal').val(formatCurrency(subtotal));
                        calculateTotal();
                    });

            }

            function calculateTotal() {
                let total = 0;
                $('.product-entry').each(function() {
                    const quantity = parseFloat($(this).find('.quantity-input').val()) || 0;
                    const price = parseFloat($(this).find('.price-input').val()) || 0;
                    total += quantity * price;
                });

                $('#total_amount').val(total.toFixed(2)); // không format bằng Intl

            }

            function formatCurrency(value) {
                return new Intl.NumberFormat('vi-VN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(value);
            }

            bindProductEvents();

            // Hiển thị chi tiết nhập hàng khi nhấn vào nút "View Detail"
            $('.import-row').on('click', function() {
                const importId = $(this).data('id');

                $.get(`/imports/${importId}`, function(data) {
                    $('#detail-import-id').text(data.import_id);
                    $('#detail-supplier').text(data.supplier.name);
                    $('#detail-total').text(parseFloat(data.total_amount).toFixed(2));
                    $('#detail-date').text(new Date(data.created_at)
                .toLocaleString()); // Hiển thị thời gian tạo
                    $('#detail-note').text(data.note ?? 'Không có ghi chú');
                    $('#detail-account').text(data.account.name); // Người tạo

                    // Clear sản phẩm cũ
                    $('#detail-products').empty();

                    // Thêm từng sản phẩm vào bảng
                    data.import_details.forEach(function(detail) {
                        $('#detail-products').append(`
                <tr>
                    <td>${detail.product.name}</td>
                    <td>${detail.quantity}</td>
                    <td>${detail.price}</td>
                    <td>${(detail.quantity * parseFloat(detail.price)).toFixed(2)}</td>
                </tr>
            `);
                    });

                    $('#modal-import-detail').modal('show');
                });
            });


        });
    </script>
@endsection
