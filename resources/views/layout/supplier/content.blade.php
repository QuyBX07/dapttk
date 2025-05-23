@extends('welcome') 
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Suppliers</h3>

                                <div class="card-tools">
                                    <form action="{{ url('/search/suppliers') }}" method="GET">
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <input type="text" name="query" class="form-control float-right" placeholder="Search" value="{{ request('query') }}">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-default">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <button class="btn btn-primary" style="margin-top: 10px; padding-right: 5px;" data-toggle="modal" data-target="#modal-default" id="addSupplierButton">
                                        Add Supplier
                                  </button>
                                </div>

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
                                            @foreach ($suppliers as $supplier)
                                                <tr>
                                                    <td>{{ $supplier->supplier_id }}</td>
                                                    <td>{{ $supplier->name }}</td>
                                                    <td>{{ $supplier->phone }}</td>
                                                    <td>{{ $supplier->email }}</td>
                                                    <td>{{ $supplier->address }}</td>
                                                    <td>
                                                      <button type="button" class="btn btn-primary"
                                                      data-toggle="modal"
                                                      data-target="#modal-default"
                                                      data-id="{{ $supplier->supplier_id }}"
                                                      data-name="{{ $supplier->name }}"
                                                      data-phone="{{ $supplier->phone }}"
                                                      data-email="{{ $supplier->email }}"
                                                      data-address="{{ $supplier->address }}">
                                                      Edit
                                                  </button>

                                                    <form action="{{ url('/suppliers/delete/' . $supplier->supplier_id) }}" method="POST" style="display:inline;">
                                                      @csrf
                                                      @method('DELETE')
                                                      <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa nhà cung cấp này không?')">Delete</button>
                                                    </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <div class="card-footer clearfix">
                                        <div class="float-right">
                                            {{ $suppliers->links() }}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div>
        </div>

        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Supplier Details</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editSupplierForm" action="" method="POST">
                            @csrf
                            @method('PUT')
                        
                            <div class="form-group">
                                <label for="modalSupplierName">Name</label>
                                <input type="text" name="name" id="modalSupplierName" class="form-control" required>
                            </div>
                        
                            <div class="form-group">
                                <label for="modalSupplierPhone">Phone</label>
                                <input type="text" name="phone" id="modalSupplierPhone" class="form-control" required>
                            </div>
                        
                            <div class="form-group">
                                <label for="modalSupplierEmail">Email</label>
                                <input type="email" name="email" id="modalSupplierEmail" class="form-control" required>
                            </div>
                        
                            <div class="form-group">
                                <label for="modalSupplierAddress">Address</label>
                                <textarea name="address" id="modalSupplierAddress" class="form-control" rows="3" required></textarea>
                            </div>
                        
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
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

    </div>
@endsection

@section('scripts')
<script>
    $('#modal-default').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const modal = $(this);

        const id = button.data('id');

        if (id) {
            const name = button.data('name');
            const phone = button.data('phone');
            const email = button.data('email');
            const address = button.data('address');

            modal.find('#modalSupplierName').val(name);
            modal.find('#modalSupplierPhone').val(phone);
            modal.find('#modalSupplierEmail').val(email);
            modal.find('#modalSupplierAddress').val(address);

            modal.find('#editSupplierForm').attr('action', `/suppliers/update/${id}`);
            modal.find('input[name="_method"]').val('PUT');
        } else {
            modal.find('#editSupplierForm')[0].reset();
            modal.find('#editSupplierForm').attr('action', '/suppliers/create');
            modal.find('input[name="_method"]').val('POST');
        }
    });
</script>
@endsection
