@extends('welcome')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Categories</h3>

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
                                            <th>Category ID</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $category)
                                            <tr>
                                                <td>{{ $category->category_id }}</td>
                                                <td>{{ $category->name }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary"
                                                    data-toggle="modal"
                                                    data-target="#modal-default"
                                                    data-id="{{ $category->category_id }}"
                                                    data-name="{{ $category->name }}">
                                                    Edit
                                                    </button>
                                    
                                                    <form action="{{ url('/categories/delete/' . $category->category_id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này không?')">
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
                        <form id="editCategoryForm" action="" method="POST">
                            @csrf
                            <input type="hidden" name="_method" value="POST">

                            <div class="form-group">
                                <label for="modalCategoryName">Name</label>
                                <input type="text" name="name" id="modalCategoryName" class="form-control" required>
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
        modal.find('#modalCategoryName').val(button.data('name'));
        modal.find('#editCategoryForm').attr('action', `/categories/update/${id}`);
        modal.find('input[name="_method"]').val('PUT');
    } else {
        // Add mode
        modal.find('#editCategoryForm')[0].reset();
        modal.find('#editCategoryForm').attr('action', '/categories/create');
        modal.find('input[name="_method"]').val('POST');
    }
});

</script>
@endsection
