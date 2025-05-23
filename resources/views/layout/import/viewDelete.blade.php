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

                                            <th>Delete At</th>
                                            <th>Delete by</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($imports as $import)
                                            <tr class="import-row" data-id="{{ $import['import_id'] }}">
                                                <td style="max-width: 20%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;"
                                                    title="ID: {{ $import['import_id'] }}">
                                                    PX-{{ \Carbon\Carbon::parse($import['created_at'])->format('Ym') }}-{{ sprintf('%03d', $loop->iteration) }}
                                                </td>
                                                <td
                                                    style="max-width: 20%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                    {{ $import['supplier']['name'] }}</td>
                                                <td
                                                    style="max-width: 15%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                    {{ number_format($import['total_amount'], 2) }}</td>
                                                <td
                                                    style="max-width: 20%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                    {{ $import['note'] }}</td>

                                                <td style="max-width: 20%; white-space: normal;">
                                                    {{ \Carbon\Carbon::parse($import['updated_at'])->format('Y-m-d H:i:s') }}
                                                </td>
                                                <td
                                                    style="max-width: 20%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                                    {{ $import['account']['name'] }}</td>



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
                            <p>
                                <strong>Mã phiếu:</strong>
                                <span id="detail-import-code" title="" style="cursor: help;"></span>
                            </p>
                            <p><strong>Supplier:</strong> <span id="detail-supplier"></span></p>
                            <p><strong>Delete by:</strong> <span id="detail-account"></span></p>
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



        </div>
    </div>

    <!-- JavaScript to handle dynamic product addition and calculations -->
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            // Hiển thị chi tiết nhập hàng khi nhấn vào row
            $('.import-row').on('click', function() {
                console.log('da click');
                const importId = $(this).data('id');

                $.get(`/imports/${importId}`, function(data) {
                    console.log(data); // Kiểm tra dữ liệu trả về từ server

                    // Gán dữ liệu vào modal
                  const createdDate = new Date(data.created_at);
                    const yearMonth = createdDate.getFullYear().toString() + String(createdDate
                        .getMonth() + 1).padStart(2, '0');
                    const code = `PX-${yearMonth}-001`; // Bạn có thể thay số thứ tự

                    $('#detail-import-code').text(code);
                    $('#detail-import-code').attr('title', 'ID gốc: ' + data.import_id);
                    $('#detail-supplier').text(data.supplier.name);
                    $('#detail-total').text(parseFloat(data.total_amount).toFixed(2));
                    $('#detail-date').text(new Date(data.created_at).toLocaleString());
                    $('#detail-note').text(data.note ?? 'Không có ghi chú');
                    $('#detail-account').text(data.account.name);

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

                    // Mở modal
                    $('#modal-import-detail').modal('show');
                }).fail(function() {
                    alert('Error fetching import details');
                });
            });
        });
    </script>
@endsection
