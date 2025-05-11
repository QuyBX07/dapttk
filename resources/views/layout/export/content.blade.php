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
                                <h3 class="card-title">Exports</h3>

                                <div class="card-tools">
                                    <form action="{{ url('/search/exports') }}" method="GET">
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
                                        data-toggle="modal" data-target="#modal-export" id="addExportButton">
                                        Add Export
                                    </button>
                                </div>
                            </div>

                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Export ID</th>
                                            <th>Customer</th>
                                            <th>Total Amount</th>
                                            <th>Note</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Create by</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($exports as $export)
                                            <tr class="export-row" data-id="{{ $export['export_id'] }}">
                                                <td>{{ $export['export_id'] }}</td>
                                                <td>{{ $export['customer']['name'] }}</td>
                                                <td>{{ number_format($export['total_amount'], 2) }}</td>
                                                <td>{{ $export['note'] }}</td>
                                                <td>{{ \Carbon\Carbon::parse($export['created_at'])->format('Y-m-d H:i:s') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($export['updated_at'])->format('Y-m-d H:i:s') }}</td>
                                                <td>{{ $export['account']['name'] }}</td>
                                                <td>
                                                    <form action="{{ url('/exports/delete/' . $export->export_id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"
                                                        onclick="event.stopPropagation(); return confirm('Bạn có chắc chắn muốn xóa xuất hàng này không?')">
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
                                        {{ $exports->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Improved Modal for adding exports -->
            <div class="modal fade" id="modal-export">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Export Details</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="exportForm" action="{{ url('/exports/create') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="customer_id">Customer</label>
                                    <select class="form-control select2" id="customer_id" name="customer_id" required>
                                        <option value="">-- Select Customer --</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Người lập phiếu -->
                                <div class="form-group">
                                    <label>Người tạo</label>
                                    <input type="text" class="form-control" value="Nam" readonly>
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
                                            <select class="form-control select2 product-select" name="details[0][product_id]" required>
                                                <option value="">-- Select Product --</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->product_id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Quantity</label>
                                            <input type="number" class="form-control quantity-input" name="details[0][quantity]" min="1" value="1" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Price</label>
                                            <input type="number" class="form-control price-input" name="details[0][price]" min="0" step="0.01" required>
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
                                            <input type="text" name="total_amount" id="total_amount" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Export</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- detail modal --}}
            <div class="modal fade" id="modal-export-detail">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Export Detail</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Export ID:</strong> <span id="detail-export-id"></span></p>
                            <p><strong>Customer:</strong> <span id="detail-customer"></span></p>
                            <p><strong>Created by:</strong> <span id="detail-account"></span></p>
                            <p><strong>Note:</strong> <span id="detail-note"></span></p>
                            <p><strong>Total Amount:</strong> <span id="detail-total"></span></p>
                            <p><strong>Export Date:</strong> <span id="detail-date"></span></p>
                            <hr>
                            <h5>Products</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
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
        </div>
    </div>
@endsection
