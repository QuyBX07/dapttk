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
                                <h3 class="card-title">Customers</h3>

                                <div class="card-tools">
                                    <form action="{{ url('/search/customers') }}" method="GET">

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
                                        data-toggle="modal" data-target="#modal-default" id="addCustomerButton">
                                      
                                        Add Customer
                                  </button>
                                </div>

                                <!-- /.card-header -->
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Address</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($customers as $customer)
                                                <tr>
                                                    <td style="max-width: 15%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">{{ $customer->customer_id }}</td>
                                                    <td style="max-width: 20%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">{{ $customer->name }}</td>
                                                    <td style="max-width: 15%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">{{ $customer->phone }}</td>
                                                    <td style="max-width: 20%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">{{ $customer->email }}</td>
                                                    <td style="max-width: 30%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">{{ $customer->address }}</td>
                                                    <td>
                                                      <button type="button" class="btn btn-primary"
                                                      data-toggle="modal"
                                                      data-target="#modal-default"
                                                      data-id="{{ $customer->customer_id }}"
                                                      data-name="{{ $customer->name }}"
                                                      data-phone="{{ $customer->phone }}"
                                                      data-email="{{ $customer->email }}"
                                                      data-address="{{ $customer->address }}">
                                                      Edit
                                                  </button>
                                                  

                                                        <form action="" method="POST" style="display:inline;">
                                                            @csrf

                                                        </form>

                                                        <form action="{{ url('/customers/delete/' . $customer->customer_id) }}" method="POST" style="display:inline;">
                                                          @csrf
                                                          @method('DELETE')
                                                          <button type="submit" class="btn btn-danger"
                                                              onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này không?')">
                                                              Delete
                                                          </button>
                                                      </form>
                                                      


                                                    </td>
                                            @endforeach

                                        </tbody>
                                    </table>

                                    <div class="card-footer clearfix">
                                        <div class="float-right">
                                            {{ $customers->links() }}
                                        </div>
                                    </div>

                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

         
                <div class="modal fade" id="modal-default">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Default Modal</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                              <div class="modal-body">
                                <form id="editCustomerForm" action="" method="POST">
                                  @csrf
                                  @method('PUT')
                              
                                  <div class="form-group">
                                      <label for="modalCustomerName">Name</label>
                                      <input type="text" name="name" id="modalCustomerName" class="form-control" required>
                                      
                                  </div>
                              
                                  <div class="form-group">
                                      <label for="modalCustomerPhone">Phone</label>
                                      <input type="text" name="phone" id="modalCustomerPhone" class="form-control" required >
                                  </div>
                              
                                  <div class="form-group">
                                      <label for="modalCustomerEmail">Email</label>
                                      <input type="email" name="email" id="modalCustomerEmail" class="form-control" required>
                                  </div>
                              
                                  <div class="form-group">
                                      <label for="modalCustomerAddress">Address</label>
                                      <textarea name="address" id="modalCustomerAddress" class="form-control" rows="3" required></textarea>
                                  </div>
                              
                                  <div class="modal-footer justify-content-between">
                                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                      <button type="submit" class="btn btn-primary">Save changes</button>
                                  </div>
                              </form>
                              
                            </div>
                            
                            </div>
                            
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
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

            
            
               
              
        @endsection

@section('scripts')
<script>
        $('#modal-default').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const modal = $(this);

            const id = button.data('id');

            if (id) {
                // === EDIT MODE ===
                const name = button.data('name');
                const phone = button.data('phone');
                const email = button.data('email');
                const address = button.data('address');

                modal.find('#modalCustomerName').val(name);
                modal.find('#modalCustomerPhone').val(phone);
                modal.find('#modalCustomerEmail').val(email);
                modal.find('#modalCustomerAddress').val(address);

                modal.find('#editCustomerForm').attr('action', `/customers/update/${id}`);
                modal.find('input[name="_method"]').val('PUT');
            } else {
                // === ADD MODE ===
                modal.find('#editCustomerForm')[0].reset(); // reset form
                modal.find('#editCustomerForm').attr('action', '/customers/create');
                modal.find('input[name="_method"]').val('POST');
            }
        });
    </script>
@endsection
