@extends('welcome')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Accounts</h3>

                            <div class="card-tools">
                                <form action="{{ url('/search/categories') }}" method="GET">
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
                                <button class="btn btn-primary" style="margin-top: 10px;"
                                    data-toggle="modal" data-target="#modal-default" id="addCategoryButton">
                                    Add Category
                                </button>
                            </div>

                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Role</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($accounts as $account)
                                            <tr>
                                                <td>{{ $account->id }}</td>
                                                <td>{{ $account->name }}</td>
                                                <td>{{ $account->email }}</td>
                                                <td>{{ $account->phone }}</td>
                                                <td>{{ $account->role }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary"
                                                            data-toggle="modal"
                                                            data-target="#modal-default"
                                                            data-id="{{ $account->id }}"
                                                            data-name="{{ $account->name }}"
                                                            data-email="{{ $account->email }}"
                                                            data-phone="{{ $account->phone }}"
                                                            data-role="{{ $account->role }}">
                                                        Edit
                                                    </button>
                                    
                                                    <form action="{{ url('/accounts/delete/' . $account->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"
                                                                onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này không?')">
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
                                        {{ $categories->links() }}
                                    </div>
                                </div>
                            </div>
                        </div> <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Category</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editAccountForm" action="" method="POST">
                            @csrf
                            <input type="hidden" name="_method" value="POST">
                        
                            <div class="form-group">
                                <label for="modalName">Name</label>
                                <input type="text" name="name" id="modalName" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="modalEmail">Email</label>
                                <input type="email" name="email" id="modalEmail" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="modalPhone">Phone</label>
                                <input type="text" name="phone" id="modalPhone" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="modalRole">Role</label>
                                <select name="role" id="modalRole" class="form-control">
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="modalPassword">Password</label>
                                <input type="password" name="password" id="modalPassword" class="form-control">
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

        {{-- Success alert --}}
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

        {{-- Error alert --}}
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

        {{-- Validation error --}}
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
@endsection

@section('scripts')
<script>
    $('#modal-default').on('show.bs.modal', function(event) {
    const button = $(event.relatedTarget);
    const modal = $(this);
    const id = button.data('id');

    if (id) {
        // Edit mode
        modal.find('#modalName').val(button.data('name'));
        modal.find('#modalEmail').val(button.data('email'));
        modal.find('#modalPhone').val(button.data('phone'));
        modal.find('#modalRole').val(button.data('role'));
        modal.find('#editAccountForm').attr('action', `/accounts/update/${id}`);
        modal.find('input[name="_method"]').val('PUT');
    } else {
        // Add mode
        modal.find('#editAccountForm')[0].reset();
        modal.find('#editAccountForm').attr('action', '/accounts/create');
        modal.find('input[name="_method"]').val('POST');
    }
});

</script>
@endsection
